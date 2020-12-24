import time
import math

import stripe
from flask import (
    current_app,
    Blueprint,
    render_template,
    redirect,
    url_for,
    flash,
    request,
    jsonify,
)
from flask_login import current_user, login_user, logout_user

from app import db, limiter
from app.models import (
    Entry,
    Item,
    ItemStock,
    Order,
    OrderStatus,
    Postage,
    Payment,
    PaymentMethod,
    PaymentStatus,
)
from app.utils.auth import needs_team, needs_admin
from app.utils.form import form_input_array

blueprint = Blueprint("portal", __name__, url_prefix="/portal")


@blueprint.route("")
@needs_team
def index():
    entry = current_user.entry
    orders = entry.orders
    return render_template("portal/index.jinja", entry=entry, orders=orders)


@blueprint.route("/login")
def login():
    return render_template("portal/need-login.jinja")


@blueprint.route("login/<int:entry>/<string:code>")
@limiter.limit("5/minute")
@limiter.limit("20/hour")
def verify(entry, code):
    entry = Entry.query.filter_by(id=entry, verification_code=code).first()
    if entry:
        if request.args.get("noverify") == None:
            verify = entry.verify(code)
            if verify:
                flash(
                    "Thank you for verifying your email, your Kohoutek 2021 entry is now confirmed!",
                    "success",
                )

        login_user(entry.user)
        return redirect(url_for("portal.index"))

    else:
        return redirect(url_for("portal.login"))


@blueprint.route("/logout")
def logout():
    logout_user()
    return redirect(url_for("root.index"))


@blueprint.route("/resend-link", methods=["GET"])
def resendLink():
    return render_template("portal/resend-link.jinja")


@blueprint.route("/resend-link", methods=["POST"])
def resendLinkProcess():
    e = Entry.query.filter_by(contact_email=request.form.get("email")).first()
    if e:
        # TODO: add exception handling around this!
        e.sendConfirmationEmail()
    else:
        time.sleep(1)  # crap attempt to avoid a timing attack

    flash(
        "If an entry with this email exists, the team portal link has been resent",
        "success",
    )
    return render_template("portal/resend-link.jinja")


@blueprint.route("/badges")
@needs_team
def placeOrder():
    items = Item.query.all()
    return render_template("portal/orders/new_order.jinja", items=items)


@blueprint.route("/badges", methods=["POST"])
@needs_team
def processOrder():
    items = form_input_array(request.form, "item")
    count = sum([int(v) for k, v in items if v != ""])

    if count > 0:
        order = Order(entry_id=current_user.entry.id)
        for id, quantity in items:
            if quantity != "":
                try:
                    quantity = int(quantity)
                    added = order.addItem(id, quantity)

                    if not added:
                        flash(
                            "There was a problem adding one of your items, please try again",
                            "warning",
                        )
                        return redirect(url_for("portal.placeOrder"))

                    elif added == ItemStock("out_of_stock"):
                        flash(
                            "One of the items you have tried to order doesn't have enough stock, please check your order and try again",
                            "warning",
                        )
                        return redirect(url_for("portal.placeOrder"))

                except ValueError:
                    flash(
                        "There was a problem adding one of your items, please try again",
                        "warning",
                    )
                    return redirect(url_for("portal.placeOrder"))

        order.save()
        return redirect(url_for("portal.addPostageToOrder", id=order.id))

    else:
        flash(
            "You haven't chosen any items, please add some and try again",
            "warning",
        )

        return redirect(url_for("portal.placeOrder"))


@blueprint.route("/order/<int:id>/items")
@needs_team
def updateItems(id):
    order = Order.query.get_or_404(id)
    if order.entry != current_user.entry:
        abort(403)

    if order.status != OrderStatus.incomplete:
        return redirect(url_for("portal.viewOrder", id=order.id))

    items = Item.query.all()

    return render_template("portal/orders/update_items.jinja", order=order, items=items)


@blueprint.route("/order/<int:id>/postage")
@needs_team
def addPostageToOrder(id):
    order = Order.query.get_or_404(id)
    if order.entry != current_user.entry:
        abort(403)

    if order.status != OrderStatus.incomplete:
        return redirect(url_for("portal.viewOrder", id=order.id))

    options = (
        Postage.query.filter(
            (Postage.item_min == 0) | (order.quantity >= Postage.item_min)
        )
        .filter((Postage.item_max == 0) | (order.quantity <= Postage.item_max))
        .all()
    )

    return render_template(
        "portal/orders/add_postage.jinja", order=order, options=options
    )


@blueprint.route("/order/<int:id>/postage", methods=["POST"])
@needs_team
def processPostage(id):
    order = Order.query.get_or_404(request.form.get("id"))
    if order.entry != current_user.entry:
        abort(403)

    if order.statusIs("incomplete"):
        added = order.addPostage(request.form.get("option"))

        # do some validation here

        order.postage_name = request.form.get("name")
        order.postage_address_1 = request.form.get("address_1")
        order.postage_address_2 = request.form.get("address_2")
        order.postage_city = request.form.get("city")
        order.postage_postcode = request.form.get("postcode")
        order.postage_country = request.form.get("country")
        order.save()

        return redirect(url_for("portal.addPaymentToOrder", id=order.id))


@blueprint.route("/order/<int:id>/payment")
@needs_team
def addPaymentToOrder(id):
    order = Order.query.get_or_404(id)
    if order.entry != current_user.entry:
        abort(403)

    if order.status != OrderStatus.incomplete:
        return redirect(url_for("portal.viewOrder", id=order.id))

    if order.postage:
        return render_template("portal/orders/add_payment.jinja", order=order)

    else:
        return redirect(url_for("portal.addPostageToOrder", id=order.id))


@blueprint.route("/order/<int:id>/payment", methods=["POST"])
@needs_team
def processPayment(id):
    order = Order.query.get_or_404(request.form.get("id"))
    if order.entry != current_user.entry:
        abort(403)

    if order.status != OrderStatus.incomplete:
        return redirect(url_for("portal.viewOrder", id=order.id))

    payment = order.payment if order.payment else Payment(order=order)
    method = request.form.get("method")

    if method == "online":
        payment.method = PaymentMethod.stripe
        payment.fee = math.ceil(((order.subtotal * 0.014) + 0.22) * 100) / 100

    elif method == "bank":
        payment.method = PaymentMethod.BACS
        payment.fee = 0

    elif method == "cheque":
        payment.method = PaymentMethod.cheque
        payment.fee = 0

    else:
        flash("Invalid payment method selected", "danger")
        return redirect(url_for("portal.addPaymentToOrder", id=order.id))

    db.session.add(payment)
    db.session.commit()

    return redirect(url_for("portal.completePayment", id=order.id))


@blueprint.route("/order/<int:id>/payment/complete")
@needs_team
def completePayment(id):
    order = Order.query.get_or_404(id)
    if order.entry != current_user.entry:
        abort(403)

    if order.status != OrderStatus.incomplete:
        return redirect(url_for("portal.viewOrder", id=order.id))

    if order.payment.method == PaymentMethod.stripe:
        return render_template(
            "portal/orders/payment_methods/stripe.jinja",
            order=order,
            stripe_key=current_app.config["STRIPE"]["publishable_key"],
        )

    elif order.payment.method == PaymentMethod.BACS:
        return render_template(
            "portal/orders/payment_methods/bank_transfer.jinja", order=order
        )

    elif order.payment.method == PaymentMethod.cheque:
        return render_template(
            "portal/orders/payment_methods/cheque.jinja", order=order
        )

    else:
        flash("Invalid payment method selected", "danger")
        return redirect(url_for("portal.addPaymentToOrder", id=order.id))


@blueprint.route("/order/<int:id>/payment/generate", methods=["POST"])
@needs_team
def stripeGenerateCheckout(id):
    order = Order.query.get_or_404(id)
    if order.entry != current_user.entry:
        abort(403)

    if order.status != OrderStatus.incomplete:
        return jsonify([])  # format error message in this

    if order.payment.method == PaymentMethod.stripe:
        stripe.api_key = current_app.config["STRIPE"]["secret_key"]

        items = [
            {
                "price_data": {
                    "currency": "gbp",
                    "product_data": {
                        "name": item.item.name,
                        "description": item.item.description,
                        "images": [item.item.image_url],
                    },
                    "unit_amount": int(item.item.unit_cost * 100),
                },
                "quantity": item.quantity,
            }
            for item in order.items
        ]

        items.append(
            {
                "price_data": {
                    "currency": "gbp",
                    "product_data": {"name": order.postage.name},
                    "unit_amount": int(order.postage.cost * 100),
                },
                "quantity": 1,
            }
        )

        items.append(
            {
                "price_data": {
                    "currency": "gbp",
                    "product_data": {"name": "Online Payment Fee"},
                    "unit_amount": int(order.payment.fee * 100),
                },
                "quantity": 1,
            }
        )

        session = stripe.checkout.Session.create(
            payment_method_types=["card"],
            line_items=items,
            mode="payment",
            success_url=url_for(
                "portal.stripePaymentSuccess", id=order.id, _external=True
            ),
            cancel_url=url_for("portal.completePayment", id=order.id, _external=True),
        )

        order.payment.reference = session.id
        order.payment.amount = float(session.amount_total) / 100
        order.save()

        return jsonify(id=session.id)

    else:
        return jsonify([])


@blueprint.route("/order/<int:id>/payment/success")
@needs_team
def stripePaymentSuccess(id):
    order = Order.query.get_or_404(id)
    if order.entry != current_user.entry:
        abort(403)

    if order.status != OrderStatus.incomplete:
        return redirect(url_for("portal.viewOrder", id=order.id))

    if order.payment.method == PaymentMethod.stripe:
        stripe.api_key = current_app.config["STRIPE"]["secret_key"]
        session = stripe.checkout.Session.retrieve(order.payment.reference)

        if session.payment_status == "paid":
            order.payment.received_at = db.func.now()
            order.payment.status = PaymentStatus.received
            order.status = OrderStatus.complete
            order.save()

            flash("Online payment successfully received", "success")
            return redirect(url_for("portal.viewOrder", id=order.id))

        else:
            # We should never get to here - Stripe handles issues when charging the card
            flash("There was a problem with your payment, please try again", "warning")
            return redirect(url_for("portal.completePayment", id=order.id))


@blueprint.route("/order/<int:id>/payment/complete", methods=["POST"])
@needs_team
def recordPayment(id):
    order = Order.query.get_or_404(request.form.get("id"))
    if order.entry != current_user.entry:
        abort(403)

    if order.status != OrderStatus.incomplete:
        return redirect(url_for("portal.viewOrder", id=order.id))

    if order.payment.method in [PaymentMethod.BACS, PaymentMethod.cheque]:
        order.payment.status = PaymentStatus.pending
        order.save()
        flash(
            "Thank you for arranging payment for your badge order, once we receive the payment your order will be updated.",
            "success",
        )

    return redirect(url_for("portal.viewOrder", id=order.id))


@blueprint.route("/order/<int:id>/cancel")
@needs_team
def cancelOrder(id):
    order = Order.query.get_or_404(id)
    if order.entry != current_user.entry:
        abort(403)

    if not (order.statusIs("complete") or order.statusIs("dispatched")):
        db.session.delete(order)
        db.session.commit()

        flash(f"Order #{ order.id } has been cancelled", "success")
        return redirect(url_for("portal.index"))

    else:
        flash(
            "You can't cancel this order online as we've already received payment for it, please get in touch with us at contact@kohoutek.co.uk if you wish to cancel",
            "warning",
        )
        return redirect(url_for("portal.index"))


@blueprint.route("/order/<int:id>")
@needs_team
def viewOrder(id):
    order = Order.query.get_or_404(id)
    if order.entry != current_user.entry:
        abort(403)

    return render_template("portal/orders/view_order.jinja", order=order)


@blueprint.route("/order/<int:id>/invoice")
@needs_team
def viewInvoice(id):
    order = Order.query.get_or_404(id)
    if order.entry != current_user.entry:
        abort(403)

    if order.status == OrderStatus.incomplete:
        return redirect(url_for("portal.viewOrder", id=order.id))

    return render_template("portal/orders/invoice.jinja", order=order)
