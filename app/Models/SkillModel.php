<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillModel extends Model
{
    use HasFactory;

    protected $table = 'skills';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'category',
    ];

    public function users()
    {
        return $this->belongsToMany(UserModel::class, 'user_skills', 'skill_id', 'user_id')
            ->withPivot('proficiency_level')
            ->withTimestamps();
    }

    public function competitions()
    {
        return $this->hasManyThrough(
            CompetitionModel::class,
            SubCompetitionModel::class,
            'skill_id', 
            'id', 
            'id', 
            'competition_id' 
        )->distinct()
          ->join('sub_competition_skills', 'sub_competition_skills.skill_id', '=', 'skills.id')
          ->join('sub_competitions', 'sub_competitions.id', '=', 'sub_competition_skills.sub_competition_id')
          ->where('skills.id', '=', $this->id);
    }
    
    public function subCompetitions()
    {
        return $this->belongsToMany(SubCompetitionModel::class, 'sub_competition_skills', 'skill_id', 'sub_competition_id')
            ->withPivot('importance_level', 'weight_value', 'criterion_type', 'ahp_priority')
            ->withTimestamps();
    }
} 