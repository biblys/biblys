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
use Biblys\Service\Config;
use Model\OrderQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

return function (Request $request, Config $config): Response|RedirectResponse {
    $om = new OrderManager();

    $content = '<h1>Paiement</h1>';

    $orderUrl = LegacyCodeHelper::getRouteParam("url");
    $order = OrderQuery::create()->findOneBySlug($orderUrl);

    /** @var Order $orderEntity */
    $orderEntity = $om->get(["order_url" => $orderUrl]);

    if (!$orderEntity) {
        throw new NotFoundException("Order $orderUrl not found.");
    }

    if ($order->isPaid() || $order->isCancelled()) {
        return new RedirectResponse("/order/{$order->getSlug()}");
    }

    $stripeConfig = $config->get('stripe');
    $stripeIsAvailable = $stripeConfig;
    $payplugIsAvailable = !!$config->get('payplug');

    $paymentMode = $request->request->get("payment");

    // Update order's payment mode
    $orderEntity->set("payment_mode", $paymentMode);
    $om->update($orderEntity);

    if ($paymentMode == 'payplug' && $payplugIsAvailable) {

        try {
            $payment = $orderEntity->createPayplugPayment();
            return new RedirectResponse($payment->get("url"));
        } catch (Payplug\Exception\BadRequestException $exception) {
            $error = $exception->getErrorObject();
            $content = '
                    <p class="alert alert-danger">
                        Une erreur est survenue lors de la création du paiement via PayPlug :<br />
                        <strong>Message : ' . $error['message'] . '</strong>
                    </p>
                    <pre>' . json_encode($error['details'], JSON_PRETTY_PRINT) . '</pre>
                ';
        }

    } elseif ($paymentMode == 'stripe' && $stripeIsAvailable) {

        $payment = $orderEntity->createStripePayment();
        /** @noinspection JSUnresolvedReference */
        $content .= '
                <script src="https://js.stripe.com/v3/"></script>
                <script>
                    const stripe = Stripe("' . $stripeConfig["public_key"] . '");
                    stripe.redirectToCheckout({
                        sessionId: "' . $payment->get("provider_id") . '"
                      }).then(function (result) {
                        alert(result.error.message);
                      });
                </script>
    
                <p>Redirection vers la page de paiement…</p>
            ';

    }

    return new Response($content);
};
