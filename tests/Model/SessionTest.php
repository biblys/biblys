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
