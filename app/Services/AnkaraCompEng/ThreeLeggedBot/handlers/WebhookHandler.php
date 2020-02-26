<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Handlers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Utils\CommonUtils;

class WebhookHandler extends Api
{
	protected function isCallback(array $body)
	{
		return array_key_exists('callback_query', $body);
	}

	protected function isChatMessage(array $body)
	{
		if($this->isCallback($body))
			return false;

		if(! CommonUtils::array_multi_key_exists(['message', 'text'], $body))
			return false;

		$text = $body['message']['text'];

		// check it's not a command
		return count( $this->getCommandBus()
			->parseCommand($text) ) === 0;
	}

	protected function isSendedByAdmin(array $body)
	{
		if(! CommonUtils::array_multi_key_exists(['message', 'from', 'id'], $body))
			return false;

		return in_array(
			$body['message']['from']['id'],
			config('telegram.telegram_admin_ids')
		);
	}

	protected function isReply(array $body)
	{
		return CommonUtils::array_multi_key_exists(['message', 'reply_to_message'], $body);
	}

	public function shouldItSendToUser(array $body)
	{
        if( !$this->isChatMessage($body) )
            return false;

        if( !$this->isSendedByAdmin($body) )
            return false;

        if( !$this->isReply($body) )
            return false;

        return true;
	}

	public function shouldItSendToAdmin(array $body)
	{
		if( !$this->shouldItSendToUser($body) )
			return false;

        if( !$this->isChatMessage($body) )
            return false;
	}

	public function getWebhookUpdates()
	{
		$body = json_decode(file_get_contents('php://input'), true);

		if($this->isCallback($body)) {
			;
		}
		else if($this->shouldItSendToUser($body)) {
			;
		}
		else if($this->shouldItSendToAdmin($body)) {
			;
		}
		else {
			return parent::processCommand($update);
		}

		return new Update($body);
	}

?>