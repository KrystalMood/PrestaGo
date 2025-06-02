<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'photo',
        'level_id',
        'password',
        'nim',
        'nip',
        'program_studi_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'password' => 'hashed',
    ];
    public function level()
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'id');
    }

    public function program_studi()
    {
        return $this->belongsTo(StudyProgramModel::class, 'program_studi_id', 'id');
    }

    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }
    public function getRole()
    {
        return $this->level->level_kode;
    }
    
    public function skills()
    {
        return $this->belongsToMany(SkillModel::class, 'user_skills', 'user_id', 'skill_id')
                    ->withPivot('proficiency_level')
                    ->withTimestamps();
    }
    
    public function interests()
    {
        return $this->belongsToMany(InterestAreaModel::class, 'user_interests', 'user_id', 'interest_area_id')
                    ->withPivot('interest_level', 'normalized_score', 'self_assessment')
                    ->withTimestamps();
    }

    public function programStudi()
    {
        return $this->belongsTo(StudyProgramModel::class, 'program_studi_id', 'id');
    }

    public function internships()
    {
        return $this->hasMany(Internship::class, 'user_id', 'id');
    }
}
