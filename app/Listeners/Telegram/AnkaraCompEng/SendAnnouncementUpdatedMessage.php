<?php

namespace App\Listeners\Telegram\AnkaraCompEng;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Facades\ThreeLeggedBotFacade;
use App\User;

class SendAnnouncementUpdatedMessage
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

        $users                  = User::get();

        foreach($users as $user)
            $ThreeLeggedBotFacade->sendAnnouncement($user->telegram_id, $announcement);
    }
}
