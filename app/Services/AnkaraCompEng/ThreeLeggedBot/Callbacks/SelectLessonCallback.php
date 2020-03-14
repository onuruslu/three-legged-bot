<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Lesson;
use App\Services\AnkaraCompEng\ThreeLeggedBot\ThreeLeggedBotService;
use App\Transformers\KeyboardTransformer;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Objects\UpdateCallback;
use Exception;

class SelectLessonCallback
{
    /** @var UpdateCallback $update */
    protected $update;

    public function __construct(UpdateCallback $update)
    {
        $this->update       = $update;
    }

    public function handle(int $id)
    {
        $lesson    = Lesson::find($id);

        if(!$lesson)
            throw new Exception('lesson is not exists');

        if($lesson->users()->where('id', auth()->user()->id)->exists())
            auth()->user()->lessons()->detach($lesson);
        else
            auth()->user()->lessons()->attach($lesson);

        $reply_markup = app(ThreeLeggedBotService::class)->replyKeyboardMarkup([
            'inline_keyboard' => KeyboardTransformer::transform($lesson->level->lessons, auth()->user()),
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
