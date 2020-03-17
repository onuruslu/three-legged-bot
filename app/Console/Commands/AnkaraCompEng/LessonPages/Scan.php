<?php

namespace App\Console\Commands\AnkaraCompEng\LessonPages;

use Illuminate\Console\Command;
use App\Jobs\ScanSemester;
use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Semester;

class Scan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:3legsbot-pages';

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
        if ( Semester::exists() && $this->confirm('Do you want to update the semesters on the DB?') )
        {
            $this->updateSemester();
        }
        else {
            $this->createSemester();
        }

    }

    /**
     * Updates the semester, level, and lesson records on the DB
     * 
     * @return void
     */
    protected function updateSemester() : void
    {
        // Show semesters on the DB
        $semesters              = Semester::select('id', 'title', 'link')->get();
        $tableHeaders           = ['id', 'title', 'link'];

        $this->table(
            $tableHeaders,
            $semesters->toArray()
        );

        // Get id of the semester which will be updated
        $isInfiniteLoopActive   = true;

        while ($isInfiniteLoopActive)
        {

            $selectedSemesterId  = $this->ask('Please enter the id number of the semester, which you want to update');

            if (Semester::where('id', $selectedSemesterId)->exists())
            {
                $isInfiniteLoopActive   = false;
            }
            else {
                $this->error('The given id is invalid!');
            }
        }

        // Update the selected semester
        ScanSemester::dispatchNow(
            Semester::find($selectedSemesterId)->link
        );
    }

    /**
     * Creates a new semesters, levels, and lessons
     * 
     * @return void
     */
    protected function createSemester() : void
    {
        $semesterLink = $this->ask(
            'What is the url of the current semester?'
            . "\n" . "(Eg: http://comp.eng.ankara.edu.tr/lisans-egitimi/ders-sayfalari/)"
        );

        ScanSemester::dispatchNow($semesterLink);
    }
}
