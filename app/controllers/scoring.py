from flask import Blueprint, render_template, redirect, url_for

from app.models import Score

blueprint = Blueprint("scoring", __name__, url_prefix="/portal")
