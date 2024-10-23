<?php

namespace App\Services;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class PLogger
{
    public static function log($level, $message, $context = []){
        $main = new MonologLogger('main');
        $main->pushHandler(new StreamHandler(dirname(__DIR__, 2) . '/cache/log/app.log'));

        $main->pushHandler(new StreamHandler('php://stdout', $level = MonologLogger::DEBUG,
        $bubble = true));

        $main->log($level, $message, $context);
    }
}
