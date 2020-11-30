import time

from flask import Blueprint, render_template, redirect, url_for, flash, request
from flask_login import current_user, login_user, logout_user

from app import limiter
from app.models import Entry, Organisation, Permission
from app.utils.auth import needs_team, needs_admin

blueprint = Blueprint("portal", __name__, url_prefix="/portal")


@blueprint.route("")
def index():
    if current_user.hasPermission(Permission.TEAM):
        entry = current_user.entry
        return render_template("portal/index.jinja", entry=entry)
    elif current_user.hasPermission(Permission.ADMIN):
        entries = Entry.query.all()
        scouts = Entry.query.filter_by(organisation=Organisation.scouting).all()
        guides = Entry.query.filter_by(organisation=Organisation.guiding).all()
        return render_template(
            "portal/admin/index.jinja",
            total_entries=entries,
            scout_entries=scouts,
            guide_entries=guides,
        )
    else:
        return redirect(url_for("portal.login"))


@blueprint.route("/login")
def login():
    return render_template("portal/need-login.jinja")


@blueprint.route("login/<int:entry>/<string:code>")
@limiter.limit("5/minute")
@limiter.limit("20/hour")
def verify(entry, code):
    entry = Entry.query.filter_by(id=entry, verification_code=code).first()
    if entry:
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
        e.sendConfirmationEmail()
    else:
        time.sleep(1)  # crap attempt to avoid a timing attack

    flash(
        "If an entry with this email exists, the team portal link has been resent",
        "success",
    )
    return render_template("portal/resend-link.jinja")


# Team Portal Routes
@blueprint.route("/badges")
@needs_team
def orderBadges():
    pass


@blueprint.route("/badges/order/new", methods=["POST"])
@needs_team
def orderBadgesn():
    pass


# Admin Portal Routes
@blueprint.route("/entries")
@needs_admin
def listEntries():
    entries = Entry.query
    return render_template("portal/admin/entries.jinja", entries=entries)
