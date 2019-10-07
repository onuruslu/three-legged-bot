<?php

namespace App\Transformers;

use App\Announcement;

class AnnouncementTransformer extends MainTransformer {
	public static function toMarkdown(&$announcement){
		return (
                '<strong>'.$announcement->title.'</strong>'
                ."\n"
                .'<code>'.str_repeat('-', 40).'</code>'
                ."\n"
                .html_entity_decode(strip_tags($announcement->text))
                ."\n©2019 made with love"
                ."\n"
                .$announcement->link
            );
	}
}