from enum import Enum

from app import db


class OrderItems(db.Model):
    order_id = db.Column(db.Integer, db.ForeignKey("order.id"), primary_key=True)
    item_id = db.Column(db.Integer, db.ForeignKey("item.id"), primary_key=True)
    quantity = db.Column(db.Integer, default=1, nullable=False)


class Item(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text, nullable=False)
    image = db.Column(db.String(100))
    quantity = db.Column(db.Integer, default=0, nullable=False)
    unit_cost = db.Column(db.Float, nullable=False)


class OrderStatus(Enum):
    pending = "pending"


class Order(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    created_at = db.Column(db.DateTime, default=db.func.now())
    updated_at = db.Column(db.DateTime, default=db.func.now(), onupdate=db.func.now())
    entry_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    entry = db.relationship("Entry", backref=db.backref("orders", lazy=True))
    items = db.relationship(
        "Item",
        secondary=orderitems,
        lazy=True,
        backref=db.backref("orders", lazy=True),
    )

    @property
    def amount(self):
        pass
        # return sum([ (i.quantity * i.)])


class PaymentMethod(Enum):
    cheque = "cheque"
    BACS = "BACS"
    stripe = "stripe"
    manual = "manual"


class PaymentStatus(Enum):
    pending = "pending"
    confirmed = "confirmed"
    error = "error"


class Payment(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    created_at = db.Column(db.DateTime, default=db.func.now())
    order_id = db.Column(db.Integer, db.ForeignKey("order.id"), nullable=False)
    order = db.relationship(
        "Order", backref=db.backref("payment", lazy=True, uselist=False)
    )
    method = db.Column(db.Enum(PaymentMethod), nullable=False)
    status = db.Column(
        db.Enum(PaymentStatus), nullable=False, default=PaymentStatus.pending
    )
    amount_gross = db.Column(db.Float, nullable=False)
    amount_net = db.Column(db.Float, nullable=False)
    reference = db.Column(db.String, nullable=True)
