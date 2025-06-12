<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodModel extends Model
{
    use HasFactory;

    protected $table = 'periods';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function competitions()
    {
        return $this->hasMany(CompetitionModel::class, 'period_id', 'id');
    }
} 