<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\Mailer;
use Biblys\Service\Updater\Updater;
use Biblys\Service\Updater\UpdaterException;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__."/../../../tests/setUp.php";

class MainControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testHomeAsStaticPage()
    {
        // given
        $controller = new MainController();
        $request = new Request();
        $site = EntityFactory::createSite();
        $page = ModelFactory::createPage(["page_title" => "Home", "site_id" => $site->get("id")]);
        $site->setOpt("home", "page:{$page->getId()}");
        $config = new Config();
        $config->set("site", $site->get("site_id"));
        $mailer = new Mailer();
        $session = new Session();

        // when
        $response = $controller->homeAction($request, $session, $mailer, $config);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Home",
            $response->getContent(),
            "it should display the home page title message"
        );
    }

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
     * @throws UpdaterException
     * @throws AuthException
     */
    public function testAdmin()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $updater = new Updater('', '3.0', $config);
        $this->_mockContainerWithUrlGenerator();

        // when
        $response = $controller->adminAction($request, $config, $updater);

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
     * @throws AuthException
     * @throws PropelException
     * @throws UpdaterException
     */
    public function testAdminWithCloudWarning()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("cloud", ["expires" => "2019-04-28"]);
        $this->_mockContainerWithUrlGenerator();
        $updater = new Updater('', '3.0', $config);

        // when
        $response = $controller->adminAction($request, $config, $updater);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Votre abonnement Biblys Cloud a expiré le 28 avril 2019.",
            $response->getContent(),
            "it should display the warning"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws UpdaterException
     */
    public function testAdminWithUpdates()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("cloud", ["expires" => "2018-01-01"]);
        $this->_mockContainerWithUrlGenerator();
        $updater = $this->createMock(Updater::class);
        $updater->method("isUpdateAvailable")->willReturn(true);

        // when
        $response = $controller->adminAction($request, $config, $updater);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            'Mise à jour
                          <span class="icon-badge">1</span>',
            $response->getContent(),
            "it should return HTTP 200"
        );
    }


    /**
     * @return void
     */
    public function _mockContainerWithUrlGenerator(): void
    {
        $urlgenerator = $this->createMock(UrlGenerator::class);
        $urlgenerator->method("generate")->willReturn("/some/url");
        $GLOBALS["container"] = $this->createMock(ContainerInterface::class);
        $GLOBALS["container"]->method("get")->willReturn($urlgenerator);
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testAdminCloud() {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("cloud", [
            "expires" => "1999-12-31",
            "domains" => ["librys.fr", "librairieys.fr"],
        ]);

        // when
        $response = $controller->adminCloud($request, $config);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Abonnement Biblys Cloud",
            $response->getContent(),
            "it should display the title"
        );
        $this->assertStringContainsString(
            "Date d'expiration : 31 décembre 1999",
            $response->getContent(),
            "it should display expiration date"
        );
        $this->assertStringContainsString(
            "Domaines inclus : librys.fr, librairieys.fr",
            $response->getContent(),
            "it should display expiration date"
        );
    }
}
