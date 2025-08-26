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
use Biblys\Service\Slug\SlugService;
use Model\PeopleQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @throws Exception
 */
return function (Request $request, QueryParamsService $params): JsonResponse {
    $params->parse(["term" => ["type" => "string", "default" => ""]]);
    $term = $params->get("term");

    $slugService = new SlugService();
    $slugTerm = $slugService->slugify($term);

    $people = PeopleQuery::create()
        ->filterByUrl("%$slugTerm%", Criteria::LIKE)
        ->limit(25)
        ->find();

    $results = array_map(
        function ($people) {
            return [
                "label" => $people->getFullName(),
                "value" => $people->getId(),
            ];
        },
        $people->getData(),
    );

    return new JsonResponse(["results" => $results]);
};