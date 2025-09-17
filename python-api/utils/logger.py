import json
from datetime import datetime
import os

LOG_PATH = 'logs.json'

def log_decision(module, data):
    log_entry = {
        "module": module,
        "timestamp": datetime.utcnow().isoformat(),
        "data": data
    }
    logs = []
    if os.path.exists(LOG_PATH):
        with open(LOG_PATH, 'r') as f:
            logs = json.load(f)
    logs.append(log_entry)
    with open(LOG_PATH, 'w') as f:
        json.dump(logs, f, indent=2)
