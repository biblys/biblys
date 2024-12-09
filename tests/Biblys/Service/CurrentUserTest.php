<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Biblys\Service;

use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use DateTime;
use Exception;
use Mockery;
use Model\CartQuery;
use Model\Customer;
use Model\Option;
use Model\User;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);

        // when
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->assertEquals(
            $user,
            $currentUser->getUser(),
            "it returns authentified user"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testBuildFromRequestWithBadlyEncodedVisitorUidCookie()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $request = new Request();
        $latin1VisitorUid = mb_convert_encoding("Ånonymøus", "ISO-8859-1", "UTF-8");
        $request->cookies->set("visitor_uid", $latin1VisitorUid);

        // when
        $error = Helpers::runAndCatchException(fn() => CurrentUser::buildFromRequestAndConfig($request, $config));

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $error);
        $this->assertEquals("Cookies must use charset UTF-8", $error->getMessage());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testBuildFromRequestWithBadlyEncodedUserUidCookie()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $request = new Request();
        $latin1UserUid = mb_convert_encoding("Ånonymøus", "ISO-8859-1", "UTF-8");
        $request->cookies->set("user_uid", $latin1UserUid);

        // when
        $error = Helpers::runAndCatchException(fn() => CurrentUser::buildFromRequestAndConfig($request, $config));

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $error);
        $this->assertEquals("Cookies must use charset UTF-8", $error->getMessage());
    }

    /**
     * @throws UnauthorizedHttpException
     * @throws PropelException
     * @throws Exception
     */
    public function testBuildFromRequestWithHeader()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user, authMethod: "header");
        $config = new Config(["site" => $site->getId()]);

        // when
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->assertEquals(
            $user,
            $currentUser->getUser(),
            "it returns authentified user"
        );
    }


    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testBuildFromRequestWithBadlyEncodedHeader()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $request = new Request();
        $latin1UserUid = mb_convert_encoding("Ånonymøus", "ISO-8859-1", "UTF-8");
        $request->headers->set("AuthToken", $latin1UserUid);

        // when
        $error = Helpers::runAndCatchException(fn() => CurrentUser::buildFromRequestAndConfig($request, $config));

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $error);
        $this->assertEquals("Cookies must use charset UTF-8", $error->getMessage());
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
    public function testBuildFromRequestWithSessionFromOtherSite()
    {
        // given
        $currentSite = ModelFactory::createSite();
        $otherSite = ModelFactory::createSite();
        $userFromOtherSite = ModelFactory::createUser(site: $otherSite);
        $session = ModelFactory::createUserSession(user: $userFromOtherSite);
        $request = new Request();
        $request->headers->set("AuthToken", $session->getToken());
        $config = new Config(["site" => $currentSite->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

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
            $currentUser->getUser(),
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
            $currentUser->isAuthenticated(),
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
            $currentUser->isAuthenticated(),
            "it returns false if user is unauthentified"
        );
    }

    /**
     * #isAdmin
     */

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
    public function testIsAdminWithPublisherUser()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createPublisherUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $config = new Config(["site" => $site->getId()]);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $isAdmin = $currentUser->isAdmin();

        // then
        $this->assertFalse(
            $isAdmin,
            "returns false for publisher user"
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
    public function testHasRightForPublisherWithAnonymousUser()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $currentUser = new CurrentUser(null, "token");

        // when
        $hasRightforPublisher = $currentUser->hasRightForPublisher($publisher);

        // then
        $this->assertFalse(
            $hasRightforPublisher,
            "returns false for anonymous user"
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
        $userPublisher = ModelFactory::createPublisher(name: "YES");
        $otherPublisher = ModelFactory::createPublisher(name: "NO");
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
        $user = ModelFactory::createUser(site: $site);
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
        $user = ModelFactory::createUser(site: $site);
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
        $user = ModelFactory::createUser(site: $site);
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
        $user = ModelFactory::createUser(site: $site);
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
        $user = ModelFactory::createUser(site: $site);
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
        $user = ModelFactory::createUser();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentUser = new CurrentUser($user, null);
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

    /**
     * hasArticleInWishlist
     */

    /**
     * @throws PropelException
     */
    public function testHasArticleInWishlistForAnonymousUser()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $currentUser = new CurrentUser(null, null);

        // when
        $hasArticleInWishlist = $currentUser->hasArticleInWishlist($article);

        // then
        $this->assertFalse($hasArticleInWishlist);
    }

    /**
     * @throws PropelException
     */
    public function testHasArticleInWishlistWithoutCart()
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
        $hasArticleInWishlist = $currentUser->hasArticleInWishlist($article);

        // then
        $this->assertFalse($hasArticleInWishlist);
    }

    /**
     * @throws PropelException
     */
    public function testHasArticleInWishlistWithoutArticleInWishlist()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        ModelFactory::createWishlist(site: $site, user: $user);

        // when
        $hasArticleInWishlist = $currentUser->hasArticleInWishlist($article);

        // then
        $this->assertFalse($hasArticleInWishlist);
    }

    /**
     * @throws PropelException
     */
    public function testHasArticleInWishlistWithArticleInWishlist()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $wishlist = ModelFactory::createWishlist(site: $site, user: $user);
        ModelFactory::createWish($wishlist, $article);

        // when
        $hasArticleInWishlist = $currentUser->hasArticleInWishlist($article);

        // then
        $this->assertTrue($hasArticleInWishlist);
    }

    /**
     * hasAlertForArticle
     */

    /**
     * @throws PropelException
     */
    public function testHasAlertForArticleForAnonymousUser()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $currentUser = new CurrentUser(null, null);

        // when
        $hasAlertForArticle = $currentUser->hasAlertForArticle($article);

        // then
        $this->assertFalse($hasAlertForArticle);
    }

    /**
     * @throws PropelException
     */
    public function testHasAlertForArticleWithoutAlertForArticle()
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
        $hasAlertForArticle = $currentUser->hasAlertForArticle($article);

        // then
        $this->assertFalse($hasAlertForArticle);
    }

    /**
     * @throws PropelException
     */
    public function testHasArticleInWishlistWithAlertForArticle()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        ModelFactory::createAlert(site: $site, user: $user, article: $article);

        // when
        $hasAlertForArticle = $currentUser->hasAlertForArticle($article);

        // then
        $this->assertTrue($hasAlertForArticle);
    }

    /**
     * hasPurchasedArticle
     */

    /**
     * @throws PropelException
     */
    public function testHasPurchasedArticleForAnonymousUser()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $request = new Request();
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $hasPurchasedArticle = $currentUser->hasPurchasedArticle($article);

        // then
        $this->assertFalse($hasPurchasedArticle);
    }

    /**
     * @throws PropelException
     */
    public function testHasPurchasedArticleWhenUserDidNot()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $user = ModelFactory::createUser();
        $anotherUser = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        ModelFactory::createStockItem(site: $site, article: $article, user: $anotherUser);

        // when
        $hasPurchasedArticle = $currentUser->hasPurchasedArticle($article);

        // then
        $this->assertFalse($hasPurchasedArticle);
    }

    /**
     * @throws PropelException
     */
    public function testHasPurchasedArticleWhenUserDidPurchasedIt()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $article = ModelFactory::createArticle();
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        ModelFactory::createStockItem(site: $site, article: $article, user: $user);

        // when
        $hasPurchasedArticle = $currentUser->hasPurchasedArticle($article);

        // then
        $this->assertTrue($hasPurchasedArticle);
    }

    /**
     * getOrCreateCustomer
     */

    /**
     * @throws PropelException
     */
    public function testGetOrCreateCustomerWhenCustomerDoesNotExist()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

        // when
        $customer = $currentUser->getOrCreateCustomer();

        // then
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($site, $customer->getSite());
        $this->assertEquals($user, $customer->getUser());
    }

    /**
     * @throws PropelException
     */
    public function testGetOrCreateCustomerWhenCustomerExists()
    {
        // given
        $site = ModelFactory::createSite();
        $config = new Config();
        $config->set("site", $site->getId());
        $user = ModelFactory::createUser(site: $site);
        $request = RequestFactory::createAuthRequest(user: $user);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $givenCustomer = ModelFactory::createCustomer($site, $user);

        // when
        $returnedCustomer = $currentUser->getOrCreateCustomer();

        // then
        $this->assertEquals($givenCustomer, $returnedCustomer);
    }
}
