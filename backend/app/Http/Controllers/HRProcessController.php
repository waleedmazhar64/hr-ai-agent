<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\JobPosting; 
use Carbon\Carbon;

class HRProcessController extends Controller
{
    public function handle(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'resume' => 'required|file',
        ]);

        $resume = $request->file('resume');
        $path = $resume->store('resumes', 'public');

        
        $job = JobPosting::where('title', $request->position)->first();
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        $candidate = Candidate::create([
            'name' => $request->name,
            'position' => $request->position,
            'resume_path' => $path,
            'submitted_at' => now()
        ]);

        // Resume Score
        $resumeScoreResponse = Http::attach(
            'resume', file_get_contents(storage_path("app/public/$path")), $resume->getClientOriginalName()
        )->post('http://127.0.0.1:5000/api/resume-score', [
            'job_description' => $job->description
        ])->json();

        $resumeScore = $resumeScoreResponse['score'] ?? 0;
        $candidate->update(['resume_score' => $resumeScore]);

        $this->log($candidate->id, 'Resume Scored', $resumeScoreResponse);

        $interviewThreshold = 10;

        if ($candidate->resume_score >= $interviewThreshold) {
            $invite = Http::post('http://127.0.0.1:5000/api/interview-invite', [
                'name' => $candidate->name,
                'date' => now()->addDays(1)->toDateString(),
                'position' => $candidate->position
            ])->json();

            $this->log($candidate->id, 'Interview Invite Generated', $invite);

            return response()->json([
                'resume_score' => $candidate->resume_score,
                'interview_invite' => $invite['invitation_text'],
                'interview_questions' => $invite['questions'],
                'candidate_id' => auth()->user()->id
            ]);
        }

        return response()->json([
            'resume_score' => $candidate->resume_score,
            'candidate_id' => auth()->user()->id
        ]);

    }

    public function analyzeVideo(Request $request)
    {
        $request->validate([
            
            'candidate_id' => 'required|exists:candidates,id'
        ]);

        $candidate = Candidate::findOrFail($request->candidate_id);
        $video = $request->file('video');
        $filename = uniqid('interview_') . '.' . $video->getClientOriginalExtension();
        $destinationPath = storage_path('app/public/videos');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $video->move($destinationPath, $filename);
        $fullPath = $destinationPath . '/' . $filename;

        

        $interviewScore = Http::attach(
            'file', file_get_contents($fullPath), $filename
        )->post('http://127.0.0.1:5000/api/analyze-interview')->json();

        $scoreValue = $interviewScore['score'] ?? 0;
        $candidate->update([
            'interview_score' => $scoreValue,
            'interview_analyzed_at' => now()
        ]);

        $this->log($candidate->id, 'Interview Analyzed', $interviewScore);

        // Hiring Decision
        $decision = Http::post('http://127.0.0.1:5000/api/hiring-decision', [
            'resume_score' => $candidate->resume_score,
            'interview_score' => $scoreValue
        ])->json();

        $candidate->update(['decision' => $decision['decision']]);
        $this->log($candidate->id, 'Hiring Decision', $decision);

        // Onboarding
        $onboarding = [];
        if ($decision['decision'] === 'Hire') {
            $onboarding = Http::post('http://127.0.0.1:5000/api/onboarding', [
                'job_title' => $candidate->position
            ])->json();

            $this->log($candidate->id, 'Onboarding Tasks', $onboarding);
        }

        return response()->json([
            'interview_score' => $scoreValue,
            'decision' => $decision['decision'],
            'onboarding_tasks' => $onboarding ?? []
        ]);
    }

    public function predictLeave(Request $request)
    {
        $request->validate([
            'reason' => 'required|string',
            'candidate_id' => 'required|exists:candidates,id'
        ]);

        $response = Http::post('http://127.0.0.1:5000/api/leave-predict', [
            'reason' => $request->reason
        ])->json();

        $this->log($request->candidate_id, 'Leave Prediction', $response);
        return response()->json($response);
    }

    protected function log($candidateId, $action, $response)
    {
        AuditLog::create([
            'candidate_id' => $candidateId,
            'action' => $action,
            'response' => json_encode($response),
            'logged_at' => now()
        ]);
    }

    public function getTask($id)
    {
        $tasks = AuditLog::where('candidate_id', '=', $id)->where('action', '=', 'Onboarding Tasks')->first();

        return response()->json(json_decode($tasks->response, true));
            //return response()->json($tasks);
    }

    public function getAuditLogs()
    {
        $candidates = Candidate::all()->map(function ($c) {
            return [
                'name' => $c->name,
                'resume_score' => $c->resume_score,
                'interview_score' => $c->interview_score,
                'decision' => $c->decision,
                'days_to_hire' => $c->submitted_at && $c->interview_analyzed_at
                    ? Carbon::parse($c->submitted_at)->diffInDays($c->interview_analyzed_at)
                    : null
            ];
        });

        return response()->json(['logs' => $candidates]);
    }

    public function stats()
    {
        $candidates = Candidate::all()->map(function ($candidate) {
            $submittedAt = $candidate->submitted_at;
            $interviewedAt = $candidate->interview_analyzed_at;

            return [
                'name' => $candidate->name,
                'days_to_hire' => ($submittedAt && $interviewedAt)
                    ? Carbon::parse($submittedAt)->diffInDays($interviewedAt)
                    : null
            ];
        });

        return response()->json(['candidates' => $candidates]);
    }
}

