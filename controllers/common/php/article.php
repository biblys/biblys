<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$am = new ArticleManager();

$articleUrl  = $request->query->get("url");
$article = $am->get(["article_url" => $articleUrl]);

if (!$article) {
    throw new ResourceNotFoundException("No article for url " . $articleUrl . ".");
} else {
    redirect(\Biblys\Legacy\LegacyCodeHelper::getGlobalUrlGenerator()->generate('article_show', ['slug' => $article->get('url')]), null, 301);
}

