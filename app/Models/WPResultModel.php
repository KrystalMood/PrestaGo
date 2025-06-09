<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WPResultModel extends Model
{
    use HasFactory;
    
    protected $table = 'wp_calculation_results';
    
    protected $fillable = [
        'user_id',
        'competition_id',
        'calculation_type',
        'final_score',
        'vector_s',
        'vector_v',
        'relative_preference',
        'rank',
        'calculation_details',
        'calculated_at'
    ];
    
    protected $casts = [
        'final_score' => 'float',
        'vector_s' => 'float',
        'vector_v' => 'float',
        'relative_preference' => 'float',
        'rank' => 'integer',
        'calculation_details' => 'json',
        'calculated_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
    
    public function competition()
    {
        return $this->belongsTo(CompetitionModel::class, 'competition_id');
    }
    
    public function recommendation()
    {
        return $this->hasOne(RecommendationModel::class, 'wp_result_id');
    }
}
