<?php

namespace App\Transformers;

class MainTransformer{
	static public function strikethrough($text){
		 $text = "\u{0336}"
			.preg_replace(
				'~([a-z0-9 	:/"\'-\.]{1})~iU',
				"$1\u{0336}",
				$text
			);

		return $text;
	}
}