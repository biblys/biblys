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


/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection HtmlUnknownTarget */

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

return function (
    Request $request,
    Config $config,
    CurrentSite $currentSite,
    UrlGenerator $urlGenerator,
): Response|RedirectResponse
{
    $om = new OrderManager();

    $request->attributes->set("page_title", "Commande » Paiement");
    $content = '<h2 class="noprint">Commande » Paiement</h2>';

    $orderUrl = LegacyCodeHelper::getRouteParam("url");
    /** @var Order $order */
    $order = $om->get(["order_url" => $orderUrl]);

    if (!$order) {
        throw new NotFoundException("Order $orderUrl not found.");
    }

    $paypalIsAvailable = !!$config->get("paypal");
    $payplugIsAvailable = !!$config->get('payplug');

    $stripeConfig = $config->get('stripe');
    $stripeIsAvailable = $stripeConfig;

    $nameForCheckPayment = $currentSite->getOption("name_for_check_payment", $currentSite->getTitle());
    if ($request->getMethod() === "POST") {

        $payment_mode = $request->request->get("payment");

        // Update order's payment mode
        $order->set("payment_mode", $payment_mode);
        $om->update($order);

        if ($payment_mode == "paypal" && $paypalIsAvailable) {

            $url = $urlGenerator->generate("payment_paypal_pay", ["slug" => $order->get("url")]);
            return new RedirectResponse($url);

        } elseif ($payment_mode == 'payplug' && $payplugIsAvailable) {

            try {
                $payment = $order->createPayplugPayment();
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

            $payment = $order->createStripePayment();
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
                        Effectuez un virement SEPA d\'un montant de 
                        <strong>' . currency($order->getTotal() / 100) . '</strong> 
                        en précisant le code IBAN <strong>
                        ' . $currentSite->getOption('payment_iban') . '.</strong>
                    </li>
                    <li>
                        Précisez <strong>votre numéro de 
                        commande</strong> (' . $order->get('id') . ') dans le motif 
                        du virement.
                    </li>
                </ol>
            ';

        } elseif ($payment_mode == "cheque") {

            if ($order->get('shipping_mode') == "magasin") {
                $content .= '
                    <p>Vous avez choisi de payer votre commande en magasin.</p>
                    <p>Pour payer par chèque, merci d\'établir un chèque à l\'ordre de <strong>' . $nameForCheckPayment . '.</strong></p>
                    <br />
                    <p class="noprint"><a href="/payment/' . $order->get('url') . '"><span class="button">&laquo; Choisir un autre mode de paiement</span></a></p>
                ';
            } else {
                $content .= '
                    <p class="noprint">Pour régler votre commande par chèque&nbsp;:</p>
                    <ol class="noprint">
                        <li>Établissez un chèque d\'un montant de <strong>' . currency($order->getTotal() / 100) . '</strong> à l\'ordre de <strong>' . $nameForCheckPayment . '</strong>.</li>
                        <li>Inscrivez au dos du chèque <strong>votre nom</strong> et <strong>votre numéro de commande</strong> (' . $order->get('id') . ') ou imprimez cette page et joignez-la 
                         votre envoi.</li>
                        <li>Envoyez votre chèque à l\'adresse :</li>
                    </ol>
                    <p class="noprint center">
                        ' . $currentSite->getTitle() . '<br />
                        ' . str_replace('|', '<br />', $currentSite->getSite()->getAddress()) . '
                    </p>
                    <br />
                    <p class="noprint">
                        <a href="/payment/' . $order->get('url') . '" class="btn btn-outline-secondary">» Choisir un autre mode de paiement</a>
                    </p>
    
                    <h2>Bon de commande n° ' . $order->get('id') . '</h2>
    
                    <p>
                        ' . $order->get('firstname') . ' ' . $order->get('lastname') . '<br />
                        ' . $order->get('address1') . ' ' . $order->get('address2') . '<br />
                        ' . $order->get('postalcode') . ' ' . $order->get('city') . '<br />
                        ' . ($order->has('country') ? $order->get('country')->get('name') : null) . '<br />
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
    } else // CHOIX DU MODE DE PAIEMENT
    {
        $payment_options = NULL;

        if (($stripeIsAvailable) && $order->getTotal() < 50) {
            $payment_options = '
                <p class="alert alert-warning">
                    <span class="fa fa-warning"></span>&nbsp;
                    Les commandes dont le montant total est inférieur à 0,50 €<br>
                    ne peuvent être réglés par carte bancaire.
                </p>
            ';
            $stripeIsAvailable = false;
        }

        if (($payplugIsAvailable || $paypalIsAvailable) && $order->getTotal() < 100) {
            $payment_options = '
                <p class="alert alert-warning">
                    <span class="fa fa-warning"></span>&nbsp;
                    Les commandes dont le montant total est inférieur à 1,00 €<br>
                    ne peuvent être réglés par carte bancaire ou Paypal.
                </p>
            ';
            $payplugIsAvailable = false;
            $paypalIsAvailable = false;
        }

        // Withdrawal
        if ($order->get('shipping_mode') == "magasin") {

            // Check
            if ($currentSite->getOption('payment_check')) {
                $payment_options .= '
                    <h4 class="radio">
                        <label for="payment_cheque" class="radio">
                            <input type="radio" name="payment" id="payment_cheque" value="cheque" checked> 
                            Paiement en magasin (chèque ou espèce)
                        </label>
                    </h4>
                    <p>
                        Payez directement en magasin, au moment du retrait de votre commande, par chèque à l\'ordre de 
                        ' . $nameForCheckPayment . ' ou en espèces.
                    </p>
              ';
            }

            if ($currentSite->getOption('payment_iban')) {
                $payment_options .= '
                    <h4 class="radio">
                        <label for="payment_transfer" class="radio">
                            <input type="radio" name="payment" id="payment_transfer" value="transfer"> Virement
                        </label>
                    </h4>
                    <p>
                        Effectuer un virement vers notre compte. Votre commande sera expédiée après apparition du virement sur notre relevé de compte.
                    </p>
                ';
            }

            // Stripe
            if ($stripeIsAvailable) {
                $payment_options .= '
                    <h4 class="radio">
                        <label for="payment_stripe" class="radio">
                            <input type="radio" name="payment" id="payment_stripe" value="stripe"> 
                            Paiement en ligne (carte bancaire)
                        </label>
                    </h4>
                    <p>
                        Paiement par carte bancaire via le serveur sécurisé SSL de notre partenaire Stripe.
                    </p>
                ';
            }

            // PayPlug
            if ($payplugIsAvailable) {
                $payment_options .= '
                    <h4 class="radio">
                        <label for="payment_payplug" class="radio">
                            <input type="radio" name="payment" id="payment_payplug" value="payplug"> 
                            Paiement en ligne (carte bancaire)
                        </label>
                    </h4>
                    <p>
                        Paiement par carte bancaire via le serveur sécurisé SSL de notre partenaire PayPlug.
                    </p>
                    <img src="/common/img/payplug_cards.png" alt="PayPlug" height=50>
                ';
            }

            // Paypal
            if ($paypalIsAvailable) {
                $payment_options .= '
                    <h4 class="radio">
                        <label for="payment_paypal" class="radio">
                            <input type="radio" name="payment" id="payment_paypal" value="paypal"> PayPal
                        </label>
                    </h4>
                    <p>
                        Payez avec votre compte PayPal.<br /> 
                    </p>
                    <img src="/common/img/paypal_cards.png" alt="Moyens de paiement acceptés par PayPal" height="52">
                    <br><br>
                ';
            }
        } else {
            // Stripe
            if ($stripeIsAvailable) {
                $payment_options .= '
                    <h4 class="radio">
                        <label for="payment_stripe" class="radio">
                            <input type="radio" name="payment" id="payment_stripe" value="stripe"> Carte bancaire
                        </label>
                    </h4>
                    <p>
                        Paiement par carte bancaire via le serveur sécurisé de notre partenaire Stripe.<br /> 
                        Pour une expédition rapide, préférez le paiement par carte bancaire.
                    </p>
                    <a href="https://www.stripe.com/" target="_blank" rel="nooreferrer noopener">
                        <img src="/assets/images/powered-by-stripe.png" alt="PayPlug" height=41>
                    </a>
                    <br><br>
                ';
            }

            // Payplug
            if ($payplugIsAvailable) {
                $payment_options .= '
                    <h4 class="radio">
                        <label for="payment_payplug" class="radio">
                            <input type="radio" name="payment" id="payment_payplug" value="payplug"> Carte bancaire
                        </label>
                    </h4>
                    <p>
                        Paiement par carte bancaire via le serveur sécurisé de notre partenaire PayPlug.<br /> 
                        Pour une expédition rapide, préférez le paiement par carte bancaire.
                    </p>
                    <img src="/common/img/payplug_cards.png" alt="PayPlug" height=50>
                    <br><br>
                ';
            }

            // Paypal
            if ($paypalIsAvailable) {
                $payment_options .= '
                    <h4 class="radio">
                        <label for="payment_paypal" class="radio">
                            <input type="radio" name="payment" id="payment_paypal" value="paypal"> PayPal
                        </label>
                    </h4>
                    <p>
                        Payez avec votre compte PayPal.<br /> 
                    </p>
                    <img src="/common/img/paypal_cards.png" alt="Moyens de paiement acceptés par PayPal" height="52">
                    <br><br>
                ';
            }

            if ($currentSite->getOption('payment_iban')) {
                $payment_options .= '
                    <h4 class="radio">
                        <label for="payment_transfer" class="radio">
                            <input type="radio" name="payment" id="payment_transfer" value="transfer"> Virement
                        </label>
                    </h4>
                    <p>
                        Effectuer un virement vers notre compte. Votre commande sera expédiée après apparition du virement sur notre relevé de compte.
                    </p>
                    <br>
                ';
            }

            if ($currentSite->getOption('payment_check')) {
                $payment_options .= '
                    <h4 class="radio">
                        <label for="payment_cheque" class="radio">
                            <input type="radio" name="payment" id="payment_cheque" value="cheque"> Chèque
                        </label>
                    </h4>
                    <p>
                        Expédiez un chèque à l\'ordre de ' . $nameForCheckPayment . '. Votre commande 
                        sera expédiée après encaissement du chèque.
                    </p>
                ';
            }
        }

        $content .= '
            <p class="alert alert-info">Veuillez choisir le mode de paiement pour la commande n° ' . $order->get('id') . '.<br>Montant à régler : ' . currency($order->get('amount_tobepaid') / 100) . '</p>
    
            <form method="post">
                <fieldset>
                    ' . $payment_options . '
                    <div class="center"><br />
                        <button type="submit" class="btn btn-primary">Poursuivre la commande</button>
                    </div>
                </fieldset>
            </form>
        ';
    }

    return new Response($content);
};
