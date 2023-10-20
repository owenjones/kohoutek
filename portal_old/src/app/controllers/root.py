from flask import Blueprint, render_template, request, redirect, url_for

from app import db
from app.models import Entry, Organisation, County, District

blueprint = Blueprint("root", __name__)


@blueprint.route("/")
def index():
    return render_template(
        "root/index.html",
        avon=District.query.filter_by(county=County.avon).all(),
        bsg=District.query.filter_by(county=County.bsg).all(),
        sn=District.query.filter_by(county=County.sn).all(),
    )


@blueprint.route("/sign-up", methods=["GET"])
def signupRedirect():
    # just in case - sometimes form ends up sending people here?
    return redirect(url_for("root.index") + "#sign-up")


@blueprint.route("/sign-up", methods=["POST"])
def processSignup():
    if ("organisation" not in request.form) or (
        request.form["organisation"] not in ["scouting", "guiding"]
    ):
        return ("You haven't let us know whether you're in Guiding or Scouting", 422)

    organisation = Organisation(request.form["organisation"])

    if organisation == Organisation.guiding and (
        "county" not in request.form
        or request.form["county"]
        not in [
            "avon",
            "bsg",
            "sn",
        ]
    ):
        return ("You need to select which County you're in", 422)

    county = (
        County(request.form["county"])
        if organisation == Organisation.guiding
        else County.avon
    )

    if "district" not in request.form or request.form["district"] == "":
        return ("You need to select your District/Division, or enter it's name", 422)

    district = District.query.filter_by(id=request.form["district"]).first()
    if district.county != county:
        return (
            "You need to select a District/Division that's in your chosen County",
            422,
        )

    if organisation == Organisation.scouting and (
        "group-name" not in request.form or request.form["group-name"] == ""
    ):
        return ("You need to tell us the name of your Group", 422)

    if organisation == Organisation.guiding and (
        "unit-name" not in request.form or request.form["unit-name"] == ""
    ):
        return ("You need to tell us the name of your Unit", 422)

    group_name = (
        request.form["group-name"]
        if organisation == Organisation.scouting
        else request.form["unit-name"]
    )

    if "troop-name" not in request.form or request.form["troop-name"] == "":
        troop_name = None
    else:
        troop_name = request.form["troop-name"]

    if "contact-name" not in request.form or request.form["contact-name"] == "":
        return ("You need to provide a contact name", 422)

    if "contact-email" not in request.form or request.form["contact-email"] == "":
        return ("You need to provide a contact email address", 422)

    if (
        "confirm-email" not in request.form
        or request.form["confirm-email"] == ""
        or request.form["contact-email"].lower()
        != request.form["confirm-email"].lower()
    ):
        return ("You need to confirm your email address", 422)

    contact_email = request.form["contact-email"].lower()
    existing_signup = Entry.query.filter_by(contact_email=contact_email).first()

    if existing_signup:
        return (
            "This email address has already been used to sign up a group. If you need to add additional teams please login in to the portal using the link emailed to you.",
            422,
        )

    if "rules" not in request.form or request.form["rules"] != "accepted":
        return (
            "You need to confirm you have read and accept the competition rules",
            422,
        )

    try:
        e = Entry(
            contact_name=request.form["contact-name"],
            contact_email=contact_email,
            organisation=organisation,
            county=county,
            district_id=district.id,
            group_name=group_name,
            troop_name=troop_name,
        )

        db.session.add(e)
        db.session.commit()

        # e.sendConfirmationEmail() # not while testing!

        return ("OK", 200)

    except Exception as e:
        return (f"There was a problem processing the sign up - {e}", 422)
