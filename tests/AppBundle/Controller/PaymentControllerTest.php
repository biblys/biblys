<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use DateTime;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class PaymentControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testIndex()
    {
        // given
        $controller = new PaymentController();
        $request = RequestFactory::createAuthRequest();
        $currentSite = $this->createMock(CurrentSite::class);

        // then
        $this->expectException(AuthException::class);

        // when
        $controller->index($request, $currentSite);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws AuthException
     */
    public function testIndexForAdmin()
    {
        // given
        $controller = new PaymentController();
        $request = RequestFactory::createAuthRequestForAdminUser();
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
        $otherSite = ModelFactory::createSite();
        ModelFactory::createPayment(
            ["executed" => new DateTime(), "mode" => "from other site"],
            $otherSite
        );
        ModelFactory::createPayment(["executed" => null, "mode" => "not executed"], $site);

        // when
        $response = $controller->index($request, $currentSite);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Paiements", $response->getContent());
        $this->assertStringContainsString($today->format("Y-m-d"), $response->getContent());
        $this->assertStringContainsString($order->getId(), $response->getContent());
        $this->assertStringContainsString("stripe", $response->getContent());
        $this->assertStringContainsString("100,00&nbsp;&euro;", $response->getContent());
        $this->assertStringNotContainsString("from other site", $response->getContent());
        $this->assertStringNotContainsString("not executed", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws AuthException
     */
    public function testIndexWithModeFilter()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getSite")->willReturn($site);

        $orderPayedUsingStripe = ModelFactory::createOrder();
        ModelFactory::createPayment(["mode" => "stripe"], $site, $orderPayedUsingStripe);
        $orderPayedUsingPaypal = ModelFactory::createOrder();
        ModelFactory::createPayment(["mode" => "paypal"], $site, $orderPayedUsingPaypal);

        $controller = new PaymentController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->query->set("mode", "stripe");

        // when
        $response = $controller->index($request, $currentSite);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString($orderPayedUsingStripe->getId(), $response->getContent());
        $this->assertStringNotContainsString(
            $orderPayedUsingPaypal->getId(), $response->getContent()
        );
    }

    /**
     * @throws AuthException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws SyntaxError
     * @throws PropelException
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
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->query->set("start_date", "2019-04-27");
        $request->query->set("end_date", "2019-04-29");

        // when
        $response = $controller->index($request, $currentSite);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("28/04/2019", $response->getContent());
        $this->assertStringNotContainsString("26/04/2019", $response->getContent());
        $this->assertStringNotContainsString("30/04/2019", $response->getContent());
    }

}
