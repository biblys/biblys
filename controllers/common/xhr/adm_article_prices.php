<?php

use Symfony\Component\HttpFoundation\JsonResponse;

if ($request->getMethod() === 'POST') { // Creer une nouvelle categorie
    
    $priceCat = $request->request->get('price_cat');
    $pricegridId = $request->request->get('pricegrid_id');
    $priceAmount = $request->request->get('price_amount');

    $pm = new PriceManager();
    
    $price = $pm->get(['price_cat' => $priceCat, 'pricegrid_id' => $pricegridId]);
    if (!$price) {
        $price = $pm->create([]);
    }
    $price->set('pricegrid_id', $pricegridId);
    $price->set('price_cat', $priceCat);
    $price->set('price_amount', $priceAmount);
    $pm->update($price);

    $response = new JsonResponse(
        [
            'pricegrid_id' => $price->get('pricegrid_id'),
            'price_cat' => $price->get('cat'),
            'price_amount' => $price->get('amount'),
        ]
    );
} else {

    $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_SPECIAL_CHARS);
    $pricegridId = filter_input(
        INPUT_GET, 'pricegrid_id', FILTER_SANITIZE_NUMBER_INT
    );

    $req = null;
    $params = [];
    if (isset($term)) {
        $req = "AND `price_cat` LIKE :term";
        $params['term'] = '%'.$term.'%';
    }
    
    $i = 0;
    
    $json[$i]["label"] = 'Aucune';
    $json[$i]["value"] = '';
    $json[$i]["price"] = 0;
    $json[$i]["none"] = 1;
    $i++;
    
    $prices = $_SQL->prepare(
        "SELECT `price_id`, `price_cat`, `price_amount` FROM `prices` 
            WHERE `pricegrid_id` = :pricegrid_id ".$req." 
            ORDER BY `price_amount`"
    );
    $params["pricegrid_id"] = $pricegridId;
    $prices->execute($params);

    while ($p = $prices->fetch(PDO::FETCH_ASSOC)) {
        $price = currency($p["price_amount"], true);
        $json[$i]["label"] = $p["price_cat"].' : '.html_entity_decode($price);
        $json[$i]["value"] = $p["price_cat"];
        $json[$i]["price"] = $p["price_amount"];
        $i++;
    }
    $json[$i]["label"] = ' => Créer ou modifier '.$term;
    $json[$i]["value"] = $_GET["term"];
    $json[$i]["create"] = 1;
    $i++;

    $response = new JsonResponse($json);
}

$response->send();

