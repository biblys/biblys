<?php

use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Confirmation');

$om = new OrderManager();
$orderId = $request->query->get('id');
$order = $om->getById($orderId);

if (!$order) {
    throw new NotFoundException('No order for id '.htmlentities($orderId));
}

redirect('/order/'.$order->get('url'));
