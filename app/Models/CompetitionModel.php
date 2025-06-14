<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    protected static function boot()
    {
        parent::boot();
        
        static::retrieved(function ($competition) {
            $competition->updateStatusBasedOnDates();
        });
    }
    
    public function updateStatusBasedOnDates()
    {
        $now = Carbon::now();
        $registrationStart = Carbon::parse($this->registration_start);
        $registrationEnd = Carbon::parse($this->registration_end);
        $competitionStart = Carbon::parse($this->start_date);
        $competitionEnd = Carbon::parse($this->end_date);
        
        $newStatus = $this->status;
        
        if ($now->lt($registrationStart)) {
            $newStatus = 'upcoming';
        } elseif (($now->gte($registrationStart) && $now->lte($registrationEnd)) || 
                  ($now->gte($competitionStart) && $now->lte($competitionEnd))) {
            $newStatus = 'active';
        } elseif ($now->gt($competitionEnd)) {
            $newStatus = 'completed';
        }
        
        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
        }
        
        return $this;
    }

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
        return SkillModel::whereHas('subCompetitions', function($query) {
            $query->whereHas('competition', function($q) {
                $q->where('id', $this->id);
            });
        });
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
        return $query->where('status', 'active');
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
            'ongoing' => 'Aktif',
            'closed' => 'Ditutup'
        ];
        
        return $statuses[$this->status] ?? ucfirst($this->status);
    }
} 