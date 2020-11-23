import os

from dotenv import load_dotenv

load_dotenv()

from app import db
from app.models import Organisation, County, District


def seed(app):
    with app.app_context():
        app.logger.info("Creating app database...")

        db.create_all()

        app.logger.info("...created!")

        app.logger.info("Adding District/Division data...")

        districts = [
            ("Axe", County.avon),
            ("Bath", County.avon),
            ("Bristol South", County.avon),
            ("Brunel", County.avon),
            ("Cabot", County.avon),
            ("Cotswold Edge", County.avon),
            ("Gordano", County.avon),
            ("Kingswood", County.avon),
            ("Wansdyke", County.avon),
            ("Bristol North East", County.bsg),
            ("Bristol North West", County.bsg),
            ("Bristol South", County.bsg),
            ("Bristol South West", County.bsg),
            ("Concorde", County.bsg),
            ("Frome Valley", County.bsg),
            ("Kingswood North", County.bsg),
            ("Kingswood South", County.bsg),
            ("Severnvale", County.bsg),
            ("South Cotswold", County.bsg),
            ("Avon Valley South", County.sn),
            ("Bath", County.sn),
            ("Cam Valley", County.sn),
            ("Portishead", County.sn),
            ("Weston-super-Mare", County.sn),
            ("Wraxhall", County.sn),
            ("Yeo Vale", County.sn),
        ]
        for name, county in districts:
            d = District(name=name, county=county)
            db.session.add(d)
            app.logger.info(f" - added { d.name }")

        app.logger.info("...Districts/Divisions added!")

        db.session.commit()

        app.logger.info("Database seeding complete")


if __name__ == "__main__":
    from app import create_app

    app = create_app(os.getenv("ENVIRONMENT"))
    seed(app)
