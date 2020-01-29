<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Commands;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Lesson;
use App\Services\AnkaraCompEng\ThreeLeggedBot\ThreeLeggedBotService as Telegram;
use App\Transformers\KeyboardTransformer;
use Telegram\Bot\Commands\Command;
use Exception;

/**
 * Class LessonsMenuCommand.
 */
class LessonsMenuCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'lessons';

    /**
     * @var string Command Description
     */
    protected $description = 'Seçilebilecek Dersleri Listeler';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $lessons    = Lesson::where('level_id', $arguments['id'])->get();

        if($lessons->count() < 1)
            throw new Exception('id not found');

        $reply_markup = app(Telegram::class)->replyKeyboardMarkup([
            'inline_keyboard' => KeyboardTransformer::transform($lessons, auth()->user()),
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $response = app(Telegram::class)->post('editMessageReplyMarkup', [
            'chat_id' => $this->getUpdate()->getCallbackQuery()->getMessage()->getChat()->getId(),
            'message_id' => $this->getUpdate()->getCallbackQuery()->getMessage()->getMessageId(),
            'reply_markup' => $reply_markup
        ]);

        return $response;
    }
}
