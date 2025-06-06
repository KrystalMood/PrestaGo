<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyProgramModel extends Model
{
    use HasFactory;

    protected $table = 'study_programs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'code',
        'faculty',
        'degree_level',
        'accreditation',
        'year_established',
        'description'
    ];
    
    protected $casts = [
    ];

    public function users()
    {
        return $this->hasMany(UserModel::class, 'program_studi_id', 'id');
    }
} 