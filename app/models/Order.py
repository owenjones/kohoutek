from app import db

orderitems = db.Table(
    "order_items",
    db.Column("order.id", db.Integer, db.ForeignKey("order.id"), primary_key=True),
    db.Column("item_id", db.Integer, db.ForeignKey("item.id"), primary_key=True),
    db.Column("quantity", db.Integer, default=1, nullable=False),
)


class Item(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text, nullable=False)
    image = db.Column(db.String(100))
    quantity = db.Column(db.Integer, default=0, nullable=False)
    unit_cost = db.Column(db.Float, nullable=False)


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
