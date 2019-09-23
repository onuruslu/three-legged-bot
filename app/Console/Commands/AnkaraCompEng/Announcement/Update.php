<?php

namespace App\Console\Commands\AnkaraCompEng\Announcement;

use Illuminate\Console\Command;
use App\Services\AnkaraCompEng\RSSAnnouncement;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:3legsbot-announcement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronise announcements on the DB with the comp.eng.ankara.edu.tr';

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
        $rss        = new RSSAnnouncement;

        $channel    = $rss->getAnnouncements();

        $rss->storeAnnouncements($channel);
    }
}
