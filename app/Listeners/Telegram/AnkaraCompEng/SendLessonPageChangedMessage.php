<?php

namespace App\Listeners\Telegram\AnkaraCompEng;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Facades\ThreeLeggedBotFacade;
use App\User;

class SendLessonPageChangedMessage
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $lessonPage             = $event->getLessonPage();
        $ThreeLeggedBotFacade   = new ThreeLeggedBotFacade;

        $users                  = $lessonPage->lesson->users;

        foreach($users as $user)
            $ThreeLeggedBotFacade->sendLessonPageDiff($user->telegram_id, $lessonPage);
    }
}
