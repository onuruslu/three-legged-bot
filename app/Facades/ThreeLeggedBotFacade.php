<?php

namespace App\Facades;

use Telegram;
use App\Transformers\AnnouncementTransformer;
use App\Transformers\LessonPageTransformer;
use App\Announcement;
use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\LessonPage;

class ThreeLeggedBotFacade{

	public static function getParsedMessage($chatId, string $message){
		return [
            'chat_id'       => $chatId,
            'parse_mode'    => 'Html',
            'text'          => $message,
        ];
	}

	public function sendAnnouncement($chatId, Announcement $announcement){
        $message            = AnnouncementTransformer::toMarkdown($announcement);
        
        self::sendMessage($chatId, $message);
	}

    public function sendLessonPageDiff($chatId, LessonPage $lessonPage){
        $message            = LessonPageTransformer::toMarkdown($lessonPage);

        self::sendMessage($chatId, $message);
    }

    public static function sendMessage($chatId, $message){
        $messages           = self::splitMessage($message);
        
        foreach ($messages as $key => $value) {
            Telegram::sendMessage(
                self::getParsedMessage($chatId, $value)
            );
        }
    }

    public static function splitMessage($message, $maxSize=4000){
        if(mb_strlen($message)<=$maxSize)
            return [$message];

        $newMessages        = [];
        $splittedMesages    = explode("\n", $message);
        $messageCounter     = 0;

        for($i=0; $i<count($splittedMesages); $i++){
            if(
                isset($newMessages[$messageCounter])

                &&

                mb_strlen(
                    $newMessages[$messageCounter]
                    .$splittedMesages[$i]
                )

                >

                $maxSize
            )
                $messageCounter++;

                $newMessages[$messageCounter]     = isset($newMessages[$messageCounter])
                                                    ?
                                                    $newMessages[$messageCounter]."\n"
                                                    :
                                                    "\n";

                $newMessages[$messageCounter]     .= $splittedMesages[$i];
        }

        return $newMessages;
    }
}