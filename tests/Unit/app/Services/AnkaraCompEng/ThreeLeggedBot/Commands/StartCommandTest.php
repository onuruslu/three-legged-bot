<?php

namespace Tests\Unit\App\Services\AnkaraCompEng\ThreeLeggedBot\Commands;

use Tests\Unit\UnitTestCase;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks\ClassesCallback;
use App\Services\AnkaraCompEng\ThreeLeggedBot\ThreeLeggedBotService;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Objects\Update;
use App\Facades\ThreeLeggedBotFacade;
use Tests\Feature\DemoFile;
use App\User;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class StartCommandTest extends UnitTestCase
{
	public function testStartCommand() : void
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

		// creating fake user
		$fakeUser	= factory(User::class)->make();
		$fakeUser->telegram_id			= $demoFile->properties->source_chat_id;
		$fakeUser->telegram_username	= $demoFile->properties->source_username;

		// mocking ThreeLeggedBotFacade
		$mockedThreeLeggedBotFacade = $this->mock(ThreeLeggedBotFacade::class)->makePartial();

		$mockedThreeLeggedBotFacade
			->shouldReceive('sendMessage')
			->with(
				$fakeUser->telegram_id,
				view('three-legged-bot.welcome-message', $fakeUser)->render()
			)
			->once();

		// mocking ClassesCallback
		$mockedClassesCallback = $this->mock(ClassesCallback::class);

		$mockedClassesCallback
			->shouldReceive('handle')
			->withAnyArgs()
			->once();

		$this->app->bind(
			ClassesCallback::class,
			function($app, $args) use ($mockedClassesCallback, $demoFile)
			{
				$chatId	= $args['update']->getMessage()->getChat()->getId();
				$exceptedChatId	= $demoFile->properties->target_chat_id;

				$this->assertEquals(
					$exceptedChatId,
					$chatId
				);
				
				return $mockedClassesCallback;
			}
		);

		// calling fake update
		$requestArray	= json_decode($request, true);
		$update			= new Update($requestArray);
		$this->actingAs($fakeUser);

		$api			= app(ThreeLeggedBotService::class)
							->getCommandBus()
							->handler(
								$update->getMessage()->getText(),
								$update
							);
	}
}
