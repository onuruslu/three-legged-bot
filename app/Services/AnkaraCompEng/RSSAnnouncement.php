<?php

namespace App\Services\AnkaraCompEng;
use Zend\Feed\Reader\Reader;
use Zend\Feed\Reader\Feed;
use App\Announcement;

class RSSAnnouncement{
	public function getAnnouncements()
	{
    	$channel = Reader::import('http://comp.eng.ankara.edu.tr/feed/');

    	return $channel;
	}
}

?>