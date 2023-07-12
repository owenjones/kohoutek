from datetime import datetime

from flask import (
    Blueprint,
    current_app,
    render_template,
    redirect,
    url_for,
    flash,
    request,
    abort,
)
from flask_login import current_user, login_user, logout_user

from app import limiter, db
from app.models import (
    Entry,
    Organisation,
    District,
    Order,
    OrderStatus,
    Team,
    Matchmake,
)
from app.utils.auth import needs_admin

blueprint = Blueprint("admin", __name__, url_prefix="/admin")


@blueprint.route("")
@needs_admin
def index():
    districts = District.query.order_by(District.county, District.name).all()
    entries = Entry.query.all()
    scouts = Entry.query.filter_by(organisation=Organisation.scouting).all()
    guides = Entry.query.filter_by(organisation=Organisation.guiding).all()
    return render_template(
        "admin/index.html",
        districts=districts,
        total_entries=entries,
        scout_entries=scouts,
        guide_entries=guides,
    )


# Entry Routes
@blueprint.route("/entries", defaults={"status": False})
@blueprint.route("/entries/<string:status>")
@needs_admin
def listEntries(status):
    if status in ["verified", "unverified"]:
        entries = Entry.query.filter(Entry.verified == (status == "verified"))

    else:
        entries = Entry.query

    return render_template("admin/entries.html", entries=entries)


@blueprint.route("/entries/map")
@needs_admin
def mapEntries():
    matches = Matchmake.query.all()

    center = f"[{ current_app.config['MAP']['default_lon']}, { current_app.config['MAP']['default_lat']}]"

    return render_template(
        "admin/entries_map.html",
        matches=matches,
        center=center,
        mapbox_key=current_app.config["MAP"]["mapbox_key"],
    )


@blueprint.route("/entry/<int:id>")
@needs_admin
def entry(id):
    entry = Entry.query.get_or_404(id)
    return render_template("admin/entry/view.html", entry=entry)


@blueprint.route("/entry/<int:id>/contact", methods=["GET"])
@needs_admin
def contactEntry(id):
    entry = Entry.query.get_or_404(id)
    return render_template("admin/entry/contact.html", entry=entry)


@blueprint.route("/entry/<int:id>/contact", methods=["POST"])
@needs_admin
def contactEntryProcess(id):
    flash("That doesn't work yet, sorry...", "warning")
    return redirect(url_for("admin.entry", id=id))


@blueprint.route("/entry/<int:id>/cancel", methods=["GET"])
@needs_admin
def cancelEntry(id):
    entry = Entry.query.get_or_404(id)
    return render_template("admin/entry/cancel.html", entry=entry)


@blueprint.route("/entry/<int:id>/cancel", methods=["POST"])
@needs_admin
def cancelEntryProcess(id):
    entry = Entry.query.get_or_404(id)
    if request.form.get("code").lower() == entry.code.lower():
        silent = request.form.get("silent") == "true"
        error = entry.cancel(silent=silent)

        if error:
            flash(f"Something went wrong cancelling the entry - { error }", "danger")
            return redirect(url_for("admin.cancelEntry", id=id))
        else:
            flash("Entry has been cancelled", "success")
            return redirect(url_for("admin.listEntries"))

    else:
        flash(
            "Incorrect entry code (are you sure you know what you're doing?).",
            "warning",
        )

        return redirect(url_for("admin.cancelEntry", id=id))


@blueprint.route("/entry/<int:id>/resend-link", methods=["GET"])
@needs_admin
def resendLink(id):
    entry = Entry.query.get_or_404(id)
    return render_template("admin/entry/resend-link.html", entry=entry)


@blueprint.route("/entry/<int:id>/resend-link", methods=["POST"])
@needs_admin
def resendLinkProcess(id):
    entry = Entry.query.get_or_404(id)

    r = entry.sendConfirmationEmail()
    if r.status_code == 200:
        flash("Team portal link resent", "success")
    else:
        flash(f"Something went wrong resending the link - { r.text }", "danger")

    return redirect(url_for("admin.entry", id=id))


@blueprint.route("/entry/<int:id>/entries")
@needs_admin
def listEntryOrders(id):
    entry = Entry.query.get_or_404(id)
    return render_template("admin/entry/orders.html", entry=entry)


# Order Routes
@blueprint.route("/orders", defaults={"status": False})
@blueprint.route("/orders/<string:status>")
@needs_admin
def listOrders(status):
    if status:
        try:
            status = OrderStatus(status)
            orders = Order.query.filter(Order.status == status)

        except ValueError:
            abort(404)

    else:
        orders = Order.query

    return render_template("admin/orders.html", orders=orders)


@blueprint.route("/order/<int:id>")
@needs_admin
def order(id):
    order = Order.query.get_or_404(id)
    return render_template("admin/order/view.html", order=order)


@blueprint.route("/order/<int:id>/payment")
@needs_admin
def recordPayment(id):
    order = Order.query.get_or_404(id)
    return render_template("admin/order/record_payment.html", order=order)


@blueprint.route("/order/<int:id>/payment", methods=["POST"])
@needs_admin
def recordPaymentProcess(id):
    order = Order.query.get_or_404(request.form.get("id"))

    order.payment.method = request.form.get("method")
    order.payment.reference = request.form.get("reference")

    status = request.form.get("status")
    order.payment.status = status

    if status == "received":
        order.status = "complete"
        order.payment.markReceived()

    elif status in ["new", "error"]:
        order.status = "incomplete"

    elif status == "pending":
        order.status = "payment_pending"

    amount = request.form.get("amount")
    order.payment.amount = float(amount) if amount != "" else 0

    date = request.form.get("received_date")
    date = date if date != "" else "2000-01-01"
    order.payment.received_at = datetime.strptime(date, "%Y-%m-%d")

    order.save()

    flash("Payment data saved", "success")
    return render_template("admin/order/record_payment.html", order=order)


@blueprint.route("/order/<int:id>/dispatch")
@needs_admin
def dispatchOrder(id):
    order = Order.query.get_or_404(id)
    return render_template("admin/order/dispatch.html", order=order)


@blueprint.route("/order/<int:id>/dispatch/info")
@needs_admin
def dispatchOrderInfo(id):
    order = Order.query.get_or_404(id)
    return render_template("admin/order/dispatch.html", order=order)


@blueprint.route("/order/<int:id>/dispatch", methods=["POST"])
@needs_admin
def dispatchOrderProcess(id):
    order = Order.query.get_or_404(request.form.get("id"))

    order.status = "dispatched"
    order.markDispatched()
    order.save()

    flash("Marked as dispatched", "success")
    return render_template("admin/order/dispatch.html", order=order)


@blueprint.route("/order/<int:id>/cancel")
@needs_admin
def cancelOrder(id):
    order = Order.query.get_or_404(id)
    return render_template("admin/order/cancel.html", order=order)


@blueprint.route("/order/<int:id>/cancel", methods=["POST"])
@needs_admin
def cancelOrderProcess(id):
    order = Order.query.get_or_404(request.form.get("id"))
    if request.form.get("code").lower() == order.code.lower():
        db.session.delete(order)
        db.session.commit()

        flash("Order has been cancelled", "success")
        return redirect(url_for("admin.listOrders"))

    else:
        flash(
            "Incorrect order code (are you sure you know what you're doing?).",
            "warning",
        )

        return redirect(url_for("admin.cancelOrder", id=id))


# Score Routes
@blueprint.route("/scores")
@needs_admin
def scores():
    teams = (
        Team.query.filter(Team.submitted == True).order_by(Team.rawScore.desc()).all()
    )
    return render_template("admin/scores.html", teams=teams)
