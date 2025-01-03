<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


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
        $event = ModelFactory::createEvent(site: $site, isPublished: true);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getUser")->andThrow(new UnauthorizedHttpException(""));
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getId")->andReturn($site->getId());
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")->andReturn(new Response("Event"));

        // when
        $response = $controller->showAction($currentUser, $currentSite, $templateService, $event->getUrl());

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
        $event = ModelFactory::createEvent(site: $site, isPublished: false);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(false);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getId")->andReturn($site->getId());
        $templateService = Mockery::mock(TemplateService::class);

        // when
        $error = Helpers::runAndCatchException(fn() =>
            $controller->showAction( $currentUser, $currentSite, $templateService, $event->getUrl())
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
        $event = ModelFactory::createEvent(site: $site, isPublished: false);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(true);
        $currentUser->shouldReceive("hasPublisherRight")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getId")->andReturn($site->getId());
        $templateService = Mockery::mock(TemplateService::class);

        // when
        $error = Helpers::runAndCatchException(fn() =>
            $controller->showAction($currentUser, $currentSite, $templateService, $event->getUrl())
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
        $event = ModelFactory::createEvent(site: $site, publisher: $publisher, isPublished: false);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAuthenticated")->andReturn(true);
        $currentUser->shouldReceive("hasPublisherRight")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(true);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getId")->andReturn($site->getId());
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")->andReturn(new Response("Event"));

        // when
        $response = $controller->showAction($currentUser, $currentSite, $templateService, $event->getUrl());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Event", $response->getContent());
    }
}
