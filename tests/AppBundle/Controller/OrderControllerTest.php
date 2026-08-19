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
        $site->shouldReceive("getTva")->andReturn(1);
        $site->shouldReceive("getAddress")->andReturn("1 rue du Livre|33000 Bordeaux");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getTitle")->andReturn("Ma librairie");
        $currentSite->shouldReceive("getOption")->with("invoice_notice")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("siren")->andReturn(null);
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
        $this->assertStringContainsString("Silas", $content, "Le prénom du client (fixture par défaut) doit apparaître sur la facture");
        $this->assertStringNotContainsString("<em>de", $content, "Les auteurs ne doivent plus être affichés sur la facture");
        $this->assertStringNotContainsString("OverallMenu", $content, "La facture doit utiliser un layout minimal, sans le chrome du site");
        $this->assertStringNotContainsString("Ref.", $content, "La colonne Ref. doit avoir été supprimée");
        $this->assertStringContainsString("Port", $content, "La ligne de port doit être affichée quand des frais existent (500 c)");
    }

    public function testInvoiceActionDisplaysSirenWhenSiteOptionIsSet(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-siren", amount: 2000, shippingCost: 0);
        $article = ModelFactory::createArticle(title: "Le Livre Test");
        ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000);

        $site = Mockery::mock(Site::class);
        $site->shouldReceive("getTva")->andReturn(1);
        $site->shouldReceive("getAddress")->andReturn("1 rue du Livre|33000 Bordeaux");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getTitle")->andReturn("Ma librairie");
        $currentSite->shouldReceive("getOption")->with("invoice_notice")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("siren")->andReturn("123 456 789");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-siren");
        $content = $response->getContent();

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("SIREN : 123 456 789", $content);
    }

    public function testInvoiceActionHidesSirenWhenSiteOptionIsNotSet(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-no-siren", amount: 2000, shippingCost: 0);
        $article = ModelFactory::createArticle(title: "Le Livre Test");
        ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-no-siren");
        $content = $response->getContent();

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringNotContainsString("SIREN", $content);
    }

    public function testInvoiceActionHidesConditionColumnWhenAllArticlesAreNew(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-all-new", amount: 2000, shippingCost: 0);
        $article = ModelFactory::createArticle(title: "Le Livre Test");
        ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-all-new");
        $content = $response->getContent();

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringNotContainsString("État", $content, "La colonne État doit être masquée quand tous les articles sont Neuf");
    }

    public function testInvoiceActionShowsConditionColumnWhenAnArticleIsNotNew(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-mixed-1", amount: 2000, shippingCost: 0);
        $article = ModelFactory::createArticle(title: "Le Livre Test");
        $stock = ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000);
        $stock->setCondition("Occasion");
        $stock->save();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-mixed-1");
        $content = $response->getContent();

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("État", $content, "La colonne État doit être affichée quand un article n'est pas Neuf");
    }

    public function testInvoiceActionHidesShippingLineWhenFree(): void
    {
        // given
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-freeship", amount: 2000, shippingCost: 0);
        $article = ModelFactory::createArticle(title: "Le Livre Test");
        ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-freeship");
        $content = $response->getContent();

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringNotContainsString("Port", $content, "La ligne de port doit être masquée quand elle est gratuite (0 €)");
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

    public function testInvoiceActionShowsVatBreakdownByRate(): void
    {
        // given : une commande mélangeant un livre (5,5 %) et un article standard (20 %)
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-vat-mix", amount: 3000, shippingCost: 0);
        $book = ModelFactory::createArticle(title: "Le Livre Test");
        $standardItem = ModelFactory::createArticle(title: "Un Objet Dérivé");

        $bookStock = ModelFactory::createStockItem(article: $book, order: $order, sellingPrice: 2000);
        $bookStock->setSellingPriceHt(1896)->setSellingPriceTva(104)->setTvaRate(5.5)->save();

        $standardStock = ModelFactory::createStockItem(article: $standardItem, order: $order, sellingPrice: 1000);
        $standardStock->setSellingPriceHt(833)->setSellingPriceTva(167)->setTvaRate(20)->save();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-vat-mix");
        $content = $response->getContent();

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("5,5 %", $content, "Le taux de TVA du livre doit apparaître dans la ventilation");
        $this->assertStringContainsString("20 %", $content, "Le taux de TVA de l'article standard doit apparaître dans la ventilation");
    }

    public function testInvoiceActionGroupsUnknownVatRate(): void
    {
        // given : un exemplaire vendu sans taux de TVA renseigné (cas legacy),
        // accompagné d'un article à taux connu pour que le bloc de ventilation s'affiche
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-vat-unk", amount: 4000, shippingCost: 0);
        $article = ModelFactory::createArticle(title: "Le Livre Test");
        $stock = ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000);
        $stock->setSellingPriceHt(0)->setSellingPriceTva(0)->setTvaRate(null)->save();

        $knownRateArticle = ModelFactory::createArticle(title: "Un Objet Dérivé");
        $knownRateStock = ModelFactory::createStockItem(article: $knownRateArticle, order: $order, sellingPrice: 2000);
        $knownRateStock->setSellingPriceHt(1667)->setSellingPriceTva(333)->setTvaRate(20)->save();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-vat-unk");
        $content = $response->getContent();

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Taux inconnu", $content, "Un taux de TVA non renseigné doit être regroupé sous « Taux inconnu »");
    }

    public function testInvoiceActionAllocatesShippingVatForSingleRate(): void
    {
        // given : une commande mono-taux (20 %) avec des frais de port
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "inv-ship-single", amount: 2000, shippingCost: 600);
        $article = ModelFactory::createArticle(title: "Un Objet Dérivé");
        $stock = ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000);
        $stock->setSellingPriceHt(1667)->setSellingPriceTva(333)->setTvaRate(20)->save();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "inv-ship-single");
        $content = $response->getContent();

        // then : le port apparaît décomposé sous son propre taux, plus de tirets sur sa ligne,
        // et le total TVA global inclut désormais la TVA du port (333 articles + 100 port = 433 c)
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringNotContainsString('<td class="text-right align">—</td>', $content);
        // currency() rend l'entité HTML &euro;, pas le glyphe € directement
        $this->assertStringContainsString("4,33&nbsp;&euro;", $content, "La TVA totale (article + port) doit apparaître dans le pied de facture");
    }

    public function testInvoiceActionAllocatesShippingVatAcrossMultipleRates(): void
    {
        // given : livre (5,5 %) + article standard (20 %), port 600 c
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "inv-ship-multi", amount: 3000, shippingCost: 600);
        $book = ModelFactory::createArticle(title: "Le Livre Test");
        $standardItem = ModelFactory::createArticle(title: "Un Objet Dérivé");

        $bookStock = ModelFactory::createStockItem(article: $book, order: $order, sellingPrice: 2000);
        $bookStock->setSellingPriceHt(1896)->setSellingPriceTva(104)->setTvaRate(5.5)->save();

        $standardStock = ModelFactory::createStockItem(article: $standardItem, order: $order, sellingPrice: 1000);
        $standardStock->setSellingPriceHt(833)->setSellingPriceTva(167)->setTvaRate(20)->save();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "inv-ship-multi");
        $content = $response->getContent();

        // then : la ligne de port affiche un taux recalculé (marqué d'une étoile), obtenu
        // en agrégeant les parts HT/TVA du port réparties au prorata (395+153 c HT,
        // 22+30 c TVA sur 600 c TTC) puis TVA ÷ HT = 52/548 ≈ 9,5 %, plus une notice
        // explicative en pied de page
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("9,5 %*", $content, "Le taux recalculé du port doit être marqué d'une étoile");
        $this->assertStringContainsString("5,48&nbsp;&euro;", $content, "HT agrégé du port (395 + 153 c)");
        $this->assertStringContainsString(
            "les frais de port ont été",
            $content,
            "La notice explicative du taux recalculé doit apparaître en pied de page"
        );
    }

    public function testInvoiceActionHidesVatColumnsWhenSiteHasNoVat(): void
    {
        // given : un site non soumis à la TVA (art. 293 B du CGI), commande avec port
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-no-vat", amount: 2000, shippingCost: 600);
        $article = ModelFactory::createArticle(title: "Un Objet Dérivé");
        $stock = ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000);
        $stock->setSellingPriceHt(2000)->setSellingPriceTva(0)->setTvaRate(null)->save();

        $site = Mockery::mock(Site::class);
        $site->shouldReceive("getShop")->andReturn(false);
        $site->shouldReceive("getTva")->andReturn(null);
        $site->shouldReceive("getAddress")->andReturn("1 rue du Livre|33000 Bordeaux");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getTitle")->andReturn("Ma librairie");
        $currentSite->shouldReceive("getOption")->with("invoice_notice")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("siren")->andReturn(null);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-no-vat");
        $content = $response->getContent();

        // then : les colonnes Taux/Prix HT/TVA sont masquées, seul le prix reste affiché
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringNotContainsString('<th class="text-right">Taux</th>', $content);
        $this->assertStringNotContainsString('<th class="text-right">Prix HT</th>', $content);
        $this->assertStringContainsString('<th class="text-right">Prix TTC</th>', $content);
        $this->assertStringContainsString(
            "TVA non applicable en application de l'article 293 B du CGI.",
            $content
        );
    }

    public function testInvoiceActionHidesVatColumnsWhenOrderHasNoVatEvenOnVatLiableSite(): void
    {
        // given : site assujetti à la TVA (getTva() = 1), mais commande sans aucune TVA
        // (ex. articles à taux 0) — les colonnes doivent se baser sur la commande, pas sur
        // le site, tandis que la mention 293B (statut fiscal du vendeur) ne doit pas apparaître
        $controller = new OrderController();
        $request = new Request();
        $order = ModelFactory::createOrder(slug: "invoice-0-vat", amount: 2000, shippingCost: 0);
        $article = ModelFactory::createArticle(title: "Le Livre Test");
        $stock = ModelFactory::createStockItem(article: $article, order: $order, sellingPrice: 2000);
        $stock->setSellingPriceHt(2000)->setSellingPriceTva(0)->setTvaRate(null)->save();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = $this->_mockCurrentSiteForInvoice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->invoiceAction($request, $currentUser, $currentSite, $templateService, "invoice-0-vat");
        $content = $response->getContent();

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringNotContainsString('<th class="text-right">Taux</th>', $content, "Les colonnes TVA doivent être masquées car la commande n'a aucune TVA");
        $this->assertStringNotContainsString('<th class="text-right">Prix HT</th>', $content);
        $this->assertStringNotContainsString(
            "TVA non applicable en application de l'article 293 B du CGI.",
            $content,
            "Le site est assujetti à la TVA : la mention 293B ne doit pas apparaître malgré une commande sans TVA"
        );
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
