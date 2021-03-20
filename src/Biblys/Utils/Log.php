<?php

namespace Biblys\Utils;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\PsrHandler;
use Rollbar\Rollbar;

class Log
{

    public static function error(string $level, string $message, array $context = []): void
    {
        self::_writeLog('errors', $level, $message, $context);
    }

    public static function sql(string $level, string $message, array $context = []): void
    {
        self::_writeLog('sql', $level, $message, $context);
    }

    public static function mail(string $level, string $message, array $context = []): void
    {
        self::_writeLog('mails', $level, $message, $context);
    }

    public static function security(string $level, string $message, array $context = []): void
    {
        self::_writeLog('security', $level, $message, $context);
    }

    public static function paypal(string $level, string $message, array $context = []): void
    {
        self::_writeLog('paypal', $level, $message, $context);
    }

    public static function payplug(string $level, string $message, array $context = []): void
    {
        self::_writeLog('payplug', $level, $message, $context);
    }

    public static function stripe(string $level, string $message, array $context = []): void
    {
        self::_writeLog('stripe', $level, $message, $context);
    }

    private static function _writeLog(
        string $name,
        string $level,
        string $message,
        array $context = []
    ): void {
        $logger = new Logger($name);

        $filePath = BIBLYS_PATH . "/app/logs/$name.log";
        $logger->pushHandler(new StreamHandler($filePath, Logger::DEBUG));

        $config = new Config();
        $rollbarConfig = $config->get("rollbar");
        if ($rollbarConfig) {
            Rollbar::init([
                "access_token" => $rollbarConfig["access_token"],
                "environment" => $rollbarConfig["environment"],
                "code_version" => BIBLYS_VERSION
            ]);
            $logger->pushHandler(
                new PsrHandler(
                    Rollbar::logger(),
                    $rollbarConfig["level"] ?? "WARNING"
                )
            );
        }

        $logger->log($level, $message, $context);
    }
}
