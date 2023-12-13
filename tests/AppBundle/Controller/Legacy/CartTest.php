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
use Biblys\Test\ModelFactory;
use CartManager;
use Exception;
use Mockery;
use Model\ArticleQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Site;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../../setUp.php";

class CartTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCartDisplay()
    {
        global $_SITE;

        // given
        ModelFactory::createCountry();
        /* @var Site $_SITE */
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
            "article_url" => "author/papeete",
            "type_id" => 1,
        ]);
        $cm = new CartManager();
        $cm->addArticle($cart, $article);
        $mailer = new Mailer(LegacyCodeHelper::getGlobalConfig());
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
            templateService: $templateService,
            metaTagsService: $metaTagsService,
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

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testFreeShippingNotice()
    {
        /** @var Site $_SITE */
        global $_SITE;

        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/cart.php";
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("url");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite
            ->shouldReceive("getSite")
            ->andReturn(ModelFactory::createSite());
        $currentSite
            ->shouldReceive("getOption")
            ->with("sales_disabled")
            ->andReturn(null);
        $currentSite
            ->shouldReceive("getOption")
            ->with("free_shipping_target_amount")
            ->andReturn(1000);
        $currentSite
            ->shouldReceive("getOption")
            ->with(
                "free_shipping_invite_text",
                "Livraison offerte à partir de 10,00&nbsp;&euro; d'achat",
            )
            ->andReturn("Livraison offerte à partir de 10,00&nbsp;&euro; d'achat");

        ModelFactory::createCountry();
        $_SITE->setOpt("virtual_stock", 1);
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $article = EntityFactory::createArticle(["type_id" => 1, "article_price" => 500]);
        $cm = new CartManager();
        $cm->vacuum($cart);
        $cm->addArticle($cart, $article);
        $request = new Request();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_article")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_collection")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("cart_suggestions_rayon_id")->andReturn(null);
        $config = Mockery::mock(Config::class);

        // when
        $response = $controller($request, $config, $currentSite, $currentUser, $urlGenerator);

        // then
        $this->assertStringContainsString(
            "Livraison offerte à partir de 10,00&nbsp;&euro; d'achat",
            $response->getContent(),
            "it displays the free shipping notice"
        );
        $this->assertStringContainsString(
            "Ajoutez encore <strong>5,00&nbsp;&euro;</strong> à votre panier pour en bénéficier !",
            $response->getContent(),
            "it displays the missing amount for free shipping"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testFreeShippingNoticeWithTargetAmountReached()
    {
        /** @var Site $_SITE */
        global $_SITE;

        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/cart.php";
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("url");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite
            ->shouldReceive("getSite")
            ->andReturn(ModelFactory::createSite());
        $currentSite
            ->shouldReceive("getOption")
            ->with("sales_disabled")
            ->andReturn(null);
        $currentSite
            ->shouldReceive("getOption")
            ->with("free_shipping_target_amount")
            ->andReturn(1000);
        $currentSite
            ->shouldReceive("getOption")
            ->with(
                "free_shipping_success_text",
                "Vous bénéficiez de la livraison offerte !",
            )
            ->andReturn("Vous bénéficiez de la livraison offerte !");

        ModelFactory::createCountry();
        $_SITE->setOpt("virtual_stock", 1);
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $article = EntityFactory::createArticle(["type_id" => 1, "article_price" => 1500]);
        $cm = new CartManager();
        $cm->vacuum($cart);
        $cm->addArticle($cart, $article);
        $request = new Request();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_article")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_collection")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("cart_suggestions_rayon_id")->andReturn(null);
        $config = Mockery::mock(Config::class);

        // when
        $response = $controller($request, $config, $currentSite, $currentUser, $urlGenerator);

        // then
        $this->assertStringContainsString(
            "Vous bénéficiez de la livraison offerte !",
            $response->getContent(),
            "displays the success text when target amount is reached"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCartSuggestions()
    {
        /** @var Site $_SITE */
        global $_SITE;

        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/cart.php";

        ModelFactory::createCountry();
        $_SITE->setOpt("virtual_stock", 1);
        $cart = LegacyCodeHelper::getGlobalVisitor()->getCart("create");
        $article = EntityFactory::createArticle([
            "article_title" => "Article suggéré",
            "type_id" => 1,
            "article_price" => 500
        ]);
        $cm = new CartManager();
        $cm->vacuum($cart);
        $cm->addArticle($cart, $article);
        $site = ModelFactory::createSite();
        $articleModel = ArticleQuery::create()->findPk($article->get("id"));
        $articleCategory = ModelFactory::createArticleCategory(
            site: $site,
            name: "Suggestions du panier",
        );
        ModelFactory::createLink(article: $articleModel, articleCategory: $articleCategory);

        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("url");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite
            ->shouldReceive("getSite")
            ->andReturn($site);
        $currentSite
            ->shouldReceive("getOption")
            ->with("sales_disabled")
            ->andReturn(null);
        $currentSite
            ->shouldReceive("getOption")
            ->with("free_shipping_target_amount")
            ->andReturn(1000);
        $currentSite
            ->shouldReceive("getOption")
            ->with(
                "free_shipping_invite_text",
                "Livraison offerte à partir de 10,00&nbsp;&euro; d'achat",
            )
            ->andReturn("Livraison offerte à partir de 10,00&nbsp;&euro; d'achat");
        $request = new Request();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_article")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_collection")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")
            ->with("cart_suggestions_rayon_id")
            ->andReturn($articleCategory->getId());
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("media_path")->andReturn(null);
        $config->shouldReceive("get")->with("media_url")->andReturn(null);

        // when
        $response = $controller($request, $config, $currentSite, $currentUser, $urlGenerator);

        // then
        $this->assertStringContainsString(
            "Suggestions du panier",
            $response->getContent(),
            "displays article in suggestions rayon"
        );
        $this->assertStringContainsString(
            "Article suggéré",
            $response->getContent(),
            "displays article in suggestions rayon"
        );
    }
}