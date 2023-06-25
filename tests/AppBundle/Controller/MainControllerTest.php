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
use Biblys\Service\TemplateService;
use Biblys\Service\Updater\Updater;
use Biblys\Service\Updater\UpdaterException;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../../tests/setUp.php";

class MainControllerTest extends TestCase
{
    /**
     * @throws SyntaxError
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
            "Bienvenue sur votre nouveau site Biblys",
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
        ModelFactory::createPage([
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
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws Exception
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
        $currentUserService = $this->createMock(CurrentUser::class);
        $currentUserService->method("isAuthentified")->willReturn(false);
        $config = Config::load();
        $currentSiteService = $this->createMock(CurrentSite::class);
        $templateService = new TemplateService($config, $currentSiteService, $currentUserService);
        $mailer = $this->createMock(Mailer::class);

        // when
        $response = $controller->contactAction(
            $request,
            $currentUserService,
            $templateService,
            $mailer,
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Votre message a bien été envoyé.",
            $response->getContent(),
            "it should display an error message"
        );
    }

    /**
     * @throws PropelException
     * @throws UpdaterException
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
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("getOption")->willReturn("1");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("downloadable_publishers")->willReturn(null);

        // when
        $response = $controller->adminAction($request, $config, $urlGenerator, $cloud, $currentUser, $currentSite);

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
        $this->assertStringNotContainsString(
            "Numérique",
            $response->getContent(),
            "hides ebooks section",
        );
    }

    /**
     * @throws PropelException
     * @throws UpdaterException
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
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("getOption")->willReturn(null);
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->adminAction($request, $config, $urlGenerator, $cloud, $currentUser, $currentSite);

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
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->adminAction($request, $config, $urlGenerator, $cloud, $currentUser, $currentSite);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "returns HTTP 200");
        $this->assertStringNotContainsString(
            "Un message à caractère informatif",
            $response->getContent(),
            "displays the hot news message"
        );
    }

    /**
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
        $cloudSubscription->method("isActive")->willReturn(false);
        $cloudService = $this->createMock(CloudService::class);
        $cloudService->method("isConfigured")->willReturn(true);
        $cloudService->method("getSubscription")->willReturn($cloudSubscription);
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("getOption")->willReturn("1");
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->adminAction($request, $config, $urlGenerator, $cloudService, $currentUser, $currentSite);

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
        $this->assertStringNotContainsString(
            "Raccourcis",
            $response->getContent(),
            "does not displays admin dashboard"
        );
    }

    /**
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
        $currentUser = $this->createMock(CurrentUser::class);

        // when
        $response = $controller->adminShortcutsAction($request, $urlGenerator, $currentUser);

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
     * @throws PropelException
     * @throws UpdaterException
     * @throws GuzzleException
     */
    public function testAdminWithEbooks()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();
        $updater = $this->createMock(Updater::class);
        $updater->method("isUpdateAvailable")->willReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = new CloudService($config);
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("getOption")->willReturn(null);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("downloadable_publishers")->willReturn("1");

        // when
        $response = $controller->adminAction($request, $config, $urlGenerator, $cloud, $currentUser, $currentSite);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "returns HTTP 200");
        $this->assertStringContainsString(
            "Numérique",
            $response->getContent(),
            "displays the ebooks section"
        );
    }

    /**
     * @throws PropelException
     * @throws UpdaterException
     * @throws GuzzleException
     * @throws Exception
     */
    public function testAdminWithSmtpAlert()
    {
        // given
        $controller = new MainController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config(["smtp" => null]);
        $updater = $this->createMock(Updater::class);
        $updater->method("isUpdateAvailable")->willReturn(false);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = $this->createMock(CloudService::class);
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("getOption")->willReturn(null);
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->adminAction($request, $config, $urlGenerator, $cloud, $currentUser, $currentSite);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "returns HTTP 200");
        $this->assertStringContainsString(
            "L'envoi de courriel est désactivé car aucun serveur SMTP n'est configuré.",
            $response->getContent(),
            "displays the ebooks section"
        );
    }

    /**
     * @throws GuzzleException
     * @throws PropelException
     */
    public function testAdminCloudPortal()
    {
        // given
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->query->set("return_url", "return-url");
        $controller = new MainController();
        $cloudService = $this->createMock(CloudService::class);
        $cloudService->method("getPortalUrl")
            ->with("return-url")
            ->willReturn("https://stripe.com/portal?return-url");

        // when
        $response = $controller->adminCloudPortal($request, $cloudService);

        // then
        $this->assertEquals(
            302,
            $response->getStatusCode(),
            "it should return HTTP 302"
        );
        $this->assertEquals(
            "https://stripe.com/portal?return-url",
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
