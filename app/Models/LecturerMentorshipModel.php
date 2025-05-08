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
    ];

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id', 'id');
    }

    public function lecturer()
    {
        return $this->belongsTo(UserModel::class, 'dosen_id', 'id');
    }
} 