<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;
use Illuminate\Support\Facades\Http;

class ResumeController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'resume' => 'required|mimes:pdf,docx'
        ]);

        $path = $request->file('resume')->store('resumes', 'public');

        $resume = Resume::create([
            'user_id' => auth()->id(),
            'file_path' => $path
        ]);

        // Call Python ML microservice
        $response = Http::attach(
            'file',
            file_get_contents(storage_path("app/public/$path")),
            basename($path)
        )->post('http://localhost:5000/api/resume-score', [
            'resume_id' => $resume->id,
            'user_id' => auth()->id()
        ]);

        $resume->score = $response->json()['score'] ?? null;
        $resume->save();

        return response()->json(['resume' => $resume]);
    }

}
