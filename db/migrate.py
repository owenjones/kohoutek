import os
import sys

sys.path.append(os.path.abspath(""))

from dotenv import load_dotenv

load_dotenv()

from app import db
from app.models import Order, Item


def seed(app):
    with app.app_context():
        app.logger.info("Migrating app database...")
        db.create_all()
        db.session.commit()
        app.logger.info("...done!")


if __name__ == "__main__":
    from app import create_app

    app = create_app(os.getenv("ENVIRONMENT"))
    seed(app)
