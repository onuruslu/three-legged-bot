<?php

namespace Tests\Feature\UpdateMessageToAdmin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase;
use Tests\Feature\DemoFile;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UpdateMessageToUserTest extends FeatureTestCase
{
	use WithFaker;

	CONST DEMO_FILE_PATH 			= [__DIR__, 'DemoRequest', 'message_to_user.json'];
	CONST ADMIN_IDS_CONFIG_PATH		= 'telegram.telegram_admin_ids';
	CONST USER_ID_BETWEEN			= [1000, 5000];
	CONST ADMIN_ID_BETWEEN			= [5001, 10000];

	public function testSendReplyFromAdminToUser() : void
	{
		// message parsing
		$demoFile	= new DemoFile(...self::DEMO_FILE_PATH);
		$demoFile->render();
		$request	= $demoFile->getParsedTemplate();

		// fake admin ids
		$fakeAdminIds	= [
			$this->faker->randomNumber,
			$demoFile->properties->source_chat_id,
			$this->faker->randomNumber,
		];

		config([
			self::ADMIN_IDS_CONFIG_PATH => $fakeAdminIds
		]);

		// creating fake user
		$fakeUser	= factory(User::class)->make();
		$fakeUser->telegram_id	= $demoFile->properties->source_chat_id;

		// mocking
		$mockedThreeLeggedBotFacade = $this->mock('alias:App\Facades\ThreeLeggedBotFacade');

		$mockedThreeLeggedBotFacade
			->shouldReceive('createOrUpdateUser')
			->andReturn($fakeUser);

		$mockedThreeLeggedBotFacade
			->shouldReceive('sendMessage')
			->with(
				$demoFile->properties->reply_to_message_chat_id,
				$demoFile->properties->message_text
			)
			->once();

		// request control
		$response = $this->sendUpdate($request);

		$response->assertStatus(200);

		$response->assertSeeText('ok');
	}

	public function testDoesntSendReplyFromUserToSomeoneElse() : void
	{
		// message parsing
		$demoFile	= new DemoFile(...self::DEMO_FILE_PATH);
		$demoFile->render();
		$request	= $demoFile->getParsedTemplate();

		// fake admin ids
		$fakeAdminIds	= [
			$this->faker->numberBetween(...self::USER_ID_BETWEEN),
			$this->faker->numberBetween(...self::USER_ID_BETWEEN),
			$this->faker->numberBetween(...self::USER_ID_BETWEEN),
		];

		config([
			self::ADMIN_IDS_CONFIG_PATH => $fakeAdminIds
		]);

		// creating fake user
		$fakeUser	= factory(User::class)->make();
		$fakeUser->telegram_id	= $this->faker->numberBetween(...self::ADMIN_ID_BETWEEN);

		// mocking
		$mockedThreeLeggedBotFacade = $this->mock('alias:App\Facades\ThreeLeggedBotFacade');

		$mockedThreeLeggedBotFacade
			->shouldReceive('createOrUpdateUser')
			->andReturn($fakeUser);

		$mockedThreeLeggedBotFacade
			->shouldNotReceive('sendMessage');

		foreach($fakeAdminIds as $adminId){
			$mockedThreeLeggedBotFacade
				->shouldReceive('forwardMessage')
				->with(
					$adminId,
					$demoFile->properties->target_chat_id,
					$demoFile->properties->message_id
				)
				->once();
		}

		// request control
		$response = $this->sendUpdate($request);

		$response->assertStatus(200);

		$response->assertSeeText('ok');
	}
}
