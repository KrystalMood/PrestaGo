<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionSkillModel extends Model
{
    use HasFactory;

    protected $table = 'competition_skills';
    protected $primaryKey = 'id';
    protected $fillable = [
        'competition_id',
        'skill_id',
        'importance_level',
    ];

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id', 'id');
    }

    public function skill()
    {
        return $this->belongsTo(SkillModel::class, 'skill_id', 'id');
    }
} 