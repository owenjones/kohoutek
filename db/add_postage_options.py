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
                name="Passed on to group",
                description="Passed on to the group where we're able to do so. Please only choose this if you've spoken to us first!",
                cost=0,
                item_min=0,
                item_max=0,
            )
        )

        db.session.add(
            Postage(
                name="UK Postage",
                description="Postage within the UK for 1-20 badges",
                cost=1,
                item_min=0,
                item_max=20,
            )
        )

        db.session.add(
            Postage(
                name="UK Postage",
                description="Postage within the UK for 20+ badges",
                cost=3,
                item_min=21,
                item_max=0,
            )
        )

        db.session.add(
            Postage(
                name="International Postage (Europe)",
                description="Postage within Europe for 1-20 badges",
                cost=3,
                item_min=0,
                item_max=20,
            )
        )

        db.session.add(
            Postage(
                name="International Postage (Europe)",
                description="Postage within Europe for 20-40 badges",
                cost=5,
                item_min=21,
                item_max=40,
            )
        )

        db.session.add(
            Postage(
                name="International Postage (Europe)",
                description="Postage within Europe for 40-100 badges",
                cost=6,
                item_min=41,
                item_max=100,
            )
        )

        db.session.add(
            Postage(
                name="International Postage (North America)",
                description="Postage to Canada/USA for 1-20 badges",
                cost=4,
                item_min=0,
                item_max=20,
            )
        )

        db.session.add(
            Postage(
                name="International Postage (North America)",
                description="Postage to Canada/USA for 20-40 badges",
                cost=6.50,
                item_min=21,
                item_max=40,
            )
        )

        db.session.add(
            Postage(
                name="International Postage (North America)",
                description="Postage to Canada/USA for 40-100 badges",
                cost=8.5,
                item_min=41,
                item_max=100,
            )
        )

        db.session.commit()
        app.logger.info("...done!")


if __name__ == "__main__":
    from app import create_app

    app = create_app(os.getenv("ENVIRONMENT"))
    addPostageOptions(app)
