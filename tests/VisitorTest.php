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
        $user = ModelFactory::createUser();
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

    public function testSetCurrentRight()
    {
        // given
        $user = ModelFactory::createUser([
            "username" => "adminAndPublisher",
            "email" => "admin-and-publisher@biblys.fr",
        ]);
        $request = RequestFactory::createAuthRequest("", $user);
        $visitor = new Visitor($request);
        $rm = new RightManager();
        $oldRight = $rm->create(["user_id" => $user->getId(), "right_current" => 1]);
        $newRight = $rm->create(["user_id" => $user->getId()]);

        // when
        $visitor->setCurrentRight($newRight);

        // then
        $updatedNewRight = $rm->getById($newRight->get("id"));
        $this->assertEquals(
            1,
            $updatedNewRight->get("right_current"),
            "new right should be current"
        );
        $updatedOldRight = $rm->getById($oldRight->get("id"));
        $this->assertEquals(
            0,
            $updatedOldRight->get("right_current"),
            "new right should be not be current"
        );
    }
}
