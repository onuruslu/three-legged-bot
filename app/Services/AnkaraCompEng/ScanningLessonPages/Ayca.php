<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Cache;

/**
 * Since Ayca is a messenger between students
 * and instructors, we assume giving the class
 * name as an Ayça is the most apropriate choice
 * for our request class
 */

class Ayca extends Client{
	public $cookieJar;

	public function __construct($array=[]){
		$this->cookieJar		= new CookieJar;
		
		if(Cache::has('AnkaraCompEngCookie')){
			$this->cookieJar	= CookieJar::fromArray(
					collect(Cache::get('AnkaraCompEngCookie'))->keyBy('Name')->map(function($item){
						return $item['Value'];
					})->toArray(),
					'.eng.ankara.edu.tr'
				);
		}

		parent::__construct($array + ['cookies' => $this->cookieJar]);
	}
}