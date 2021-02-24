<?php

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
