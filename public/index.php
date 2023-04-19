<?php

use AppBundle\Controller\ErrorController;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Framework\RouteLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;

/** @var Site $site */

// INCLUDES
include __DIR__."/../inc/functions.php";

$config = Config::load();

$dbConfig = $config->get("db");
Biblys\Database\Connection::initPropel($dbConfig);

// Identification utilisateur
/** @var $_V */
if ($_V->isLogged()) {
    $_LOG = $_V;
}

$request = Request::createFromGlobals();

// TODO: use a DeprecationNoticesHandler class
// TODO: handle displaying error in JSON and CLI
set_error_handler(function ($level, $message) use ($config): void {
    if ($config->get("environment") !== "dev") {
        return;
    }

    $i = 0;
    $trace = "";
    foreach (debug_backtrace() as $b) {

        if (!isset($b['file']) || !isset($b['line']) || !isset($b['function'])) {
            continue;
        }

        $trace .= "#{$i} {$b['file']}({$b['line']}): {$b['function']}\n";
        $i++;
    }

    echo "<div class=\"biblys-warning noprint\">
            DEPRECATED: {$message}
            <pre>{$trace}</pre>
        </div>";
}, E_USER_DEPRECATED ^ E_DEPRECATED);


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
