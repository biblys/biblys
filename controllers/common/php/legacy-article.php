<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

$am = new ArticleManager();

/** @var Request $request */
$articleUrl  = $request->query->get("url");
return new RedirectResponse("/legacy/a/$articleUrl", 301);
