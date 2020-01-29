<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Lesson as eLesson;
use App\Services\AnkaraCompEng\ScanningLessonPages\Bots\Lesson;
use SebastianBergmann\Diff\Differ;

class ScanLessonPageService{
	public function saveDiffsOfLessonPage(eLesson $eLesson = null){
        $lesson                     = new Lesson($eLesson->link);
        $lesson->scan();
        $sourceCode           		= $lesson->contentSourceCode;
        $sourceText          		= strip_tags($sourceCode);
        $sourceText                 = str_replace("&nbsp;", ' ', $sourceText);
        $sourceText                 = html_entity_decode($sourceText);

        $previousLessonPageEntity   = $eLesson->history()->exists() ? $eLesson->lastPage() : null;

        $differ                     = new Differ();
        $diff                       = $differ->diffToArray(
                                            $previousLessonPageEntity ? $previousLessonPageEntity->text : '',
                                            $sourceText
                                        );

        if( !$previousLessonPageEntity
            || ($previousLessonPageEntity && md5($previousLessonPageEntity->text) !== md5($sourceText))
        )
            $eLesson->history()->create([
                'text'      => $sourceText,
                'diff'      => $diff,
            ]);
	}
}

?>

