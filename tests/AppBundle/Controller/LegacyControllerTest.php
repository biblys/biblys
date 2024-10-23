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

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once(__DIR__."/../../setUp.php");

class LegacyControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDefaultAction()
    {
        // given
        $request = new Request();
        $request->query->set("page", "bientot");
        $session = new Session();
        $mailer = Mockery::mock(Mailer::class);
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->with("AppBundle:Legacy:default.html.twig", [
                "title" => null,
                "content" => '<p>Bientôt…</p>',
            ])
            ->andReturn(new Response("bientôt"));

        // when
        $response = $legacyController->defaultAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            metaTagsService: $metaTagsService,
        );

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "responds with status code 200"
        );
        $this->assertStringContainsString(
            "bientôt",
            $response->getContent(),
            "renders the bientot template"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDefaultActionRequiringLogin()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise");

        // given
        $request = new Request();
        $request->query->set("page", "log_page");
        $session = new Session();
        $mailer = Mockery::mock(Mailer::class);
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTagsService,
            request: $request,
        );

        // when
        $legacyController->defaultAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            metaTagsService: $metaTagsService,
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDefaultActionRequiringPublisherRight()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Vous n'avez pas le droit de gérer une maison d'édition.");

        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $request->query->set("page", "pub_page");
        $session = new Session();
        $mailer = Mockery::mock(Mailer::class);
        $legacyController = new LegacyController();
        $config = new Config(["site" => $site->getId()]);
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTagsService,
            request: $request,
        );

        // when
        $legacyController->defaultAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            metaTagsService: $metaTagsService,
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDefaultActionRequiringAdminRight()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Accès réservé aux administrateurs.");

        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createPublisherUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $request->query->set("page", "adm_page");
        $session = new Session();
        $mailer = Mockery::mock(Mailer::class);
        $legacyController = new LegacyController();
        $config = new Config(["site" => $site->getId()]);
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTagsService,
            request: $request,
        );

        // when
        $legacyController->defaultAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            metaTagsService: $metaTagsService,
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDefaultActionForStaticPagesLegacyRoute()
    {
        // given
        $request = new Request();
        ModelFactory::createPage(["page_url" => "page-statique"]);
        $request->query->set("page", "page-statique");
        $session = new Session();
        $mailer = Mockery::mock(Mailer::class);
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("static_page_show", ["slug" => "page-statique"])
            ->willReturn("/page/page-statique");
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTagsService,
            request: $request,
        );

        // when
        $response = $legacyController->defaultAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            metaTagsService: $metaTagsService,
        );

        // then
        $this->assertEquals(
            "301",
            $response->getStatusCode(),
            "responds with status code 301"
        );
        $this->assertEquals(
            "/page/page-statique",
            $response->headers->get("location"),
            "redirects to new static page url"
        );
    }
}
