<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionModel extends Model
{
    use HasFactory;

    protected $table = 'competitions';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'description',
        'organizer',
        'level',
        'type',
        'start_date',
        'end_date',
        'registration_start',
        'registration_end',
        'competition_date',
        'registration_link',
        'requirements',
        'status',
        'verified',
        'added_by',
        'period_id',
        'category_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_start' => 'date',
        'registration_end' => 'date',
        'competition_date' => 'date',
        'verified' => 'boolean',
    ];

    public function addedBy()
    {
        return $this->belongsTo(UserModel::class, 'added_by', 'id');
    }

    public function period()
    {
        return $this->belongsTo(PeriodModel::class, 'period_id', 'id');
    }
    
    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'id');
    }

    public function skills()
    {
        return $this->belongsToMany(SkillModel::class, 'competition_skills', 'competition_id', 'skill_id')
            ->withPivot('importance_level')
            ->withTimestamps();
    }

    public function participants()
    {
        return $this->hasMany(CompetitionParticipantModel::class, 'competition_id', 'id');
    }

    public function recommendations()
    {
        return $this->hasMany(RecommendationModel::class, 'competition_id', 'id');
    }

    public function mentorships()
    {
        return $this->hasMany(LecturerMentorshipModel::class, 'competition_id', 'id');
    }

    public function achievements()
    {
        return $this->hasMany(AchievementModel::class, 'competition_id', 'id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }
} 