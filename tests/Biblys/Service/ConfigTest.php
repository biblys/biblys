<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use Biblys\Exception\InvalidConfigurationException;
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

    public function testGetAuthenticationSecretIfTooShort(): void
    {
        // given
        $config = new Config(["authentication" => ["secret" => "too short"]]);

        // then
        $this->expectException(InvalidConfigurationException::class);

        // when
        $config->getAuthenticationSecret();
    }

    public function testGetAuthenticationSecret(): void
    {
        // given
        $config = new Config(["authentication" => [
            "secret" => "5fc8ae2cb08b95804b4e3c57b90ee37fdadbb53f2b62826c42c7afd18d64f04b"
        ]]);

        // then
        $this->expectException(InvalidConfigurationException::class);

        // when
        $config->getAuthenticationSecret();
    }

    /** isAxysEnabled */

    public function testIsAxysEnabledWhenDisabled(): void
    {
        // given
        $config = new Config([]);

        // then
        $isEnabled = $config->isAxysEnabled();

        // then
        $this->assertFalse($isEnabled);
    }

    public function testIsAxysEnabledWhenEnabled(): void
    {
        // given
        $config = new Config(["axys"  => [
            "client_id" => "client_id",
            "client_secret" => "client_secret",
        ]]);

        // then
        $isEnabled = $config->isAxysEnabled();

        // then
        $this->assertTrue($isEnabled);
    }

    /** getImagesPath */

    /**
     * @throws Exception
     */
    public function testGetImagesPathReturnsConfigValue(): void
    {
        // given
        $config = new Config(["images" => ["path" => "/path/to/images"]]);

        // when
        $path = $config->getImagesPath();

        // then
        $this->assertEquals("/path/to/images", $path);
    }

    /**
     * @throws Exception
     */
    public function testGetImagesPathReturnsDefaultValue(): void
    {
        // given
        $config = new Config();

        // when
        $path = $config->getImagesPath();

        // then
        $this->assertEquals("public/images/", $path);
    }

    /** getImagesBaseUrl */

    /**
     * @throws Exception
     */
    public function testGetImagesBaseUrlReturnsConfigValue(): void
    {
        // given
        $config = new Config(["images" => ["base_url" => "https://paronymie.fr/images/"]]);

        // when
        $baseUrl = $config->getImagesBaseUrl();

        // then
        $this->assertEquals("https://paronymie.fr/images/", $baseUrl);
    }

    /**
     * @throws Exception
     */
    public function testGetImagesBaseUrlReturnsDefaultValue(): void
    {
        // given
        $config = new Config();

        // when
        $baseUrl = $config->getImagesBaseUrl();

        // then
        $this->assertEquals("/images/", $baseUrl);
    }
}
