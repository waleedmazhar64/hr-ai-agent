import cv2
import mediapipe as mp
import tempfile
import speech_recognition as sr
import moviepy.editor as mp_editor

def analyze_interview(file):
    with tempfile.NamedTemporaryFile(delete=False, suffix='.mp4') as tmp:
        tmp.write(file.read())
        video_path = tmp.name

    gesture_score = extract_gestures(video_path)
    speech_score = analyze_speech(video_path)
    final_score = round((gesture_score + speech_score) / 2, 2)

    return {
        "interview_score": final_score,
        "gesture_score": gesture_score,
        "speech_score": speech_score,
        "verdict": "Pass" if final_score > 10 else "Fail"
    }

def extract_gestures(video_path):
    cap = cv2.VideoCapture(video_path)
    mp_face = mp.solutions.face_detection.FaceDetection()
    face_count = 0
    frame_count = 0

    while cap.isOpened():
        ret, frame = cap.read()
        if not ret:
            break
        frame_count += 1
        results = mp_face.process(cv2.cvtColor(frame, cv2.COLOR_BGR2RGB))
        if results.detections:
            face_count += 1

    cap.release()
    return round((face_count / frame_count) * 100, 2) if frame_count else 0

def analyze_speech(video_path):
    recognizer = sr.Recognizer()
    try:
        video = mp_editor.VideoFileClip(video_path)
        audio_path = video.audio.write_audiofile("temp_audio.wav", logger=None)
        with sr.AudioFile("temp_audio.wav") as source:
            audio = recognizer.record(source)
        text = recognizer.recognize_google(audio)

        keywords = ["team", "problem", "solution", "experience", "project"]
        score = sum(1 for word in keywords if word in text.lower())
        return min(score * 20, 100)
    except Exception as e:
        print("Speech error:", e)
        return 0


def interview_invite():
    data = request.json
    name = data.get('name')
    position = data.get('position')
    date = data.get('date')

    invitation_text = (
        f"Dear {name}, you are invited for an interview for the position of {position} on {date}.\n"
        "Please prepare to answer the following questions."
    )

    questions = [
        "Describe your previous work experience related to this role.",
        "What are your key strengths and how do they apply to this position?",
        "How do you handle pressure or tight deadlines?",
        "Why do you want to work with our company?"
    ]

    return jsonify({
        "invitation_text": invitation_text,
        "questions": questions
    })


def calculate_hiring_decision(resume_score, interview_score):
    threshold_resume = 0
    threshold_interview = 0

    if resume_score >= threshold_resume and interview_score >= threshold_interview:
        return "Hire", "Both resume and interview scores are strong."
    elif interview_score < threshold_interview:
        return "Reject", "Interview performance did not meet expectations."
    else:
        return "Reject", "Resume score is below required threshold."
