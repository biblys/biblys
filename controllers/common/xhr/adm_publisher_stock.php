<?php

use Symfony\Component\HttpFoundation\JsonResponse;

$am = new ArticleManager();

$articleId = $request->query->get("article_id");
$publisherStock = $request->query->get("article_publisher_stock");

$article = $am->getById($articleId);
if (!$article) {
    throw new Exception("Impossible de trouver l'article $articleId.");
}

$article->set("article_publisher_stock", $publisherStock);
$am->update($article);

$response = new JsonResponse(["ok" => 1]);
$response->send();
