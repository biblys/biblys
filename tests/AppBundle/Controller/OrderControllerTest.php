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

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\LoggerService;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\OrderQuery;
use Model\Payment;
use Model\PaymentQuery;
use Payplug\Core\HttpClient;
use Payplug\Core\IHttpRequest;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../setUp.php";

class OrderControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        OrderQuery::create()->deleteAll();
        PaymentQuery::create()->deleteAll();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function testIndexAction(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");
        $templateService = Helpers::getTemplateService();
        $config = Mockery::mock(Config::class);
        $config->expects("isMondialRelayEnabled")->andReturn(false);

        // when
        $response = $controller->indexAction($request, $currentUser, $config, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Commandes web", $response->getContent());
        $this->assertStringNotContainsString("Exporter pour Mondial Relay", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testIndexActionWithMondialRelayEnabled(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");
        $templateService = Helpers::getTemplateService();
        $config = Mockery::mock(Config::class);
        $config->expects("isMondialRelayEnabled")->andReturn(true);

        // when
        $response = $controller->indexAction($request, $currentUser, $config, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Commandes web", $response->getContent());
        $this->assertStringContainsString("Export Mondial Relay", $response->getContent());
    }

    /**
     * @throws PropelException
     */
    public function testShowForAdmin()
    {
        // given
        $order = ModelFactory::createOrder();
        $controller = new OrderController();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->show($currentUser, $order->getId());

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/order/order-slug", $response->getTargetUrl());
    }

    /** updateAction
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     */

    public function testUpdateActionToMarkOrderAsPaid(): void
    {
        // given
        $controller = new OrderController();
        $order = ModelFactory::createOrder(amountToBePaid: 999);

        $payload = json_encode(["payment_mode" => Payment::MODE_CARD, "tracking_number" => null]);
        $request = new Request(content: $payload);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("render");
        $mailer = Mockery::mock(Mailer::class);
        $mailer->expects("send");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate");

        // when
        $response = $controller->updateAction(
            $request,
            $currentSite,
            $currentUser,
            $templateService,
            $mailer,
            urlGenerator: $urlGenerator,
            id: $order->getId(),
            action: "payed",
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $order->reload();
        $this->assertTrue($order->isPaid());
        $payment = PaymentQuery::create()->findOneByOrderId($order->getId());
        $this->assertNotNull($payment);
        $this->assertEquals(Payment::MODE_CARD, $payment->getMode());
        $this->assertEquals(999, $payment->getAmount());
        $this->assertTrue($payment->isExecuted());
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     */
    public function testUpdateActionToMarkOrderAsShipped()
    {
        // given
        $controller = new OrderController();

        $order = ModelFactory::createOrder();
        $payload = json_encode(["payment_mode" => null, "tracking_number" => null]);

        $request = new Request(content: $payload);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Mockery::mock(TemplateService::class);
        $mailer = Mockery::mock(Mailer::class);

        // when
        $response = $controller->updateAction(
            $request,
            $currentSite,
            $currentUser,
            $templateService,
            $mailer,
            urlGenerator: Mockery::mock(UrlGenerator::class),
            id: $order->getId(),
            action: "shipped",
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $updatedOrder = OrderQuery::create()->findPk($order->getId());
        $this->assertNotNull($updatedOrder->getShippingDate());
    }

    /** payplugNotificationAction */

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithoutPayplugConfig(): void
    {
        // given
        $GLOBALS["LEGACY_CONFIG"] = new Config([]);
        $controller = new OrderController();
        $request = new Request();
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->expects("log")->with("payplug", "ERROR", "Payplug configuration not found.");

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Payplug configuration not found.");

        // when
        $controller->payplugNotificationAction($request, $loggerService, "order-url");
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithMissingSecret(): void
    {
        // given
        $GLOBALS["LEGACY_CONFIG"] = new Config(["payplug" => ["public_key" => "pk_test"]]);
        $controller = new OrderController();
        $request = new Request();
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->expects("log")->with("payplug", "ERROR", "Missing payplug private key.");

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Missing payplug private key.");

        // when
        $controller->payplugNotificationAction($request, $loggerService, "order-url");
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithUnknownOrder(): void
    {
        // given
        $GLOBALS["LEGACY_CONFIG"] = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $controller = new OrderController();
        $request = new Request();
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->expects("log")->with("payplug", "ERROR", "Order unknown-url not found.");

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Order unknown-url not found.");

        // when
        $controller->payplugNotificationAction($request, $loggerService, "unknown-url");
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionIgnoresRefund(): void
    {
        // given
        $GLOBALS["LEGACY_CONFIG"] = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $order = ModelFactory::createOrder(slug: "pp-refund");
        $controller = new OrderController();
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

        // when
        $response = $controller->payplugNotificationAction($request, $loggerService, $order->getSlug());

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
        $GLOBALS["LEGACY_CONFIG"] = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $order = ModelFactory::createOrder(slug: "pp-invalid-json");
        $controller = new OrderController();
        $request = new Request(content: "not valid json");
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->shouldReceive("log");

        // then
        $this->expectException(BadRequestHttpException::class);

        // when
        $controller->payplugNotificationAction($request, $loggerService, $order->getSlug());
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testPayplugNotificationActionWithUnpaidPayment(): void
    {
        // given
        $GLOBALS["LEGACY_CONFIG"] = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $order = ModelFactory::createOrder(slug: "pp-unpaid");
        $controller = new OrderController();
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

        // when
        $response = $controller->payplugNotificationAction($request, $loggerService, $order->getSlug());

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
        $GLOBALS["LEGACY_CONFIG"] = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $order = ModelFactory::createOrder(slug: "pp-not-found");
        $controller = new OrderController();
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

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Payment pay_test_unknown not found.");

        // when
        try {
            $controller->payplugNotificationAction($request, $loggerService, $order->getSlug());
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
        $GLOBALS["LEGACY_CONFIG"] = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $order = ModelFactory::createOrder(slug: "pp-mismatch");
        ModelFactory::createPayment(order: $order, providerId: "pay_test_mismatch", executedAt: null);
        $controller = new OrderController();
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

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invoice number does not match order ID.");

        // when
        try {
            $controller->payplugNotificationAction($request, $loggerService, $order->getSlug());
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
        $GLOBALS["LEGACY_CONFIG"] = new Config(["payplug" => ["secret" => "sk_test_123"]]);
        $order = ModelFactory::createOrder(amountToBePaid: 1000, slug: "pp-success");
        $payment = ModelFactory::createPayment(order: $order, mode: Payment::MODE_PAYPLUG, providerId: "pay_test_success", executedAt: null);
        $controller = new OrderController();
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

        // when
        $response = $controller->payplugNotificationAction($request, $loggerService, $order->getSlug());

        // then
        HttpClient::$REQUEST_HANDLER = null;
        $this->assertEquals(200, $response->getStatusCode());
        $payment->reload();
        $this->assertTrue($payment->isExecuted());
        $order->reload();
        $this->assertTrue($order->isPaid());
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     */
    public function testUpdateActionToMarkOrderAsShippedWithTrackingNumber()
    {
        // given
        $controller = new OrderController();

        $order = ModelFactory::createOrder();
        $payload = json_encode(["payment_mode" => null, "tracking_number" => "123456789"]);

        $request = new Request(content: $payload);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Mockery::mock(TemplateService::class);
        $mailer = Mockery::mock(Mailer::class);

        // when
        $response = $controller->updateAction(
            $request,
            $currentSite,
            $currentUser,
            $templateService,
            $mailer,
            urlGenerator: Mockery::mock(UrlGenerator::class),
            id: $order->getId(),
            action: "shipped",
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $updatedOrder = OrderQuery::create()->findPk($order->getId());
        $this->assertNotNull($updatedOrder->getShippingDate());
        $this->assertEquals("123456789", $updatedOrder->getTrackNumber());
    }
}
