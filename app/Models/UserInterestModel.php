<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInterestModel extends Model
{
    use HasFactory;

    protected $table = 'user_interests';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'interest_area_id',
        'interest_level',
        'normalized_score',
        'self_assessment'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function interestArea()
    {
        return $this->belongsTo(InterestAreaModel::class, 'interest_area_id', 'id');
    }
} 