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

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Mockery;
use Model\OrderQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class PaymentControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        OrderQuery::create()->deleteAll();
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
        ModelFactory::createPayment([
            "mode" => "stripe",
            "amount" => "10000",
            "executed" => $today,
        ], $site, $order);
        ModelFactory::createPayment(["amount" => "300"], $site);
        ModelFactory::createPayment(["amount" => "900"], $site);
        $otherSite = ModelFactory::createSite();
        ModelFactory::createPayment(
            ["executed" => new DateTime(), "mode" => "from other site"],
            $otherSite
        );
        ModelFactory::createPayment(["executed" => null, "mode" => "not executed"], $site);
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
            ["mode" => "stripe", "date" => new DateTime("2001-01-01")],
            $site,
            $orderPayedUsingStripe
        );
        $orderPayedUsingPaypal = ModelFactory::createOrder();
        ModelFactory::createPayment(
            ["mode" => "paypal", "date" => new DateTime("2001-01-02")],
            $site,
            $orderPayedUsingPaypal
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

        ModelFactory::createPayment(["executed" => new DateTime("2019-04-26")], $site);
        ModelFactory::createPayment(["executed" => new DateTime("2019-04-28")], $site);
        ModelFactory::createPayment(["executed" => new DateTime("2019-04-30")], $site);

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
}
