<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementModel extends Model
{
    use HasFactory;

    protected $table = 'achievements';
    protected $primaryKey = 'achievement_id';
    
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'competition_name',
        'competition_id',
        'type',
        'level',
        'date',
        'status',
        'verified_by',
        'verified_at',
        'rejected_reason',
        'period_id',
    ];

    protected $casts = [
        'date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function verifier()
    {
        return $this->belongsTo(UserModel::class, 'verified_by', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(AttachmentModel::class, 'achievement_id', 'achievement_id');
    }

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id', 'id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
} 