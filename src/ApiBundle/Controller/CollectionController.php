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

use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Exception\InvalidEntityException;
use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Framework\Controller;
use Model\BookCollection;
use Model\BookCollectionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

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

        $queryParams->parse(["term" => ["type" => "string"]]);
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

        $results = array_map(/**
         * @throws PropelException
         */ function ($collection) use($currentSite) {
            return [
                "label" => "{$collection->getName()} ({$collection->getPublisher()->getName()})",
                "value" => $collection->getId(),
            ];
        }, $collections);

        return new JsonResponse(["results" => $results]);
    }

    /**
     * @throws PropelException
     */
    public function createAction(
        CurrentUser $currentUser,
        BodyParamsService $bodyParams,
    ): JsonResponse
    {
        $currentUser->authPublisher();

        $bodyParams->parse([
            "collection_name" => ["type" => "string"],
            "collection_publisher" => ["type" => "string"],
            "collection_publisher_id" => ["type" => "numeric"],
        ]);

        $targetPublisherId = $bodyParams->getInteger("collection_publisher_id");
        if (!($this->_currentUserCanManagePublisher($currentUser, $targetPublisherId))) {
            throw new BadRequestHttpException("Vous n'avez pas le droit de créer une collection pour cet éditeur.");
        }

        $collection = new BookCollection();
        $collection->setName($bodyParams->get("collection_name"));
        $collection->setPublisherId($targetPublisherId);

        try {
            $collection->save();
        } catch (InvalidEntityException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        } catch (EntityAlreadyExistsException $exception) {
            throw new ConflictHttpException($exception->getMessage());
        }

        return new JsonResponse([
            "collection_id" => $collection->getId(),
            "collection_name" => $collection->getName(),
            "collection_publisher" => $collection->getPublisher()->getName(),
            "collection_publisher_id" => $collection->getPublisherId(),
        ], 201);
    }

    /**
     * @throws PropelException
     */
    private function _currentUserCanManagePublisher(CurrentUser $currentUser, int $targetPublisherId): bool
    {
        if ($currentUser->isAdmin()) {
            return true;
        }

        if (!$currentUser->hasPublisherRight()) {
            return false;
        }

        return $currentUser->getCurrentRight()->getPublisherId() === $targetPublisherId;
    }
}
