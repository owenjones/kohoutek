from enum import Enum

import pycountry

from app import db
from app.models import Item, OrderItem, ItemStock, Payment


class Postage(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(250), nullable=False)
    description = db.Column(db.Text, nullable=False)
    cost = db.Column(db.Float, nullable=False)
    item_min = db.Column(db.Integer)
    item_max = db.Column(db.Integer)


class OrderStatus(Enum):
    incomplete = "incomplete"
    payment_pending = "payment_pending"
    complete = "complete"
    dispatched = "dispatched"
    cancelled = "cancelled"


class Order(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    created_at = db.Column(db.DateTime, default=db.func.now())
    updated_at = db.Column(db.DateTime, default=db.func.now(), onupdate=db.func.now())
    status = db.Column(db.Enum(OrderStatus), default=OrderStatus.incomplete)

    entry_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    entry = db.relationship("Entry", backref=db.backref("orders", lazy=True))

    items = db.relationship(
        "OrderItem",
        backref=db.backref("order", lazy=True),
        cascade="all, delete-orphan",
    )

    postage_id = db.Column(db.Integer, db.ForeignKey("postage.id"), nullable=True)
    postage = db.relationship(
        "Postage",
        lazy=True,
    )

    postage_name = db.Column(db.Text, nullable=True)
    postage_address_1 = db.Column(db.Text, nullable=True)
    postage_address_2 = db.Column(db.Text, nullable=True)
    postage_city = db.Column(db.Text, nullable=True)
    postage_postcode = db.Column(db.Text, nullable=True)
    postage_country = db.Column(db.Text, nullable=True)

    payment_id = db.Column(db.Integer, db.ForeignKey("payment.id"), nullable=True)
    payment = db.relationship(
        "Payment",
        backref=db.backref(
            "order",
            lazy=True,
            uselist=False,
        ),
        single_parent=True,
        cascade="all, delete-orphan",
    )

    @property
    def code(self):
        return f"{ self.entry.code }-{ self.id }"

    def save(self):
        db.session.add(self)
        db.session.commit()

    def statusIs(self, status):
        return self.status == OrderStatus(status)

    @property
    def subtotal(self):
        # Total for order NOT including payment processing fees
        total = sum([item.amount for item in self.items])

        if self.postage:
            total += self.postage.cost

        return total

    @property
    def total(self):
        # Total for order including all fees
        total = self.subtotal

        if self.payment:
            total += self.payment.fee or 0

        return total

    @property
    def quantity(self):
        return sum([item.quantity for item in self.items])

    @property
    def status_name(self):
        map = {
            OrderStatus.incomplete: "incomplete",
            OrderStatus.payment_pending: "payment_pending",
            OrderStatus.complete: "complete",
            OrderStatus.dispatched: "dispatched",
            OrderStatus.cancelled: "cancelled",
        }

        return map[self.status]

    @property
    def status_message(self):
        map = {
            OrderStatus.incomplete: "In Progress",
            OrderStatus.payment_pending: "Payment Pending",
            OrderStatus.complete: "Payment Received",
            OrderStatus.dispatched: "Dispatched",
            OrderStatus.cancelled: "Cancelled",
        }

        return map[self.status]

    @property
    def status_colour(self):
        map = {
            OrderStatus.incomplete: "warning",
            OrderStatus.payment_pending: "warning",
            OrderStatus.complete: "success",
            OrderStatus.dispatched: "success",
            OrderStatus.cancelled: "danger",
        }

        return map[self.status]

    @property
    def postage_country_name(self):
        return pycountry.countries.get(alpha_2=self.postage_country).name or "Unknown"

    def addItem(self, item_id, quantity):
        stock_item = Item.query.get(item_id)

        if stock_item:
            if stock_item.hasStock(quantity):
                item = OrderItem.query.filter_by(
                    order_id=self.id, item_id=item_id
                ).first()

                if item:
                    if quantity > 0:
                        item.quantity = quantity

                    else:
                        self.items.remove(item)

                else:
                    if quantity > 0:
                        item = OrderItem(item_id=item_id, quantity=quantity)
                        self.items.append(item)

                return True

            return ItemStock.out_of_stock

        else:
            return False

    def addPostage(self, id):
        self.postage_id = id
