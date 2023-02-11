<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

$am = new ArticleManager();

/** @var Request $request */
$peopleSlug  = $request->query->get("url");
$queryParams = $request->query->all();
unset($queryParams["page"], $queryParams["url"]);
$queryString = http_build_query($queryParams);

$legacyUrl = "/legacy/p/$peopleSlug/?$queryString";

return new RedirectResponse($legacyUrl, 301);
