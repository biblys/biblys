<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

use Biblys\Service\Config;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../inc/constants.php";

$config = new Config();
Biblys\Database\Connection::initPropel($config->get("db"));

$routes = require __DIR__ . "/../src/ApiBundle/routes.php";
$urlgenerator = new UrlGenerator($routes, new RequestContext());

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
