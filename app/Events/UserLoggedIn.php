<?php

namespace App\Events;

use App\Models\UserModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    
    /**
     * Create a new event instance.
     */
    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }
}