<?php

namespace AppBundle\Controller;

use Axys\AxysOpenIDConnectProvider;
use Biblys\Service\Axys;
use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Framework\Exception\AuthException;
use Lcobucci\JWT\Token;
use Model\SessionQuery;
use OpenIDConnectClient\AccessToken;
use OpenIDConnectClient\Exception\InvalidTokenException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../setUp.php";

class OpenIDConnectControllerTest extends TestCase
{
    public function testAxys()
    {
        // given
        $oidcProvider = $this->createMock(AxysOpenIDConnectProvider::class);
        $oidcProvider->method("getAuthorizationUrl")->willReturn("https://axys.me/authorize");
        $axys = $this->createMock(Axys::class);
        $axys->method("getOpenIDConnectProvider")->willReturn($oidcProvider);
        $controller = new OpenIDConnectController();

        // when
        $response = $controller->axys($axys);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("https://axys.me/authorize", $response->getTargetUrl());
    }

    /**
     * @throws InvalidTokenException
     * @throws PropelException
     */
    public function testCallback()
    {
        // given
        $request = new Request();
        $request->query->set("code", "authorization_code");
        $controller = new OpenIDConnectController();
        $user = ModelFactory::createUser();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);

        $idToken = new Token(["alg" => "RS256"], ["sub" => $user->getId()]);
        $accessToken = $this->createMock(AccessToken::class);
        $accessToken->method("getIdToken")->willReturn($idToken);

        $oidcProvider = $this->createMock(AxysOpenIDConnectProvider::class);
        $oidcProvider->method("getAccessToken")
            ->with("authorization_code", ["code" => "authorization_code"])
            ->willReturn($accessToken);

        $axys = $this->createMock(Axys::class);
        $axys->method("getOpenIDConnectProvider")->willReturn($oidcProvider);

        // when
        $response = $controller->callback($request, $axys, $currentSite);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/", $response->getTargetUrl());

        $cookies = $response->headers->getCookies();
        $this->assertCount(2, $cookies);

        $this->assertEquals("id_token", $cookies[0]->getName());
        $this->assertEquals("..", $cookies[0]->getValue());

        $this->assertEquals("user_uid", $cookies[1]->getName());
        $session = SessionQuery::create()
            ->filterBySite($site)
            ->findOneByToken($cookies[1]->getValue());
        $this->assertNotNull($session);
    }

    /**
     * @route GET /openid/logout
     * @throws AuthException
     * @throws PropelException
     */
    public function testLogout()
    {
        // given
        $request = RequestFactory::createAuthRequest();
        $controller = new OpenIDConnectController();

        // when
        $response = $controller->logout($request);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/", $response->getTargetUrl());

        $session = SessionQuery::create()->findOneByToken($request->cookies->get("user_uid"));
        $this->assertNull($session, "Session should be deleted");
    }
}
