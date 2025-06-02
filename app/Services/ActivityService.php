<?php

namespace App\Services;

use App\Models\ActivityModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;

class ActivityService
{
    public static function log(
        string $iconType,
        string $message,
        string $action,
        ?string $subject = null,
        ?string $causer = null,
        ?UserModel $user = null
    ): ActivityModel {
        $userId = $user ? $user->id : (Auth::check() ? Auth::id() : null);

        return ActivityModel::create([
            'icon_type' => $iconType,
            'message' => $message,
            'action' => $action,
            'subject' => $subject,
            'causer' => $causer,
            'user_id' => $userId,
        ]);
    }

    public static function getLatest(int $limit = 5)
    {
        return ActivityModel::with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
