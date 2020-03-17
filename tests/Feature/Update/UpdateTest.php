<?php

namespace Tests\Feature\Update;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Handlers\WebhookHandler;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Objects\Update;
use Tests\Feature\DemoFile;
use App\User;
use Telegram\Bot\Objects\User as TelegramUser;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UpdateTest extends FeatureTestCase
{
	CONST DEMO_FILE_PATH	= [__DIR__, 'DemoRequest', 'start_command.json'];

	public function testFromReceivingUpdateToUsingThreeleggedbotfacade() : void
	{
		// message parsing
		$demoFile	= new DemoFile(...self::DEMO_FILE_PATH);
		$demoFile->render();
		$request	= $demoFile->getParsedTemplate();

		// creating fake user
		$fakeUser	= factory(User::class)->make();
		$fakeUser->telegram_id			= $demoFile->properties->source_chat_id;
		$fakeUser->telegram_username	= $demoFile->properties->source_username;

		// mocking
		$mockedThreeLeggedBotFacade = $this->mock('alias:App\Facades\ThreeLeggedBotFacade');

		$mockedThreeLeggedBotFacade
			->shouldReceive('createOrUpdateUser')
			->withArgs(
				function($user) {
					return is_a($user, TelegramUser::class);
				}
			)
			->once()
			->andReturn($fakeUser);

		$mockedWebHookHandler = $this->mock(WebhookHandler::class)->makePartial();

		$mockedWebHookHandler
			->shouldReceive('handleCommand')
			->withArgs(
				function($update) {
					return is_a($update, Update::class);
				}
			)
			->once();

		$this->app->instance(WebhookHandler::class, $mockedWebHookHandler);

		// request control
		$response = $this->sendUpdate($request);

		$response->assertStatus(200);

		$response->assertSeeText('ok');
	}
}
