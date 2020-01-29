<?php

namespace App\Services\AnkaraCompEng\ThreeLeggedBot\Commands;

use Telegram\Bot\Commands\Command;

/**
 * Class BackCommand.
 */
class BackCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'back';

    /**
     * @var string Command Description
     */
    protected $description = 'Seçilebilecek Dersleri Listeler';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        return $this->triggerCommand('classes', $arguments);
    }
}
