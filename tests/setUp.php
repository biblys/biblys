<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$_SERVER = [
    "HTTP_HOST" => "www.biblys.fr",
    "REQUEST_URI" => "/",
    "SERVER_NAME" => "localhost",
    "SCRIPT_NAME" => "index.php"
];

global $config;
require_once('inc/functions.php');
$config->set('environment', 'test');
