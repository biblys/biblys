<?php

ini_set("display_errors", "On");
error_reporting(E_ALL);

$_SERVER["HTTP_HOST"] = "www.biblys.fr";
$_SERVER["REQUEST_URI"] = "/";
$_SERVER["SERVER_NAME"] = "localhost";
$_SERVER["SCRIPT_NAME"] = "index.php";

// Load database config
require_once "inc/constants.php";
$config = new Biblys\Utils\Config();
$dbConfig = $config->get("db");

// Connect to test
$testDb = Biblys\Database\Connection::init($dbConfig);

// Reset test database
$testBaseName = $dbConfig["base"];
$testDb->exec("DROP DATABASE IF EXISTS `$testBaseName`");
$testDb->exec("CREATE DATABASE `$testBaseName`");
$testDb->exec("USE `$testBaseName`");
$sql = file_get_contents("db-schema.sql");
$testDb->exec($sql);

// Include entity autoloader
require_once BIBLYS_PATH . "inc/autoload-entity.php";

// Create fixtures
$sm = new SiteManager();
$jm = new JobManager();
$cm = new CountryManager();
$sm->create([
    "site_id" => 1,
    "site_tva" => "fr",
    "site_title" => "Librairie Ys",
    "site_contact" => "librairieys@example.com",
]);
$jm->create(["job_id" => 1]);
$jm->create(["job_id" => 2]);
$cm->create(["country_id" => 67, "country_name" => "France"]);

require_once BIBLYS_PATH . "inc/functions.php";
$config->set("environment", "test");
