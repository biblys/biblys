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


namespace Biblys\Database;

use Biblys\Service\Config;
use Biblys\Service\LoggerService;
use Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PDO;
use PDOException;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class Connection
{
    /**
     * @throws Exception
     */
    public static function init(Config $config): PDO
    {

        try {
            $_SQL = new PDO(
                self::getDsnFromConfig($config),
                $config->get("db.user"),
                $config->get("db.pass"),
            );
            $_SQL->exec("SET CHARACTER SET utf8");
            $_SQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $GLOBALS["_SQL"] = $_SQL;

            return $_SQL;
        } catch (PDOException $e) {
            $logger = new LoggerService();
            $logger->log(
                logger: "errors",
                level: "ERROR",
                message: "Cannot connect to MySQL server ".$config->get("db.host").":" .$config->get("db.port")." #".$e->getCode().": ".$e->getMessage(),
            );
            throw new ServiceUnavailableHttpException(null, "An error ocurred while connecting to database.");
        }
    }

    /**
     * @throws PropelException
     */
    public static function initPropel(Config $config): void
    {
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->checkVersion(2);
        $serviceContainer->setAdapterClass("default", "mysql");

        $propelConfig = [
            "dsn" => self::getDsnFromConfig($config),
            "user" => $config->get("db.user"),
            "password" => $config->get("db.pass"),
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
        $manager = new ConnectionManagerSingle("default");
        $manager->setConfiguration($propelConfig);
        $manager->setName("default");

        $serviceContainer->setConnectionManager($manager);
        $serviceContainer->setDefaultDatasource("default");

        $defaultLogger = new Logger("defaultLogger");
        $defaultLogger->pushHandler(new StreamHandler(__DIR__ . "/../app/logs/propel.log", Logger::WARNING));
        $serviceContainer->setLogger("defaultLogger", $defaultLogger);

        require_once __DIR__ . "/./loadDatabase.php";
    }
    
    public static function getDsnFromConfig(Config $config): string
    {
        $dbPort = self::_getDbPortFromConfig($config);

        $host = $config->get("db.host");
        $hostForCli = $config->get("db.host_for_cli");
        if (php_sapi_name() === "cli" && $config->get("db.host_for_cli")) {
            $host = $hostForCli;
        }

        return "mysql:host=" . $host . ";port=" . $dbPort . ";dbname=" . $config->get("db.base");}

    private static function _getDbPortFromConfig(Config $config): int
    {
        if (getenv("DB_PORT")) {
            return getenv("DB_PORT");
        }

        if ($config->has("db.port")) {
            return $config->get("db.port");
        }

        return 3306;
    }
}
