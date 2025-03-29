<?php /** @noinspection PhpIllegalPsrClassPathInspection */
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

    /**
     * @throws Exception
     */
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
        $option = $config->get("site");

        // then
        $this->assertEquals("1", $option);
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
        $this->assertTrue($has, "returns true if config has option for path");
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
        $this->assertFalse($has, "returns false if config has no option for path");
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

    /** isMondialRelayEnabled */

    /**
     * @throws Exception
     */
    public function testIsMondialRelayEnabledWhenDisabled(): void
    {
        // given
        $config = new Config([]);

        // then
        $isEnabled = $config->isMondialRelayEnabled();

        // then
        $this->assertFalse($isEnabled);
    }

    /**
     * @throws Exception
     */
    public function testIsMondialRelayEnabledWhenEnabled(): void
    {
        // given
        $config = new Config(["mondial_relay"  => [
            "code_enseigne" => "code_enseigne",
            "private_key" => "private_key",
        ]]);

        // then
        $isEnabled = $config->isMondialRelayEnabled();

        // then
        $this->assertTrue($isEnabled);
    }

    /** isStripeEnabled */

    /**
     * @throws Exception
     */
    public function testIsStripeEnabledWhenDisabled(): void
    {
        // given
        $config = new Config([]);

        // then
        $isEnabled = $config->isStripeEnabled();

        // then
        $this->assertFalse($isEnabled);
    }

    /**
     * @throws Exception
     */
    public function testIsStripeEnabledWhenEnabled(): void
    {
        // given
        $config = new Config(["stripe"  => [
            "public_key" => "abcd",
            "secret_key" => "1234",
            "endpoint_secret" => "!Ù€ô",
        ]]);

        // then
        $isEnabled = $config->isStripeEnabled();

        // then
        $this->assertTrue($isEnabled);
    }

    /** isPayPalEnabled */

    /**
     * @throws Exception
     */
    public function testIsPayPalEnabledWhenDisabled(): void
    {
        // given
        $config = new Config([]);

        // then
        $isEnabled = $config->isPayPalEnabled();

        // then
        $this->assertFalse($isEnabled);
    }

    /**
     * @throws Exception
     */
    public function testIsPayPalEnabledWhenEnabled(): void
    {
        // given
        $config = new Config(["paypal"  => [
            "client_id" => "abcd",
            "client_secret" => "1234",
        ]]);

        // then
        $isEnabled = $config->isPayPalEnabled();

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

    /** isCacheEnabled */

    /**
     * @throws Exception
     */
    public function testIsCacheEnabledReturnsFalseWithNoConfig(): void
    {
        // given
        $config = new Config();

        // when
        $isEnabled = $config->isCacheEnabled();

        // then
        $this->assertFalse($isEnabled);
    }

    /**
     * @throws Exception
     */
    public function testIsCacheEnabledReturnsTrueForFilesystemConfig(): void
    {
        // given
        $config = new Config(["cache" => ["driver" => "filesystem"]]);

        // when
        $isEnabled = $config->isCacheEnabled();

        // then
        $this->assertTrue($isEnabled);
    }

    /**
     * @throws Exception
     */
    public function testIsCacheEnabledReturnsTrueForApcConfig(): void
    {
        // given
        $config = new Config(["cache" => ["driver" => "apc"]]);

        // when
        $isEnabled = $config->isCacheEnabled();

        // then
        $this->assertTrue($isEnabled);
    }
}
