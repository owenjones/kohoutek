from flask import (
    Blueprint,
    render_template,
    redirect,
    url_for,
    flash,
    request,
)

from app.models import (
    Entry,
    Organisation,
    District,
)
from app.utils.auth import needs_admin

blueprint = Blueprint("admin", __name__, url_prefix="/admin")


@blueprint.route("")
@needs_admin
def index():
    districts = District.query.order_by(District.county, District.name).all()
    entries = Entry.query.all()
    scouts = Entry.query.filter_by(organisation=Organisation.scouting).all()
    guides = Entry.query.filter_by(organisation=Organisation.guiding).all()
    return render_template(
        "admin/index.html",
        districts=districts,
        total_entries=entries,
        scout_entries=scouts,
        guide_entries=guides,
    )


# Entry Routes
@blueprint.route("/entries", defaults={"status": False})
@blueprint.route("/entries/<string:status>")
@needs_admin
def listEntries(status):
    if status in ["verified", "unverified"]:
        entries = Entry.query.filter(Entry.verified == (status == "verified"))

    else:
        entries = Entry.query

    return render_template("admin/entries.html", entries=entries)


@blueprint.route("/entry/<int:id>")
@needs_admin
def entry(id):
    entry = Entry.query.get_or_404(id)
    return render_template("admin/entry/view.html", entry=entry)


@blueprint.route("/entry/<int:id>/contact", methods=["GET"])
@needs_admin
def contactEntry(id):
    entry = Entry.query.get_or_404(id)
    return render_template("admin/entry/contact.html", entry=entry)


@blueprint.route("/entry/<int:id>/contact", methods=["POST"])
@needs_admin
def contactEntryProcess(id):
    flash("That doesn't work yet, sorry...", "warning")
    return redirect(url_for("admin.entry", id=id))


@blueprint.route("/entry/<int:id>/cancel", methods=["GET"])
@needs_admin
def cancelEntry(id):
    entry = Entry.query.get_or_404(id)
    return render_template("admin/entry/cancel.html", entry=entry)


@blueprint.route("/entry/<int:id>/cancel", methods=["POST"])
@needs_admin
def cancelEntryProcess(id):
    entry = Entry.query.get_or_404(id)
    if request.form.get("code").lower() == entry.code.lower():
        silent = request.form.get("silent") == "true"
        error = entry.cancel(silent=silent)

        if error:
            flash(f"Something went wrong cancelling the entry - { error }", "danger")
            return redirect(url_for("admin.cancelEntry", id=id))
        else:
            flash("Entry has been cancelled", "success")
            return redirect(url_for("admin.listEntries"))

    else:
        flash(
            "Incorrect entry code (are you sure you know what you're doing?).",
            "warning",
        )

        return redirect(url_for("admin.cancelEntry", id=id))


@blueprint.route("/entry/<int:id>/resend-link", methods=["GET"])
@needs_admin
def resendLink(id):
    entry = Entry.query.get_or_404(id)
    return render_template("admin/entry/resend-link.html", entry=entry)


@blueprint.route("/entry/<int:id>/resend-link", methods=["POST"])
@needs_admin
def resendLinkProcess(id):
    entry = Entry.query.get_or_404(id)

    r = entry.sendConfirmationEmail()
    if r.status_code == 200:
        flash("Team portal link resent", "success")
    else:
        flash(f"Something went wrong resending the link - { r.text }", "danger")

    return redirect(url_for("admin.entry", id=id))