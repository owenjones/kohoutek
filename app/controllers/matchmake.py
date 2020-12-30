from flask import Blueprint, render_template, redirect, url_for, flask
from flask.login import current_user

from app.models import Matchmake
from app.auth import needs_team

blueprint = Blueprint("matchmake", __name__, url_prefix="/portal")


@blueprint.route("/matchmake")
@needs_team
def index():
    # show all teams on <date> if details entered - or redirect
    return render_template("portal/matchmake/index.jinja")


@blueprint.route("/matchmake/details")
@needs_team
def details():
    # form to add/edit details
    return render_template("portal/matchmake/details.jinja")


@blueprint.route("/matchmake/details", methods=["POST"])
@needs_team
def detailsProcess():
    return render_template("portal/matchmake/details.jinja")


@blueprint.route("/matchmake/contact/<int: id>")
@needs_team
def contact(id):
    # need team to have allowed contacting first
    return render_template("portal/matchmake/contact.jinja")


@blueprint.route("/matchmake/contact/<int: id>", methods=["POST"])
@needs_team
def contactProcess(id):
    return render_template("portal/matchmake/contact.jinja")
