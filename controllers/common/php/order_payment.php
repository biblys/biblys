<?php

    use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

    $om = new OrderManager();
    $sm = new StockManager();

    $_PAGE_TITLE = 'Commande &raquo; Paiement';

    $_ECHO .= '
        <h2 class="noprint">'.$_PAGE_TITLE.'</h2>
    ';

    $numerique = 0;
    $paypal = null;
    $cheque = null;

    $om = new OrderManager();

    $order_url = $request->query->get("url");
    /** @var Order $order */
    $order = $om->get(["order_url" => $order_url]);

    if (!$order) {
        throw new NotFoundException("Order $order_url not found.");
    }

    // Is Paypal available ?
    $paypal = false;
    $paypal_config = $config->get("paypal");
    if ($paypal_config) {
        $paypal = true;
    }

    // Is Payplug available ?
    $payplug = false;
    $payplug_config = $config->get('payplug');
    if ($payplug_config) {
        $payplug = true;
    }

    // Is Stripe available
    $stripe = false;
    $stripeConfig = $config->get('stripe');
    if ($stripeConfig) {
        $stripe = true;
    }

    $o = $order;

    if ($request->getMethod() === "POST") {

        $payment_mode = $request->request->get("payment");

        // Update order's payment mode
        $update = $_SQL->prepare("UPDATE `orders` SET `order_payment_mode` = :mode WHERE `order_id` = :id LIMIT 1");
        $update->execute(["mode" => $payment_mode, "id" => $order->get("id")]);

        if ($payment_mode == "paypal" && $paypal)  {

            $url = $order->createPaypalPaymentLink($paypal_config["client_id"], $paypal_config["client_secret"]);
            redirect($url);

        } elseif ($payment_mode == 'payplug' && $payplug) {

            try {
                $payment = $order->createPayplugPayment();
                redirect($payment->get("url"));
            } catch(Payplug\Exception\BadRequestException $exception) {
                $error = $exception->getErrorObject();
                $_ECHO = '
                    <p class="alert alert-danger">
                        Une erreur est survenue lors de la création du paiement via PayPlug :<br />
                        <strong>Message : '.$error['message'].'</strong>
                    </p>
                    <pre>'.json_encode($error['details'], JSON_PRETTY_PRINT).'</pre>
                ';
            }

        } elseif ($payment_mode == 'stripe' && $stripe) {
            
            $payment = $order->createStripePayment();
            $_ECHO .= '
                <script src="https://js.stripe.com/v3/"></script>
                <script>
                    var stripe = Stripe("'.$stripeConfig["public_key"].'");
                    stripe.redirectToCheckout({
                        sessionId: "'.$payment->get("provider_id").'"
                      }).then(function (result) {
                        alert(result.error.message);
                      });
                </script>

                <p>Redirection vers la page de paiement…</p>
            ';

        } elseif($payment_mode == "transfer") {

            $_ECHO .= '
                <p class="noprint">Pour r&eacute;gler votre commande par virement&nbsp;:</p>
                <ol class="noprint">
                    <li>
                        Effectuez un virement SEPA d\'un montant de 
                        <strong>'.currency($order->getTotal() / 100).'</strong> 
                        en précisant le code IBAN <strong>
                        '.$site->getOpt('payment_iban').'.</strong>
                    </li>
                    <li>
                        Pr&eacute;cisez <strong>votre num&eacute;ro de 
                        commande</strong> ('.$order->get('id').') dans le motif 
                        du virement.
                    </li>
                </ol>
            ';

        } elseif($payment_mode == "cheque") {

            if($order->get('shipping_mode') == "magasin") {
                $_ECHO .= '
                    <p>Vous avez choisi de payer votre commande en magasin.</p>
                    <p>Pour payer par chèque, merci d\'établir un chèque à l\'ordre de <strong>'.$site->getNameForCheckPayment().'.</strong></p>
                    <br />
                    <p class="noprint"><a href="/payment/'.$order->get('url').'"><span class="button">&laquo; Choisir un autre mode de paiement</span></a></p>
                ';
            } else {
                $shipping_fee = null;
                if ($order->has('shipping')) {
                    $shipping_fee = '<tr><td></td><td class="right">Frais de port :</td><td class="right">'.currency($order->get('shipping') / 100).'</td></tr>';
                }

                $_ECHO .= '
                    <p class="noprint">Pour r&eacute;gler votre commande par ch&egrave;que&nbsp;:</p>
                    <ol class="noprint">
                        <li>&Eacute;tablissez un ch&egrave;que d\'un montant de <strong>'.currency($order->getTotal() / 100).'</strong> &agrave; l\'ordre de <strong>'.$site->getNameForCheckPayment().'</strong>.</li>
                        <li>Inscrivez au dos du ch&egrave;que <strong>votre nom</strong> et <strong>votre num&eacute;ro de commande</strong> ('.$order->get('id').') ou imprimez cette page et joignez-l&agrave; &agrave votre envoi.</li>
                        <li>Envoyez votre ch&egrave;que &agrave; l\'adresse :</li>
                    </ol>
                    <p class="noprint center">
                        '.$site->get('title').'<br />
                        '.str_replace('|','<br />',$site->get('address')).'
                    </p>
                    <br />
                    <p class="noprint">
                        <a href="/payment/'.$order->get('url').'" class="btn btn-default">&laquo; Choisir un autre mode de paiement</a>
                    </p>

                    <h2>Bon de commande '.$site->get('tag').' n&deg; '.$order->get('id').'</h2>

                    <p>
                        '.$order->get('firstname').' '.$order->get('lastname').'<br />
                        '.$order->get('address1').' '.$order->get('address2').'<br />
                        '.$order->get('postalcode').' '.$order->get('city').'<br />
                        '.($order->has('country') ? $order->get('country')->get('name') : null).'<br />
                    </p>
                    <br />
                ';
            }
        } else {
            $_ECHO .= '
                <p class="alert alert-danger">Vous devez choisir un moyen de paiement.</p>
                <a class="btn btn-primary" href="javascript:history.back()">retour</a>
            ';
        }
    }
    else // CHOIX DU MODE DE PAIMENT
    {
        $payment_options = NULL;

        // Disable Payplug / Paypal if amount is < 100
        if (($payplug || $paypal) && $order->getTotal() < 100) {
            $payment_options = '
                <p class="alert alert-warning">
                    <span class="fa fa-warning"></span>&nbsp;
                    Les commandes dont le montant total est inférieur à 1,00 €<br>
                    ne peuvent être réglés par carte bancaire ou Paypal.
                </p>
            ';
            $payplug = false;
            $paypal = false;
        }
        
        // Disable Stripe if < 50
        if (($stripe) && $order->getTotal() < 50) {
            $payment_options = '
                <p class="alert alert-warning">
                    <span class="fa fa-warning"></span>&nbsp;
                    Les commandes dont le montant total est inférieur à 0,50 €<br>
                    ne peuvent être réglés par carte bancaire.
                </p>
            ';
            $stripe = false;
        }

        // Withdrawal
        if ($order->get('shipping_mode') == "magasin") {
            
            // Check
            if ($site->getOpt('payment_check')) {
                $payment_options .= '
                    <h4 class="radio"><label for="payment_cheque" class="pointer radio"><input type="radio" name="payment" id="payment_cheque" value="cheque" checked> Paiement en magasin (chèque ou espèce)</label></h4>
                    <p>Payez directement en magasin, au moment du retrait de votre commande, par chèque à l\'ordre de '.$site->getNameForCheckPayment().' ou en espèces.</p>
                ';
            }

            // Payplug
            if ($payplug) {
                $payment_options .= '
                    <h4 class="radio"><label for="payment_payplug" class="radio"><input type="radio" name="payment" id="payment_payplug" value="payplug"> Paiement en ligne (carte bancaire)</label></h4>
                    <p>Paiement par carte bancaire via le serveur sécurisé SSL de notre partenaire PayPlug.</p>
                    <img src="/common/img/payplug_cards.png" border="0" alt="PayPlug" height=50>
                ';
            }

            // Paypal
            if ($paypal) {
                $payment_options .= '
                    <h4 class="radio"><label for="payment_paypal" class="pointer radio"><input type="radio" name="payment" id="payment_paypal" value="paypal"> Paiement en ligne (Paypal)</label></h4>
                    <p>Payez en ligne par carte bancaire via le serveur sécurisé SSL de notre partenaire Paypal.</p>
                    <img src="/common/img/paypal_cards.png" border="0" alt="PayPal Acceptance Mark" height="50">
                ';
            }

            // Stripe
            if ($stripe) {
                $payment_options .= '
                    <h4 class="radio"><label for="payment_stripe" class="radio"><input type="radio" name="payment" id="payment_stripe" value="stripe"> Paiement en ligne (carte bancaire)</label></h4>
                    <p>Paiement par carte bancaire via le serveur sécurisé SSL de notre partenaire Stripe.</p>
                ';
            }
        }

        // Shipping
        else {

            // Payplug
            if ($payplug) {
                $payment_options .= '
                    <h4 class="radio"><label for="payment_payplug" class="radio"><input type="radio" name="payment" id="payment_payplug" value="payplug"> Carte bancaire</label></h4>
                    <p>Paiement par carte bancaire via le serveur s&eacute;curis&eacute; SSL de notre partenaire PayPlug. Pour une exp&eacute;dition rapide, pr&eacute;f&eacute;rez le paiement par carte bancaire.</p>
                    <img src="/common/img/payplug_cards.png" border="0" alt="PayPlug" height=50>
                    <br><br>
                ';
            }

            // Paypal
            if ($paypal) {
                $payment_options .= '
                    <h4 class="radio"><label for="payment_paypal" class="radio"><input type="radio" name="payment" id="payment_paypal" value="paypal"> Paypal</label></h4>
                    <p>Paiement par carte bancaire via le serveur s&eacute;curis&eacute; SSL de notre partenaire Paypal. Pour une exp&eacute;dition rapide, pr&eacute;f&eacute;rez le paiement par carte bancaire.</p>
                    <img src="/common/img/paypal_cards.png" border="0" alt="PayPal Acceptance Mark">
                    <br><br>
                ';

            }

            // Stripe
            if ($stripe) {
                $payment_options .= '
                    <h4 class="radio"><label for="payment_stripe" class="radio"><input type="radio" name="payment" id="payment_stripe" value="stripe"> Carte bancaire</label></h4>
                    <p>Paiement par carte bancaire via le serveur sécurisé de notre partenaire Stripe. Pour une expédition rapide, préférez le paiement par carte bancaire.</p>
                    <a href="https://www.stripe.com/" target="_blank" rel="nooreferrer noopener">
                        <img src="/assets/images/powered-by-stripe.png" border="0" alt="PayPlug" height=41>
                    </a>
                    <br><br>
                ';
            }

            if ($site->getOpt('payment_iban')) {
                $payment_options .= '
                    <h4 class="radio"><label for="payment_transfer" class="radio"><input type="radio" name="payment" id="payment_transfer" value="transfer"> Virement</label></h4>
                    <p>Effectuer un virement vers notre compte. Votre commande sera expédiée après apparition du virement sur notre relevé de compte.</p>
                    <br>
                ';
            }
        
            if ($site->getOpt('payment_check')) {
                $payment_options .= '
                    <h4 class="radio"><label for="payment_cheque" class="radio"><input type="radio" name="payment" id="payment_cheque" value="cheque"> Ch&egrave;que</label></h4>
                    <p>Exp&eacute;diez un ch&egrave;que &agrave; l\'ordre de '.$site->getNameForCheckPayment().'. Votre commande sera exp&eacute;di&eacute;e apr&egrave;s r&eacute;c&eacute;ption du ch&egrave;que.</p>
                ';
            }
        }

        $_ECHO .= '

            <p class="alert alert-info">Veuillez choisir le mode de paiement pour la commande n&deg; '.$order->get('id').'.<br>Montant &agrave; r&eacute;gler : '.currency($order->get('amount_tobepaid') / 100).'</p>

            <form method="post">
                <fieldset>
                    '.$payment_options.'
                    <div class="center"><br />
                        <button type="submit" class="btn btn-primary">Poursuivre la commande</button>
                    </div>
                </fieldset>
            </form>
        ';

    }
