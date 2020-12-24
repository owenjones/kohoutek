from enum import Enum

from flask import url_for

from app import db


class ItemStock(Enum):
    in_stock = "in_stock"
    out_of_stock = "out_of_stock"


class Item(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text, nullable=False)
    image = db.Column(db.String(100), nullable=True)
    limited = db.Column(db.Boolean, default=False)
    stock = db.Column(
        db.Integer,
        nullable=False,
        default=0,
    )
    unit_cost = db.Column(db.Float, nullable=False)
    index = db.Column(db.Integer, nullable=False, default=10)

    @property
    def status(self):
        return (
            ItemStock.in_stock
            if (self.limited == False or (self.limited == True and self.remaining > 0))
            else ItemStock.out_of_stock
        )

    @property
    def sold(self):
        return sum([item.quantity for item in self.ordered])

    @property
    def remaining(self):
        return (self.stock - self.sold) if self.limited else 0

    def hasStock(self, quantity):
        return (not self.limited) or (self.remaining >= quantity)

    @property
    def image_url(self):
        return url_for("static", filename=f"img/badges/{ self.image }", _external=True)


class OrderItem(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    order_id = db.Column(db.Integer, db.ForeignKey("order.id"))
    item_id = db.Column(db.Integer, db.ForeignKey("item.id"))
    item = db.relationship(
        "Item", backref=db.backref("ordered"), lazy=True, uselist=False
    )
    quantity = db.Column(db.Integer, default=1, nullable=False)

    @property
    def amount(self):
        return self.quantity * self.item.unit_cost
