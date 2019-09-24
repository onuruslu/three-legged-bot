<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Bots;

class Lesson extends Main {
	private $activeLessonLink;

	public function __construct(string $activeLessonLink){
		parent::__construct();

		$this->activeLessonLink		= $activeLessonLink;

		$contentSourceCode			= $this->cropContent($this->activeLessonLink);

		return $contentSourceCode;
	}
}