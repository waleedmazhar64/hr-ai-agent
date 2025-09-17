def generate_invitation(name, date, position):
    return f"Dear {name},\n\nYou are invited to an interview for the position of {position} on {date}. Please confirm your availability.\n\nBest regards,\nHR Team"

def analyze_interview_response(text):
    text = text.lower()
    keywords = ['teamwork', 'communication', 'problem solving', 'initiative', 'leadership']
    match_score = sum(1 for word in keywords if word in text)

    label = "Strong" if match_score >= 4 else "Moderate" if match_score >= 2 else "Weak"

    return {
        "matched_keywords": [k for k in keywords if k in text],
        "score": match_score * 20,
        "label": label
    }

def make_hiring_decision(resume_score, interview_score):
    avg = (resume_score + interview_score) / 2
    if avg >= 70:
        return "Hire"
    elif avg >= 50:
        return "Consider"
    else:
        return "Reject"
