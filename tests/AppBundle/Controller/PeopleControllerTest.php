<?php

namespace AppBundle\Controller;

use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../setUp.php";

class PeopleControllerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAuthorsAction()
    {
        // given
        ModelFactory::createArticle([], [
            EntityFactory::createPeople(),
            EntityFactory::createPeople(),
        ]);
        $controller = new PeopleController();
        $request = new Request();
        $GLOBALS["container"] = require_once __DIR__."/../../../src/container.php";

        // when
        $response = $controller->authorsAction($request);

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }
}
