<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
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

require_once __DIR__ . "/../../../tests/setUp.php";

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

        $site = ModelFactory::createSite();
        ModelFactory::createImage(type: 'cover', fileSize: 99999999);
        ModelFactory::createImage(site: $site, type: 'photo', fileSize: 99999999);
        ModelFactory::createImage(type: 'other', fileSize: 99999999);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin")->andReturns();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturns($site);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("renderResponse")->andReturns(new Response("response"));

        // when
        $response = $controller->diskUsageAction($currentUser, $currentSite, $templateService);

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $currentUser->shouldHaveReceived("authAdmin")->withNoArgs();
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("renderResponse")
            ->with("AppBundle:Maintenance:disk-usage.html.twig", [
                "articlesCount" => 1,
                "articlesSize" => 0.093,
                "stockItemsCount" => 1,
                "stockItemsSize" => 0.093,
                "totalCount" => 2,
                "totalSize" => 0.186,
            ]);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("response", $response->getContent());
    }
}
