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


namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Mockery;
use Model\LinkQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

require_once __DIR__."/../../setUp.php";

class LinkControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddTagsActionForPublisher()
    {
        // given
        $controller = new LinkController();
        $link = ModelFactory::createLink();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive('authPublisher');

        // when
        $response = $controller->deleteAction(
            $currentUser,
            $link->getId()
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $deletedLink = LinkQuery::create()->findPk($link->getId());
        $this->assertNull($deletedLink, "it should delete the link");
    }
}
