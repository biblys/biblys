<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../../setUp.php";

class OrderControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testShowForAdmin()
    {
        // given
        $site = ModelFactory::createSite();
        $order = ModelFactory::createOrder(site: $site);
        $controller = new OrderController();
        $currentSite = new CurrentSite($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->show($currentSite, $currentUser, $order->getId());

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/order/order-slug", $response->getTargetUrl());
    }
}
