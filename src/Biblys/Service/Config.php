<?php

namespace Biblys\Service;

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
        if ($options === []) {
            trigger_deprecation(
                "biblys/biblys",
                "2.67.0",
                "Calling Config constructor without option is deprecated. Pass an array of options as the constructor's argument or use Config::load to load options from config file.",
            );
            $this->options = self::_getOptionsFromConfigFile();
            return;
        }

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
     * @return mixed
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
}
