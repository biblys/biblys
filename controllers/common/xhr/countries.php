<?php

global $request;

use Symfony\Component\HttpFoundation\JsonResponse;

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
