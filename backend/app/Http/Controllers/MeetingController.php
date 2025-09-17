<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function schedule(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'date' => 'required|date',
            'participants' => 'required|array'
        ]);

        $meeting = Meeting::create([
            'title' => $request->title,
            'date' => $request->date,
            'participants' => json_encode($request->participants),
            'notes' => $request->notes ?? null
        ]);

        return response()->json($meeting);
    }

    public function index()
    {
        return Meeting::all(); // Optional: filter by user
    }

}
