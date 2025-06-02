<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCompetitionModel extends Model
{
    use HasFactory;

    protected $table = 'sub_competitions';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'description',
        'competition_id',
        'category_id',
        'start_date',
        'end_date',
        'registration_start',
        'registration_end',
        'competition_date',
        'registration_link',
        'requirements',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_start' => 'date',
        'registration_end' => 'date',
        'competition_date' => 'date',
    ];

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id', 'id');
    }
    
    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'id');
    }

    public function participants()
    {
        return $this->hasMany(SubCompetitionParticipantModel::class, 'sub_competition_id', 'id');
    }
    
    public function skills()
    {
        return $this->belongsToMany(SkillModel::class, 'sub_competition_skills', 'sub_competition_id', 'skill_id')
            ->withPivot('importance_level', 'weight_value', 'criterion_type', 'ahp_priority')
            ->withTimestamps();
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