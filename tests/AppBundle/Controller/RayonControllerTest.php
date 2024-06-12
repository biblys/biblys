<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use AppBundle\Controller\RayonController;
use Biblys\Test\Factory;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../setUp.php";

class RayonControllerTest extends PHPUnit\Framework\TestCase
{
    public function testRayonShow()
    {
        // given
        $rayon = Factory::createRayon();
        $controller = new RayonController();
        $request = new Request();
        $request->query->set("p", 3);

        // when
        $response = $controller->showAction($request, $rayon->get("url"));

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            $rayon->get("name"),
            $response->getContent(),
            "it should contain rayon name"
        );
    }

    public function testRayonShowWithInvalidPageNumber()
    {
        // then
        $this->expectException("Symfony\Component\HttpKernel\Exception\BadRequestHttpException");
        $this->expectExceptionMessage("Page number must be a positive integer");

        // given
        $rayon = Factory::createRayon();
        $controller = new RayonController();
        $request = new Request();
        $request->query->set("p", -1690);

        // when
        $controller->showAction($request, $rayon->get("url"));
    }
}