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

use Biblys\Exception\InvalidConfigurationException;
use Exception;
use Symfony\Component\Yaml\Parser;

class Config
{
    private array $options;

    /**
     * @throws Exception
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function get($path = null): array|bool|string|null
    {
        $optionByPath = $this->_getOptionByPath($path);
        if ($optionByPath !== null) {
            return $optionByPath;
        }

        return self::_getDefaultValueForPath($path);
    }

    public function set($level1key, $value): void
    {
        $this->options[$level1key] = $value;
    }

    public function has($path): bool
    {
        $option = $this->get($path);
        if ($option !== null) {
            return true;
        }

        return false;
    }

    private function _getOptionByPath(mixed $path): array|bool|string|null
    {
        $pathSteps = explode(".", $path);

        $current = $this->options;
        foreach($pathSteps as $step) {
            if (!isset($current[$step])) {
                return null;
            }
            $current = $current[$step];
        }

        return $current;
    }

    private static function _getConfigFilePath(): string
    {
        if (getenv("PHP_ENV") === "test") {
            return __DIR__ . "/../../../tests/config-for-tests.yml";
        }

        $legacyFilePath = __DIR__ . "/../../../app/config.yml";
        if (file_exists($legacyFilePath)) {
            return $legacyFilePath;
        }

        return __DIR__ . "/../../../config.yml";
    }

    private static function _getDefaultValueForPath($path): ?string
    {
        if ($path === "site") {
            return 1;
        }

        if ($path === "composer_home") {
            return "~/.composer";
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public static function _getOptionsFromConfigFile(): array
    {
        $configFilePath = self::_getConfigFilePath();
        if (!file_exists($configFilePath)) {
            throw new Exception("Cannot find config file at $configFilePath.");
        }

        $yaml = new Parser();
        return $yaml->parse(file_get_contents($configFilePath));
    }

    /**
     * @throws Exception
     */
    public static function load(): Config
    {
        $options = self::_getOptionsFromConfigFile();
        return new Config($options);
    }

    /**
     * @throws InvalidConfigurationException
     */
    public function getAuthenticationSecret(): string
    {
        $secret = $this->get('authentication.secret');

        if (strlen($secret) !== 32) {
            throw new InvalidConfigurationException(
                "Authentication secret must be 32 characters long."
            );
        }

        return $secret;
    }

    public function isAxysEnabled(): bool
    {
        $clientId = $this->get("axys.client_id");
        $clientSecret = $this->get("axys.client_secret");
        if ($clientId && $clientSecret) {
            return true;
        }

        return false;
    }

    public function isMondialRelayEnabled(): bool
    {
        $clientId = $this->get("mondial_relay.code_enseigne");
        $clientSecret = $this->get("mondial_relay.private_key");
        if ($clientId && $clientSecret) {
            return true;
        }

        return false;
    }

    public function isStripeEnabled(): bool
    {
        if ($this->get("stripe.secret_key") && $this->get("stripe.public_key") && $this->get("stripe.endpoint_secret")) {
            return true;
        }

        return false;
    }

    public function isPayPalEnabled(): bool
    {
        if ($this->get("paypal.client_id") && $this->get("paypal.client_secret")) {
            return true;
        }

        return false;
    }

    public function getImagesPath(): string
    {
        return $this->get("images.path") ?? "public/images/";
    }

    public function getImagesBaseUrl(): string
    {
        return $this->get("images.base_url") ?? "/images/";
    }

    public function isCacheEnabled(): bool
    {
        if ($this->get("cache.driver") === "filesystem") {
            return true;
        }

        if ($this->get("cache.driver") === "apc") {
            return true;
        }

        return false;
    }
}
