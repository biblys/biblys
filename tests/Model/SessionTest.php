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
    public function testBuildForUserAndCurrentSite()
    {
        // given
        $user = ModelFactory::createAxysAccount();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $expiresAt = new DateTime("+1 day");

        // when
        $session1 = new Session();
        $session1->setAxysAccount($user);
        $session1->setSite($currentSite->getSite());
        $session1->setToken(Session::generateToken());
        $session1->setExpiresAt($expiresAt);
        $session = $session1;

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
