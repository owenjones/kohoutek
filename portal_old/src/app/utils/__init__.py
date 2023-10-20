import os
from datetime import datetime
import math
import string
import random

import inflect

from flask import current_app
from jinja2 import select_autoescape


class ConsoleFormat:
    Reset = "\033[0m"

    Red = "\033[31m"
    Yellow = "\033[33m"
    Green = "\033[32m"
    Blue = "\033[34m"
    Magenta = "\033[35m"

    Bold = "\033[1m"
    Italic = "\033[3m"
    Underline = "\033[4m"

    Tab = "\t"
    Newline = "\n"


def enableAutoescape(app):
    app.jinja_env.autoescape = select_autoescape(default_for_string=True, default=True)


def pluralise(word, n):
    if abs(n) != 1:
        i = inflect.engine()
        return i.plural(word)
    else:
        return word


def timeAgo(then):
    # FUTURE: switch from using seconds to proper timedelta attributes
    now = datetime.utcnow()
    diff = (now - then).total_seconds()

    if diff < 60:
        seconds = math.floor(diff)
        return f"{ seconds } { pluralise('second', seconds) } ago"

    elif diff < 3600:
        minutes = math.floor(diff / 60)
        return f"{ minutes } { pluralise('minute', minutes) } ago"

    elif diff < 86400:
        hours = math.floor(diff / 60 / 60)
        return f"{ hours } { pluralise('hour', hours) } ago"

    else:
        days = math.floor(diff / 60 / 60 / 24)
        return f"{ days } { pluralise('day', days) } ago"


def getFileExtension(filename):
    return filename.rsplit(".", 1)[1].lower()


def randomKey(length):
    numbers = string.digits
    return "".join(random.choice(numbers) for x in range(length))


def randomString(length):
    letters = string.ascii_letters
    return "".join(random.choice(letters) for x in range(length))
