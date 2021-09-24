<?php

namespace Biblys\Database;

use Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PDO;
use PDOException;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;

class Connection
{
    /**
     * @throws Exception
     */
    public static function init(array $config): PDO
    {

        try {
            $_SQL = new PDO(
                self::getDsnFromConfig($config),
                $config["user"],
                $config["pass"]
            );
            $_SQL->exec("SET CHARACTER SET utf8");
            $_SQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $GLOBALS["_SQL"] = $_SQL;

            return $_SQL;
        } catch (PDOException $e) {
            throw new Exception(
                "Cannot connect to MySQL server " . $config["host"] . ":" . $config["port"] . " #" . $e->getCode() . ": " . $e->getMessage()
            );
        }
    }

    public static function initPropel(array $config)
    {
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->checkVersion(2);
        $serviceContainer->setAdapterClass("default", "mysql");

        $propelConfig = [
            "dsn" => self::getDsnFromConfig($config),
            "user" => $config["user"],
            "password" => $config["pass"],
            "settings" => [
                "charset" => "utf8",
                "queries" => [],
            ],
            "classname" => "\\Propel\\Runtime\\Connection\\ConnectionWrapper",
            "model_paths" => [
                0 => "src",
                1 => "vendor",
            ],
        ];
        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration($propelConfig);
        $manager->setName("default");

        $serviceContainer->setConnectionManager("default", $manager);
        $serviceContainer->setDefaultDatasource("default");

        $defaultLogger = new Logger("defaultLogger");
        $defaultLogger->pushHandler(new StreamHandler(__DIR__ . "/../app/logs/propel.log", Logger::WARNING));
        $serviceContainer->setLogger("defaultLogger", $defaultLogger);

        require_once __DIR__ . "/./loadDatabase.php";
    }
    
    public static function getDsnFromConfig(array $config): string
    {
        $dbPort = self::_getDbPortFromConfig($config);
        return "mysql:host=" . $config["host"] . ";port=" . $dbPort . ";dbname=" . $config["base"];   
    }

    private static function _getDbPortFromConfig(array $config): int
    {
        if (getenv("DB_PORT")) {
            return getenv("DB_PORT");
        }

        if (array_key_exists("port", $config)) {
            return $config["port"];
        }

        return 3306;
    }
}
