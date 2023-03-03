<?php

namespace Biblys\Service;

use PHPUnit\Framework\TestCase;

class LoggerServiceTest extends TestCase
{

    public function testLog()
    {
        // given
        $logFilePath = __DIR__ . "/../../../app/logs/errors.log";
        if (file_exists($logFilePath)) {
            unlink($logFilePath);
        }

        // when
        Log::error("ERROR", "An error occured");

        // then
        $this->assertTrue(
            file_exists($logFilePath),
            "it should create log file"
        );
        $this->assertStringContainsString(
            "An error occured",
            file_get_contents($logFilePath),
            "it should write log file"
        );
    }

    public function testLogForCriticalError()
    {
        // given
        $logFilePath = __DIR__ . "/../../../app/logs/errors.log";
        if (file_exists($logFilePath)) {
            unlink($logFilePath);
        }

        // when
        Log::error("CRITICAL ERROR", "A critical error occured");

        // then
        $this->assertTrue(
            file_exists($logFilePath),
            "it should create log file"
        );
        $this->assertStringContainsString(
            "A critical error occured",
            file_get_contents($logFilePath),
            "it should write log file"
        );
    }
}
