<?php

namespace App\Console\Commands\AnkaraCompEng\LessonPages;

use Illuminate\Console\Command;
use App\Jobs\ScanSemester;

class Scan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:3legsbot-pages {semester-link: Link of the semester page (Eg: http://comp.eng.ankara.edu.tr/lisans-egitimi/ders-sayfalari/)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans the lesson pages and saves them to the database';

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
        $semesterLink       = $this->argument('semester-link');

        ScanSemester::dispatchNow($semesterLink);

    }
}
