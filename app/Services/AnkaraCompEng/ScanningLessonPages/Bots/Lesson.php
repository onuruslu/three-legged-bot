<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Bots;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Level as eLevel;

class Lesson extends Main {
	public function __construct(string $activeLink){
		parent::__construct();
		
		$this->setActiveLink($activeLink);
	}

	public function scan(){
		$this->cropBlocks($this->getActiveLink());
	}

	public function save(eLevel $parent){
		$this->scan();

		return $parent->lessons()->firstOrCreate(
			['link'					=> $this->getActiveLink()],
			['title'				=> html_entity_decode($this->getPageTitle())]
		);
	}
}