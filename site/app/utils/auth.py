from functools import wraps
from flask import redirect, url_for, abort
from flask_login import current_user

from app.models import Permission


def needs_group(f):
    @wraps(f)
    def inner(*args, **kwargs):
        if current_user.hasPermission(Permission.GROUP):
            return f(*args, **kwargs)

        elif current_user.get_id() == None:
            # Not logged in - redirect to form
            return redirect(url_for("group.login"))

        else:
            # Logged in but not a Group User
            return abort(403)

    return inner


def needs(permission):
    def outer(f):
        @wraps(f)
        def inner(*args, **kwargs):
            if current_user.hasPermission(permission):
                return f(*args, **kwargs)

            elif current_user.get_id() == None:
                return redirect(url_for("auth.login"))

            else:
                return abort(403)

        return inner

    return outer


def needs_login(f):
    return needs(Permission.LOGIN)(f)


def needs_curate(f):
    return needs(Permission.CURATE)(f)


def needs_manage(f):
    return needs(Permission.MANAGE)(f)


def needs_admin(f):
    return needs(Permission.ADMIN)(f)
