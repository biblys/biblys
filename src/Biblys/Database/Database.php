<?php

namespace Biblys\Database;

use Biblys\Service\Config;
use Exception;
use Framework\Composer\ScriptRunner;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Propel;

class Database
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var ConnectionInterface
     */
    private $connection;

    public function __construct(array $dbConfig)
    {
        $config = new Config(["db" => $dbConfig]);
        Connection::initPropel($config);

        $this->config = $dbConfig;
        $this->connection = Propel::getConnection();
    }

    public function reset()
    {
        $this->connection->exec(sprintf("DROP DATABASE IF EXISTS `%s`", $this->config["base"]));
        $this->connection->exec(sprintf("CREATE DATABASE `%s`", $this->config["base"]));
        $this->connection->exec(sprintf("USE `%s`", $this->config["base"]));
    }

    /**
     * @throws Exception
     */
    public function migrate()
    {
        ScriptRunner::run("propel:migrate");
    }
}