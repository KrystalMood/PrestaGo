<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionFeedback extends Model
{
    use HasFactory;

    protected $table = 'competition_feedback';

    protected $fillable = [
        'user_id',
        'competition_id',
        'overall_rating',
        'organization_rating',
        'judging_rating',
        'learning_rating',
        'materials_rating',
        'strengths',
        'improvements',
        'skills_gained',
        'recommendation',
        'additional_comments',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class);
    }
}