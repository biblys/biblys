<?php /** @noinspection HtmlUnknownAttribute */
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


/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection HtmlUnknownTarget */

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\TemplateService;
use Model\OrderQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function (
    Request $request,
    Config $config,
    CurrentSite $currentSite,
    UrlGenerator $urlGenerator,
    TemplateService $templateService,
): Response|RedirectResponse
{
    $om = new OrderManager();

    $request->attributes->set("page_title", "Commande » Paiement");
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

    $paypalIsAvailable = !!$config->get("paypal");
    $payplugIsAvailable = !!$config->get('payplug');
    $transferIsAvailable = $currentSite->getOption('payment_iban');
    $checkIsAvailable = $currentSite->getOption('payment_check');

    $nameForCheckPayment = $currentSite->getOption("name_for_check_payment", $currentSite->getTitle());

    $orderWillBeCollected = $order->getShippingMode() === "magasin";
    $orderWillBeShipped = !$orderWillBeCollected;

    if ($request->getMethod() === "POST") {

        $payment_mode = $request->request->get("payment");

        // Update order's payment mode
        $orderEntity->set("payment_mode", $payment_mode);
        $om->update($orderEntity);

        if ($payment_mode == 'payplug' && $payplugIsAvailable) {

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

        } elseif ($payment_mode == 'stripe' && $stripeIsAvailable) {

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

        } elseif ($payment_mode == "transfer") {

            $content .= '
                <p class="noprint">Pour régler votre commande par virement&nbsp;:</p>
                <ol class="noprint">
                    <li>
                        Effectuez un virement SEPA d‘un montant de 
                        <strong>' . currency($order->getTotalAmountWithShipping() / 100) . '</strong> 
                        en précisant le code IBAN <strong>
                        ' . $currentSite->getOption('payment_iban') . '.</strong>
                    </li>
                    <li>
                        Précisez <strong>votre numéro de 
                        commande</strong> (' . $order->getId() . ') dans le motif 
                        du virement.
                    </li>
                </ol>
            ';

        } elseif ($payment_mode == "cheque") {

            if ($orderWillBeCollected) {
                $content .= '
                    <p>Vous avez choisi de payer votre commande en magasin.</p>
                    <p>Pour payer par chèque, merci d‘établir un chèque à l‘ordre de <strong>' . $nameForCheckPayment . '.</strong></p>
                    <br />
                    <p class="noprint"><a href="/payment/' . $order->getSlug() . '"><span class="button">&laquo; Choisir un autre mode de paiement</span></a></p>
                ';
            } else {
                $content .= '
                    <p class="noprint">Pour régler votre commande par chèque&nbsp;:</p>
                    <ol class="noprint">
                        <li>Établissez un chèque d‘un montant de <strong>' . currency($order->getTotalAmountWithShipping() / 100) . '</strong> à l‘ordre de <strong>' . $nameForCheckPayment . '</strong>.</li>
                        <li>Inscrivez au dos du chèque <strong>votre nom</strong> et <strong>votre numéro de commande</strong> (' . $order->getId() . ') ou imprimez cette page et joignez-la 
                         votre envoi.</li>
                        <li>Envoyez votre chèque à l‘adresse :</li>
                    </ol>
                    <p class="noprint center">
                        ' . $currentSite->getTitle() . '<br />
                        ' . str_replace('|', '<br />', $currentSite->getSite()->getAddress()) . '
                    </p>
                    <br />
                    <p class="noprint">
                        <a href="/payment/' . $order->getSlug() . '" class="btn btn-outline-secondary">» Choisir un autre mode de paiement</a>
                    </p>
    
                    <h2>Bon de commande n° ' . $order->getId() . '</h2>
    
                    <p>
                        ' . $order->getFirstname() . ' ' . $order->getLastname() . '<br />
                        ' . $order->getAddress1() . ' ' . $order->getAddress2() . '<br />
                        ' . $order->getPostalcode() . ' ' . $order->getCity() . '<br />
                        ' . ($order->getCountry() ? $order->getCountry()->getName() : null) . '<br />
                    </p>
                    <br />
                ';
            }
        } else {
            $content .= '
                <p class="alert alert-danger">Vous devez choisir un moyen de paiement.</p>
                <a class="btn btn-primary" href="javascript:history.back()">retour</a>
            ';
        }
        return new Response($content);

    }
    else // CHOIX DU MODE DE PAIEMENT
    {
        return $templateService->renderResponse('AppBundle:Payment:legacy-choice.html.twig', [
            "order" => $order,
            "stripeIsAvailable" => $stripeIsAvailable,
            "payplugIsAvailable" => $payplugIsAvailable,
            "paypalIsAvailable" => $paypalIsAvailable,
            "paypalClientId" => $config->get("paypal.client_id"),
            "transferIsAvailable" => $transferIsAvailable,
            "checkIsAvailable" => $checkIsAvailable,
            "orderWillBeShipped" => $orderWillBeShipped,
            "orderWillBeCollected" => $orderWillBeCollected,
        ]);
    }
};
