<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationModel extends Model
{
    use HasFactory;

    protected $table = 'recommendations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'competition_id',
        'recommendation_score',
        'status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id', 'id');
    }
} 