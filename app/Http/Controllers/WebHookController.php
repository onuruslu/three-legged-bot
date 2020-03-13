<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnkaraCompEng\ThreeLeggedBot\Handlers\WebhookHandler;

class WebHookController extends Controller
{
    public function trigger(Request $r)
    {
    	$update		= app(WebhookHandler::class)->getWebhookUpdates($r->getContent());

    	$update->handle();

    	return 'ok';
    }
}
