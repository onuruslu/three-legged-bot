<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\AnkaraCompEng\ScanningLessonPages\Bots\Semester;

class ScanSemester implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $semesterLink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($semesterLink)
    {
        $this->semesterLink     = $semesterLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logger('ScanSemester start');
        $semesterContent    = new Semester($this->semesterLink);

        $semester           = $semesterContent->save();

        foreach($semesterContent->getLinks() as $link)
            ScanLevel::dispatchNow($semester, $link);

        logger('ScanSemester end');
    }
}
