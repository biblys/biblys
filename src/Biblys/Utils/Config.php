<?php

namespace Biblys\Utils;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class Config
{
    private $config;

    public function __construct()
    {
        $configFilePath = self::_getConfigFilePath();

        // If config file does not exists, throw Exception
        if (!file_exists($configFilePath)) {
            throw new \Exception("Cannot find config file at $configFilePath.");
        }

        // Get global config
        $yaml = new Parser();

        try {
            $this->config = $yaml->parse(file_get_contents($configFilePath));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
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

    static private function _getConfigFilePath()
    {
        if (getenv("PHP_ENV")) {
            return BIBLYS_PATH . 'tests/config-for-tests.yml';
        }

        return BIBLYS_PATH . 'app/config.yml';
    }

    static private function _getDefaultValueForKey($key): ?string
    {
        if ($key === "site") {
            return 1;
        }

        if ($key === "users_table_name") {
            return "Users";
        }

        return null;
    }
}
