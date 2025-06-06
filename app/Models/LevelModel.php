<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    use HasFactory;
    protected $table = 'level';
    protected $primaryKey = 'id';
    protected $fillable = [
        'level_kode',
        'level_nama',
    ];
}
