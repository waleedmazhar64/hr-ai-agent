<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function schedule(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:users,id',
            'date' => 'required|date'
        ]);

        $interview = Interview::create([
            'candidate_id' => $request->candidate_id,
            'date' => $request->date,
            'status' => 'scheduled'
        ]);

        return response()->json($interview);
    }

}
