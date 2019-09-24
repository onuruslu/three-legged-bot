<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Bots;

class Level extends Main {
	private $activeLevelLink;

	public function __construct(string $activeLevelLink){
		parent::__construct();
		
		$this->activeLevelLink	= $activeLevelLink;
	}

	public function getLevelPageLinks(){
		$contentSourceCode			= $this->cropContent($this->activeLevelLink);

		preg_match_all('~<a href=[`"\'](?\'link\'[^"\'`]+)(?<!\.[a-z]{3}|\.[a-z]{4})[`"\']>(?\'title\'.*(?\'code\'[a-zA-Z]{3}[0-9]{3}).*)</a>~sU', $contentSourceCode, $output);

		return array_combine($output['code'], $output['link']);
	}

	public function lesson($lessonCode){
		$links		= $this->getLevelPageLinks();

		// TODO: If there is no lessons with given code, throw exception
		return new Lesson($links[$lessonCode]);
	}
}