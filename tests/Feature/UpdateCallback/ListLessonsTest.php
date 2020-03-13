<?php

namespace Tests\Feature\UpdateCallback;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Commands\LessonsMenuCommand;
use Telegram\Bot\Commands\CommandInterface;
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


	public function tearDown() : void
	{
		\Mockery::close();
	}

	/**
	 * Tests lessons menu from receiving the update to using
	 * the WebhookHandler->getCommandBus()->execute() method
	 *
	 * @return void
	 */
	public function testLessonsMenuFromReceivingUpdateToExecute() : void
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

		// mocking LessonsMenuCommand
		$mockedLessonsMenuCommand				= \Mockery::mock('overload:' . LessonsMenuCommand::class, CommandInterface::class);
		$mockedLessonMenuCounter				= 0;
		list($callbackCommand, $selectedYear)	= explode('#', $demoFile->properties->callback_argument);

		$mockedLessonsMenuCommand
			->shouldReceive('getName')
			->andReturn($callbackCommand)
			->shouldReceive('make')
			->withArgs(
				function($arg1, $arg2, $arg3) use (&$mockedLessonMenuCounter, $selectedYear)
				{
					if($arg2['id'] == $selectedYear){
						$mockedLessonMenuCounter++;

						return true;
					}

					return false;
				}
			);

		// request control
		$response = $this->sendUpdate($request);

		$response->assertStatus(200);

		$response->assertSeeText('ok');

		$this->assertEquals(1, $mockedLessonMenuCounter);
	}
}
