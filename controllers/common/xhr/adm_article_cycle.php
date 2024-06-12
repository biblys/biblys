<?php

use Symfony\Component\HttpFoundation\JsonResponse;

$json = [];

$term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_SPECIAL_CHARS);
if ($term) {
    $i = 0;
    $cycles = $_SQL->prepare("SELECT `cycle_id`, `cycle_name` FROM `cycles` WHERE `cycle_name` LIKE :term ORDER BY `cycle_name`");
    $cycles->execute(['term' => '%'.$term.'%']);
    while ($c = $cycles->fetch(PDO::FETCH_ASSOC)) {
        $json[$i]["label"] = $c["cycle_name"];
        $json[$i]["value"] = $c["cycle_name"];
        $json[$i]["cycle_id"] = $c["cycle_id"];
        $json[$i]["cycle_name"] = $c["cycle_name"];
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
