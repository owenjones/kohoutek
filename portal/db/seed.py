import os
import sys

sys.path.append(os.path.abspath(""))

from dotenv import load_dotenv

load_dotenv()


def init(app):
    with app.app_context():
        app.logger.info("Creating fresh app database...")

        db.drop_all()
        db.create_all()

        app.logger.info("...created!")
        app.logger.info("Adding root user...")

        rkey = os.getenv("ROOT_KEY", randomKey(12))
        u = User(username="root", key=rkey, role=Role.ADMIN)
        db.session.add(u)

        app.logger.info(f"...root user added, key: { rkey }")


if __name__ == "__main__":
    from app import create_app

    app = create_app(os.getenv("ENVIRONMENT"))

    from add_districts import addDistricts
    from add_items import addItems
    from add_postage_options import addPostageOptions
    from add_activities import addActivities

    init(app)
    addDistricts(app)
    addItems(app)
    addPostageOptions(app)
    addActivities(app)
