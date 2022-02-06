<?php

use AppBundle\Controller\ErrorController;
use Biblys\Legacy\LayoutBuilder;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Framework\Exception\ServiceUnavailableException;
use Framework\Framework;
use Framework\RouteLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;

// INCLUDES
include __DIR__."/../inc/functions.php";

$config = new Config();

$dbConfig = $config->get("db");
Biblys\Database\Connection::initPropel($dbConfig);

// Identification utilisateur
/** @var $_V */
if ($_V->isLogged()) {
    $_LOG = $_V;
}

$request = Request::createFromGlobals();

/** @var Site $site */
list($_JS_CALLS, $_CSS_CALLS) = LayoutBuilder::loadAssets($config, $_V, $site, $request);

$exceptionController = new ErrorController();

// Site closed
$closed = $site->getOpt('closed');
if ($closed) {
    if (!$_V->isAdmin()) {
        throw new ServiceUnavailableException($closed);
    }

    trigger_error(
        "
            Biblys est en mode maintenance (raison : $closed).<br />
            Durant la période de maintenance, le site est accessible uniquement<br />
            aux administrateurs mais son utilisation est déconseillée<br />
            et peut conduire à la perte de données.
        ",
        E_USER_WARNING
    );
}

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

if (!$response instanceof JsonResponse && $currentSite->hasOptionEnabled("use_legacy_layout_builder")) {
    $response = LayoutBuilder::wrapResponseInThemeLayout($site, $_CSS_CALLS, $_JS_CALLS, $_V, $urlgenerator, $request, $config, $response);
}

$response->send();
$framework->terminate($request, $response);
