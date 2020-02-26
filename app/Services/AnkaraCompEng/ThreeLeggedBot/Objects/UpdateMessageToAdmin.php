<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Objects;

use Telegram\Bot\Objects\Update;
use App\Facades\ThreeLeggedBotFacade;

class UpdateMessageToAdmin extends Update
{
	public function handle() {

        foreach(config('telegram.telegram_admin_ids') as $adminId)
        {
            ThreeLeggedBotFacade::forwardMessage(
            	$adminId,
                $this->getMessage()->getChat()->getId(), 
                $this->getMessage()->getMessageId()
            );
        }

        return true;
	}
}