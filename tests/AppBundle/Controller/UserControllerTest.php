<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class UserControllerTest extends TestCase
{
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
        $request = new Request();

        // when
        $response = $userController->login($request, $currentUser, $urlGenerator);

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
        $request = new Request();
        $request->query->set("return_url", "url_to_return_to");

        // when
        $response = $userController->login($request, $currentUser, $urlGenerator);

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
        $request = new Request();

        // when
        $response = $userController->login($request, $currentUser, $urlGenerator);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(
            "/",
            $response->headers->get("Location"),
        );
    }

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

        // when
        $response = $userController->account();

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
            "Vous avez été déconnecté·e du site Librairie Ys.",
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
