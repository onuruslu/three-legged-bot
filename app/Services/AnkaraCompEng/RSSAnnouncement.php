<?php

namespace App\Services\AnkaraCompEng;
use Zend\Feed\Reader\Reader;
use Zend\Feed\Reader\Feed\Rss;
use App\Announcement;

class RSSAnnouncement{
	public function getAnnouncements()
	{
    	$channel = Reader::import('http://comp.eng.ankara.edu.tr/feed/');

    	return $channel;
	}

	public function storeAnnouncements(Rss $channel)
	{
		foreach($channel as $item){
			list($remote_id)  = sscanf($item->getXpath()->evaluate(
              'string('
              . $item->getXpathPrefix()
              . '/guid)'
            ),
            'http://comp.eng.ankara.edu.tr/?p=%d');

			Announcement::updateOrCreate(
				[
					'remote_id'				=> $remote_id,
					'remote_updated_at'		=> $item->getDateModified()->format('Y-m-d H:i:s'),
				],
				[
					'title'					=> $item->getTitle(),
					'text'					=> $item->getContent(),
					'link'					=> $item->getPermalink(),
					'remote_created_at'		=> $item->getDateCreated()->format('Y-m-d H:i:s'),
				]
			);
			
		}
	}
}

?>