import os
import sys

sys.path.append(os.path.abspath(""))

from dotenv import load_dotenv

load_dotenv()

from app.models import Entry
from app.utils.mail import sendmail


def sendUpdateEmail(app, subject, template):
    with app.app_context():
        app.logger.info("Sending update emails...")

        entries = Entry.query.all()

        for entry in entries:
            sent = sendmail(
                entry.contact_email,
                subject,
                template,
                verified=entry.verified,
                verify_link=entry.portal_link(),
                portal_link=entry.portal_link("portal.index"),
                badge_link=entry.portal_link("orders.placeOrder"),
                match_link=entry.portal_link("matchmake.index"),
                activity_link=entry.portal_link("portal.downloadActivities"),
                score_link=entry.portal_link("scoring.index"),
            )

            if sent.status_code == 200:
                app.logger.info(f" - sent to {entry.name}")

            else:
                app.logger.warn(f" - failed to send to {entry.name} ({entry.code})")

        app.logger.info("...done!")


if __name__ == "__main__":
    from app import create_app

    app = create_app(os.getenv("ENVIRONMENT"))
    subject = "Kohoutek 2021 Final Scores"
    template = "scores-online"
    sendUpdateEmail(app, subject, template)
