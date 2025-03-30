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
use Model\Order;
use Model\OrderQuery;
use Model\Payment;
use Model\PaymentQuery;
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
    public function testCreateStripePaymentForOrderWithExistingCustomer()
    {
        // given
        $customer = ModelFactory::createCustomer();
        $order = ModelFactory::createOrder(customer: $customer, amountToBePaid: 999);

        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);

        $expectedSearchQuery = "metadata['customer_id']:'{$customer->getId()}'";
        $returnedSearchResults = [new SearchResult("cus_1234")];
        $stripeClient = $this->_mockStripeClient($order, $expectedSearchQuery, $returnedSearchResults);

        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService, $stripeClient);

        // when
        $clientSecret = $paymentService->createStripePaymentForOrder($order);

        // then
        $payment = PaymentQuery::create()->filterByOrder($order)->findOne();
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals($order, $payment->getOrder());
        $this->assertEquals("stripe", $payment->getMode());
        $this->assertEquals(999, $payment->getAmount());
        $this->assertEquals("pi_1234", $payment->getProviderId());
        $this->assertNull($payment->getExecuted());
        $this->assertEquals("pi_1234_secret_abcd", $clientSecret);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCreateStripePaymentForOrderWithNewCustomer()
    {
        // given
        $customer = ModelFactory::createCustomer();
        $order = ModelFactory::createOrder(customer: $customer, amountToBePaid: 999);

        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $loggerService = Mockery::mock(LoggerService::class);

        $expectedSearchQuery = "metadata['customer_id']:'{$customer->getId()}'";
        $returnedSearchResults = [];
        $stripeClient = $this->_mockStripeClient(
            $order, $expectedSearchQuery, $returnedSearchResults, expectedCustomerIdForCreation: $order->getCustomerId()
        );

        $paymentService = new PaymentService($config, $currentSite, $urlGenerator, $loggerService, $stripeClient);

        // when
        $clientSecret = $paymentService->createStripePaymentForOrder($order);

        // then
        $payment = PaymentQuery::create()->filterByOrder($order)->findOne();
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals($order, $payment->getOrder());
        $this->assertEquals("stripe", $payment->getMode());
        $this->assertEquals(999, $payment->getAmount());
        $this->assertEquals("pi_1234", $payment->getProviderId());
        $this->assertNull($payment->getExecuted());
        $this->assertEquals("pi_1234_secret_abcd", $clientSecret);
    }

    private function _mockStripeClient(
        Order $order,
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

        $paymentIntentService = Mockery::mock(PaymentIntentService::class);
        $paymentIntent = new PaymentIntent("pi_1234");
        $paymentIntent->client_secret = "pi_1234_secret_abcd";
        $paymentIntentService->expects("create")->with([
            "amount" => 999,
            "currency" => "eur",
            "customer"=> "cus_1234",
            "payment_method_types" => ["card"],
            "metadata" => [
                "order_id" => $order->getId(),
            ],
        ])->andReturn($paymentIntent);
        $stripeClient->expects("getService")->with("paymentIntents")->andReturn($paymentIntentService);

        return $stripeClient;
    }

}
