<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Objects;

use Telegram\Bot\Objects\Update;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Contracts\HandleableUpdate;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Handlers\WebhookHandler;
use Exception;

class UpdateCallback extends Update implements HandleableUpdate
{
	protected $callbackData;

	public function __construct($body) {
		parent::__construct($body);

		$this->parseCallback();
	}

	public function handle() {
		$id		= $this->callbackData['id'] ?? null;
		$type	= $this->callbackData['type'];
		$params = null;

		if(!empty($id))
			$params	= ['id' => $id];

        return app(WebhookHandler::class)->getCommandBus()
                    ->execute(
                        $type,
                        $params,
                        $this
                    );
	}

	protected function parseCallback() {
        if(!preg_match(
        	'~^(?\'type\'\w+?)(?:#(?\'id\'\d+?))??$~iU',
        	$this->getCallbackQuery()->get('data'),
        	$this->callbackData
        ))
            throw new Exception('incorrect format');

        if( !isset(
        	app(WebhookHandler::class)->getCommands()[$this->callbackData['type']]
        ))
            throw new Exception(
                'callback command not found'
                . json_encode(app(WebhookHandler::class)->getCommands())
                . json_encode($this->callbackData)
            );
	}
}