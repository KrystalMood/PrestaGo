<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipFeedback extends Model
{
    use HasFactory;

    protected $table = 'internship_feedback';

    protected $fillable = [
        'internship_id',
        'overall_rating',
        'mentor_rating',
        'task_rating',
        'learning_rating',
        'environment_rating',
        'strengths',
        'improvements',
        'skills_gained',
        'recommendation',
        'additional_comments',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}