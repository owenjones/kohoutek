import os
import logging

from app.utils import ConsoleFormat


class AppFormatter(logging.Formatter):
    colours = {
        logging.DEBUG: ConsoleFormat.Blue,
        logging.INFO: ConsoleFormat.Green,
        logging.WARNING: ConsoleFormat.Yellow,
        logging.ERROR: ConsoleFormat.Red,
    }

    def format(self, record):
        l = f"[%(asctime)s][{ self.colours[record.levelno] }{ ConsoleFormat.Bold }%(levelname)s{ ConsoleFormat.Reset}] %(message)s"
        f = logging.Formatter(l)
        return f.format(record)


def setupLogging():
    level = os.getenv("LOG_LEVEL", "INFO")

    logger = logging.getLogger("app")
    logger.setLevel(level)

    handler = logging.StreamHandler()
    handler.setLevel(level)
    handler.setFormatter(AppFormatter())

    logger.addHandler(handler)
