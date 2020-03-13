<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Lesson;
use App\Services\AnkaraCompEng\ThreeLeggedBot\ThreeLeggedBotService;
use App\Transformers\KeyboardTransformer;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Objects\UpdateCallback;

class LessonsCallback
{
    /** @var UpdateCallback $update */
    protected $update;

    public function __construct(UpdateCallback $update)
    {
        $this->update       = $update;
    }

    public function handle(int $id)
    {
        $lessons    = Lesson::where('level_id', $id)->get();

        if($lessons->count() < 1)
            throw new Exception('id not found');

        $reply_markup = app(ThreeLeggedBotService::class)->replyKeyboardMarkup([
            'inline_keyboard' => KeyboardTransformer::transform($lessons, auth()->user()),
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $response = app(ThreeLeggedBotService::class)->post('editMessageReplyMarkup', [
            'chat_id' => $this->update->getCallbackQuery()->getMessage()->getChat()->getId(),
            'message_id' => $this->update->getCallbackQuery()->getMessage()->getMessageId(),
            'reply_markup' => $reply_markup
        ]);

        return $response;
    }
}
