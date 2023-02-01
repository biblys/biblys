<?php

namespace AppBundle\Controller;

use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Model\LinkQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

require_once __DIR__."/../../setUp.php";

class LinkControllerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testDeleteActionByUser()
    {
        // given
        $controller = new LinkController();
        $request = new Request();

        // then
        $this->expectException(UnauthorizedHttpException::class);

        // when
        $controller->deleteAction($request, 1);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddTagsActionForPublisher()
    {
        // given
        $controller = new LinkController();
        $request = RequestFactory::createAuthRequestForPublisherUser();
        $link = ModelFactory::createLink();

        // when
        $response = $controller->deleteAction($request, $link->getId());

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
