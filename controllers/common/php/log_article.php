<?php

/** @var Visitor $_V */

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

if (!$_V->isAdmin() && !$_V->isPublisher()) {
    throw new AccessDeniedHttpException("Page réservée aux éditeurs.");
}

/** @var Site $site */
$publisherId = $_V->getCurrentRight()->get("publisher_id");
if (!$site->allowsPublisherWithId($publisherId)) {
    $pm = new PublisherManager();
    throw new AccessDeniedHttpException("Votre maison d'édition n'est pas autorisée sur ce site.");
}

return require_once("adm_article.php");
