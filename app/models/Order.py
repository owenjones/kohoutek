from enum import Enum

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

    items = db.relationship("OrderItem", backref=db.backref("order", lazy=True))

    postage_id = db.Column(db.Integer, db.ForeignKey("postage.id"), nullable=True)
    postage = db.relationship("Postage", lazy=True)

    def save(self):
        db.session.add(self)
        db.session.commit()

    def statusIs(self, status):
        return self.status == OrderStatus(status)

    @property
    def subtotal(self):
        return sum([item.amount for item in self.items])

    @property
    def total(self):
        total = self.subtotal

        if self.postage:
            total += self.postage.cost

        if self.payment:
            total += self.payment.fee

        return total

    @property
    def quantity(self):
        return sum([item.quantity for item in self.items])

    @property
    def status_name(self):
        map = {
            OrderStatus.incomplete: "Incomplete",
            OrderStatus.payment_pending: "Payment Pending",
            OrderStatus.complete: "Complete",
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

    def addItem(self, item_id, quantity):
        stock_item = Item.query.get(item_id)

        if stock_item:
            if stock_item.hasStock(quantity):
                item = OrderItem.query.filter_by(
                    order_id=self.id, item_id=item_id
                ).first()

                if item:
                    item.quantity += quantity

                else:
                    item = OrderItem(item_id=item_id, quantity=quantity)
                    self.items.append(item)

            else:
                return ItemStock.out_of_stock

        else:
            return None
