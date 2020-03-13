<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;

abstract class FeatureTestCase extends TestCase
{
	protected function sendUpdate(string $request) : TestResponse
	{
		return $this->call('POST', env('TELEGRAM_BOT_WEBHOOK_URL_PATH'), [], [], [], [], $request);
	}
}
