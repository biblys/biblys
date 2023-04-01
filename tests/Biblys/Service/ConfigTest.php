<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use Biblys\Service\Config;

require_once __DIR__."/../../setUp.php";


class ConfigTest extends PHPUnit\Framework\TestCase
{
    public function testGetValueFromConstructor()
    {
        // given
        $config = new Config(["this_is" => "a_test"]);

        // when
        $option = $config->get("this_is");

        // then
        $this->assertEquals("a_test", $option);
    }

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

    /**
     * @throws Exception
     */
    public function testGetValueByPath()
    {
        // given
        $options = ["a" => [
            "really" => [
                "deep" => [
                    "option" => "value"
                ]
            ]
        ]];
        $config = new Config($options);

        // when
        $value = $config->get("a.really.deep.option");

        // then
        $this->assertEquals("value", $value);
    }
}
