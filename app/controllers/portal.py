import time
import math

import stripe
from flask import (
    Blueprint,
    render_template,
    redirect,
    url_for,
    flash,
    request,
)
from flask_login import current_user, login_user, logout_user

from app import db, limiter
from app.models import Entry
from app.utils.auth import needs_team

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
        return redirect(request.args.get("next") or url_for("portal.index"))

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
