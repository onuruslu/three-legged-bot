<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Objects;

use Telegram\Bot\Objects\Update;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Contracts\HandleableUpdate;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Handlers\WebhookHandler;
use Exception;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks\ClassesCallback;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks\LessonsCallback;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks\SelectLessonCallback;

class UpdateCallback extends Update implements HandleableUpdate
{
    CONST CALLBACK_CLASSES  = [
        'classes'       => ClassesCallback::class,
        'lessons'       => LessonsCallback::class,
        'selectLesson'  => SelectLessonCallback::class,
    ];

	protected $callbackData;

	public function __construct($body) {
		parent::__construct($body);

		$this->parseCallback();
	}

	public function handle() {
		$id			= $this->callbackData['id'] ?? null;
		$callback	= $this->callbackData['callback'];

		return app(self::CALLBACK_CLASSES[$callback], ['update' => $this])->handle($id);
	}

	protected function parseCallback() {
        if (!preg_match(
        	'~^(?\'callback\'\w+?)(?:#(?\'id\'\d+?))??$~iU',
        	$this->getCallbackQuery()->get('data'),
        	$this->callbackData
        ))
            throw new Exception('incorrect format');

        if ( !array_key_exists($this->callbackData['callback'], self::CALLBACK_CLASSES) )
            throw new Exception(
                'callback command not found'
                . json_encode(self::CALLBACK_CLASSES)
                . json_encode($this->callbackData)
            );
	}
}