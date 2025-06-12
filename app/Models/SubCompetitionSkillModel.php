<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCompetitionSkillModel extends Model
{
    use HasFactory;

    protected $table = 'sub_competition_skills';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sub_competition_id',
        'skill_id',
        'importance_level',
        'weight_value',
        'criterion_type',
        'ahp_priority'
    ];

    public function subCompetition()
    {
        return $this->belongsTo(SubCompetitionModel::class, 'sub_competition_id', 'id');
    }

    public function skill()
    {
        return $this->belongsTo(SkillModel::class, 'skill_id', 'id');
    }
}
