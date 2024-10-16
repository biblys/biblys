<?php

/** @noinspection HttpUrlsUsage */

namespace Biblys\Legacy;

use Biblys\Data\ArticleType;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Exception\OrderDetailsValidationException;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Test\EntityFactory;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use CartManager;
use DateTime;
use Entity\Exception\CartException;
use Exception;
use Mockery;
use Model\Article;
use Model\Cart;
use OrderManager;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

require_once(__DIR__."/../../setUp.php");

class OrderDeliveryHelpersTest extends TestCase
{
    /**
     * @throws OrderDetailsValidationException
     * @throws Exception
     */
    public function testValidateOrderDetails()
    {
        // given
        $request = new Request();
        $request->request->set("order_firstname", "Victor");
        $request->request->set("order_lastname", "Hugo");
        $request->request->set("order_address1", "Place des Vosges");
        $request->request->set("order_postalcode", "75004");
        $request->request->set("order_city", "Paris");
        $request->request->set("country_id", 1);
        $request->request->set("order_email", "victor.hugo@biblys.fr");
        $request->request->set("cgv_checkbox", 1);
        $request->request->set("order_phone", "");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("order_phone_required")
            ->andReturn(0);

        $cart = Mockery::mock(Cart::class);
        $cart->shouldReceive("containsDownloadableArticles")->andReturn(false);

        // when
        OrderDeliveryHelpers::validateOrderDetails($request, $currentSite, $cart);

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws OrderDetailsValidationException
     * @throws Exception
     */
    public function testValidateOrderDetailsThrowsWhenPhoneIsRequiredAndMissing()
    {
        // given
        $request = new Request();
        $request->request->set("order_firstname", "Victor");
        $request->request->set("order_lastname", "Hugo");
        $request->request->set("order_address1", "Place des Vosges");
        $request->request->set("order_postalcode", "75004");
        $request->request->set("order_city", "Paris");
        $request->request->set("country_id", 1);
        $request->request->set("order_email", "victor.hugo@biblys.fr");
        $request->request->set("cgv_checkbox", 1);
        $request->request->set("order_phone", "");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("order_phone_required")
            ->andReturn(1);

        $cart = Mockery::mock(Cart::class);
        $cart->shouldReceive("containsDownloadableArticles")->andReturn(false);

        // then
        $this->expectException(OrderDetailsValidationException::class);
        $this->expectExceptionMessage("Le champ &laquo;&nbsp;Numéro de téléphone&nbsp;&raquo; est obligatoire.");

        // when
        OrderDeliveryHelpers::validateOrderDetails($request, $currentSite, $cart);
    }

    /**
     * @throws OrderDetailsValidationException
     * @throws Exception
     */
    public function testValidateOrderDetailsSucceedsWhenPhoneIsRequiredAndPresent()
    {
        // given
        $request = new Request();
        $request->request->set("order_firstname", "Victor");
        $request->request->set("order_lastname", "Hugo");
        $request->request->set("order_address1", "Place des Vosges");
        $request->request->set("order_postalcode", "75004");
        $request->request->set("order_city", "Paris");
        $request->request->set("country_id", 1);
        $request->request->set("order_email", "victor.hugo@biblys.fr");
        $request->request->set("cgv_checkbox", 1);
        $request->request->set("order_phone", "01234567890");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("order_phone_required")
            ->andReturn(1);

        $cart = Mockery::mock(Cart::class);
        $cart->shouldReceive("containsDownloadableArticles")->andReturn(false);

        // when
        OrderDeliveryHelpers::validateOrderDetails($request, $currentSite, $cart);

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws OrderDetailsValidationException
     * @throws Exception
     */
    public function testValidateOrderDetailsThrowsWhenCartContainsDownloadableAndCheckboxIsUnchecked()
    {
        // given
        $request = new Request();
        $request->request->set("order_firstname", "Victor");
        $request->request->set("order_lastname", "Hugo");
        $request->request->set("order_address1", "Place des Vosges");
        $request->request->set("order_postalcode", "75004");
        $request->request->set("order_city", "Paris");
        $request->request->set("country_id", 1);
        $request->request->set("order_email", "victor.hugo@biblys.fr");
        $request->request->set("cgv_checkbox", 1);
        $request->request->set("order_phone", "06");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("order_phone_required")
            ->andReturn(1);

        $cart = Mockery::mock(Cart::class);
        $cart->shouldReceive("containsDownloadableArticles")->andReturn(true);

        // then
        $this->expectException(OrderDetailsValidationException::class);
        $this->expectExceptionMessage(
            "Vous devez accepter les conditions spécifiques au numérique, " .
            "car votre panier contient des articles téléchargeables."
        );

        // when
        OrderDeliveryHelpers::validateOrderDetails($request, $currentSite, $cart);
    }

    /**
     * @throws OrderDetailsValidationException
     * @throws Exception
     */
    public function testValidateOrderDetailsThrowsWhenCartContainsDownloadableAndCheckboxIsChecked()
    {
        // given
        $request = new Request();
        $request->request->set("order_firstname", "Victor");
        $request->request->set("order_lastname", "Hugo");
        $request->request->set("order_address1", "Place des Vosges");
        $request->request->set("order_postalcode", "75004");
        $request->request->set("order_city", "Paris");
        $request->request->set("country_id", 1);
        $request->request->set("order_email", "victor.hugo@biblys.fr");
        $request->request->set("order_phone", "01234567890");
        $request->request->set("cgv_checkbox", 1);
        $request->request->set("downloadable_articles_checkbox", "1");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("order_phone_required")
            ->andReturn(1);

        $cart = Mockery::mock(Cart::class);
        $cart->shouldReceive("containsDownloadableArticles")->andReturn(true);

        // when
        OrderDeliveryHelpers::validateOrderDetails($request, $currentSite, $cart);

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function testSendOrderConfirmationMail()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $cm = new CartManager();
        $om = new OrderManager();
        $cart = EntityFactory::createCart();
        $article = EntityFactory::createArticle([
            "article_title" => "Le livre dans la commande",
            "article_url" => "le-livre-dans-la-commande",
        ]);
        $copy = EntityFactory::createStock(["article_id" => $article->get("id")]);
        $cm->addStock($cart, $copy);
        $order = EntityFactory::createOrder();
        $om->hydrateFromCart($order, $cart);
        $shipping = EntityFactory::createShipping(mode: "Colissimo", fee: 560);
        $termsPage = ModelFactory::createPage();

        $mailBody = '
            <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Commande n° '.$order->get("id").'</title>
                    <style>
                        p {
                            margin-bottom: 5px;
                        }
                    </style>
                </head>
                <body>
                    <p>Bonjour Marie,</p>

                    <p>votre nouvelle commande a bien été enregistrée.</p>

                    <p><strong><a href="http://www.biblys.fr/order/'.$order->get("url").'">Commande n&deg; '.$order->get("id").'</a></strong></p>

                    <p><strong>1 article</strong></p>

                    
                <p>
                    <a href="http://www.biblys.fr/le-livre-dans-la-commande">Le livre dans la commande</a> 
                    (La Blanche n&deg;&nbsp;19)<br>
                    de <br>
                    La Blanche<br>
                    18,99&nbsp;&euro;
                    <br />Emplacement : Paris
                </p>
            

                    <p>
                        ------------------------------<br />
                        Frais de port : 5,60&nbsp;&euro; (Colissimo)<br>
                        Total : 18,99&nbsp;&euro;
                    </p>

                    

                    <p><strong>Adresse d’expédition :</strong></p>

                    <p>
                         Marie Golade<br />
                        <br />
                        
                         <br />
                        France
                    </p>

                    

                    <p>
                        Si ce n’est pas déjà fait, vous pouvez payer votre commande à l’adresse ci-dessous :<br />
                        https://www.biblys.fr/order/'.$order->get("url").'
                    </p>
                    
                    
                    <p>
                        Consultez nos conditions générales de vente :<br />
                        http://www.biblys.fr/page/'.$termsPage->getUrl().'
                    </p>
                

                    <p>Merci pour votre commande !</p>
                </body>
            </html>
        ';
        
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")
            ->with(
                "customer@example.net",
                "Commande n° {$order->get("id")}",
                $mailBody
            )
            ->andReturn(true);
        $mailer->shouldReceive("send")
            ->with(
                "contact@paronymie.fr",
                "Commande n° {$order->get("id")}",
                $mailBody,
                ['contact@paronymie.fr' => 'Marie Golade'],
                ['reply-to' => 'customer@example.net'],
            )
            ->andReturn(true);

        // when
        OrderDeliveryHelpers::sendOrderConfirmationMail(
            $order,
            $shipping,
            $mailer,
            $currentSite,
            false,
            $termsPage,
        );

        // then
        $this->expectNotToPerformAssertions();
    }


    /**
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function testSendOrderConfirmationMailWithMondialRelayShipping()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $cm = new CartManager();
        $om = new OrderManager();
        $cart = EntityFactory::createCart();
        $article = EntityFactory::createArticle([
            "article_title" => "Le livre au point retrait",
            "article_url" => "le-livre-au-point-retrait",
        ]);
        $copy = EntityFactory::createStock(["article_id" => $article->get("id")]);
        $cm->addStock($cart, $copy);
        $order = EntityFactory::createOrder(firstName: "Rondial", lastName: "Melay",  mondialRelayPickupPointCode: "001234");
        $om->hydrateFromCart($order, $cart);
        $shipping = EntityFactory::createShipping(type: "mondial-relay", mode: "Mondial Relay", fee: 999);
        $termsPage = ModelFactory::createPage();

        $mailBody = '
            <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Commande n° '.$order->get("id").'</title>
                    <style>
                        p {
                            margin-bottom: 5px;
                        }
                    </style>
                </head>
                <body>
                    <p>Bonjour Rondial,</p>

                    <p>votre nouvelle commande a bien été enregistrée.</p>

                    <p><strong><a href="http://www.biblys.fr/order/'.$order->get("url").'">Commande n&deg; '.$order->get("id").'</a></strong></p>

                    <p><strong>1 article</strong></p>

                    
                <p>
                    <a href="http://www.biblys.fr/le-livre-au-point-retrait">Le livre au point retrait</a> 
                    (La Blanche n&deg;&nbsp;19)<br>
                    de <br>
                    La Blanche<br>
                    18,99&nbsp;&euro;
                    <br />Emplacement : Paris
                </p>
            

                    <p>
                        ------------------------------<br />
                        Frais de port : 9,99&nbsp;&euro; (Mondial Relay)<br>
                        Total : 18,99&nbsp;&euro;
                    </p>

                    

                    <p><strong>Adresse d’expédition :</strong></p>

                    <p>
                        Rondial Melay<br />
                        Point Mondial Relay n° 001234
                        <a href="https://www.biblys.fr/order/'.$order->get("url").'">Plus d’infos</a>
                    </p>

                    

                    <p>
                        Si ce n’est pas déjà fait, vous pouvez payer votre commande à l’adresse ci-dessous :<br />
                        https://www.biblys.fr/order/'.$order->get("url").'
                    </p>
                    
                    
                    <p>
                        Consultez nos conditions générales de vente :<br />
                        http://www.biblys.fr/page/'.$termsPage->getUrl().'
                    </p>
                

                    <p>Merci pour votre commande !</p>
                </body>
            </html>
        ';

        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")
            ->with(
                "customer@example.net",
                "Commande n° {$order->get("id")}",
                $mailBody
            )
            ->andReturn(true);
        $mailer->shouldReceive("send")
            ->with(
                "contact@paronymie.fr",
                "Commande n° {$order->get("id")}",
                $mailBody,
                ['contact@paronymie.fr' => 'Rondial Melay'],
                ['reply-to' => 'customer@example.net'],
            )
            ->andReturn(true);

        // when
        OrderDeliveryHelpers::sendOrderConfirmationMail(
            $order,
            $shipping,
            $mailer,
            $currentSite,
            false,
            $termsPage,
        );

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function testSendOrderConfirmationMailWithSubjectPrefix()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")
            ->with("order_mail_subject_prefix")
            ->andReturn("PARONYMIE ·");
        $cm = new CartManager();
        $om = new OrderManager();
        $cart = EntityFactory::createCart();
        $article = EntityFactory::createArticle();
        $copy = EntityFactory::createStock(["article_id" => $article->get("id")]);
        $cm->addStock($cart, $copy);
        $order = EntityFactory::createOrder();
        $om->hydrateFromCart($order, $cart);
        $shipping = EntityFactory::createShipping();
        $termsPage = ModelFactory::createPage();

        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")
            ->with(
                "customer@example.net",
                "Commande n° {$order->get("id")}",
                Mockery::any()
            )
            ->andReturn(true);
        $mailer->shouldReceive("send")
            ->with(
                "contact@paronymie.fr",
                "PARONYMIE · Commande n° {$order->get("id")}",
                Mockery::any(),
                ['contact@paronymie.fr' => 'Marie Golade'],
                ['reply-to' => 'customer@example.net'],
            )
            ->andReturn(true);

        // when
        OrderDeliveryHelpers::sendOrderConfirmationMail(
            $order,
            $shipping,
            $mailer,
            $currentSite,
            false,
            $termsPage,
        );

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function testSendConfirmationMailOnOrderUpdate()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $cm = new CartManager();
        $om = new OrderManager();
        $cart = EntityFactory::createCart();
        $article = EntityFactory::createArticle([
            "article_title" => "Le livre dans la commande mise à jour",
            "article_url" => "le-livre-dans-la-commande-mise-a-jour",
        ]);
        $copy = EntityFactory::createStock(["article_id" => $article->get("id")]);
        $cm->addStock($cart, $copy);
        $order = EntityFactory::createOrder();
        $om->hydrateFromCart($order, $cart);
        $shipping = EntityFactory::createShipping(mode: "Colissimo", fee: 560);
        $termsPage = ModelFactory::createPage();

        $mailBody = '
            <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Commande n° '.$order->get("id").' (mise à jour)</title>
                    <style>
                        p {
                            margin-bottom: 5px;
                        }
                    </style>
                </head>
                <body>
                    <p>Bonjour Marie,</p>

                    <p>votre commande a été mise à jour.</p>

                    <p><strong><a href="http://www.biblys.fr/order/'.$order->get("url").'">Commande n&deg; '.$order->get("id").'</a></strong></p>

                    <p><strong>1 article</strong></p>

                    
                <p>
                    <a href="http://www.biblys.fr/le-livre-dans-la-commande-mise-a-jour">Le livre dans la commande mise à jour</a> 
                    (La Blanche n&deg;&nbsp;19)<br>
                    de <br>
                    La Blanche<br>
                    18,99&nbsp;&euro;
                    <br />Emplacement : Paris
                </p>
            

                    <p>
                        ------------------------------<br />
                        Frais de port : 5,60&nbsp;&euro; (Colissimo)<br>
                        Total : 18,99&nbsp;&euro;
                    </p>

                    

                    <p><strong>Adresse d’expédition :</strong></p>

                    <p>
                         Marie Golade<br />
                        <br />
                        
                         <br />
                        France
                    </p>

                    

                    <p>
                        Si ce n’est pas déjà fait, vous pouvez payer votre commande à l’adresse ci-dessous :<br />
                        https://www.biblys.fr/order/'.$order->get("url").'
                    </p>
                    
                    
                    <p>
                        Consultez nos conditions générales de vente :<br />
                        http://www.biblys.fr/page/'.$termsPage->getUrl().'
                    </p>
                

                    <p>Merci pour votre commande !</p>
                </body>
            </html>
        ';

        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")
            ->with(
                "customer@example.net",
                "Commande n° {$order->get("id")} (mise à jour)",
                $mailBody,
            )
            ->andReturn(true);
        $mailer->shouldReceive("send")
            ->with(
                "contact@paronymie.fr",
                "Commande n° {$order->get("id")} (mise à jour)",
                $mailBody,
                ['contact@paronymie.fr' => 'Marie Golade'],
                ['reply-to' => 'customer@example.net'],
            )
            ->andReturn(true);


        // when
        OrderDeliveryHelpers::sendOrderConfirmationMail(
            $order,
            $shipping,
            $mailer,
            $currentSite,
            true,
            $termsPage,
        );

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testValidateCartContentWhenCartIsEmpty()
    {
        // given
        $site = ModelFactory::createSite();
        $cart = ModelFactory::createCart(site: $site);
        $currentSite = new CurrentSite($site);

        // when
        $exception = Helpers::runAndCatchException(function() use($currentSite, $cart) {
            OrderDeliveryHelpers::validateCartContent($currentSite, $cart);
        });

        // then
        $this->assertInstanceOf(CartException::class, $exception);
        $this->assertEquals("Le panier est vide.", $exception->getMessage());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testValidateCartContentWhenCartContainsPrivatelyPrintedArticle()
    {
        // given
        $site = ModelFactory::createSite();
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle(
            title: "HorsCo", availabilityDilicom: Article::$AVAILABILITY_PRIVATELY_PRINTED
        );
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);
        $currentSite = new CurrentSite($site);

        // when
        $exception = Helpers::runAndCatchException(function() use($currentSite, $cart) {
            OrderDeliveryHelpers::validateCartContent($currentSite, $cart);
        });

        // then
        $this->assertInstanceOf(CartException::class, $exception);
        $this->assertEquals(
            "Le panier contient un article hors-commerce : HorsCo.",
            $exception->getMessage()
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testValidateCartContentWhenSpecialOfferIsNotActive()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $cart = ModelFactory::createCart(site: $site);

        $targetCollection = ModelFactory::createCollection(name: "Collection cible");
        $freeArticle = ModelFactory::createArticle(
            title: "Trotar",
            collection: $targetCollection,
            availabilityDilicom: Article::$AVAILABILITY_PRIVATELY_PRINTED,
        );
        ModelFactory::createStockItem(site: $site, article: $freeArticle, cart: $cart);

        $article1 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article1, cart: $cart);
        $article2 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article2, cart: $cart);

        ModelFactory::createSpecialOffer(
            site: $site,
            name: "Cado",
            targetCollection: $targetCollection,
            freeArticle: $freeArticle,
            endDate: new DateTime("yesterday")
        );

        // when
        $exception = Helpers::runAndCatchException(function() use($currentSite, $cart) {
            OrderDeliveryHelpers::validateCartContent($currentSite, $cart);
        });

        // then
        $this->assertInstanceOf(CartException::class, $exception);
        $this->assertEquals(
            "Le panier contient un article hors-commerce : Trotar.",
            $exception->getMessage()
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testValidateCartContentWhenSpecialOfferConditionsAreNotMet()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $cart = ModelFactory::createCart(site: $site);

        $targetCollection = ModelFactory::createCollection(name: "Collection cible");
        $freeArticle = ModelFactory::createArticle(
            title: "Cékado",
            collection: $targetCollection,
            availabilityDilicom: Article::$AVAILABILITY_PRIVATELY_PRINTED,
        );
        ModelFactory::createStockItem(site: $site, article: $freeArticle, cart: $cart);

        ModelFactory::createSpecialOffer(
            site: $site,
            name: "Cado",
            targetCollection: $targetCollection,
            freeArticle: $freeArticle,
        );

        $article1 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article1, cart: $cart);

        // when
        $exception = Helpers::runAndCatchException(function() use($currentSite, $cart) {
            OrderDeliveryHelpers::validateCartContent($currentSite, $cart);
        });

        // then
        $this->assertInstanceOf(CartException::class, $exception);
        $this->assertEquals(
            "Votre panier ne remplit pas les conditions pour bénéficier de l'offre spéciale Cado.",
            $exception->getMessage()
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testValidateCartContentWhenSpecialOfferFreeArticleIsMoreThanOnceInCart()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $cart = ModelFactory::createCart(site: $site);

        $targetCollection = ModelFactory::createCollection(name: "Collection cible");
        $freeArticle = ModelFactory::createArticle(
            title: "Cékado",
            collection: $targetCollection,
            availabilityDilicom: Article::$AVAILABILITY_PRIVATELY_PRINTED,
        );
        ModelFactory::createStockItem(site: $site, article: $freeArticle, cart: $cart);
        ModelFactory::createStockItem(site: $site, article: $freeArticle, cart: $cart);

        ModelFactory::createSpecialOffer(
            site: $site,
            name: "Cado",
            targetCollection: $targetCollection,
            freeArticle: $freeArticle,
        );

        $article1 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article1, cart: $cart);
        $article2 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article2, cart: $cart);

        // when
        $exception = Helpers::runAndCatchException(function() use($currentSite, $cart) {
            OrderDeliveryHelpers::validateCartContent($currentSite, $cart);
        });

        // then
        $this->assertInstanceOf(CartException::class, $exception);
        $this->assertEquals(
            "Un panier ne peut pas contenir plusieurs articles offerts",
            $exception->getMessage()
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testValidateCartContentWhenCartMeetsConditionsForSpecialOffer()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $cart = ModelFactory::createCart(site: $site);

        $targetCollection = ModelFactory::createCollection(name: "Collection cible");
        $freeArticle = ModelFactory::createArticle(
            title: "Cékado",
            collection: $targetCollection,
            availabilityDilicom: Article::$AVAILABILITY_PRIVATELY_PRINTED,
        );
        ModelFactory::createStockItem(site: $site, article: $freeArticle, cart: $cart);

        ModelFactory::createSpecialOffer(
            site: $site,
            targetCollection: $targetCollection,
            freeArticle: $freeArticle,
        );

        $article1 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article1, cart: $cart);
        $article2 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article2, cart: $cart);

        // when
        OrderDeliveryHelpers::validateCartContent($currentSite, $cart);

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testValidateCartContentWhenCartIsOk()
    {
        // given
        $site = ModelFactory::createSite();
        $cart = ModelFactory::createCart(site: $site);
        ModelFactory::createStockItem(site: $site, cart: $cart);
        $currentSite = new CurrentSite($site);

        // when
        OrderDeliveryHelpers::validateCartContent($currentSite, $cart);

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * #getCountryInput
     */

    /**
     * @throws PropelException
     */
    public function testGetCountryInput()
    {
        $cart = ModelFactory::createCart();
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $article, cart: $cart);
        $country = ModelFactory::createCountry(name: "Le Pays du jamais");

        // when
        $countryInput = OrderDeliveryHelpers::getCountryInput($cart, $country->getId());

        // then
        $this->assertEquals(
            '
            <input type="text" class="order-delivery-form__input" value="Le Pays du jamais" readonly>
            <input type="hidden" name="country_id" value="'.$country->getId().'">
            <a class="btn btn-light" href="/pages/cart">modifier</a>
        ',
            $countryInput
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetCountryInputWith0AsCountryId()
    {
        $cart = ModelFactory::createCart();
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $article, cart: $cart);

        // when
        $error = Helpers::runAndCatchException(fn() =>
        OrderDeliveryHelpers::getCountryInput($cart, countryId: 0)
        );

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $error);
        $this->assertEquals("The country_id parameter is required when cart needs shipping.", $error->getMessage());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetCountryInputWithUnknownCountry()
    {
        $cart = ModelFactory::createCart();
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(article: $article, cart: $cart);

        // when
        $error = Helpers::runAndCatchException(fn() =>
            OrderDeliveryHelpers::getCountryInput($cart, countryId: 999)
        );

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $error);
        $this->assertEquals("The country_id parameter must match an existing country.", $error->getMessage());
    }
}
