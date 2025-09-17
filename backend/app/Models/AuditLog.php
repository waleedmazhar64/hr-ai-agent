<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['candidate_id', 'action', 'response', 'logged_at'];
    public $timestamps = false;
}
