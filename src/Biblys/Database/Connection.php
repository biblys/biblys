<?php

namespace Biblys\Database;

class Connection
{
    public static function init($config): \PDO
    {
        if (!array_key_exists('port', $config)) {
            $config['port'] = 3306;
        }

        try {
            $_SQL = new \PDO(
                'mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['base'],
                $config['user'],
                $config['pass']
            );
            $_SQL->exec('SET CHARACTER SET utf8');
            $_SQL->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $GLOBALS['_SQL'] = $_SQL;

            return $_SQL;
        } catch (\PDOException $e) {
            throw new \Exception(
                'Cannot connect to MySQL server ' . $config['host'] . ':' . $config['port'] . ' #' . $e->getCode() . ': ' . $e->getMessage()
            );
        }
    }
}
