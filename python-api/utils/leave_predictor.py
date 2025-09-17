from textblob import TextBlob

def predict_leave_approval(reason):
    reason_lower = reason.lower()

    valid_keywords = [
        "sick", "ill", "medical", "doctor", "health",
        "family", "emergency", "funeral", "death",
        "personal", "rest", "mental", "tired", "wedding"
    ]

    for word in valid_keywords:
        if word in reason_lower:
            return {
                "approved": True,
                "reason": f"Leave approved based on keyword: '{word}' in your request."
            }

    sentiment = TextBlob(reason).sentiment.polarity
    if sentiment > 0.1:
        return {
            "approved": True,
            "reason": f"Leave approved based on positive sentiment in your message (polarity: {sentiment:.2f})."
        }

    return {
        "approved": False,
        "reason": "Leave not approved — request lacks justification or context."
    }

