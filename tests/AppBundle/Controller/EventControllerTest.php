<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../setUp.php";

class EventControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testShowAction()
    {
        // givens
        $controller = new EventController();
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site);

        $request = Mockery::mock(Request::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getUser")->andThrow(new UnauthorizedHttpException(""));
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getId")->andReturn($site->getId());
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")->andReturn(new Response("Event"));

        // when
        $response = $controller->showAction($request, $currentUser, $currentSite, $templateService, $event->getUrl());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Event", $response->getContent());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testShowActionForOfflinePostAndAnonymousUser()
    {
        // given
        $controller = new EventController();
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site, status: false);

        $request = Mockery::mock(Request::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getId")->andReturn($site->getId());
        $templateService = Mockery::mock(TemplateService::class);

        // when
        $error = Helpers::runAndCatchException(fn() =>
            $controller->showAction($request, $currentUser, $currentSite, $templateService, $event->getUrl())
        );

        // then
        $this->assertInstanceOf(NotFoundHttpException::class, $error);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testShowActionForOfflinePostAndSimpleUser()
    {
        // given
        $controller = new EventController();
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site, status: false);

        $request = Mockery::mock(Request::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthentified")->andReturn(true);
        $currentUser->shouldReceive("hasPublisherRight")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getId")->andReturn($site->getId());
        $templateService = Mockery::mock(TemplateService::class);

        // when
        $error = Helpers::runAndCatchException(fn() =>
            $controller->showAction($request, $currentUser, $currentSite, $templateService, $event->getUrl())
        );

        // then
        $this->assertInstanceOf(NotFoundHttpException::class, $error);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testShowActionForOfflinePostAndAdminUser()
    {
        // given
        $controller = new EventController();
        $publisher = ModelFactory::createPublisher();
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site, publisher: $publisher, status: false);

        $request = Mockery::mock(Request::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthentified")->andReturn(true);
        $currentUser->shouldReceive("hasPublisherRight")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(true);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getId")->andReturn($site->getId());
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")->andReturn(new Response("Event"));

        // when
        $response = $controller->showAction($request, $currentUser, $currentSite, $templateService, $event->getUrl());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Event", $response->getContent());
    }
}
