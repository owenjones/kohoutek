import time

from flask import Blueprint, render_template, redirect, url_for, flash, request
from flask_login import current_user, login_user, logout_user

from app import limiter
from app.models import Entry, Organisation
from app.utils.auth import needs_admin

blueprint = Blueprint("admin", __name__, url_prefix="/admin")


@blueprint.route("")
@needs_admin
def index():
    entries = Entry.query.all()
    scouts = Entry.query.filter_by(organisation=Organisation.scouting).all()
    guides = Entry.query.filter_by(organisation=Organisation.guiding).all()
    return render_template(
        "admin/index.jinja",
        total_entries=entries,
        scout_entries=scouts,
        guide_entries=guides,
    )


@blueprint.route("/entries")
@needs_admin
def listEntries():
    entries = Entry.query
    return render_template("admin/entries.jinja", entries=entries)


# @blueprint.route("/entry/<int: id>")
# @needs_admin
# def displayEntry(id):
#     entry = Entry.query.get(id)
#     return render_template("admin/entry.jinja", entry=entry)
