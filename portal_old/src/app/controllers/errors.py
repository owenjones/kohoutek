from flask import Blueprint, render_template

blueprint = Blueprint("errors", __name__)


@blueprint.app_errorhandler(400)
def badRequest(error):
    return render_template("root/error.html", error=error), 400


@blueprint.app_errorhandler(401)
def unauthorisedError(error):
    return render_template("root/error.html", error=error), 401


@blueprint.app_errorhandler(403)
def forbiddenError(error):
    return render_template("root/error.html", error=error), 403


@blueprint.app_errorhandler(404)
def notFoundError(error):
    return render_template("root/error.html", error=error), 404


@blueprint.app_errorhandler(405)
def methodNotAllowed(error):
    return render_template("root/error.html", error=error), 405


@blueprint.app_errorhandler(413)
def requestTooLarge(error):
    return render_template("root/error.html", error=error), 413


@blueprint.app_errorhandler(429)
def tooManyRequests(error):
    return (
        render_template(
            "root/error.html",
            error="429 Too Many Requests: You've tried to access this page too many times, please wait and try again later",
        ),
        429,
    )


@blueprint.app_errorhandler(504)
def serverError(error):
    return render_template("root/error.html", error=error), 500
