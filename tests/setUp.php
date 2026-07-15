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


use Biblys\Database\Connection;
use Biblys\Service\Config;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../inc/constants.php";

ini_set("display_errors", "On");
// Exclude deprecations: Propel-generated models in src/Model/Base use non-canonical
// cast syntax (e.g. `(boolean)`), deprecated since PHP 8.5. Displaying these notices
// produces output before session_start(), which then fails with "headers already sent".
error_reporting(E_ALL & ~E_DEPRECATED);

$_SERVER["HTTP_HOST"] = "www.biblys.fr";
$_SERVER["REQUEST_URI"] = "/";
$_SERVER["SERVER_NAME"] = "localhost";
$_SERVER["SCRIPT_NAME"] = "index.php";
$_SERVER["REMOTE_ADDR"] = "127.0.0.1";

$config = Config::load();

// Prevent tests being executed using any other table than the test database
$testDbName = $config->get("db.base");
if (!str_contains((string) $testDbName, "test")) {
    fwrite(STDERR,
        "Refusing to run tests: resolved database \"{$testDbName}\" does not look like a test database.\n" .
        "Run tests via `composer test` or `composer test:path`, which set PHP_ENV=test, " .
        "instead of calling vendor/bin/phpunit directly.\n"
    );
    exit(1);
}

Connection::initPropel($config);

require_once __DIR__."/../inc/functions.php";
$config->set("environment", "test");

$session = new \Symfony\Component\HttpFoundation\Session\Session();
$session->start();
