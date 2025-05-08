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
        return $this->belongsToMany(CompetitionModel::class, 'competition_skills', 'skill_id', 'competition_id')
            ->withPivot('importance_level')
            ->withTimestamps();
    }
} 