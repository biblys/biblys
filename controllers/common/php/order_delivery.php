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


use Biblys\Legacy\CartHelpers;
use Biblys\Legacy\OrderDeliveryHelpers;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\MailingList\MailingListService;
use Biblys\Service\QueryParamsService;
use Biblys\Service\StringService;
use DansMaCulotte\MondialRelay\DeliveryChoice;
use Model\AlertQuery;
use Model\CountryQuery;
use Model\CustomerQuery;
use Model\Map\StockTableMap;
use Model\PageQuery;
use Model\StockQuery;
use Propel\Runtime\Propel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function (
    Request            $request,
    CurrentSite        $currentSite,
    UrlGenerator       $urlGenerator,
    CurrentUser        $currentUser,
    Mailer             $mailer,
    QueryParamsService $queryParamsService,
    Config             $config,
): Response|RedirectResponse {
    $orderManager = new OrderManager();
    $stockItemManager = new StockManager();

    $queryParamsService->parse([
        "country_id" => ["type" => "numeric", "default" => 0],
        "shipping_id" => ["type" => "numeric", "default" => 0],
        "pickup_point_code" => ["type" => "string", "default" => ""],
        "reuse" => ["type" => "numeric", "default" => 0],
    ]);

    $request->attributes->set("page_title", "Votre commande");

    $salesDisabled = $currentSite->getOption("sales_disabled");
    if ($salesDisabled) {
        throw new NotFoundHttpException("La vente en ligne est temporairement désactivée sur ce site.");
    }

    $cart = $currentUser->getCart();
    if (!$cart) {
        return new Response('<p class="error">Le panier n\'existe pas</p>');
    }

    $content = "";
    $isUpdatingAnExistingOrder = false;
    $totalWeight = 0;
    $totalPrice = 0;

    $currentUrlService = new CurrentUrlService($request);
    $currentUrl = $currentUrlService->getRelativeUrl();
    $loginUrl = $urlGenerator->generate("user_login", ["return_url" => $currentUrl]);

    $orderInProgress = OrderDeliveryHelpers::getOrderInProgressForVisitor($currentUser, $currentSite);
    if ($orderInProgress) {
        $stockItems = $orderInProgress->getStockItems();
        foreach ($stockItems as $stockItem) {
            $totalWeight += $stockItem->getWeight();
            $totalPrice += $stockItem->getSellingPrice();
        }
        $isUpdatingAnExistingOrder = true;
    }

    $stock = $stockItemManager->getAll([
        'cart_id' => $cart->getId(),
    ]);
    $numberOfCopiesInCart = count($stock);

    if ($numberOfCopiesInCart === 0) {
        return new Response('<p class="error">Votre panier est vide !</p>');
    }

    foreach ($stock as $s) {
        $totalWeight += $s->get('weight');
        $totalPrice += $s->get('selling_price');
    }

    $countryId = $queryParamsService->getInteger("country_id");
    $countryInput = OrderDeliveryHelpers::getCountryInput($cart, $countryId);

    $shippingId = $queryParamsService->getInteger("shipping_id");
    try {
        $shipping = OrderDeliveryHelpers::calculateShippingFees($cart, $shippingId);
    } catch (BadRequestHttpException) {
        return new RedirectResponse("/pages/cart");
    }


    $shippingMode = $shipping ? $shipping->get("mode") : "";
    $shippingFee = $shipping ? $shipping->get("fee") : 0;
    $shippingType = $shipping?->get("type");

    $pickupPointCode = null;
    $pickupPointForm = "";
    if ($shippingType === "mondial-relay") {
        $pickupPointSelectUrl = $urlGenerator->generate("shipping_select_pickup_point", [
            "country_id" => $countryId,
            "shipping_id" => $shippingId,
        ]);

        $pickupPointCode = $queryParamsService->get("pickup_point_code");
        if (!$pickupPointCode) {
            return new RedirectResponse($pickupPointSelectUrl, 301);
        }

        $delivery = new DeliveryChoice([
            "site_id" => $config->get("mondial_relay.code_enseigne"),
            "site_key" => $config->get("mondial_relay.private_key"),
        ]);
        $country = CountryQuery::create()->findPk($countryId);
        $pickupPoint = $delivery->findPickupPointByCode($country->getCode(), $pickupPointCode);

        $pickupPointForm = '
           <fieldset class="order-delivery-form__fieldset order-delivery-form__pickup-points">
              <legend>Point de retrait</legend>
              ' . $pickupPoint->name . ' (' . $pickupPoint->postalCode . ')
              <a href="' . $pickupPointSelectUrl . '" class="btn btn-info">Modifier</a>
              <input type="hidden" name="pickup_point_code" value="' . $pickupPointCode . '">
            </fieldset>
        ';
    }

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

    if ($request->getMethod() === "POST") {

        $error = null;
        try {
            OrderDeliveryHelpers::validateCartContent($currentSite, $cart);
            OrderDeliveryHelpers::validateOrderDetails($request, $currentSite, $cart);
        } catch (Exception $exception) {
            $error = $exception->getMessage();
        }

        if ($error === null) {
            $db = Propel::getServiceContainer()->getWriteConnection(StockTableMap::DATABASE_NAME);
            $db->beginTransaction();

            /* MAILING */
            $newsletter_checked = $request->request->get('newsletter', false);
            $orderEmail = $request->request->get('order_email');
            if ($newsletter_checked && $mailingListService->isConfigured()) {
                $mailingList->addContact($orderEmail, true);
            }

            try {

                /* GET ORDER */

                // If there is an ongoing order, get it
                if ($isUpdatingAnExistingOrder) {
                    $order = $orderInProgress;
                } // Else, create a new order
                else {
                    $order = new \Model\Order();
                    $order->setSite($currentSite->getSite());
                    $orderSlug = StringService::random();
                    $order->setSlug($orderSlug);
                }

                /* CUSTOMER */

                $customer = CustomerQuery::create()->findOneByEmail($orderEmail);
                if ($currentUser->isAuthenticated()) {
                    $order->setUser($currentUser->getUser());
                    $customer = CustomerQuery::create()->findOneByUserId($currentUser->getUser()->getId());
                }

                if (!$customer) {
                    $customer = new \Model\Customer();
                }

                if ($currentUser->isAuthenticated()) {
                    $customer->setUser($currentUser->getUser());
                }

                $customer->setEmail($orderEmail);
                $customer->setFirstName($request->request->get("order_firstname"));
                $customer->setLastName($request->request->get("order_lastname"));
                $customer->save($db);

                $order->setCustomerId($customer->getId());

                /* COUNTRY */

                $countryId = $request->request->get("country_id");
                $country = CountryQuery::create()->findPk($countryId);
                if (!$country) {
                    throw new BadRequestHttpException("Pays inconnu.");
                }

                // General order info

                $order->setInsert(date('Y-m-d H:i:s'));
                $order->setType('web');
                $order->setAmount($totalPrice);
                $order->setAmountTobepaid($total);
                $order->setCountry($country);

                if ($shipping) {
                    $order->setShippingId($shipping->get("id"));
                    $order->setShippingMode($shippingType);
                    $order->setShippingCost($shippingFee);
                }

                $pickupPointCode = $request->request->get("pickup_point_code", "");
                if ($pickupPointCode) {
                    $order->setMondialRelayPickupPointCode($pickupPointCode);
                }

                $comment = $request->request->get('comment', false);
                if ($comment) {
                    $order->setComment($comment);
                }

                if (isset($shipping)) {
                    $order->setShippingId($shipping->get('id'));
                }

                $order->setCountryId($request->request->get("country_id"));
                $order->setFirstname($request->request->get("order_firstname"));
                $order->setLastname($request->request->get("order_lastname"));
                $order->setAddress1($request->request->get("order_address1"));
                $order->setAddress2($request->request->get("order_address2"));
                $order->setPostalcode($request->request->get("order_postalcode"));
                $order->setCity($request->request->get("order_city"));
                $order->setEmail($request->request->get("order_email"));
                $order->setPhone($request->request->get("order_phone"));
                $order->setComment($request->request->get("order_comment"));

                // Persist order
                $order->save($db);

                // Save customer country
                $customer->setCountryId($countryId);
                $customer->save($db);

                // Get cart content
                $cartStockItems = StockQuery::create()
                    ->filterByCartId($cart->getId())->find();

                // Add each copy to the order
                /** @var \Model\Stock $stockItem */
                $orderCampaignId = null;
                foreach ($cartStockItems as $stockItem) {
                    /** @var Stock $stockItemEntity */
                    $stockItemEntity = $stockItemManager->getById($stockItem->getId());
                    if (!$stockItemEntity->isAvailable()) {
                        throw new Exception("Exemplaire {$stockItem->getId()} indisponible.");
                    }

                    $stockItem->setOrder($order);
                    $stockItem->setSellingDate(new DateTime());
                    $stockItem->setCartId(null);
                    $stockItem->setCartDate(null);

                    // Tax
                    $stockItemEntity = $stockItemManager->getById($stockItem->getId());
                    $rate = $stockItemManager->getTaxRate($stockItemEntity);
                    $price = $stockItem->getSellingPrice();

                    $coefficient = 1 + ($rate / 100);
                    $priceWithoutTax = round($price / $coefficient);
                    $tax = $price - $priceWithoutTax;

                    $stockItem->setTvaRate($rate);
                    $stockItem->setSellingPriceHt($priceWithoutTax);
                    $stockItem->setSellingPriceTva($tax);

                    if ($stockItem->getCampaignId()) {
                        $orderCampaignId = $stockItem->getCampaignId();
                    }

                    // Customer
                    $stockItem->setCustomerId($customer->getId());

                    $stockItem->save($db);
                }

                // Reset cart
                $cart->setAmount(0);
                $cart->setCount(0);
                $cart->save($db);

                // Update order from copies
                $amount = array_reduce(
                    $order->getStockItems()->getArrayCopy(),
                    fn($carry, $stockItem) => $carry + $stockItem->getSellingPrice(),
                    initial: 0);
                $order->setAmount($amount);

                $termsPageId = $currentSite->getOption("cgv_page");
                $termsPage = PageQuery::create()->findPk($termsPageId);

                $order->save($db);

                // Delete alerts for purchased articles
                if ($currentSite->hasOptionEnabled("alerts") && $currentUser->isAuthenticated()) {
                    foreach ($order->getStockItems() as $stockItem) {
                        $alert = AlertQuery::create()
                            ->filterByUser($currentUser->getUser())
                            ->filterByArticleId($stockItem->getArticleId());

                        // If it exists, delete it
                        $alert?->delete();
                    }
                }

                /** @var Order $orderEntity */
                OrderDeliveryHelpers::sendOrderConfirmationMail(
                    $order,
                    $shipping,
                    $mailer,
                    $currentSite,
                    $isUpdatingAnExistingOrder,
                    $termsPage
                );
            } catch (Exception $exception) {
                $db->rollBack();
                throw $exception;
            }

            $db->commit();

            $orderSlug = $order->getSlug();
            if ($isUpdatingAnExistingOrder) {
                $redirectUrl = "/order/$orderSlug?updated=1";
            } else {
                $redirectUrl = "/order/$orderSlug?created=1";
            }

            if ($orderCampaignId) {
                $crowdfundingCampaignManager = new CFCampaignManager();
                $crowdfundingRewardManager = new CFRewardManager();

                $campaign = $crowdfundingCampaignManager->getById($orderCampaignId);
                $crowdfundingCampaignManager->updateFromSales($campaign);
                $rewards = $crowdfundingRewardManager->getAll([
                    "campaign_id" => $campaign->get("id"),
                    "reward_limited" => 1
                ]);
                foreach ($rewards as $reward) {
                    $crowdfundingRewardManager->updateQuantity($reward);
                }
            }

            return new RedirectResponse($redirectUrl, 301);
        }
    }

    $content .= '
    <h1>Votre commande</h1>

    <h2>Récapitulatif</h2>
';

    if (isset($o["order_id"])) {
        $content .= '<p class="warning">Les livres du panier seront ajoutés &agrave; votre <a href="/order/' . $o["order_url"] . '">commande en cours</a>.</p><br />';
    }

    $content .= '
        <table class="table" style="max-width: 468px; margin: auto;">
            <tbody>
    ';

    if ($cart->containsPhysicalArticles()) {
        $content .= '
            <tr>
                <td class="right">Articles à expédier : </td>
                <td>' . $cart->getPhysicalArticleCount() . '</td>
            </tr>
        ';
    }

    if ($cart->containsDownloadableArticles()) {
        $content .= '
            <tr>
                <td class="right">Articles à télécharger : </td>
                <td>' . $cart->getDownloadableArticleCount() . '</td>
            </tr>
        ';
    }

    if ($currentSite->getSite()->getShippingFee() === "fr") {
        $content .= '
                <tr>
                    <td class="right">Poids : </td>
                    <td>' . $totalWeight . 'g</td>
                </tr>
    ';
    }
    if (CartHelpers::cartNeedsShipping($cart)) {
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

    $shipping_date = $currentSite->getOption('shipping_date');
    if ($shipping_date) {
        $content .= '
        <h2>Date d\'expédition</h2>
        <p>' . $currentSite->getOption('shipping_date') . '</p>
    ';
    }

    $form_class = null;
    if (!$currentUser->isAuthenticated()) {
        $content .= "
        <h2>Vos coordonnées</h2>
        <p><a href=\"$loginUrl\" class=\"btn btn-primary\" rel='nofollow'>Connectez-vous</a> ou <a href=\"$loginUrl\" class=\"btn btn-success\" rel='nofollow'>inscrivez-vous</a> pour enregistrer vos coordonnées et commander plus rapidement.</p>
        <br />
        <button id=\"show_orderForm\" class=\"showThis btn btn-warning\">Je souhaite commander sans utiliser de compte</button>
        <br /><br />
    ";
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

        if ($currentUser->isAuthenticated()
            && $mailingListService->isConfigured()
            && $mailingList->hasContact($currentUser->getUser()->getEmail())) {
            $showCheckbox = false;
        }

        if ($showCheckbox) {
            $newsletter_checkbox = '
                <div class="form-check">
                  <input name="newsletter" class="form-check-input" type="checkbox" value="1" id="newsletter"  ' . $checked . '>
                  <label class="form-check-label" for="newsletter">
                    Je souhaite recevoir la newsletter <small>(facultatif)</small><br>
                        <small>
                            En cochant cette case, vous acceptez de recevoir par
                            courriel notre newsletter. Vous comprenez que vous pouvez
                            vous désabonner de ces communications en cliquant sur le
                            lien de désabonnement inséré à la fin de ces courriels.
                        </small>
                  </label>
                </div>
            ';
        }
    }

    // CGV checkbox
    $cgv_page = $currentSite->getOption("cgv_page");
    $cgv_checkbox = '<input type="hidden" name="cgv_checkbox" value=1>';
    if ($cgv_page) {
        $pageManager = new PageManager();
        $termsPage = $pageManager->getById($cgv_page);
        if ($termsPage) {
            $cgv_checkbox = '
                <div class="form-check">
                  <input name="cgv_checkbox" class="form-check-input" type="checkbox" value="1" id="cgv_checkbox" required>
                  <label class="form-check-label" for="cgv_checkbox">
                     J\'accepte les 
                    <a href="/pages/' . $termsPage->get('url') . '">Conditions Générales de Vente</a>
                    <small class="required-field-indicator">(obligatoire)</small><br />
                    <small>
                        En cochant cette case, vous reconnaissez avoir pris connaissance de nos
                        <a href="/pages/' . $termsPage->get('url') . '">
                            Conditions Générales de Vente
                        </a>
                        et vous déclarez les accepter sans réserve.
                    </small>
                  </label>
                </div>
          ';
        }
    }

    $downloadableArticlesCheckbox = "";
    if ($cart->containsDownloadableArticles()) {
        $downloadableArticlesCheckbox = '
            <div class="form-check">
                  <input  name="downloadable_articles_checkbox" class="form-check-input" type="checkbox" value="1" id="downloadable_articles_checkbox" required>
                  <label class="form-check-label" for="downloadable_articles_checkbox">
                    J’accepte les conditions spécifiques au numérique
                    <small class="required-field-indicator">(obligatoire)</small><br />
                    <small>
                        En cochant cette case, vous déclarez comprendre que votre commande contient
                        des articles numériques téléchargeables et que vous renoncez à votre droit
                        de rétraction sur ces articles dès le premier téléchargement.
                    </small>
                  </label>
                </div>
        ';
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

    $orderEntity = new Order([]);

    $previousOrder = null;
    if ($currentUser->isAuthenticated()) {
        $previousOrder = $orderManager->get(
            [
                'user_id' => $currentUser->getUser()->getId(),
                'order_cancel_date' => 'NULL',
            ],
            ['order' => 'order_created', 'sort' => 'desc']
        );

        // Prefill order email with user email
        $orderEntity->set('order_email', $currentUser->getUser()->getEmail());
    }

    if ($previousOrder) {
        $url = '/pages/order_delivery?reuse=1';
        if ($shipping) {
            $url .= "&country_id=$countryId&shipping_id=" . $shipping->get("id");
        }
        if ($pickupPointCode) {
            $url .= "&pickup_point_code=$pickupPointCode";
        }
        $content .= '
        <div class="previous-order">
            <p>
                <i class="fa-regular fa-lightbulb"></i>
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

        if ($queryParamsService->getInteger("reuse") === 1) {
            $orderEntity->set('title', $previousOrder->get('title'));
            $orderEntity->set('firstname', $previousOrder->get('firstname'));
            $orderEntity->set('lastname', $previousOrder->get('lastname'));
            $orderEntity->set('address1', $previousOrder->get('address1'));
            $orderEntity->set('address2', $previousOrder->get('address2'));
            $orderEntity->set('postalcode', $previousOrder->get('postalcode'));
            $orderEntity->set('city', $previousOrder->get('city'));
            $orderEntity->set('email', $previousOrder->get('email'));
            $orderEntity->set('phone', $previousOrder->get('phone'));
        }
    }

    if ($request->getMethod() === "POST") {
        $orderEntity->set('title', $request->request->get('order_title'));
        $orderEntity->set('firstname', $request->request->get('order_firstname'));
        $orderEntity->set('lastname', $request->request->get('order_lastname'));
        $orderEntity->set('address1', $request->request->get('order_address1'));
        $orderEntity->set('address2', $request->request->get('order_address2'));
        $orderEntity->set('postalcode', $request->request->get('order_postalcode'));
        $orderEntity->set('city', $request->request->get('order_city'));
        $orderEntity->set('email', $request->request->get('order_email'));
        $orderEntity->set('phone', $request->request->get('order_phone'));
        $orderEntity->set('comment', $request->request->get('order_comment'));
    }

    $isPhoneRequired = $currentSite->getOption("order_phone_required");

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
                <input type="text" name="order_firstname" id="order_firstname" value="' . $orderEntity->get('firstname') . '" class="order-delivery-form__input" required />
            </div>

            <div class="order-delivery-form__field order-delivery-form__field--half">
                <label for="order_lastname" class="order-delivery-form__label">
                    Nom de famille
                    <span class="required-field-indicator">*</span>
                </label>
                <input type="text" name="order_lastname" id="order_lastname" value="' . $orderEntity->get('lastname') . '" class="order-delivery-form__input" style="text-transform : uppercase;" required />
            </div>

            <div class="order-delivery-form__field">
                <label for="order_address1" class="order-delivery-form__label">
                    Numéro et nom de rue
                    <span class="required-field-indicator">*</span>
                </label>
                <input type="text" name="order_address1" id="order_address1" value="' . $orderEntity->get('address1') . '" class="order-delivery-form__input" required />
            </div>

            <div class="order-delivery-form__field">
                <label for="order_address2" class="order-delivery-form__label">
                    Complément d\'adresse
                    <small>(bât., BP, lieu-dit, etc.)</small>
                </label>
                <input type="text" name="order_address2" id="order_address2" value="' . $orderEntity->get('address2') . '" class="order-delivery-form__input" />
            </div>

            <div class="order-delivery-form__field order-delivery-form__field--half">
                <label for="order_postalcode" class="order-delivery-form__label">
                    Code postal
                    <span class="required-field-indicator">*</span>
                </label>
                <input type="text" name="order_postalcode" id="order_postalcode" value="' . $orderEntity->get('postalcode') . '" class="order-delivery-form__input" required />
            </div>

            <div class="order-delivery-form__field order-delivery-form__field--half">
                <label for="order_city" class="order-delivery-form__label">
                    Ville
                     <span class="required-field-indicator">*</span>
                </label>
                <input type="text" name="order_city" id="order_city" value="' . $orderEntity->get('city') . '" class="order-delivery-form__input" required />
            </div>

            <div class="order-delivery-form__field">
                <label for="order_email" class="order-delivery-form__label">
                    Adresse e-mail
                     <span class="required-field-indicator">*</span>
                </label>
                <input type="email" name="order_email" id="order_email" value="' . $orderEntity->get('email') . '" class="order-delivery-form__input" required />
            </div>

            <div class="order-delivery-form__field">
                <label for="order_phone" class="order-delivery-form__label">
                    Téléphone
                    ' . ($isPhoneRequired ? '<span class="required-field-indicator">*</span>' : '') . '
                </label>
                <input 
                    type="text" 
                    name="order_phone" 
                    id="order_phone" 
                    value="' . $orderEntity->get('phone') . '" 
                    class="order-delivery-form__input"
                    ' . ($isPhoneRequired ? 'required' : '') . '
                />
            </div>
         </fieldset>
         
         ' . $pickupPointForm . '
         
         <fieldset class="order-delivery-form__fieldset">
            <legend>Commentaires</legend>
            <p><small>À l\'intention du préparateur de la commande</small></p>
            <div class="order-delivery-form__field">
                <textarea name="order_comment" maxlength="1024" class="order-delivery-form__textarea" id="order_comment" rows=5>' . $orderEntity->get('comment') . '</textarea>
            </div>
         </fieldset>
         
         <fieldset class="order-delivery-form__fieldset">
            ' . $newsletter_checkbox . '
            ' . $cgv_checkbox . '
            ' . $downloadableArticlesCheckbox . '
        </fieldset>

        <fieldset class="order-delivery-form__fieldset order-delivery-form__buttons">
            ' . $card_warning . '
            <a href="/pages/cart" class="btn btn-light">Revenir au panier</a>
            <button class="btn btn-primary" type="submit">Enregistrer la commande</button>
        </fieldset>
    </form>

';

    return new Response($content);
};
