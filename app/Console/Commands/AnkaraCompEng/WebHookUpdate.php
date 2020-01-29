<?php

namespace App\Console\Commands\AnkaraCompEng;

use Illuminate\Console\Command;
use App\Services\AnkaraCompEng\ThreeLeggedBot\ThreeLeggedBotService;

class WebHookUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:3legsbot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Long-polling for the Three Legged Bot.';

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
        $updates = app(ThreeLeggedBotService::class)->getUpdates([
            'offset' => \Cache::get('lastUpdateId', 0),
            'limit' => 1
        ]);

        if(count($updates) <= 0){
            $this->info('There is no new messages');
            return;
        }

        \Cache::put('lastUpdateId', $updates[0]->getUpdateId()+1);

        return app(ThreeLeggedBotService::class)->processCommand($updates[0]);
    }
}
