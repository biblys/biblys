<?php

/** @noinspection HttpUrlsUsage */

namespace Biblys\Legacy;

use Biblys\Service\Mailer;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use CartManager;
use Exception;
use OrderManager;
use PHPUnit\Framework\TestCase;

require_once(__DIR__."/../../setUp.php");

class OrderDeliveryHelpersTest extends TestCase
{

    /**
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
                    <title>YS | Commande n° '.$order->get("id").'</title>
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
                    "YS | Commande n° {$order->get("id")}",
                    $mailBody
                ],
                [
                    "contact@librys.fr",
                    "YS | Commande n° {$order->get("id")}",
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
                    <title>YS | Commande n° '.$order->get("id").' (mise à jour)</title>
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
                    "YS | Commande n° {$order->get("id")} (mise à jour)",
                    $mailBody
                ],
                [
                    "contact@librys.fr",
                    "YS | Commande n° {$order->get("id")} (mise à jour)",
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
