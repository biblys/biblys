<?php

namespace Biblys\Service;

use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use DateTime;
use Exception;
use Mockery;
use Model\CartQuery;
use Model\Option;
use Model\User;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

require_once __DIR__."/../../setUp.php";

class CurrentUserTest extends TestCase
{
    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     * @throws Exception
     */
    public function testBuildFromRequestWithCookie()
    {
        // given
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest("", $user);

        // when
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, Config::load());

        // then
        $this->assertEquals(
            $user,
            $currentUser->getUser(),
            "it returns authentified user"
        );
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     * @throws Exception
     */
    public function testBuildFromRequestWithHeader()
    {
        // given
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest("", $user, "header");

        // when
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, Config::load());

        // then
        $this->assertEquals(
            $user,
            $currentUser->getUser(),
            "it returns authentified user"
        );
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     * @throws Exception
     */
    public function testBuildFromRequestWithoutToken()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // given
        $request = new Request();
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, Config::load());

        // when
        $currentUser->getUser();
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     * @throws Exception
     */
    public function testBuildFromRequestWithInvalidToken()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // given
        $request = new Request();
        $request->headers->set("AuthToken", "InvalidToken");
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, Config::load());

        // when
        $currentUser->getUser();
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     * @throws Exception
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
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, Config::load());

        // when
        $currentUser->getUser();
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     * @throws Exception
     */
    public function testBuildFromRequestWithDeletedUser()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // given
        $session = ModelFactory::createUserSession();
        $session->setUserId(12345);
        $request = new Request();
        $request->headers->set("AuthToken", $session->getToken());
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, Config::load());

        // when
        $currentUser->getUser();
    }

    /**
     * @throws PropelException
     */
    public function testSetUser()
    {
        // given
        $user = ModelFactory::createUser();
        $currentUser = new CurrentUser(null, "token");

        // when
        $currentUser->setUser($user);

        // then
        $this->assertEquals(
            $user,
            $currentUser->getAxysAccount(),
            "it sets the user"
        );
        $this->assertNull(
            $currentUser->getToken(),
            "it sets the token to null"
        );
    }

    /**
     * @throws PropelException
     */
    public function testIsAuthentifiedForUser()
    {
        // given
        $user = ModelFactory::createUser();

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
        $site = ModelFactory::createSite();
        $user = ModelFactory::createAdminUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $isAdmin = $currentUser->isAdmin();

        // then
        $this->assertTrue(
            $isAdmin,
            "returns true for admin user"
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
     * @throws Exception
     */
    public function testIsAdminForSiteReturnsTrueForAdmin()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createAdminUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $isAdminForSite = $currentUser->isAdminForSite($site);

        // then
        $this->assertTrue(
            $isAdminForSite,
            "returns true for admin user"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testIsAdminForSiteReturnsFalseForNonAdmin()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $isAdminForSite = $currentUser->isAdminForSite($site);

        // then
        $this->assertFalse(
            $isAdminForSite,
            "returns false for simple user"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasRightForPublisherWithPublisherUser()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $user = ModelFactory::createPublisherUser(publisher: $publisher);
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
        $user = ModelFactory::createUser();
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
        $user = ModelFactory::createPublisherUser(publisher: $userPublisher);
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
        $user = ModelFactory::createPublisherUser();
        $currentUser = new CurrentUser($user, "token");

        // when
        $hasRightforPublisher = $currentUser->hasPublisherRight();

        // then
        $this->assertTrue(
            $hasRightforPublisher,
            "returns true for user with rights for at least one publisher"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasPublisherRightWithNonPublisher()
    {
        // given
        $user = ModelFactory::createUser();
        $currentUser = new CurrentUser($user, "token");

        // when
        $hasRightforPublisher = $currentUser->hasPublisherRight();

        // then
        $this->assertFalse(
            $hasRightforPublisher,
            "returns false for user with no publisher rights"
        );
    }

    /**
     * @throws PropelException
     */
    public function testHasPublisherRightForAnonymousUser()
    {
        // given
        $currentUser = new CurrentUser(null, "token");

        // when
        $hasRightforPublisher = $currentUser->hasPublisherRight();

        // then
        $this->assertFalse(
            $hasRightforPublisher,
            "returns false for anonymous user"
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetCurrentRight()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $user = ModelFactory::createPublisherUser(publisher: $publisher);
        $currentUser = new CurrentUser($user, "token");

        // when
        $right = $currentUser->getCurrentRight();

        // then
        $this->assertEquals(
            $publisher,
            $right->getPublisher(),
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetOption()
    {
        // given
        $user = ModelFactory::createUser();
        $option = new Option();
        $option->setUser($user);
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
        $user = ModelFactory::createUser();
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
     * @throws Exception
     */
    public function testGetUserToken()
    {
        // given
        $request = RequestFactory::createAuthRequest();
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, Config::load());
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
     * @throws Exception
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
        $this->assertNull($anonymousUserCart->getUserId());
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
        $this->assertNull($anonymousUserCart->getUserId());
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
        $user = ModelFactory::createUser();
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
        $user = ModelFactory::createUser();
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
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest("", $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $userCart = $currentUser->getOrCreateCart();

        // then
        $this->assertEquals($user->getId(), $userCart->getUserId());
        $this->assertEquals("web", $userCart->getType());
        $this->assertNull($userCart->getUid());
    }

    public function testGetEmail()
    {
        // given
        $user = new User();
        $user->setEmail("get-email@biblys.fr");
        $currentUser = new CurrentUser($user, "token");

        // when
        $email = $currentUser->getEmail();

        // then
        $this->assertEquals("get-email@biblys.fr", $email);
    }

    /**
     * #authUser
     */

    public function testAuthUserForAnonymousUser()
    {
        // given
        $currentUser = new CurrentUser(null, "token");

        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // when
        $currentUser->authUser();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAuthUserForAuthentifiedUser()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectNotToPerformAssertions();

        // when
        $currentUser->authUser();
    }

    /**
     * #authAdmin
     */

    /**
     * @throws Exception
     */
    public function testAuthAdminForAnonymousUser()
    {
        // given
        $currentUser = new CurrentUser(null, "token");

        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // when
        $currentUser->authAdmin();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAuthAdminForNonAdmin()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Accès réservé aux administrateurs");

        // when
        $currentUser->authAdmin();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAuthAdminForAdmin()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createAdminUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectNotToPerformAssertions();

        // when
        $currentUser->authAdmin();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAuthAdminForNonAdminWithErrorMessage()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("You shall not pass!");

        // when
        $currentUser->authAdmin(errorMessage: "You shall not pass!");
    }

    /**
     * #authPublisher (for specific publisher)
     */

    /**
     * @throws Exception
     */
    public function testAuthPublisherWithSpecificPublisherForAnonymousUser()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $currentUser = new CurrentUser(null, "token");

        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // when
        $currentUser->authPublisher($publisher);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAuthPublisherWithSpecificPublisherForAuthentifiedUser()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "STOP");
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Vous n'avez pas le droit de gérer l'éditeur STOP");

        // when
        $currentUser->authPublisher($publisher);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAuthPublisherWithSpecificPublisherForPublisherUser()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $site = ModelFactory::createSite();
        $user = ModelFactory::createPublisherUser(site: $site, publisher: $publisher);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectNotToPerformAssertions();

        // when
        $currentUser->authPublisher($publisher);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAuthPublisherWithSpecificPublisherForAdmin()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $site = ModelFactory::createSite();
        $user = ModelFactory::createAdminUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectNotToPerformAssertions();

        // when
        $currentUser->authPublisher($publisher);
    }

    /**
     * #authPublisher (for any publisher)
     */

    /**
     * @throws Exception
     */
    public function testAuthPublisherForAnonymousUser()
    {
        // given
        $currentUser = new CurrentUser(null, "token");

        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // when
        $currentUser->authPublisher();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAuthPublisherForAuthentifiedUser()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Vous n'avez pas le droit de gérer une maison d'édition.");

        // when
        $currentUser->authPublisher();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAuthPublisherForPublisherUser()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $site = ModelFactory::createSite();
        $user = ModelFactory::createPublisherUser(site: $site, publisher: $publisher);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectNotToPerformAssertions();

        // when
        $currentUser->authPublisher();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAuthPublisherForAdmin()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createAdminUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectNotToPerformAssertions();

        // when
        $currentUser->authPublisher();
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
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest(user: $user);
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
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        ModelFactory::createCart(site: $site, user: $user);

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
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $cart = ModelFactory::createCart(site: $site, user: $user);
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);

        // when
        $hasArticleInCart = $currentUser->hasArticleInCart($article);

        // then
        $this->assertTrue($hasArticleInCart);
    }

    /**
     * @throws PropelException
     */
    public function testHasStockItemInCartWithoutCart()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $stockItem = ModelFactory::createStockItem();
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $hasStockItemInCart = $currentUser->hasStockItemInCart($stockItem);

        // then
        $this->assertFalse($hasStockItemInCart);
    }

    /**
     * @throws PropelException
     */
    public function testHasStockItemInCartWithoutStockItemInCart()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $stockItem = ModelFactory::createStockItem();
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        ModelFactory::createCart(site: $site, user: $user);

        // when
        $hasStockItemInCart = $currentUser->hasStockItemInCart($stockItem);

        // then
        $this->assertFalse($hasStockItemInCart);
    }

    /**
     * @throws PropelException
     */
    public function testHasStockItemInCartWithStockItemInCart()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $cart = ModelFactory::createCart(site: $site, user: $user);
        $stockItem = ModelFactory::createStockItem(site: $site, cart: $cart);

        // when
        $hasStockItemInCart = $currentUser->hasStockItemInCart($stockItem);

        // then
        $this->assertTrue($hasStockItemInCart);
    }

    /**
     * @throws PropelException
     */
    public function testCartTransferWhenVisitorHasNoCookie()
    {
        // given
        $axysAccount = ModelFactory::createAxysAccount();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentUser = new CurrentUser($axysAccount, null);
        $currentUser->injectCurrentSite($currentSite);

        // when
        $currentUser->transfertVisitorCartToUser(null);

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws PropelException
     */
    public function testCartTransferWhenVisitorCartDoesNotExist()
    {
        // given
        $user = ModelFactory::createUser();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentUser = new CurrentUser($user, null);
        $currentUser->injectCurrentSite($currentSite);

        // when
        $currentUser->transfertVisitorCartToUser("visitor-token");

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws PropelException
     */
    public function testCartTransferWhenVisitorCartExists()
    {
        // given
        $site = ModelFactory::createSite();
        $visitorCart = ModelFactory::createCart(site: $site, uniqueId: "visitor-token");
        $stockItemInVisitorCart = ModelFactory::createStockItem(site: $site, cart: $visitorCart, sellingPrice: 999);
        $user = ModelFactory::createUser();
        $userCart = ModelFactory::createCart(site: $site, user: $user);
        $stockItemInUserCart = ModelFactory::createStockItem(site: $site, cart: $userCart, sellingPrice: 2499);


        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentUser = new CurrentUser($user, null);
        $currentUser->injectCurrentSite($currentSite);

        // when
        $currentUser->transfertVisitorCartToUser("visitor-token");

        // then
        $this->assertEquals(
            $userCart->getId(),
            $stockItemInVisitorCart->getCartId(),
            "it transfers item in visitor cart to user cart"
        );
        $this->assertEquals(
            $userCart->getId(),
            $stockItemInUserCart->getCartId(),
            "it keeps item already in user cart"
        );
        $this->assertEquals(3498, $userCart->getAmount(), "it updates user cart amount");
        $this->assertEquals(2, $userCart->getCount(), "it updates user cart count");
        $deletedVisitorCart = CartQuery::create()->findPk($visitorCart->getId());
        $this->assertNull($deletedVisitorCart, "it deletes visitor cart");
    }
}
