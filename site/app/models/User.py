from flask_login import UserMixin, AnonymousUserMixin

from app import db, login_manager


class Permission:
    NONE = 0b000
    LOGIN = 0b001
    TEAM = 0b100
    ADMIN = 0b010


class Role:
    GUEST = Permission.NONE
    USER = Permission.LOGIN
    TEAM = Permission.TEAM
    ADMIN = Permission.ADMIN | Permission.LOGIN


class User(UserMixin, db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(50), unique=True)
    loginKey = db.Column(db.String(12))
    role = db.Column(db.Integer, nullable=False, default=Role.USER)

    entry_id = db.Column(
        db.Integer, db.ForeignKey("entry.id"), nullable=True, unique=True
    )
    entry = db.relationship(
        "Entry", backref=db.backref("user", lazy=True, uselist=False)
    )

    def __init__(self, **kwargs):
        super(User, self).__init__(**kwargs)

    def hasPermission(self, permission):
        return (self.role & permission) > 0


class Guest(AnonymousUserMixin):
    def hasPermission(self, permission):
        return False


login_manager.anonymous_user = Guest
