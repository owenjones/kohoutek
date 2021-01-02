from app import db


class Activity(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.Text, nullable=True)


class Score(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    team_id = db.Column(db.Integer, db.ForeignKey("team.id"))
    team = db.relationship("Team", backref=db.backref("scores"))
    activity_id = db.Column(db.Integer, db.ForeignKey("activity.id"))
    score = db.Column(db.Integer, default=0)


class Team(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.Text, nullable=True)

    entry_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    entry = db.relationship("Entry", backref=db.backref("teams", lazy=True))

    @property
    def score(self):
        return sum([s.score for s in self.scores])
