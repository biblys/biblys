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
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\SearchResult;
use Stripe\Service\CustomerService;
use Stripe\Service\PaymentIntentService;
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
    public function testCreateStripePaymentForOrder()
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
        $productService->expects("create")->with(["name" => "Stripe Article"])->andReturn((object)["id" => 5678]);
        $priceService = Mockery::mock(PriceService::class);
        $priceService->expects("create")->with([
            "product" => 5678,
            "unit_amount" => 999,
            "currency" => "EUR",
        ])->andReturn((object)["id" => "6789"]);
        $session = Mockery::mock(Session::class);
        $session->expects("offsetGet")->with("id")->andReturn(1234);
        $sessionService = Mockery::mock(SessionService::class);
        $sessionService->expects("create")->with([
            "payment_method_types" => ["card"],
            "line_items" => [
                [
                    "price" => "6789",
                    "quantity" => 1,
                ],
            ],
            "mode" => "payment",
            "success_url" => "https://www.biblys.fr/order/order-slug?payed=1",
            "cancel_url" => "https://www.biblys.fr/payment/order-slug",
            "customer_email" => "silas.coade@example.net",
        ])->andReturn($session);
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
        $this->assertEquals(1234, $payment->getProviderId());
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
        $productService->expects("create")->with([
            "name" => "Stripe Article",
        ])->andReturn((object)["id" => 5678]);
        $productService->expects("create")->with(["name" => "Frais de port"])->andReturn((object)["id" => 9123]);
        $priceService = Mockery::mock(PriceService::class);
        $priceService->expects("create")->with([
            "product" => 5678,
            "unit_amount" => 999,
            "currency" => "EUR",
        ])->andReturn((object)["id" => "6789"]);
        $priceService->expects("create")->with([
            "product" => 9123,
            "unit_amount" => 499,
            "currency" => "EUR",
        ])->andReturn((object)["id" => "3456"]);
        $session = Mockery::mock(Session::class);
        $session->expects("offsetGet")->with("id")->andReturn(1234);
        $sessionService = Mockery::mock(SessionService::class);
        $sessionService->expects("create")->with([
            "payment_method_types" => ["card"],
            "line_items" => [
                [
                    "price" => "6789",
                    "quantity" => 1,
                ], [
                    "price" => "3456",
                    "quantity" => 1,
                ],
            ],
            "mode" => "payment",
            "success_url" => "https://www.biblys.fr/order/order-slug?payed=1",
            "cancel_url" => "https://www.biblys.fr/payment/order-slug",
            "customer_email" => "silas.coade@example.net",
        ])->andReturn($session);
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

    /** getOrCreateStripeCustomer */

    /**
     * @throws Exception
     */
    public function testGetOrCreateStripeCustomerForExistingCustomer(): void
    {
        // given
        $customer = ModelFactory::createCustomer();
        $order = ModelFactory::createOrder(customer: $customer);

        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);

        $expectedSearchQuery = "metadata['customer_id']:'{$customer->getId()}'";
        $returnedSearchResults = [new SearchResult("cus_1234")];
        $stripeClient = $this->_mockStripeClient($expectedSearchQuery, $returnedSearchResults);

        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService, $stripeClient);

        // when
        $customer = $paymentService->getOrCreateStripeCustomerForOrder($order);

        // then
        $this->assertInstanceof(Customer::class, $customer);
        $this->assertEquals("cus_1234", $customer->id);
    }

    /**
     * @throws Exception
     */
    public function testGetOrCreateStripeCustomerForNewCustomer(): void
    {
        // given
        $customer = ModelFactory::createCustomer();
        $order = ModelFactory::createOrder(customer: $customer);

        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);

        $expectedSearchQuery = "metadata['customer_id']:'{$customer->getId()}'";
        $returnedSearchResults = [];
        $stripeClient = $this->_mockStripeClient(
            $expectedSearchQuery, $returnedSearchResults, expectedCustomerIdForCreation: $order->getCustomerId()
        );


        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService, $stripeClient);

        // when
        $customer = $paymentService->getOrCreateStripeCustomerForOrder($order);

        // then
        $this->assertInstanceof(Customer::class, $customer);
        $this->assertEquals("cus_1234", $customer->id);
    }

    /** createStripePaymentIntent */

    /**
     * @throws InvalidConfigurationException
     * @throws PropelException
     */
    public function testCreatePaymentIntent(): void
    {
        // given
        $order = ModelFactory::createOrder(amountToBePaid: 999);
        $stripeCustomer = new Customer("cus_1234");

        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);

        $stripeClient = Mockery::mock(StripeClient::class);
        $paymentIntentService = Mockery::mock(PaymentIntentService::class);
        $paymentIntentService->expects("create")->with([
            "amount" => 999,
            "customer"=> "cus_1234",
            "currency" => "eur",
            "payment_method_types" => ["card"],
            "metadata" => [
                "order_id" => $order->getId(),
            ],
        ])->andReturn(new PaymentIntent("pi_1234"));
        $stripeClient->expects("getService")->with("paymentIntents")->andReturn($paymentIntentService);

        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService, $stripeClient);

        // when
        $paymentIntent = $paymentService->createStripePaymentIntentForOrder($order, $stripeCustomer);

        // then
        $this->assertInstanceof(PaymentIntent::class, $paymentIntent);
    }

    private function _mockStripeClient(
        string $expectedSearchQuery,
        array  $returnedSearchResults,
        ?int   $expectedCustomerIdForCreation = null
    ): StripeClient
    {
        $stripeClient = Mockery::mock(StripeClient::class);

        $customerService = Mockery::mock(CustomerService::class);
        $customerService->expects("search")->with(["query" => $expectedSearchQuery])
            ->andReturn((object)["data" => $returnedSearchResults]);
        $customerService->expects("retrieve")->with("cus_1234")->andReturn(new Customer("cus_1234"));

        if ($expectedCustomerIdForCreation) {
            $customerService->expects("create")->with(["metadata" => ["customer_id" => $expectedCustomerIdForCreation]])
                ->andReturn(new Customer("cus_1234"));
        }

        $stripeClient->expects("getService")->with("customers")->andReturn($customerService);

        return $stripeClient;
    }

}
