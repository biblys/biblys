<?php

namespace Legacy;

use AppBundle\Controller\LegacyController;
use ArticleManager;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Test\EntityFactory;
use CartManager;
use CollectionManager;
use Exception;
use Framework\Exception\AuthException;
use OrderManager;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use PublisherManager;
use ShippingManager;
use Site;
use StockManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Visitor;

require_once __DIR__ . "/../../../setUp.php";

class CartTest extends TestCase
{
    /**
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function testCartDisplay()
    {
        global $_V, $site;

        // given
        $site->setOpt("virtual_stock", 1);
        $flashBag = $this
            ->getMockBuilder("Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface")
            ->getMock();
        $flashBag->method("get")->willReturn([]);
        $session = $this
            ->getMockBuilder("Symfony\Component\HttpFoundation\Session\Session")
            ->getMock();
        $session->method("getFlashBag")->willReturn($flashBag);
        $request = new Request();
        $request->query->set("page", "cart");
        $cart = $_V->getCart("create");
        $article = EntityFactory::createArticle([
            "article_title" => "Papeete",
            "type_id" => 1,
        ]);
        $cm = new CartManager();
        $cm->addArticle($cart, $article);
        $mailer = new Mailer();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);

        // when
        $legacyController = new LegacyController();
        $response = $legacyController->defaultAction($request, $session, $mailer, $config, $currentSite);

        // then
        $om = new OrderManager();
        $order = $om->get(["order_email" => "customer@biblys.fr"]);
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertStringContainsString(
            "Papeete",
            $response->getContent(),
            "it should display article in cart"
        );
        $this->assertStringContainsString(
            "Finaliser votre commande",
            $response->getContent(),
            "it should display the finalize order button"
        );
    }
}