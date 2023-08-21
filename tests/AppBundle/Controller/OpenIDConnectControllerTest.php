<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\OpenIDConnectProviderService;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Biblys\Service\TokenService;
use Biblys\Test\ModelFactory;
use DateTime;
use Exception;
use Facile\OpenIDClient\Exception\OAuth2Exception;
use Facile\OpenIDClient\Token\TokenSetInterface;
use Firebase\JWT\JWT;
use JsonException;
use Mockery;
use Model\SessionQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

require_once __DIR__."/../../setUp.php";

class OpenIDConnectControllerTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testAxys()
    {
        // given
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->expects("parse")->andReturn("");
        $queryParams->expects("get")->with("return_url")->andReturn("/my-account");
        $tokenService = $this->createMock(TokenService::class);
        $tokenService->method("createOIDCStateToken")
            ->with("/my-account", "secret_key")
            ->willReturn("oidc-state-token");

        $controller = new OpenIDConnectController();
        $openIDConnectProviderService = $this->createMock(OpenIDConnectProviderService::class);
        $openIDConnectProviderService
            ->method("getAuthorizationUri")
            ->willReturn("https://axys.me/authorize");

        // when
        $response = $controller->axys($queryParams, $tokenService, $openIDConnectProviderService);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("https://axys.me/authorize", $response->getTargetUrl());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallback()
    {
        // given
        $identityProvider = "axys";
        $externalId = "AXYS1234";
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $openIDConnectProviderService = $this->_buildOIDCProviderService($externalId);

        $user = ModelFactory::createUser(site: $site);
        ModelFactory::createAuthenticationMethod(
            site: $site,
            user: $user,
            identityProvider: $identityProvider,
            externalId: $externalId,
        );

        $request = self::_buildCallbackRequest();
        $controller = new OpenIDConnectController();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("setUser")->with($user);
        $currentUser->expects("transfertVisitorCartToUser")->with("visitor_token");
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->shouldReceive("parse")->with([
            "code" => ["type" => "string"],
            "state" => ["type" => "string"],
            "error" => ["type" => "string", "default" => null],
        ]);
        $queryParamsService->shouldReceive("get")
            ->with("error")->andReturn("");

        // when
        $response = $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            queryParams: $queryParamsService,
            templateService: $this->createMock(TemplateService::class),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/", $response->getTargetUrl());

        $cookies = $response->headers->getCookies();
        $this->assertCount(1, $cookies);

        $userUidCookie = $cookies[0];
        $this->assertEquals("user_uid", $userUidCookie->getName());
        $this->assertEquals(1682278410, $userUidCookie->getExpiresTime());
        $session = SessionQuery::create()
            ->filterBySite($site)
            ->filterByUser($user)
            ->findOneByToken($userUidCookie->getValue());
        $this->assertNotNull($session);
        $this->assertEquals(new DateTime("@1682278410"), $session->getExpiresAt());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallbackWithReturnUrl()
    {
        // given
        $identityProvider = "axys";
        $externalId = "AXYS5678";
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $openIDConnectProviderService = $this->_buildOIDCProviderService($externalId);

        $user = ModelFactory::createUser(site: $site);
        ModelFactory::createAuthenticationMethod(
            site: $site,
            user: $user,
            identityProvider: $identityProvider,
            externalId: $externalId,
        );

        $request = self::_buildCallbackRequest(returnUrl: "/my-account");
        $controller = new OpenIDConnectController();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("setUser")->with($user);
        $currentUser->expects("transfertVisitorCartToUser")->with("visitor_token");
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->shouldReceive("parse")->with([
            "code" => ["type" => "string"],
            "state" => ["type" => "string"],
            "error" => ["type" => "string", "default" => null],
        ]);
        $queryParamsService->shouldReceive("get")
            ->with("error")->andReturn("");

        // when
        $response = $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            queryParams: $queryParamsService,
            templateService: $this->createMock(TemplateService::class),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/my-account", $response->getTargetUrl());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallbackWithAccessDeniedError()
    {
        // given
        $request = Request::create("https://www.biblys.fr/openid/callback?error=access_denied");

        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $openIDConnectProviderService = $this->createMock(OpenIDConnectProviderService::class);
        $expectedResponse = new Response("access_denied");
        $templateService = $this->createMock(TemplateService::class);
        $templateService
            ->expects($this->once())
            ->method("renderResponse")
            ->with("AppBundle:OpenIDConnect:callback.html.twig", [
                "siteTitle" => "Éditions Paronymie"
            ])
            ->willReturn($expectedResponse);
        $controller = new OpenIDConnectController();

        $currentUser = Mockery::mock(CurrentUser::class);
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->shouldReceive("parse")->with([
            "code" => ["type" => "string"],
            "state" => ["type" => "string"],
            "error" => ["type" => "string", "default" => null],
        ]);
        $queryParamsService->shouldReceive("get")
            ->with("error")->andReturn("access_denied");

        // when
        $returnedResponse = $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            queryParams: $queryParamsService,
            templateService: $templateService
        );

        // then
        $this->assertEquals($expectedResponse, $returnedResponse);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallbackHandlesOAuth2Exception()
    {
        // given
        $controller = new OpenIDConnectController();

        $request = self::_buildCallbackRequest();
        $site = ModelFactory::createSite();

        $currentSite = new CurrentSite($site);
        $openIDConnectProviderService = Mockery::mock(OpenIDConnectProviderService::class);
        $openIDConnectProviderService->expects("getTokenSet")->andThrow(new OAuth2Exception("invalid_grant"));
        $currentUser = Mockery::mock(CurrentUser::class);

        // then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage("invalid_grant");
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->shouldReceive("parse")->with([
            "code" => ["type" => "string"],
            "state" => ["type" => "string"],
            "error" => ["type" => "string", "default" => null],
        ]);
        $queryParamsService->shouldReceive("get")
            ->with("error")->andReturn("");

        // when
        $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            queryParams: $queryParamsService,
            templateService: $this->createMock(TemplateService::class),
        );
    }

    /**
     * @param string $returnUrl
     * @return Request
     */
    private static function _buildCallbackRequest(string $returnUrl = ""): Request
    {
        $request = Request::create("https://www.biblys.fr/openid/callback");
        $stateToken = JWT::encode(["return_url" => $returnUrl], "secret_key", "HS256");
        $request->query->set("code", "authorization_code");
        $request->query->set("state", $stateToken);
        $request->cookies->set("visitor_uid", "visitor_token");
        return $request;
    }

    private function _buildOIDCProviderService(string $externalId): OpenIDConnectProviderService
    {
        $tokenSet = $this->createMock(TokenSetInterface::class);
        $tokenSet->method("claims")->willReturn(["sub" => $externalId, "exp" => 1682278410]);
        $openIDConnectProviderService = $this->createMock(OpenIDConnectProviderService::class);
        $openIDConnectProviderService->method("getTokenSet")->willReturn($tokenSet);
        return $openIDConnectProviderService;
    }
}
