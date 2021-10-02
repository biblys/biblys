<?php

namespace Model;

use Biblys\Test\EntityFactory;
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
        $user = EntityFactory::createUser();

        // when
        $session = Session::buildForUser($user);

        // then
        $this->assertNotNull($session->getToken(), "it creates a token");
        $this->assertEquals($user, $session->getUser(), "it associates given user");
        $this->assertTrue($session->getExpiresAt() > new DateTime(), "it sets an expire date in the future");
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
    }
}
