<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Bots;

use App\Services\AnkaraCompEng\Ayca;
use Illuminate\Support\Facades\Cache;

class Main {
	CONST LOGIN_PAGE_URL	= 'http://comp.eng.ankara.edu.tr/wp-login.php?action=postpass';
	CONST LOGIN_PASSWORD	= '**PASSWORD**';

	public $sourceCode;
	public $client;

	public function __construct(){
		$this->client		= new Ayca();
	}

	public function scanPage($link){
		$response 			= $this->client->get($link);
		$this->sourceCode	= $response->getBody()->getContents();

		if($this->isItLoginPage($this->sourceCode))
			$this->sourceCode	= $this->login($link);

		return $this->sourceCode;
	}

	public function cropContent($link){
		$this->scanPage($link);

		preg_match('~<div class="entry-content">\s*?(.*)\s*</div><!-- \.entry-content -->~sU', $this->sourceCode, $output);
		$contentSourceCode	= $output[1];

		return $contentSourceCode;
	}

	public function isItLoginPage($contentSourceCode){
		return !!strpos($contentSourceCode, 'post-password-form');
	}

	public function login($link){
		echo 'login çalıştı';
		$response			= $this->client->post(
			self::LOGIN_PAGE_URL,
			[
				#'allow_redirects' => false,
				'headers' => [
					'Referer'	=> $link
				],
				'form_params' => [
					'post_password' => self::LOGIN_PASSWORD,
					'Submit'		=> 'Giriş'
				]
			]
		);

		Cache::put(
			'AnkaraCompEngCookie',
			$this->client->cookieJar->toArray(),
			now()->addWeeks(1)
		);

		return $response->getBody()->getContents();
	}
}