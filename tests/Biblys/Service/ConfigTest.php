<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use Biblys\Service\Config;

require_once __DIR__."/../../setUp.php";


class ConfigTest extends PHPUnit\Framework\TestCase
{
    /**
     * @throws Exception
     */
    public function testConfigFromConstructor()
    {
        // given
        $config = new Config(["this_is" => "a_test"]);

        // when
        $option = $config->get("this_is");

        // then
        $this->assertEquals("a_test", $option);
    }

    public function testConfigFromFile()
    {
        // given
        $config = Config::load();

        // when
        $option = $config->get("config");

        // then
        $this->assertEquals(
            "for tests",
            $option
        );
    }

    public function testGetDefaultValue()
    {
        // given
        $config = new Config();

        // when
        $option = $config->get("media_path");

        // then
        $this->assertEquals("/public/media", $option);
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

    /**
     * @throws Exception
     */
    public function testHasReturnsTrue()
    {
        // given
        $config = new Config(["has" => "a value"]);

        // when
        $has = $config->has("has");

        // then
        $this->assertEquals(true, $has, "returns true if config has option for path");
    }

    /**
     * @throws Exception
     */
    public function testHasReturnsFalse()
    {
        // given
        $config = new Config(["has" => "a value"]);

        // when
        $has = $config->has("hasn't");

        // then
        $this->assertEquals(false, $has, "returns false if config has no option for path");
    }
}
