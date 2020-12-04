from flask_login import UserMixin, AnonymousUserMixin

from app import db, login_manager
from app.utils import randomKey


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

        if "key" not in kwargs:
            self.generateKey()

    @property
    def key(self):
        return False if self.id == 1 else self.loginKey

    @key.setter
    def key(self, key):
        self.loginKey = key

    def generateKey(self):
        self.loginKey = randomKey(8)

    def validateKey(self, key):
        return key == self.loginKey

    def hasPermission(self, permission):
        return (self.role & permission) > 0


class Guest(AnonymousUserMixin):
    def hasPermission(self, permission):
        return False


login_manager.anonymous_user = Guest