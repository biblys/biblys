<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use Biblys\Test\Factory;

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

        // Create a fake stock copy
        $article = $am->create(['type_id' => 1]);
        $webStock = $sm->create(array(
            'article_id' => $article->get('id'),
            'stock_selling_price' => 500,
            'stock_weight' => 100
        ));

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

        // Create a fake stock copy
        $article = $am->create(['type_id' => 1]);
        $shopStock = $sm->create(array(
            'article_id' => $article->get('id'),
            'stock_selling_price' => 500,
            'stock_weight' => 100
        ));

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

        $am = new ArticleManager();
        $sm = new StockManager();
        $cm = new CartManager();

        $article = $am->create(['type_id' => 2, 'article_availability_dilicom' => 6]);
        $stock = $sm->create(['article_id' => $article->get('id')]);
        $cart = $cm->create();

        $cm->addArticle($cart, $article);
    }

    /**
     * Test testing if cart contains physical products and needs shipping
     */
    public function testNeedsShipping()
    {
        global $site;

        $cm = new CartManager();
        $am = new ArticleManager();

        $not_virtual_stock = false;
        if (!$site->getOpt('virtual_stock')) {
            $site->setOpt('virtual_stock', 1);
            $not_virtual_stock = true;
        }

        $cart = $cm->create();
        $this->assertFalse($cart->needsShipping(), "Empty cart don't need shipping");

        $downloadable = $am->create(["type_id" => 2]);
        $added = $cm->addArticle($cart, $downloadable);
        $this->assertFalse($cart->needsShipping(), "Carts with downloadable article don't need shipping");

        $physical = $am->create(["type_id" => 1]);
        $cm->addArticle($cart, $physical);
        $this->assertTrue($cart->needsShipping(), "Carts with physical article need shipping");

        if ($not_virtual_stock) {
            $site->setOpt('virtual_stock', 0);
        }

        $cm->delete($cart);
    }

    /**
     * Test adding article from virtual stock
     */
    public function testAddArticle()
    {
        global $site;

        $cm = new CartManager();
        $am = new ArticleManager();

        $not_virtual_stock = false;
        if (!$site->getOpt('virtual_stock')) {
            $site->setOpt('virtual_stock', 1);
            $not_virtual_stock = true;
        }

        $cart = $cm->create();
        $article = $am->create(["article_availability_dilicom" => 1]);

        $cm->addArticle($cart, $article);

        $this->assertTrue($cart->contains("article", $article->get("id")));

        if ($not_virtual_stock) {
            $site->setOpt('virtual_stock', 0);
        }
    }

    /**
     * Test stealing a new copy added to another cart less than one hour ago
     */
    public function testAddArticleCooldown()
    {
        global $site;

        $cm = new CartManager();
        $am = new ArticleManager();
        $sm = new StockManager();

        $not_virtual_stock = false;
        if (!$site->getOpt('virtual_stock')) {
            $site->setOpt('virtual_stock', 1);
            $not_virtual_stock = true;
        }

        $article = $am->create([
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
            $site->setOpt('virtual_stock', 0);
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

        global $site;

        $cm = new CartManager();
        $am = new ArticleManager();

        $not_virtual_stock = false;
        if (!$site->getOpt('virtual_stock')) {
            $site->setOpt('virtual_stock', 1);
            $not_virtual_stock = true;
        }

        $cart = $cm->create();
        $article = $am->create(
            ["article_title" => "Plop", "article_availability_dilicom" => 10]
        );

        $cm->addArticle($cart, $article);

        if ($not_virtual_stock) {
            $site->setOpt('virtual_stock', 0);
        }
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
        $stock = Factory::createStock();
        $cm->addStock($cart, $stock);

        // when / then
        $this->assertTrue(
            $cart->containsStock($stock),
            "it should return true if reward is in cart"
        );
    }

    public function testContainsReward()
    {
        // given
        $cm = new CartManager();
        $cart = $cm->create([]);
        $reward = Factory::createCrowfundingReward();
        $cm->addCFReward($cart, $reward);

        // when / then
        $this->assertTrue(
            $cart->containsReward($reward),
            "it should return true if reward is in cart"
        );
    }

    /**
     * Test delete a cart
     * @depends testCreateWebCart
     * @depends testCreateShopCart
     */
    public function testDelete(Cart $webCart, Cart $shopCart)
    {
        $cm = new CartManager();

        $cm->delete($webCart, 'Test entity');
        $cm->delete($shopCart, 'Test entity');

        $isWebCart = $cm->getById($webCart->get('id'));
        $isShopCart = $cm->getById($shopCart->get('id'));

        $this->assertFalse($isWebCart);
        $this->assertFalse($isShopCart);
    }
}
