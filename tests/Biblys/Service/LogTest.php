<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use Biblys\Service\Log;
use Monolog\Logger;

require_once __DIR__."/../../setUp.php";


class LogTest extends PHPUnit\Framework\TestCase
{
    public function testLog()
    {
        // given
        $logFilePath = biblysPath() . "app/logs/errors.log";
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
        $logFilePath = biblysPath() . "app/logs/errors.log";
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
