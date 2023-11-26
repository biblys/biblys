<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

require_once __DIR__."/../../setUp.php";

class OrderControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testShowForUnauthorizedUser()
    {
        // given
        $request = RequestFactory::createAuthRequest();
        $currentSite = $this->createMock(CurrentSite::class);
        $controller = new OrderController();


        // then
        $this->expectException(AccessDeniedHttpException::class);

        // when
        $controller->show($request, $currentSite, 1);
    }

    /**
     * @throws PropelException
     */
    public function testShowForAdmin()
    {
        // given
        $site = ModelFactory::createSite();
        $order = ModelFactory::createOrder(site: $site);
        $request = RequestFactory::createAuthRequestForAdminUser();
        $controller = new OrderController();
        $currentSite = new CurrentSite($site);

        // when
        $response = $controller->show($request, $currentSite, $order->getId());

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/order/order-slug", $response->getTargetUrl());
    }
}
