from app import db


class Score(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    entry_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    entry = db.relationship("Entry", backref=db.backref("scores", lazy=True))
