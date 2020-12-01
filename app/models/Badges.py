from app import db


class Badge(db.Model):
    id = db.Column(db.Integer, primary_key=True)


class BadgeOrder(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    created_at = db.Column(db.DateTime, default=db.func.now())
    updated_at = db.Column(db.DateTime, default=db.func.now(), onupdate=db.func.now())

    def __init__(self, **kwargs):
        super(BadgeOrder, self).__init__(**kwargs)
