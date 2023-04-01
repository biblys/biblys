<?php

namespace Biblys\Service;

use Exception;
use Symfony\Component\Yaml\Parser;

class Config
{
    private $config;

    /**
     * @throws Exception
     */
    public function __construct(array $options = [])
    {
        if ($options !== []) {
            $this->config = $options;
            return;
        }

        $this->config = self::_getConfigFromFile();
    }

    public function get($key = null)
    {
        if (!$key) {
            return $this->config;
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        if (self::_getDefaultValueForKey($key) !== null) {
            return self::_getDefaultValueForKey($key);
        }

        return false;
    }

    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    private static function _getConfigFilePath(): string
    {
        if (getenv("PHP_ENV") === "test") {
            return __DIR__ . "/../../../tests/config-for-tests.yml";
        }

        return __DIR__ . "/../../../app/config.yml";
    }

    private static function _getDefaultValueForKey($key): ?string
    {
        if ($key === "site") {
            return 1;
        }

        if ($key === "media_path") {
            return "/public/media";
        }

        if ($key === "composer_home") {
            return "~/.composer";
        }

        return null;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public static function _getConfigFromFile(): mixed
    {
        $configFilePath = self::_getConfigFilePath();
        if (!file_exists($configFilePath)) {
            throw new Exception("Cannot find config file at $configFilePath.");
        }

        $yaml = new Parser();
        return $yaml->parse(file_get_contents($configFilePath));
    }
}
