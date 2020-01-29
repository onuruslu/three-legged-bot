<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Commands;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Lesson;
use App\Services\AnkaraCompEng\ThreeLeggedBot\ThreeLeggedBotService as Telegram;
use App\Transformers\KeyboardTransformer;
use Telegram\Bot\Commands\Command;
use Exception;

/**
 * Class SelectLessonCommand.
 */
class SelectLessonCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'selectLesson';

    /**
     * @var string Command Description
     */
    protected $description = 'Ders seçer';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $lesson    = Lesson::find($arguments['id']);

        if(!$lesson)
            throw new Exception('lesson is not exists');

        if($lesson->users()->where('id', auth()->user()->id)->exists())
            auth()->user()->lessons()->detach($lesson);
        else
            auth()->user()->lessons()->attach($lesson);

        $reply_markup = app(Telegram::class)->replyKeyboardMarkup([
            'inline_keyboard' => KeyboardTransformer::transform($lesson->level->lessons, auth()->user()),
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
