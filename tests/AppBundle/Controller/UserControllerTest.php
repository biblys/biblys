<?php

namespace AppBundle\Controller;

use Biblys\Exception\InvalidConfigurationException;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
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
     */
    public function testIndexAction()
    {
        // given
        $controller = new UserController();
        $site = ModelFactory::createSite();
        $users = [
            ModelFactory::createUser(site: $site),
            ModelFactory::createUser(site: $site),
        ];
        $currentSite = new CurrentSite($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive('authAdmin')->andReturns();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->shouldReceive("renderResponse")
            ->with("AppBundle:User:index.html.twig", [
                "users" => $users
            ])->andReturn(new Response());

        // when
        $response = $controller->indexAction($currentSite, $currentUser, $templateService);

        // then
        $this->assertEquals("200", $response->getStatusCode());
    }

    /**
     * #login
     */

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testLogin()
    {
        // given
        $userController = new UserController();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("isAuthentified")->willReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("openid_axys")
            ->willReturn("/openid/axys");
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->andReturn("");
        $queryParams->expects("get")->with("return_url")->andReturn("");

        // when
        $response = $userController->login($queryParams, $currentUser, $urlGenerator);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Se connecter avec Axys", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testLoginWithReturnUrl()
    {
        // given
        $userController = new UserController();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("isAuthentified")->willReturn(false);
        $urlGenerator
            ->method("generate")
            ->with("openid_axys", ["return_url" => "url_to_return_to"])
            ->willReturn("/openid/axys?return_url=url_to_return_to");
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->andReturn("");
        $queryParams->expects("get")->with("return_url")->andReturn("url_to_return_to");

        // when
        $response = $userController->login($queryParams, $currentUser, $urlGenerator);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("url_to_return_to", $response->getContent());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testLoginForAuthentifiedUser()
    {
        // given
        $userController = new UserController();
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("isAuthentified")->willReturn(true);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("main_home")
            ->willReturn("/");
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->andReturn("");
        $queryParams->expects("get")->with("return_url")->andReturn("");

        // when
        $response = $userController->login($queryParams, $currentUser, $urlGenerator);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(
            "/",
            $response->headers->get("Location"),
        );
    }

    /**
     * #sendLoginEmail
     */

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
        $request->request->set("email", "invalid-email");
        $request->request->set("return_url", "/continue");
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

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("L'adresse invalid-email est invalide.");

        // when
        $controller->sendLoginEmailAction(
            $request, $currentSite, $tokenService, $templateService, $urlGenerator, $mailer,
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
        $request->request->set("email", "user@example.net");
        $request->request->set("return_url", "/continue");
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

        // when
        $returnedResponse = $controller->sendLoginEmailAction(
            $request, $currentSite, $tokenService, $templateService, $urlGenerator, $mailer,
        );

        // then
        $tokenService->shouldReceive("createLoginToken")
            ->with("user@example.net", "signup-by-email", "/continue");
        $mailer->shouldHaveReceived("send");
        $templateService->shouldHaveReceived("render")
            ->with("AppBundle:User:signup-by-email-email.html.twig", Mockery::any());
        $templateService->shouldHaveReceived("renderResponse")
            ->with("AppBundle:User:send-login-email.html.twig", [
                "recipientEmail" => "user@example.net",
                "returnUrl" => "/continue",
                "senderEmail" => "editions@paronymie.fr",
            ]);
        $this->assertEquals($expectedResponse, $returnedResponse);
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
        $request->request->set("email", "user@example.net");
        $request->request->set("return_url", "/continue");
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

        // when
        $returnedResponse = $controller->sendLoginEmailAction(
            $request, $currentSite, $tokenService, $templateService, $urlGenerator, $mailer
        );

        // then
        $mailer->shouldHaveReceived("validateEmail")->with("user@example.net");
        $mailer->shouldHaveReceived("send");
        $urlGenerator->shouldHaveReceived("generate")->with(
            "user_login_with_token", ["token" => "token"],
        );
        $templateService->shouldHaveReceived("render")
            ->with("AppBundle:User:login-with-email-email.html.twig", Mockery::any());
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

    /** Others */

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

        $user = ModelFactory::createUser(email: "logged-user@biblys.fr");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("getUser")->andReturn($user);

        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("renderResponse")->with("AppBundle:User:account.html.twig", [
            "user_email" => "logged-user@biblys.fr",
        ])->andReturn(new Response("Vous êtes connecté·e à l'aide d'un compte Axys."));

        // when
        $response = $userController->account($currentUser, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString(
            "Vous êtes connecté·e à l'aide d'un compte Axys.",
            $response->getContent(),
        );
    }

    public function testLogout()
    {
        // given
        $userController = new UserController();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")
            ->with("user_logged_out")
            ->willReturn("logged_out_url");

        // when
        $response = $userController->logout($urlGenerator);

        // then
        $cookie = $response->headers->getCookies()[0];
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("logged_out_url", $response->headers->get("Location"));
        $this->assertEquals("user_uid", $cookie->getName(), "clears user_uid cookie");
        $this->assertEquals(null, $cookie->getValue(), "clears user_uid cookie");
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testLoggedOut()
    {
        // given
        $userController = new UserController();

        // when
        $response = $userController->loggedOut();

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString(
            "Vous avez été déconnecté·e du site Éditions Paronymie.",
            $response->getContent(),
        );
    }

    public function testSignup()
    {
        // given
        $userController = new UserController();

        // when
        $response = $userController->signup();

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("https://axys.me", $response->getTargetUrl());
    }
}
