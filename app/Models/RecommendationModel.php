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
        'match_score',
        'status',
        'recommended_by',
        'ahp_result_id',
        'wp_result_id',
        'recommendation_reason',
        'notified',
        'notified_at',
        'match_factors',
        'for_lecturer',
        'calculation_method'
    ];
    
    protected $casts = [
        'match_score' => 'float',
        'notified' => 'boolean',
        'notified_at' => 'datetime',
        'match_factors' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id', 'id');
    }
    
    public function ahpResult()
    {
        return $this->belongsTo(AHPResultModel::class, 'ahp_result_id');
    }
    
    public function wpResult()
    {
        return $this->belongsTo(WPResultModel::class, 'wp_result_id');
    }
} 