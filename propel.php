<?php

use Biblys\Database\Connection;
use Biblys\Service\Config;

$config = Config::load();
$dbConfig = $config->get("db");

$propelConfig = [
    'propel' => [
        'database' => [
            'connections' => [
                'default' => [
                    'adapter' => 'mysql',
                    'dsn' => Connection::getDsnFromConfig($dbConfig),
                    'user' => $dbConfig["user"],
                    'password' => $dbConfig["pass"],
                    'settings' => [
                        'charset' => 'utf8'
                    ]
                ]
            ]
        ]
    ]
];

return $propelConfig;
