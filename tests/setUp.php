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

$config = Config::load();
setUpTestDatabase($config->get("db"));
require_once __DIR__."/../inc/functions.php";
$config->set("environment", "test");

$session = new \Symfony\Component\HttpFoundation\Session\Session();
$session->start();

/**
 * @throws PropelException
 */
function createFixtures(): void
{
    $site = new \Model\Site();
    $site->setTva("FR");
    $site->setTitle("Éditions Paronymie");
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
    new Biblys\Database\Database($dbConfig);
    createFixtures();
}