<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Commands;

use App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks\ClassesCallback;
use App\Transformers\KeyboardTransformer;
use Telegram\Bot\Commands\Command;
use App\User;
use Telegram\Bot\Objects\User as TelegramUser;
use App\Services\AnkaraCompEng\ThreeLeggedBot\ThreeLeggedBotService as Telegram;
use App\Facades\ThreeLeggedBotFacade;

/**
 * Class StartCommand.
 */
class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'start';

    /**
     * @var string Command Description
     */
    protected $description = 'Başlangıç için start komutu';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $welcomeMessage     = self::getWelcomeMessage(auth()->user());

        app(ThreeLeggedBotFacade::class)->sendMessage(
            auth()->user()->telegram_id,
            $welcomeMessage
        );

        $classesCallback    = app(ClassesCallback::class, ['update' => $this->getUpdate()]);

        return $classesCallback->handle();
    }

    protected static function getWelcomeMessage(User $user)
    {
        return view('three-legged-bot.welcome-message', $user)->render();
    }
}
