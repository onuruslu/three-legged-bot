<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Bots;

class Semester extends Main {
	CONST FALL_SEMESTER_LINK	= 'http://comp.eng.ankara.edu.tr/lisans-egitimi/ders-sayfalari/';
	CONST SPRING_SEMESTER_LINK	= 'http://comp.eng.ankara.edu.tr/lisans-egitimi/ders-sayfalari-bahar-donemi/';
	CONST SUMMER_SEMESTER_LINK	= 'http://comp.eng.ankara.edu.tr/ders-sayfalari-2018-2019-egitim-ogretim-yili-yaz-donemi/';

	private $activeSemesterLink;

	public function __construct(string $activeSemesterLink){
		parent::__construct();
		
		$this->activeSemesterLink	= $activeSemesterLink;
	}

	public function getLevelPageLinks(){
		$contentSourceCode			= $this->cropContent($this->activeSemesterLink);

		preg_match_all('~<a href=[`"\'](?\'link\'[^"\'`]+)(?<!\.[a-z]{3}|\.[a-z]{4})[`"\']>(?\'title\'.+)</a>~sU', $contentSourceCode, $output);

		return array_reverse($output['link']);
	}

	public function level(int $level){
		$links				= $this->getLevelPageLinks();

		return new Level($links[$level-1]);
	}
}