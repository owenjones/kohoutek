import time

from flask import Blueprint, render_template, redirect, url_for, flash, request
from flask_login import current_user, login_user, logout_user

from app import limiter
from app.models import Entry, Item
from app.utils.auth import needs_team, needs_admin

blueprint = Blueprint("portal", __name__, url_prefix="/portal")

# Auth Flow
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


# Portal Routes
@blueprint.route("")
@needs_team
def index():
    entry = current_user.entry
    return render_template("portal/index.jinja", entry=entry)


@blueprint.route("/badges")
@needs_team
def listOrders():
    orders = current_user.orders
    items = Item.query.all()
    return render_template("portal/orders/index.jinja", orders=orders)


@blueprint.route("/badges/order/new", methods=["POST"])
@needs_team
def processOrder():
    pass
