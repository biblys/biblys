<?php

namespace AppBundle\Controller\Legacy;

use AppBundle\Controller\LegacyController;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Biblys\Test\EntityFactory;
use CartManager;
use Exception;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../../setUp.php";

class CartTest extends TestCase
{
    /**
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function testCartDisplay()
    {
        global $_SITE;

        // given
        $_SITE->setOpt("virtual_stock", 1);
        $flashBag = $this
            ->getMockBuilder("Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface")
            ->getMock();
        $flashBag->method("get")->willReturn([]);
        $session = $this
            ->getMockBuilder("Symfony\Component\HttpFoundation\Session\Session")
            ->getMock();
        $session->method("getFlashBag")->willReturn($flashBag);
        $request = new Request();
        $request->query->set("page", "cart");
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $article = EntityFactory::createArticle([
            "article_title" => "Papeete",
            "type_id" => 1,
        ]);
        $cm = new CartManager();
        $cm->addArticle($cart, $article);
        $mailer = new Mailer();
        $config = new Config();
        $currentSite = CurrentSite::buildFromConfig($config);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $legacyController = new LegacyController();
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTagsService,
            request: $request,
        );

        // when
        $response = $legacyController->defaultAction(
            request: $request,
            session: $session,
            mailer: $mailer,
            config: $config,
            currentSite: $currentSite,
            currentUser: $currentUser,
            urlGenerator: $urlGenerator,
            templateService: $templateService
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertStringContainsString(
            "Papeete",
            $response->getContent(),
            "it should display article in cart"
        );
        $this->assertStringContainsString(
            "Finaliser votre commande",
            $response->getContent(),
            "it should display the finalize order button"
        );
    }
}