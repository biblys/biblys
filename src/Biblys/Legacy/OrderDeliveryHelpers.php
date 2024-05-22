<?php

/** @noinspection HttpUrlsUsage */

namespace Biblys\Legacy;

use Article;
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
use Model\Cart;
use Model\Page;
use Model\SpecialOffer;
use Model\SpecialOfferQuery;
use Model\Stock;
use Model\StockQuery;
use Order;
use OrderManager;
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
        Request $request,
        CurrentSite $currentSite,
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

    public static function getOrderInProgressForVisitor(CurrentUser $currentUser): mixed
    {
        if (!$currentUser->isAuthentified()) {
            return null;
        }

        $om = new OrderManager();
        return $om->get([
            'order_type' => 'web',
            'user_id' => $currentUser->getUser()->getId(),
            'order_payment_date' => 'NULL',
            'order_shipping_date' => 'NULL',
            'order_cancel_date' => 'NULL'
        ]);
    }

    /**
     * @throws PropelException
     */
    public static function getCountryInput(Cart $cart, ?int $countryId): string
    {
        $com = new CountryManager();
        if (CartHelpers::cartNeedsShipping($cart)) {
            if (!is_int($countryId)) {
                throw new BadRequestHttpException("country_id parameter is required when cart needs shipping");
            }
            $country = $com->getById($countryId);
            if (!$country) {
                trigger_error('Pays incorrect');
            }
            $countryInput = '
            <input type="text" class="order-delivery-form__input" value="'.$country->get('name').'" readonly>
            <input type="hidden" name="country_id" value="'.$country->get('id').'">
            <a class="btn btn-light" href="/pages/cart">modifier</a>
        ';
        } else {

            $countries = $com->getAll();
            $countriesOptions = array_map(function ($country) {
                return '<option value="'.$country->get('id').'">'.$country->get('name').'</option>';
            }, $countries);

            $countryInput = '
            <select id="country_id" name="country_id" class="order-delivery-form__select">
                <option></option>
                <option value="67">France</option>
                '.implode($countriesOptions).'
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
        Order $order,
        ?Shipping $shipping,
        Mailer $mailer,
        CurrentSite $currentSite,
        bool $isUpdatingAnExistingOrder,
        ?Page $termsPage
    ): void
    {
        $site = $currentSite->getSite();
        $mailSubject = "Commande n° {$order->get("id")}";
        if ($isUpdatingAnExistingOrder) {
            $mailSubject .= ' (mise à jour)';
        }

        $confirmationSentence = '<p>votre nouvelle commande a bien été enregistrée.</p>';
        if ($isUpdatingAnExistingOrder) {
            $confirmationSentence = '<p>votre commande a été mise à jour.</p>';
        }

        $copiesInOrder = $order->getCopies();
        $numberOfCopiesInOrder = count($copiesInOrder);
        $articlesInOrder = array_map(function ($copy) {
            $location = null;
            $condition = null;
            $pubYear = null;

            if ($copy->has('stockage')) {
                $location = "<br />Emplacement : ".$copy->get('stockage');
            }

            if ($copy->has('condition')) {
                $condition = $copy->get('condition').' | ';
            }

            if ($copy->has('pub_year')) {
                $pubYear = ', '.$copy->get('pub_year');
            }

            /** @var Article $article */
            $article = $copy->get('article');
            return '
                <p>
                    <a href="http://'.$_SERVER['HTTP_HOST'].'/'.$article->get('url').'">'.$article->get('title').'</a> 
                    ('.$article->get("collection")->get("name").numero($article->get('number')).')<br>
                    de '.authors($article->get("authors")).'<br>
                    '.$article->get("collection")->get("name").$pubYear.'<br>
                    '.$condition.currency($copy->get('selling_price') / 100).'
                    '.$location.'
                </p>
            ';
        }, $copiesInOrder);

        $shippingLine = 'Frais de port offerts<br>';
        if (!empty($shipping)) {
            $shippingLine = "Frais de port : ".currency($shipping->get("fee") / 100)." (".$shipping->get("mode").")<br>";
        }

        $orderEbooks = null;
        if ($order->containsDownloadable()) {
            $orderEbooks = '
                <p>
                    Après paiement de votre commande, vous pourrez télécharger les articles numériques de votre commande depuis
                    <a href="http://'.$_SERVER['HTTP_HOST'].'/pages/log_myebooks">
                        votre bibliothèque numérique</a>.
                </p>
            ';
        }

        $mailAddressType = '<p><strong>Adresse d’expédition :</strong></p>';
        if ($shipping && $shipping->get("type") === "magasin") {
            $mailAddressType = '<p>Vous avez choisi le retrait en magasin. Vous serez averti par courriel lorsque votre commande sera disponible.</p><p><strong>Adresse de facturation :</strong></p>';
        }

        $mailComment = null;
        if ($order->has('comment')) {
            $mailComment = '<p><strong>Commentaire du client :</strong></p><p>'.nl2br($order->get('comment')).'</p>';
        }

        $termsLink = null;
        if ($termsPage) {
            $termsLink = '
                    <p>
                        Consultez nos conditions générales de vente :<br />
                        http://www.biblys.fr/page/'.$termsPage->getUrl().'
                    </p>
                ';
        }

        $mailBody = '
            <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>'.$mailSubject.'</title>
                    <style>
                        p {
                            margin-bottom: 5px;
                        }
                    </style>
                </head>
                <body>
                    <p>Bonjour '.$order->get('firstname').',</p>

                    '.$confirmationSentence.'

                    <p><strong><a href="http://'.$_SERVER['HTTP_HOST'].'/order/'.$order->get('url').'">Commande n&deg; '.$order->get('order_id').'</a></strong></p>

                    <p><strong>'.$numberOfCopiesInOrder.' article'.s($numberOfCopiesInOrder).'</strong></p>

                    '.implode($articlesInOrder).'

                    <p>
                        ------------------------------<br />
                        '.$shippingLine.'
                        Total : '.currency($order->getTotal() / 100).'
                    </p>

                    '.$orderEbooks.'

                    '.$mailAddressType.'

                    <p>
                        '.$order->get('title').' '.$order->get('firstname').' '.$order->get('lastname').'<br />
                        '.$order->get('address1').'<br />
                        '.($order->has('address2') ? $order->get('address2').'<br>' : null).'
                        '.$order->get('postalcode').' '.$order->get('city').'<br />
                        '.$order->getCountryName().'
                    </p>

                    '.$mailComment.'

                    <p>
                        Si ce n’est pas déjà fait, vous pouvez payer votre commande à l’adresse ci-dessous :<br />
                        http://'.$_SERVER['HTTP_HOST'].'/order/'.$order->get('url').'
                    </p>
                    
                    '.$termsLink.'

                    <p>Merci pour votre commande !</p>
                </body>
            </html>
        ';

        // Send email to customer from site
        $mailer->send($order->get('email'), $mailSubject, $mailBody);

        // Send email to site contact adress
        $from = [$site->getContact() => trim($order->get('firstname').' '.$order->get('lastname'))];
        $replyTo = $order->get('email');
        $mailSubject = trim($currentSite->getOption("order_mail_subject_prefix")." ".$mailSubject);
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
            ->filterByAvailabilityDilicom(\Model\Article::$AVAILABILITY_PRIVATELY_PRINTED)
            ->endUse()
            ->find();
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
        Cart $cart,
        SpecialOffer $specialOffer
    ): bool
    {
        $cartItems = $cart->getStocks()->getArrayCopy();
        $itemsInTargetCollectionCount = array_reduce($cartItems, function ($total, $copy) use ($specialOffer) {
            /** @var \Model\Article $article */
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
