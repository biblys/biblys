<?php

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
