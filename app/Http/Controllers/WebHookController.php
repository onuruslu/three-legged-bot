<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnkaraCompEng\ThreeLeggedBot\ThreeLeggedBotService;

class WebHookController extends Controller
{
    public function trigger()
    {
    	$update		= app(ThreeLeggedBotService::class)->getWebhookUpdates();

    	logger(json_encode($update));

    	app(ThreeLeggedBotService::class)->processCommand($update);

    	return 'ok';
    }
}
