<?php

use ApiBundle\Controller\ErrorController;
use Framework\RouteLoader;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;

// INCLUDES
require_once(__DIR__."/../inc/functions.php");

$response = new JsonResponse();

// Config
$config = new Biblys\Service\Config();

$dbConfig = $config->get("db");
Biblys\Database\Connection::initPropel($dbConfig);

$axysConfig = $config->get("axys");

// Identification utilisateur
if (auth()) {
    $_LOG = auth('log');
}

// PAGE EN COURS
$_PAGE = str_replace("/x/", "", $_SERVER["REQUEST_URI"]);
$_PAGE = explode('?', $_PAGE);
$_PAGE = $_PAGE[0];

// Verification page utilisateur et admin
$request = Request::createFromGlobals();
$routes = RouteLoader::load();
$urlGenerator = new UrlGenerator($routes, new RequestContext());
$currentUrl = $request->getSchemeAndHttpHost().$request->getBaseUrl().$request->getPathInfo();
$loginUrl = $urlGenerator->generate("user_login", ["return_url" => $currentUrl]);
$_PAGE_TYPE = substr($_PAGE, 0, 4);
/** @var Visitor $_V */
if ($_PAGE_TYPE == "adm_" && !$_V->isAdmin() && !$_V->isPublisher() && !$_V->isBookshop() && !$_V->isLibrary()) {
    json_error(0, "Cette action est réservée aux administrateurs (".$_PAGE."). Veuillez vous <a href='".$loginUrl."'>identifier</a>.");
}
if ($_PAGE_TYPE == "log_" and !$_V->isLogged()) {
    json_error(0, "Action impossible. Veuillez vous <a href='".$loginUrl."'>identifier</a>.");
}

$_RESULT = null;

// Recherche de la page site, par defaut, ou 404
try {
    /** @var Site $site */
    if (file_exists(__DIR__.'/../controllers/'.$site->get('name').'/xhr/'.$_PAGE.'.php')) {
        $_PAGE = include(__DIR__.'/../controllers/'.$site->get('name').'/xhr/'.$_PAGE.'.php');
    } elseif (file_exists(__DIR__.'/../controllers/common/xhr/'.$_PAGE.'.php')) {
        $_PAGE = include(__DIR__.'/../controllers/common/xhr/'.$_PAGE.'.php');
    } else {
        header("HTTP/1.0 404 Not Found");
        die('ERROR > Page introuvable');
    }
} catch (Throwable $exception) {
    $controller = new ErrorController();
    $response = $controller->exception($exception);
    $response->send();
}

// Close MySQL connection
$_SQL = null;
