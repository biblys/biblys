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


use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Model\Session;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @throws PropelException
     */
    public function testIsPublisherReturnsTrueWhenUserHasRight(): void
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $request = RequestFactory::createAuthRequestForPublisherUser(publisher: $publisher);
        $visitor = new Visitor($request);

        // when / then
        $this->assertTrue(
            $visitor->isPublisher(),
            "should return true when user has a publisher right"
        );
    }

    /**
     * @throws PropelException
     */
    public function testConstructorIgnoresExpiredSession(): void
    {
        // given
        $user = ModelFactory::createUser();
        $session = new Session();
        $session->setUser($user);
        $session->setToken(Session::generateToken());
        $session->setExpiresAt(new DateTime('yesterday'));
        $session->save();

        $request = Request::create("", "", [], [], [], [], "");
        $request->cookies->set("user_uid", $session->getToken());

        // when
        $visitor = new Visitor($request);

        // then
        $this->assertNull(
            $visitor->get('id'),
            "should return null when session is expired"
        );
    }

    /**
     * @throws PropelException
     */
    public function testIsPublisherReturnsFalseWhenUserHasNoRight(): void
    {
        // given
        $user = ModelFactory::createUser();
        $request = RequestFactory::createAuthRequest("", $user);
        $visitor = new Visitor($request);

        // when / then
        $this->assertFalse(
            $visitor->isPublisher(),
            "should return false when user has no publisher right"
        );
    }
}
