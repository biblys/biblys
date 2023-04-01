<?php

namespace Biblys\Service;

use Monolog\Logger;
use Monolog\Handler\PsrHandler;
use Monolog\Handler\StreamHandler;
use Rollbar\Rollbar;

class LoggerService
{
    public function log(string $logger, string $level, string $message, array $context = []): void
    {
        $this->_writeLog($logger, $level, $message, $context);
    }

    private function _writeLog(
        string $name,
        string $level,
        string $message,
        array $context = []
    ): void {
        $logger = new Logger($name);

        $filePath = __DIR__ . "/../../../app/logs/$name.log";
        $logger->pushHandler(new StreamHandler($filePath, Logger::DEBUG));

        if ($name === "errors") {
            self::_sendToRollbar($logger);
        }

        if ($level === "CRITICAL ERROR") {
            $level = Logger::CRITICAL;
        }

        $logger->log($level, $message, $context);
    }

    /**
     * @param Logger $logger
     */
    private function _sendToRollbar(Logger $logger): void
    {
        $config = Config::load();
        $rollbarConfig = $config->get("rollbar");
        if (!$rollbarConfig) {
            return;
        }

        $logger->pushHandler(
            new PsrHandler(
                Rollbar::logger(),
                $rollbarConfig["level"] ?? "WARNING"
            )
        );
    }
}