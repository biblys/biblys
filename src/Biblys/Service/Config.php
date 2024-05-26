<?php

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

        return __DIR__ . "/../../../app/config.yml";
    }

    private static function _getDefaultValueForPath($path): ?string
    {
        if ($path === "site") {
            return 1;
        }

        if ($path === "media_path") {
            return "/public/media";
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
}
