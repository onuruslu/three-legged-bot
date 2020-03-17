<?php

namespace Tests\Feature\UpdateMessageToAdmin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase;
use Tests\Feature\DemoFile;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use Telegram\Bot\Objects\User as TelegramUser;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UpdateMessageToAdminTest extends FeatureTestCase
{
	use WithFaker;

	CONST DEMO_FILE_PATH 			= [__DIR__, 'DemoRequest', 'message_to_admin.json'];
	CONST ADMIN_IDS_CONFIG_PATH		= 'telegram.telegram_admin_ids';

	public function testSendMessageToAdmins() : void
	{
		// message parsing
		$demoFile	= new DemoFile(...self::DEMO_FILE_PATH);
		$demoFile->render();
		$request	= $demoFile->getParsedTemplate();

		// fake admin ids
		$fakeAdminIds	= [
			$this->faker->numberBetween(1),
			$this->faker->numberBetween(1),
			$this->faker->numberBetween(1),
		];

		config([
			self::ADMIN_IDS_CONFIG_PATH => $fakeAdminIds
		]);

		// creating fake user
		$fakeUser	= factory(User::class)->make();

		// mocking
		$mockedThreeLeggedBotFacade = $this->mock('alias:App\Facades\ThreeLeggedBotFacade');

		foreach(config(self::ADMIN_IDS_CONFIG_PATH) as $adminId)
		{
			$mockedThreeLeggedBotFacade
				->shouldReceive('forwardMessage')
				->with(
					$adminId,
					$demoFile->properties->target_chat_id,
					$demoFile->properties->message_id
				)
				->once();
		}

		$mockedThreeLeggedBotFacade
			->shouldReceive('createOrUpdateUser')
			->withArgs(
				function($user) {
					return is_a($user, TelegramUser::class);
				}
			)
			->once()
			->andReturn($fakeUser);

		// request control
		$response = $this->sendUpdate($request);

		$response->assertStatus(200);

		$response->assertSeeText('ok');
	}
}
