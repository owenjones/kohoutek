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
from app.models import Matchmake, Message
from app.utils.auth import needs_team
from app.utils.mail import sendmail

blueprint = Blueprint("matchmake", __name__, url_prefix="/portal")


@blueprint.route("/match")
@needs_team
def index():
    if not current_user.entry.match:
        flash("You need to add your details before you can matchmake", "warning")
        return redirect(url_for("matchmake.details"))

    # TODO: sort matches by distance from current entry
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

    try:
        date = datetime.strptime(request.form.get("date"), "%Y-%m-%d")

    except ValueError:
        flash(
            "Please enter your date in the correct format (year-month-day)", "warning"
        )
        return redirect(url_for("matchmake.details"))

    contact = "contact" in request.form and request.form.get("contact") == "on"
    number = (
        0
        if ("number" not in request.form or request.form.get("number") == "")
        else request.form.get("number")
    )

    match = current_user.entry.match or Matchmake(entry=current_user.entry)
    match.date = date
    match.number = number
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
    if current_user.entry.match:
        db.session.delete(current_user.entry.match)
        db.session.commit()
        flash("Your matchmaking details have been removed", "success")

    return redirect(url_for("portal.index"))


@blueprint.route("/match/contact/<int:id>")
@needs_team
def contact(id):
    match = Matchmake.query.get_or_404(id)
    if not match.contact:
        flash(
            "This entry has requested not to be contacted through the team portal",
            "warning",
        )
        return redirect(url_for("matchmake.index"))

    if match == current_user.entry.match:
        flash(
            "You can't send yourself a message!",
            "warning",
        )
        return redirect(url_for("matchmake.index"))

    return render_template("portal/matchmake/contact.jinja", match=match)


@blueprint.route("/match/contact/<int:id>", methods=["POST"])
@needs_team
def contactProcess(id):
    match = Matchmake.query.get(request.form.get("id"))
    if not match.contact:
        flash(
            "This entry has requested not to be contacted through the team portal",
            "warning",
        )
        return redirect(url_for("matchmake.index"))

    if "message" not in request.form or request.form.get("message") == "":
        flash(
            "You haven't entered a message to send",
            "warning",
        )
        return redirect(url_for("matchmake.contact"), id=match.id)

    message = request.form.get("message")

    save = Message(
        sent_from_id=current_user.entry.id, sent_to_id=match.entry.id, message=message
    )
    db.session.add(save)
    db.session.commit()

    send = sendmail(
        match.entry.contact_email,
        "Kohoutek Matchmaker",
        "matchmake-contact",
        entry_name=current_user.entry.name,
        message=message.split("\n"),
        reply_link=match.entry.portal_link(
            "matchmake.contact", id=current_user.entry.id
        ),
    )

    if send.status_code == 200:
        flash(f"Your message has been sent to { match.entry.name }", "success")

    else:
        flash(
            f"There was an error sending your message to { match.entry.name }",
            "warning",
        )

    return redirect(url_for("matchmake.index"))
