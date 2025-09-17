import docx2txt
import fitz  # PyMuPDF
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

def extract_text(file):
    filename = file.filename.lower()
    if filename.endswith('.docx'):
        return docx2txt.process(file)
    elif filename.endswith('.pdf'):
        text = ""
        with fitz.open(stream=file.read(), filetype="pdf") as doc:
            for page in doc:
                text += page.get_text()
        return text
    else:
        raise ValueError("Unsupported file format.")

def parse_and_score_resume(file, job_description):
    resume_text = extract_text(file)
    documents = [resume_text, job_description]
    vectorizer = TfidfVectorizer()
    try:
        tfidf_matrix = vectorizer.fit_transform(documents)
        score = cosine_similarity(tfidf_matrix[0:1], tfidf_matrix[1:2])[0][0]
    except:
        score = 0.0
    return {
        "score": round(score * 100, 2),
        "resume_text": resume_text
    }
