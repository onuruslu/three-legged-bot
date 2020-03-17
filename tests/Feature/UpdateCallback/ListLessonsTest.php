<?php

namespace Tests\Feature\UpdateCallback;

use Tests\Feature\FeatureTestCase;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks\LessonsCallback;
use Tests\Feature\DemoFile;
use App\Facades\ThreeLeggedBotFacade;
use App\User;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ListLessonsTest extends FeatureTestCase
{
	CONST DEMO_FILE_PATH 			= [__DIR__, 'DemoRequest', 'list_lessons_callback.json'];

	/**
	 * Tests listing lessons from receiving the update
	 * to using the LessonsCallback()->handle() method
	 *
	 * @return void
	 */
	public function testLessonsMenuFromReceivingUpdateToHandle() : void
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
			->once()
			->andReturn($fakeUser);

		$this->app->instance(ThreeLeggedBotFacade::class, $mockedThreeLeggedBotFacade);

		// mocking LessonsCallback
		$mockedLessonsCallback = $this->mock(LessonsCallback::class);

		list($callbackCommand, $selectedYearId)	= explode('#', $demoFile->properties->callback_argument);

		$mockedLessonsCallback
			->shouldReceive('handle')
			->withArgs(
				function($id) use ($selectedYearId) {
					return $id === intval($selectedYearId) && $id != 0;
				}
			)
			->once();

		$this->app->bind(
			LessonsCallback::class,
			function($app, $args) use ($mockedLessonsCallback, $demoFile)
			{
				$chatId	= $args['update']->getCallbackQuery()->getMessage()->getChat()->getId();
				$exceptedChatId	= $demoFile->properties->target_user_id;

				$this->assertEquals(
					$exceptedChatId,
					$chatId
				);
				
				return $mockedLessonsCallback;
			}
		);

		// request control
		$response = $this->sendUpdate($request);

		$response->assertStatus(200);

		$response->assertSeeText('ok');
	}
}
