<?php

namespace Tests\Unit\App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks;

use Tests\Unit\UnitTestCase;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks\ClassesCallback;
use Telegram\Bot\Objects\Update as RootUpdate;
use Tests\Feature\DemoFile;

class ClassesCallbackTest extends UnitTestCase
{

	/**
	 * It should accept "RootUpdate" object as an
	 * contructor parameter to work with StartCommand
	 * 
	 * @return void
	 */
	public function testInitalizingWithRootupdateObject() : void
	{
		// message parsing
		$demoFile	= new DemoFile(
			base_path(),
			'tests',
			'Feature',
			'Update',
			'DemoRequest',
			'start_command.json'
		);
		$demoFile->render();
		$request	= $demoFile->getParsedTemplate();

		$requestArray	= json_decode($request, true);
		$update			= new RootUpdate($requestArray);

		$classesCallback	= new ClassesCallback($update);

		$this->assertEquals(
			$demoFile->properties->target_chat_id,
			$update->getMessage()->getChat()->getId()
		);
	}
}
