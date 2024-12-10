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

use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\QueryParamsService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Mockery;
use Model\RedirectionQuery;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class RedirectionControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        RedirectionQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     * @throws Exception
     * @throws \Exception
     */
    public function testIndexAction()
    {
        // given
        $controller = new RedirectionController();

        $site = ModelFactory::createSite();
        ModelFactory::createRedirection(site: $site, oldUrl: "/old-url", newUrl: "/new-url");

        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("authAdmin");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn($site->getId());
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->indexAction($currentUser, $currentSite, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Redirections", $response->getContent());
        $this->assertStringContainsString("/old-url", $response->getContent());
        $this->assertStringContainsString("/new-url", $response->getContent());
    }

    /**
     * @throws PropelException
     * @throws Exception
     * @throws \Exception
     */
    public function testCreateAction()
    {
        // given
        $controller = new RedirectionController();

        $site = ModelFactory::createSite();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("authAdmin");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn($site->getId());
        $bodyParamsService = Mockery::mock(BodyParamsService::class);
        $bodyParamsService->expects("parse");
        $bodyParamsService->expects("get")->with("old_url")->andReturn("/old-url");
        $bodyParamsService->expects("get")->with("new_url")->andReturn("/new-url");
        $flashMessageService = Mockery::mock(FlashMessagesService::class);
        $flashMessageService
            ->expects("add")
            ->with("success", 'La redirection de « /old-url » vers « /new-url » a été créée.');
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->with("redirection_index")->andReturn("/redirections");

        // when
        $response = $controller->createAction(
            $currentUser,
            $currentSite,
            $bodyParamsService,
            $flashMessageService,
            $urlGenerator
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $redirectionWasCreated = RedirectionQuery::create()
            ->filterBySiteId($currentSite->getId())
            ->filterByOldUrl("/old-url")
            ->filterByNewUrl("/new-url")
            ->exists();
        $this->assertTrue($redirectionWasCreated);
    }


    /**
     * @throws PropelException
     * @throws Exception
     * @throws \Exception
     */
    public function testCreateActionWithExistingUrl()
    {
        // given
        $controller = new RedirectionController();

        $site = ModelFactory::createSite();
        $redirection = ModelFactory::createRedirection(site: $site, oldUrl: "/old-url", newUrl: "/new-url");
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("authAdmin");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn($site->getId());
        $bodyParamsService = Mockery::mock(BodyParamsService::class);
        $bodyParamsService->expects("parse");
        $bodyParamsService->expects("get")->with("old_url")->andReturn("/old-url");
        $bodyParamsService->expects("get")->with("new_url")->andReturn("/other-new-url");
        $flashMessageService = Mockery::mock(FlashMessagesService::class);
        $flashMessageService
            ->expects("add")
            ->with("success", 'La redirection de « /old-url » vers « /other-new-url » a été créée.');
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->with("redirection_index")->andReturn("/redirections");

        // when
        $response = $controller->createAction(
            $currentUser,
            $currentSite,
            $bodyParamsService,
            $flashMessageService,
            $urlGenerator
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $redirection->reload();
        $this->assertEquals("/other-new-url", $redirection->getNewUrl());
    }

    /**
     * @throws PropelException
     * @throws Exception
     * @throws \Exception
     */
    public function testDeleteAction()
    {
        // given
        $controller = new RedirectionController();
        $site = ModelFactory::createSite();
        $redirection = ModelFactory::createRedirection(site: $site, oldUrl: "/old-url", newUrl: "/new-url");

        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("authAdmin");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn($site->getId());
        $flashMessageService = Mockery::mock(FlashMessagesService::class);
        $flashMessageService
            ->expects("add")
            ->with("success", 'La redirection de « /old-url » vers « /new-url » a bien été supprimée.');
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->with("redirection_index")->andReturn("/redirections");

        // when
        $response = $controller->deleteAction(
            $currentUser,
            $currentSite,
            $flashMessageService,
            $urlGenerator,
            $redirection->getId(),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $redirectionWasDeleted = !RedirectionQuery::create()
            ->filterBySiteId($site->getId())
            ->filterByOldUrl("/old-url")
            ->filterByNewUrl("/new-url")
            ->exists();
        $this->assertTrue($redirectionWasDeleted);
    }
}
