from flask import Blueprint, flash, render_template, redirect, request, url_for
from flask_login import current_user

from app import db
from app.models import Activity, EntryActivities, Score, Section, Team
from app.utils.auth import needs_team
from app.utils.form import form_input_array

blueprint = Blueprint("scoring", __name__, url_prefix="/portal/scores")


@blueprint.route("", methods=["GET"])
@needs_team
def index():
    entry = current_user.entry
    match = current_user.entry.match or False
    teams = current_user.entry.teams or False
    toSubmit = current_user.entry.teams.filter(Team.submitted == False).count()
    activities = Activity.query.order_by("name")

    return render_template(
        "portal/scores/index.jinja",
        sections=Section,
        entry=entry,
        matchPrompt=(not match),
        teams=teams,
        toSubmit=toSubmit,
        activities=activities,
    )


@blueprint.route("/create", methods=["POST"])
@needs_team
def create():
    teams = request.form.get("teams", False)
    if not teams:
        flash("You haven't entered how many teams you have", "warning")
        return redirect(url_for("scoring.index"))

    try:
        teams = int(teams)

    except ValueError:
        flash("That's not a valid number of teams", "warning")
        return redirect(url_for("scoring.index"))

    if teams < 0:
        flash("You can't have a negative number of teams", "warning")
        return redirect(url_for("scoring.index"))

    activities = form_input_array(request.form, "activity")

    if len(activities) != 4:
        flash("Please select four activities", "warning")
        return redirect(url_for("scoring.index"))

    for activity in activities:
        obj = Activity.query.get(activity[1])
        if obj:
            current_user.entry.activities.append(EntryActivities(activity=obj))
        else:
            flash("Please select a valid activity", "warning")
            return redirect(url_for("scoring.index"))

    for team in range(1, teams + 1):
        current_user.entry.teams.append(Team())

    db.session.commit()

    return redirect(url_for("scoring.index"))


@blueprint.route("/update", methods=["POST"])
@needs_team
def update():
    add = request.form.get("add", False)
    delete = request.form.get("delete", False)
    save = request.form.get("save", False)
    submit = request.form.get("submit", False)

    if delete:
        team = Team.query.get(delete)

        if team and team.entry == current_user.entry:
            if current_user.entry.teams.count() == 1:
                flash("You need to have at least one team", "warning")

            else:
                db.session.delete(team)
                flash("Team deleted", "success")

        else:
            flash("This isn't your team to delete", "danger")

    for team in current_user.entry.teams:
        if team.submitted == False:
            fields = dict(form_input_array(request.form, f"team-{team.id}"))
            team.name = fields.get("name", False)
            team.section = Section(fields.get("section", False))

            try:
                members = int(fields.get("number", 0))

            except ValueError:
                members = 0

            team.members = members

            for i, entryActivity in enumerate(current_user.entry.activities):
                team.scores[i].activity_id = entryActivity.activity.id
                team.scores[i].score = fields.get(f"activity-{i+1}", 0)

    if save:
        flash("Your scores have been saved", "success")

    if add:
        current_user.entry.teams.append(Team())
        flash("Additional team added", "success")

    if submit:
        # TODO: See if this can be less intensive?
        for team in current_user.entry.teams:
            if team.submitted == False:
                validNumbers = team.members > 0
                validSection = team.section in Section
                validScores = (
                    Score.query.filter_by(team_id=team.id)
                    .filter((Score.score <= 0) | (Score.score > 50))
                    .count()
                ) == 0

                if not validNumbers:
                    flash(
                        "There was a problem submitting your scores: the number of members in a team cannot be negative",
                        "warning",
                    )
                    break

                if not validSection:
                    flash(
                        "There was a problem submitting your scores: one of your teams has an invalid section",
                        "warning",
                    )
                    break

                if not validScores:
                    flash(
                        "There was a problem submitting your scores: activity scores must be between 0 and 50",
                        "warning",
                    )
                    break

                team.submitted = True

    db.session.commit()
    return redirect(url_for("scoring.index"))


@blueprint.route("/change-activties", methods=["GET"])
@needs_team
def changeActivities():
    pass


@blueprint.route("/change-activities", methods=["POST"])
def changeActivitiesProcess():
    pass
