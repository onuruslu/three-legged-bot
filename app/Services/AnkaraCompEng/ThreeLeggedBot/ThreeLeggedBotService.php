<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot;

use Telegram;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;
use App\User;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Commands\StartCommand;
use App\Facades\ThreeLeggedBotFacade;
use Exception;

// TODO: message type detection and processing the message should be
// different classes
class ThreeLeggedBotService extends Api{

    public function __construct(){
        parent::__construct();

        foreach(config('telegram.commands') as $command)
            $this->addCommand($command);
    }

    protected function isCallback(Update $update)
    {
        return $update->getCallbackQuery() !== null;
    }

    protected function isPlainMessage(Update $update)
    {
        if($this->isCallback($update))
            return false;

        $text = $update->getMessage()->getText();

        return count( $this->getCommandBus()
            ->parseCommand($text) ) === 0;
    }

    protected function isSendedByAdmin(Update $update)
    {
        return in_array(
            $update->getMessage()->getFrom()->getId(),
            config('telegram.telegram_admin_ids')
        );
    }

    protected function isReplyToUser(Update $update)
    {
        return $update->getMessage()->getReplyToMessage() !== null;
    }

    protected function shouldItSendToUser(Update $update)
    {
        if( !$this->isPlainMessage($update) )
            return false;

        if( !$this->isSendedByAdmin($update) )
            return false;

        if( !$this->isReplyToUser($update) )
            return false;

        return true;
    }

    /**
     * Check update object for a command and process.
     *
     * @param Update $update
     */
    public function processCommand(Update $update)
    {
        self::login($update);

        if ($this->isCallback($update))
        {
            return $this->processCallback($update);
        }
        else if ($this->shouldItSendToUser($update))
        {
            return $this->processMessageToUser($update);
        }
        else if ($this->isPlainMessage($update))
        {
            return $this->processMessageToAdmin($update);
        }
        else
        {
            return parent::processCommand($update);
        }
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

    protected function processCallback(Update $update){
        if(!preg_match('~^(?\'callback\'\w+?)(?:#(?\'id\'\d+?))??$~iU', $update->getCallbackQuery()->get('data'), $output))
            throw new Exception('incorrect format');

        if(! isset($this->getCommands()[$output['callback']]))
            throw new Exception('callback command not found');

        return $this->getCommandBus()
                        ->execute(
                            $output['callback'],
                            isset($output['id']) ? ['id' => $output['id']] : null,
                            $update
                        );
    }

    protected function processMessageToAdmin(Update $update){

        foreach(config('telegram.telegram_admin_ids') as $adminId)
        {
            parent::forwardMessage([
                'chat_id' => $adminId,
                'from_chat_id' => $update->getMessage()->getChat()->getId(), 
                'message_id' => $update->getMessage()->getMessageId()
            ]);
        }
        
        return true;
    }

    protected function processMessageToUser(Update $update){
        return parent::sendMessage([
            'chat_id' => $update->getMessage()->getReplyToMessage()->getChat()->getId(),
            'text' => $update->getMessage()->getText(),
        ]);
    }
}