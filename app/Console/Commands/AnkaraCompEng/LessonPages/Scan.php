<?php

namespace App\Console\Commands\AnkaraCompEng\LessonPages;

use Illuminate\Console\Command;

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
        //
    }
}
