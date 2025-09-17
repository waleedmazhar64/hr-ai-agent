<?php

namespace App\Http\Controllers;

use App\Models\JobPosting; 
use Illuminate\Http\Request;

class JobController extends Controller
{
	public function index()
	{
	    return response()->json(JobPosting::all());
	}
	
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:job_postings',
            'description' => 'required'
        ]);

        $job = JobPosting::create($request->only('title', 'description'));
        return response()->json($job, 201);
    }
}

