<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Lesson as eLesson;
use App\Services\AnkaraCompEng\ScanningLessonPages\ScanLessonPageService;

class ScanLessonPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(eLesson $eLesson = null)
    {
        $this->eLesson          = $eLesson;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logger('ScanLessonPage start');

        $scan                   = app(ScanLessonPageService::class);
        $scan->saveDiffsOfLessonPage($this->eLesson);

        logger('ScanLessonPage end');
    }
}
