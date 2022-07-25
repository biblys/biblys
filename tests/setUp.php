<?php

use Biblys\Service\Config;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../inc/constants.php";

ini_set("display_errors", "On");
error_reporting(E_ALL);

$_SERVER["HTTP_HOST"] = "www.biblys.fr";
$_SERVER["REQUEST_URI"] = "/";
$_SERVER["SERVER_NAME"] = "localhost";
$_SERVER["SCRIPT_NAME"] = "index.php";
$_SERVER["REMOTE_ADDR"] = "127.0.0.1";

$config = new Config();
setUpTestDatabase($config->get("db"));
require_once BIBLYS_PATH . "inc/functions.php";
$config->set("environment", "test");

/**
 * @throws PropelException
 */
function createFixtures(): void
{
    $site = new \Model\Site();
    $site->setTva("FR");
    $site->setTitle("Librairie Ys");
    $site->setContact("contact@biblys.fr");
    $site->setTag("YS");
    $site->save();
}

/**
 * @throws PropelException
 * @throws Exception
 */
function setUpTestDatabase($dbConfig)
{
    $db = new Biblys\Database\Database($dbConfig);
    $db->reset();
    $db->migrate();
    createFixtures();
}