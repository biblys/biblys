<?php

namespace ApiBundle\Controller;

use Biblys\Test\Factory;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../setUp.php";

class AuthControllerTest extends TestCase
{

    /**
     * @throws PropelException
     * @throws AuthException
     */
    public function testAuthAction()
    {
        // given
        $controller = new AuthController();
        Factory::createUser([
            "email" => "login@biblys.fr",
            "password" => "p4ssword",
        ]);
        $request = new Request();
        $request->request->set("login", "login@biblys.fr");
        $request->request->set("password", "p4ssword");

        // when
        $response = $controller->authAction($request);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey("token", $data);
    }

    /**
     * @throws PropelException
     * @throws AuthException
     *
     */
    public function testMeAction()
    {
        // given
        $controller = new AuthController();
        $user = Factory::createUser([
            "email" => "me@biblys.fr",
            "username" => "Me",
        ]);
        $request = Factory::createAuthRequest("", $user);

        // when
        $response = $controller->meAction($request);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $data = json_decode($response->getContent(), true);
        $this->assertEquals("me@biblys.fr", $data["user"]["email"]);
        $this->assertEquals("Me", $data["user"]["username"]);
    }
}
