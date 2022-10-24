import requests

from flask import current_app, render_template


def makeurl(endpoint):
    return f'{ current_app.config["MAIL"]["url"] }{ current_app.config["MAIL"]["domain"] }/{ endpoint }'


def sendmail(to, subject, template, **params):
    return requests.post(
        makeurl("messages"),
        auth=("api", current_app.config["MAIL"]["key"]),
        data={
            "to": to,
            "from": current_app.config["MAIL"]["from"],
            "h:Reply-To": current_app.config["MAIL"]["reply-to"],
            "subject": subject,
            "text": render_template(f"mail/{template}/text.jinja", **params),
            "html": render_template(f"mail/{template}/html.jinja", **params),
        },
    )
