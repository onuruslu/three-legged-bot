<?php

namespace Tests\Feature\UpdateCallback;

use Tests\Feature\FeatureTestCase;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Callbacks\SelectLessonCallback;
use Tests\Feature\DemoFile;
use App\Facades\ThreeLeggedBotFacade;
use App\User;

class SelectLessonTest extends FeatureTestCase
{
	CONST DEMO_FILE_PATH 			= [__DIR__, 'DemoRequest', 'select_lesson_callback.json'];

	/**
	 * Tests selecting a lesson from receiving the update
	 * to using the SelectLessonCallback->handle() method
	 *
	 * @return void
	 */
	public function testSelectLessonFromReceivingUpdateToHandle() : void
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

		// mocking SelectLessonCallback
		$mockedSelectLessonCallback = $this->mock(SelectLessonCallback::class);

		list($callbackCommand, $selectedLessonId)	= explode('#', $demoFile->properties->callback_argument);

		$mockedSelectLessonCallback
			->shouldReceive('handle')
			->withArgs(
				function($id) use ($selectedLessonId) {
					return $id === intval($selectedLessonId) && $id != 0;
				}
			)
			->once();

		$this->app->bind(
			SelectLessonCallback::class,
			function($app, $args) use ($mockedSelectLessonCallback, $demoFile)
			{
				$chatId	= $args['update']->getCallbackQuery()->getMessage()->getChat()->getId();
				$exceptedChatId	= $demoFile->properties->target_user_id;

				$this->assertEquals(
					$exceptedChatId,
					$chatId
				);
				
				return $mockedSelectLessonCallback;
			}
		);

		// request control
		$response = $this->sendUpdate($request);

		$response->assertStatus(200);

		$response->assertSeeText('ok');
	}
}
