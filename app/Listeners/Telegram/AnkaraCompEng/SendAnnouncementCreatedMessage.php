<?php

namespace App\Listeners\Telegram\AnkaraCompEng;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Facades\ThreeLeggedBotFacade;

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
        $announcement           = $event->getAnnouncement();
        $ThreeLeggedBotFacade   = new ThreeLeggedBotFacade;

        $ThreeLeggedBotFacade->sendAnnouncement('723019950', $announcement);
    }
}
