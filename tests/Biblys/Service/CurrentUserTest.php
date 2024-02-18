<?php

namespace Biblys\Service;

use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use DateTime;
use Exception;
use Model\Option;
use Model\Right;
use Model\SiteQuery;
use Model\AxysAccount;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

require_once __DIR__."/../../setUp.php";

class CurrentUserTest extends TestCase
{
    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     */
    public function testBuildFromRequestWithCookie()
    {
        // given
        $user = ModelFactory::createAxysAccount();
        $request = RequestFactory::createAuthRequest("", $user);

        // when
        $currentUser = CurrentUser::buildFromRequest($request);

        // then
        $this->assertEquals(
            $user,
            $currentUser->getAxysAccount(),
            "it returns authentified user"
        );
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     */
    public function testBuildFromRequestWithHeader()
    {
        // given
        $user = ModelFactory::createAxysAccount();
        $request = RequestFactory::createAuthRequest("", $user, "header");

        // when
        $currentUser = CurrentUser::buildFromRequest($request);

        // then
        $this->assertEquals(
            $user,
            $currentUser->getAxysAccount(),
            "it returns authentified user"
        );
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     */
    public function testBuildFromRequestWithoutToken()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // given
        $request = new Request();
        $currentUser = CurrentUser::buildFromRequest($request);

        // when
        $currentUser->getAxysAccount();
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     */
    public function testBuildFromRequestWithInvalidToken()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // given
        $request = new Request();
        $request->headers->set("AuthToken", "InvalidToken");
        $currentUser = CurrentUser::buildFromRequest($request);

        // when
        $currentUser->getAxysAccount();
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     */
    public function testBuildFromRequestWithExpiredSession()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // given
        $session = ModelFactory::createUserSession();
        $session->setExpiresAt(new DateTime("yesterday"));
        $session->save();
        $request = new Request();
        $request->headers->set("AuthToken", $session->getToken());
        $currentUser = CurrentUser::buildFromRequest($request);

        // when
        $currentUser->getAxysAccount();
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     */
    public function testBuildFromRequestWithDeletedUser()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // given
        $session = ModelFactory::createUserSession();
        $session->setAxysAccountId(12345);
        $request = new Request();
        $request->headers->set("AuthToken", $session->getToken());
        $currentUser = CurrentUser::buildFromRequest($request);

        // when
        $currentUser->getAxysAccount();
    }

    /**
     * @throws PropelException
     */
    public function testIsAuthentifiedForUser()
    {
        // given
        $user = ModelFactory::createAxysAccount();

        // when
        $currentUser = new CurrentUser($user, "token");

        // then
        $this->assertTrue(
            $currentUser->isAuthentified(),
            "it returns true if user is authentified"
        );
    }

    public function testIsAuthentifiedForNonUser()
    {
        // given
        $user = null;

        // when
        $currentUser = new CurrentUser($user, "token");

        // then
        $this->assertFalse(
            $currentUser->isAuthentified(),
            "it returns false if user is unauthentified"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testIsAdmin()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $isAdmin = $currentUser->isAdmin();

        // then
        $this->assertTrue(
            $isAdmin,
            "it returns true for admin user"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testIsAdminWithNonAdminUser()
    {
        // given
        $request = RequestFactory::createAuthRequest();
        $config = new Config();
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $isAdmin = $currentUser->isAdmin();

        // then
        $this->assertFalse(
            $isAdmin,
            "it returns false for non-admin user"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testIsAdminWithUnauthentifiedUser()
    {
        // given
        $request = new Request();
        $config = new Config();
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $isAdmin = $currentUser->isAdmin();

        // then
        $this->assertFalse(
            $isAdmin,
            "it returns false for unauthentified user"
        );
    }

    /**
     * @throws PropelException
     */
    public function testIsAdminForSite()
    {
        // given
        $config = new Config();
        $site = SiteQuery::create()->findOneById($config->get("site"));
        $admin = ModelFactory::createAdminAxysAccount();

        // when
        $currentUser = new CurrentUser($admin, "token");

        // then
        $this->assertTrue(
            $currentUser->isAdminForSite($site),
            "it returns true for admin user"
        );
    }

    /**
     * @throws PropelException
     */
    public function testIsAdminForSiteForNonAdmin()
    {
        // given
        $config = new Config();
        $site = SiteQuery::create()->findOneById($config->get("site"));
        $user = ModelFactory::createAxysAccount();

        // when
        $currentUser = new CurrentUser($user, "token");

        // then
        $this->assertFalse(
            $currentUser->isAdminForSite($site),
            "it returns false for non-admin user"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasPublisher()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $user = ModelFactory::createPublisherAxysAccount($publisher);
        $currentUser = new CurrentUser($user, "token");

        // when
        $hasRightforPublisher = $currentUser->hasRightForPublisher($publisher);

        // then
        $this->assertTrue(
            $hasRightforPublisher,
            "it returns true for user with rights for given publisher"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasPublisherRightForNonPublisher()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $user = ModelFactory::createAxysAccount();
        $currentUser = new CurrentUser($user, "token");

        // when
        $hasRightForPublisher = $currentUser->hasRightForPublisher($publisher);

        // then
        $this->assertFalse(
            $hasRightForPublisher,
            "it returns false for user without publisher rights"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasPublisherRightForOtherPublisher()
    {
        // given
        $userPublisher = ModelFactory::createPublisher();
        $otherPublisher = ModelFactory::createPublisher();
        $user = ModelFactory::createPublisherAxysAccount($userPublisher);
        $currentUser = new CurrentUser($user, "token");

        // when
        $hasRightForPublisher = $currentUser->hasRightForPublisher($otherPublisher);

        // then
        $this->assertFalse(
            $hasRightForPublisher,
            "it returns false for user without publisher rights"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasPublisherRight()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $user = ModelFactory::createPublisherAxysAccount($publisher);
        $currentUser = new CurrentUser($user, "token");

        // when
        $hasRightforPublisher = $currentUser->hasPublisherRight();

        // then
        $this->assertTrue(
            $hasRightforPublisher,
            "it returns true for user with rights for at least one publisher"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasPublisherRightWithNonPublisher()
    {
        // given
        $user = ModelFactory::createAxysAccount();
        $currentUser = new CurrentUser($user, "token");

        // when
        $hasRightForPublisher = $currentUser->hasPublisherRight();

        // then
        $this->assertFalse(
            $hasRightForPublisher,
            "it returns false for user with no publisher rights"
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetCurrentRight()
    {
        // given
        $currentRight = $this->createMock(Right::class);
        $currentRight->method("getId")->willReturn("1111");
        $user = $this->createMock(AxysAccount::class);
        $user->expects($this->once())
            ->method("getCurrentRight")
            ->willReturn($currentRight);
        $currentUser = new CurrentUser($user, "token");

        // when
        $returnedRight = $currentUser->getCurrentRight();

        // then
        $this->assertEquals($currentRight, $returnedRight);
    }

    /**
     * @throws PropelException
     */
    public function testGetOption()
    {
        // given
        $user = ModelFactory::createAxysAccount();
        $option = new Option();
        $option->setAxysAccount($user);
        $option->setKey("days_since_last_login");
        $option->setValue("31");
        $option->save();
        $currentUser = new CurrentUser($user, "token");

        // when
        $currentUser->getOption("days_since_last_login");

        // then
        $this->assertEquals(
            "31",
            $currentUser->getOption("days_since_last_login"),
            "it returns the value of the option"
        );
    }

    /**
     * @throws PropelException
     */
    public function testSetOption()
    {
        // given
        $user = ModelFactory::createAxysAccount();
        $currentUser = new CurrentUser($user, "token");

        // when
        $currentUser->setOption("days_since_last_login", "64");

        // then
        $this->assertEquals(
            "64",
            $currentUser->getOption("days_since_last_login"),
            "it sets the value of the option"
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetUserToken()
    {
        // given
        $request = RequestFactory::createAuthRequest();
        $currentUser = CurrentUser::buildFromRequest($request);
        $requestToken = $request->cookies->get("user_uid");

        // when
        $userToken = $currentUser->getToken();

        // then
        $this->assertEquals(
            $requestToken,
            $userToken,
            "it returns the user uid"
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetCartForAnonymousUser()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $request = new Request();
        $request->cookies->set("visitor_uid", "this-visitor-uid");
        ModelFactory::createCart(site: ModelFactory::createSite(), uniqueId: "this-visitor-uid");
        $cart = ModelFactory::createCart(site: $site, uniqueId: "this-visitor-uid");
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $userCart = $currentUser->getCart();

        // then
        $this->assertEquals($cart, $userCart);
    }

    /**
     * @throws PropelException
     */
    public function testGetOrCreateCartForAnonymousUserWhenCartExists()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $request = new Request();
        $request->cookies->set("visitor_uid", "this-visitor-uid");
        $cart = ModelFactory::createCart(site: $site, uniqueId: "this-visitor-uid");
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $anonymousUserCart = $currentUser->getOrCreateCart();

        // then
        $this->assertEquals($cart, $anonymousUserCart);
        $this->assertNull($anonymousUserCart->getAxysAccountId());
    }

    /**
     * @throws PropelException
     */
    public function testGetOrCreateCartForAnonymousUserWhenCartDoesNotExist()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $request = new Request();
        $request->cookies->set("visitor_uid", "visitor-uid-without-cart");
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $anonymousUserCart = $currentUser->getOrCreateCart();

        // then
        $this->assertEquals("visitor-uid-without-cart", $anonymousUserCart->getUid());
        $this->assertEquals("web", $anonymousUserCart->getType());
        $this->assertNull($anonymousUserCart->getAxysAccountId());
    }

    /**
     * @throws PropelException
     */
    public function testGetCartForAuthentifiedUser()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $user = ModelFactory::createAxysAccount();
        $request = RequestFactory::createAuthRequest("", $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $cart = ModelFactory::createCart(site: $site, user: $user);

        // when
        $userCart = $currentUser->getCart();

        // then
        $this->assertEquals($cart, $userCart);
    }

    /**
     * @throws PropelException
     */
    public function testGetOrCreateCartForAuthentifiedUserWhenCartExists()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $user = ModelFactory::createAxysAccount();
        $request = RequestFactory::createAuthRequest("", $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $cart = ModelFactory::createCart(site: $site, user: $user);

        // when
        $userCart = $currentUser->getOrCreateCart();

        // then
        $this->assertEquals($cart, $userCart);
    }

    /**
     * @throws PropelException
     */
    public function testGetOrCreateCartForAuthentifiedUserWhenCartDoesNotExist()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $axysAccount = ModelFactory::createAxysAccount();
        $request = RequestFactory::createAuthRequest("", $axysAccount);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $userCart = $currentUser->getOrCreateCart();

        // then
        $this->assertEquals($axysAccount->getId(), $userCart->getAxysAccountId());
        $this->assertEquals("web", $userCart->getType());
        $this->assertNull($userCart->getUid());
    }

    public function testGetEmail()
    {
        // given
        $axysAccount = new AxysAccount();
        $axysAccount->setEmail("get-email@biblys.fr");
        $currentUser = new CurrentUser($axysAccount, "token");

        // when
        $email = $currentUser->getEmail();

        // then
        $this->assertEquals("get-email@biblys.fr", $email);
    }

    /**
     * @throws PropelException
     */
    public function testHasArticleInCartWithoutCart()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $axysAccount = ModelFactory::createAxysAccount();
        $request = RequestFactory::createAuthRequest(user: $axysAccount);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $hasArticleInCart = $currentUser->hasArticleInCart($article);

        // then
        $this->assertFalse($hasArticleInCart);
    }

    /**
     * @throws PropelException
     */
    public function testHasArticleInCartWithoutArticleInCart()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $axysAccount = ModelFactory::createAxysAccount();
        $request = RequestFactory::createAuthRequest(user: $axysAccount);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        ModelFactory::createCart(site: $site, user: $axysAccount);

        // when
        $hasArticleInCart = $currentUser->hasArticleInCart($article);

        // then
        $this->assertFalse($hasArticleInCart);
    }

    /**
     * @throws PropelException
     */
    public function testHasArticleInCartWithArticleInCart()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $axysAccount = ModelFactory::createAxysAccount();
        $request = RequestFactory::createAuthRequest(user: $axysAccount);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $cart = ModelFactory::createCart(site: $site, user: $axysAccount);
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);

        // when
        $hasArticleInCart = $currentUser->hasArticleInCart($article);

        // then
        $this->assertTrue($hasArticleInCart);
    }
}
