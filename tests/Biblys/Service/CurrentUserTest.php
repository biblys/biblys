<?php

namespace Biblys\Service;

use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use DateTime;
use Framework\Exception\AuthException;
use Model\SiteQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../setUp.php";

class CurrentUserTest extends TestCase
{

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testBuildFromRequestWithCookie()
    {
        // given
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest("", $user, "cookie");

        // when
        $currentUser = CurrentUser::buildFromRequest($request);

        // then
        $this->assertEquals(
            $user,
            $currentUser->getUser(),
            "it returns authentified user"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testBuildFromRequestWithHeader()
    {
        // given
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest("", $user, "header");

        // when
        $currentUser = CurrentUser::buildFromRequest($request);

        // then
        $this->assertEquals(
            $user,
            $currentUser->getUser(),
            "it returns authentified user"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testBuildFromRequestWithoutToken()
    {
        // then
        $this->expectException("Framework\Exception\AuthException");
        $this->expectExceptionMessage("Identification requise.");

        // given
        $request = new Request();
        $currentUser = CurrentUser::buildFromRequest($request);

        // when
        $currentUser->getUser();
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testBuildFromRequestWithInvalidToken()
    {
        // then
        $this->expectException("Framework\Exception\AuthException");
        $this->expectExceptionMessage("Identification requise.");

        // given
        $request = new Request();
        $request->headers->set("AuthToken", "InvalidToken");
        $currentUser = CurrentUser::buildFromRequest($request);

        // when
        $currentUser->getUser();
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testBuildFromRequestWithExpiredSession()
    {
        // then
        $this->expectException("Framework\Exception\AuthException");
        $this->expectExceptionMessage("Identification requise.");

        // given
        $session = ModelFactory::createUserSession();
        $session->setExpiresAt(new DateTime("yesterday"));
        $session->save();
        $request = new Request();
        $request->headers->set("AuthToken", $session->getToken());
        $currentUser = CurrentUser::buildFromRequest($request);

        // when
        $currentUser->getUser();
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testBuildFromRequestWithDeletedUser()
    {
        // then
        $this->expectException("Framework\Exception\AuthException");
        $this->expectExceptionMessage("Identification requise.");

        // given
        $session = ModelFactory::createUserSession();
        $session->setUser(null);
        $session->setUserId(12345);
        $request = new Request();
        $request->headers->set("AuthToken", $session->getToken());
        $currentUser = CurrentUser::buildFromRequest($request);

        // when
        $currentUser->getUser();
    }

    /**
     * @throws PropelException
     */
    public function testIsAuthentifiedForUser()
    {
        // given
        $user = ModelFactory::createUser();

        // when
        $currentUser = new CurrentUser($user);

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
        $currentUser = new CurrentUser($user);

        // then
        $this->assertFalse(
            $currentUser->isAuthentified(),
            "it returns false if user is unauthentified"
        );
    }

    public function testIsAdminForSite()
    {
        // given
        $config = new Config();
        $site = SiteQuery::create()->findOneById($config->get("site"));
        $admin = ModelFactory::createAdminUser();

        // when
        $currentUser = new CurrentUser($admin);

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
        $user = ModelFactory::createUser();

        // when
        $currentUser = new CurrentUser($user);

        // then
        $this->assertFalse(
            $currentUser->isAdminForSite($site),
            "it returns false for non-admin user"
        );
    }
}
