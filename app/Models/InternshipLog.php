<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'log_date',
        'log_type',
        'activities',
        'achievements',
        'challenges',
        'plan',
        'supervisor_comment',
        'status',
    ];

    protected $casts = [
        'log_date' => 'date',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}