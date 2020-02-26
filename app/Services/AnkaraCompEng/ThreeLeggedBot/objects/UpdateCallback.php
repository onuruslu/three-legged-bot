<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Objects;

use Telegram\Bot\Objects\Update;

class UpdateCallback extends Update
{
	protected $callbackData;

	public function __construct(string $body) {
		parent::__construct($body);

		$this->parseCallback();
	}

	public function handle() {
		$id		= $this->callbackData['id'];
		$type	= $this->callbackData['type'];
		$params = null;

		if(!empty($id))
			$params	= ['id' => $id];

        return $this->getCommandBus()
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
        	$this->getCommands()[$this->callbackData['type']]
        ))
            throw new Exception('callback command not found');
	}
}