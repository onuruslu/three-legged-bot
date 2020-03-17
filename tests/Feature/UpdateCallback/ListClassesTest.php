<?php

namespace Tests\Feature\UpdateCallback;

use Tests\Feature\FeatureTestCase;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks\ClassesCallback;
use Tests\Feature\DemoFile;
use App\Facades\ThreeLeggedBotFacade;
use App\User;
use Telegram\Bot\Objects\User as TelegramUser;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ListClassesTest extends FeatureTestCase
{
	CONST DEMO_FILE_PATH 			= [__DIR__, 'DemoRequest', 'list_classes_callback.json'];

	/**
	 * Tests listing of classes from receiving the update
	 * to using the ClassesCallback()->handle() method
	 *
	 * @return void
	 */
	public function testClassesMenuFromReceivingUpdateToHandle() : void
	{
		// message parsing
		$demoFile	= new DemoFile(...self::DEMO_FILE_PATH);
		$demoFile->render();
		$request	= $demoFile->getParsedTemplate();

		// creating fake user
		$fakeUser	= factory(User::class)->make();

		// mocking ThreeLeggedBotFacade
		$mockedThreeLeggedBotFacade = $this->mock('alias:App\Facades\ThreeLeggedBotFacade')->makePartial();

		$mockedThreeLeggedBotFacade
			->shouldReceive('createOrUpdateUser')
			->withArgs(
				function($user) {
					return is_a($user, TelegramUser::class);
				}
			)
			->once()
			->andReturn($fakeUser);

		$this->app->instance(ThreeLeggedBotFacade::class, $mockedThreeLeggedBotFacade);

		// mocking ClassesCallback
		$mockedClassesCallback = $this->mock(ClassesCallback::class);

		$mockedClassesCallback
			->shouldReceive('handle')
			->once();

		$this->app->bind(
			ClassesCallback::class,
			function($app, $args) use ($mockedClassesCallback, $demoFile)
			{
				$chatId	= $args['update']->getCallbackQuery()->getMessage()->getChat()->getId();
				$exceptedChatId	= $demoFile->properties->target_user_id;

				$this->assertEquals(
					$exceptedChatId,
					$chatId
				);
				
				return $mockedClassesCallback;
			}
		);

		// request control
		$response = $this->sendUpdate($request);

		$response->assertStatus(200);

		$response->assertSeeText('ok');
	}
}
