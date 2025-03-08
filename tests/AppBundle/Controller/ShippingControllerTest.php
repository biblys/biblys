<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Test\Helpers;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShippingControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws Exception
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws \Exception
     */
    public function testOptionsAction()
    {
        // given
        $controller = new ShippingController();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->expects($this->once())->method("authAdmin");
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->optionsAction($currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws SyntaxError
     * @throws Exception
     * @throws RuntimeError
     * @throws LoaderError
     * @throws \Exception
     */
    public function testCountriesAction()
    {
        // given
        $controller = new ShippingController();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->expects($this->once())->method("authAdmin");
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->countriesAction($currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("France", $response->getContent());
    }
}
