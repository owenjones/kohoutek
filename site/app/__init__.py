from flask import Flask
from flask_talisman import Talisman
from flask_compress import Compress
from flask_sqlalchemy import SQLAlchemy
from flask_login import LoginManager
from flask_wtf.csrf import CSRFProtect
from flask_limiter import Limiter
from flask_limiter.util import get_remote_address
from flask_json import FlaskJSON

from app.config import loadConfig
from app.utils import enableAutoescape
from app.utils.logging import setupLogging

talisman = Talisman()
compress = Compress()
db = SQLAlchemy()
login_manager = LoginManager()
csrf = CSRFProtect()
limiter = Limiter(key_func=get_remote_address)
json = FlaskJSON()


def create_app(config):
    setupLogging()
    app = Flask(__name__)
    loadConfig(config, app)

    app.logger.info(f" --- { app.config['NAME'] } --- ")
    app.logger.info(f"Launching with { config } config")

    talisman.init_app(app, content_security_policy=app.config["CSP"])
    compress.init_app(app)
    db.init_app(app)
    # login_manager.init_app(app)
    csrf.init_app(app)
    limiter.init_app(app)
    json.init_app(app)

    from app.controllers import registerControllers

    registerControllers(app)
    enableAutoescape(app)
    return app
