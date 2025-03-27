<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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


namespace Biblys\Service;

use Biblys\Exception\CannotFindPayableOrderException;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Mockery;
use Model\OrderQuery;
use Model\Payment;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Stripe\Checkout\Session;
use Stripe\Service\Checkout\CheckoutServiceFactory;
use Stripe\Service\Checkout\SessionService;
use Stripe\Service\PriceService;
use Stripe\Service\ProductService;
use Stripe\StripeClient;
use Symfony\Component\Routing\Generator\UrlGenerator;

class PaymentServiceTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        OrderQuery::create()->deleteAll();
    }

    /** getPayableOrderBySlug */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetPayableOrderBySlugWithUnknownSlug()
    {
        // given
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);
        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService);
        ModelFactory::createOrder();

        // when
        $exception = Helpers::runAndCatchException(
            fn() => $paymentService->getPayableOrderBySlug("unknown-slug")
        );

        // then
        $this->assertInstanceOf(CannotFindPayableOrderException::class, $exception);
        $this->assertEquals("Commande inconnue", $exception->getMessage());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetPayableOrderBySlugWithAlreadyPaidOrder()
    {
        // given
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);
        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService);
        $order = ModelFactory::createOrder(paymentDate: new DateTime());

        // when
        $exception = Helpers::runAndCatchException(
            fn() => $paymentService->getPayableOrderBySlug($order->getSlug())
        );

        // then
        $this->assertInstanceOf(CannotFindPayableOrderException::class, $exception);
        $this->assertEquals("Commande déjà payée", $exception->getMessage());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetPayableOrderBySlugWithCancelledOrder()
    {
        // given
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);
        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService);
        $order = ModelFactory::createOrder(cancelDate: new DateTime());

        // when
        $exception = Helpers::runAndCatchException(
            fn() => $paymentService->getPayableOrderBySlug($order->getSlug())
        );

        // then
        $this->assertInstanceOf(CannotFindPayableOrderException::class, $exception);
        $this->assertEquals("Commande annulée", $exception->getMessage());
    }

    /**
     * @throws PropelException
     * @throws CannotFindPayableOrderException
     */
    public function testGetPayableOrderBySlug()
    {
        // given
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);
        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService);
        $order = ModelFactory::createOrder();

        // when
        $returnedOrder = $paymentService->getPayableOrderBySlug($order->getSlug());

        // then
        $this->assertEquals($order, $returnedOrder);
    }

    /** createStripePaymentForOrder */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCreateStripePaymentForOrderWithoutStripeClient()
    {
        // given
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);
        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService);
        $order = ModelFactory::createOrder();

        // when
        $exception = Helpers::runAndCatchException(fn() => $paymentService->createStripePaymentForOrder($order));

        // then
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertEquals("Stripe n’est pas configuré.", $exception->getMessage());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCreateStripePaymentForOrderForUnknownProduct()
    {
        // given
        $config = new Config();
        $order = ModelFactory::createOrder(amountToBePaid: 999);
        $article = ModelFactory::createArticle(title: "Stripe Article");
        ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 999);

        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);

        $stripe = Mockery::mock(StripeClient::class);
        $productService = Mockery::mock(ProductService::class);
        $priceService = Mockery::mock(PriceService::class);
        $sessionService = Mockery::mock(SessionService::class);

        $productService->expects("search")
            ->with(["query" => "active:'true' AND metadata['article_id']:'{$article->getId()}'"])
            ->andReturn((object)["data" => []]);
        $productService->expects("create")->with([
            "name" => "Stripe Article",
            "metadata" => ["article_id" => $article->getId()],
            "default_price_data" => [
                "unit_amount" => 999,
                "currency" => "EUR",
            ]
        ])->andReturn((object) ["id" => "prod_5678", "default_price" => "price_1234"]);
        $sessionService->expects("create")->with([
            "payment_method_types" => ["card"],
            "line_items" => [
                [
                    "price" => "price_1234",
                    "quantity" => 1,
                ],
            ],
            "mode" => "payment",
            "success_url" => "https://www.biblys.fr/order/order-slug?payed=1",
            "cancel_url" => "https://www.biblys.fr/payment/order-slug",
            "customer_email" => "silas.coade@example.net",
        ])->andReturn((object)["id" => "cs_1234"]);
        $checkoutServiceFactory = Mockery::mock(CheckoutServiceFactory::class);
        $checkoutServiceFactory->sessions = $sessionService;
        $stripe->expects("getService")->with("products")->andReturn($productService);
        $stripe->expects("getService")->with("prices")->andReturn($priceService);
        $stripe->expects("getService")->with("checkout")->andReturn($checkoutServiceFactory);

        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService, $stripe);

        // when
        $payment = $paymentService->createStripePaymentForOrder($order);

        // then
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals($order, $payment->getOrder());
        $this->assertEquals("stripe", $payment->getMode());
        $this->assertEquals(999, $payment->getAmount());
        $this->assertEquals("cs_1234", $payment->getProviderId());
        $this->assertNull($payment->getExecuted());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCreateStripePaymentForOrderForExistingProduct()
    {
        // given
        $config = new Config();
        $order = ModelFactory::createOrder(amountToBePaid: 999);
        $article = ModelFactory::createArticle(title: "Stripe Article");
        ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 999);

        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);

        $stripe = Mockery::mock(StripeClient::class);
        $productService = Mockery::mock(ProductService::class);
        $priceService = Mockery::mock(PriceService::class);
        $sessionService = Mockery::mock(SessionService::class);

        $productService->expects("search")
            ->with(["query" => "active:'true' AND metadata['article_id']:'{$article->getId()}'"])
            ->andReturn((object)["data" => [(object)["id" => "prod_1234", "default_price" => "price_1234"]]]);
        $priceService->expects("retrieve")->with("price_1234")->andReturn((object)[
            "id" => "price_1234",
            "unit_amount" => 999,
        ]);
        $productService->expects("update")->with(
            "prod_1234",
            ["name" => "Stripe Article", "default_price" => "price_1234"]
        )->andReturn((object)["id" => "prod_1234", "default_price" => "price_1234"]);
        $sessionService->expects("create")->with([
            "payment_method_types" => ["card"],
            "line_items" => [
                [
                    "price" => "price_1234",
                    "quantity" => 1,
                ],
            ],
            "mode" => "payment",
            "success_url" => "https://www.biblys.fr/order/order-slug?payed=1",
            "cancel_url" => "https://www.biblys.fr/payment/order-slug",
            "customer_email" => "silas.coade@example.net",
        ])->andReturn((object)["id" => "cs_1234"]);
        $checkoutServiceFactory = Mockery::mock(CheckoutServiceFactory::class);
        $checkoutServiceFactory->sessions = $sessionService;
        $stripe->expects("getService")->with("products")->andReturn($productService);
        $stripe->expects("getService")->with("prices")->andReturn($priceService);
        $stripe->expects("getService")->with("checkout")->andReturn($checkoutServiceFactory);

        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService, $stripe);

        // when
        $payment = $paymentService->createStripePaymentForOrder($order);

        // then
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals($order, $payment->getOrder());
        $this->assertEquals("stripe", $payment->getMode());
        $this->assertEquals(999, $payment->getAmount());
        $this->assertEquals("cs_1234", $payment->getProviderId());
        $this->assertNull($payment->getExecuted());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCreateStripePaymentForOrderForExistingProductWithUpdatedPrice()
    {
        // given
        $config = new Config();
        $order = ModelFactory::createOrder(amountToBePaid: 999);
        $article = ModelFactory::createArticle(title: "Stripe Article");
        ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 999);

        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);

        $stripe = Mockery::mock(StripeClient::class);
        $productService = Mockery::mock(ProductService::class);
        $priceService = Mockery::mock(PriceService::class);
        $sessionService = Mockery::mock(SessionService::class);

        $productService->expects("search")
            ->with(["query" => "active:'true' AND metadata['article_id']:'{$article->getId()}'"])
            ->andReturn((object)["data" => [(object)["id" => "prod_1234", "default_price" => "price_1234"]]]);
        $priceService->expects("retrieve")->with("price_1234")->andReturn((object)[
            "id" => "price_1234",
            "unit_amount" => 899,
        ]);
        $priceService->expects("create")->with([
            "product" => "prod_1234",
            "unit_amount" => 999,
            "currency" => "EUR",
        ])->andReturn((object)["id" => "price_2345"]);
        $productService->expects("update")->with(
            "prod_1234",
            ["name" => "Stripe Article", "default_price" => "price_2345"]
        )->andReturn((object)["id" => "prod_1234", "default_price" => "price_1234"]);
        $sessionService->expects("create")->with([
            "payment_method_types" => ["card"],
            "line_items" => [
                [
                    "price" => "price_1234",
                    "quantity" => 1,
                ],
            ],
            "mode" => "payment",
            "success_url" => "https://www.biblys.fr/order/order-slug?payed=1",
            "cancel_url" => "https://www.biblys.fr/payment/order-slug",
            "customer_email" => "silas.coade@example.net",
        ])->andReturn((object)["id" => "cs_1234"]);
        $checkoutServiceFactory = Mockery::mock(CheckoutServiceFactory::class);
        $checkoutServiceFactory->sessions = $sessionService;
        $stripe->expects("getService")->with("products")->andReturn($productService);
        $stripe->expects("getService")->with("prices")->andReturn($priceService);
        $stripe->expects("getService")->with("checkout")->andReturn($checkoutServiceFactory);

        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService, $stripe);

        // when
        $payment = $paymentService->createStripePaymentForOrder($order);

        // then
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals($order, $payment->getOrder());
        $this->assertEquals("stripe", $payment->getMode());
        $this->assertEquals(999, $payment->getAmount());
        $this->assertEquals("cs_1234", $payment->getProviderId());
        $this->assertNull($payment->getExecuted());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCreateStripePaymentForOrderWithShipping()
    {
        // given
        $config = new Config();
        $order = ModelFactory::createOrder(amountToBePaid: 1498, shippingCost: 499);
        $article = ModelFactory::createArticle(title: "Stripe Article");
        ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 999);

        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);

        $stripe = Mockery::mock(StripeClient::class);
        $productService = Mockery::mock(ProductService::class);
        $priceService = Mockery::mock(PriceService::class);
        $sessionService = Mockery::mock(SessionService::class);

        $productService->expects("search")
            ->andReturn((object)["data" => [(object)["id" => "prod_1234", "default_price" => "price_1234"]]]);
        $priceService->expects("retrieve")->with("price_1234")->andReturn((object)[
            "id" => "price_1234",
            "unit_amount" => 999,
        ]);
        $productService->expects("update")
            ->andReturn((object)["id" => "prod_1234", "default_price" => "price_1234"]);
        $productService->expects("create")->with(["name" => "Frais de port"])
            ->andReturn((object)["id" => "prod_9123"]);
        $priceService->expects("create")->with([
            "product" => "prod_9123",
            "unit_amount" => 499,
            "currency" => "EUR",
        ])->andReturn((object)["id" => "price_5678"]);
        $sessionService->expects("create")->with([
            "payment_method_types" => ["card"],
            "line_items" => [
                [
                    "price" => "price_1234",
                    "quantity" => 1,
                ], [
                    "price" => "price_5678",
                    "quantity" => 1,
                ],
            ],
            "mode" => "payment",
            "success_url" => "https://www.biblys.fr/order/order-slug?payed=1",
            "cancel_url" => "https://www.biblys.fr/payment/order-slug",
            "customer_email" => "silas.coade@example.net",
        ])->andReturn((object)["id" => "cs_1234"]);
        $checkoutServiceFactory = Mockery::mock(CheckoutServiceFactory::class);
        $checkoutServiceFactory->sessions = $sessionService;
        $stripe->expects("getService")->with("products")->andReturn($productService);
        $stripe->expects("getService")->with("prices")->andReturn($priceService);
        $stripe->expects("getService")->with("checkout")->andReturn($checkoutServiceFactory);

        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService, $stripe);

        // when
        $payment = $paymentService->createStripePaymentForOrder($order);

        // then
        $this->assertInstanceOf(Payment::class, $payment);
    }

}
