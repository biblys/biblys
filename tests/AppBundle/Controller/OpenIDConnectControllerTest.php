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
use Model\AuthenticationMethodQuery;
use Mockery;
use Model\OptionQuery;
use Model\SessionQuery;
use Model\UserQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
    public function testCallbackWithUpdatedEmail()
    {
        // given
        $identityProvider = "axys";
        $externalId = "AXYS5678";
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $openIDConnectProviderService = $this->_buildOIDCProviderService(
            $externalId,
            email: "new-email@example.net",
        );

        $user = ModelFactory::createUser(site: $site, email: "old-email@example.net");
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

        // when
        $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            templateService: $this->createMock(TemplateService::class),
        );

        // then
        $user->reload();
        $this->assertEquals("new-email@example.net", $user->getEmail());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallbackWithAlreadyExistingEmail()
    {
        // given
        $identityProvider = "axys";
        $externalId = "AXYS5678";
        $site = ModelFactory::createSite(title: "NOPE");
        $currentSite = new CurrentSite($site);
        $openIDConnectProviderService = $this->_buildOIDCProviderService(
            $externalId,
            email: "existing-email@example.net",
        );

        $otherUser = ModelFactory::createUser(site: $site, email: "existing-email@example.net");
        ModelFactory::createAuthenticationMethod(
            site: $site,
            user: $otherUser,
            identityProvider: $identityProvider,
            externalId: 999,
        );

        $request = self::_buildCallbackRequest(returnUrl: "/my-account");
        $controller = new OpenIDConnectController();
        $currentUser = Mockery::mock(CurrentUser::class);

        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Il existe déjà un compte NOPE pour l'adresse existing-email@example.net");

        // when
        $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            templateService: $this->createMock(TemplateService::class),
        );

        // then
        $otherUser->reload();
        $this->assertEquals("new-email@example.net", $otherUser->getEmail());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallbackWithUserImport()
    {
        // given
        $identityProvider = "axys";
        $externalId = "1234";
        $userEmail = "user-to-import@biblys.fr";
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $openIDConnectProviderService = $this->_buildOIDCProviderService(
            externalId: $externalId,
            email: $userEmail,
        );

        $cart = ModelFactory::createCart(site: $site, axysAccountId: $externalId);
        $sellerCart = ModelFactory::createCart(site: $site, sellerId: $externalId);
        $customer = ModelFactory::createCustomer(site: $site, axysAccountId: $externalId);
        $list = ModelFactory::createStockItemList(site: $site, axysAccountId: $externalId);
        $option = ModelFactory::createUserOption(site: $site, axysAccountId: $externalId);
        $order = ModelFactory::createOrder(site: $site, axysAccountId: $externalId);
        $post = ModelFactory::createPost(site: $site, axysAccountId: $externalId);
        $adminRight = ModelFactory::createRight(user: null, site: $site, axysAccountId: $externalId);
        $libraryItem = ModelFactory::createStockItem(site: $site, axysAccountId: $externalId);
        $subscription = ModelFactory::createSubscription(site: $site, axysAccountId: $externalId);
        $alert = ModelFactory::createAlert(axysAccountId: $externalId);
        $publisherRight = ModelFactory::createRight(
            user: null, site: null, publisher: ModelFactory::createPublisher(), axysAccountId: $externalId
        );
        $vote = ModelFactory::createVote(axysAccountId: $externalId);
        $wishlist = ModelFactory::createWishlist(axysAccountId: $externalId);
        $wish = ModelFactory::createWish(wishlist: $wishlist, axysAccountId: $externalId);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("setUser");
        $currentUser->expects("transfertVisitorCartToUser");

        $request = self::_buildCallbackRequest();
        $controller = new OpenIDConnectController();

        // when
        $response = $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            templateService: $this->createMock(TemplateService::class),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());

        $user = UserQuery::create()
            ->filterBySite($site)
            ->findOneByEmail($userEmail);
        $this->assertNotNull($user);
        $authenticationMethod = AuthenticationMethodQuery::create()
            ->filterBySite($site)
            ->filterByUser($user)
            ->filterByIdentityProvider($identityProvider)
            ->filterByExternalId($externalId)
            ->findOneByExternalId($externalId);
        $this->assertNotNull($authenticationMethod);

        $importDateOption = OptionQuery::create()
            ->filterByUser($user)
            ->filterBySite($site)
            ->findOneByKey("imported_from_axys");
        $this->assertNotNull($importDateOption);
        $this->assertEquals(date("Y-m-d"), $importDateOption->getValue());

        $cart->reload();
        $this->assertEquals($user->getId(), $cart->getUserId());
        $this->assertNull($cart->getAxysAccountId());

        $sellerCart->reload();
        $this->assertEquals($user->getId(), $sellerCart->getSellerUserId());
        $this->assertNull($sellerCart->getAxysAccountId());

        $customer->reload();
        $this->assertEquals($user->getId(), $customer->getUserId());
        $this->assertNull($customer->getAxysAccountId());

        $list->reload();
        $this->assertEquals($user->getId(), $list->getUserId());
        $this->assertNull($list->getAxysAccountId());

        $option->reload();
        $this->assertEquals($user->getId(), $option->getUserId());
        $this->assertNull($option->getAxysAccountId());

        $order->reload();
        $this->assertEquals($user->getId(), $order->getUserId());
        $this->assertNull($order->getAxysAccountId());

        $post->reload();
        $this->assertEquals($user->getId(), $post->getUserId());
        $this->assertNull($post->getAxysAccountId());

        $adminRight->reload();
        $this->assertEquals($user->getId(), $adminRight->getUserId());
        $this->assertNull($adminRight->getAxysAccountId());
        $this->assertTrue($adminRight->isAdmin());

        $libraryItem->reload();
        $this->assertEquals($user->getId(), $libraryItem->getUserId());
        $this->assertNull($libraryItem->getAxysAccountId());

        $subscription->reload();
        $this->assertEquals($user->getId(), $subscription->getUserId());
        $this->assertNull($subscription->getAxysAccountId());

        $alert->reload();
        $this->assertEquals($externalId, $alert->getAxysAccountId());
        $this->assertNull($alert->getUserId());
        $this->assertNull($alert->getSiteId());

        $publisherRight->reload();
        $this->assertEquals($externalId, $publisherRight->getAxysAccountId());
        $this->assertNull($publisherRight->getUserId());
        $this->assertNull($publisherRight->getSiteId());

        $vote->reload();
        $this->assertEquals($externalId, $vote->getAxysAccountId());
        $this->assertNull($vote->getSiteId());
        $this->assertNull($vote->getUserId());

        $wishlist->reload();
        $this->assertEquals($externalId, $wishlist->getAxysAccountId());
        $this->assertNull($wishlist->getSiteId());
        $this->assertNull($wishlist->getUserId());

        $wish->reload();
        $this->assertEquals($externalId, $wish->getAxysAccountId());
        $this->assertNull($wish->getSiteId());
        $this->assertNull($wish->getUserId());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallbackWithAlertsImport()
    {
        // given
        $externalId = "AXYS9876";
        $userEmail = "user-with-alerts@biblys.fr";
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("alerts", true);
        $openIDConnectProviderService = $this->_buildOIDCProviderService(
            externalId: $externalId,
            email: $userEmail,
        );

        $alert = ModelFactory::createAlert(site: $site, axysAccountId: $externalId);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("setUser");
        $currentUser->expects("transfertVisitorCartToUser");

        $request = self::_buildCallbackRequest();
        $controller = new OpenIDConnectController();

        // when
        $response = $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            templateService: $this->createMock(TemplateService::class),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());

        $user = UserQuery::create()
            ->filterBySite($site)
            ->findOneByEmail($userEmail);

        $alert->reload();
        $this->assertEquals($user->getId(), $alert->getUserId());
        $this->assertEquals($site->getId(), $alert->getSiteId());
        $this->assertNull($alert->getAxysAccountId());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallbackWithPublisherRightsImport()
    {
        // given
        $externalId = "AXYS9876";
        $userEmail = "user-with-publisher-rights@biblys.fr";
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_rights_managment", true);
        $openIDConnectProviderService = $this->_buildOIDCProviderService(
            externalId: $externalId,
            email: $userEmail,
        );

        $adminRightForOtherSite = ModelFactory::createRight(
            user: null, site: ModelFactory::createSite(), axysAccountId: $externalId
        );
        $publisherRight = ModelFactory::createRight(
            user: null, site: null, publisher: ModelFactory::createPublisher(), axysAccountId: $externalId
        );
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("setUser");
        $currentUser->expects("transfertVisitorCartToUser");

        $request = self::_buildCallbackRequest();
        $controller = new OpenIDConnectController();

        // when
        $response = $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            templateService: $this->createMock(TemplateService::class),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());

        $user = UserQuery::create()
            ->filterBySite($site)
            ->findOneByEmail($userEmail);

        $adminRightForOtherSite->reload();
        $this->assertNull($adminRightForOtherSite->getUserId());
        $this->assertNotEquals($site->getId(), $adminRightForOtherSite->getSiteId());
        $this->assertEquals(0, $adminRightForOtherSite->getAxysAccountId());

        $publisherRight->reload();
        $this->assertEquals($user->getId(), $publisherRight->getUserId());
        $this->assertEquals($site->getId(), $publisherRight->getSiteId());
        $this->assertFalse($publisherRight->isAdmin());
        $this->assertNull($publisherRight->getAxysAccountId());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallbackWithVotesImport()
    {
        // given
        $externalId = "AXYS5432";
        $userEmail = "user-with-votes@biblys.fr";
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("voting", true);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("setUser");
        $currentUser->expects("transfertVisitorCartToUser");
        $openIDConnectProviderService = $this->_buildOIDCProviderService(
            externalId: $externalId,
            email: $userEmail,
        );
        $vote = ModelFactory::createVote(axysAccountId: $externalId);

        $request = self::_buildCallbackRequest();
        $controller = new OpenIDConnectController();

        // when
        $response = $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            templateService: $this->createMock(TemplateService::class),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());

        $user = UserQuery::create()
            ->filterBySite($site)
            ->findOneByEmail($userEmail);

        $vote->reload();
        $this->assertEquals($user->getId(), $vote->getUserId());
        $this->assertEquals($site->getId(), $vote->getSiteId());
        $this->assertNull($vote->getAxysAccountId());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallbackWithWishlistImport()
    {
        // given
        $externalId = "AXYS2345";
        $userEmail = "user-with-wishes@biblys.fr";
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("wishlist", true);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("setUser");
        $currentUser->expects("transfertVisitorCartToUser");

        $openIDConnectProviderService = $this->_buildOIDCProviderService(
            externalId: $externalId,
            email: $userEmail,
        );

        $wishlist = ModelFactory::createWishlist(axysAccountId: $externalId);
        $wish = ModelFactory::createWish(wishlist: $wishlist, axysAccountId: $externalId);

        $request = self::_buildCallbackRequest();
        $controller = new OpenIDConnectController();

        // when
        $response = $controller->callback(
            request: $request,
            currentSite: $currentSite,
            currentUser: $currentUser,
            config: new Config(["axys" => ["client_secret" => "secret_key"]]),
            openIDConnectProviderService: $openIDConnectProviderService,
            templateService: $this->createMock(TemplateService::class),
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());

        $user = UserQuery::create()
            ->filterBySite($site)
            ->findOneByEmail($userEmail);

        $wishlist->reload();
        $this->assertEquals($user->getId(), $wishlist->getUserId());
        $this->assertEquals($site->getId(), $wishlist->getSiteId());
        $this->assertNull($wishlist->getAxysAccountId());

        $wish->reload();
        $this->assertEquals($user->getId(), $wish->getUserId());
        $this->assertEquals($site->getId(), $wish->getSiteId());
        $this->assertNull($wish->getAxysAccountId());
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

    private function _buildOIDCProviderService(
        string $externalId,
        string $email = "oidc-user@biblys.fr",
    ): OpenIDConnectProviderService
    {
        $tokenSet = $this->createMock(TokenSetInterface::class);
        $tokenSet->method("claims")->willReturn(
            ["sub" => $externalId, "email" => $email, "exp" => 1682278410]
    );
        $openIDConnectProviderService = $this->createMock(OpenIDConnectProviderService::class);
        $openIDConnectProviderService->method("getTokenSet")->willReturn($tokenSet);
        return $openIDConnectProviderService;
    }
}
