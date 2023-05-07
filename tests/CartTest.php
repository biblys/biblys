<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use Biblys\Test\EntityFactory;
use Propel\Runtime\Exception\PropelException;

require_once "setUp.php";

class CartTest extends PHPUnit\Framework\TestCase
{

    public function setUp(): void
    {
        $this->carts = [];
        $this->stocks = [];
        $this->article = null;
    }

    public function tearDown(): void
    {
        if (count($this->carts) >= 1) {
            $cm = new CartManager();
            foreach ($this->carts as $cart) {
                $cm->delete($cart);
            }
            $this->carts = [];
        }

        if (count($this->stocks) >= 1) {
            $sm = new StockManager();
            foreach ($this->stocks as $stock) {
                $sm->delete($stock);
            }
            $this->stocks = [];
        }

        if ($this->article) {
            $am = new ArticleManager();
            $am->delete($this->article);
            $this->article = null;
        }
    }

    /**
     * Test creating a web cart
     */
    public function testCreateWebCart()
    {
        $cm = new CartManager();

        $webCart = $cm->create(array('cart_type' => 'web'));

        $this->assertInstanceOf('Cart', $webCart);
        $this->assertEquals($webCart->get('type'), 'web');

        return $webCart;
    }

    /**
     * Test creating a shop checkout cart
     */
    public function testCreateShopCart()
    {
        $cm = new CartManager();

        $shopCart = $cm->create(array('cart_type' => 'shop'));

        $this->assertInstanceOf('Cart', $shopCart);
        $this->assertEquals($shopCart->get('type'), 'shop');

        return $shopCart;
    }

    /**
     * Test getting a cart
     * @depends testCreateWebCart
     */
    public function testGet(Cart $webCart)
    {
        $cm = new CartManager();

        $gotCart = $cm->getById($webCart->get('id'));

        $this->assertInstanceOf('Cart', $webCart);
        $this->assertEquals($webCart->get('id'), $gotCart->get('id'));

        return $webCart;
    }

    /**
     * Test adding a stock copy to a web cart
     * @depends testCreateWebCart
     * @returns the newly created stock that was added to the cart
     */
    public function testAddStockToWebCart(Cart $webCart)
    {
        $cm = new CartManager();
        $sm = new StockManager();
        $am = new ArticleManager();

        $webStock = EntityFactory::createStock([
            'stock_selling_price' => 500,
            'stock_weight' => 100
        ]);

        // Try adding it to cart & updating cart
        $cm->addStock($webCart, $webStock);

        // Reload stock from database
        $webStock = $sm->reload($webStock);

        $this->assertEquals($webStock->get('cart_id'), $webCart->get('id'));
        $this->assertTrue($webStock->has('cart_date'));

        return $webStock;
    }

    /**
     * Test build one line cart
     */
    public function testBuildOneLine()
    {
        $this->assertEquals(Cart::buildOneLine(0, 0), '<a
                href="/pages/cart"
                rel="nofollow"
                class="btn btn-default btn-sm empty"><span class="fa fa-shopping-cart"></span> Panier vide</a>');
        $this->assertEquals(Cart::buildOneLine(10, 10000), '<a
                href="/pages/cart"
                rel="nofollow"
                class="btn btn-default btn-sm not-empty"><span class="fa fa-shopping-cart"></span> 10 articles (100,00&nbsp;&euro;)</a>');
    }

    /**
     * Test adding a stock copy to a shop cart
     * @depends testCreateShopCart
     * @returns the newly created stock that was added to the cart
     */
    public function testAddStockToShopCart(Cart $shopCart)
    {
        $cm = new CartManager();
        $sm = new StockManager();
        $am = new ArticleManager();

        $shopStock = EntityFactory::createStock([
            'stock_selling_price' => 500,
            'stock_weight' => 100
        ]);

        // Try adding it to cart & updating cart
        $cm->addStock($shopCart, $shopStock);

        // Reload stock from database
        $shopStock = $sm->reload($shopStock);

        $this->assertEquals($shopStock->get('cart_id'), $shopCart->get('id'));
        $this->assertTrue($shopStock->has('cart_date'));

        return $shopStock;
    }

    /**
     * Test adding a stock that is already in a web cart (authorized)
     * @depends testCreateShopCart
     * @depends testAddStockToWebCart
     */
    public function testStealFromWebCart(Cart $shopCart, Stock $webStock)
    {
        $cm = new CartManager();

        $cm->addStock($shopCart, $webStock);

        $this->assertEquals($shopCart->get('id'), $webStock->get('cart_id'));
    }

    /**
     * Test adding a stock that is already in a shop cart (forbidden)
     * @depends testCreateWebCart
     * @depends testAddStockToShopCart
     */
    public function testStealFromShopCart(Cart $webCart, Stock $shopStock)
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Cet article est réservé en magasin.");

        $cm = new CartManager();

        $cm->addStock($webCart, $shopStock);
    }

    /**
     * An unavailable ebook shouldn't be added to cart even if stock available
     */
    public function testAddUnavailableEbook()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Cet article est indisponible.");

        $sm = new StockManager();
        $cm = new CartManager();

        $article = EntityFactory::createArticle(
            ["type_id" => 2, "article_availability_dilicom" => 6]
        );
        EntityFactory::createStock(["article_id" => $article->get("id")]);
        $cart = $cm->create();

        $cm->addArticle($cart, $article);
    }

    /**
     * Test testing if cart contains physical products and needs shipping
     */
    public function testNeedsShipping()
    {
        global $_SITE;

        $cm = new CartManager();
        $am = new ArticleManager();

        $not_virtual_stock = false;
        if (!$_SITE->getOpt('virtual_stock')) {
            $_SITE->setOpt('virtual_stock', 1);
            $not_virtual_stock = true;
        }

        $cart = $cm->create();
        $this->assertFalse(
            $cart->needsShipping(),
            "Empty cart don't need shipping"
        );

        $downloadable = EntityFactory::createArticle(["type_id" => 2]);
        $cm->addArticle($cart, $downloadable);
        $this->assertFalse(
            $cart->needsShipping(),
            "Carts with downloadable article don't need shipping"
        );

        $physical = EntityFactory::createArticle(["type_id" => 1]);
        $cm->addArticle($cart, $physical);
        $this->assertTrue($cart->needsShipping(), "Carts with physical article need shipping");

        if ($not_virtual_stock) {
            $_SITE->setOpt('virtual_stock', 0);
        }

        $cm->delete($cart);
    }

    /**
     * Test adding article from virtual stock
     */
    public function testAddArticle()
    {
        global $_SITE;

        $cm = new CartManager();
        $am = new ArticleManager();

        $_SITE->setOpt('virtual_stock', 1);

        $cart = $cm->create();
        $article = EntityFactory::createArticle(["article_availability_dilicom" => 1]);

        $cm->addArticle($cart, $article);

        $this->assertTrue($cart->containsArticle($article));

    }

    /**
     * Test adding article from virtual stock
     * @throws Exception
     */
    public function testAddArticleForUnreleaseArticle()
    {
        global $_SITE;

        // given
        $this->expectException("Entity\Exception\CartException");
        $this->expectExceptionMessage("L'article <a href=\"/\">L'Animalie</a> n'a pas pu être ajouté au panier car il n'est pas encore disponible.");

        // given
        $cm = new CartManager();
        $_SITE->setOpt('virtual_stock', 1);
        $cart = $cm->create();
        $tomorrow = new DateTime("tomorrow");
        $article = EntityFactory::createArticle([
            "article_pubdate" => $tomorrow->format("Y-m-d"),
            "article_availability_dilicom" => 1
        ]);

        // when
        $cm->addArticle($cart, $article);
    }

    /**
     * Test stealing a new copy added to another cart less than one hour ago
     */
    public function testAddArticleCooldown()
    {
        global $_SITE;

        $cm = new CartManager();
        $am = new ArticleManager();
        $sm = new StockManager();

        $not_virtual_stock = false;
        if (!$_SITE->getOpt('virtual_stock')) {
            $_SITE->setOpt('virtual_stock', 1);
            $not_virtual_stock = true;
        }

        $article = EntityFactory::createArticle([
            "article_availability_dilicom" => 1,
            "article_price" => 1000,
        ]);
        $stock = $sm->create([
            'article_id' => $article->get('id'),
            'stock_condition' => 'Neuf',
            'stock_selling_price' => $article->get('price'),
        ]);

        $firstCart = $cm->create();
        $secondCart = $cm->create();

        $cm->addStock($firstCart, $stock);
        $this->assertTrue(
            $firstCart->containsStock($stock),
            "Available copy should be added to cart"
        );

        $cm->addArticle($secondCart, $article);
        $this->assertFalse(
            $secondCart->containsStock($stock),
            "In-cart copy should not be added to another cart during cooldown period"
        );

        $twoHoursAgo = date('Y-m-d H:i:s', strtotime('2 hours ago'));
        $stock->set('stock_cart_date', $twoHoursAgo);
        $sm->update($stock);
        $cm->addArticle($secondCart, $article);
        $this->assertTrue(
            $secondCart->containsStock($stock),
            "In-cart copy should be added to another cart after cooldown period"
        );

        if ($not_virtual_stock) {
            $_SITE->setOpt('virtual_stock', 0);
        }

        $this->stocks = $article->getStock();
        $this->carts = [$firstCart, $secondCart];
        $this->article = $article;
    }

    /**
     * Test not adding article from virtual stock
     * when article is unavailable
     */
    public function testAddUnavailableArticle()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("L'article Plop n'a pas pu être ajouté au panier car il est hors commerce.");

        global $_SITE;

        $cm = new CartManager();
        $am = new ArticleManager();

        $not_virtual_stock = false;
        if (!$_SITE->getOpt('virtual_stock')) {
            $_SITE->setOpt('virtual_stock', 1);
            $not_virtual_stock = true;
        }

        $cart = $cm->create();
        $article = EntityFactory::createArticle(
            ["article_title" => "Plop", "article_availability_dilicom" => 10]
        );

        $cm->addArticle($cart, $article);

        if ($not_virtual_stock) {
            $_SITE->setOpt('virtual_stock', 0);
        }
    }

    /**
     * @throws Exception
     */
    public function testAddArticleToBeReprinted()
    {
        // then
        $this->expectException("Entity\Exception\CartException");
        $this->expectExceptionMessage("L'article À réimprimer n'a pas pu être ajouté au panier car il est en cours de réimpression.");

        // given
        global $_SITE;
        $_SITE->setOpt('virtual_stock', 1);
        $cm = new CartManager();
        $cart = $cm->create();
        $article = EntityFactory::createArticle([
            "article_title" => "À réimprimer",
            "article_availability_dilicom" => 03
        ]);

        // when
        $cm->addArticle($cart, $article);
    }

    /**
     * Test getting user info from cart
     */
    public function testGetUserInfo()
    {
        $cart = new Cart(['cart_ip' => '127.0.0.1']);

        $this->assertEquals($cart->getUserInfo(), '127.0.0.1');
    }

    public function testContainsStock()
    {
        // given
        $cm = new CartManager();
        $cart = $cm->create([]);
        $stock = EntityFactory::createStock();
        $cm->addStock($cart, $stock);

        // when / then
        $this->assertTrue(
            $cart->containsStock($stock),
            "it should return true if stock is in cart"
        );
    }

    public function testContainsArticle()
    {
        // given
        $cm = new CartManager();
        $cart = $cm->create([]);
        $article = EntityFactory::createArticle();
        $cm->addArticle($cart, $article);

        // when / then
        $this->assertTrue(
            $cart->containsArticle($article),
            "it should return true if article is in cart"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testContainsReward()
    {
        // given
        $GLOBALS["_SITE"] = EntityFactory::createSite();
        $reward = EntityFactory::createCrowdfundingReward([
            "site_id" => $GLOBALS["_SITE"]->get("id"),
            "limited" => 0,
        ]);
        $cart = EntityFactory::createCart();
        $cm = new CartManager();
        $cm->addCFReward($cart, $reward);

        // when / then
        $this->assertTrue(
            $cart->containsReward($reward),
            "it should return true if reward is in cart"
        );
    }

    /**
     * Test delete a cart
     * @throws Exception
     */
    public function testDelete()
    {
        // given
        $GLOBALS["_SITE"] = EntityFactory::createSite();
        $cm = new CartManager();
        $webCart = EntityFactory::createCart(["cart_type" => "web"]);
        $shopCart = EntityFactory::createCart(["cart_type" => "shop"]);

        // when
        $cm->delete($webCart);
        $cm->delete($shopCart);

        // then
        $isWebCart = $cm->getById($webCart->get('id'));
        $isShopCart = $cm->getById($shopCart->get('id'));
        $this->assertFalse($isWebCart);
        $this->assertFalse($isShopCart);
    }
}
