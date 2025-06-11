<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company',
        'description',
        'start_date',
        'end_date',
        'position',
        'supervisor_name',
        'supervisor_contact',
        'user_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(InternshipLog::class);
    }

    public function certificates()
    {
        return $this->hasMany(InternshipCertificate::class);
    }

    public function feedback()
    {
        return $this->hasMany(InternshipFeedback::class);
    }
}