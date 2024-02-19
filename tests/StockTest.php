<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Test\EntityFactory;

require_once __DIR__."/setUp.php";

class StockTest extends PHPUnit\Framework\TestCase
{
    // Set site TVA before tests
    public static function setUpBeforeClass(): void
    {
        $_SITE = LegacyCodeHelper::getGlobalSite();
        $sm = new SiteManager();
        $_SITE->set('site_tva', 'fr');
        $sm->update($_SITE);
    }

    /**
     * Test creating a copy.
     */
    public function testCreate()
    {
        $sm = new StockManager();
        $am = new ArticleManager();

        $article = EntityFactory::createArticle(["type_id" => 1]);

        $stock = $sm->create(['article_id' => $article->get('id')]);

        $this->assertInstanceOf('Stock', $stock);

        return $stock;
    }

    /**
     * Test getting a copy.
     *
     * @depends testCreate
     */
    public function testGet(Stock $stock)
    {
        $sm = new StockManager();

        $gotStock = $sm->getById($stock->get('id'));

        $this->assertInstanceOf('Stock', $stock);
        $this->assertEquals($stock->get('id'), $gotStock->get('id'));

        return $stock;
    }

    /**
     * Test updating a copy.
     *
     * @depends testGet
     */
    public function testUpdate(Stock $stock)
    {
        $sm = new StockManager();

        $now = date('Y-m-d H:i:s');

        $stock->set('stock_selling_price', 1000);
        $stock->set('stock_selling_date', $now);
        $sm->update($stock);

        $updatedStock = $sm->getById($stock->get('id'));

        $this->assertTrue($updatedStock->has('updated'));
        $this->assertEquals($updatedStock->get('selling_price'), 1000);
        $this->assertEquals($updatedStock->get('selling_date'), $now);

        return $updatedStock;
    }

    /**
     * Test getting tax rate.
     *
     * @depends testUpdate
     */
    public function testGetTaxRate(Stock $stock)
    {
        $sm = new StockManager();

        $rate = $sm->getTaxRate($stock);

        $this->assertEquals($rate, 5.5);
    }

    /**
     * Test getting price without VAT.
     *
     * @depends testUpdate
     */
    public function testCalculateTax(Stock $stock)
    {
        $sm = new StockManager();

        $stock = $sm->calculateTax($stock);

        $this->assertEquals(948, $stock->get('selling_price_ht'));
        $this->assertEquals(52, $stock->get('selling_price_tva'));
    }

    /**
     * Test if copy has a selling date.
     */
    public function testIsSold()
    {
        $sm = new StockManager();
        $stock = $sm->create();

        $this->assertFalse($stock->isSold(), 'By default, copy should not be sold.');

        $stock->set('stock_selling_date', date('Y-d-m H:i:s'));

        $this->assertTrue($stock->isSold(), 'Copy with a selling date should be sold.');
    }

    /**
     * Test getting availability.
     *
     * @depends testCreate
     */
    public function testIsAvailable(Stock $stock)
    {
        $am = new ArticleManager();
        $sm = new StockManager();
        $cm = new CartManager();
        $now = date('Y-m-d H:i:s');

        // By default, it should be available
        $stock->set('stock_selling_date', null);
        $this->assertTrue($stock->isAvailable());

        // Sold copy should not be available
        $stock->set('stock_selling_date', $now);
        $this->assertFalse($stock->isAvailable());
        $stock->set('stock_selling_date', null);

        // Returned copy should not be available
        $stock->set('stock_return_date', $now);
        $this->assertFalse($stock->isAvailable());
        $stock->set('stock_return_date', null);

        // Lost copy should not be available
        $stock->set('stock_lost_date', $now);
        $this->assertFalse($stock->isAvailable());
        $stock->set('stock_lost_date', null);
    }

    /**
     * Test if an article is in a shop cart.
     */
    public function testIsReserved()
    {
        $cm = new CartManager();
        $sm = new StockManager();

        $stock = $sm->create();
        $shopCart = $cm->create(['cart_type' => 'shop']);
        $webCart = $cm->create(['cart_type' => 'web']);

        $this->assertFalse($stock->isReserved());

        $stock->set('cart_id', $webCart->get('id'));
        $stock->set('stock_cart_date', date('Y-m-d H:i:s'));
        $this->assertFalse($stock->isReserved());

        $stock->set('cart_id', $shopCart->get('id'));
        $stock->set('stock_cart_date', date('Y-m-d H:i:s'));
        $this->assertTrue($stock->isReserved());
    }

    /**
     * Test settings copy as returned.
     */
    public function testSetReturned()
    {
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $cm = new CartManager();
        $sm = new StockManager();
        $cart = $cm->create();
        $stock = $sm->create(['stock_weight' => 1000]);

        // Set the copy in a cart
        $cm->addStock($cart, $stock);

        // Set returned
        $stock->setReturned($now);

        $this->assertEquals($stock->get('return_date'), $now);
        $this->assertEquals($stock->get('cart_id'), null);
        $this->assertEquals($stock->get('cart_date'), null);
    }

    /**
     * Test getting cart button.
     */
    public function testGetCartButton()
    {
        $sm = new StockManager();
        $item = $sm->create();

        $button = $item->getCartButton();
        $this->assertEquals($button, '
            <a class="btn btn-primary btn-sm add_to_cart event"
                data-type="stock" data-id="'.$item->get('id').'">
                <span class="fa fa-shopping-cart"></span>
            </a>
        ');

        $buttonWithText = $item->getCartButton('Ajouter au panier');
        $this->assertEquals($buttonWithText, '
            <a class="btn btn-primary add_to_cart event"
                data-type="stock" data-id="'.$item->get('id').'">
                <span class="fa fa-shopping-cart"></span> Ajouter au panier
            </a>
        ');
    }

    /**
     * Test getting difference between selling and purchase price.
     *
     * @var [type]
     */
    public function testGetDiscount()
    {
        $stock = new Stock([
            'stock_selling_price' => 970,
            'stock_purchase_price' => 585,
        ]);

        $this->assertEquals($stock->getDiscountRate(), 28);
    }

    /**
     * Test deleting a copy.
     *
     * @depends testGet
     */
    public function testDelete(Stock $stock)
    {
        $sm = new StockManager();

        $sm->delete($stock, 'Test entity');

        $stockExists = $sm->getById($stock->get('id'));

        $this->assertFalse($stockExists);
    }

    /**
     * Test restoring a saved price.
     */
    public function testRestoreSavedPrice()
    {
        $stock = new Stock(
            [
                'stock_selling_price' => 100,
                'stock_selling_price_saved' => 200,
            ]
        );
        $stock->restoreSavedPrice();

        $this->assertEquals(
            200,
            $stock->get('selling_price'),
            'Selling price should be restored'
        );
        $this->assertEquals(
            null,
            $stock->get('selling_price_saved'),
            'Saved price should be nullified'
        );

        $stock->restoreSavedPrice();

        $this->assertEquals(
            200,
            $stock->get('selling_price'),
            'Should not restore a null saved price'
        );
    }

    public function testEditFreePrice()
    {
        // given
        $article = EntityFactory::createArticle([
            "article_price_editable" => 1,
            "article_price" => 100,
        ]);
        $stock = EntityFactory::createStock([
            "article_id" => $article->get("id"),
            "stock_selling_price" => 100,
        ]);
        $stock->set("stock_selling_price", 100);

        // when
        $stock->editFreePrice(200);

        // then
        $this->assertEquals(
            200,
            $stock->get("stock_selling_price"),
            "it should have updated price"
        );
    }

    public function testEditNonFreePrice()
    {
        // then
        $this->expectException("Exception");
        $this->expectExceptionMessage("Le prix de cet article n'est pas libre.");

        // given
        $stock = EntityFactory::createStock();
        $article = $stock->getArticle();
        $article->set("article_price_editable", 0);

        // when
        $stock->editFreePrice(200);
    }

    public function testEditFreePriceToInvalidPrice()
    {
        // then
        $this->expectException("Exception");
        $this->expectExceptionMessage("Le prix doit être supérieur à 5,00&nbsp;&euro;");

        // given
        $article = EntityFactory::createArticle([
            "article_price" => 500,
            "article_price_editable" => 1,
        ]);
        $stock = EntityFactory::createStock(["article_id" => $article->get("id")]);
        $article->set("article_price_editable", 1);

        // when
        $stock->editFreePrice(400);
    }
}
