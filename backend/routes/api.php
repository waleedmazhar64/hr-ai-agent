<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\HRProcessController;
use App\Http\Controllers\JobController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::get('/jobs', [JobController::class, 'index']);

    Route::post('/resume/upload', [ResumeController::class, 'upload']);

    Route::post('/interview/schedule', [InterviewController::class, 'schedule']);

    Route::post('/onboarding/task', [OnboardingController::class, 'assign']);
    Route::get('/onboarding/tasks', [OnboardingController::class, 'index']);

    Route::post('/leave/apply', [LeaveController::class, 'apply']);
    Route::get('/leave/status', [LeaveController::class, 'status']);

    Route::post('/meeting/schedule', [MeetingController::class, 'schedule']);
    Route::get('/meeting/list', [MeetingController::class, 'index']);


    Route::post('/hr/process', [HRProcessController::class, 'handle']);
    Route::post('/hr/video-analyze', [HRProcessController::class, 'analyzeVideo']);
    Route::post('/hr/leave', [HRProcessController::class, 'predictLeave']);
    Route::get('/hr/audit-logs', [HRProcessController::class, 'getAuditLogs']);
    Route::post('/hr/stats', [HRProcessController::class, 'stats']);
    Route::get('/hr/onboarding/{id}', [HRProcessController::class, 'getTask']);
});

