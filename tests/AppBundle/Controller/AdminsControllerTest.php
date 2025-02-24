<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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
use Biblys\Service\Mailer;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\RightQuery;
use Model\UserQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminsControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        RightQuery::create()->deleteAll();
        UserQuery::create()->deleteAll();
    }

    /* addAction */

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function testAddAction(): void
    {
        // given
        $controller = new AdminsController();
        ModelFactory::createUser(email: "admin-to-add@example.org");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->newAction($currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("admin-to-add@example.org", $response->getContent());
    }

    /* createAction */

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testCreateActionForExistingUser(): void
    {
        // given
        $controller = new AdminsController();
        $site = ModelFactory::createSite();

        ModelFactory::createAdminUser(site: $site, email: "already-admin@example.org");
        $user = ModelFactory::createUser(site: $site, email: "new-admin@example.org");

        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->shouldReceive("parse")->with(["user_email" => ["type" => "string"]]);
        $bodyParams->shouldReceive("get")->with("user_email")->andReturn("new-admin@example.org");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate");
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->shouldReceive("add")->with(
            "success",
            "Un accès administrateur a été ajouté pour le compte new-admin@example.org."
        );
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->twice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->createAction($bodyParams, $currentUser, $currentSite, $urlGenerator, $flashMessages, $mailer, $templateService);

        // then
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertTrue(RightQuery::create()->isUserAdmin($user));
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testCreateActionForNewUser(): void
    {
        // given
        $controller = new AdminsController();
        $site = ModelFactory::createSite();

        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->shouldReceive("parse")->with(["user_email" => ["type" => "string"]]);
        $bodyParams->shouldReceive("get")->with("user_email")->andReturn("new-user@example.org");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate");
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->shouldReceive("add")->twice();
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->twice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->createAction($bodyParams, $currentUser, $currentSite, $urlGenerator, $flashMessages, $mailer, $templateService);

        // then
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $user = UserQuery::create()->findOneByEmail("new-user@example.org");
        $this->assertNotNull($user);
        $this->assertTrue(RightQuery::create()->isUserAdmin($user));
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testCreateActionFailsIfAdminAlreadyExists(): void
    {
        // given
        $controller = new AdminsController();
        $site = ModelFactory::createSite();

        $user = ModelFactory::createAdminUser(site: $site, email: "already-admin@example.org");

        $bodyParams = Mockery::mock(BodyParamsService::class);
        $bodyParams->shouldReceive("parse")->with(["user_email" => ["type" => "string"]]);
        $bodyParams->shouldReceive("get")->with("user_email")->andReturn("already-admin@example.org");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("http://example.org");
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->shouldReceive("add")->with(
            "error",
            "L'utilisateur already-admin@example.org a déjà un accès administrateur."
        );
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive("send")->twice();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->createAction($bodyParams, $currentUser, $currentSite, $urlGenerator, $flashMessages, $mailer, $templateService);

        // then
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertTrue(RightQuery::create()->isUserAdmin($user));
    }
}
