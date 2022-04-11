<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

namespace AppBundle\Controller;

use Biblys\Service\Cloud\CloudService;
use Biblys\Service\Cloud\CloudSubscription;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Service\Updater\Updater;
use Biblys\Service\Updater\UpdaterException;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use DateTime;
use Exception;
use Framework\Exception\AuthException;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../../tests/setUp.php";

class MainControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testHomeWithDefaultTemplate()
    {
        // given
        $controller = new MainController();
        $request = new Request();
        $site = EntityFactory::createSite();
        $config = new Config();
        $config->set("site", $site->get("site_id"));
        $mailer = new Mailer();
        $session = new Session();
        $currentSite = CurrentSite::buildFromConfig($config);

        // when
        $response = $controller->homeAction($request, $session, $mailer, $config, $currentSite);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Bienvenue sur votre nouveau site Biblys !",
            $response->getContent(),
            "it should display the home page title message"
        );
    }

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
        $currentSite = CurrentSite::buildFromConfig($config);

        // when
        $response = $controller->homeAction($request, $session, $mailer, $config, $currentSite);

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

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
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
     * @throws GuzzleException
     */
    public function testAdmin()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("environment", "test");
        $updater = new Updater('', '3.0', $config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = $this->createMock(CloudService::class);

        // when
        $response = $controller->adminAction($request, $config, $updater, $urlGenerator, $cloud);

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
     * @throws GuzzleException
     */
    public function testAdminWithoutCloudSubscription()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("environment", "test");
        $config->set("cloud", ["customer_id" => "12345"]);
        $updater = new Updater('', '3.0', $config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = $this->createMock(CloudService::class);

        // when
        $response = $controller->adminAction($request, $config, $updater, $urlGenerator, $cloud);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Aucun abonnement Biblys Cloud en cours",
            $response->getContent(),
            "it should display the invite"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws UpdaterException
     * @throws GuzzleException
     */
    public function testAdminWithCloudSubscriptionExpiringSoon()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("environment", "test");
        $config->set("cloud", ["customer_id" => "12345"]);
        $updater = new Updater('', '3.0', $config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = $this->createMock(CloudService::class);
        $cloud->method("getSubscription")->willReturn(new CloudSubscription(
            "past_due",
            (new DateTime("2019-04-28"))->getTimestamp(),
        ));

        // when
        $response = $controller->adminAction($request, $config, $updater, $urlGenerator, $cloud);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Votre abonnement Biblys Cloud a expiré.",
            $response->getContent(),
            "it should display the warning"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws UpdaterException
     * @throws GuzzleException
     */
    public function testAdminWithCloudSubscriptionExpired()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("environment", "test");
        $config->set("cloud", ["customer_id" => "12345"]);
        $updater = new Updater('', '3.0', $config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = $this->createMock(CloudService::class);
        $cloud->method("getSubscription")->willReturn(new CloudSubscription(
            "past_due",
            (new DateTime("2019-04-28"))->getTimestamp(),
        ));

        // when
        $response = $controller->adminAction($request, $config, $updater, $urlGenerator, $cloud);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Votre abonnement Biblys Cloud a expiré.",
            $response->getContent(),
            "it should display the warning"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws UpdaterException
     * @throws GuzzleException
     */
    public function testAdminWithUpdates()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("cloud", ["expires" => "2018-01-01"]);
        $updater = $this->createMock(Updater::class);
        $updater->method("isUpdateAvailable")->willReturn(true);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = $this->createMock(CloudService::class);

        // when
        $response = $controller->adminAction($request, $config, $updater, $urlGenerator, $cloud);

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
     * @throws AuthException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws UpdaterException
     */
    public function testAdminShortcuts()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");

        // when
        $response = $controller->adminShortcutsAction($request, $urlGenerator);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Raccourcis",
            $response->getContent(),
            "it should display the title"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws GuzzleException
     */
    public function testAdminCloud() {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("cloud", [
            "domains" => ["librys.fr", "librairieys.fr"],
        ]);
        $cloud = $this->createMock(CloudService::class);
        $cloud->method("getSubscription")->willReturn(null);

        // when
        $response = $controller->adminCloud($request, $config, $cloud);

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
            "Aucun abonnement Biblys Cloud en cours",
            $response->getContent(),
            "it should display the invite"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws GuzzleException
     */
    public function testAdminCloudWithSubscription() {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("cloud", [
            "domains" => ["librys.fr", "librairieys.fr"],
        ]);
        $cloud = $this->createMock(CloudService::class);
        $cloud->method("getSubscription")->willReturn(new CloudSubscription(
            "active",
            (new DateTime("1999-12-31"))->getTimestamp(),
        ));

        // when
        $response = $controller->adminCloud($request, $config, $cloud);

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
            "Domaines inclus : librys.fr, librairieys.fr",
            $response->getContent(),
            "it should display expiration date"
        );
    }

    /**
     * @throws AuthException
     * @throws GuzzleException
     * @throws PropelException
     */
    public function testAdminCloudPortal()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $controller = new MainController();
        $cloud = $this->createMock(CloudService::class);
        $cloud->method("getPortalUrl")->willReturn("https://stripe.com/portal");

        // when
        $response = $controller->adminCloudPortal($request, $urlGenerator, $cloud);

        // then
        $this->assertEquals(
            302,
            $response->getStatusCode(),
            "it should return HTTP 302"
        );
        $this->assertEquals(
            "https://stripe.com/portal",
            $response->getTargetUrl(),
            "it should redirect to the stripe portal"
        );
    }

}
