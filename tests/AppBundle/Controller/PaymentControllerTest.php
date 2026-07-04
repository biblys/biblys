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

namespace AppBundle\Controller;

use Biblys\Service\BodyParamsService;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\LoggerService;
use Biblys\Service\Mailer;
use Biblys\Service\PaymentService;
use Biblys\Service\TemplateService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Mockery;
use Mockery\Mock;
use Model\OrderQuery;
use Model\Payment;
use Model\PaymentQuery;
use Payplug\Core\HttpClient;
use Payplug\Core\IHttpRequest;
use Payplug\Exception\BadRequestException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Usecase\AddPaymentToOrderAndExecuteUsecase;

require_once __DIR__ . "/../../setUp.php";

class PaymentControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        OrderQuery::create()->deleteAll();
        PaymentQuery::create()->deleteAll();
        Mockery::resetContainer();
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Exception
     */
    public function testIndex()
    {
        // given
        $controller = new PaymentController();
        $request = new Request();
        $order = ModelFactory::createOrder();
        $today = new DateTime();
        ModelFactory::createPayment(order: $order, executedAt: $today);
        ModelFactory::createPayment(order: $order, amount: 300);
        ModelFactory::createPayment(order: $order, amount: 900);
        ModelFactory::createPayment(executedAt: null);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->index($request, $currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Paiements", $response->getContent());
        $this->assertStringContainsString($today->format("Y-m-d"), $response->getContent());
        $this->assertStringContainsString($order->getId(), $response->getContent());
        $this->assertStringContainsString("stripe", $response->getContent());
        $this->assertStringContainsString("100,00&nbsp;&euro;", $response->getContent());
        $this->assertStringNotContainsString("from other site", $response->getContent());
        $this->assertStringNotContainsString("not executed", $response->getContent());
        $this->assertStringContainsString("112,00&nbsp;&euro;", $response->getContent());
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Exception
     */
    public function testIndexWithModeFilter()
    {
        // given
        $orderPayedUsingStripe = ModelFactory::createOrder();
        ModelFactory::createPayment(
            order: $orderPayedUsingStripe,
            executedAt: new DateTime(),
        );
        $orderPayedUsingPaypal = ModelFactory::createOrder();
        ModelFactory::createPayment(
            order: $orderPayedUsingPaypal,
            mode: Payment::MODE_PAYPAL,
            executedAt: new DateTime(),
        );

        $controller = new PaymentController();
        $request = new Request();
        $request->query->set("mode", "stripe");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->index($request, $currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString($orderPayedUsingStripe->getId(), $response->getContent());
        $this->assertStringNotContainsString("01/02/2001", $response->getContent());
    }

    /**
     * @throws Exception
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testIndexWithDatesFilter()
    {
        // given
        $order = ModelFactory::createOrder();
        ModelFactory::createPayment(order: $order, executedAt: new DateTime("2019-04-26"));
        ModelFactory::createPayment(order: $order, executedAt: new DateTime("2019-04-28"));
        ModelFactory::createPayment(order: $order, executedAt: new DateTime("2019-04-30"));

        $controller = new PaymentController();
        $request = new Request();
        $request->query->set("start_date", "2019-04-27");
        $request->query->set("end_date", "2019-04-29");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->index($request, $currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("28/04/2019", $response->getContent());
        $this->assertStringNotContainsString("26/04/2019", $response->getContent());
        $this->assertStringNotContainsString("30/04/2019", $response->getContent());
    }

    /** createAction */

    /**
     * @throws PropelException
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testCreateAction(): void
    {
        // given
        $order = ModelFactory::createOrder(amountToBePaid: 1500);

        $controller = new PaymentController();

        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->shouldReceive("parse")->with([
            "order_id" => ["type" => "numeric"],
            "payment_mode" => ["type" => "string"],
            "payment_amount" => ["type" => "numeric"],
        ])->once();
        $bodyParams->shouldReceive("getInteger")->with("order_id")->andReturn($order->getId());
        $bodyParams->shouldReceive("get")->with("payment_mode")->andReturn("cash");
        $bodyParams->shouldReceive("getInteger")->with("payment_amount")->andReturn(1500);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        $mailer = Mockery::mock(Mailer::class);
        $mailer->expects("send")->once();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate");
        $templateService = Helpers::getTemplateService();

        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->expects("add")
            ->with("success", "Un paiement de 15,00&nbsp;&euro; a été ajouté à la commande {$order->getId()}.")
            ->once();

        // when
        $response = $controller->createAction(
            bodyParams: $bodyParams,
            currentUser: $currentUser,
            mailer: $mailer,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            flashMessages: $flashMessagesService
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/pages/adm_order?order_id={$order->getId()}", $response->getTargetUrl());
        $order->reload(true);
        $this->assertTrue($order->isPaid());
        $payment = PaymentQuery::create()->findOneByOrderId($order->getId());
        $this->assertNotNull($payment);
        $this->assertEquals(1500, $payment->getAmount());
        $this->assertEquals("cash", $payment->getMode());
    }

    /** stripeWebhookAction */

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testStripeWebhookActionWithoutStripeConfig(): void
    {
        // given
        $controller = new PaymentController();
        $config = new Config([]);
        $request = new Request();
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Stripe is not configured.");

        // when
        $controller->stripeWebhookAction($request, $config, $mailer, $currentSite, $urlGenerator, $templateService);
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testStripeWebhookActionWithMissingPublicKey(): void
    {
        // given
        $controller = new PaymentController();
        $config = new Config(["stripe" => ["secret_key" => "sk_test_123", "endpoint_secret" => "whsec_123"]]);
        $request = new Request();
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Missing Stripe public key.");

        // when
        $controller->stripeWebhookAction($request, $config, $mailer, $currentSite, $urlGenerator, $templateService);
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testStripeWebhookActionWithMissingSecretKey(): void
    {
        // given
        $controller = new PaymentController();
        $config = new Config(["stripe" => ["public_key" => "pk_test_123", "endpoint_secret" => "whsec_123"]]);
        $request = new Request();
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Missing Stripe secret key.");

        // when
        $controller->stripeWebhookAction($request, $config, $mailer, $currentSite, $urlGenerator, $templateService);
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testStripeWebhookActionWithMissingEndpointSecret(): void
    {
        // given
        $controller = new PaymentController();
        $config = new Config(["stripe" => ["public_key" => "pk_test_123", "secret_key" => "sk_test_123"]]);
        $request = new Request();
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Missing Stripe endpoint secret.");

        // when
        $controller->stripeWebhookAction($request, $config, $mailer, $currentSite, $urlGenerator, $templateService);
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testStripeWebhookActionWithMissingSignatureHeader(): void
    {
        // given
        $controller = new PaymentController();
        $config = new Config(["stripe" => [
            "public_key" => "pk_test_123",
            "secret_key" => "sk_test_123",
            "endpoint_secret" => "whsec_123",
        ]]);
        $request = new Request();
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("stripe-signature header is missing");

        // when
        $controller->stripeWebhookAction($request, $config, $mailer, $currentSite, $urlGenerator, $templateService);
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testStripeWebhookActionIgnoresNonPaymentIntentSucceededEvent(): void
    {
        // given
        $controller = new PaymentController();
        $endpointSecret = "whsec_test_secret";
        $config = new Config(["stripe" => [
            "public_key" => "pk_test_123",
            "secret_key" => "sk_test_123",
            "endpoint_secret" => $endpointSecret,
        ]]);
        $order = ModelFactory::createOrder(amountToBePaid: 1000);
        $payment = ModelFactory::createPayment(order: $order, providerId: "pi_test_123", executedAt: null);
        $payload = json_encode([
            "id" => "evt_test_123",
            "object" => "event",
            "type" => "payment_intent.created",
            "data" => ["object" => ["id" => "pi_test_123"]],
        ]);
        $timestamp = time();
        $signature = hash_hmac("sha256", "$timestamp.$payload", $endpointSecret);
        $request = Request::create("/", "POST", [], [], [], [], $payload);
        $request->headers->set("stripe-signature", "t=$timestamp,v1=$signature");
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Mockery::mock(TemplateService::class);

        // when
        $response = $controller->stripeWebhookAction($request, $config, $mailer, $currentSite, $urlGenerator, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $payment->reload();
        $this->assertFalse($payment->isExecuted());
        $order->reload();
        $this->assertFalse($order->isPaid());
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testStripeWebhookActionHandlesPaymentIntentSucceededEvent(): void
    {
        // given
        $controller = new PaymentController();
        $endpointSecret = "whsec_test_secret";
        $config = new Config(["stripe" => [
            "public_key" => "pk_test_123",
            "secret_key" => "sk_test_123",
            "endpoint_secret" => $endpointSecret,
        ]]);
        $order = ModelFactory::createOrder(amountToBePaid: 1000, email: "customer@example.com");
        $payment = ModelFactory::createPayment(order: $order, providerId: "pi_test_456", executedAt: null);
        $payload = json_encode([
            "id" => "evt_test_456",
            "object" => "event",
            "type" => "payment_intent.succeeded",
            "data" => ["object" => ["id" => "pi_test_456"]],
        ]);
        $timestamp = time();
        $signature = hash_hmac("sha256", "$timestamp.$payload", $endpointSecret);
        $request = Request::create("/", "POST", [], [], [], [], $payload);
        $request->headers->set("stripe-signature", "t=$timestamp,v1=$signature");
        $mailer = Mockery::mock(Mailer::class);
        $mailer->expects("send")->once()->with("customer@example.com", Mockery::any(), Mockery::any());
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->with("legacy_order", ["url" => $order->getSlug()])->andReturn("/order/order-slug");
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->stripeWebhookAction($request, $config, $mailer, $currentSite, $urlGenerator, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $payment->reload();
        $this->assertTrue($payment->isExecuted());
        $order->reload();
        $this->assertTrue($order->isPaid());
    }

    /** selectMethodAction */

    /**
     * @throws Exception
     */
    public function testSelectMethodAction()
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder();
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("payment_check")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("name_for_check_payment")->andReturn(null);
        $templateService = Helpers::getTemplateService();
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        // when
        $response = $controller->selectMethodAction($paymentService, $config, $currentSite, $templateService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Comment souhaitez-vous régler votre commande ?", $response->getContent());
        $this->assertStringNotContainsString("Stripe", $response->getContent());
        $this->assertStringNotContainsString("PayPlug", $response->getContent());
        $this->assertStringNotContainsString("PayPal", $response->getContent());
        $this->assertStringNotContainsString("Virement", $response->getContent());
    }

    /**
     * @throws Exception
     */
    public function testSelectMethodActionWithStripe()
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder(amountToBePaid: 1000);
        $config = new Config(["stripe" => ["public_key" => "abcd"]]);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("payment_check")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("name_for_check_payment")->andReturn(null);
        $templateService = Helpers::getTemplateService();
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        // when
        $response = $controller->selectMethodAction($paymentService, $config, $currentSite, $templateService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("stripe-payment-form", $response->getContent());
    }

    /**
     * @throws Exception
     */
    public function testSelectMethodActionWithPayPlug()
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder();
        $config = new Config(["payplug" => ["secret" => "abcd"]]);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("payment_check")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("name_for_check_payment")->andReturn(null);
        $templateService = Helpers::getTemplateService();
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        // when
        $response = $controller->selectMethodAction($paymentService, $config, $currentSite, $templateService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("PayPlug", $response->getContent());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSelectMethodActionWithPayPal()
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder();
        $config = new Config([
            "paypal" => ["client_id" => "PAYPAL_CLIENT_ID", "client_secret" => "1234"],
            "stripe" => ["public_key" => "abcd"]
        ]);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("payment_check")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("name_for_check_payment")->andReturn(null);
        $templateService = Helpers::getTemplateService();
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        // when
        $response = $controller->selectMethodAction($paymentService, $config, $currentSite, $templateService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("PayPal", $response->getContent());
        $this->assertStringContainsString("PAYPAL_CLIENT_ID", $response->getContent());
        $this->assertStringContainsString("Payez avec votre compte PayPal.", $response->getContent());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSelectMethodActionWhenOnlyPaypalIsAvailable()
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder();
        $config = new Config(["paypal" => ["client_id" => "PAYPAL_CLIENT_ID", "client_secret" => "1234"]]);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("payment_check")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("name_for_check_payment")->andReturn(null);
        $templateService = Helpers::getTemplateService();
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        // when
        $response = $controller->selectMethodAction($paymentService, $config, $currentSite, $templateService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("PayPal", $response->getContent());
        $this->assertStringContainsString("PAYPAL_CLIENT_ID", $response->getContent());
        $this->assertStringContainsString("Payez par carte bancaire ou avec votre compte PayPal.", $response->getContent());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSelectMethodActionWithTransfer()
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder();
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("payment_iban")->andReturn("PAYMENT_IBAN");
        $currentSite->shouldReceive("getOption")->with("payment_check")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("name_for_check_payment")->andReturn(null);
        $templateService = Helpers::getTemplateService();
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        // when
        $response = $controller->selectMethodAction($paymentService, $config, $currentSite, $templateService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Virement", $response->getContent());
        $this->assertStringContainsString("PAYMENT_IBAN", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function testSelectMethodActionWithCheck()
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder();
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("payment_check")->andReturn(1);
        $currentSite->shouldReceive("getOption")->with("name_for_check_payment")->andReturn("L’ordre");
        $templateService = Helpers::getTemplateService();
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        // when
        $response = $controller->selectMethodAction($paymentService, $config, $currentSite, $templateService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Chèque", $response->getContent());
        $this->assertStringContainsString("L’ordre", $response->getContent());
        $this->assertStringContainsString("Envoyez votre chèque", $response->getContent());
        $this->assertStringNotContainsString(
            "Remettez votre chèque lors du retrait",
            $response->getContent()
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function testSelectMethodActionWithCheckAndInStorePickup()
    {
        // given
        $controller = new PaymentController();
        $shippingOption = ModelFactory::createShippingOption(type: "magasin");
        $order = ModelFactory::createOrder(shippingOption: $shippingOption);
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("payment_check")->andReturn(1);
        $currentSite->shouldReceive("getOption")->with("name_for_check_payment")->andReturn("L’ordre");
        $templateService = Helpers::getTemplateService();
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        // when
        $response = $controller->selectMethodAction($paymentService, $config, $currentSite, $templateService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Chèque", $response->getContent());
        $this->assertStringContainsString(
            "Remettez votre chèque lors du retrait de votre commande en magasin.",
            $response->getContent()
        );
        $this->assertStringNotContainsString("Envoyez votre chèque", $response->getContent());
    }

    /** createPayplugPaymentAction */

    /**
     * @throws Exception
     */
    public function testCreatePayplugPaymentAction()
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder();
        $payment = ModelFactory::createPayment(url: "/payment_url");
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);
        $paymentService->expects("createPayplugPaymentForOrder")->with($order)->andReturn($payment);
        $loggerService = Mockery::mock(LoggerService::class);
        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);

        // when
        $response = $controller->createPayplugPaymentAction(
            $paymentService,
            $loggerService,
            $flashMessagesService,
            $urlGenerator,
            $order->getSlug()
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/payment_url", $response->getTargetUrl());
    }

    /**
     * @throws Exception
     */
    public function testCreatePayplugPaymentActionWithError()
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder();
        $badRequestException = new BadRequestException(
            "error", '{"message":"message","details":{"details": "details"}}'
        );

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->shouldReceive("getPayableOrderBySlug")->andReturn($order);
        $paymentService->expects("createPayplugPaymentForOrder")->with($order)
            ->andThrows($badRequestException);
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->expects("log")->with("payplug", "CRITICAL", "message", ["details" => "details"]);
        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->expects("add")
            ->with("error", "Une erreur est survenue lors de la création du paiement via PayPlug : message");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->with("payment_pay", ["slug" => $order->getSlug()])
            ->andReturn("/order_url");

        // when
        $response = $controller->createPayplugPaymentAction(
            $paymentService,
            $loggerService,
            $flashMessagesService,
            $urlGenerator,
            $order->getSlug()
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/order_url", $response->getTargetUrl());
    }


    /** payplugNotificationAction */

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithoutPayplugConfig(): void
    {
        // given
        $controller = new PaymentController();
        $request = new Request();
        $config = new Config([]);
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->expects("log")->with("payplug", "ERROR", "Payplug configuration not found.");
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Helpers::getTemplateService();

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Payplug configuration not found.");

        // when
        $controller->payplugNotificationAction(
            request: $request,
            loggerService: $loggerService,
            config: $config,
            mailer: $mailer,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            url: "slug"
        );
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithMissingSecret(): void
    {
        // given
        $controller = new PaymentController();
        $request = new Request();
        $config = new Config(["payplug" => ["public_key" => "pk_test"]]);
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->expects("log")->with("payplug", "ERROR", "Missing payplug private key.");
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Helpers::getTemplateService();

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Missing payplug private key.");

        // when
        $controller->payplugNotificationAction(
            request: $request,
            loggerService: $loggerService,
            config: $config,
            mailer: $mailer,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            url: "slug"
        );
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithUnknownOrder(): void
    {
        // given
        $controller = new PaymentController();
        $request = new Request();
        $config = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->expects("log")->with("payplug", "ERROR", "Order unknown-url not found.");
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Helpers::getTemplateService();

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Order unknown-url not found.");

        // when
        $controller->payplugNotificationAction(
            request: $request,
            loggerService: $loggerService,
            config: $config,
            mailer: $mailer,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            url: "unknown-url",
        );
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionIgnoresRefund(): void
    {
        // given
        $order = ModelFactory::createOrder(slug: "pp-refund");
        $controller = new PaymentController();
        $config = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $body = json_encode(["object" => "refund", "id" => "re_test_123", "payment_id" => "pay_test_123"]);
        $request = new Request(content: $body);
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->shouldReceive("log");
        $httpMock = Mockery::mock(IHttpRequest::class);
        $httpMock->shouldReceive("setopt");
        $httpMock->shouldReceive("exec")->andReturn(json_encode([
            "object" => "refund",
            "id" => "re_test_123",
            "payment_id" => "pay_test_123",
        ]));
        $httpMock->shouldReceive("getinfo")->andReturn(200);
        $httpMock->shouldReceive("errno")->andReturn(0);
        $httpMock->shouldReceive("error")->andReturn("");
        $httpMock->shouldReceive("close");
        HttpClient::$REQUEST_HANDLER = $httpMock;
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->payplugNotificationAction(
            request: $request,
            loggerService: $loggerService,
            config: $config,
            mailer: $mailer,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            url: $order->getSlug()
        );

        // then
        HttpClient::$REQUEST_HANDLER = null;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("", $response->getContent());
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithInvalidJson(): void
    {
        // given
        $order = ModelFactory::createOrder(slug: "pp-invalid-json");
        $controller = new PaymentController();
        $config = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $request = new Request(content: "not valid json");
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->shouldReceive("log");
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Helpers::getTemplateService();

        // then
        $this->expectException(BadRequestHttpException::class);

        // when
        $controller->payplugNotificationAction(
            request: $request,
            loggerService: $loggerService,
            config: $config,
            mailer: $mailer,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            url: $order->getSlug()
        );
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithUnpaidPayment(): void
    {
        // given
        $order = ModelFactory::createOrder(slug: "pp-unpaid");
        $controller = new PaymentController();
        $config = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $body = json_encode(["object" => "payment", "id" => "pay_test_unpaid"]);
        $request = new Request(content: $body);
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->shouldReceive("log");
        $httpMock = Mockery::mock(IHttpRequest::class);
        $httpMock->shouldReceive("setopt");
        $httpMock->shouldReceive("exec")->andReturn(json_encode([
            "object" => "payment",
            "id" => "pay_test_unpaid",
            "is_paid" => false,
        ]));
        $httpMock->shouldReceive("getinfo")->andReturn(200);
        $httpMock->shouldReceive("errno")->andReturn(0);
        $httpMock->shouldReceive("error")->andReturn("");
        $httpMock->shouldReceive("close");
        HttpClient::$REQUEST_HANDLER = $httpMock;
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->payplugNotificationAction(
            request: $request,
            loggerService: $loggerService,
            config: $config,
            mailer: $mailer,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            url: $order->getSlug()
        );

        // then
        HttpClient::$REQUEST_HANDLER = null;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("", $response->getContent());
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithPaymentNotFoundInDatabase(): void
    {
        // given
        $order = ModelFactory::createOrder(slug: "pp-not-found");
        $controller = new PaymentController();
        $config = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $body = json_encode(["object" => "payment", "id" => "pay_test_unknown"]);
        $request = new Request(content: $body);
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->shouldReceive("log");
        $httpMock = Mockery::mock(IHttpRequest::class);
        $httpMock->shouldReceive("setopt");
        $httpMock->shouldReceive("exec")->andReturn(json_encode([
            "object" => "payment",
            "id" => "pay_test_unknown",
            "is_paid" => true,
        ]));
        $httpMock->shouldReceive("getinfo")->andReturn(200);
        $httpMock->shouldReceive("errno")->andReturn(0);
        $httpMock->shouldReceive("error")->andReturn("");
        $httpMock->shouldReceive("close");
        HttpClient::$REQUEST_HANDLER = $httpMock;
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Helpers::getTemplateService();

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Payment pay_test_unknown not found.");

        // when
        try {
            $controller->payplugNotificationAction(
                request: $request,
                loggerService: $loggerService,
                config: $config,
                mailer: $mailer,
                currentSite: $currentSite,
                urlGenerator: $urlGenerator,
                templateService: $templateService,
                url: $order->getSlug()
            );
        } finally {
            HttpClient::$REQUEST_HANDLER = null;
        }
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithOrderIdMismatch(): void
    {
        // given
        $order = ModelFactory::createOrder(slug: "pp-mismatch");
        ModelFactory::createPayment(order: $order, providerId: "pay_test_mismatch", executedAt: null);
        $controller = new PaymentController();
        $config = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $body = json_encode(["object" => "payment", "id" => "pay_test_mismatch"]);
        $request = new Request(content: $body);
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->shouldReceive("log");
        $httpMock = Mockery::mock(IHttpRequest::class);
        $httpMock->shouldReceive("setopt");
        $httpMock->shouldReceive("exec")->andReturn(json_encode([
            "object" => "payment",
            "id" => "pay_test_mismatch",
            "is_paid" => true,
            "metadata" => ["order_id" => 99999],
        ]));
        $httpMock->shouldReceive("getinfo")->andReturn(200);
        $httpMock->shouldReceive("errno")->andReturn(0);
        $httpMock->shouldReceive("error")->andReturn("");
        $httpMock->shouldReceive("close");
        HttpClient::$REQUEST_HANDLER = $httpMock;
        $mailer = Mockery::mock(Mailer::class);
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $templateService = Helpers::getTemplateService();

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invoice number does not match order ID.");

        // when
        try {
            $controller->payplugNotificationAction(
                request: $request,
                loggerService: $loggerService,
                config: $config,
                mailer: $mailer,
                currentSite: $currentSite,
                urlGenerator: $urlGenerator,
                templateService: $templateService,
                url: $order->getSlug()
            );
        } finally {
            HttpClient::$REQUEST_HANDLER = null;
        }
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionAddsPaymentToOrder(): void
    {
        // given
        $order = ModelFactory::createOrder(amountToBePaid: 1000, slug: "pp-success");
        $payment = ModelFactory::createPayment(order: $order, mode: Payment::MODE_PAYPLUG, providerId: "pay_test_success", executedAt: null);
        $controller = new PaymentController();
        $config = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $body = json_encode(["object" => "payment", "id" => "pay_test_success"]);
        $request = new Request(content: $body);
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->shouldReceive("log");
        $httpMock = Mockery::mock(IHttpRequest::class);
        $httpMock->shouldReceive("setopt");
        $httpMock->shouldReceive("exec")->andReturn(json_encode([
            "object" => "payment",
            "id" => "pay_test_success",
            "is_paid" => true,
            "metadata" => ["order_id" => $order->getId()],
        ]));
        $httpMock->shouldReceive("getinfo")->andReturn(200);
        $httpMock->shouldReceive("errno")->andReturn(0);
        $httpMock->shouldReceive("error")->andReturn("");
        $httpMock->shouldReceive("close");
        HttpClient::$REQUEST_HANDLER = $httpMock;
        $mailer = Mockery::mock(Mailer::class);
        $mailer->expects("send")->once();
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate");
        $templateService = Helpers::getTemplateService();

        // when
        $response =
            $controller->payplugNotificationAction(
                request: $request,
                loggerService: $loggerService,
                config: $config,
                mailer: $mailer,
                currentSite: $currentSite,
                urlGenerator: $urlGenerator,
                templateService: $templateService,
                url: $order->getSlug()
            );

        // then
        HttpClient::$REQUEST_HANDLER = null;
        $this->assertEquals(200, $response->getStatusCode());
        $payment->reload();
        $this->assertTrue($payment->isExecuted());
        $order->reload();
        $this->assertTrue($order->isPaid());
    }

    /** refundAction */

    /**
     * @throws PropelException
     */
    public function testRefundActionRefundsPayment(): void
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder();
        $payment = ModelFactory::createPayment(order: $order, amount: 2000, mode: Payment::MODE_STRIPE);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->expects("add")->with("success", "Le paiement a été remboursé.")->once();

        // when
        $response = $controller->refundAction($payment->getId(), $currentUser, $flashMessages);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/admin/payments/", $response->getTargetUrl());
        $payment->reload();
        $this->assertNotNull($payment->getRefundedAt());
        $refund = PaymentQuery::create()->findOneByOriginalId($payment->getId());
        $this->assertNotNull($refund);
        $this->assertEquals(-2000, $refund->getAmount());
    }

    /**
     * @throws PropelException
     */
    public function testRefundActionReturns404IfPaymentNotFound(): void
    {
        // given
        $controller = new PaymentController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $flashMessages = Mockery::mock(FlashMessagesService::class);

        // when / then
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $controller->refundAction(999999, $currentUser, $flashMessages);
    }

    /**
     * @throws PropelException
     */
    public function testRefundActionRedirectsWithErrorIfAlreadyRefunded(): void
    {
        // given
        $controller = new PaymentController();
        $payment = ModelFactory::createPayment(refundedAt: new DateTime());

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->expects("add")->with("danger", "Ce paiement a déjà été remboursé.")->once();

        // when
        $response = $controller->refundAction($payment->getId(), $currentUser, $flashMessages);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/admin/payments/", $response->getTargetUrl());
    }
}
