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
        $sourceText          		= html_entity_decode(strip_tags($sourceCode));

        $previousLessonPageEntity   = $eLesson->history()->exists() ? $eLesson->lastPage : '';

        $differ                     = new Differ();
        $diff                       = $differ->diffToArray(
                                            $previousLessonPageEntity,
                                            $sourceText
                                        );

        $eLesson->history()->create([
            'text'      => $sourceText,
            'diff'      => json_encode($diff),
        ]);
	}
}

?>

