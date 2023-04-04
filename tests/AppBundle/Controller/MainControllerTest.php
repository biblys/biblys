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
use Biblys\Service\CurrentUser;
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
        $this->markTestSkipped("Flaky test");

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
        $page = ModelFactory::createPage([
            "page_title" => "Home",
            "page_url" => "home",
            "site_id" => $site->get("id"),
        ]);
        $site->setOpt("home", "page:home");
        $config = new Config();
        $config->set("site", $site->get("site_id"));
        $mailer = new Mailer();
        $session = new Session();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);

        // when
        $response = $controller->homeAction(
            $request,
            $session,
            $mailer,
            $config,
            $currentSite,
            $urlGenerator
        );

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
        $config->set("cloud", ["expires" => "2020-01-01"]);
        $updater = $this->createMock(Updater::class);
        $updater->method("isUpdateAvailable")->willReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = new CloudService($config);

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
     * @throws PropelException
     * @throws UpdaterException
     * @throws AuthException
     * @throws GuzzleException
     */
    public function testAdminWithHotNews()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $hotNews = ["date" => "2019-04-28", "message" => "Un message à caractère informatif", "link" => "https://www.biblys.fr"];
        $config->set("cloud", ["hot_news" => $hotNews]);
        $updater = $this->createMock(Updater::class);
        $updater->method("isUpdateAvailable")->willReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = new CloudService($config);

        // when
        $response = $controller->adminAction($request, $config, $updater, $urlGenerator, $cloud);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "returns HTTP 200");
        $this->assertStringContainsString(
            "Un message à caractère informatif",
            $response->getContent(),
            "displays the hot news message"
        );
    }

    /**
     * @throws PropelException
     * @throws UpdaterException
     * @throws AuthException
     * @throws GuzzleException
     */
    public function testAdminWithHotNewsMarkedAsRead()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $hotNews = ["date" => "2019-04-28", "message" => "Un message à caractère informatif", "link" => "https://www.biblys.fr"];
        $config->set("cloud", ["hot_news" => $hotNews]);
        $updater = $this->createMock(Updater::class);
        $updater->method("isUpdateAvailable")->willReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = new CloudService($config);
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("getOption")->willReturn("1");

        // when
        $response = $controller->adminAction($request, $config, $updater, $urlGenerator, $cloud, $currentUser);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "returns HTTP 200");
        $this->assertStringNotContainsString(
            "Un message à caractère informatif",
            $response->getContent(),
            "displays the hot news message"
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
        $updater = $this->createMock(Updater::class);
        $updater->method("isUpdateAvailable")->willReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = $this->createMock(CloudService::class);
        $cloud->method("isConfigured")->willReturn(true);
        $cloud->method("getSubscription")->willReturn(null);

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
    public function testAdminWithUnpaidCloudSubscription()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $config->set("environment", "test");
        $config->set("cloud", ["customer_id" => "12345"]);
        $updater = $this->createMock(Updater::class);
        $updater->method("isUpdateAvailable")->willReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloudSubscription = $this->createMock(CloudSubscription::class);
        $cloudSubscription->method("isPaid")->willReturn(false);
        $cloudService = $this->createMock(CloudService::class);
        $cloudService->method("isConfigured")->willReturn(true);
        $cloudService->method("getSubscription")->willReturn($cloudSubscription);

        // when
        $response = $controller->adminAction($request, $config, $updater, $urlGenerator, $cloudService);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "returns HTTP 200"
        );
        $this->assertStringContainsString(
            "Votre abonnement Biblys Cloud a expiré.",
            $response->getContent(),
            "displays the warning"
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
            "it should contain the update notifications badge"
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
     * @throws PropelException
     * @throws GuzzleException
     */
    public function testAdminCloudWithUnpaidSubscription() {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = $this->createMock(Config::class);
        $config->method("get")->willReturn(true);
        $cloudSubscription = $this->createMock(CloudSubscription::class);
        $cloudService = $this->createMock(CloudService::class);
        $cloudService->method("getSubscription")->willReturn($cloudSubscription);

        // when
        $response = $controller->adminCloud($request, $config, $cloudService);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "returns HTTP 200");
        $this->assertStringContainsString("Votre abonnement a expiré.", $response->getContent(), "displays warning");
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

    /**
     * @throws PropelException
     */
    public function testHotNewsMarkAsRead()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("main_admin")
            ->willReturn("admin-dashboard-url");
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser
            ->expects($this->once())
            ->method("setOption")
            ->with("hot_news_read", 1);

        // when
        $response = $controller->hotNewsMarkAsRead($request, $urlGenerator, $currentUser);

        // then
        $this->assertEquals(302, $response->getStatusCode(), "returns HTTP 302");
        $this->assertEquals(
            "admin-dashboard-url",
            $response->getTargetUrl(),
            "redirects to the admin dashboard"
        );
    }
}
