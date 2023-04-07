<?php

namespace ApiBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Framework\Controller;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        ModelFactory::createUser([
            "email" => "login@biblys.fr",
            "username" => "validUser",
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
     * @throws AuthException
     * @throws PropelException
     */
    public function testAuthActionWithMissingCredentials()
    {
        // then
        $this->expectException("Symfony\Component\HttpKernel\Exception\BadRequestHttpException");
        $this->expectExceptionMessage("Credentials are missing");

        // given
        $controller = new AuthController();
        $request = new Request();

        // when
        $controller->authAction($request);
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testAuthActionWithUnknownUser()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Bad credentials");

        // given
        $controller = new AuthController();
        $request = new Request();
        $request->request->set("login", "unknown@biblys.fr");
        $request->request->set("password", "p4ssword");

        // when
        $controller->authAction($request);
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testAuthActionWithWrongPassword()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Bad credentials");
        ModelFactory::createUser([
            "email" => "login@biblys.fr",
            "username" => "validUser",
            "password" => "p4ssword",
        ]);

        // given
        $controller = new AuthController();
        $request = new Request();
        $request->request->set("login", "login@biblys.fr");
        $request->request->set("password", "passw0rd");

        // when
        $controller->authAction($request);
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testAuthActionWithNonValidatedEmail()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Email address has not been validated");
        ModelFactory::createUser([
            "email" => "non-validated-email@biblys.fr",
            "username" => "nonValidatedEmail",
            "password" => "p4ssword",
            "email_key" => "abcd1234"
        ]);

        // given
        $controller = new AuthController();
        $request = new Request();
        $request->request->set("login", "non-validated-email@biblys.fr");
        $request->request->set("password", "p4ssword");

        // when
        $controller->authAction($request);
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
        $user = ModelFactory::createUser([
            "email" => "me@biblys.fr",
            "username" => "Me",
        ]);
        $request = RequestFactory::createAuthRequest("", $user, $method = "header");
        $currentUserService = CurrentUser::buildFromRequest($request);

        // when
        $response = $controller->meAction($currentUserService);

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
