import time

from flask import (
    Blueprint,
    render_template,
    redirect,
    url_for,
    flash,
    request,
    make_response,
)
from flask_login import current_user, login_user, logout_user

from app.models import Entry, Permission
from app.utils.auth import needs_team

blueprint = Blueprint("portal", __name__, url_prefix="/portal")


@blueprint.route("")
def index():
    if current_user.is_authenticated and current_user.hasPermission(Permission.TEAM):
        entry = current_user.entry
        orders = entry.orders
        return render_template("portal/index.html", entry=entry, orders=orders)

    else:
        response = make_response(render_template("portal/need-login.html"))
        response.headers["Cache-Control"] = "no-cache, no-store, must-revalidate"
        response.headers["Pragma"] = "no-cache"
        return response


@blueprint.route("/login")
def login():
    return redirect(url_for("portal.index"))


@blueprint.route("login/<int:entry>/<string:code>")
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
        return redirect(url_for("portal.index"))


@blueprint.route("/logout")
def logout():
    logout_user()
    return redirect(url_for("root.index"))


@blueprint.route("/resend-link", methods=["GET"])
def resendLink():
    return render_template("portal/resend-link.html")


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
    return render_template("portal/resend-link.html")


@blueprint.route("/activities")
@needs_team
def downloadActivities():
    match = current_user.entry.match or False
    return render_template("portal/download-activities.html", matchPrompt=(not match))
