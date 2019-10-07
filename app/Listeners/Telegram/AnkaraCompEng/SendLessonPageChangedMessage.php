<?php

namespace App\Listeners\Telegram\AnkaraCompEng;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Facades\ThreeLeggedBotFacade;

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

        $ThreeLeggedBotFacade->sendLessonPageDiff('723019950', $lessonPage);
    }
}
