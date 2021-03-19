<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use Biblys\Utils\Config;

require_once "tests/setUp.php";


class ConfigTest extends PHPUnit\Framework\TestCase
{
    public function testGetValueFromFile()
    {
        // given
        $config = new Config();

        // when
        $dbConfig = $config->get("db");

        // then
        $this->assertEquals(
            "127.0.0.1",
            $dbConfig["host"]
        );
    }

    public function testGetDefaultValue()
    {
        // given
        $config = new Config();

        // when
        $siteId = $config->get("site");

        // then
        $this->assertEquals(
            1,
            $siteId
        );
    }
}
