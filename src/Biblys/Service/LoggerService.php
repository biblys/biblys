<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


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