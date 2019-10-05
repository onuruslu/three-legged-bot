<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Semester as eSemester;
use App\Services\AnkaraCompEng\ScanningLessonPages\Bots\Level;

class ScanLevel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $levelLink;
    protected $semester;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(eSemester $semester, string $levelLink)
    {
        $this->levelLink     = $levelLink;
        $this->semester      = $semester;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logger('ScanLevel start');
        $levelContent       = new Level($this->levelLink);

        $level              = $levelContent->save($this->semester);

        foreach($levelContent->getLinks() as $link)
            ScanLesson::dispatchNow($level, $link);

        logger('ScanLevel end');
    }
}
