from enum import Enum

from app import db


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
    received_at = db.Column(db.DateTime, nullable=True)

    @property
    def type(self):
        map = {
            PaymentMethod.cheque: "Cheque",
            PaymentMethod.BACS: "Bank Transfer",
            PaymentMethod.stripe: "Online Payment",
            PaymentMethod.manual: "Manual Payment",
        }

        return map[self.method]
