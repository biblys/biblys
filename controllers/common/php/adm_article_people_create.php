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


use Biblys\Service\BodyParamsService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @throws Exception
 */
return function (Request $request, BodyParamsService $params): JsonResponse {
    $pm = new PeopleManager();

    $params->parse([
        "people_first_name" => ["type" => "string"],
        "people_last_name" => ["type" => "string"],
    ]);

    $people = $pm->create([
        "people_first_name" => $params->get("people_first_name"),
        "people_last_name" => $params->get("people_last_name"),
    ]);

    return new JsonResponse([
        "people_id" => $people->get('id'),
        "people_name" => $people->get('name'),
        "job_id" => 1
    ]);
};