<?php /** @noinspection PhpUnhandledExceptionInspection */

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Legacy\OrderDeliveryHelpers;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\MailingList\MailingListService;
use Model\PageQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

/** @var Request $request */
/** @var UrlGenerator $urlGenerator */
/** @var CurrentUser $currentUser */
/** @var CurrentSite $currentSite */

$request->attributes->set("page_title", "Commande » Validation");

$salesDisabled = $currentSite->getOption("sales_disabled");
if ($salesDisabled) {
    throw new NotFoundHttpException("La vente en ligne est temporairement désactivée sur ce site.");
}


$cart = LegacyCodeHelper::getGlobalVisitor()->getCart();
if (!$cart) {
    return new Response('<p class="error">Le panier n\'existe pas</p>');
}

// Managers
$cm = new CustomerManager();
$com = new CountryManager();
$om = new OrderManager();
$sm = new StockManager();
$um = new AxysAccountManager();
$shm = new ShippingManager();

$content = "";
$isUpdatingAnExistingOrder = false;
$numberOfCopiesInCart = 0;
$totalWeight = 0;
$totalPrice = 0;
$total = 0;

$currentUrlService = new CurrentUrlService($request);
$currentUrl = $currentUrlService->getRelativeUrl();
$loginUrl = $urlGenerator->generate("user_login", ["return_url" => $currentUrl]);
$signupUrl = $urlGenerator->generate("user_signup");

$orderInProgress = OrderDeliveryHelpers::getOrderInProgressForVisitor(LegacyCodeHelper::getGlobalVisitor());
if ($orderInProgress) {
    $copies = $orderInProgress->getCopies();
    foreach ($copies as $copy) {
        $numberOfCopiesInCart++;
        $totalWeight += $copy->get('weight');
        $totalPrice += $copy->get('selling_price');
    }
    $isUpdatingAnExistingOrder = true;
}

$stock = $cart->getStock();
$numberOfCopiesInCart = count($stock);

if ($numberOfCopiesInCart === 0) {
    return new Response('<p class="error">Votre panier est vide !</p>');
}

foreach ($stock as $s) {
    $totalWeight += $s->get('weight');
    $totalPrice += $s->get('selling_price');
}

/** @var Request $request */
$countryId = $request->query->get('country_id');
$countryInput = OrderDeliveryHelpers::getCountryInput($cart, $countryId, LegacyCodeHelper::getGlobalVisitor()->get("country"));

$shipping = OrderDeliveryHelpers::calculateShippingFees($cart, $request->query->get('shipping_id'));
$shippingMode = $shipping ? $shipping->get("mode") : "";
$shippingFee = $shipping ? $shipping->get("fee") : 0;
$shippingType = $shipping?->get("type");

// Add shipping to order total amount
$total = $totalPrice;
if ($shipping) {
    $total += $shipping->get('fee');
}

$config = Config::load();
$mailingList = null;
$mailingListService = new MailingListService($config);
if ($mailingListService->isConfigured()) {
    $mailingList = $mailingListService->getMailingList();
}

// Confirm order
/** @var Request $request */
if ($request->getMethod() === "POST") {

    $error = null;
    try {
        OrderDeliveryHelpers::validateOrderDetails($request);
    } catch (Exception $exception) {
        $error = $exception->getMessage();
    }

    if ($error === null) {
        /** @var PDO $_SQL */
        $_SQL->beginTransaction();

        /* MAILING */
        $newsletter_checked = $request->request->get('newsletter', false);
        $email = $request->request->get('order_email');
        if ($newsletter_checked && $mailingListService->isConfigured()) {
            $mailingList->addContact($email, true);
        }

        /* GET ORDER */

        // If there is an ongoing order, get it
        if ($isUpdatingAnExistingOrder) {
            $order = $orderInProgress;
        } // Else, create a new order
        else {
            $order = $om->create();
        }

        /* CUSTOMER */

        // Get customer from User
        if ($currentUser->isAuthentified()) {
            $order->set('axys_account_id', LegacyCodeHelper::getGlobalVisitor()->get('id'));
            $customer = LegacyCodeHelper::getGlobalVisitor()->getCustomer('create');
        } // Else get customer from email address
        elseif ($getCustomer = $cm->get(array('customer_email' => $_POST['order_email']))) {
            $customer = $getCustomer;
        } // else Create a new customer with order info
        else {
            $customer = $cm->create();
            $customer->set('customer_email', $_POST['order_email'])
                ->set('customer_first_name', $_POST['order_firstname'])
                ->set('customer_last_name', $_POST['order_lastname']);
            $cm->update($customer);
        }
        $order->set('customer_id', $customer->get('id'));

        /* COUNTRY */
        $countryId = $request->request->get('country_id');
        $country = $com->getById($countryId);
        if (!$country) {
            throw new BadRequestHttpException("Pays inconnu.");
        }

        // General order info
        $order->set('order_insert', date('Y-m-d H:i:s'))
            ->set('order_type', 'web')
            ->set('order_amount', $totalPrice)
            ->set('order_amount_tobepaid', $total)
            ->set('country', $country);

        if ($shipping) {
            $order->set('order_shipping_mode', $shippingType)
                ->set('order_shipping', $shippingFee);
        }

        $comment = $request->request->get('comment', false);
        if ($comment) {
            $order->set('order_comment', $comment);
        }

        if (isset($shipping)) {
            $order->set('shipping_id', $shipping->get('id'));
        }

        // Update order info from form
        foreach ($_POST as $key => $val) {
            if ($key === "cgv_checkbox" || $key === "newsletter") {
                continue;
            }

            $order->set($key, $val);
        }

        // Persist order
        $om->update($order);

        // Save customer country
        $customer->set('country_id', $countryId);
        $cm->update($customer);

        // Hydrate order from cart
        $updatedOrder = $om->hydrateFromCart($order, $cart);

        /** @var Mailer $mailer */
        /** @var CurrentSite $currentSite */
        $termsPageId = $currentSite->getOption("cgv_page");
        $termsPage = PageQuery::create()->findPk($termsPageId);
        OrderDeliveryHelpers::sendOrderConfirmationMail(
            $updatedOrder,
            $shipping,
            $mailer,
            LegacyCodeHelper::getGlobalSite(),
            $isUpdatingAnExistingOrder,
            $termsPage
        );

        // Delete alerts for purchased articles
        /** @var CurrentSite $currentSite */
        if ($currentSite->hasOptionEnabled("alerts")) {
            $order->deleteRelatedAlerts();
        }

        $_SQL->commit();

        $orderUrl = $order->get("url");
        if ($isUpdatingAnExistingOrder) {
            $redirectUrl = "/order/$orderUrl?updated=1";
        } else {
            $redirectUrl = "/order/$orderUrl?created=1";
        }

        return new RedirectResponse($redirectUrl, 301);
    }
}

$content .= '
    <h2>Commande » Validation</h2>

    <h3>Récapitulatif</h3>
';

if (isset($o["order_id"])) {
    $content .= '<p class="warning">Les livres du panier seront ajoutés &agrave; votre <a href="/order/' . $o["order_url"] . '">commande en cours</a>.</p><br />';
}

$content .= '
    <table class="table" style="width: 300px; margin: auto;">
        <thead>
            <tr>
                <th class="center" colspan="2">Votre commande</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="right">Articles : </td>
                <td>' . $numberOfCopiesInCart . '</td>
            </tr>
';
if (LegacyCodeHelper::getGlobalSite()["site_shipping_fee"] == 'fr') {
    $content .= '
                <tr>
                    <td class="right">Poids : </td>
                    <td>' . $totalWeight . 'g</td>
                </tr>
    ';
}
if ($cart->needsShipping()) {
    $content .= '
            <tr>
                <td class="right">Sous-total : </td>
                <td>' . currency($totalPrice / 100) . '</td>
            </tr>
            <tr>
                <td class="right">Frais de port&nbsp;: </td>
                <td>' . currency($shippingFee / 100) . ' (' . $shippingMode . ')</td>
            </tr>
    ';
}
$content .= '
            <tr>
                <td class="right">Montant &agrave; régler : </td>
                <td>' . currency($total / 100) . '</td>
            </tr>
        </tbody>
    </table>
';

/** @var CurrentSite $currentSite */
$shipping_date = $currentSite->getOption('shipping_date');
if ($shipping_date) {
    $content .= '
        <h3>Date d\'expédition</h3>
        <p>' . $currentSite->getOption('shipping_date') . '</p>
    ';
}

$form_class = null;
if (!$currentUser->isAuthentified()) {
    $content .= '
        <h3>Vos coordonnées</h3>
        <h4>Vous avez un compte Axys ?</h4> <p><a href="'.$loginUrl.'" class="btn btn-primary">Connectez-vous</a> pour remplir automatiquement vos coordonnées.</p>
        <h4>Vous n\'avez pas de compte Axys ?</h4> <p><a href="'.$signupUrl.'" class="btn btn-primary">Inscrivez-vous</a> pour sauvegarder vos coordonnées pour une prochaine commande.</p>
        <br />
        <button id="show_orderForm" class="showThis btn btn-warning">Je souhaite commander sans utiliser un compte Axys</button>
        <br /><br />
    ';
    $form_class = 'hidden';
}

if (isset($error)) {
    $content .= '<p class="error">' . $error . '</p>';
}

// Newsletter checkbox
$newsletter_checkbox = null;
if ($currentSite->getOption("newsletter") == 1) {
    $checked = null;
    $showCheckbox = true;

    if (LegacyCodeHelper::getGlobalVisitor()->isLogged()
            && $mailingListService->isConfigured()
            && $mailingList->hasContact(LegacyCodeHelper::getGlobalVisitor()->get("email"))) {
        $showCheckbox = false;
    }

    if ($showCheckbox) {
        $newsletter_checkbox = '
            <p class="checkbox order-delivery-form__checkbox">
                <label class="after">
                    <input type="checkbox" name="newsletter" value="1" ' . $checked . ' >
                    Je souhaite recevoir la newsletter <small>(facultatif)</small><br>
                    <small>
                        En cochant cette case, vous acceptez de recevoir par
                        courriel notre newsletter. Vous comprenez que vous pouvez
                        vous désabonner de ces communications en cliquant sur le
                        lien de désabonnement inséré à la fin de ces courriels.
                    </small>
                </label>
            </p>
        ';
    }
}

// CGV checkbox
$cgv_page = $currentSite->getOption("cgv_page");
$cgv_checkbox = '<input type="hidden" name="cgv_checkbox" value=1>';
if ($cgv_page) {
    $pm = new PageManager();
    $termsPage = $pm->getById($cgv_page);
    if ($termsPage) {
        $cgv_checkbox = '
            <p class="checkbox order-delivery-form__checkbox">
                <label class="after">
                    <input type="checkbox" name="cgv_checkbox" value=1 required>
                    J\'accepte les 
                    <a href="/pages/'.$termsPage->get('url').'">Conditions Générales de Vente</a>
                    <small class="required-field-indicator">(obligatoire)</small><br />
                    <small>
                        En cochant cette case, vous reconnaissez avoir pris connaissance de nos
                        <a href="/pages/'.$termsPage->get('url').'">
                            Conditions Générales de Vente
                        </a>
                        et vous déclarez les accepter sans réserve.
                    </small>
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

if (LegacyCodeHelper::getGlobalVisitor()->isLogged()) {
    $om = new OrderManager();
    $previousOrder = $om->get(
        [
            'axys_account_id' => LegacyCodeHelper::getGlobalVisitor()->get('id'),
            'order_cancel_date' => 'NULL',
        ],
        ['order' => 'order_created', 'sort' => 'desc']
    );

    // Prefill order email with user email
    $order->set('order_email', LegacyCodeHelper::getGlobalVisitor()->get('email'));
}

if ($previousOrder) {
    $url = '/pages/order_delivery?reuse=1';
    if ($shipping) {
        $url .= "&country_id=$countryId&shipping_id=".$shipping->get("id");
    }
    $content .= '
        <div class="previous-order">
            <p>
                <span class="fa fa-lightbulb-o"></span>
                Vous pouvez préremplir le formulaire avec les coordonnées
                utilisées lors de votre précédente commande
                (n°&nbsp;' . $previousOrder->get('id') . ').
            </p>
            <p>
                ' . $previousOrder->get('firstname') . ' ' . $previousOrder->get('lastname') . '<br>
                ' . $previousOrder->get('address1') . '<br>
                ' . $previousOrder->get('address2') . '<br>
                ' . $previousOrder->get('postalcode') . ' ' . $previousOrder->get('city') . '<br>
            </p>
            <p class="text-center">
                <a href="' . $url . '" class="btn btn-info">Réutiliser cette adresse</a>
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

$content .= '
    <form id="orderForm" method="post" class="order-delivery-form fieldset check ' . $form_class . '">
        <fieldset class="order-delivery-form__fieldset">
            <legend>Vos coordonnées</legend>
            <div class="required-fields-notice">
                Les champs suivis d\'une étoile (<span class="required-field-indicator">*</span>) sont obligatoires.    
            </div>
            
            <div class="order-delivery-form__field order-delivery-form__field--country">
                <label for="country_id" class="order-delivery-form__label">
                    Pays
                    <span class="required-field-indicator">*</span>
                </label>
                ' . $countryInput . '
            </div>
            
            <div class="order-delivery-form__field order-delivery-form__field--half">
                <label for="order_firstname" class="order-delivery-form__label">
                    Prénom
                    <span class="required-field-indicator">*</span>
                </label>
                <input type="text" name="order_firstname" id="order_firstname" value="' . $order->get('firstname') . '" class="order-delivery-form__input" required />
            </div>

            <div class="order-delivery-form__field order-delivery-form__field--half">
                <label for="order_lastname" class="order-delivery-form__label">
                    Nom de famille
                    <span class="required-field-indicator">*</span>
                </label>
                <input type="text" name="order_lastname" id="order_lastname" value="' . $order->get('lastname') . '" class="order-delivery-form__input" style="text-transform : uppercase;" required />
            </div>

            <div class="order-delivery-form__field">
                <label for="order_address1" class="order-delivery-form__label">
                    Numéro et nom de rue
                    <span class="required-field-indicator">*</span>
                </label>
                <input type="text" name="order_address1" id="order_address1" value="' . $order->get('address1') . '" class="order-delivery-form__input" required />
            </div>

            <div class="order-delivery-form__field">
                <label for="order_address2" class="order-delivery-form__label">
                    Complément d\'adresse
                    <small>(bât., BP, lieu-dit, etc.)</small>
                </label>
                <input type="text" name="order_address2" id="order_address2" value="' . $order->get('address2') . '" class="order-delivery-form__input" />
            </div>

            <div class="order-delivery-form__field order-delivery-form__field--half">
                <label for="order_postalcode" class="order-delivery-form__label">
                    Code postal
                    <span class="required-field-indicator">*</span>
                </label>
                <input type="text" name="order_postalcode" id="order_postalcode" value="' . $order->get('postalcode') . '" class="order-delivery-form__input" required />
            </div>

            <div class="order-delivery-form__field order-delivery-form__field--half">
                <label for="order_city" class="order-delivery-form__label">
                    Ville
                     <span class="required-field-indicator">*</span>
                </label>
                <input type="text" name="order_city" id="order_city" value="' . $order->get('city') . '" class="order-delivery-form__input" required />
            </div>

            <div class="order-delivery-form__field">
                <label for="order_email" class="order-delivery-form__label">
                    Adresse e-mail
                     <span class="required-field-indicator">*</span>
                </label>
                <input type="email" name="order_email" id="order_email" value="' . $order->get('email') . '" class="order-delivery-form__input" required />
            </div>

            <div class="order-delivery-form__field">
                <label for="order_phone" class="order-delivery-form__label">Téléphone</label>
                <input type="text" name="order_phone" id="order_phone" value="' . $order->get('phone') . '" class="order-delivery-form__input" />
            </div>
         </fieldset>
         
         <fieldset class="order-delivery-form__fieldset">
            <legend>Commentaires</legend>
            <p><small>À l\'intention du préparateur de la commande</small></p>
            <div class="order-delivery-form__field">
                <textarea name="order_comment" maxlength="1024" class="order-delivery-form__textarea" id="order_comment" rows=5>' . $order->get('comment') . '</textarea>
            </div>
         </fieldset>
         
         <fieldset class="order-delivery-form__fieldset">
            ' . $newsletter_checkbox . '
            ' . $cgv_checkbox . '
        </fieldset>

        <fieldset class="order-delivery-form__fieldset order-delivery-form__buttons">
            ' . $card_warning . '
            <a href="/pages/cart" class="btn btn-light">Revenir au panier</a>
            <button class="btn btn-primary" type="submit">Enregistrer la commande</button>
        </fieldset>
    </form>

';

return new Response($content);
