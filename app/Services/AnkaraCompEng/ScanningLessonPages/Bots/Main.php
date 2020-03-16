<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Bots;

use App\Services\AnkaraCompEng\ScanningLessonPages\Ayca;
use Illuminate\Support\Facades\Cache;

class Main {
	public $sourceCode;
	public $client;
	public $pageTitle;
	public $contentSourceCode;
	protected $activeLink;

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

	private function cropPageTitle(){
		preg_match('~<title>(?\'pageTitle\'.+) \| Bilgisayar~iU', $this->sourceCode, $output);

		if(isset($output['pageTitle']))
			$this->pageTitle				= $output['pageTitle'];

		return $this->pageTitle;
	}

	private function cropContent(){
		preg_match('~<div class="entry-content">\s*?(?\'content\'.*)\s*</div><!-- \.entry-content -->~sU', $this->sourceCode, $output);

		if(isset($output['content']))
			$this->contentSourceCode	= $output['content'];

		return $this->contentSourceCode;
	}

	public function cropBlocks($link){
		$this->scanPage($link);
		$this->cropPageTitle();
		$this->cropContent();
	}

	public function isItLoginPage($contentSourceCode){
		return !!strpos($contentSourceCode, 'post-password-form');
	}

	public function login($link){
		$response			= $this->client->post(
			config('ankara.compeng.login_page_url'),
			[
				#'allow_redirects' => false,
				'headers' => [
					'Referer'	=> $link
				],
				'form_params' => [
					'post_password' => config('ankara.compeng.login_password'),
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

	protected function setActiveLink(string $activeLink){
		$this->activeLink			= $activeLink;
		$this->pageTitle			= $activeLink;
	}

	public function getActiveLink(){
		return $this->activeLink;
	}

	public function getPageTitle(){
		return isset($this->pageTitle) ? $this->pageTitle : $this->activeSemesterLink;
	}
}