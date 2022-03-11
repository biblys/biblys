<?php

namespace Biblys\Axys;

use Biblys\Service\Config;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Axys\LegacyClient;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__."/../../setUp.php";

class ClientTest extends TestCase
{
    public function testGetWidgetUrl()
    {
        // given
        $axys = new LegacyClient();

        // when
        $widgetUrl = $axys->getWidgetUrl();

        // then
        $this->assertEquals(
            "https://axys.me/widget.php?version=1",
            $widgetUrl,
            "it returns widget url"
        );
    }

    public function testGetWidgetUrlWithTokenAsInstanceProperty()
    {
        // given
        $axys = new LegacyClient([], "userToken12345");

        // when
        $widgetUrl = $axys->getWidgetUrl();

        // then
        $this->assertEquals(
            "https://axys.me/widget.php?version=1&UID=userToken12345",
            $widgetUrl,
            "it returns widget url with uid"
        );
    }

    /**
     * @throws PropelException
     */
    public function testBuildMenu()
    {
        $site = ModelFactory::createSite();
        $site->setWishlist(true );
        ModelFactory::createSiteOption($site, "alerts", true);
        $site->setVpc(true);
        $site->setShop(true);
        ModelFactory::createSiteOption($site, "show_elibrary", true);
        $config = new Config();
        $config->set("site", $site->getId());
        $request = new Request();
        $urlGenerator = $this->createMock(UrlGenerator::class);

        // when
        $menu = LegacyClient::buildMenu($config, $urlGenerator, $request);

        // then
        $this->assertStringContainsString("mes envies", $menu);
        $this->assertStringContainsString("mes alertes", $menu);
        $this->assertStringContainsString("mes commandes", $menu);
        $this->assertStringContainsString("mes achats", $menu);
        $this->assertStringContainsString("ma bibliothèque", $menu);
    }

    /**
     * @throws PropelException
     */
    public function testBuildMenuForPublisher()
    {
        $config = new Config();
        $request = RequestFactory::createAuthRequestForPublisherUser();
        $urlGenerator = $this->createMock(UrlGenerator::class);

        // when
        $menu = LegacyClient::buildMenu($config, $urlGenerator, $request);

        // then
        $this->assertStringContainsString("tableau de bord", $menu);
    }

    /**
     * @throws PropelException
     */
    public function testBuildMenuForAdministrator()
    {
        $config = new Config();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $urlGenerator = $this->createMock(UrlGenerator::class);

        // when
        $menu = LegacyClient::buildMenu($config, $urlGenerator, $request);

        // then
        $this->assertStringContainsString("administration", $menu);
    }
}
