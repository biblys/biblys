<?php

use Biblys\Database\Connection;
use Biblys\Service\Config;

$config = Config::load();

$propelConfig = [
    'propel' => [
        'database' => [
            'connections' => [
                'default' => [
                    'adapter' => 'mysql',
                    'dsn' => Connection::getDsnFromConfig($config),
                    'user' => $config->get("db.user"),
                    'password' => $config->get("db.pass"),
                    'settings' => [
                        'charset' => 'utf8'
                    ]
                ]
            ]
        ]
    ]
];

return $propelConfig;
