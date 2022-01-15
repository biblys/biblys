<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use AppBundle\Controller\MainController;
use Biblys\Service\Config;
use Biblys\Test\RequestFactory;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../../tests/setUp.php";

class MainControllerTest extends PHPUnit\Framework\TestCase
{
    public function testContact()
    {
        // given
        $controller = new MainController();
        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->request->set("email", "angry.customer.666.@biblys.fr");
        $request->request->set("name", "Angry Customer");
        $request->request->set("subject", "I'm angry");
        $request->request->set("message", "WHAT THE F");

        // when
        $response = $controller->contactAction($request);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "L&#039;adresse angry.customer.666.@biblys.fr est invalide.",
            $response->getContent(),
            "it should display an error message"
        );
    }

    /**
     * @throws PropelException
     */
    public function testAdmin()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();

        // when
        $response = $controller->adminAction($request, $config);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Administration Biblys",
            $response->getContent(),
            "it should display the title"
        );
    }

    /**
     * @throws PropelException
     */
    public function testAdminWithCloudWarning()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("cloud", ["expires" => "2018-01-01"]);

        // when
        $response = $controller->adminAction($request, $config);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Votre abonnement Biblys Cloud a expiré le 01/01/2018",
            $response->getContent(),
            "it should display the warning"
        );
    }
}
