<?php

namespace AppBundle\Controller\Legacy;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
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
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/cart.php";

        ModelFactory::createCountry();
        /* @var Site $globalSite */
        $flashBag = $this
            ->getMockBuilder("Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface")
            ->getMock();
        $flashBag->method("get")->willReturn([]);
        $session = $this
            ->getMockBuilder("Symfony\Component\HttpFoundation\Session\Session")
            ->getMock();
        $session->method("getFlashBag")->willReturn($flashBag);
        $request = new Request();

        $site = ModelFactory::createSite();
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle(title: "Le livre dans mon panier");
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart);
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->andReturn(null);
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $imagesService = Mockery::mock(ImagesService::class);
        $imagesService->expects("articleHasCoverImage")->andReturn(true);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("render");

        // when
        $response = $controller($request, $config, $currentSite, $currentUser, $urlGenerator, $imagesService, $templateService);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertStringContainsString(
            "Le livre dans mon panier",
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
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/cart.php";
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("url");
        $site = ModelFactory::createSite();
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
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_article")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_collection")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("cart_suggestions_rayon_id")->andReturn(null);

        ModelFactory::createCountry();
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle(title: "Le livre dans mon panier");
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart, sellingPrice: 500);

        $request = new Request();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);
        $config = new Config();
        $imagesService = Mockery::mock(ImagesService::class);
        $imagesService->expects("articleHasCoverImage")->andReturn(true);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("render");

        // when
        $response = $controller($request, $config, $currentSite, $currentUser, $urlGenerator, $imagesService, $templateService);

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
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/cart.php";
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")->andReturn("url");
        $currentSite = Mockery::mock(CurrentSite::class);
        $site = ModelFactory::createSite();
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
                "free_shipping_success_text",
                "Vous bénéficiez de la livraison offerte !",
            )
            ->andReturn("Vous bénéficiez de la livraison offerte !");
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_article")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_collection")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("cart_suggestions_rayon_id")->andReturn(null);

        ModelFactory::createCountry();
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle(title: "Le livre dans mon panier");
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart, sellingPrice: 1500);

        $request = new Request();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);
        $config = new Config();
        $imagesService = Mockery::mock(ImagesService::class);
        $imagesService->expects("articleHasCoverImage")->andReturn(true);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("render");

        // when
        $response = $controller($request, $config, $currentSite, $currentUser, $urlGenerator, $imagesService, $templateService);

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
        // given
        $controller = require __DIR__ . "/../../../../controllers/common/php/cart.php";

        ModelFactory::createCountry();
        $site = ModelFactory::createSite();
        $cart = ModelFactory::createCart(site: $site);
        $article = ModelFactory::createArticle();
        ModelFactory::createStockItem(site: $site, article: $article, cart: $cart, sellingPrice: 500);
        $articleCategory = ModelFactory::createArticleCategory(
            site: $site,
            name: "Suggestions du panier",
        );
        $suggestedArticle = ModelFactory::createArticle(title: "Article suggéré");
        ModelFactory::createLink(article: $suggestedArticle, articleCategory: $articleCategory);

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
            ->andReturn(null);
        $currentSite
            ->shouldReceive("getOption")
            ->with(
                "free_shipping_invite_text",
                "Livraison offerte à partir de 10,00&nbsp;&euro; d'achat",
            )
            ->andReturn("Livraison offerte à partir de 10,00&nbsp;&euro; d'achat");
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_article")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_collection")->andReturn(null);
        $currentSite->shouldReceive("getOption")->with("special_offer_amount")->andReturn(null);
        $currentSite->shouldReceive("getOption")
            ->with("cart_suggestions_rayon_id")
            ->andReturn($articleCategory->getId());
        $request = new Request();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("getOrCreateCart")->andReturn($cart);
        $currentUser->shouldReceive("isAdmin")->andReturn(false);
        $currentUser->shouldReceive("isAuthentified")->andReturn(false);
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("getImagesPath")->with()->andReturn(null);
        $config->shouldReceive("getImagesBaseUrl")->with()->andReturn(null);
        $imagesService = Mockery::mock(ImagesService::class);
        $imagesService->expects("articleHasCoverImage")->andReturn(true);
        $imagesService->expects("getCoverUrlForArticle");
        $templateService = Mockery::mock(TemplateService::class);
        $templateService->expects("render");

        // when
        $response = $controller($request, $config, $currentSite, $currentUser, $urlGenerator, $imagesService, $templateService);

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