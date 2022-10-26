import os
import sys

from dotenv import load_dotenv

load_dotenv()

from app import create_app

application = create_app(os.getenv("ENVIRONMENT"))
