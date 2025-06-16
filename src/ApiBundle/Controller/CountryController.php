<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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

use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Framework\Controller;
use Model\Country;
use Model\CountryQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;

class CountryController extends Controller
{
    /**
     * @throws PropelException
     */
    public function searchAction(CurrentUser $currentUser, QueryParamsService $queryParams): JsonResponse
    {
        $currentUser->authAdmin();

        $queryParams->parse(["term" => ["type" => "string", "mb_min_length" => 3]]);
        $term = $queryParams->get("term");

        $countries = CountryQuery::create()
            ->filterByName("%$term%", Criteria::LIKE)
            ->find();

        $results = array_map(function (Country $country) {
            return [
                "label" => "{$country->getName()} ({$country->getCode()})",
                "value" => $country->getId(),
            ];
        }, $countries->getData());

        return new JsonResponse(["results" => $results]);
    }
}