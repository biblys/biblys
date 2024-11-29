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


namespace ApiBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Framework\Controller;
use Model\BookCollectionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;

class CollectionController extends Controller
{

    /**
     * @throws PropelException
     */
    public function searchAction(
        CurrentUser $currentUser,
        CurrentSite $currentSite,
        QueryParamsService $queryParams,
    ): JsonResponse
    {
        $currentUser->authPublisher();

        $queryParams->parse(["term" => ["type" => "string", "mb_min_length" => 3]]);
        $searchQuery = $queryParams->get("term");

        $collectionQuery = BookCollectionQuery::create()
            ->filterByName("%$searchQuery%", Criteria::LIKE)
            ->_or()
            ->filterByPublisherName("%$searchQuery%", Criteria::LIKE)
            ->limit(25);

        if ($currentUser->hasPublisherRight()) {
            $collectionQuery->filterByPublisherId($currentUser->getCurrentRight()->getPublisherId());
        }

        $collections = $collectionQuery->find()->getArrayCopy();

        $json = array_map(/**
         * @throws PropelException
         */ function ($collection) use($currentSite) {
            return [
                "label" => "{$collection->getName()} ({$collection->getPublisher()->getName()})",
                "value" => $collection->getName(),
                "collection_name" => $collection->getName(),
                "collection_publisher" => $collection->getPublisher()->getName(),
                "collection_id" => $collection->getId(),
                "publisher_id" => $collection->getPublisherId(),
                "pricegrid_id" => $collection->getPricegridId(),
                "publisher_allowed_on_site" => $currentSite->allowsPublisher($collection->getPublisher()) ? 1 : 0,
            ];
        }, $collections);

        $json[] = [
            "label" => "=> Créer : $searchQuery",
            "value" => $searchQuery,
            "create" => 1
        ];

        return new JsonResponse($json);
    }
}
