<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks;

use App\Services\AnkaraCompEng\ThreeLeggedBot\Contracts\Callback;
use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Level;
use App\Services\AnkaraCompEng\ThreeLeggedBot\ThreeLeggedBotService;
use App\Transformers\KeyboardTransformer;
use Telegram\Bot\Objects\Update as RootUpdate;

class ClassesCallback implements Callback
{
    /** @var RootUpdate $update */
    protected $update;

    public function __construct(RootUpdate $update)
    {
        $this->update       = $update;
    }

    public function handle($dummy = null)
    {
        $reply_markup = app(ThreeLeggedBotService::class)->replyKeyboardMarkup([
            'inline_keyboard' => KeyboardTransformer::transform(Level::get(), auth()->user()),
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        if ( null === $this->update->getCallbackQuery() )
            return app(ThreeLeggedBotService::class)->sendMessage([
                'chat_id' => $this->update->getMessage()->getChat()->getId(),
                'text' => "Ders aldığınız sınıfı seçin",
                'reply_markup' => $reply_markup,
            ]);
        else
            return app(ThreeLeggedBotService::class)->post('editMessageReplyMarkup', [
                'chat_id' => $this->update->getCallbackQuery()->getMessage()->getChat()->getId(),
                'message_id' => $this->update->getCallbackQuery()->getMessage()->getMessageId(),
                'reply_markup' => $reply_markup
            ]);
    }
}
