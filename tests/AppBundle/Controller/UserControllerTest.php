<?php

namespace AppBundle\Controller;

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
    public function testLogin()
    {
        // given
        $userController = new UserController();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("openid_axys")
            ->willReturn("/openid/axys");
        $request = new Request();

        // when
        $response = $userController->login($request, $urlGenerator);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/openid/axys", $response->getTargetUrl());
    }

    public function testLoginWithReturnUrl()
    {
        // given
        $userController = new UserController();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("openid_axys", ["return_url" => "return_url"])
            ->willReturn("openid_axys_url_with_return_url_param");
        $request = new Request();
        $request->query->set("return_url", "return_url");

        // when
        $response = $userController->login($request, $urlGenerator);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("openid_axys_url_with_return_url_param", $response->getTargetUrl());
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
