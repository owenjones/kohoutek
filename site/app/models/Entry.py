from enum import Enum

from flask import url_for

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
    district_rel = db.relationship("District")
    district_name = db.Column(db.String(250), nullable=True)
    group_name = db.Column(db.String(250))
    troop_name = db.Column(db.String(250), nullable=True)

    def __init__(self, **kwargs):
        super(Entry, self).__init__(**kwargs)
        self.verification_code = randomString(35)
        self.user = User(role=Role.TEAM)

    @property
    def portal_link(self):
        return url_for(
            "portal.verify",
            entry=self.id,
            code=self.verification_code,
            _external=True,
        )

    def sendConfirmationEmail(self):
        send = sendmail(
            self.contact_email,
            "Kohoutek 2021 Entry",
            "signup-confirmation",
            confirmation_link=self.portal_link,
        )

    def verify(self, code):
        if code == self.verification_code and not self.verified:
            self.verified = True
            db.session.add(self)
            db.session.commit()
            return True
        else:
            return False

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
