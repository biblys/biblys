<?php

use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once "setUp.php";

class VisitorTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testConstructor()
    {
        // given
        $user = ModelFactory::createAxysAccount();
        $request = RequestFactory::createAuthRequest("", $user);

        // when
        $visitor = new Visitor($request);

        // then
        $this->assertEquals(
            $user->getId(),
            $visitor->get('id'),
            "should set user when request has a session token"
        );
    }
}
