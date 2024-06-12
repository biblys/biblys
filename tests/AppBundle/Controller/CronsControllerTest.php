<?php

namespace AppBundle\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CronsControllerTest extends TestCase
{
    public function testExportPdlAction()
    {
        // given
        $controller = new CronsController();
        $request = new Request();
        $request->headers->set("X-CRON-KEY", "CRONKEY");

        // when
        $response = $controller->exportPdlAction($request);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with HTTP 200"
        );
    }
}