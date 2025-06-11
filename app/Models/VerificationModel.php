<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationModel extends Model
{
    use HasFactory;

    protected $table = 'verifications';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'status',
        'notes',
        'rejection_reason',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function verifier()
    {
        return $this->belongsTo(UserModel::class, 'verified_by', 'id');
    }

    public function documents()
    {
        return $this->hasMany(VerificationDocumentModel::class, 'verification_id', 'id');
    }
} 