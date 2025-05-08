<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSkillModel extends Model
{
    use HasFactory;

    protected $table = 'user_skills';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id',
        'skill_id',
        'proficiency_level',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function skill()
    {
        return $this->belongsTo(SkillModel::class, 'skill_id', 'id');
    }
} 