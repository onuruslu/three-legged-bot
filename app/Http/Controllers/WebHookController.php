<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Handlers\WebhookHandler;

class WebHookController extends Controller
{
    public function trigger()
    {
    	$update		= app(WebhookHandler::class)->getWebhookUpdates();

    	$update->handle();

    	return 'ok';
    }
}
