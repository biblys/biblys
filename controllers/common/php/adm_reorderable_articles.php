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

use Biblys\Service\CurrentSite;
use Biblys\Service\QueryParamsService;
use Model\BookCollectionQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @return JsonResponse
 * @throws Exception
 */
return function(CurrentSite $currentSite, QueryParamsService $paramsService) {
    $paramsService->parse(["collection_id" => ["type" => "numeric"]]);
    $collectionId = $paramsService->getInteger("collection_id");

    $collection = BookCollectionQuery::create()->findPk($collectionId);
    if (!$collection) {
        throw new NotFoundHttpException();
    }

    $query = "SELECT `articles`.`article_id`, `article_title`, `article_url`, `article_ean`,
        (SELECT `link_do_not_reorder` FROM `links` WHERE `articles`.`article_id` = `links`.`article_id` AND `link_do_not_reorder` = 1 AND `site_id` = :site_id) AS `dnr`
        FROM `articles`
        WHERE `articles`.`collection_id` = :collection_id 
          AND `type_id` != '2'
          AND `article_availability_dilicom` != 6";

    $articles = EntityManager::prepareAndExecute($query, [
        "site_id" => $currentSite->getId(),
        "collection_id" => $collectionId,
    ]);
    $articles = $articles->fetchAll(PDO::FETCH_ASSOC);

    $collectionArticles = [];
    foreach ($articles as $article) {
        $ventes = EntityManager::prepareAndExecute("
            SELECT DATE_FORMAT(`stock_selling_date`,'%Y-%m-%d') AS `lastSale` FROM `stock` 
            WHERE `article_id` = :article_id 
              AND `stock_condition` = 'Neuf' AND `stock_selling_date` IS NOT NULL ORDER BY `stock_selling_date` DESC
        ", ["article_id" => $article["article_id"]]);
        $vente = $ventes->fetch(PDO::FETCH_ASSOC);
        if ($vente !== false) {
            $article["lastSale"] = $vente["lastSale"];
        }
        $article["sales"] = $ventes->rowCount();

        if (!$article["sales"]) {
            continue;
        }

        $stock = EntityManager::prepareAndExecute("
            SELECT `stock_id` FROM `stock` 
                WHERE `stock`.`article_id` = :article_id AND `stock_condition` = 'Neuf' AND `stock`.`stock_selling_date` IS NULL AND `stock`.`stock_return_date` IS NULL AND `stock_lost_date` IS NULL",
            ["article_id" => $article["article_id"]]);
        $article["stock"] = $stock->rowCount();

        $collectionArticles[] = $article;
    }

    $result = [
        "collection" => $collection->getName(),
        "publisher" => $collection->getPublisher()->getName(),
        "articles" => $collectionArticles
    ];

    return new JsonResponse($result);
};
