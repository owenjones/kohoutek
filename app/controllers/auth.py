from flask import (
    current_app,
    Blueprint,
    request,
    flash,
    render_template,
    redirect,
    url_for,
)
from flask_login import current_user, login_required, login_user, logout_user

from app import login_manager, limiter
from app.models import User, Permission

blueprint = Blueprint("auth", __name__)

login_manager.login_view = "auth.login"


@blueprint.app_context_processor
def injectAuthChecks():
    def hasAdmin():
        return current_user.hasPermission(Permission.ADMIN)

    def hasTeam():
        return current_user.hasPermission(Permission.TEAM)

    def hasLogin():
        return current_user.hasPermission(Permission.LOGIN)

    return dict(hasAdmin=hasAdmin, hasTeam=hasTeam, hasLogin=hasLogin)


@blueprint.app_template_filter("roleName")
def roleName(role):
    roles = {0: "Guest", 1: "User", 3: "Admin", 4: "Team"}
    return roles[role] if role in roles else "UNKNOWN"


@login_manager.user_loader
def loadUser(id):
    return User.query.get(id)


@blueprint.route("/login")
@limiter.limit("10/minute")
@limiter.limit("50/hour")
def login():
    if current_user.is_authenticated and current_user.hasPermission(Permission.LOGIN):
        return redirect(url_for("admin.index"))
    else:
        return render_template("auth/login.jinja", back=request.referrer)


@blueprint.route("/login", methods=["POST"])
@limiter.limit("5/minute")
@limiter.limit("25/hour")
def processLogin():
    username = request.form["user"].lower()
    u = User.query.filter_by(username=username).first()

    if u and u.validateKey(request.form["key"]):
        if u.hasPermission(Permission.LOGIN):
            login_user(u)
            current_app.logger.info(
                f"user login ({ u.username }) from { request.remote_addr }"
            )
            flash("Successfully logged in", "success")
            return redirect(url_for("admin.index"))

        else:
            flash("This user is not permitted to login", "danger")
            return render_template("auth/login.jinja")

    else:
        current_app.logger.error(f"incorrect username/key from { request.remote_addr }")
        flash("Username or key incorrect", "danger")
        return render_template("auth/login.jinja")


@blueprint.route("/logout")
def logout():
    logout_user()
    return redirect(url_for("auth.login"))
