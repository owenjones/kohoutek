from flask import Blueprint, render_template

blueprint = Blueprint("root", __name__)


@blueprint.route("/")
def index():
    return render_template("root/index.jinja")
