<?php

namespace AppBundle\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;

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

        // when
        $response = $userController->login($urlGenerator);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/openid/axys", $response->getTargetUrl());
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
