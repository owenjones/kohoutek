from enum import Enum

from app import db
from app.utils.auth import randomString


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
    district_name = db.Column(db.String(250), nullable=True)
    group_name = db.Column(db.String(250))
    troop_name = db.Column(db.String(250), nullable=True)

    def __init__(self, **kwargs):
        super(Signup, self).__init__(**kwargs)
        self.verification_code = randomString(50)

    def verify(self, code):
        if code == self.verification_code:
            self.verified = True
            self.verification_code = None
            db.session.add(self)
            db.session.commit()
            return True
        else:
            return False
