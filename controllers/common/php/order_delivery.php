<?php

use Biblys\Axys\Client;
use Biblys\Service\Config;
use Biblys\Service\Mailer;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OrderDetailsValidationException extends Exception {};

$config = new Config();
$axys = new Client($config->get("axys"));

function validateOrderDetails($request) {
    if (empty($request->request->get('order_firstname'))) {
        throw new OrderDetailsValidationException(
            'Le champ &laquo;&nbsp;Pr&eacute;nom&nbsp;&raquo; est obligatoire !'
        );
    }

    if (empty($request->request->get('order_lastname'))) {
        throw new OrderDetailsValidationException(
            'Le champ &laquo;&nbsp;Nom&nbsp;&raquo; est obligatoire !'
        );
    }

    if (empty($request->request->get('order_address1'))) {
        throw new OrderDetailsValidationException(
            'Le champ &laquo;&nbsp;Adresse&nbsp;&raquo; est obligatoire !'
        );
    }

    if (empty($request->request->get('order_postalcode'))) {
        throw new OrderDetailsValidationException(
            'Le champ &laquo;&nbsp;Code Postal&nbsp;&raquo; est obligatoire !'
        );
    }

    if (empty($request->request->get('order_city'))) {
        throw new OrderDetailsValidationException(
            'Le champ &laquo;&nbsp;Ville&nbsp;&raquo; est obligatoire !'
        );
    }

    if (empty($request->request->get('order_email'))) {
        throw new OrderDetailsValidationException(
            'Le champ &laquo;&nbsp;Adresse e-mail&nbsp;&raquo; est obligatoire !'
        );
    }

    if (empty($request->request->get('country_id'))) {
        throw new OrderDetailsValidationException(
            'Le champ &laquo;&nbsp;Pays&nbsp;&raquo; est obligatoire !'
        );
    }

    if (empty($request->request->get('cgv_checkbox'))) {
        throw new OrderDetailsValidationException(
            'Vous devez accepter les Conditions Générales de Vente.'
        );
    }

    // Validate e-mail
    $orderEmail = $request->request->get('order_email');
    $validator = new EmailValidator();
    $multipleValidations = new MultipleValidationWithAnd([
        new RFCValidation(),
        new DNSCheckValidation()
    ]);
    $isEmailValid = $validator->isValid($orderEmail, $multipleValidations);
    if (!$isEmailValid) {
        throw new Exception("L'adresse e-mail est pas valide.");
    }
}

    $_PAGE_TITLE = 'Commande &raquo; Validation';

    $order_update = 0;

    $order_gift_mode = null;
    $order_gift_recipient = null;

    // Managers
    $cm = new CustomerManager();
    $com = new CountryManager();
    $om = new OrderManager();
    $sm = new StockManager();
    $um = new UserManager();
    $mailer = new Mailer();
    $shm = new ShippingManager();

    if ($cart = $_V->getCart()) {

        // Gift
        $gift_mode = null;
        if ($cart->get('cart_as-a-gift') == 'party') {

            // Get user
            if ($u = $um->get(array('user_id' => $cart->get('gift-recipient')))) {

                // Get customer
                $cm = new CustomerManager();
                if ($c = $cm->get(array('user_id' => $u->get('id'), 'site_id' => $_SITE['site_id']))) {
                    $shm = new ShippingManager();

                    // Get shipping for shop
                    if ($sh = $shm->get(array('site_id' => $_SITE['site_id'], 'shipping_type' => 'magasin'))) {
                        $gift_mode = '<h2><i class="fa fa-gift"></i> Mode cadeau</h2>'
                                . '<p>Après validation et paiement, les articles de votre panier seront emballés et mis à disposition de '.$u->getUserName().' pour son anniversaire le '._date($c->get('privatization'), 'j f Y').'.</p>'
                                . '<input type="hidden" name="shipping_id" value="'.$sh->get('id').'">';

                        $order_gift_mode = 'party';
                        $order_gift_recipient = $u->get('id');
                        $order_gift_recipient_name = $u->getUserName();
                    }
                }
            }
        }

        $article_count = 0;
        $total_weight = 0;
        $total_price = 0;
        $total = 0;

        // Is there already an order in progress ?
        $orderInProgress = null;
        if ($_V->isLogged()) {
            $orderInProgress = $om->get([
                'order_type' => 'web',
                'user_id' => $_V->get('id'),
                'order_payment_date' => 'NULL',
                'order_shipping_date' => 'NULL',
                'order_cancel_date' => 'NULL'
            ]);
            if ($orderInProgress) {
                $copies = $orderInProgress->getCopies();
                foreach ($copies as $copy) {
                    $article_count++;
                    $total_weight += $copy->get('weight');
                    $total_price += $copy->get('selling_price');
                }
                $order_update = 1;
            }
        }

        // Get cart content
        $stock = $sm->getAll(array('cart_id' => $cart->get('id')));
        foreach ($stock as $s) {
            $article_count++;
            $total_weight += $s->get('weight');
            $total_price += $s->get('selling_price');
        }

        // Shipping & country
        $shipping_fee = 0;
        $shipping_mode = 'Offerts';

        if ($cart->needsShipping()) {
            $country_id = $request->query->get('country_id');
            $country = $com->getById($country_id);
            if (!$country) {
                trigger_error('Pays incorrect');
            }

            $country_input = '
                <input type="text" value="'.$country->get('name').'" readonly>
                <input type="hidden" name="country_id" value="'.$country->get('id').'">
                <a class="btn btn-info btn-xs" href="/pages/cart">modifier</a>
            ';


            $shipping_id = $request->query->get('shipping_id');
            $shipping = $shm->getById($shipping_id);
            if (!$shipping) {
                trigger_error('Frais de port incorrect.');
            }

            $shipping_fee = $shipping->get('fee');
            $shipping_mode = $shipping->get('type');
        }

        // If cart doesn't need shipping
        else {

            // If user country is saved
            $user_country = $_V->get('country');

            $countries = $com->getAll();
            $countries_options = array_map(function ($country) use ($_V) {
                $selected = null;
                if ($country->get('name') === $_V->get('country')) {
                    $selected = " selected";
                }
                return '<option value="'.$country->get('id').'"'.$selected.'>'.$country->get('name').'</option>';
            }, $countries);

            $country_input = '
                <select name="country_id">
                    <option></option>
                    <option value="67">France</option>
                    '.implode($countries_options).'
                </select>';

            $shipping_fee = 0;
        }

        // Add shipping to order total amount
        $total = $total_price + $shipping_fee;

        if (empty($article_count)) {
            $_ECHO .= '<p class="error">Erreur : Votre panier est vide !</p>';
        } else {

            // Confirm order
            if ($request->getMethod() == "POST") {

                $error = null;
                try {
                    validateOrderDetails($request);
                } catch(Exception $exception) {
                    $error = $exception->getMessage();
                }

                if ($error === null) {
                    $_SQL->beginTransaction();


                    /* MAILING */
                    $newsletter_checked = $request->request->get('newsletter', false);
                    $email = $request->request->get('order_email', null);
                    if ($newsletter_checked) {
                        $mm = new MailingManager();
                        try {
                            $result = $mm->addSubscriber($email, true);
                        } catch (\Exception $e) {
                            // Ignore errors
                        }
                    }

                    $mail = array();

                    /* GET ORDER */

                    // If there is a ongoing order, get it
                    if ($orderInProgress) {
                        $order = $orderInProgress;
                        $order_update = 1;
                    }

                    // Else, create a new order
                    else {
                        $order = $om->create();
                    }

                    /* CUSTOMER */

                    // Get customer from User
                    if ($_V->isLogged()) {
                        $order->set('user_id', $_V->get('id'));
                        $customer = $_V->getCustomer('create');
                    }

                    // Else get customer from email address
                    elseif ($getCustomer = $cm->get(array('customer_email' => $_POST['order_email']))) {
                        $customer = $getCustomer;
                    }

                    // else Create a new customer with order info
                    else {
                        $customer = $cm->create(array());
                        $customer->set('customer_email', $_POST['order_email'])
                            ->set('customer_first_name', $_POST['order_firstname'])
                            ->set('customer_last_name', $_POST['order_lastname']);
                        $cm->update($customer);
                    }
                    $order->set('customer_id', $customer->get('id'));

                    /* COUNTRY */

                    $country_id = $request->request->get('country_id');
                    $country = $com->getById($country_id);
                    if (!$country) {
                        trigger_error('Pays inconnu.');
                    }

                    // General order info
                    $order->set('order_insert', date('Y-m-d H:i:s'))
                        ->set('order_type', 'web')
                        ->set('order_amount', $total_price)
                        ->set('order_shipping_mode', $shipping_mode)
                        ->set('order_shipping', $shipping_fee)
                        ->set('order_amount_tobepaid', $total)
                        ->set('country', $country);

                    $comment = $request->request->get('comment', false);
                    if ($comment) {
                        $order->set('order_comment', $comment);
                    }

                    if (isset($shipping)) {
                        $order->set('shipping_id', $shipping->get('id'));
                    }

                    // Save UTM params from cookies
                    $order->setUtmParams($request->cookies);

                    // Update order info from form
                    foreach ($_POST as $key => $val) {
                        if ($key === "cgv_checkbox") {
                            continue;
                        }

                        $order->set($key, $val);
                    }

                    // Is the order a gift ?
                    if (isset($order_gift_mode)) {
                        $order->set('order_as-a-gift', $order_gift_mode);
                        $order->set('order_gift-recipient', $order_gift_recipient);
                    }

                    // Persist order
                    $om->update($order);

                    // Save customer country
                    $customer->set('country_id', $country_id);
                    $cm->update($customer);

                    // Cart stock
                    $campaign = false;
                    $mail['articles'] = array();
                    $stock = $sm->getAll(array('cart_id' => $cart->get('id')));
                    foreach ($stock as $s) {

                        // Prepare article list for mail
                        $location = null;
                        $condition = null;
                        $pub_year = null;
                        if ($s->has('stockage')) {
                            $location = "<br />Emplacement : ".$s->get('stockage');
                        }
                        if ($s->has('condition')) {
                            $condition = $s->get('condition').' | ';
                        }
                        if ($s->has('pub_year')) {
                            $pub_year = ', '.$s->get('pub_year');
                        }

                        $a = $s->get('article');

                        $mail['articles'][] = '
                            <p>
                                <a href="http://'.$_SERVER['HTTP_HOST'].'/'.$a->get('url').'">'.$a->get('title').'</a> ('.$a->get('article_collection').numero($a->get('number')).')<br>
                                de '.authors($a->get('authors')).'<br>
                                '.$a->get('article_publisher').$pub_year.'<br>
                                '.$condition.currency($s->get('selling_price') / 100).'
                                '.$location.'
                            </p>
                        ';
                    }

                    // Hydrate order from cart
                    $om->hydrateFromCart($order, $cart);

                    // Send emails
                    if (!empty($shipping_fee)) {
                        $mail['shipping'] = "Frais de port : ".currency($shipping_fee / 100)." (".$shipping_mode.")<br>";
                    } else {
                        $mail['shipping'] = 'Frais de port offerts<br>';
                    }

                    $mail['gift'] = null;
                    $mail['subject'] = $_SITE["site_tag"].' | Commande n° '.$order->get('id');
                    if ($order_update) {
                        $mail['subject'] .= ' (mise à jour)';
                    }
                    if (isset($order_gift_mode)) {
                        $mail['subject'] .= ' [Cadeau]';
                        $mail['gift'] = '<p>Commande cadeau pour '.$order_gift_recipient_name.'</p>';
                    }

                    if ($shipping_mode == "magasin") {
                        $mail['address_type'] = '<p>Vous avez choisi le retrait en magasin. Vous serez averti par courriel lorsque votre commande sera disponible.</p><p><strong>Adresse de facturation :</strong></p>';
                    } else {
                        $mail['address_type'] = '<p><strong>Adresse d\'expédition :</strong></p>';
                    }

                    $order_ebooks = null;
                    if ($order->containsDownloadable()) {
                        $order_ebooks = '
                            <p>
                                Après paiement de votre commande, vous pourrez télécharger les articles numériques de votre commande depuis
                                <a href="http://'.$_SERVER['HTTP_HOST'].'/pages/log_myebooks">
                                    votre bibliothèque numérique
                                </a>.
                            </p>
                        ';
                    }

                    $mail['content'] = '
                        <html>
                            <head>
                                <meta charset="UTF-8">
                                <title>'.$mail['subject'].'</title>
                                <style>
                                    p {
                                        margin-bottom: 5px;
                                    }
                                </style>
                            </head>
                            <body>
                                <p>Bonjour '.$order->get('firstname').',</p>
                    ';

                    if ($order_update) {
                        $mail['content'] .= '<p>votre commande a été mise à jour.</p>';
                    } else {
                        $mail['content'] .= '<p>votre nouvelle commande a bien été enregistrée.</p>';
                    }

                    $mail['comment'] = '';
                    if ($order->has('comment')) {
                        $mail['comment'] = '<p><strong>Commentaire du client :</strong></p><p>'.nl2br($order->get('comment')).'</p>';
                    }

                    $mail['content'] .= '
                                <p><strong><a href="http://'.$_SERVER['HTTP_HOST'].'/order/'.$order->get('url').'">Commande n&deg; '.$order->get('order_id').'</a></strong></p>

                                '.$mail['gift'].'

                                <p><strong>'.$article_count.' article'.s($article_count).'</strong></p>

                                '.implode($mail['articles']).'

                                <p>
                                    ------------------------------<br />
                                    '.$mail['shipping'].'
                                    Total : '.currency($total / 100).'
                                </p>

                                '.$order_ebooks.'

                                '.$mail['address_type'].'

                                <p>
                                    '.$order->get('title').' '.$order->get('firstname').' '.$order->get('lastname').'<br />
                                    '.$order->get('address1').'<br />
                                    '.($order->has('address2') ? $order->get('address2').'<br>' : null).'
                                    '.$order->get('postalcode').' '.$order->get('city').'<br />
                                    '.$order->get('country')->get('name').'
                                </p>

                                '.$mail['comment'].'

                                <p>
                                    Si ce n\'est pas déjà fait, vous pouvez payer votre commande à l\'adresse ci-dessous :<br />
                                    http://'.$_SERVER['HTTP_HOST'].'/order/'.$order->get('url').'
                                </p>

                                <p>Merci pour votre commande !</p>
                            </body>
                        </html>
                    ';

                    // Send email to customer from site
                    $mailer->send($order->get('email'), $mail['subject'], $mail['content']);

                    // Send email to seller & log from customer
                    $from = [$_SITE['site_contact'] => trim($order->get('firstname').' '.$order->get('lastname'))];
                    $reply_to = $order->get('email');
                    $mailer->send($_SITE['site_contact'], $mail['subject'], $mail['content'], $from, ['reply-to' => $reply_to]);

                    // Delete alerts for purchased articles
                    $order->deleteRelatedAlerts();

                    $_SQL->commit();

                    $orderUrl = $order->get("url");
                    if ($order_update) {
                        $redirectUrl = "/order/$orderUrl?updated=1";
                    } else {
                        $redirectUrl = "/order/$orderUrl?created=1";
                    }

                    return new RedirectResponse($redirectUrl, 301);
                }
            }

            $_ECHO .= '
                <h2>'.$_PAGE_TITLE.'</h2>

                <h3>R&eacute;capitulatif</h3>
            ';

            if (isset($o["order_id"])) {
                $_ECHO .= '<p class="warning">Les livres du panier seront ajout&eacute;s &agrave; votre <a href="/order/'.$o["order_url"].'">commande en cours</a>.</p><br />';
            }

            $_ECHO .= '
                <table class="table" style="width: 300px; margin: auto;">
                    <thead>
                        <tr>
                            <th class="center" colspan="2">Votre commande</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="right">Articles : </td>
                            <td>'.$article_count.'</td>
                        </tr>
            ';
            if ($_SITE["site_shipping_fee"] == 'fr') {
                $_ECHO .= '
                            <tr>
                                <td class="right">Poids : </td>
                                <td>'.$total_weight.'g</td>
                            </tr>
                ';
            }
            if ($cart->needsShipping()) {
                $_ECHO .= '
                        <tr>
                            <td class="right">Sous-total : </td>
                            <td>'.currency($total_price / 100).'</td>
                        </tr>
                        <tr>
                            <td class="right">Frais de port&nbsp;: </td>
                            <td>'.currency($shipping_fee / 100).' ('.$shipping_mode.')</td>
                        </tr>
                ';
            }
            $_ECHO .= '
                        <tr>
                            <td class="right">Montant &agrave; r&eacute;gler : </td>
                            <td>'.currency($total / 100).'</td>
                        </tr>
                    </tbody>
                </table>
            ';

            $shipping_date = $site->getOpt('shipping_date');
            if ($shipping_date) {
                $_ECHO .= '
                    <h3>Date d\'expédition</h3>
                    <p>'.$site->getOpt('shipping_date').'</p>
                ';
            }

            // Gift mode
            if (isset($gift_mode)) {
                $_ECHO .= $gift_mode;
            }

            $form_class = null;
            if (!auth()) {
                $_ECHO .= '
                    <h3>Vos coordonn&eacute;es</h3>
                    <h4>Vous avez un compte Axys ?</h4> <p><a href="'.$axys->getLoginUrl().'" class="btn btn-primary">Connectez-vous</a> pour remplir automatiquement vos coordonn&eacute;es.</p>
                    <h4>Vous n\'avez pas de compte Axys ?</h4> <p><a href="'.$axys->getSignupUrl().'" class="btn btn-primary">Inscrivez-vous</a> pour sauvegarder vos coordonn&eacute;es pour une prochaine commande.</p>
                    <br />
                    <button id="show_orderForm" class="showThis btn btn-warning">Je souhaite commander sans utiliser un compte Axys</button>
                    <br /><br />
                ';
                $form_class = 'hidden';
            }

            if (isset($error)) {
                $_ECHO .= '<p class="error">'.$error.'</p>';
            }

            $save_data_checkbox = null;

            // Newsletter checkbox
            $newsletter_checkbox = null;
            if ($site->getOpt('newsletter') == 1) {
                $checked = null;
                $showCheckbox = true;

                if ($_V->isLogged()) {
                    $mm = new MailingManager();
                    $mailing = $mm->get(["mailing_email" => $_V->get("email")]);
                    if ($mailing) {
                        if ($mailing->isSubscribed()) {
                            $showCheckbox = false;
                        } else {
                            $checked = null;
                        }
                    }
                }

                if ($showCheckbox) {
                    $newsletter_checkbox = '
                        <p class="checkbox">
                            <label class="after">
                                <input type="checkbox" name="newsletter" value="1" '.$checked.' >
                                Je souhaite recevoir la newsletter pour être tenu-e
                                au courant de l\'actualité du site.<br>
                                <small>
                                    En cochant cette case, j\'accepte de recevoir par
                                    courriel la newsletter de '.$site->get('title').'. Je
                                    comprends que je peux me désabonner de ces
                                    communications en cliquant sur le lien de
                                    désabonnement inséré à la fin de ces courriels.
                                </small>
                            </label>
                        </p>
                    ';
                }
            }

            // CGV checkbox
            $cgv_page = $site->getOpt('cgv_page');
            $cgv_checkbox = '<input type="hidden" name="cgv_checkbox" value=1>';
            if ($cgv_page) {
                $pm = new PageManager();
                $page = $pm->getById($cgv_page);
                if ($page) {
                    $cgv_checkbox = '
                        <p class="checkbox">
                            <label class="after">
                                <input type="checkbox" name="cgv_checkbox" value=1 required>
                                En cochant cette case, je reconnais avoir pris
                                connaissance des
                                <a href="/pages/'.$page->get('url').'">
                                    Conditions Générales de Vente.
                                </a>
                            </label>
                        </p>
                    ';
                }
            }

            $card_warning = null;
            if ($total < 100) {
                $card_warning = '
                    <p class="alert alert-warning">
                        <span class="fa fa-warning"></span>&nbsp;
                        Les commandes dont le montant total est inférieur à 1,00 €<br>
                        ne peuvent être réglés par carte bancaire ou Paypal.
                    </p>
                ';
            }

            $order = new Order([]);

            $previousOrder = null;

            if ($_V->isLogged()) {
                $om = new OrderManager();
                $previousOrder = $om->get(
                    [
                        'user_id' => $_V->get('id'),
                        'order_cancel_date' => 'NULL',
                    ],
                    ['order' => 'order_created', 'sort' => 'desc']
                );

                // Prefill order email with user email
                $order->set('order_email', $_V->get('email'));
            }

            if ($previousOrder) {
                $url = '/pages/order_delivery?country_id='.$country_id.'&shipping_id='.$shipping_id.'&reuse=1';
                $_ECHO .= '
                    <div class="previous-order">
                        <p>
                            <span class="fa fa-lightbulb-o"></span>
                            Vous pouvez pré-remplir le formulaire avec les coordonnées
                            utilisées lors de votre précédente commande
                            (n°&nbsp;'.$previousOrder->get('id').').
                        </p>
                        <p>
                            '.$previousOrder->get('firstname').' '.$previousOrder->get('lastname').'<br>
                            '.$previousOrder->get('address1').'<br>
                            '.$previousOrder->get('address2').'<br>
                            '.$previousOrder->get('postalcode').' '.$previousOrder->get('city').'<br>
                        </p>
                        <p class="text-center">
                            <a href="'.$url.'" class="btn btn-info">Réutiliser cette adresse</a>
                        </p>
                    </div>
                ';

                if ($request->query->get('reuse') === '1') {
                    $order->set('title', $previousOrder->get('title'));
                    $order->set('firstname', $previousOrder->get('firstname'));
                    $order->set('lastname', $previousOrder->get('lastname'));
                    $order->set('address1', $previousOrder->get('address1'));
                    $order->set('address2', $previousOrder->get('address2'));
                    $order->set('postalcode', $previousOrder->get('postalcode'));
                    $order->set('city', $previousOrder->get('city'));
                    $order->set('email', $previousOrder->get('email'));
                    $order->set('phone', $previousOrder->get('phone'));
                }
            }

            if ($request->getMethod() === "POST") {
                $order->set('title', $request->request->get('order_title'));
                $order->set('firstname', $request->request->get('order_firstname'));
                $order->set('lastname', $request->request->get('order_lastname'));
                $order->set('address1', $request->request->get('order_address1'));
                $order->set('address2', $request->request->get('order_address2'));
                $order->set('postalcode', $request->request->get('order_postalcode'));
                $order->set('city', $request->request->get('order_city'));
                $order->set('email', $request->request->get('order_email'));
                $order->set('phone', $request->request->get('order_phone'));
                $order->set('comment', $request->request->get('order_comment'));
            }

            $_ECHO .= '
                <form id="orderForm" method="post" class="order-delivery-form fieldset check '.$form_class.'">
                    <fieldset>
                        <legend>Vos coordonnées</legend>
                        <p>
                            <label for="order_firstname">Prénom :</label>
                            <input type="text" name="order_firstname" id="order_firstname" class="form-control" value="'.$order->get('firstname').'" required />
                        </p>
                        <p>
                            <label for="order_lastname">Nom :</label>
                            <input type="text" name="order_lastname" id="order_lastname" class="form-control" value="'.$order->get('lastname').'" style="text-transform : uppercase;"; required />
                        </p>
                        <br>

                        <p>
                            <label for="order_address1">Adresse (ligne 1) :</label>
                            <input type="text" name="order_address1" id="order_address1" class="form-control" value="'.$order->get('address1').'" class="long" required />
                        </p>
                        <p>
                            <label for="order_address2">Adresse (ligne 2) :</label>
                            <input type="text" name="order_address2" id="order_address2" class="form-control" value="'.$order->get('address2').'" class="long" />
                        </p>
                        <p>
                            <label for="order_postalcode">Code postal :</label>
                            <input type="text" name="order_postalcode" id="order_postalcode" class="form-control" value="'.$order->get('postalcode').'" required />
                        </p>
                        <p>
                            <label for="order_city">Ville :</label>
                            <input type="text" name="order_city" id="order_city"  class="form-control" value="'.$order->get('city').'" required />
                        </p>
                        <p>
                            <label for="country_id">Pays :</label>
                            '.$country_input.'
                        </p>
                        <br>

                        <p>
                            <label for="order_email">Adresse e-mail :</label>
                            <input type="email" name="order_email" class="form-control" id="order_email" value="'.$order->get('email').'" required />
                        </p>
                        <p>
                            <label for="order_phone">Téléphone :</label>
                            <input type="text" name="order_phone" class="form-control" id="order_phone" value="'.$order->get('phone').'" />
                        </p>
                        <br>

                        <p>
                            <label for="order_comment">Commentaires :<br><small>à l\'intention du préparateur de la commande</small></label>
                            <textarea name="order_comment" maxlength="1024" class="form-control" id="order_comment" rows=5>'.$order->get('comment').'</textarea>
                        </p>

                        <br>
                        '.$save_data_checkbox.'
                        '.$newsletter_checkbox.'
                        '.$cgv_checkbox.'
                    </fieldset>
                    <fieldset class="text-center">
                        '.$card_warning.'
                        <input class="btn btn-primary" type="submit" value="Enregistrer la commande" />
                    </fieldset>
                </form>

            ';
        }
    } else {
        $_ECHO .= '<p class="error">Votre panier est vide !</p>';
    }
