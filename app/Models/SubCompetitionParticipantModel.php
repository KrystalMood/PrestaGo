<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCompetitionParticipantModel extends Model
{
    use HasFactory;

    protected $table = 'sub_competition_participants';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'sub_competition_id',
        'user_id',
        'team_name',
        'status',
    ];

    public function subCompetition()
    {
        return $this->belongsTo(SubCompetitionModel::class, 'sub_competition_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
} 