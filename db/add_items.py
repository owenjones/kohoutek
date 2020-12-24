import os
import sys

sys.path.append(os.path.abspath(""))

from dotenv import load_dotenv

load_dotenv()

from app import db
from app.models import Item


def addItems(app):
    with app.app_context():
        app.logger.info("Adding order items...")

        db.session.add(
            Item(
                name="2021 Badge",
                description="The badge for the 2021 Kohoutek Online event",
                image="2021",
                unit_cost=1,
                index=1,
            )
        )

        db.session.add(
            Item(
                name="2020 Badge",
                description="For the collectors, the 2020 event badge",
                image="2020",
                unit_cost=1,
                limited=True,
                stock=100,
                index=2,
            )
        )

        db.session.add(
            Item(
                name="2019 Badge",
                description="For the collectors, the 2019 event badge",
                image="2019",
                unit_cost=1,
                limited=True,
                stock=150,
                index=3,
            )
        )

        db.session.commit()
        app.logger.info("...done!")


if __name__ == "__main__":
    from app import create_app

    app = create_app(os.getenv("ENVIRONMENT"))
    addItems(app)
