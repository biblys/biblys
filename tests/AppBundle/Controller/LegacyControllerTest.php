<?php

namespace AppBundle\Controller;

use Biblys\Test\RequestFactory;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once(__DIR__."/../../setUp.php");

class LegacyControllerTest extends TestCase
{
    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDefaultAction()
    {
        // given
        $controller = new LegacyController();
        $request = new Request();
        $request->query->set("page", "bientot");

        // when
        $response = $controller->defaultAction($request);

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "it should respond with status code 200"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDefaultActionRequiringLogin()
    {
        // then
        $this->expectException("Framework\Exception\AuthException");
        $this->expectExceptionMessage("Identification requise");

        // given
        $controller = new LegacyController();
        $request = new Request();
        $request->query->set("page", "log_page");

        // when
        $controller->defaultAction($request);
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDefaultActionRequiringPublisherRight()
    {
        // then
        $this->expectException("Framework\Exception\AuthException");
        $this->expectExceptionMessage("Vous n'avez pas l'autorisation de modifier un éditeur.");

        // given
        $controller = new LegacyController();
        $request = RequestFactory::createAuthRequest();
        $request->query->set("page", "pub_page");

        // when
        $controller->defaultAction($request);
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDefaultActionRequiringAdminRight()
    {
        // then
        $this->expectException("Framework\Exception\AuthException");
        $this->expectExceptionMessage("Accès réservé aux administrateurs.");

        // given
        $controller = new LegacyController();
        $request = RequestFactory::createAuthRequestForPublisherUser();
        $request->query->set("page", "adm_page");

        // when
        $controller->defaultAction($request);
    }
}
