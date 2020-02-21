<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot;

use Telegram;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;
use App\User;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Commands\StartCommand;
use App\Facades\ThreeLeggedBotFacade;
use Exception;

class ThreeLeggedBotService extends Api{

	public function __construct(){
		parent::__construct();

    	foreach(config('telegram.commands') as $command)
    		$this->addCommand($command);
	}

    /**
     * Check update object for a command and process.
     *
     * @param Update $update
     */
    public function processCommand(Update $update)
    {
        self::login($update);

    	$callbackQuery = $update->getCallbackQuery();

        if ($callbackQuery !== null) {
        	if(!preg_match('~^(?\'callback\'\w+?)(?:#(?\'id\'\d+?))??$~iU', $callbackQuery->get('data'), $output))
        		throw new Exception('incorrect format');

        	if(! isset($this->getCommands()[$output['callback']]))
        		throw new Exception('callback command not found');

        	return $this->getCommandBus()
    						->execute(
    							$output['callback'],
    							isset($output['id']) ? ['id' => $output['id']] : null,
    							$update
    						);
        } else if (
            count($this->getCommandBus()->parseCommand(
                $update->getMessage()->getText()
            ) === 0
        ) {
                parent::forwardMessage([
                    'chat_id' => auth()->user()->telegram_id, 
                    'from_chat_id' => $update->getMessage()->getChat()->getId(), 
                    'message_id' => $update->getMessage()->getMessageId()
                ]);
            }
        else
            return parent::processCommand($update);
    }

    public function post($endpoint, array $params = [], $fileUpload = false){
    	return parent::post($endpoint, $params, $fileUpload);
    }

    protected static function login(Update $update)
    {
        if($update->getMessage())
            $telegramUser = $update->getMessage()->getFrom();
        else if($update->getCallbackQuery())
            $telegramUser = $update->getCallbackQuery()->getFrom();

        $user = User::where('telegram_id', $telegramUser->getId())->first();

        if( !$user )
            $user = ThreeLeggedBotFacade::createOrUpdateUser($telegramUser);
        
        \Auth::login($user);
    }
}