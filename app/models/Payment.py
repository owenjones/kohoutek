from enum import Enum

from app import db
from app.utils.mail import sendmail


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
    reference = db.Column(db.Text, nullable=True)
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
    def type_name(self):
        map = {
            PaymentMethod.cheque: "cheque",
            PaymentMethod.BACS: "BACS",
            PaymentMethod.stripe: "stripe",
            PaymentMethod.manual: "manual",
        }

        return map[self.method]

    @property
    def status_name(self):
        map = {
            PaymentStatus.new: "new",
            PaymentStatus.pending: "pending",
            PaymentStatus.received: "received",
            PaymentStatus.error: "error",
        }

        return map[self.status]

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

    def isBy(self, method):
        return self.method == PaymentMethod(method)

    def markReceived(self):
        self.status = PaymentStatus.received

        sent = sendmail(
            self.order.entry.contact_email,
            "Payment Received",
            "order-payment-received",
            order=self.order,
            order_link=self.order.entry.portal_link("orders.viewOrder", id=self.id),
        )

        if sent.status_code == 200:
            return True

        else:
            return False
