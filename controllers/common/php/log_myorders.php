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
use Model\OrderQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

return function(Request $request, Config $config, CurrentUser $currentUser): Response
{
    $currentUser->authUser();

    $orders = OrderQuery::create()
        ->filterByType('web')
        ->filterByUserId($currentUser->getUser()->getId())
        ->orderByInsert('desc')
        ->find();

    $content = '
        <h1><i class="fa fa-box"></i> Mes commandes</h1>
        <table class="table mt-4">
            <thead>
                <tr class="center">
                    <th>Numéro</th>
                    <th>Date</th>
                    <th>Paiement</th>
                    <th>Expédition</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
    ';

    /** @var \Model\Order $order */
    foreach ($orders as $order) {

        $paymentStatus = "En attente";
        if ($order->getPaymentDate()) {
            $paymentStatus = _date($order->getPaymentDate(), 'd/m/Y');
        }

        $shippingStatus = "En attente";
        if ($order->getShippingDate()) {
            $shippingStatus = _date($order->getShippingDate(), 'd/m/Y');
        }

        if ($order->getCancelDate()) {
            $content .= '
                <tr>
                    <td class="center"><a href="/order/' . $order->getSlug() . '">' . $order->getId() . '</a></td>
                    <td class="center">' . _date($order->getCreatedAt(), 'd/m/Y') . '</td>
                    <td class="center" colspan=3>Annulée le ' . _date($order->getCancelDate(), 'd/m/Y') . '</td>
                </tr>
            ';
        } else {
            $content .= '
                <tr>
                    <td class="center"><a href="/order/' . $order->getSlug() . '">' . $order->getId() . '</a></td>
                    <td class="center">' . _date($order->getCreatedAt(), 'd/m/Y') . '</td>
                    <td class="center">' . $paymentStatus . '</td>
                    <td class="center">' . $shippingStatus . '</td>
                    <td class="center">' . currency($order->getTotalAmountWithShipping(), true) . '</td>
                </tr>
            ';
        }
    }

    $content .= '
            </tbody>
        </table>
    ';

    return new Response($content);
};