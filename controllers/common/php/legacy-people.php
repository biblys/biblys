<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

$am = new ArticleManager();

/** @var Request $request */
$peopleUrl  = $request->query->get("url");
return new RedirectResponse("/legacy/p/$peopleUrl/", 301);
