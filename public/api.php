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


ini_set('display_errors', 'On');
error_reporting(E_ALL);

use Biblys\Service\Config;
use Framework\RouteLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../inc/constants.php";

$config = Config::load();
Biblys\Database\Connection::initPropel($config);

$routes = RouteLoader::load();

$container = include __DIR__."/../src/container.php";
$container->setParameter("routes", $routes);
$container->register("listener.error", ErrorListener::class)
    ->setArguments(["ApiBundle\Controller\ErrorController::exception"]);
$container->getDefinition("dispatcher")
    ->addMethodCall("addSubscriber", [new Reference("listener.error")]);

$framework = $container->get("framework");
$request = Request::createFromGlobals();

$response = $framework->handle($request);
$response->send();

$framework->terminate($request, $response);
