<?php

ini_set("display_errors", "On");
error_reporting(E_ALL);

$_SERVER = [
    "HTTP_HOST" => "www.biblys.fr",
    "REQUEST_URI" => "/",
    "SERVER_NAME" => "localhost",
    "SCRIPT_NAME" => "index.php"
];

// Load database config
require_once "inc/constants.php";
$config = new Biblys\Utils\Config();
$dbConfig = $config->get("db");
if (!isset($dbConfig["test"])) {
    throw new Exception("Missing test database config!");
}

// Connect to test
$testDb = Biblys\Database\Connection::init($dbConfig["test"]);

// Reset test database
$testBaseName = $dbConfig["test"]["base"];
$testDb->exec("DROP DATABASE IF EXISTS `$testBaseName`");
$testDb->exec("CREATE DATABASE `$testBaseName`");
$testDb->exec("USE `$testBaseName`");
$sql = file_get_contents("db-schema.sql");
$testDb->exec($sql);

global $config;
require_once "inc/functions.php";
$config->set("environment", "test");
