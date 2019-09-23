<?php

namespace App\Events\AnkaraCompEng;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Announcement;

class AnnouncementUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $announcement;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement     = $announcement;
    }

    public function getAnnouncement(){
        return $this->announcement;
    }
}
