# HR AI Agent — Workflow integration prototype

An MSc Artificial Intelligence project exploring HR workflow orchestration across a Laravel backend and a Python/Flask service. The repository demonstrates service integration and text-similarity matching, with explicit academic prototype limits.

## Start here

| Review question | Source |
| --- | --- |
| How are application workflows exposed? | [Laravel API routes](backend/routes/api.php) |
| How are Python endpoints organised? | [Flask service](python-api/app.py) |
| How are PDF/DOCX files matched to a job description? | [TF-IDF and cosine similarity](python-api/utils/resume_parser.py) |
| What does interview analysis currently implement? | [Interview utilities](python-api/utils/interview_analyzer.py) |
| How are onboarding and leave responses generated? | [Python utilities](python-api/utils) |

## Components

- `backend/`: Laravel account, job, resume, interview, onboarding, leave and meeting routes.
- `python-api/`: Flask endpoints and matching/workflow utilities.
- `frontend`: a Git submodule/gitlink entry, not ordinary checked-in Angular source. This checkout has no root `.gitmodules` file, so a normal clone does not provide a reproducible frontend checkout.

## Python endpoint map

| Method | Path |
| --- | --- |
| POST | `/api/resume-score` |
| POST | `/api/interview-invite` |
| POST | `/api/analyze-interview` |
| POST | `/api/hiring-decision` |
| POST | `/api/onboarding` |
| POST | `/api/leave-predict` |
| GET | `/api/logs` |

The resume endpoint accepts a `resume` file and `job_description` form field. It extracts PDF/DOCX text and calculates TF-IDF cosine similarity. A similarity score is not a validated measure of candidate suitability.

## Local development

For the backend, install its locked Composer dependencies, configure a local `.env` and disposable database, and run migrations before serving it. Configure the Python service URL in the backend integration code.

For the Python service, use an isolated environment with versions compatible with the packages in [requirements.txt](python-api/requirements.txt):

```bash
cd python-api
python -m venv .venv
source .venv/bin/activate
python -m pip install -r requirements.txt
python app.py
```

The script starts a development server on port 5000. Requirements are not version-pinned; reproducibility needs further work. Do not expose the development server or logs publicly.

## Scope and next engineering work

Interview/speech-analysis functionality includes placeholders. The complete stack was not executed during this documentation review, and the frontend checkout needs repair before a reproducible full demo.

Useful next work: dependency pinning, API-contract tests, robust file validation, a reproducible frontend checkout and evaluation of matching behaviour using synthetic inputs. Human review, privacy controls and substantive validation would be prerequisites for any real HR deployment.

This project is an academic integration prototype, not a validated automated hiring system.

## Author

[Waleed Mazhar — Senior Software Engineer](https://github.com/waleedmazhar64)
