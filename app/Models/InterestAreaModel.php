<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestAreaModel extends Model
{
    use HasFactory;

    protected $table = 'interest_areas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'weight_value',
        'criterion_type',
        'ahp_priority',
        'display_order',
        'is_active'
    ];

    public function users()
    {
        return $this->belongsToMany(UserModel::class, 'user_interests', 'interest_area_id', 'user_id')
                    ->withPivot('interest_level')
                    ->withTimestamps();
    }
} 