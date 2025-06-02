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
        'start_date',
        'end_date',
        'registration_start',
        'registration_end',
        'competition_date',
        'registration_link',
        'status',
        'verified',
        'added_by',
        'period_id',
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
    
    public function skills()
    {
        return $this->hasManyThrough(
            SkillModel::class,
            SubCompetitionModel::class,
            'competition_id', 
            'id', 
            'id', 
            'id' 
        )->distinct()
          ->join('sub_competition_skills', 'skills.id', '=', 'sub_competition_skills.skill_id')
          ->where('sub_competitions.competition_id', '=', $this->id)
          ->select('skills.*', 'sub_competition_skills.importance_level');
    }

    public function participants()
    {
        return $this->hasMany(CompetitionParticipantModel::class, 'competition_id', 'id');
    }

    public function subCompetitions()
    {
        return $this->hasMany(SubCompetitionModel::class, 'competition_id', 'id');
    }

    public function recommendations()
    {
        return $this->hasMany(RecommendationModel::class, 'competition_id', 'id');
    }

    public function interests()
    {
        return $this->belongsToMany(InterestAreaModel::class, 'competition_interests', 'competition_id', 'interest_area_id')
                    ->withPivot('relevance_score', 'importance_factor', 'is_mandatory', 'minimum_level')
                    ->withTimestamps();
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

    public function getLevelFormattedAttribute()
    {
        if (empty($this->level)) {
            return null;
        }
        
        $levels = [
            'international' => 'Internasional',
            'national' => 'Nasional',
            'regional' => 'Regional',
            'provincial' => 'Provinsi',
            'university' => 'Universitas',
            'internal' => 'Internal'
        ];
        
        return $levels[$this->level] ?? ucfirst($this->level);
    }

    public function getStatusIndonesianAttribute()
    {
        if (empty($this->status)) {
            return null;
        }
        
        $statuses = [
            'active' => 'Aktif',
            'open' => 'Aktif',
            'upcoming' => 'Akan Datang',
            'completed' => 'Selesai',
            'ongoing' => 'Sedang Berlangsung',
            'closed' => 'Ditutup'
        ];
        
        return $statuses[$this->status] ?? ucfirst($this->status);
    }
} 