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

use PHPUnit\Framework\TestCase;

class LoggerServiceTest extends TestCase
{

    public function testLog()
    {
        // given
        $loggerService = new LoggerService();
        $logFilePath = __DIR__ . "/../../../app/logs/errors.log";
        if (file_exists($logFilePath)) {
            unlink($logFilePath);
        }

        // when
        $loggerService->log("errors", "ERROR", "An error occurred");

        // then
        $this->assertTrue(
            file_exists($logFilePath),
            "it should create log file"
        );
        $this->assertStringContainsString(
            "An error occurred",
            file_get_contents($logFilePath),
            "it should write log file"
        );
    }

    public function testLogForCriticalError()
    {
        // given
        $loggerService = new LoggerService();
        $logFilePath = __DIR__ . "/../../../app/logs/errors.log";
        if (file_exists($logFilePath)) {
            unlink($logFilePath);
        }

        // when
        $loggerService->log("errors", "CRITICAL ERROR", "A critical error occurred");


        // then
        $this->assertTrue(
            file_exists($logFilePath),
            "it should create log file"
        );
        $this->assertStringContainsString(
            "A critical error occurred",
            file_get_contents($logFilePath),
            "it should write log file"
        );
    }
}
