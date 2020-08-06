<?php

namespace App\Loggers\Telegram;

use Monolog\Logger;

/**
 * Class TelegramLogger
 * @package App\Loggers\Telegram
 */
class TelegramLogger {
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return Logger
     */
    public function __invoke(array $config)
    {
        return new Logger(
            config('app.name'),
            [
                new TelegramLogHandler($config['level'])
            ]
        );
    }
}
