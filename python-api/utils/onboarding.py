def generate_onboarding_tasks(employee_data):
    role = employee_data.get("role", "employee").lower()
    tasks = [
        {"task": "Submit ID documents", "status": "Pending"},
        {"task": "Sign NDA agreement", "status": "Pending"},
        {"task": "Attend welcome session", "status": "Scheduled"},
    ]
    if "developer" in role:
        tasks.append({"task": "Setup local dev environment", "status": "Pending"})
    if "hr" in role:
        tasks.append({"task": "Review company policies", "status": "Pending"})
    return tasks
