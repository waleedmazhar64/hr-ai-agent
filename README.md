# 🤖 AI HR Agent – Automating HR Workflows

AI HR Agent is a **full-stack, AI-powered Human Resources automation system** that streamlines **job posting, resume screening, interview analysis, onboarding task generation, and leave approval prediction** into a single, unified workflow.  
This project was developed as part of an MSc dissertation to explore **how effectively AI can automate end-to-end HR processes** while improving efficiency and decision-making.

## 🚀 Features

- ✅ **Job Posting & Management** – Admin can create, edit, and delete job openings.
- ✅ **Candidate Portal** – Candidates can submit resumes, receive interview invites, and record video interviews via webcam.
- ✅ **Resume Parsing & Scoring** – Python microservice uses NLP (TF-IDF + cosine similarity) to match resumes with job descriptions.
- ✅ **AI-Powered Interview Analysis** – Processes recorded video, analyzes voice tone, keywords, and gestures to generate an interview score.
- ✅ **Automated Hiring Decisions** – Weighted decision model automatically classifies candidates as “Hire” or “Reject.”
- ✅ **Dynamic Onboarding Task Generation** – Generates job-specific onboarding tasks for hired candidates.
- ✅ **Leave Approval Prediction** – Sentiment & keyword-based AI decision-making for employee leave requests.
- ✅ **Analytics Dashboard** – Admin view with charts visualizing hiring efficiency, time-to-hire, and decision audit logs.
- ✅ **Audit Logging** – All AI decisions are logged for research and transparency.
- ✅ **Universal Design** – Can be adapted to different industries (IT, healthcare, education, etc.).

---

## 🏗️ Architecture

The project follows a **three-tier architecture**:

- **Frontend:** Angular 17  
- **Backend:** Laravel 11 (API Gateway + Database + Workflow Logic)  
- **AI Microservices:** Python 3.10 (Flask, scikit-learn, Mediapipe, OpenCV, TextBlob)  
- **Database:** MySQL  
- **Video Processing:** MediaRecorder API (Frontend) + MoviePy (Python)

  ```mermaid
graph TD
A[Admin Creates Job] --> B[Candidate Submits Resume]
B --> C[Resume Sent to Python API]
C --> D[Resume Scored & Stored in DB]
D --> E[Interview Invitation Generated]
E --> F[Candidate Records & Uploads Video]
F --> G[Python Analyzes Video & Returns Score]
G --> H[Laravel Makes Final Hiring Decision]
H --> I[Onboarding Tasks Generated]
I --> J[Employee Dashboard Displays Tasks]
J --> K[Leave Requests Evaluated by AI]

## ⚙️ Installation

### 1️⃣ Clone Repository
      git clone https://github.com/waleedmazhar64/ai-hr-agent.git
      cd ai-hr-agent


### 2️⃣ Backend (Laravel)
      cd backend
      composer install
      cp .env.example .env
      php artisan key:generate
      php artisan migrate --seed
      php artisan serve

Backend will run at: http://127.0.0.1:8000

### 3️⃣ Frontend (Angular)
    cd ../frontend
    npm install
    ng serve

Frontend will run at: http://localhost:4200

### 4️⃣ Python AI Microservice
    pip install -r requirements.txt
    python app.py

Microservice will run at: http://127.0.0.1:5000
