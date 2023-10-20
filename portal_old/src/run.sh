#!/bin/bash
source .env
export FLASK_APP="app:create_app('${ENVIRONMENT}')"
export FLASK_ENV=${ENVIRONMENT}
export FLASK_RUN_EXTRA_FILES=.env
flask run
