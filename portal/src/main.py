import os
import sys

from dotenv import load_dotenv

load_dotenv()

from app import create_app

app = create_app(os.getenv("ENVIRONMENT"))
