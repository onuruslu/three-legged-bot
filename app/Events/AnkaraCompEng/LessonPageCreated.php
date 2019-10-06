<?php

namespace App\Events\AnkaraCompEng;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LessonPageCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $lessonPage;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LessonPage $lessonPage)
    {
        $this->lessonPage   = $lessonPage;
    }

    public function getLessonPage(){
        return $this->lessonPage;
    }
}
