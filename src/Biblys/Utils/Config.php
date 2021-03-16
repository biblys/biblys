<?php

namespace Biblys\Utils;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class Config
{
    private $config;

    public function __construct()
    {
        // Config file path
        $file = BIBLYS_PATH . '/app/config.yml';

        // If config file does not exists, throw Exception
        if (!file_exists($file)) {
            throw new \Exception("Config file not found.");
        }

        // Get global config
        $yaml = new Parser();

        try {
            $this->config = $yaml->parse(file_get_contents($file));
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

        return false;
    }

    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }
}
