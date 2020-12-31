from haversine import haversine

from app import db


class Matchmake(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    date = db.Column(db.DateTime, nullable=True)
    number = db.Column(db.Integer, default=0)
    contact = db.Column(db.Boolean, default=False)
    longitude = db.Column(db.String(15), nullable=True)
    latitude = db.Column(db.String(15), nullable=True)

    entry_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    entry = db.relationship(
        "Entry", backref=db.backref("match", lazy=True, uselist=False)
    )

    @property
    def lngLat(self):
        return f"[{ self.longitude }, { self.latitude }]"

    @property
    def date_string(self):
        return self.date.strftime("%Y-%m-%d")

    def distanceTo(self, point):
        return round(
            haversine(
                (float(self.longitude), float(self.latitude)),
                (float(point.longitude), float(point.latitude)),
            )
        )


class Message(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    sent_from_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    sent_to_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    message = db.Column(db.Text)
