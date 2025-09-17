<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'file_path', 'parsed_data', 'score',
    ];

    protected $casts = [
        'parsed_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
