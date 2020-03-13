<?php

namespace Tests\Feature\UpdateCallback;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Commands\ClassesMenuCommand;
use Telegram\Bot\Commands\CommandInterface;
use Tests\Feature\DemoFile;
use App\Facades\ThreeLeggedBotFacade;
use App\User;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ListYearsTest extends FeatureTestCase
{
	CONST DEMO_FILE_PATH 			= [__DIR__, 'DemoRequest', 'list_years_callback.json'];


	public function tearDown() : void
	{
		\Mockery::close();
	}

	/**
	 * Tests years menu from receiving the update to using
	 * the WebhookHandler->getCommandBus()->execute() method
	 *
	 * @return void
	 */
	public function testYearsMenuFromReceivingUpdateToExecute() : void
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

		// mocking ClassMenuCommand
		$mockedClassMenuCommand		= \Mockery::mock('overload:' . ClassesMenuCommand::class, CommandInterface::class);
		$mockedClassMenuCounter		= 0;

		$mockedClassMenuCommand
			->shouldReceive('getName')
			->andReturn('classes')
			->shouldReceive('make')
			->withArgs(
				function($arg1, $arg2, $arg3) use (&$mockedClassMenuCounter)
				{
					$mockedClassMenuCounter++;
					return true;
				}
			);

		// request control
		$response = $this->sendUpdate($request);

		$response->assertStatus(200);

		$response->assertSeeText('ok');

		$this->assertEquals(1, $mockedClassMenuCounter);
	}
}
