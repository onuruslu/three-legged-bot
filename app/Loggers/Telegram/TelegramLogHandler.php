<?php


namespace App\Loggers\Telegram;

use App\Facades\ThreeLeggedBotFacade;
use Exception;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Class TelegramLogHandler
 * @package App\Loggers\Telegram
 */
class TelegramLogHandler extends AbstractProcessingHandler
{
    /**
     * Admin chat ids
     *
     * @var int
     */
    private $adminIds;

    /**
     * Application name
     *
     * @string
     */
    private $appName;

    /**
     * TelegramHandler constructor.
     * @param int $level
     */
    public function __construct($level)
    {
        $level = Logger::toMonologLevel($level);

        parent::__construct($level, true);

        // define variables for making Telegram request
        $this->adminIds = config('telegram.telegram_admin_ids');

        // define variables for text message
        $this->appName = config('app.name');
    }

    /**
     * @param array $record
     */
    public function write(array $record): void
    {
        if(!$this->adminIds) {
            return;
        }

        // trying to make request and send notification
        try {
            foreach ($this->adminIds as $adminId)
            {
                ThreeLeggedBotFacade::sendMessage(
                    $adminId,
                    $this->formatText($record['formatted'], $record['level_name'])
                );
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**
     * @param string $text
     * @param string $level
     * @return string
     */
    private function formatText(string $text, string $level): string
    {
        return '<b>' . $this->appName .  '</b> (' . $level . ')' . PHP_EOL  . $text;
    }
}
