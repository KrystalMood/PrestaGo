<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerMentorshipModel extends Model
{
    use HasFactory;

    protected $table = 'lecturer_mentorships';
    protected $primaryKey = 'id';
    protected $fillable = [
        'competition_id',
        'dosen_id',
        'status',
        'notes',
        'average_rating',
        'rating_count'
    ];

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id', 'id');
    }

    public function lecturer()
    {
        return $this->belongsTo(UserModel::class, 'dosen_id', 'id');
    }
    
    public function ratings()
    {
        return $this->hasMany(LecturerRating::class, 'dosen_id', 'dosen_id')
                    ->where('competition_id', $this->competition_id);
    }

    public function updateAverageRating()
    {
        $ratings = $this->ratings()->pluck('activity_rating');
        $this->rating_count = $ratings->count();
        $this->average_rating = $ratings->count() > 0 ? $ratings->avg() : null;
        $this->save();
    }
} 