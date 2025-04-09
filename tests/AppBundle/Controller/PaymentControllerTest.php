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

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\LoggerService;
use Biblys\Service\PaymentService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Mockery;
use Model\OrderQuery;
use Model\PaymentQuery;
use Payplug\Exception\BadRequestException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
        $site = ModelFactory::createSite();
        $order = ModelFactory::createOrder();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getSite")->willReturn($site);
        $today = new DateTime();
        ModelFactory::createPayment(site: $site, order: $order, executedAt: $today);
        ModelFactory::createPayment(site: $site, amount: 300);
        ModelFactory::createPayment(site: $site, amount: 900);
        $otherSite = ModelFactory::createSite();
        ModelFactory::createPayment(site: $otherSite);
        ModelFactory::createPayment(site: $site, executedAt: null);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->index($request, $currentSite, $currentUser, $templateService);

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
        $site = ModelFactory::createSite();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getSite")->willReturn($site);

        $orderPayedUsingStripe = ModelFactory::createOrder();
        ModelFactory::createPayment(
            site: $site,
            order: $orderPayedUsingStripe,
            executedAt: new DateTime(),
        );
        $orderPayedUsingPaypal = ModelFactory::createOrder();
        ModelFactory::createPayment(
            site: $site,
            order: $orderPayedUsingPaypal,
            mode: "paypal",
            executedAt: new DateTime(),
        );

        $controller = new PaymentController();
        $request = new Request();
        $request->query->set("mode", "stripe");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->index($request, $currentSite, $currentUser, $templateService);

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
        $site = ModelFactory::createSite();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getSite")->willReturn($site);

        ModelFactory::createPayment(site: $site, executedAt: new DateTime("2019-04-26"));
        ModelFactory::createPayment(site: $site, executedAt: new DateTime("2019-04-28"));
        ModelFactory::createPayment(site: $site, executedAt: new DateTime("2019-04-30"));

        $controller = new PaymentController();
        $request = new Request();
        $request->query->set("start_date", "2019-04-27");
        $request->query->set("end_date", "2019-04-29");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->index($request, $currentSite, $currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("28/04/2019", $response->getContent());
        $this->assertStringNotContainsString("26/04/2019", $response->getContent());
        $this->assertStringNotContainsString("30/04/2019", $response->getContent());
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
        $currentSite->expects("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->expects("getOption")->with("payment_check")->andReturn(null);
        $currentSite->expects("getOption")->with("name_for_check_payment")->andReturn(null);
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
        $currentSite->expects("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->expects("getOption")->with("payment_check")->andReturn(null);
        $currentSite->expects("getOption")->with("name_for_check_payment")->andReturn(null);
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
        $currentSite->expects("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->expects("getOption")->with("payment_check")->andReturn(null);
        $currentSite->expects("getOption")->with("name_for_check_payment")->andReturn(null);
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
        $config = new Config(["paypal" => ["client_id" => "PAYPAL_CLIENT_ID", "client_secret" => "1234"]]);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->expects("getOption")->with("payment_check")->andReturn(null);
        $currentSite->expects("getOption")->with("name_for_check_payment")->andReturn(null);
        $templateService = Helpers::getTemplateService();
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        // when
        $response = $controller->selectMethodAction($paymentService, $config, $currentSite, $templateService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("PayPal", $response->getContent());
        $this->assertStringContainsString("PAYPAL_CLIENT_ID", $response->getContent());
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
        $currentSite->expects("getOption")->with("payment_iban")->andReturn("PAYMENT_IBAN");
        $currentSite->expects("getOption")->with("payment_check")->andReturn(null);
        $currentSite->expects("getOption")->with("name_for_check_payment")->andReturn(null);
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
        $currentSite->expects("getOption")->with("payment_iban")->andReturn(null);
        $currentSite->expects("getOption")->with("payment_check")->andReturn(1);
        $currentSite->expects("getOption")->with("name_for_check_payment")->andReturn("L’ordre");
        $templateService = Helpers::getTemplateService();
        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        // when
        $response = $controller->selectMethodAction($paymentService, $config, $currentSite, $templateService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Chèque", $response->getContent());
        $this->assertStringContainsString("L’ordre", $response->getContent());
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
            "error", '{"message":"message","details":"details"}'
        );

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);
        $paymentService->expects("createPayplugPaymentForOrder")->with($order)
            ->andThrows($badRequestException);
        $loggerService = Mockery::mock(LoggerService::class);
        $loggerService->expects("log")->with("payplug", "message", "details");
        $flashMessagesService = Mockery::mock(FlashMessagesService::class);
        $flashMessagesService->expects("add")
            ->with("error", "Une erreur est survenue lors de la création du paiement via PayPlug : message");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->with("payment", ["slug" => $order->getSlug()])
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
}
