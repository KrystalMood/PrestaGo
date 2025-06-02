<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttachmentModel extends Model
{
    use HasFactory;

    protected $table = 'attachments';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'achievement_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function achievement()
    {
        return $this->belongsTo(AchievementModel::class, 'achievement_id', 'id');
    }
} 