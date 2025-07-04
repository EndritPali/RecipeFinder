<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ResetRequestSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $email;
    public $last_password;

    public function __construct($user, $email, $last_password)
    {
        $this->user = $user;
        $this->email = $email;
        $this->last_password = $last_password;
    }

    public function broadcastOn()
    {
        return new Channel('password-resets');
    }

    public function broadcastAs()
    {
        return 'PasswordResetRequested';
    }
}
