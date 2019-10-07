<?php

namespace App\Transformers;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\LessonPage;

CONST NO_CHANGE		= 0;
CONST ADDED			= 1;
CONST DELETED		= 2;

class LessonPageTransformer extends MainTransformer {
	private static function parseDiff($item){
		list($text, $status)		= $item;

		if($text==="\n")
			return $text;

		elseif($status===NO_CHANGE)
			return $text;

		elseif($status===ADDED)
			return '<strong>'
				.substr_replace(
					$text,
					'</strong>',
					-1,
					0
				);

		elseif($status===DELETED)
			return self::strikethrough($text);
	}

	public static function toMarkdown(LessonPage &$lessonPage){

		$text		= '';

		foreach($lessonPage->diff as $i => $item){
			$text	.= self::parseDiff($item);
		}

		return (
			'<strong>'.$lessonPage->lesson->title.'</strong>'
			."\n"
			.'<code>'.str_repeat('-', 40).'</code>'
			."\n"
			.$text
			."\n"
			."\n"
			.$lessonPage->lesson->link
			."\n"
			.'<code>'.str_repeat('-', 40).'</code>'
			."\n"
			.'Değişmeyen Metin'
			."\n"
			.'<strong>Yeni Eklenen Metin</strong>'
			."\n"
			."\u{0336}S\u{0336}i\u{0336}l\u{0336}i\u{0336}n\u{0336}e\u{0336}n\u{0336} \u{0336}M\u{0336}e\u{0336}t\u{0336}i\u{0336}n\u{0336}"
		);
	}
}