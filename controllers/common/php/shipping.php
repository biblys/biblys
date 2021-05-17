<?php

use Symfony\Component\HttpFoundation\JsonResponse;

$sm = new ShippingManager();
$cm = new CountryManager();

/** @var $request */
$country_id = $request->query->get('country_id');
$country = $cm->getById($country_id);

if (!$country) {
    trigger_error('Unknown country');
}

$order_weight = $request->query->get('order_weight', 0);
$order_amount = $request->query->get('order_amount', 0);
$fees = $sm->getFees($country, $order_weight, $order_amount);

$result = array_map(
    function ($fee) {
        return [
            'id' => $fee->get('id'),
            'fee' => $fee->get('fee'),
            'mode' => $fee->get('mode'),
            'type' => $fee->get('type'),
            'info' => $fee->get('info'),
        ];
    },
    $fees
);

return new JsonResponse($result);
