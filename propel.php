<?php

use Biblys\Database\Connection;
use Biblys\Service\Config;

$config = new Config();
$dbConfig = $config->get("db");

return [
    'propel' => [
        'database' => [
            'connections' => [
                'default' => [
                    'adapter' => 'mysql',
                    'dsn' => Connection::getDsnFromConfig($dbConfig),
                    'user' => 'root',
                    'password' => '',
                    'settings' => [
                        'charset' => 'utf8'
                    ]
                ]
            ]
        ]
    ]
];
