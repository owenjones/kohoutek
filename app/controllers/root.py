from flask import Blueprint, render_template, request, redirect, url_for

from app import db
from app.models import Entry, Organisation, County, District, Team

blueprint = Blueprint("root", __name__)


@blueprint.route("/")
def index():
    teams = (
        Team.query.filter(Team.submitted == True).order_by(Team.rawScore.desc()).all()
    )

    groups = (
        db.session.execute(
            "SELECT COUNT(DISTINCT `entry`.`id`) AS `total` FROM `entry` JOIN `team` ON `entry`.`id` = `team`.`entry_id` WHERE `team`.`submitted` = 1"
        )
        .first()
        .total
    )

    participants = (
        db.session.execute(
            "SELECT SUM(`team`.`members`) AS `total` FROM `team` WHERE `team`.`submitted` = 1"
        )
        .first()
        .total
    )

    trophy = db.session.execute(
        "SELECT * FROM `team` JOIN `entry` ON `entry`.`id` = `team`.`entry_id` WHERE `entry`.`county` != 'other' AND `team`.`submitted` = 1 ORDER BY `team`.`rawScore` DESC"
    ).fetchall()

    return render_template(
        "root/index.jinja",
        teams=teams,
        total_entered=groups,
        total_participants=participants,
        trophy=trophy,
    )


@blueprint.route("/sign-up", methods=["GET"])
def signupRedirect():
    # just in case - sometimes form ends up sending people here?
    return redirect(url_for("root.index") + "#sign-up")


@blueprint.route("/sign-up", methods=["POST"])
def processSignup():
    return ("Signups for Kohoutek 2021 are now closed", 422)

    if ("organisation" not in request.form) or (
        request.form["organisation"] not in ["scouting", "guiding"]
    ):
        return ("You haven't let us know whether you're in Guiding or Scouting", 422)

    organisation = Organisation(request.form["organisation"])

    if "county" not in request.form or request.form["county"] not in [
        "avon",
        "bsg",
        "sn",
        "other",
    ]:
        return ("You need to select which County you're in", 422)

    county = County(request.form["county"])

    if (
        county in [County.avon, County.bsg, County.sn]
        and ("district" not in request.form or request.form["district"] == "")
    ) or (
        county == County.other
        and (
            (
                organisation == Organisation.scouting
                and (
                    "district-name" not in request.form
                    or request.form["district-name"] == ""
                )
            )
            or (
                organisation == Organisation.guiding
                and (
                    "division-name" not in request.form
                    or request.form["division-name"] == ""
                )
            )
        )
    ):
        return ("You need to select your District/Division, or enter it's name", 422)

    if county in [County.avon, County.bsg, County.sn]:
        district = District.query.filter_by(id=request.form["district"]).first()
        if district.county != county:
            return (
                "You need to select a District/Division that's in your chosen County",
                422,
            )

        else:
            district_id = district.id
            district_name = None

    else:
        district_id = None
        district_name = (
            request.form["district-name"]
            if organisation == Organisation.scouting
            else request.form["division-name"]
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
        return ("This email address has already been used to sign up a group", 422)

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
            district_id=district_id,
            district_name=district_name,
            group_name=group_name,
            troop_name=troop_name,
        )

        db.session.add(e)
        db.session.commit()

        e.sendConfirmationEmail()

        return ("OK", 200)

    except Exception as e:
        return (f"There was a problem processing the sign up - {e}", 422)
