from app import db


class Matchmake(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    date = db.Column(db.DateTime, nullable=True)
    number = db.Column(db.Integer, nullable=True)
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
