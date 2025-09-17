from flask import Flask, request, jsonify
from utils.resume_parser import parse_and_score_resume
from utils.interview_analyzer import analyze_interview, calculate_hiring_decision
from utils.onboarding import generate_onboarding_tasks
from utils.leave_predictor import predict_leave_approval
from utils.logger import log_decision
import json

app = Flask(__name__)

@app.route('/api/resume-score', methods=['POST'])
def resume_score():
    file = request.files['resume']
    job_description = request.form.get('job_description')
    result = parse_and_score_resume(file, job_description)
    log_decision("resume_score", result)
    return jsonify(result)

@app.route('/api/interview-invite', methods=['POST'])
def interview_invite():
    data = request.get_json()
    name = data.get('name')
    position = data.get('position')
    date = data.get('date')

    invitation_text = (
        f"Dear {name}, you are invited for a video interview for the {position} position on {date}.\n"
        "Please be ready to answer the following questions on camera."
    )

    questions = [
        "Tell us about yourself.",
        "Describe your experience relevant to this position.",
        "How do you handle challenges and pressure at work?",
        "What motivates you professionally?"
    ]

    result = {
        "invitation_text": invitation_text,
        "questions": questions
    }

    log_decision("interview_invite", result)
    return jsonify(result)


@app.route('/api/analyze-interview', methods=['POST'])
def interview_analysis():
    file = request.files['file']
    result = analyze_interview(file)
    log_decision("interview_analysis", result)
    return jsonify(result)

@app.route('/api/hiring-decision', methods=['POST'])
def hiring_decision():
    data = request.get_json()
    resume_score = float(data.get('resume_score', 0))
    interview_score = float(data.get('interview_score', 0))

    decision, reason = calculate_hiring_decision(resume_score, interview_score)

    return jsonify({
        "decision": decision,
        "reason": reason
    })

@app.route('/api/onboarding', methods=['POST'])
def onboarding_tasks():
    data = request.get_json()
    result = generate_onboarding_tasks(data)
    log_decision("onboarding_tasks", result)
    return jsonify(result)

@app.route('/api/leave-predict', methods=['POST'])
def leave_predict():
    reason = request.get_json().get('reason')
    result = {"approved": predict_leave_approval(reason)}
    log_decision("leave_predict", result)
    return jsonify(result)

@app.route('/api/logs', methods=['GET'])
def get_logs():
    with open('logs.json', 'r') as f:
        logs = json.load(f)
    return jsonify(logs)

if __name__ == '__main__':
    app.run(debug=True, port=5000)
