<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'name', 'position', 'resume_path',
        'resume_score', 'interview_score', 'decision',
        'submitted_at', 'interview_analyzed_at'
    ];

    public function logs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
