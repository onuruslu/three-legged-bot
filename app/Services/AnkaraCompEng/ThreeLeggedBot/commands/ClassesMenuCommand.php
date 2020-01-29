<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Commands;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Level;
use App\Services\AnkaraCompEng\ThreeLeggedBot\ThreeLeggedBotService as Telegram;
use App\Transformers\KeyboardTransformer;
use Telegram\Bot\Commands\Command;

/**
 * Class ClassesMenuCommand.
 */
class ClassesMenuCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'classes';

    /**
     * @var string Command Description
     */
    protected $description = 'Seçilebilecek Dersleri Listeler';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $reply_markup = app(Telegram::class)->replyKeyboardMarkup([
            'inline_keyboard' => KeyboardTransformer::transform(Level::get(), auth()->user()),
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        if(null === $this->getUpdate()->getCallbackQuery() )
            return app(Telegram::class)->sendMessage([
                'chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(),
                'text' => "Ders aldığınız sınıfı seçin",
                'reply_markup' => $reply_markup,
            ]);
        else
            return app(Telegram::class)->post('editMessageReplyMarkup', [
                'chat_id' => $this->getUpdate()->getCallbackQuery()->getMessage()->getChat()->getId(),
                'message_id' => $this->getUpdate()->getCallbackQuery()->getMessage()->getMessageId(),
                'reply_markup' => $reply_markup
            ]);
    }
}
