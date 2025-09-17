<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function assign(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'task' => 'required|string'
        ]);

        $task = OnboardingTask::create([
            'employee_id' => $request->employee_id,
            'task' => $request->task,
            'status' => 'pending'
        ]);

        return response()->json($task);
    }

    public function index()
    {
        return OnboardingTask::where('employee_id', auth()->id())->get();
    }

}
