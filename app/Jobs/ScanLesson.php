<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Level as eLevel;
use App\Services\AnkaraCompEng\ScanningLessonPages\Bots\Lesson;

class ScanLesson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lessonLink;
    protected $level;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(eLevel $level, string $lessonLink)
    {
        $this->lessonLink       = $lessonLink;
        $this->level            = $level;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logger('ScanLesson start');
        $lessonContent    = new Lesson($this->lessonLink);

        $lessonContent->save($this->level);

        logger('ScanLesson end');
    }
}
