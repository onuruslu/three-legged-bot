<?php

namespace App\Listeners\Telegram\AnkaraCompEng;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAnnouncementCreatedMessage
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $announcement       = $event->getAnnouncement();
    }
}
