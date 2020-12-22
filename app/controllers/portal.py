import time

from flask import Blueprint, render_template, redirect, url_for, flash, request, jsonify
from flask_login import current_user, login_user, logout_user

from app import limiter
from app.models import Entry, Item, Order, ItemStock
from app.utils.auth import needs_team, needs_admin

blueprint = Blueprint("portal", __name__, url_prefix="/portal")


@blueprint.route("")
@needs_team
def index():
    entry = current_user.entry
    return render_template("portal/index.jinja", entry=entry)


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


# Badge Order Routes
@blueprint.route("/badges")
@needs_team
def listOrders():
    orders = current_user.entry.orders
    return render_template("portal/orders/index.jinja", orders=orders)


@blueprint.route("/badges/order")
@needs_team
def placeOrder():
    items = Item.query.all()
    return render_template("portal/orders/new.jinja", items=items)


@blueprint.route("/badges/order", methods=["POST"])
@needs_team
def processOrder():
    return "Process"


@blueprint.route("/badges/order/<int:id>")
@needs_team
def viewOrder(id):
    pass


@blueprint.route("/badges/order/<int:id>/postage")
@needs_team
def addPostageToOrder(id):
    return "Add Postage"


@blueprint.route("/badges/order/<int:id>/postage", methods=["POST"])
@needs_team
def processPostage(id):
    return "Process"


@blueprint.route("/badges/order/<int:id>/payment")
@needs_team
def addPaymentToOrder(id):
    return "Add Payment"


@blueprint.route("/badges/order/<int:id>/payment", methods=["POST"])
@needs_team
def processPayment(id):
    return "Process"
