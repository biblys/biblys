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
use Model\CycleQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @throws Exception
 */
return function (Request $request, QueryParamsService $params): JsonResponse {
    if ($request->getMethod() === 'POST') {
        $cycleName = filter_input(INPUT_POST, 'cycle_name', FILTER_SANITIZE_SPECIAL_CHARS);

        $cm = new CycleManager();
        $cycle = $cm->get(['cycle_name' => $cycleName]);
        if (!$cycle) {
            $cycle = $cm->create(['cycle_name' => $cycleName]);
        }

        $json = [
            'cycle_id' => $cycle->get('id'),
            'cycle_name' => $cycle->get('name')
        ];
        return new JsonResponse($json);
    }

    $params->parse(["term" => ["type" => "string"]]);
    $term = $params->get("term");

    $cycles = CycleQuery::create()
        ->filterByName("%$term%", Criteria::LIKE)
        ->orderByName()
        ->limit(10)
        ->find()
        ->getData();

    $results = array_map(
        function ($cycle) {
            return [
                "value" => $cycle->getId(),
                "label" => $cycle->getName(),
            ];
        },
        $cycles
    );

    return new JsonResponse(["results" => $results]);
};
