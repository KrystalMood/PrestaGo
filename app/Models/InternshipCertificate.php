<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'certificate_type',
        'issue_date',
        'file_path',
        'file_name',
        'file_type',
        'description',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}