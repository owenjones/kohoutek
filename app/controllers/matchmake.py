from datetime import datetime

from flask import (
    abort,
    current_app,
    Blueprint,
    flash,
    render_template,
    redirect,
    request,
    url_for,
)
from flask_login import current_user

from app import db
from app.models import Matchmake
from app.utils.auth import needs_team

blueprint = Blueprint("matchmake", __name__, url_prefix="/portal")


@blueprint.route("/match")
@needs_team
def index():
    # show all teams on <date> if details entered - or redirect
    match = current_user.entry.match
    matches = (
        Matchmake.query.filter(Matchmake.date == match.date)
        .filter(Matchmake.id != match.id)
        .all()
    )

    return render_template(
        "portal/matchmake/index.jinja",
        mapbox_key=current_app.config["MAP"]["mapbox_key"],
        match=match,
        matches=matches,
    )


@blueprint.route("/match/details")
@needs_team
def details():
    match = current_user.entry.match

    center = (
        match.lngLat
        if match
        else f"[{ current_app.config['MAP']['default_lon']}, { current_app.config['MAP']['default_lat']}]"
    )

    return render_template(
        "portal/matchmake/details.jinja",
        mapbox_key=current_app.config["MAP"]["mapbox_key"],
        center=center,
        match=match,
    )


@blueprint.route("/match/details", methods=["POST"])
@needs_team
def detailsProcess():
    if "date" not in request.form or request.form.get("date") == "":
        flash("You haven't said when you'll be taking part", "warning")
        return redirect(url_for("matchmake.details"))

    if (
        "longitude" not in request.form
        or "latitude" not in request.form
        or request.form.get("longitude") == ""
        or request.form.get("latitiude") == ""
    ):
        flash("You haven't added a location to the map", "warning")
        return redirect(url_for("matchmake.details"))

    date = datetime.strptime(request.form.get("date"), "%Y-%m-%d")
    contact = "contact" in request.form and request.form.get("contact") == "on"

    match = current_user.entry.match or Matchmake(entry=current_user.entry)
    match.date = date
    match.contact = contact
    match.longitude = request.form.get("longitude")
    match.latitude = request.form.get("latitude")

    db.session.add(match)
    db.session.commit()

    flash("Your matchmaking details have been saved", "success")
    return redirect(url_for("matchmake.index"))


@blueprint.route("/match/details/remove", methods=["POST"])
@needs_team
def detailsRemove():
    pass


@blueprint.route("/match/contact/<int:id>")
@needs_team
def contact(id):
    # need team to have allowed contacting first
    return render_template("portal/matchmake/contact.jinja")


@blueprint.route("/match/contact/<int:id>", methods=["POST"])
@needs_team
def contactProcess(id):
    return render_template("portal/matchmake/contact.jinja")
