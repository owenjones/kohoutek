import os
import sys

sys.path.append(os.path.abspath(""))

from dotenv import load_dotenv

load_dotenv()

from app import db
from app.models import Activity


def addActivities(app):
    with app.app_context():
        app.logger.info("Adding activities...")

        activities = [
            "Search the Seven Seas",
            "Race to the Pole",
            "Tower Building",
            "Kim's Game",
            "Buried Treasure",
            "The Sea in the Media",
            "Crossword",
            "Underwater Scene",
            "Origami Fish",
            "Turtley Awesome Quiz",
        ]
        for activity in activities:
            db.session.add(Activity(name=activity))
            app.logger.info(f" - added { activity }")

        db.session.commit()
        app.logger.info("...done!")


if __name__ == "__main__":
    from app import create_app

    app = create_app(os.getenv("ENVIRONMENT"))
    addActivities(app)
