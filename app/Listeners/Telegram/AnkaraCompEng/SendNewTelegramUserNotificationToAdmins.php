<?php

namespace App\Listeners\Telegram\AnkaraCompEng;

use App\Events\AnkaraCompEng\TelegramUserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Facades\ThreeLeggedBotFacade;

class SendNewTelegramUserNotificationToAdmins
{
    /**
     * Handle the event.
     *
     * @param  TelegramUserCreated  $event
     * @return void
     */
    public function handle(TelegramUserCreated $event)
    {

        if(!$event->getNewUser()->telegram_id)
            return;

        $admins     = User::whereIn('telegram_id', config('telegram.telegram_admin_ids'))->get();

        foreach($admins as $admin) {
            ThreeLeggedBotFacade::sendMessage(
                $admin->telegram_id,
                view('three-legged-bot.new-user-notification', $event->getNewUser())->render()
            );
        }
    }
}
