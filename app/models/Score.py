from enum import Enum

from app import db


class Section(Enum):
    beaver = "Beavers"
    cub = "Cubs"
    scout = "Scouts"
    explorer = "Explorers"
    network = "Network"
    rainbow = "Rainbows"
    brownie = "Brownies"
    guide = "Guides"
    ranger = "Rangers"


class Activity(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.Text, nullable=False)


class EntryActivities(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    entry_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    activity_id = db.Column(db.Integer, db.ForeignKey("activity.id"), nullable=False)
    entry = db.relationship("Entry", backref=db.backref("activities", lazy=True))
    activity = db.relationship(
        "Activity",
        backref=db.backref("entries", lazy=True),
    )


class Team(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.Text, nullable=True)
    section = db.Column(db.Enum(Section), nullable=True)
    members = db.Column(db.Integer, default=0)
    entry_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    entry = db.relationship("Entry", backref=db.backref("teams", lazy="dynamic"))
    scores = db.relationship(
        "Score",
        backref=db.backref("team", lazy=True),
        cascade="all, delete-orphan",
    )
    submitted = db.Column(db.Boolean, default=False)

    def __init__(self, **kwargs):
        super(Team, self).__init__(**kwargs)

        # TODO: Cleaner way to do this (was in a rush...)
        self.scores.append(Score())
        self.scores.append(Score())
        self.scores.append(Score())
        self.scores.append(Score())

    def rawScore(self):
        return sum([activity.score for activity in self.activities])


class Score(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    activity_id = db.Column(db.Integer, db.ForeignKey("activity.id"), nullable=True)
    activity = db.relationship("Activity", backref=db.backref("scores", lazy=True))
    team_id = db.Column(db.Integer, db.ForeignKey("team.id"), nullable=False)
    score = db.Column(db.Integer, nullable=True)
