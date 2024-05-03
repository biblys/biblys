<?php

/** @noinspection HttpUrlsUsage */

namespace Biblys\Legacy;

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Exception\OrderDetailsValidationException;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use CartManager;
use Exception;
use Mockery;
use OrderManager;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
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

        // when
        OrderDeliveryHelpers::validateOrderDetails($request, $currentSite);

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

        // then
        $this->expectException(OrderDetailsValidationException::class);
        $this->expectExceptionMessage("Le champ &laquo;&nbsp;Numéro de téléphone&nbsp;&raquo; est obligatoire.");

        // when
        OrderDeliveryHelpers::validateOrderDetails($request, $currentSite);
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

        // when
        OrderDeliveryHelpers::validateOrderDetails($request, $currentSite);

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
        $site = EntityFactory::createSite();
        $site->setOpt("virtual_stock", 1);
        $GLOBALS["LEGACY_CURRENT_SITE"] = $site;
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
        $shipping = EntityFactory::createShipping();
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
                    <p>Bonjour Alec,</p>

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

                    

                    <p><strong>Adresse d\'expédition :</strong></p>

                    <p>
                         Alec <br />
                        <br />
                        
                         <br />
                        France
                    </p>

                    

                    <p>
                        Si ce n\'est pas déjà fait, vous pouvez payer votre commande à l\'adresse ci-dessous :<br />
                        http://www.biblys.fr/order/'.$order->get("url").'
                    </p>
                    
                    
                    <p>
                        Consultez nos conditions générales de vente :<br />
                        http://www.biblys.fr/page/'.$termsPage->getUrl().'
                    </p>
                

                    <p>Merci pour votre commande !</p>
                </body>
            </html>
        ';
        
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->exactly(2))
            ->method("send")
            ->withConsecutive(
                [
                    "customer@example.net",
                    "Commande n° {$order->get("id")}",
                    $mailBody
                ],
                [
                    "contact@paronymie.fr",
                    "Commande n° {$order->get("id")}",
                    $mailBody
                ]
            )
            ->willReturn(true);

        // when
        OrderDeliveryHelpers::sendOrderConfirmationMail(
            $order,
            $shipping,
            $mailer,
            $site,
            false,
            $termsPage,
        );
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
        $site = EntityFactory::createSite();
        $site->setOpt("virtual_stock", 1);
        $GLOBALS["site"] = $site;
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
        $shipping = EntityFactory::createShipping();
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
                    <p>Bonjour Alec,</p>

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

                    

                    <p><strong>Adresse d\'expédition :</strong></p>

                    <p>
                         Alec <br />
                        <br />
                        
                         <br />
                        France
                    </p>

                    

                    <p>
                        Si ce n\'est pas déjà fait, vous pouvez payer votre commande à l\'adresse ci-dessous :<br />
                        http://www.biblys.fr/order/'.$order->get("url").'
                    </p>
                    
                    
                    <p>
                        Consultez nos conditions générales de vente :<br />
                        http://www.biblys.fr/page/'.$termsPage->getUrl().'
                    </p>
                

                    <p>Merci pour votre commande !</p>
                </body>
            </html>
        ';

        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->exactly(2))
            ->method("send")
            ->withConsecutive(
                [
                    "customer@example.net",
                    "Commande n° {$order->get("id")} (mise à jour)",
                    $mailBody
                ],
                [
                    "contact@paronymie.fr",
                    "Commande n° {$order->get("id")} (mise à jour)",
                    $mailBody
                ]
            )
            ->willReturn(true);

        // when
        OrderDeliveryHelpers::sendOrderConfirmationMail(
            $order,
            $shipping,
            $mailer,
            $site,
            true,
            $termsPage,
        );
    }
}
