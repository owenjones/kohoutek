from enum import Enum

from flask import current_app as app, url_for

from app import db
from app.models import User, Role
from app.utils import randomString
from app.utils.mail import sendmail


class Organisation(Enum):
    scouting = "scouting"
    guiding = "guiding"


class County(Enum):
    avon = "avon"
    bsg = "bsg"  # Bristol and South Gloucestershire
    sn = "sn"  # Somerset North
    other = "other"


class District(db.Model):
    # Districts and Divisions lumped into one table
    id = db.Column(db.Integer, primary_key=True)
    county = db.Column(db.Enum(County))
    name = db.Column(db.String(250))


class Entry(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    created_at = db.Column(db.DateTime, default=db.func.now())
    updated_at = db.Column(db.DateTime, default=db.func.now(), onupdate=db.func.now())
    verified = db.Column(db.Boolean(), default=False)
    verification_code = db.Column(db.String(250))
    contact_name = db.Column(db.String(250), nullable=False)
    contact_email = db.Column(db.String(250), nullable=False, unique=True)
    organisation = db.Column(db.Enum(Organisation))
    county = db.Column(db.Enum(County))
    district_id = db.Column(db.Integer(), db.ForeignKey("district.id"), nullable=True)
    district_rel = db.relationship("District", backref=db.backref("entries"))
    district_name = db.Column(db.String(250), nullable=True)
    group_name = db.Column(db.String(250))
    troop_name = db.Column(db.String(250), nullable=True)

    def __init__(self, **kwargs):
        super(Entry, self).__init__(**kwargs)
        self.verification_code = randomString(35)
        self.user = User(role=Role.TEAM)

    def portal_link(self, next=False, **kwargs):
        portal = url_for(
            "portal.verify",
            entry=self.id,
            code=self.verification_code,
            _external=True,
        )

        next = url_for(next, **kwargs) if next else False
        next = f"?next={next}" if next else ""

        return portal + next

    def sendConfirmationEmail(self):
        send = sendmail(
            self.contact_email,
            "Kohoutek Entry",
            "signup-confirmation",
            confirmation_link=self.portal_link(),
        )

        return send

    def cancel(self, silent):
        try:
            db.session.delete(self)
            db.session.commit()

        except Exception as e:
            return e

        if not silent:
            send = sendmail(
                self.contact_email,
                "Kohoutek Entry Cancelled",
                "entry-cancelled",
                entry_name=self.name,
            )

            if send.status_code != 200:
                return send.text

        return False  # No error

    def verify(self, code):
        if code == self.verification_code and not self.verified:
            self.verified = True
            db.session.add(self)
            db.session.commit()
            return True
        else:
            return False

    @property
    def code(self):
        code = app.config["CODE_PREFIX"]
        return f"{code}-{self.id}"

    @property
    def name(self):
        if self.hasTroop():
            return f"{ self.group_name } ({ self.troop_name })"
        else:
            return self.group_name

    # Translate data back into organisation relevant terms
    def hasTroop(self):
        return self.organisation == Organisation.scouting and self.troop_name

    @property
    def group_label(self):
        return "Group" if self.organisation == Organisation.scouting else "Unit"

    @property
    def district_label(self):
        return "District" if self.organisation == Organisation.scouting else "Division"

    @property
    def district(self):
        return self.district_rel.name if self.district_id else self.district_name

    def hasCounty(self):
        return self.county != County.other

    @property
    def county_name(self):
        map = {
            County.avon: "Avon",
            County.bsg: "Bristol and South Gloucestershire",
            County.sn: "Somerset North",
        }

        return map[self.county] if self.county in map else None

    @property
    def organisation_name(self):
        map = {
            Organisation.scouting: "Scouting",
            Organisation.guiding: "Guiding",
        }

        return map[self.organisation] if self.organisation in map else None
