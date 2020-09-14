<?php

use Symfony\Component\HttpFoundation\JsonResponse;

use Biblys\Axys\Client as AxysClient;

// INCLUDES
if (file_exists('../inc/functions.php')) {
    include('../inc/functions.php');
} else {
    include('/home/biblys/biblys/prod/inc/functions.php');
}

$response = new JsonResponse();

// Config
$config = new Config();
$axysConfig = $config->get("axys");
$axys = new AxysClient($axysConfig);

// Identification utilisateur
if (auth()) {
    $_LOG = auth('log');
}

// PAGE EN COURS
$_PAGE = str_replace("/x/", "", $_SERVER["REQUEST_URI"]);
$_PAGE = explode('?', $_PAGE);
$_PAGE = $_PAGE[0];

// Verification page utilisateur et admin
$_PAGE_TYPE = substr($_PAGE, 0, 4);
if ($_PAGE_TYPE == "adm_" && !$_V->isAdmin() && !$_V->isPublisher() && !$_V->isBookshop() && !$_V->isLibrary()) {
    json_error(0, "Cette action est réservée aux administrateurs (".$_PAGE."). Veuillez vous <a href='".$axys->getLoginUrl()."'>identifier</a> ou <a href='".$axys->getSignupUrl()."'>créer un compte Axys</a>.");
}
if ($_PAGE_TYPE == "log_" and !$_V->isLogged()) {
    json_error(0, "Action impossible. Veuillez vous <a href='".$axys->getLoginUrl()."'>identifier</a> ou <a href='".$axys->getSignupUrl()."'>créer un compte Axys</a>.");
}

$_RESULT = null;

// Recherche de la page site, par defaut, ou 404
try {
    if (file_exists(BIBLYS_PATH.'controllers/'.$site->get('name').'/xhr/'.$_PAGE.'.php')) {
        $_PAGE = include(BIBLYS_PATH.'controllers/'.$site->get('name').'/xhr/'.$_PAGE.'.php');
    } elseif (file_exists(BIBLYS_PATH.'/controllers/common/xhr/'.$_PAGE.'.php')) {
        $_PAGE = include(BIBLYS_PATH.'/controllers/common/xhr/'.$_PAGE.'.php');
    } else {
        header("HTTP/1.0 404 Not Found");
        die('ERROR > Page introuvable');
    }
} catch (Exception $exception) {
    biblys_exception($exception);
}

// Close MySQL connection
$_SQL = null;
