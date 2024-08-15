<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../setUp.php";

class StockItemControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testEditFreePriceAction()
    {
        // given
        $controller = new StockItemController();

        $site = ModelFactory::createSite();
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle(price: 500, isPriceEditable: true);
        $stock = ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);

        $request = new Request();
        $request->request->set("new_price", 6);
        $request->headers->set("Accept", "application/json");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getCart")->andReturn($cart);
        $currentUser->shouldReceive("hasStockItemInCart")->andReturn(true);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->andReturn(null);

        // when
        $response = $controller->editFreePriceAction($request, $currentUser, $currentSite, $stock->getId());

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http status 200"
        );
        $stock->reload();
        $this->assertEquals(
            600,
            $stock->getSellingPrice(),
            "it should have updated stock selling price"
        );
        $cart->reload();
        $this->assertEquals(
            600,
            $cart->getAmount(),
            "it should have updated cart amount"
        );
    }
}
