<?php

namespace Legacy;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__."/../setUp.php";


class ShippingTest extends TestCase
{
    public function testGettingFees()
    {
        // given
        $request = new Request();
        $request->query->set("country_id", 67);
        $request->query->set("order_weight", 1500);
        $request->query->set("order_amount", 2295);

        // when
        /** @var Response $response */
        $response = require __DIR__."/../../controllers/common/php/shipping.php";

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with 200"
        );
    }

    public function testGettingFeesWithMissingParameters()
    {
        // given
        $request = new Request();
        $request->query->set("country_id", 67);

        // when
        /** @var Response $response */
        $response = require __DIR__."/../../controllers/common/php/shipping.php";

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with 200"
        );
    }
}