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
        return $this->belongsToMany(SkillModel::class, 'competition_skills', 'competition_id', 'skill_id')
            ->withPivot('importance_level')
            ->withTimestamps();
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

    public function getRequirementsHtmlAttribute()
    {
        if (empty($this->requirements)) {
            return null;
        }
        
        $lines = explode("\n", $this->requirements);
        $htmlLines = [];
        
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            if (!empty($trimmedLine)) {
                $htmlLines[] = "<li>{$trimmedLine}</li>";
            }
        }
        
        if (empty($htmlLines)) {
            return null;
        }
        
        return '<ul class="list-disc pl-5 space-y-1">' . implode('', $htmlLines) . '</ul>';
    }
    
    public function getLevelFormattedAttribute()
    {
        if (empty($this->level)) {
            return null;
        }
        
        $levels = [
            'international' => 'International',
            'national' => 'National',
            'regional' => 'Regional',
            'provincial' => 'Provincial',
            'university' => 'University'
        ];
        
        return $levels[$this->level] ?? ucfirst($this->level);
    }
} 