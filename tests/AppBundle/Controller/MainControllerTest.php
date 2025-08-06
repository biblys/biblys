<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

namespace AppBundle\Controller;

use Biblys\Exception\InvalidConfigurationException;
use Biblys\Service\Cloud\CloudService;
use Biblys\Service\Cloud\CloudSubscription;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\MetaTagsService;
use Biblys\Test\EntityFactory;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Mockery;
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
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHomeWithDefaultTemplate()
    {
        // given
        $controller = new MainController();
        $request = new Request();
        $site = EntityFactory::createSite();
        $config = new Config();
        $config->set("site", $site->get("site_id"));
        $config->set("environment", "test");
        $mailer = Mockery::mock(Mailer::class);
        $session = new Session();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->homeAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            metaTagsService: $metaTagsService,
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
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
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
        $mailer = Mockery::mock(Mailer::class);
        $session = new Session();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->homeAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            metaTagsService: $metaTagsService,
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
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
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
        $request->request->set("subject", "I'm very angry");
        $request->request->set("message", "WHAT THE F*CK IS HAPPENING?!");
        $request->request->set("phone", "");
        $currentUserService = $this->createMock(CurrentUser::class);
        $currentUserService->method("isAuthenticated")->willReturn(false);
        $config = Config::load();
        $site = ModelFactory::createSite();
        $currentSiteService = $this->createMock(CurrentSite::class);
        $currentSiteService->method("getSite")->willReturn($site);
        $templateService = Helpers::getTemplateService();
        $mailer = $this->createMock(Mailer::class);

        // when
        $response = $controller->contactAction(
            $request,
            $currentUserService,
            $templateService,
            $mailer,
            $config,
            $currentSiteService,
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
            "displays a success message"
        );
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testContactWithTooShortSubject()
    {
        // given
        $controller = new MainController();
        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->request->set("email", "angry.customer.666.@biblys.fr");
        $request->request->set("name", "Angry Customer");
        $request->request->set("subject", "NOPE");
        $request->request->set("message", "A message long enough to pass the test");
        $request->request->set("phone", "12345678");
        $currentUserService = $this->createMock(CurrentUser::class);
        $currentUserService->method("isAuthenticated")->willReturn(false);
        $config = Config::load();
        $currentSiteService = $this->createMock(CurrentSite::class);
        $templateService = Helpers::getTemplateService();
        $mailer = $this->createMock(Mailer::class);

        // when
        $response = $controller->contactAction(
            $request,
            $currentUserService,
            $templateService,
            $mailer,
            $config,
            $currentSiteService,
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Le sujet doit être long d&#039;au moins 6 caractères.",
            $response->getContent(),
            "displays an error message"
        );
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testContactWithTooShortBody()
    {
        // given
        $controller = new MainController();
        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->request->set("email", "angry.customer.666.@biblys.fr");
        $request->request->set("name", "Angry Customer");
        $request->request->set("subject", "I'm very angry");
        $request->request->set("message", "Court");
        $request->request->set("phone", "123456789");
        $currentUserService = $this->createMock(CurrentUser::class);
        $currentUserService->method("isAuthenticated")->willReturn(false);
        $config = Config::load();
        $currentSiteService = $this->createMock(CurrentSite::class);
        $templateService = Helpers::getTemplateService();
        $mailer = $this->createMock(Mailer::class);

        // when
        $response = $controller->contactAction(
            $request,
            $currentUserService,
            $templateService,
            $mailer,
            $config,
            $currentSiteService,
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Le message doit être long d&#039;au moins 10 caractères.",
            $response->getContent(),
            "displays an error message"
        );
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testContactWithFilledHoneyPotField()
    {
        // given
        $controller = new MainController();
        $request = new Request();
        $request->setMethod("POST");
        $request->headers->set("X-HTTP-METHOD-OVERRIDE", "POST");
        $request->request->set("email", "angry.customer.666.@biblys.fr");
        $request->request->set("name", "Angry Customer");
        $request->request->set("subject", "I'm very angry");
        $request->request->set("message", "WHAT THE F*CK");
        $request->request->set("phone", "+33.1.23.45.67.89");
        $currentUserService = $this->createMock(CurrentUser::class);
        $currentUserService->method("isAuthenticated")->willReturn(false);
        $config = Config::load();
        $currentSiteService = $this->createMock(CurrentSite::class);
        $templateService = Helpers::getTemplateService();
        $mailer = $this->createMock(Mailer::class);

        // when
        $response = $controller->contactAction(
            $request,
            $currentUserService,
            $templateService,
            $mailer,
            $config,
            $currentSiteService,
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Le message n&#039;a pas pu être envoyé.",
            $response->getContent(),
            "displays an error message"
        );
    }

    /**
     * @throws GuzzleException
     * @throws InvalidConfigurationException
     * @throws PropelException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testAdmin()
    {
        // given
        $controller = new MainController();
        $config = new Config();
        $config->set("environment", "test");
        $config->set("cloud", ["expires" => "2020-01-01"]);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = new CloudService($config, new Client());
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOption")->once()->andReturn("1");
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $currentUser->shouldReceive("setOption")->once()->with("last_version_known", BIBLYS_VERSION);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("downloadable_publishers")->willReturn(null);

        // when
        $response = $controller->adminAction($config, $urlGenerator, $cloud, $currentUser, $currentSite);

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
            "Invitations de téléchargement",
            $response->getContent(),
            "hides ebooks section",
        );
    }

    /**
     * @throws GuzzleException
     * @throws InvalidConfigurationException
     * @throws PropelException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminWithHotNews()
    {
        // given
        $controller = new MainController();
        $config = new Config();
        $hotNews = ["date" => "2019-04-28", "message" => "Un message à caractère informatif", "link" => "https://www.biblys.fr"];
        $config->set("cloud", ["hot_news" => $hotNews]);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = new CloudService($config, new Client());
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOption")->once()->andReturn(null);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $currentUser->shouldReceive("setOption")->once()->with("last_version_known", BIBLYS_VERSION);
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->adminAction($config, $urlGenerator, $cloud, $currentUser, $currentSite);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "returns HTTP 200");
        $this->assertStringContainsString(
            "Un message à caractère informatif",
            $response->getContent(),
            "displays the hot news message"
        );
    }

    /**
     * @throws GuzzleException
     * @throws InvalidConfigurationException
     * @throws PropelException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminWithHotNewsMarkedAsRead()
    {
        // given
        $controller = new MainController();
        $config = new Config();
        $hotNews = ["date" => "2019-04-28", "message" => "Un message à caractère informatif", "link" => "https://www.biblys.fr"];
        $config->set("cloud", ["hot_news" => $hotNews]);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = new CloudService($config, new Client());
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOption")->once()->andReturn("1");
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $currentUser->shouldReceive("setOption")->once()->with("last_version_known", BIBLYS_VERSION);
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->adminAction($config, $urlGenerator, $cloud, $currentUser, $currentSite);

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
     * @throws GuzzleException|\PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminWithoutCloudSubscription()
    {
        // given
        $controller = new MainController();
        $config = new Config();
        $config->set("environment", "test");
        $config->set("cloud", ["customer_id" => "12345"]);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloudService = $this->createMock(CloudService::class);
        $cloudService->method("isConfigured")->willReturn(true);
        $cloudService->method("getSubscription")->willReturn(null);
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("getOption")->willReturn("1");
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->adminAction(
            $config,
            $urlGenerator,
            $cloudService,
            $currentUser,
            $currentSite
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "returns HTTP 200"
        );
    }

    /**
     * @throws PropelException
     * @throws GuzzleException|\PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminWithUnpaidCloudSubscription()
    {
        // given
        $controller = new MainController();
        $config = new Config();
        $config->set("environment", "test");
        $config->set("cloud", ["customer_id" => "12345"]);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloudSubscription = $this->createMock(CloudSubscription::class);
        $cloudSubscription->method("isActive")->willReturn(false);
        $cloudService = $this->createMock(CloudService::class);
        $cloudService->method("isConfigured")->willReturn(true);
        $cloudService->method("getSubscription")->willReturn($cloudSubscription);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOption")->once()->andReturn("1");
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->adminAction($config, $urlGenerator, $cloudService, $currentUser, $currentSite);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "returns HTTP 200"
        );
        $this->assertStringContainsString(
            "Votre abonnement Biblys a expiré.",
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
     * @throws SyntaxError|\PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminShortcuts()
    {
        // given
        $controller = new MainController();
        $request = new Request();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();
        $currentUser->shouldReceive("getOption")->once()->with("shortcuts")->andReturn("");

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
     * @throws GuzzleException|\PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminCloud() {
        // given
        $controller = new MainController();
        $request = new Request();
        $config = new Config();
        $config->set("cloud", [
            "domains" => ["paronymie.fr"],
        ]);
        $cloud = $this->createMock(CloudService::class);
        $cloud->method("getSubscription")->willReturn(null);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->adminCloud($request, $config, $cloud, $currentUser);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Abonnement Biblys",
            $response->getContent(),
            "it should display the title"
        );
        $this->assertStringContainsString(
            "Aucun abonnement Biblys en cours",
            $response->getContent(),
            "it should display the invite"
        );
    }

    /**
     * @throws PropelException
     * @throws GuzzleException|\PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminCloudWithSubscription() {
        // given
        $controller = new MainController();
        $request = new Request();
        $config = new Config();
        $config->set("cloud", [
            "domains" => ["paronymie.fr"],
        ]);
        $cloud = $this->createMock(CloudService::class);
        $cloud->method("getSubscription")->willReturn(new CloudSubscription(
            "active",
        ));
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->adminCloud($request, $config, $cloud, $currentUser);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Abonnement Biblys",
            $response->getContent(),
            "it should display the title"
        );
        $this->assertStringContainsString(
            "Domaines inclus : paronymie.fr",
            $response->getContent(),
            "it should display expiration date"
        );
    }

    /**
     * @throws PropelException
     * @throws GuzzleException|\PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminCloudWithUnpaidSubscription() {
        // given
        $controller = new MainController();
        $request = new Request();
        $config = $this->createMock(Config::class);
        $config->method("get")->willReturn(true);
        $cloudSubscription = $this->createMock(CloudSubscription::class);
        $cloudService = $this->createMock(CloudService::class);
        $cloudService->method("getSubscription")->willReturn($cloudSubscription);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->adminCloud($request, $config, $cloudService, $currentUser);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "returns HTTP 200");
        $this->assertStringContainsString("Votre abonnement a expiré.", $response->getContent(), "displays warning");
    }

    /**
     * @throws GuzzleException
     * @throws PropelException
     * @throws InvalidConfigurationException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminWithEbooks()
    {
        // given
        $controller = new MainController();
        $config = new Config();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = new CloudService($config, new Client());
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("getOption")->willReturn(null);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("downloadable_publishers")->willReturn("1");

        // when
        $response = $controller->adminAction($config, $urlGenerator, $cloud, $currentUser, $currentSite);

        // then
        $this->assertEquals(200, $response->getStatusCode(), "returns HTTP 200");
        $this->assertStringContainsString(
            "Invitations de téléchargement",
            $response->getContent(),
            "displays the ebooks section"
        );
    }

    /**
     * @throws PropelException
     * @throws GuzzleException
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminWithSmtpAlert()
    {
        // given
        $controller = new MainController();
        $config = new Config(["smtp" => null]);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");
        $cloud = $this->createMock(CloudService::class);
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("getOption")->willReturn(null);
        $currentSite = $this->createMock(CurrentSite::class);

        // when
        $response = $controller->adminAction($config, $urlGenerator, $cloud, $currentUser, $currentSite);

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
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testAdminCloudPortal()
    {
        // given
        $request = new Request();
        $request->query->set("return_url", "return-url");
        $controller = new MainController();
        $cloudService = $this->createMock(CloudService::class);
        $cloudService->method("getPortalUrl")
            ->with("return-url")
            ->willReturn("https://stripe.com/portal?return-url");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->adminCloudPortal($request, $cloudService, $currentUser);

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
     * @throws PropelException|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHotNewsMarkAsRead()
    {
        // given
        $controller = new MainController();
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator
            ->method("generate")
            ->with("main_admin")
            ->willReturn("admin-dashboard-url");
        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser
            ->expects($this->once())
            ->method("setOption")
            ->with("cloud_news_read_at", $this->anything());
        $currentUser->expects($this->once())->method("authAdmin");

        // when
        $response = $controller->markCloudNewsAsReadAction($urlGenerator, $currentUser);

        // then
        $this->assertEquals(302, $response->getStatusCode(), "returns HTTP 302");
        $this->assertEquals(
            "admin-dashboard-url",
            $response->getTargetUrl(),
            "redirects to the admin dashboard"
        );
    }
}
