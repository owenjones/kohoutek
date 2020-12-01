from pathlib import Path
from importlib import import_module


class registerControllers:
    found = []
    loaded = []

    def __init__(self, app):
        self.find()
        self.load()
        self.register(app)

    def find(self):
        dir = Path(__file__).resolve().parent
        self.found = [
            f.stem
            for f in dir.iterdir()
            if f.is_file() and f.suffix == ".py" and f.name != "__init__.py"
        ]

    def load(self):
        for file in self.found:
            controller = import_module(f".{file}", __name__)
            self.loaded.append(controller)

    def register(self, app):
        for controller in self.loaded:
            if hasattr(controller, "blueprint"):
                app.register_blueprint(controller.blueprint)
