<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'dosen_id',
        'competition_id',
        'rated_by_user_id',
        'activity_rating',
        'comments'
    ];

    public function lecturer()
    {
        return $this->belongsTo(UserModel::class, 'dosen_id', 'id');
    }

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id', 'id');
    }

    public function ratedBy()
    {
        return $this->belongsTo(UserModel::class, 'rated_by_user_id', 'id');
    }
} 