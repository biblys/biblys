<?php

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Test\RequestFactory;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

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
        $request = new Request();
        $request->query->set("page", "bientot");
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);

        // when
        $response = $legacyController->defaultAction($request, $session, $mailer, $config, $currentSite);

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
        $request = new Request();
        $request->query->set("page", "log_page");
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);

        // when
        $legacyController->defaultAction($request, $session, $mailer, $config, $currentSite);
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
        $request = RequestFactory::createAuthRequest();
        $request->query->set("page", "pub_page");
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);

        // when
        $legacyController->defaultAction($request, $session, $mailer, $config, $currentSite);
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
        $request = RequestFactory::createAuthRequestForPublisherUser();
        $request->query->set("page", "adm_page");
        $session = new Session();
        $mailer = new Mailer();
        $legacyController = new LegacyController();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);

        // when
        $legacyController->defaultAction($request, $session, $mailer, $config, $currentSite);
    }
}
