import os
import sys

sys.path.append(os.path.abspath(""))

from dotenv import load_dotenv

load_dotenv()

from app import db
from app.models import Postage


def addPostageOptions(app):
    with app.app_context():
        app.logger.info("Adding postage options...")

        db.session.add(
            Postage(
                name="UK Postage",
                description="Postage within the UK for 1-20 badges",
                cost=2,
                item_min=0,
                item_max=20,
            )
        )

        db.session.add(
            Postage(
                name="UK Postage",
                description="Postage within the UK for 20+ badges",
                cost=3.50,
                item_min=21,
                item_max=0,
            )
        )

        db.session.add(
            Postage(
                name="International Postage",
                description="Postage outside the UK for 1-20 badges",
                cost=5,
                item_min=0,
                item_max=20,
            )
        )

        db.session.add(
            Postage(
                name="International Postage",
                description="Postage outside the UK for 20+ badges",
                cost=10,
                item_min=21,
                item_max=0,
            )
        )

        db.session.commit()
        app.logger.info("...done!")


if __name__ == "__main__":
    from app import create_app

    app = create_app(os.getenv("ENVIRONMENT"))
    addPostageOptions(app)
