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


/** @noinspection HttpUrlsUsage */

namespace Biblys\Legacy;

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Exception\OrderDetailsValidationException;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use CountryManager;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Entity\Exception\CartException;
use Exception;
use Model\Article;
use Model\Cart;
use Model\Order;
use Model\OrderQuery;
use Model\Page;
use Model\SpecialOffer;
use Model\SpecialOfferQuery;
use Model\Stock;
use Model\StockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Shipping;
use ShippingManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class OrderDeliveryHelpers
{
    /**
     * @throws OrderDetailsValidationException
     * @throws Exception
     */
    public static function validateOrderDetails(
        Request     $request,
        CurrentSite $currentSite,
        Cart $cart,
    ): void
    {
        if (empty($request->request->get('order_firstname'))) {
            throw new OrderDetailsValidationException(
                'Le champ &laquo;&nbsp;Prénom&nbsp;&raquo; est obligatoire !'
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

        if (
            $currentSite->getOption("order_phone_required") &&
            empty($request->request->get("order_phone"))
        ) {
            throw new OrderDetailsValidationException(
                "Le champ &laquo;&nbsp;Numéro de téléphone&nbsp;&raquo; est obligatoire."
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

        $downloadableCheckbox = $request->request->get('downloadable_articles_checkbox');
        if ($cart->containsDownloadableArticles() && $downloadableCheckbox !== "1") {
            throw new OrderDetailsValidationException(
                "Vous devez accepter les conditions spécifiques au numérique, " .
                "car votre panier contient des articles téléchargeables."
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

    /**
     * @throws PropelException
     */
    public static function getOrderInProgressForVisitor(CurrentUser $currentUser, CurrentSite $currentSite): ?Order
    {
        if (!$currentUser->isAuthenticated()) {
            return null;
        }

        return OrderQuery::create()
            ->filterByType('web')
            ->filterBySite($currentSite->getSite())
            ->filterByUser($currentUser->getUser())
            ->filterByPaymentDate(null, Criteria::ISNULL)
            ->filterByShippingDate(null, Criteria::ISNULL)
            ->filterByCancelDate(null, Criteria::ISNULL)
            ->findOne();
    }

    /**
     * @throws PropelException
     */
    public static function getCountryInput(Cart $cart, int $countryId): string
    {
        $com = new CountryManager();
        if (CartHelpers::cartNeedsShipping($cart)) {
            if ($countryId === 0) {
                throw new BadRequestHttpException("The country_id parameter is required when cart needs shipping.");
            }

            $country = $com->getById($countryId);
            if (!$country) {
                throw new BadRequestHttpException("The country_id parameter must match an existing country.");
            }
            $countryInput = '
            <input type="text" class="order-delivery-form__input" value="' . $country->get('name') . '" readonly>
            <input type="hidden" name="country_id" value="' . $country->get('id') . '">
            <a class="btn btn-light" href="/pages/cart">modifier</a>
        ';
        } else {

            $countries = $com->getAll();
            $countriesOptions = array_map(function ($country) {
                return '<option value="' . $country->get('id') . '">' . $country->get('name') . '</option>';
            }, $countries);

            $countryInput = '
            <select id="country_id" name="country_id" class="order-delivery-form__select">
                <option></option>
                <option value="67">France</option>
                ' . implode($countriesOptions) . '
            </select>
        ';
        }
        return $countryInput;
    }

    /**
     * @throws PropelException
     */
    public static function calculateShippingFees(Cart $cart, ?int $shippingIdFromRequest): ?Shipping
    {
        if (CartHelpers::cartNeedsShipping($cart)) {
            if (!is_int($shippingIdFromRequest)) {
                throw new BadRequestHttpException("shipping_id parameter is required when cart needs shipping");
            }
            $shm = new ShippingManager();
            /** @var Shipping $shipping */
            $shipping = $shm->getById($shippingIdFromRequest);
            if (!$shipping) {
                trigger_error("Frais de port incorrect.");
            }

            return $shipping;
        }

        return null;
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public static function sendOrderConfirmationMail(
        Order       $order,
        ?Shipping   $shipping,
        Mailer      $mailer,
        CurrentSite $currentSite,
        bool        $isUpdatingAnExistingOrder,
        ?Page       $termsPage
    ): void
    {
        $site = $currentSite->getSite();
        $mailSubject = "Commande n° {$order->getId()}";
        if ($isUpdatingAnExistingOrder) {
            $mailSubject .= ' (mise à jour)';
        }

        $confirmationSentence = '<p>votre nouvelle commande a bien été enregistrée.</p>';
        if ($isUpdatingAnExistingOrder) {
            $confirmationSentence = '<p>votre commande a été mise à jour.</p>';
        }

        $copiesInOrder = $order->getStockItems();
        $numberOfCopiesInOrder = $copiesInOrder->count();
        /** @var Stock[] $stockItems */
        $stockItems = $copiesInOrder->getArrayCopy();
        $articlesInOrder = array_map(function (Stock $copy) {
            $location = null;
            $condition = null;
            $pubYear = null;

            if ($copy->getStockage()) {
                $location = "<br />Emplacement : " . $copy->getStockage();
            }

            if ($copy->getCondition()) {
                $condition = $copy->getCondition() . ' | ';
            }

            if ($copy->getPubYear()) {
                $pubYear = ', ' . $copy->getPubYear();
            }

            /** @var Article $article */
            $article = $copy->getArticle();
            return '
                <p>
                    <a href="http://' . $_SERVER['HTTP_HOST'] . '/' . $article->getUrl() . '">' . $article->getTitle() . '</a> 
                    (' . $article->getBookCollection()->getName() . numero($article->getNumber()) . ')<br>
                    de ' . authors($article->getAuthors()) . '<br>
                    ' . $article->getBookCollection()->getName(). $pubYear . '<br>
                    ' . $condition . currency($copy->getSellingPrice() / 100) . '
                    ' . $location . '
                </p>
            ';
        }, $stockItems);

        $shippingLine = 'Frais de port offerts<br>';
        if (!empty($shipping)) {
            $shippingLine = "Frais de port : " . currency($shipping->get("fee") / 100) . " (" . $shipping->get("mode") . ")<br>";
        }

        $orderContainsDownloadable = false;
        foreach ($order->getStockItems() as $stockItem) {
            $article = $stockItem->getArticle();
            $articleIsDownloadable = $article->getTypeId() === 2 || $article->getTypeId() === 10 || $article->getTypeId() === 11;
            if ($articleIsDownloadable) {
                $orderContainsDownloadable = true;
            }
        }

        $orderEbooks = null;
        if ($orderContainsDownloadable) {
            $orderEbooks = '
                <p>
                    Après paiement de votre commande, vous pourrez télécharger les articles numériques de votre commande depuis
                    <a href="http://' . $_SERVER['HTTP_HOST'] . '/pages/log_myebooks">
                        votre bibliothèque numérique</a>.
                </p>
            ';
        }

        $mailAddressType = '<p><strong>Adresse d’expédition :</strong></p>';
        if ($shipping && $shipping->get("type") === "magasin") {
            $mailAddressType = '<p>Vous avez choisi le retrait en magasin. Vous serez averti par courriel lorsque votre commande sera disponible.</p><p><strong>Adresse de facturation :</strong></p>';
        }

        $shippingAddress = '<p>
                        ' . $order->getTitle() . ' ' . $order->getFirstname() . ' ' . $order->getLastname() . '<br />
                        ' . $order->getAddress1() . '<br />
                        ' . ($order->getAddress2() ? $order->getAddress2() . '<br>' : null) . '
                        ' . $order->getPostalcode() . ' ' . $order->getCity() . '<br />
                        ' . $order->getCountry()->getName() . '
                    </p>';

        $orderUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/order/' . $order->getSlug();
        if ($shipping && $shipping->get("type") === "mondial-relay") {
            $shippingAddress = '<p>
                        ' . $order->getFirstname() . ' ' . $order->getLastname() . '<br />
                        Point Mondial Relay n° '.$order->getMondialRelayPickupPointCode().'
                        <a href="' . $orderUrl . '">Plus d’infos</a>
                    </p>';
        }

        $mailComment = null;
        if ($order->getComment()) {
            $mailComment = '<p><strong>Commentaire du client :</strong></p><p>' . nl2br($order->getComment()) . '</p>';
        }

        $termsLink = null;
        if ($termsPage) {
            $termsLink = '
                    <p>
                        Consultez nos conditions générales de vente :<br />
                        http://www.biblys.fr/page/' . $termsPage->getUrl() . '
                    </p>
                ';
        }

        $mailBody = '
            <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>' . $mailSubject . '</title>
                    <style>
                        p {
                            margin-bottom: 5px;
                        }
                    </style>
                </head>
                <body>
                    <p>Bonjour ' . $order->getFirstname() . ',</p>

                    ' . $confirmationSentence . '

                    <p><strong><a href="http://' . $_SERVER['HTTP_HOST'] . '/order/' . $order->getSlug() . '">Commande n&deg; ' . $order->getId() . '</a></strong></p>

                    <p><strong>' . $numberOfCopiesInOrder . ' article' . s($numberOfCopiesInOrder) . '</strong></p>

                    ' . implode($articlesInOrder) . '

                    <p>
                        ------------------------------<br />
                        ' . $shippingLine . '
                        Total : ' . currency($order->getTotalAmountWithShipping() / 100) . '
                    </p>

                    ' . $orderEbooks . '

                    ' . $mailAddressType . '

                    ' . $shippingAddress . '

                    ' . $mailComment . '

                    <p>
                        Si ce n’est pas déjà fait, vous pouvez payer votre commande à l’adresse ci-dessous :<br />
                        ' . $orderUrl . '
                    </p>
                    
                    ' . $termsLink . '

                    <p>Merci pour votre commande !</p>
                </body>
            </html>
        ';

        // Send email to customer from site
        $mailer->send($order->getEmail(), $mailSubject, $mailBody);

        // Send email to site contact address
        $from = [$site->getContact() => trim($order->getFirstname() . ' ' . $order->getLastname())];
        $replyTo = $order->getEmail();
        $mailSubject = trim($currentSite->getOption("order_mail_subject_prefix") . " " . $mailSubject);
        $mailer->send($site->getContact(), $mailSubject, $mailBody, $from, ['reply-to' => $replyTo]);
    }

    /**
     * @throws PropelException
     * @throws CartException
     */
    public static function validateCartContent(
        CurrentSite $currentSite,
        Cart        $cart,
    ):
    void
    {
        if ($cart->countStocks() === 0) {
            throw new CartException("Le panier est vide.");
        }

        /** @var Stock $item */
        $privatelyPrintedItems = StockQuery::create()
            ->filterByCart($cart)
            ->useArticleQuery()
            ->filterByAvailabilityDilicom(Article::$AVAILABILITY_PRIVATELY_PRINTED)
            ->endUse()
            ->find();

        $specialOffersApplied = 0;
        foreach ($privatelyPrintedItems as $item) {
            $specialOfferForArticle = SpecialOfferQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByActive()
                ->findOneByFreeArticleId($item->getArticle()->getId());

            if (!$specialOfferForArticle) {
                throw new CartException(
                    "Le panier contient un article hors-commerce : {$item->getArticle()->getTitle()}."
                );
            }

            $specialOffersApplied++;
            if ($specialOffersApplied > 1) {
                throw new CartException("Un panier ne peut pas contenir plusieurs articles offerts");
            }

            if (!self::_cartMeetsSpecialOfferConditions($cart, $specialOfferForArticle)) {
                throw new CartException(
                    "Votre panier ne remplit pas les conditions pour bénéficier de l'offre spéciale {$specialOfferForArticle->getName()}."
                );
            }
        }
    }

    /**
     * @throws PropelException
     */
    private static function _cartMeetsSpecialOfferConditions(
        Cart         $cart,
        SpecialOffer $specialOffer
    ): bool
    {
        $cartItems = $cart->getStocks()->getArrayCopy();
        $itemsInTargetCollectionCount = array_reduce($cartItems, function ($total, $copy) use ($specialOffer) {
            /** @var Article $article */
            $article = $copy->getArticle();

            if ($article === $specialOffer->getFreeArticle()) {
                return $total;
            }

            if ($article->getCollectionId() === $specialOffer->getTargetCollectionId()) {
                $total++;
            }

            return $total;
        }, 0);

        return $itemsInTargetCollectionCount >= $specialOffer->getTargetQuantity();
    }
}
