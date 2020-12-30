from app import db


class Matchmake(db.Model):
    id = db.Column(db.Integer, foreign_key=True)
    entry_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    entry = db.relationship(
        "Entry", backref=db.backref("matchmake", lazy=True, uselist=False)
    )

    date = db.Column(db.DateTime, nullable=True)
    contact = db.Column(db.Boolean, default=False)
    lat = db.Column(db.Float, nullable=True)
    lon = db.Column(db.Float, nullable=True)
    number = db.Column(db.Integer, nullable=True)
