<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Bots;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Semester as eSemester;

class Level extends Main {
	private $links;

	public function __construct(string $activeLink){
		parent::__construct();
		
		$this->setActiveLink($activeLink);
	}

	public function scan(){
		$this->cropBlocks($this->getActiveLink());

		preg_match_all('~<a href=[`"\'](?\'link\'[^"\'`]+)(?<!\.[a-z]{3}|\.[a-z]{4})[`"\']>(?\'title\'.*(?\'code\'[a-zA-Z]{3}\s*[0-9]{3}).*)</a>~sU', $this->contentSourceCode, $output);

		$output['code'] = array_map(
				function($lessonCode){ return preg_replace("~\s~s", '', $lessonCode); },
				$output['code']
			);

		$this->links	= array_combine($output['code'], $output['link']);
	}

	public function lesson($lessonCode){
		$this->scan();

		// TODO: If there is no lessons with given code, throw exception
		return new Lesson($this->links[$lessonCode]);
	}

	public function getLinks(){
		return $this->links;
	}

	public function save(eSemester $parent){
		$this->scan();

		return $parent->levels()->firstOrCreate(
			['link'					=> $this->getActiveLink()],
			['title'				=> html_entity_decode($this->getPageTitle())]
		);
	}
}