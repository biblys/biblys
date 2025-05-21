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

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Test\EntityFactory;
use Symfony\Component\HttpFoundation\Request;

require_once "setUp.php";

class OrderTest extends PHPUnit\Framework\TestCase
{
    // Set site TVA before tests
    public static function setUpBeforeClass(): void
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();
        $sm = new SiteManager();
        $globalSite->set('site_tva', 'fr');
        $sm->update($globalSite);
    }

    /**
     * Test creating an order
     */
    public function testCreate()
    {
        $om = new OrderManager();

        $order = $om->create(["order_email" => "customer@biblys.fr"]);

        $this->assertInstanceOf('Order', $order);

        return $order;
    }

    /**
     * Test getting an order
     * @depends testCreate
     */
    public function testGet(Order $order)
    {
        $om = new OrderManager();

        $gotOrder = $om->getById($order->get('id'));

        $this->assertInstanceOf('Order', $order);
        $this->assertEquals($order->get('id'), $gotOrder->get('id'));

        return $order;
    }

    /**
     * Test updating an order
     * @depends testGet
     */
    public function testUpdate(Order $order)
    {
        $om = new OrderManager();

        $order->set('order_lastname', 'COMMANDETOU');
        $om->update($order);

        $updatedOrder = $om->getById($order->get('id'));

        $this->assertTrue($updatedOrder->has('updated'));
        $this->assertEquals($updatedOrder->get('lastname'), 'COMMANDETOU');

        return $updatedOrder;
    }

    /**
     * Test if order is payed
     */
    public function testGetTotal()
    {
        $om = new OrderManager();
        $order = $om->create([
            "order_amount" => 1000,
            "order_shipping" => 247
        ]);

        $this->assertEquals($order->getTotal(), 1247);
    }

    /**
     * Test if order is payed
     * @depends testGet
     */
    public function testIsPayed(Order $order)
    {
        $om = new OrderManager();

        $order->set('order_amount_tobepaid', 1100);

        $this->assertFalse($order->isPayed());

        $order->set('order_amount_tobepaid', 0);
        $order->set('order_payment_date', '2016-02-15 00:10:00');

        $this->assertTrue($order->isPayed());
    }

    /**
     * Test if order has been shipped
     * @depends testGet
     */
    public function testIsShipped(Order $order)
    {
        $om = new OrderManager();

        $order->set('order_shipping_date', null);

        $this->assertFalse($order->isShipped());

        $order->set('order_shipping_date', '2016-02-15 00:10:00');

        $this->assertTrue($order->isShipped());
    }

    /**
     * Test adding stock
     * @depends testUpdate
     */
    public function testAddStock(Order $order)
    {
        // given
        $cm = new CountryManager();
        $om = new OrderManager();
        $am = new ArticleManager();
        $sm = new StockManager();
        $order->set('country_id', 65);
        $om->update($order);
        $book = EntityFactory::createArticle(["type_id" => 1, "article_price" => 1000]);
        $book = $sm->create(array('article_id' => $book->get('id'), 'stock_selling_price' => $book->get('price')));
        $om->addStock($order, $book);
        $book = $sm->reload($book);
        $ebook = EntityFactory::createArticle(["type_id" => 2, "article_price" => 500]);
        $ebook = $sm->create(array('article_id' => $ebook->get('id'), 'stock_selling_price' => $ebook->get('price')));

        // when
        $om->addStock($order, $ebook);
        $ebook = $sm->reload($ebook);

        // then
        $this->assertEquals($order->get('id'), $book->get('order')->get('id'));
        $this->assertNotNull($book->get('selling_date'));
        $this->assertEquals($order->get('id'), $ebook->get('order')->get('id'));
        $this->assertNotNull($ebook->get('selling_date'));
        $this->assertEquals(
            5.5,
            $ebook->get('tva_rate'),
            "Tax rate should be 5.5 for an ebook sold in France"
        );

        return $order;
    }

    /**
     * Test setting the customer
     * @depends testAddStock
     */
    public function testSetCustomer(Order $order)
    {
        $om = new OrderManager();
        $cm = new CustomerManager();

        // Create fake customer
        $customer = $cm->create();

        // Set order customer
        $om->setCustomer($order, $customer);

        $this->assertEquals($order->get('customer_id'), $customer->get('id'));

        return $order;
    }

    /**
     * Test adding payment (as a string)
     * @depends testSetCustomer
     */
    public function testAddPayment(Order $order)
    {
        $om = new OrderManager();

        $amount = $order->get('amount_tobepaid');

        // Adding payement
        $om->addPayment($order, "cash", $amount);
        $om->update($order);

        $this->assertEquals($order->get('amount_tobepaid'), 0);
        $this->assertEquals($order->get('payment_cash'), $amount);
        $this->assertNotNull($order->get('payment_date'));

        return $order;
    }

    /**
     * Test adding payment (as an object)
     * @depends testSetCustomer
     */
    public function testAddPaymentObject(Order $order)
    {
        $om = new OrderManager();
        $pm = new PaymentManager();

        $order = $om->create([
            "order_amount" => 1234,
            "order_amount_tobepaid" => 1234,
            "order_email" => "customer@biblys.fr",
        ]);
        $payment = $pm->create([
            "order_id" => $order->get("id"),
            "payment_amount" => 1234,
            "payment_mode" => "cash"
        ]);

        // Adding payement
        $om->addPayment($order, $payment);

        $this->assertEquals($order->get('amount_tobepaid'), 0);
        $this->assertEquals($order->get('payment_cash'), 1234);
        $this->assertNotNull($order->get('payment_date'));

        return $order;
    }

    /**
     * Test adding payment when order is already payed
     * @depends testAddPayment
     */
    public function testAddPaymentWhenPayed(Order $order)
    {
        $om = new OrderManager();

        $om->addPayment($order, "cash", "1000");
        $om->update($order);

        $this->assertEquals($order->get('amount_tobepaid'), 0);
        $this->assertEquals($order->get('payment_cash'), "1000");
        $this->assertNotNull($order->get('payment_date'));

        return $order;
    }

    /**
     * Test setting the customer to a payed order
     * @depends testAddStock
     */
    public function testSetCustomerPayed(Order $order)
    {
        $om = new OrderManager();
        $cm = new CustomerManager();
        $sm = new StockManager();

        // Create fake customer
        $customer = $cm->create();

        // Set order customer
        $om->setCustomer($order, $customer);

        // Check that order has correct customer
        $this->assertEquals($order->get('customer_id'), $customer->get('id'));

        // Check that every copy has the correct customer
        $copies = $sm->getAll(["order_id" => $order->get('id')]);
        foreach ($copies as $copy) {
            $this->assertEquals($copy->get('customer_id'), $customer->get('id'));
        }
    }

    /**
     * Test unsetting customer on a payed order
     * @depends testAddStock
     */
    public function testUnsetCustomer(Order $order)
    {
        $om = new OrderManager();
        $cm = new CustomerManager();
        $sm = new StockManager();

        // Set order customer
        $om->setCustomer($order, null);

        // Check that order has correct customer
        $this->assertNull($order->get('customer_id'));

        // Check that every copy has the correct customer
        $copies = $sm->getAll(["order_id" => $order->get('id')]);
        foreach ($copies as $copy) {
            $this->assertNull($copy->get('customer_id'));
        }
    }

    /**
     * Test getting stock from orders
     */
    public function testGetStock()
    {
        $om = new OrderManager();
        $sm = new StockManager();
        $order = $om->create();
        $copy = $sm->create(["stock_weight" => 1000]);
        $om->addStock($order, $copy);

        $orderCopies = $order->getCopies();
        $orderCopy = $orderCopies[0];

        $this->assertTrue(is_array($orderCopies));
        $this->assertInstanceOf('Stock', $orderCopy);
        $this->assertEquals($copy->get('id'), $orderCopy->get('id'));
    }

    /**
     * Test if article contains downloadable articles
     */
    public function testContainsDownloadable()
    {
        $am = new ArticleManager();
        $om = new OrderManager();
        $sm = new StockManager();

        $order = $om->create();
        $article = EntityFactory::createArticle(["type_id" => 2]);
        $copy = $sm->create([
            'article_id' => $article->get('id'),
            'stock_weight' => 1000
        ]);
        $om->addStock($order, $copy);

        $this->assertTrue($order->containsDownloadable());
    }

    /**
     * Set order's shipping fee
     */
    public function testSetShippingFee()
    {
        $fee = new Shipping([
            'shipping_id' => 45,
            'shipping_type' => 'colissimo',
            'shipping_fee' => 485
        ]);

        $order = new Order(['order_amount' => 5500]);

        $order->setShippingFee($fee);

        $this->assertEquals($order->get('shipping_id'), 45);
        $this->assertEquals($order->get('order_shipping'), 485);
        $this->assertEquals($order->get('order_shipping_mode'), 'colissimo');
        $this->assertEquals($order->get('order_amount_tobepaid'), 5500 + 485);
    }

    /**
     * Set a payed order's shipping fee
     */
    public function testSetShippingFeeForPayedOrder()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Impossible de modifier le mode d'expédition d'une commande qui a déjà été payée.");

        $order = new Order(['order_payment_date' => '2016-11-25 20:13:37']);

        $order->setShippingFee(new Shipping([]));
    }

    /**
     * Set a shipped order's shipping fee
     */
    public function testSetShippingFeeForShippedOrder()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Impossible de modifier le mode d'expédition d'une commande qui a déjà été expédiée.");

        $order = new Order(['order_shipping_date' => '2016-11-25 20:13:37']);

        $order->setShippingFee(new Shipping([]));
    }

    /**
     * Test getting order's total weight
     */
    public function testGetTotalWeight()
    {
        $om = new OrderManager();
        $sm = new StockManager();

        $order = $om->create([]);

        $item1 = $sm->create(['stock_weight' => 100]);
        $om->addStock($order, $item1);
        $item2 = $sm->create(['stock_weight' => 200]);
        $om->addStock($order, $item2);

        $this->assertEquals($order->getTotalWeight(), 300);

        $sm->delete($item1);
        $sm->delete($item2);
        $om->delete($order);
    }

    public function testCancel()
    {
        // given
        $om = new OrderManager();
        $order = $om->create([
            "order_email" => "customer@biblys.fr",
        ]);

        // when
        $om->cancel($order);

        // then
        $this->assertTrue(
            $order->has("order_cancel_date"),
            "it should have a cancel date"
        );
    }

    public function testCancelShopOrder()
    {
        // given
        $om = new OrderManager();
        $order = $om->create([
            "order_type" => "shop",
        ]);

        // when
        $om->cancel($order);

        // then
        $this->assertTrue(
            $order->has("order_cancel_date"),
            "it should have a cancel date"
        );
    }

    public function testGetCountryWithCountryName()
    {
        // given
        $order = new Order(["order_country" => "Togo"]);

        // when
        $country = $order->getCountryName();

        // then
        $this->assertEquals(
            "Togo",
            $country,
            "should return country when entered as a string"
        );
    }

    public function testGetCountryWithCountryId()
    {
        // given
        $cm = new CountryManager();
        $country = $cm->create(["country_name" => "Bénin"]);
        $order = new Order(["country_id" => $country->get("id")]);

        // when
        $country = $order->getCountryName();

        // then
        $this->assertEquals(
            "Bénin",
            $country,
            "should return country when entered as an id"
        );
    }

    /**
     * @throws Exception
     */
    public function testRemoveStock()
    {
        // given
        $stock = EntityFactory::createStock();
        $order = EntityFactory::createOrder();
        $om = new OrderManager();
        $om->addStock($order, $stock);

        // when
        $om->removeStock($order, $stock);

        // then
        $copies = $order->getCopies();
        $this->assertCount(0, $copies);
    }

    /**
     * @throws Exception
     */
    public function testRemoveStockAssociatedWithReward()
    {
        // given
        $GLOBALS["LEGACY_CURRENT_SITE"] = EntityFactory::createSite();
        $reward = EntityFactory::createCrowdfundingReward(["site_id" => $GLOBALS["LEGACY_CURRENT_SITE"]->get("id")]);
        $stock = EntityFactory::createStock(["reward_id" => $reward->get("id")]);
        $order = EntityFactory::createOrder();
        $om = new OrderManager();
        $om->addStock($order, $stock);

        // when
        $om->removeStock($order, $stock);

        // then
        $copies = $order->getCopies();
        $this->assertCount(0, $copies);
    }

    /**
     * Test deleting an order
     * @depends testGet
     */
    public function testDelete(Order $order)
    {
        $om = new OrderManager();

        $om->delete($order, 'Test entity');

        $Orderxists = $om->getById($order->get('id'));

        $this->assertFalse($Orderxists);
    }

    /**
     * @throws Exception
     */
    public function testUpdateFromStock()
    {
        // given
        $om = new OrderManager();
        $order = EntityFactory::createOrder(shippingId: 247);
        $stock = EntityFactory::createStock(["stock_selling_price" => 1000]);
        $om->addStock($order, $stock);

        // when
        $om->updateFromStock($order);

        // then
        $updatedOrder = $om->getById($order->get("id"));
        $this->assertEquals(
            1000,
            $updatedOrder->get("order_amount"),
            "updates order's amount"
        );
        $this->assertEquals(
            1247,
            $updatedOrder->get("order_amount_tobepaid"),
            "updates order's amount to be paid including shipping fee"
        );
    }

    /**
     * @throws Exception
     */
    public function testUpdateFromStockIfOrderIsPayed()
    {
        // given
        $om = new OrderManager();
        $stock = EntityFactory::createStock(["stock_selling_price" => 1000]);
        $order = EntityFactory::createOrder(shippingId: 247, paymentDate: new DateTime("2016-02-15 00:10:00"));
        $om->addStock($order, $stock);

        // when
        $om->updateFromStock($order);

        // then
        $updatedOrder = $om->getById($order->get("id"));
        $this->assertEquals(
            1000,
            $updatedOrder->get("order_amount"),
            "updates order's amount"
        );
        $this->assertEquals(
            0,
            $updatedOrder->get("order_amount_tobepaid"),
            "does not updates order's amount to be paid"
        );
    }
}
