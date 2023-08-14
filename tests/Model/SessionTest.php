<?php

namespace Model;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use DateTime;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class SessionTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testBuildForUser()
    {
        // given
        $user = ModelFactory::createUser();

        // when
        $session = Session::buildForUser($user);

        // then
        $this->assertNotNull($session->getToken(), "it creates a token");
        $this->assertEquals($user, $session->getAxysAccount(), "it associates given user");
        $this->assertTrue($session->getExpiresAt() > new DateTime(), "it sets an expire date in the future");
    }

    /**
     * @throws PropelException
     */
    public function testBuildForUserAndCurrentSite()
    {
        // given
        $user = ModelFactory::createUser();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $expiresAt = new DateTime("+1 day");

        // when
        $session = Session::buildForUserAndCurrentSite($user, $currentSite , $expiresAt);

        // then
        $this->assertNotNull($session->getToken(), "it creates a token");
        $this->assertEquals($user, $session->getAxysAccount(), "it associates given user");
        $this->assertEquals($site, $session->getSite(), "it associates current site");
        $this->assertEquals($expiresAt, $session->getExpiresAt(), "it sets an expire date in the future");
    }

    public function testGenerateToken()
    {
        // when
        $token = Session::generateToken();

        // then
        $this->assertEquals(
            32,
            strlen($token),
            "it should return a 32 chars string"
        );
        $this->assertMatchesRegularExpression(
            '/^[a-zA-Z\d]{32}$/',
            $token,
            "it should only contain alphanumeric characters"
        );
    }
}
