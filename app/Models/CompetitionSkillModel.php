<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        if (!\Illuminate\Support\Facades\Schema::hasTable('competition_skills')) {
            $this->setTable('non_existent_table'); 
            $this->exists = false; 
        }
    }

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id', 'id');
    }

    public function skill()
    {
        return $this->belongsTo(SkillModel::class, 'skill_id', 'id');
    }
} 