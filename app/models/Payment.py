from enum import Enum

from app import db


class PaymentMethod(Enum):
    cheque = "cheque"
    BACS = "BACS"
    stripe = "stripe"
    manual = "manual"


class PaymentStatus(Enum):
    new = "new"
    pending = "pending"
    received = "received"
    error = "error"


class Payment(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    created_at = db.Column(db.DateTime, default=db.func.now())
    method = db.Column(db.Enum(PaymentMethod), nullable=False)
    status = db.Column(
        db.Enum(PaymentStatus), nullable=False, default=PaymentStatus.new
    )
    amount = db.Column(db.Float, nullable=True)
    fee = db.Column(db.Float, nullable=True)
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

    @property
    def status_message(self):
        map = {
            PaymentStatus.new: "Pending",
            PaymentStatus.pending: "Pending",
            PaymentStatus.received: "Received",
            PaymentStatus.error: "Error",
        }

        return map[self.status]

    @property
    def status_colour(self):
        map = {
            PaymentStatus.new: "warning",
            PaymentStatus.pending: "warning",
            PaymentStatus.received: "success",
            PaymentStatus.error: "danger",
        }

        return map[self.status]

    def statusIs(self, status):
        return self.status == PaymentStatus(status)
