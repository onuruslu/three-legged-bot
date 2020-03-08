<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Objects;

use Telegram\Bot\Objects\Update;
use App\Facades\ThreeLeggedBotFacade;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Contracts\HandleableUpdate;

class UpdateMessageToUser extends Update implements HandleableUpdate
{
	public function handle() {
        if($this->getMessage()->getReplyToMessage()->getForwardFrom() === null)
            return false;
        
        return ThreeLeggedBotFacade::sendMessage(
            $this->getMessage()->getReplyToMessage()->getForwardFrom()->getId(),
            $this->getMessage()->getText()
        );
	}
}