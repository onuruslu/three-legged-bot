<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Objects;

use Telegram\Bot\Objects\Update as RootUpdate;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Handlers\WebhookHandler;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Contracts\HandleableUpdate;

class Update extends RootUpdate implements HandleableUpdate
{
	public function handle() {
		return app(WebhookHandler::class)->handleCommand($this);
	}
}