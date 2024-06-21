<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../../tests/setUp.php";

class MaintenanceControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testDiskUsageAction(): void
    {
        // given
        $controller = new MaintenanceController();

        ModelFactory::createImage(type: 'cover', fileSize: 99999999);
        ModelFactory::createImage(type: 'photo', fileSize: 99999999);
        ModelFactory::createImage(type: 'other', fileSize: 1);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin")->andReturns();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("renderResponse")->andReturns(new Response("response"));

        // when
        $response = $controller->diskUsageAction($currentUser, $templateService);

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $currentUser->shouldHaveReceived("authAdmin")->withNoArgs();
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("renderResponse")
            ->with("AppBundle:Maintenance:disk-usage.html.twig", [
                "articles" => 0.093,
                "total" => 0.186
            ]);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("response", $response->getContent());
    }
}
