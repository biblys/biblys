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
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\OrderQuery;
use Model\Payment;
use Model\PaymentQuery;
use Model\Site;
use Model\User;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
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

    /**
     * @throws PropelException
     */
    public function testUpdateActionCancelIsBlockedWhenOrderHasPayments(): void
    {
        // given
        $controller = new OrderController();
        $order = ModelFactory::createOrder();
        ModelFactory::createPayment(order: $order, amount: 1000);

        $payload = json_encode(["payment_mode" => null, "tracking_number" => null]);
        $request = new Request(content: $payload);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $templateService = Mockery::mock(TemplateService::class);
        $mailer = Mockery::mock(Mailer::class);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Effectuez d'abord un remboursement.");

        // when
        $controller->updateAction(
            $request,
            $currentSite,
            $currentUser,
            $templateService,
            $mailer,
            urlGenerator: Mockery::mock(UrlGenerator::class),
            id: $order->getId(),
            action: "cancel",
        );
    }

    /**
     * @throws PropelException
     */
    public function testUpdateActionCancelIsAllowedWhenOrderHasNoPayments(): void
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
            action: "cancel",
        );

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $updatedOrder = OrderQuery::create()->findPk($order->getId());
        $this->assertNotNull($updatedOrder->getCancelDate());
    }

    private function _mockCurrentSiteForInvoice(): CurrentSite
    {
        $site = Mockery::mock(Site::class);
        $site->shouldReceive("getShop")->andReturn(false);
        $site->shouldReceive("getTva")->andReturn(1);
        $site->shouldReceive("getAddress")->andReturn("1 rue du Livre|33000 Bordeaux");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getTitle")->andReturn("Ma librairie");
        $currentSite->shouldReceive("getOption")->with("invoice_notice")->andReturn(null);
        return $currentSite;
    }

    public function testInvoiceActionForAnonymousOrder(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-anon-1"); // pas de user → anonyme
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-anon-1");

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Facture n° {$order->getId()}", $response->getContent());
    }

    public function testInvoiceActionThrowsNotFoundForUnknownOrder(): void
    {
        $controller = new OrderController();
        $request = new Request();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        $this->expectException(ResourceNotFoundException::class);
        $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "does-not-exist");
    }

    public function testInvoiceActionRendersOrderContent(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-content1", amount: 2000, shippingCost: 500);
        $article = ModelFactory::createArticle(title: "Le Livre Test");
        ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000, weight: 300);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-content1");
        $content = $response->getContent();

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Le Livre Test", $content);
        $this->assertStringContainsString("Silas", $content); // prénom client (fixture par défaut)
        $this->assertStringNotContainsString("<em>de", $content); // auteurs supprimés
        $this->assertStringContainsString("0,3", $content); // poids 300 g → 0,3 kg
        $this->assertStringNotContainsString("OverallMenu", $content); // pas de chrome du site (layout minimal)
    }

    public function testInvoiceActionRendersLineWithNullSellingPrice(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-null", amount: 0, shippingCost: 0);
        $article = ModelFactory::createArticle(title: "Le Livre Test");
        $stock = ModelFactory::createStockItem(article: $article, order: $order);
        $stock->setSellingPrice(null)->save();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-null");

        // then
        $this->assertEquals(200, $response->getStatusCode(), "Un prix de vente null ne doit pas lever d'exception currency(null)");
        $this->assertStringContainsString("Le Livre Test", $response->getContent());
    }

    public function testInvoiceActionRendersPaidOrderWithoutPaymentMode(): void
    {
        // given : commande réglée (date de paiement) mais sans mode de paiement
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-nomode", paymentDate: new \DateTime());
        $article = ModelFactory::createArticle(title: "Le Livre Test");
        ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-nomode");

        // then
        $this->assertEquals(200, $response->getStatusCode(), "Une commande réglée sans mode de paiement ne doit pas lever d'erreur ucwords(null)");
        $this->assertStringContainsString("Règlement effectué", $response->getContent());
    }

    public function testInvoiceActionThrowsAccessDeniedForOtherUser(): void
    {
        $controller = new OrderController();
        $request = new Request();
        $owner = ModelFactory::createUser(email: "owner-invoice@example.org");
        $order = ModelFactory::createOrder(user: $owner, slug: "invoice-owned-1");

        $intruder = ModelFactory::createUser(email: "intruder-invoice@example.org");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(true);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentUser->shouldReceive("getUser")->andReturn($intruder);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        $this->expectException(AccessDeniedHttpException::class);
        $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-owned-1");
    }
}
