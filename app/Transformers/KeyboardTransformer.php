<?php

namespace App\Transformers;

use App\User;

class KeyboardTransformer{
	CONST CLASS_SLUG = 'classes';
	CONST LESSON_SLUG = 'lessons';
	CONST SELECT_LESSON_SLUG = 'selectLesson';

	static public function transform($collection, User $user){
		if($collection->count() < 1)
			return;

		$tableName		= $collection->first()->getTable();

		return self::$tableName($collection, $user);
	}

	static protected function levels($collection, User $user){
		return $collection->map(function($item, $key){
			return [[
				'text'			=> "[\u{2713}] " . $item->title,
				'callback_data'	=> self::LESSON_SLUG . '#' . $item->id,
			]];
		});
	}

	static protected function lessons($collection, User $user){
		$data		= $collection->map(function($item, $key) use ($user) {

			$checked= $item->users()->where('id', $user->id)->exists() ? "[\u{2713}] " : '[ ] ';

			return [[
				'text'			=> $checked . $item->title,
				'callback_data'	=> self::SELECT_LESSON_SLUG . '#' . $item->id,
			]];
		});

		$data->push([['text' => 'Geri', 'callback_data' => self::CLASS_SLUG]]);

		return $data;
	}
}