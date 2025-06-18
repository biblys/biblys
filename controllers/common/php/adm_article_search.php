<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


use Biblys\Service\QueryParamsService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

return function (Request $request, QueryParamsService $queryParams): JsonResponse {
    $queryParams->parse(["term" => ["type" => "string"]]);
    $query = $request->query->get("term");

    $am = new ArticleManager();
    /** @var Article[] $articles */
    $articles = $am->search($query, ['limit' => 10]);

    $searchResults = array_map(function ($article) {
        return [
            "label" => sprintf("%s (%s%s)", $article->get("title"), $article->get("collection")->get("name"), numero($article->get("number"), ' - ')),
            "value" => $article->get("id"),
            "url" => "/pages/adm_stock?add=" . $article->get("id") . "#add",
            "article_id" => $article->get("id")
        ];
    }, $articles);

    $urlEncodedQuery = urlencode($query);
    $searchResults[] = [
        "label" => "→ Nouvel article pour « $query »",
        "value" => '',
        "url" => "/pages/article_edit?import=$urlEncodedQuery"
    ];

    return new JsonResponse(["results" => $searchResults]);
};