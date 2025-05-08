<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionParticipantModel extends Model
{
    use HasFactory;

    protected $table = 'competition_participants';
    protected $primaryKey = 'id';
    protected $fillable = [
        'competition_id',
        'user_id',
        'status',
        'notes',
    ];

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
} 