<?php

namespace Biblys\Database;

class Connection
{
    public static function init(array $config): \PDO
    {
        $dbPort = self::_getDbPort($config);

        try {
            $_SQL = new \PDO(
                'mysql:host=' . $config['host'] . ';port=' . $dbPort . ';dbname=' . $config['base'],
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

    private static function _getDbPort(array $config): int
    {
        if (getenv("DB_PORT")) {
            return getenv("DB_PORT");
        }

        if (array_key_exists('port', $config)) {
            return $config['port'];
        }

        return 3306;
    }
}
