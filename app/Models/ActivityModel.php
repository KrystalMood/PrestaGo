<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityModel extends Model
{
    use HasFactory;
    
    protected $table = 'activities';

    protected $fillable = [
        'icon_type',
        'message',
        'user_id',
        'causer',
        'subject',
        'action',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function getFormattedTimeAttribute(): string
    {
        $time = Carbon::parse($this->created_at);
        $now = Carbon::now();
        $diff = $time->diffInSeconds($now);

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' menit yang lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam yang lalu';
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return $days . ' hari yang lalu';
        } else {
            $months = floor($diff / 2592000);
            return $months . ' bulan yang lalu';
        }
    }
}
