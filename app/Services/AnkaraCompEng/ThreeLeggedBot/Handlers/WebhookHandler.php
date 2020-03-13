<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Handlers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update as RootUpdate;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Utils\CommonUtils;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Objects\UpdateCallback;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Objects\UpdateMessageToUser;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Objects\UpdateMessageToAdmin;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Objects\Update;
use App\User;
use App\Facades\ThreeLeggedBotFacade;

class WebhookHandler extends Api
{
    public function __construct()
    {
        parent::__construct();

        foreach(config('telegram.commands') as $command)
            $this->addCommand($command);
    }

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
        if( !$this->isChatMessage($body) )
            return false;

        return true;
	}

    protected static function login(RootUpdate $update)
    {
        if($update->getMessage())
            $telegramUser = $update->getMessage()->getFrom();
        else if($update->getCallbackQuery())
            $telegramUser = $update->getCallbackQuery()->getFrom();

        $user = User::where('telegram_id', $telegramUser->getId())->first();

        if( !$user ){
            $user = ThreeLeggedBotFacade::createOrUpdateUser($telegramUser);
        }
        
        \Auth::login($user);
    }

	public function getWebhookUpdates(string $rawPost = null)
	{
		if(empty($rawPost))
			$rawPost	= file_get_contents('php://input');

		$body = json_decode($rawPost, true);

    	logger(json_encode($body));

		if($this->isCallback($body)) {
			$update		= new UpdateCallback($body);
		}
		else if($this->shouldItSendToUser($body)) {
			$update		= new UpdateMessageToUser($body);
		}
		else if($this->shouldItSendToAdmin($body)) {
			$update		= new UpdateMessageToAdmin($body);
		}
		else {
			$update		= new Update($body);
		}

		self::login($update);

		return $update;
	}

	public function handleCommand(RootUpdate $update)
	{
		return $this->processCommand($update);
	}
}

?>