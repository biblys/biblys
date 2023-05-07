<?php

use AppBundle\Controller\ErrorController;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Framework\RouteLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;


// INCLUDES
include __DIR__."/../inc/functions.php";

$config = Config::load();
Biblys\Database\Connection::initPropel($config);

$request = Request::createFromGlobals();
$session = new Session();
$session->start();
$request->setSession($session);

catchDeprecationNotices($config, $session);

$exceptionController = new ErrorController();

$routes = RouteLoader::load();
$GLOBALS["urlgenerator"] = new UrlGenerator($routes, new RequestContext());

$container = include __DIR__."/../src/container.php";
$container->register("listener.error", ErrorListener::class)
    ->setArguments(["AppBundle\Controller\ErrorController::exception"]);
$container->getDefinition("dispatcher")
    ->addMethodCall("addSubscriber", [new Reference("listener.error")]);


$framework = $container->get("framework");
$originalRequest = $request; // used by LegacyController
/** @var Response $response */
$response = $framework->handle($request);

// Ugly hack to prevent HttpKernel to set response's status code to 404
// when request when through the ErrorController and LegacyController
// because routes does not exist in new controllers
if (
    $response->getStatusCode() === 404 &&
    $response->headers->has("should-reset-status-code-to-200")
) {
    $response->setStatusCode(200);
}

// Set security headers
$response->headers->set('X-XSS-Protection', '1; mode=block');
$response->headers->set('X-Content-Type-Options', 'nosniff');
$response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
$response->headers->set('X-Frame-Options', 'DENY');

// Set HTTP Strict Transport Security to one month if config has option
if ($request->isSecure() && $config->get('hsts') === true) {
    $response->headers->set('Strict-Transport-Security', 'max-age=15768000');
}

$currentSite = CurrentSite::buildFromConfig($config);

$response->send();
$framework->terminate($request, $response);
