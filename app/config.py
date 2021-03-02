import os
from pathlib import Path

from dotenv import load_dotenv

env = Path("..") / ".env"
load_dotenv(dotenv_path=env)

from app.utils import randomString, randomKey


def loadConfig(config, app):
    configs = {
        "development": DevConfig,
        "production": ProdConfig,
    }

    app.config.from_object(configs[config])


class Config:
    NAME = os.getenv("NAME", "Flask App")
    ROOT_KEY = os.getenv("ROOT_KEY", randomKey(12))
    SECRET_KEY = os.getenv("SECRET_KEY", randomString(25))

    STRIPE = {
        "publishable_key": os.getenv("STRIPE_PUBLISHABLE_KEY"),
        "secret_key": os.getenv("STRIPE_SECRET_KEY"),
    }

    MAIL = {
        "url": "https://api.eu.mailgun.net/v3/",
        "domain": os.getenv("MAIL_DOMAIN"),
        "key": os.getenv("MAIL_KEY"),
        "from": os.getenv("MAIL_FROM_ADDRESS"),
        "reply-to": os.getenv("MAIL_REPLY_ADDRESS"),
    }

    CSP = {
        "default-src": [
            "'self'",
            "'unsafe-inline'",
            "fonts.googleapis.com",
            "fonts.gstatic.com",
            "api.mapbox.com",
        ],
        "img-src": ["*", "data:", "blob:"],
        "script-src": [
            "'self'",
            "'unsafe-inline'",
            "cdnjs.cloudflare.com",
            "js.stripe.com",
            "api.mapbox.com",
        ],
        "frame-src": [
            "js.stripe.com",
        ],
        "worker-src": ["blob:"],
        "child-src": ["blob:"],
        "connect-src": [
            "'self'",
            "*.tiles.mapbox.com",
            "api.mapbox.com",
            "events.mapbox.com",
        ],
    }

    COMPRESS_MIMETYPES = [
        "text/html",
        "text/css",
        "text/xml",
        "application/json",
        "application/javascript",
    ]
    COMPRESS_LEVEL = 6
    COMPRESS_MIN_SIZE = 500
    SEND_FILE_MAX_AGE_DEFAULT = 31536000

    MAX_CONTENT_LENGTH = 50 * 1024 * 1024
    UPLOAD_FOLDER = "app/uploads"
    UPLOAD_EXTENSIONS = {
        "bmp",
        "gif",
        "jpg",
        "jpeg",
        "png",
        "webp",
        "avi",
        "mov",
        "mp4",
        "webm",
    }

    SQLALCHEMY_TRACK_MODIFICATIONS = False

    MAP = {
        "mapbox_key": os.getenv("MAPBOX_KEY", ""),
        "default_lat": os.getenv("MAP_DEFAULT_LAT", 0),
        "default_lon": os.getenv("MAP_DEFAULT_LON", 0),
    }


class DevConfig(Config):
    ENVIRONMENT = "development"
    SQLALCHEMY_DATABASE_URI = f"sqlite:///{ os.getenv('DEV_DB_FILE', '')}"


class ProdConfig(Config):
    ENVIRONMENT = "production"
    SERVER_NAME = os.getenv("SERVER_NAME", "")

    user = os.getenv("DB_USER")
    password = os.getenv("DB_PASS")
    server = os.getenv("DB_HOST", "127.0.0.1")
    database = os.getenv("DB_NAME")

    SQLALCHEMY_DATABASE_URI = f"mysql://{ user }:{ password }@{ server }/{ database }?ssl=true&charset=utf8mb4"
