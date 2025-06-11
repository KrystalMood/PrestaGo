<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AHPResultModel extends Model
{
    use HasFactory;
    
    protected $table = 'ahp_calculation_results';
    
    protected $fillable = [
        'user_id',
        'competition_id',
        'calculation_type',
        'final_score',
        'consistency_ratio',
        'is_consistent',
        'calculation_details',
        'calculated_at'
    ];
    
    protected $casts = [
        'final_score' => 'float',
        'consistency_ratio' => 'float',
        'is_consistent' => 'boolean',
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
        return $this->hasOne(RecommendationModel::class, 'ahp_result_id');
    }
} 