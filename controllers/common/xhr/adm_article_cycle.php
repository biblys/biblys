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


use Symfony\Component\HttpFoundation\JsonResponse;

$json = [];

$term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_SPECIAL_CHARS);
if ($term) {
    $i = 0;
    $cm = new CycleManager();
    $cycles = $cm->getAll(['cycle_name' => "LIKE %$term%"]);
    foreach ($cycles as $cycle) {
        $json[$i]["label"] = $cycle->get("cycle_name");
        $json[$i]["value"] = $cycle->get("cycle_name");
        $json[$i]["cycle_id"] = $cycle->get("cycle_id");
        $json[$i]["cycle_name"] = $cycle->get("cycle_name");
        $i++;
    }
    $json[$i]["label"] = '=> Créer : '.$_GET["term"];
    $json[$i]["value"] = $_GET["term"];
    $json[$i]["create"] = 1;
} elseif ($request->getMethod() === 'POST') {

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
}

$response = new JsonResponse($json);
$response->send();
