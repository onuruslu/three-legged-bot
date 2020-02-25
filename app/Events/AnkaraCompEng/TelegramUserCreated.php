<?php

namespace App\Events\AnkaraCompEng;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;

class TelegramUserCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $newUser;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $newUser)
    {
        $this->newUser  = $newUser;
    }

    public function getNewUser()
    {
        return $this->newUser;
    }
}
