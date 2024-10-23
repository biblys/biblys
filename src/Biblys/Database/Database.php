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