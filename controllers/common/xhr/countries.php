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



use Biblys\Legacy\LegacyCodeHelper;
use Symfony\Component\HttpFoundation\JsonResponse;

$request = LegacyCodeHelper::getGlobalRequest();
$cm = new CountryManager();

$term = $request->query->get("term");
if ($term) {
    $i = 0;
    $countries = $cm->getAll(
        ["country_name" => "LIKE ".$term]
    );
    $json = [];
    foreach ($countries as $c) {
        $json[$i]["label"] = $c["country_name"];
        $json[$i]["value"] = $c["country_name"];
        $json[$i]["country_name"] = $c["country_name"];
        $json[$i]["country_id"] = $c["country_id"];
        $i++;
    }
    
    $response = new JsonResponse($json);
    $response->send();
}
