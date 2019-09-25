<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Bots;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Semester as eSemester;

class Semester extends Main {
	private $links;

	public function __construct(string $activeLink){
		parent::__construct();
		
		$this->setActiveLink($activeLink);
	}

	public function scan(){
		$this->cropBlocks($this->getActiveLink());

		preg_match_all('~<a href=[`"\'](?\'link\'[^"\'`]+)(?<!\.[a-z]{3}|\.[a-z]{4})[`"\']>(?\'title\'.+)</a>~sU', $this->contentSourceCode, $output);

		$this->links				= array_reverse($output['link']);
	}

	public function level(int $level){
		$this->scan();

		return new Level($this->links[$level-1]);
	}

	public function getLinks(){
		return $this->links;
	}

	public function save(){
		$this->scan();

		return eSemester::firstOrCreate(
			['link'					=> $this->getActiveLink()],
			['title'				=> html_entity_decode($this->getPageTitle())]
		);
	}
}