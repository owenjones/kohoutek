import os
import sys

sys.path.append(os.path.abspath(""))

from dotenv import load_dotenv

load_dotenv()

from app import db
from app.models import Item


def seed(app):
    with app.app_context():
        app.logger.info("Adding order items...")

        db.session.add(
            Item(
                name="2021 Badge",
                description="The badge for the 2021 online event",
                unit_cost=1,
            )
        )

        db.session.add(
            Item(
                name="2020 Badge",
                description="The badge for the 2020 event",
                unit_cost=1,
                limited=True,
                stock=100,
            )
        )

        db.session.add(
            Item(
                name="2019 Badge",
                description="The badge for the 2019 event",
                unit_cost=1,
                limited=True,
                stock=100,
            )
        )

        db.session.commit()
        app.logger.info("...done!")


if __name__ == "__main__":
    from app import create_app

    app = create_app(os.getenv("ENVIRONMENT"))
    seed(app)
