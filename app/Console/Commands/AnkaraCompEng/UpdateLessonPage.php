<?php

namespace App\Console\Commands\AnkaraCompEng;

use Illuminate\Console\Command;
use App\Jobs\ScanLessonPage;
use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Lesson;

class UpdateLessonPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:3legsbot-lesson-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the lesson page records.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lessons = Lesson::has('users')->get();

        foreach($lessons as $lesson)
            ScanLessonPage::dispatchNow($lesson);
    }
}
