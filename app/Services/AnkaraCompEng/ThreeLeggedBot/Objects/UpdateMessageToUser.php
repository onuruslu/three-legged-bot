<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Objects;

use Telegram\Bot\Objects\Update;
use App\Facades\ThreeLeggedBotFacade;

class UpdateMessageToUser extends Update
{
	public function handle() {
        if($this->getMessage()->getReplyToMessage()->getForwardFrom() === null)
            return false;
        
        return ThreeLeggedBotFacade::sendMessage([
            'chat_id' => $this->getMessage()->getReplyToMessage()->getForwardFrom()->getId(),
            'text' => $this->getMessage()->getText(),
        ]);
	}
}