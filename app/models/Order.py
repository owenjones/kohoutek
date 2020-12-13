from enum import Enum

from app import db
from app.models import Item, OrderItem


class OrderStatus(Enum):
    pending = "pending"
    confirmed = "confirmed"
    dispatched = "dispatched"
    cancelled = "cancelled"


class Order(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    created_at = db.Column(db.DateTime, default=db.func.now())
    updated_at = db.Column(db.DateTime, default=db.func.now(), onupdate=db.func.now())
    status = db.Column(db.Enum(OrderStatus), default=OrderStatus.pending)
    entry_id = db.Column(db.Integer, db.ForeignKey("entry.id"), nullable=False)
    entry = db.relationship("Entry", backref=db.backref("orders", lazy=True))
    items = db.relationship("OrderItem", backref=db.backref("order", lazy=True))

    def save(self):
        db.session.add(self)
        db.session.commit()

    @property
    def code(self):
        return f"{self.entry.code}-o{self.id}"

    @property
    def amount(self):
        return sum([item.amount for item in self.items])

    @property
    def quantity(self):
        return sum([item.quantity for item in self.items])

    def addItem(self, item_id, quantity):
        stock_item = Item.query.get(item_id)

        if stock_item:
            if stock_item.stock >= quantity:
                item = OrderItem(item_id=item_id, quantity=quantity)
                self.items.append(item)

            else:
                return False
