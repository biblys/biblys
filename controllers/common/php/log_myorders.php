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


use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$om = new OrderManager();

$content = "<h2>Mes commandes</h2>";

$request = Request::createFromGlobals();
$config = Config::load();
$currentUserService = CurrentUser::buildFromRequestAndConfig($request, $config);

$orders = $om->getAll([
    'order_type' => 'web',
    'user_id' => $currentUserService->getUser()->getId(),
], [
    'order' => 'order_insert',
    'sort' => 'desc'
]);

$content .= '
    <table class="table">
        <thead>
            <tr class="center">
                <th>Ref.</th>
                <th>Date</th>
                <th>Paiement</th>
                <th>Expédition</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
';

foreach ($orders as $order) {
    $o = $order;

    if ($o["order_payment_date"]) {
        $o["order_payment"] = _date($o["order_payment_date"], 'd/m/Y');
    } else {
        $o["order_payment"] = '<a href="/payment/'.$o["order_url"].'">En attente</a>';
    }
    
    if ($o["order_shipping_date"]) {
        $o["order_shipping_status"] = _date($o["order_shipping_date"], 'd/m/Y');
    } else {
        $o["order_shipping_status"] = "En attente";
    }
    
    if ($o["order_cancel_date"]) {
        $content .= '
            <tr>
                <td class="center"><a href="/order/'.$o["order_url"].'">'.$o["order_id"].'</a></td>
                <td class="center">'._date($o["order_insert"], 'd/m/Y').'</td>
                <td class="center" colspan=3>Annulée le '._date($o["order_cancel_date"], 'd/m/Y').'</td>
            </tr>
        ';
    } else {
        $content .= '
            <tr>
                <td class="center"><a href="/order/'.$o["order_url"].'">'.$o["order_id"].'</a></td>
                <td class="center">'._date($o["order_insert"], 'd/m/Y').'</td>
                <td class="center">'.$o["order_payment"].'</td>
                <td class="center">'.$o["order_shipping_status"].'</td>
                <td class="center">'.price($o["order_amount"]+$o["order_shipping"], 'EUR').'</td>
            </tr>
        ';
    }
}

$content .= '
        </tbody>
    </table>
';

return new Response($content);
