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

use Biblys\Data\ArticleType;
use Biblys\Exception\InvalidConfigurationException;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\BodyParamsService;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\InvalidTokenException;
use Biblys\Service\Mailer;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Biblys\Service\TokenService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Mockery;
use Model\SessionQuery;
use Model\UserQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../setUp.php";

class UserControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testIndexAction()
    {
        // given
        $controller = new UserController();

        ModelFactory::createUser(email: "an-email-user@example.org");
        $ssoUser = ModelFactory::createUser(email: "a-sso-user@example.org");
        ModelFactory::createAuthenticationMethod(user: $ssoUser);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive('authAdmin')->andReturns();
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")->andReturns();
        $queryParamsService->expects("getInteger")->with("p")->andReturn(0);
        $queryParamsService->expects("get")->with("q")->andReturn("");
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->indexAction($currentUser, $queryParamsService,$templateService);

        // then
        $this->assertEquals("200", $response->getStatusCode());
        $this->assertStringContainsString("an-email-user@example.org", $response->getContent());
        $this->assertStringContainsString("axys", $response->getContent());
    }
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testIndexActionWithSearchQuery()
    {
        // given
        $controller = new UserController();

        ModelFactory::createUser(email: "the-user-i-m-looking-for@example.org");
        ModelFactory::createUser(email: "another-user@example.org");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive('authAdmin')->andReturns();
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")->andReturns();
        $queryParamsService->expects("getInteger")->with("p")->andReturn(0);
        $queryParamsService->expects("get")->with("q")->andReturn("looking-for");
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->indexAction($currentUser, $queryParamsService,$templateService);

        // then
        $this->assertEquals("200", $response->getStatusCode());
        $this->assertStringContainsString("the-user-i-m-looking-for@example.org", $response->getContent());
        $this->assertStringNotContainsString("another-user@example.org", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testShowAction()
    {
        // given
        $controller = new UserController();
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);
        $currentSite = new CurrentSite($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive('authAdmin')->andReturns();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")
            ->with("AppBundle:User:admin_informations.html.twig", [
                "user" => $user
            ], true)->andReturn(new Response());

        // when
        $response = $controller->showAction($currentSite, $currentUser, $templateService, $user->getId());

        // then
        $this->assertEquals("200", $response->getStatusCode());
    }

    /**
     * @throws PropelException
     */
    public function testDeleteAction(): void
    {
        // given
        $controller = new UserController();

        $userToDelete = ModelFactory::createUser(email: "user-to-delete@example.org");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->andReturns();
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->expects("add")->with(
            "success",
            "L'utilisateur user-to-delete@example.org a bien été supprimé."
        );
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->with("user_index")->andReturn("/admin/users");

        // when
        $response = $controller->deleteAction($currentUser, $urlGenerator, $flashMessages, $userToDelete->getId());

        // then
        $this->assertEquals("302", $response->getStatusCode());
        $this->assertEquals("/admin/users", $response->getTargetUrl());
        $this->assertTrue($userToDelete->isDeleted());
    }

    /**
     * @throws PropelException
     */
    public function testDeleteActionDisplayErrorWhenDeletionFails(): void
    {
        // given
        $controller = new UserController();

        $userToDelete = ModelFactory::createUser(email: "user-with-orders@example.org");
        ModelFactory::createOrder(user: $userToDelete);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->andReturns();
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->expects("add")->with(
            "error",
            "Impossible de supprimer le compte user-with-orders@example.org car il a des commandes."
        );
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->with("admin_user_informations", ["id" => $userToDelete->getId()])
            ->andReturn("/users/123");

        // when
        $response = $controller->deleteAction($currentUser, $urlGenerator, $flashMessages, $userToDelete->getId());

        // then
        $this->assertEquals("302", $response->getStatusCode());
        $this->assertEquals("/users/123", $response->getTargetUrl());
        $this->assertFalse($userToDelete->isDeleted());
    }

    /**
     * #login
     */

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testLogin()
    {
        // given
        $userController = new UserController();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("isAuthenticated")->willReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("openid_axys")
            ->willReturn("/openid/axys");
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->andReturn("");
        $queryParams->expects("get")->with("return_url")->andReturn("");
        $config = Mockery::mock(Config::class);
        $config->expects("isAxysEnabled")->andReturns(false);

        // when
        $response = $userController->login($queryParams, $currentUser, $urlGenerator, $config);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("S'identifier par e-mail", $response->getContent());
        $this->assertStringNotContainsString("Se connecter avec Axys", $response->getContent());
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testLoginWhenAxysIsEnabled()
    {
        // given
        $userController = new UserController();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("isAuthenticated")->willReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("openid_axys")
            ->willReturn("/openid/axys");
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->andReturn("");
        $queryParams->expects("get")->with("return_url")->andReturn("");
        $config = Mockery::mock(Config::class);
        $config->expects("isAxysEnabled")->andReturns(true);

        // when
        $response = $userController->login($queryParams, $currentUser, $urlGenerator, $config);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("S'identifier par e-mail", $response->getContent());
        $this->assertStringContainsString("Se connecter avec Axys", $response->getContent());
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testLoginWithReturnUrl()
    {
        // given
        $userController = new UserController();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("isAuthenticated")->willReturn(false);
        $urlGenerator
            ->method("generate")
            ->with("openid_axys", ["return_url" => "url_to_return_to"])
            ->willReturn("/openid/axys?return_url=url_to_return_to");
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->andReturn("");
        $queryParams->expects("get")->with("return_url")->andReturn("url_to_return_to");
        $config = Mockery::mock(Config::class);
        $config->expects("isAxysEnabled")->andReturns(false);

        // when
        $response = $userController->login($queryParams, $currentUser, $urlGenerator, $config);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("url_to_return_to", $response->getContent());
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testLoginForAuthenticatedUser()
    {
        // given
        $userController = new UserController();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("isAuthenticated")->willReturn(true);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("main_home")
            ->willReturn("/");
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->andReturn("");
        $queryParams->expects("get")->with("return_url")->andReturn("");
        $config = Mockery::mock(Config::class);
        $config->expects("isAxysEnabled")->andReturns(false);

        // when
        $response = $userController->login($queryParams, $currentUser, $urlGenerator, $config);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(
            "/",
            $response->headers->get("Location"),
        );
    }

    /** #sendLoginEmail */

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function testSendLoginEmailWhenEmailIsInvalid()
    {
        // given
        $controller = new UserController();
        $expectedResponse = new Response();
        $site = ModelFactory::createSite(contact: "editions@paronymie.fr");
        $currentSite = new CurrentSite($site);
        $request = new Request();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")
            ->andReturn($expectedResponse);
        $templateService->shouldReceive("render")->andReturn("mail body");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("createLoginToken")->andReturn("token");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->andReturn("login_url");
        $mailer = Mockery::mock(Mailer::class);
        $mailer->expects("validateEmail")->andThrow(
            new InvalidEmailAddressException("L'adresse invalid-email est invalide.")
        );
        $bodyParamsService = Mockery::mock(BodyParamsService::class);
        $bodyParamsService->expects("parse")->andReturns();
        $bodyParamsService->expects("get")->with("email")->andReturn("invalid-email");
        $bodyParamsService->expects("get")->with("return_url")->andReturn("/continue");
        $bodyParamsService->expects("get")->with("username")->andReturn("");

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("L'adresse invalid-email est invalide.");

        // when
        $controller->sendLoginEmailAction(
            $request,
            $bodyParamsService,
            $currentSite,
            $tokenService,
            $templateService,
            $urlGenerator,
            $mailer,
        );
    }

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function testSendLoginEmailWhenEmailDoesNotExist()
    {
        // given
        $controller = new UserController();
        $expectedResponse = new Response();
        $site = ModelFactory::createSite(contact: "editions@paronymie.fr");
        $currentSite = new CurrentSite($site);
        $request = new Request();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")
            ->andReturn($expectedResponse);
        $templateService->shouldReceive("render")->andReturn("mail body");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("createLoginToken")->andReturn("token");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->andReturn("login_url");
        $mailer = Mockery::mock(Mailer::class);
        $mailer->expects("validateEmail");
        $mailer->expects("send");

        $bodyParamsService = Mockery::mock(BodyParamsService::class);
        $bodyParamsService->expects("parse")->andReturns();
        $bodyParamsService->expects("get")->with("email")->andReturn("user@example.net");
        $bodyParamsService->expects("get")->with("return_url")->andReturn("/continue");
        $bodyParamsService->expects("get")->with("username")->andReturn("");

        // when
        $returnedResponse = $controller->sendLoginEmailAction(
            $request,
            $bodyParamsService,
            $currentSite,
            $tokenService,
            $templateService,
            $urlGenerator,
            $mailer,
        );

        // then
        $tokenService->shouldReceive("createLoginToken")
            ->with("user@example.net", "signup-by-email", "/continue");
        $mailer->shouldHaveReceived("send");
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("render")
            ->with("AppBundle:User:signup-by-email-email.html.twig", Mockery::any());
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("renderResponse")
            ->with("AppBundle:User:send-login-email.html.twig", [
                "recipientEmail" => "user@example.net",
                "returnUrl" => "/continue",
                "senderEmail" => "editions@paronymie.fr",
            ]);
        $this->assertEquals($expectedResponse, $returnedResponse);
    }

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function testSendLoginEmailWhenHoneyPotIsFilled()
    {
        // given
        $controller = new UserController();
        $expectedResponse = new Response();
        $site = ModelFactory::createSite(contact: "editions@paronymie.fr");
        $currentSite = new CurrentSite($site);
        $request = new Request();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")
            ->andReturn($expectedResponse);
        $templateService->shouldReceive("render")->andReturn("mail body");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("createLoginToken")->andReturn("token");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->andReturn("login_url");
        $mailer = Mockery::mock(Mailer::class);
        $mailer->expects("validateEmail");
        $mailer->expects("send");
        $bodyParamsService = Mockery::mock(BodyParamsService::class);
        $bodyParamsService->expects("parse")->andReturns();
        $bodyParamsService->expects("get")->with("email")->andReturn("user@example.net");
        $bodyParamsService->expects("get")->with("return_url")->andReturn("/continue");
        $bodyParamsService->expects("get")->with("username")->andReturn("honeypot");

        // when
        $response = $controller->sendLoginEmailAction(
            $request,
            $bodyParamsService,
            $currentSite,
            $tokenService,
            $templateService,
            $urlGenerator,
            $mailer,
        );

        // then
        $mailer->shouldNotHaveReceived("send");
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws InvalidConfigurationException
     */
    public function testSendLoginEmailWhenEmailExists()
    {
        // given
        $controller = new UserController();
        $expectedResponse = new Response();
        $expectedMailBody = new Response();
        $site = ModelFactory::createSite(
            title: "Paronymie Login",
            domain: "login.paronymie.fr",
            contact: "editions@paronymie.fr",
        );
        $currentSite = new CurrentSite($site);
        ModelFactory::createUser(site: $site, email: "user@example.net");
        $request = new Request();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")
            ->andReturn($expectedResponse);
        $templateService->shouldReceive("render")
            ->andReturn($expectedMailBody);
        $mailer = Mockery::mock(Mailer::class);
        $mailer->expects("validateEmail");
        $mailer->expects("send");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("createLoginToken")->andReturn("token");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->andReturn("login_url");
        $bodyParamsService = Mockery::mock(BodyParamsService::class);
        $bodyParamsService->expects("parse")->andReturns();
        $bodyParamsService->expects("get")->with("email")->andReturn("user@example.net");
        $bodyParamsService->expects("get")->with("return_url")->andReturn("/continue");
        $bodyParamsService->expects("get")->with("username")->andReturn("");

        // when
        $returnedResponse = $controller->sendLoginEmailAction(
            $request,
            $bodyParamsService,
            $currentSite,
            $tokenService,
            $templateService,
            $urlGenerator,
            $mailer
        );

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $mailer->shouldHaveReceived("validateEmail")->with("user@example.net");
        $mailer->shouldHaveReceived("send");
        /** @noinspection PhpUndefinedMethodInspection */
        $urlGenerator->shouldHaveReceived("generate")->with(
            "user_login_with_token", ["token" => "token"],
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("render")
            ->with("AppBundle:User:login-with-email-email.html.twig", Mockery::any());
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("renderResponse")
            ->with("AppBundle:User:send-login-email.html.twig", [
                "recipientEmail" => "user@example.net",
                "returnUrl" => "/continue",
                "senderEmail" => "editions@paronymie.fr",
            ]);
        $this->assertEquals($expectedResponse, $returnedResponse);
    }

    /** #signupWithToken */

    /**
     * @throws Exception
     */
    public function testSignupWithTokenWithInvalidTokenAction()
    {
        // given
        $controller = new UserController();
        $site = ModelFactory::createSite();
        ModelFactory::createUser(site: $site, email: "new-@paronymie.fr");
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")->with(["token" => ["type" => "string"]]);
        $queryParamsService->expects("get")->with("token")->andReturn("signup_token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("decodeLoginToken")->andReturn([
            "email" => "existing-user@paronymie.fr",
            "action" => "login-by-email",
            "after_login_url" => "/continue",
        ]);
        $currentSite = new CurrentSite($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $session = Mockery::mock(Session::class);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Ce lien d'inscription est invalide.");

        // when
        $controller->signupWithTokenAction(
            queryParams: $queryParamsService,
            tokenService: $tokenService,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            session: $session,
        );
    }

    /**
     * @throws Exception
     */
    public function testSignupWithTokenForExistingUser()
    {
        // given
        $controller = new UserController();
        $site = ModelFactory::createSite();
        ModelFactory::createUser(site: $site, email: "existing-user@paronymie.fr");
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")->with(["token" => ["type" => "string"]]);
        $queryParamsService->expects("get")->with("token")->andReturn("signup_token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("decodeLoginToken")->andReturn([
            "email" => "existing-user@paronymie.fr",
            "action" => "signup-by-email",
            "after_login_url" => "/continue",
        ]);
        $currentSite = new CurrentSite($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $session = Mockery::mock(Session::class);


        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Ce lien d'inscription est invalide.");

        // when
        $controller->signupWithTokenAction(
            queryParams: $queryParamsService,
            tokenService: $tokenService,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            session: $session,
        );
    }

    /**
     * @throws Exception
     */
    public function testSignupWithToken()
    {
        // given
        $controller = new UserController();
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")->with(["token" => ["type" => "string"]]);
        $queryParamsService->expects("get")->with("token")->andReturn("signup_token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("decodeLoginToken")->andReturn([
            "email" => "new-user@paronymie.fr",
            "action" => "signup-by-email",
            "after_login_url" => "/continue",
        ]);
        $tokenService->expects("createLoginToken")->andReturn("token");
        $site = ModelFactory::createSite(title: "Paronymie ltd");
        $currentSite = new CurrentSite($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->andReturn("/login_url");
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->expects("add");
        $session = Mockery::mock(Session::class);
        $session->expects("getFlashBag")->andReturn($flashBag);

        // when
        $response = $controller->signupWithTokenAction(
            queryParams: $queryParamsService,
            tokenService: $tokenService,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            session: $session,
        );

        // then
        $tokenService->shouldHaveReceived("createLoginToken");
        /** @noinspection PhpUndefinedMethodInspection */
        $flashBag->shouldHaveReceived("add")
            ->with("success", "Votre compte new-user@paronymie.fr a bien été créé.");
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/login_url", $response->getTargetUrl());
        $newUser = UserQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneByEmail("new-user@paronymie.fr");
        $this->assertNotNull($newUser);
    }

    /** #loginWithToken */

    /**
     * @throws Exception
     */
    public function testLoginWithTokenWithInvalidToken()
    {
        // given
        $controller = new UserController();
        $request = new Request();
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")->with(["token" => ["type" => "string"]]);
        $queryParamsService->expects("get")->with("token")->andReturn("login_token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("decodeLoginToken")->andThrows(new InvalidTokenException());
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentUser = Mockery::mock(CurrentUser::class);

        // when
        $exception = Helpers::runAndCatchException(function () use (
            $controller, $request, $queryParamsService, $tokenService, $currentSite, $currentUser
        ) {
            $controller->loginWithTokenAction(
                $request,
                $queryParamsService,
                $tokenService,
                $currentSite,
                $currentUser,
            );
        });

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $exception);
        $this->assertEquals("Ce lien de connexion est invalide.", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testLoginWithTokenWithForUnknownEmail()
    {
        // given
        $controller = new UserController();
        $request = new Request();
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")->with(["token" => ["type" => "string"]]);
        $queryParamsService->expects("get")->with("token")->andReturn("login_token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("decodeLoginToken")->andReturn(["email" => "unknown@paronymie.fr"]);
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentUser = Mockery::mock(CurrentUser::class);

        // when
        $exception = Helpers::runAndCatchException(function () use (
            $controller, $request, $queryParamsService, $tokenService, $currentSite, $currentUser
        ) {
            $controller->loginWithTokenAction(
                $request, $queryParamsService, $tokenService, $currentSite, $currentUser
            );
        });

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $exception);
        $this->assertEquals("Ce lien de connexion est invalide.", $exception->getMessage());
    }

    /**
     * @throws PropelException
     * @throws InvalidConfigurationException
     */
    public function testLoginWithEmailToken()
    {
        // given
        $controller = new UserController();
        $request = new Request();
        $request->query->set("token", "login_token");
        $request->cookies->set("visitor_uid", "visitor_token");
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")->with(["token" => ["type" => "string"]]);
        $queryParamsService->expects("get")->with("token")->andReturn("login_token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("decodeLoginToken")->andReturn([
            "email" => "user@paronymie.fr",
            "action" => "login-by-email",
            "after_login_url" => "/after_login_url",
        ]);
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $user = ModelFactory::createUser(site: $site, email: "user@paronymie.fr");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("setUser")->with($user);
        $currentUser->expects("transfertVisitorCartToUser")->with("visitor_token");

        // when
        $response = $controller->loginWithTokenAction(
            $request,
            $queryParamsService,
            $tokenService,
            $currentSite,
            $currentUser
        );

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $tokenService->shouldHaveReceived("decodeLoginToken")->with("login_token");

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/after_login_url", $response->getTargetUrl());

        $cookies = $response->headers->getCookies();
        $this->assertCount(1, $cookies);

        $userUidCookie = $cookies[0];
        $this->assertEquals("user_uid", $userUidCookie->getName());
        $session = SessionQuery::create()
            ->filterBySite($site)
            ->filterByUser($user)
            ->findOneByToken($userUidCookie->getValue());
        $this->assertNotNull($session);

        $user->reload();
        $this->assertInstanceOf(DateTime::class, $user->getLastLoggedAt());
        $this->assertInstanceOf(DateTime::class, $user->getEmailValidatedAt());
    }

    /**
     * @throws PropelException
     * @throws InvalidConfigurationException
     */
    public function testLoginWithOidcToken()
    {
        // given
        $controller = new UserController();
        $request = new Request();
        $request->query->set("token", "login_token");
        $request->cookies->set("visitor_uid", "visitor_token");
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")->with(["token" => ["type" => "string"]]);
        $queryParamsService->expects("get")->with("token")->andReturn("login_token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("decodeLoginToken")->andReturn([
            "email" => "user@paronymie.fr",
            "action" => "login-with-oidc",
            "after_login_url" => "",
        ]);
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $user = ModelFactory::createUser(site: $site, email: "user@paronymie.fr");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("setUser")->with($user);
        $currentUser->expects("transfertVisitorCartToUser")->with("visitor_token");

        // when
        $controller->loginWithTokenAction(
            $request,
            $queryParamsService,
            $tokenService,
            $currentSite,
            $currentUser
        );

        // then
        $user->reload();
        $this->assertInstanceOf(DateTime::class, $user->getLastLoggedAt());
        $this->assertNull($user->getEmailValidatedAt());
    }

    /** #account */

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testAccount()
    {
        // given
        $userController = new UserController();

        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site, email: "logged-user@biblys.fr");

        $currentSite = new CurrentSite($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("getUser")->andReturn($user);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("renderResponse")
            ->andReturn(new Response("Vous êtes connecté·e à l'aide d'un compte Axys."));

        // when
        $response = $userController->account($currentSite, $currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("renderResponse")
            ->with("AppBundle:User:account.html.twig", [
                "user_email" => "logged-user@biblys.fr",
                "has_axys_method" => false
            ], true);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testAccountWithAuthMethod()
    {
        // given
        $userController = new UserController();

        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site, email: "logged-user@biblys.fr");

        $currentSite = new CurrentSite($site);
        ModelFactory::createAuthenticationMethod(site: $site, user: $user);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("getUser")->andReturn($user);

        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("renderResponse")->andReturn(new Response("Vous êtes connecté·e à l'aide d'un compte Axys."));

        // when
        $response = $userController->account($currentSite, $currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("renderResponse")
            ->with("AppBundle:User:account.html.twig", [
                "user_email" => "logged-user@biblys.fr",
                "has_axys_method" => true,
            ], true);
    }

    /** #logout */

    public function testLogout()
    {
        // given
        $userController = new UserController();
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->expects("add");
        $session = Mockery::mock(Session::class);
        $session->expects("getFlashBag")->andReturn($flashBag);

        // when
        $response = $userController->logout($session);

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $flashBag->shouldHaveReceived("add")->with("success", "Vous avez été déconnecté·e. À bientôt !");
        $cookie = $response->headers->getCookies()[0];
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/", $response->headers->get("Location"));
        $this->assertEquals("user_uid", $cookie->getName(), "clears user_uid cookie");
        $this->assertEquals(null, $cookie->getValue(), "clears user_uid cookie");
    }

    /** #requestEmailUpdate */

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws PropelException
     */
    public function testRequestEmailUpdateWhenNewEmailIsOldEmail(): void
    {
        // given
        $userController = new UserController();
        $user = ModelFactory::createUser(email: "old-email@paronymie.fr");

        $request = new Request();
        $request->request->set("new_email", "old-email@paronymie.fr");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authUser");
        $currentUser->expects("getUser")->andReturn($user);
        $tokenService = Mockery::mock(TokenService::class);
        $templateService = Mockery::mock(TemplateService::class);
        $mailer = Mockery::mock(Mailer::class);
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("La nouvelle adresse doit être différente de l'ancienne.");

        // when
        $userController->requestEmailUpdateAction(
            $request,
            $currentUser,
            $tokenService,
            $templateService,
            $mailer,
            $flashMessages,
            $urlGenerator
        );
    }

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws PropelException
     */
    public function testRequestEmailUpdateWhenEmailIsInvalid(): void
    {
        // given
        $userController = new UserController();

        $request = new Request();
        $request->request->set("new_email", "new-email");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authUser");
        $currentUser->expects("getUser")->andReturn(ModelFactory::createUser());
        $tokenService = Mockery::mock(TokenService::class);
        $templateService = Mockery::mock(TemplateService::class);
        $mailer = Mockery::mock(Mailer::class);
        $mailer->expects("validateEmail")->andThrow(InvalidEmailAddressException::class);
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("L'adresse new-email est invalide.");

        // when
        $userController->requestEmailUpdateAction(
            $request,
            $currentUser,
            $tokenService,
            $templateService,
            $mailer,
            $flashMessages,
            $urlGenerator
        );
    }

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function testRequestEmailUpdate(): void
    {
        // given
        $userController = new UserController();

        $user = ModelFactory::createUser();

        $request = new Request();
        $request->request->set("new_email", "new-email@paronymie.fr");
        $currentUser = new CurrentUser($user, "token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("createEmailUpdateToken");
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("render")->andReturn("email body");
        $mailer = Mockery::mock(Mailer::class);
        $mailer->expects("validateEmail");
        $mailer->expects("send");
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->expects("add");
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->andReturn("/user/account");

        // when
        $response = $userController->requestEmailUpdateAction(
            $request,
            $currentUser,
            $tokenService,
            $templateService,
            $mailer,
            $flashMessages,
            $urlGenerator
        );

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $mailer->shouldHaveReceived("validateEmail")->with("new-email@paronymie.fr");
        /** @noinspection PhpUndefinedMethodInspection */
        $tokenService->shouldHaveReceived("createEmailUpdateToken")
            ->with($user, "new-email@paronymie.fr");
        /** @noinspection PhpUndefinedMethodInspection */
        $templateService->shouldHaveReceived("render");
        /** @noinspection PhpUndefinedMethodInspection */
        $mailer->shouldHaveReceived("send")
            ->with("new-email@paronymie.fr", "Validez votre nouvelle adresse e-mail", "email body");
        /** @noinspection PhpUndefinedMethodInspection */
        $flashMessages->shouldHaveReceived("add")
            ->with("info", "Cliquez sur le lien envoyé à new-email@paronymie.fr pour valider votre changement d'adresse email.");
        /** @noinspection PhpUndefinedMethodInspection */
        $urlGenerator->shouldHaveReceived("generate")->with("user_account");
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/user/account", $response->getTargetUrl());
    }

    /** #emailUpdate */

    /**
     * @throws InvalidConfigurationException
     * @throws PropelException
     */
    public function testUpdateEmailWithInvalidToken(): void
    {
        // given
        $userController = new UserController();

        $loggedInUser = ModelFactory::createUser();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authUser");
        $currentUser->expects("getUser")->andReturn($loggedInUser);
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse");
        $queryParams->expects("get")->andReturn("token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("decodeEmailUpdateToken")
            ->andThrow(InvalidTokenException::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $flashMessages = Mockery::mock(FlashMessagesService::class);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Ce lien est invalide.");

        // when
        $userController->updateEmailAction(
            $currentUser,
            $queryParams,
            $tokenService,
            $flashMessages,
            $urlGenerator,
        );
    }

    /**
     * @throws InvalidConfigurationException
     * @throws PropelException
     */
    public function testUpdateEmailForWrongAccount(): void
    {
        // given
        $userController = new UserController();

        $loggedInUser = ModelFactory::createUser();
        $tokenUser = ModelFactory::createUser();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authUser");
        $currentUser->expects("getUser")->andReturn($loggedInUser);
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse");
        $queryParams->expects("get")->andReturn("token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("decodeEmailUpdateToken")
            ->andReturn(["user_id" => $tokenUser->getId(), "new_email" => "new-email@paronymie.fr"]);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $flashMessages = Mockery::mock(FlashMessagesService::class);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("Ce lien n'est pas utilisable avec ce compte utilisateur.");

        // when
        $userController->updateEmailAction(
            $currentUser,
            $queryParams,
            $tokenService,
            $flashMessages,
            $urlGenerator,
        );
    }

    /**
     * @throws InvalidTokenException
     * @throws InvalidConfigurationException
     * @throws PropelException
     */
    public function testUpdateEmail(): void
    {
        // given
        $userController = new UserController();

        $user = ModelFactory::createUser(email: "old-email@paronymie.fr");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authUser");
        $currentUser->expects("getUser")->andReturn($user);
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse");
        $queryParams->expects("get")->andReturn("token");
        $tokenService = Mockery::mock(TokenService::class);
        $tokenService->expects("decodeEmailUpdateToken")
            ->andReturn(["user_id" => $user->getId(), "new_email" => "new-email@paronymie.fr"]);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->andReturn("/user/account");
        $flashMessages = Mockery::mock(FlashMessagesService::class);
        $flashMessages->expects("add");

        // when
        $response = $userController->updateEmailAction(
            $currentUser,
            $queryParams,
            $tokenService,
            $flashMessages,
            $urlGenerator,
        );

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $flashMessages->shouldHaveReceived("add")
            ->with(
                "success",
                "Votre nouvelle adresse e-mail new-email@paronymie.fr a bien été enregistrée."
            );
        /** @noinspection PhpUndefinedMethodInspection */
        $urlGenerator->shouldHaveReceived("generate")->with("user_account");
        /** @noinspection PhpUndefinedMethodInspection */
        $queryParams->shouldHaveReceived("parse")->with(["token" => ["type" => "string"]]);
        /** @noinspection PhpUndefinedMethodInspection */
        $tokenService->shouldHaveReceived("decodeEmailUpdateToken")->with("token");

        $user->reload();
        $this->assertEquals("new-email@paronymie.fr", $user->getEmail());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/user/account", $response->getTargetUrl());
    }

    /* UserController->ordersAction */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testOrdersAction()
    {
        // given
        $controller = new UserController();

        $user = ModelFactory::createUser();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authUser")->andReturns();
        $currentUser->shouldReceive("getUser")->andReturn($user);
        $templateService = Helpers::getTemplateService();

        $order = ModelFactory::createOrder(user: $user);

        // when
        $response = $controller->ordersAction($currentUser, $templateService);

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "responds with status code 200"
        );
        $this->assertStringContainsString(
            $order->getId(),
            $response->getContent(),
            "displays the order"
        );
    }

    /* UserController->libraryAction */

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testLibraryAction()
    {
        // given
        $controller = new UserController();

        $user = ModelFactory::createUser();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authUser")->andReturns();
        $currentUser->shouldReceive("getUser")->andReturn($user);

        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);

        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse");
        $queryParams->expects("get")->andReturn("0");

        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")->andReturn(
            new Response("In my library")
        );

        $article = ModelFactory::createArticle(title: "In my library", typeId: ArticleType::EBOOK);
        ModelFactory::createStockItem(
            site: $currentSite->getSite(),
            article: $article,
            user: $user,
            sellingDate: new DateTime(),
        );

        // when
        $response = $controller->libraryAction(
            currentUser: $currentUser,
            queryParams: $queryParams,
            templateService: $templateService,
        );

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "responds with status code 200"
        );
        $this->assertStringContainsString(
            "In my library",
            $response->getContent(),
            "displays the article title"
        );
    }
}
