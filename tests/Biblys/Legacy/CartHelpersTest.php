<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Biblys\Legacy;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use DateTime;
use Mockery;
use Model\SpecialOfferQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__."/../../setUp.php";

class CartHelpersTest extends TestCase
{

    /**
     * @throws PropelException
     */
    protected function tearDown(): void
    {
        SpecialOfferQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     */
    public function testGetSpecialOfferNoticeWhenNoSpecialOffer()
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);

        // when
        $notice = CartHelpers::getSpecialOffersNotice(
            $currentSite,
            $urlGenerator,
            ModelFactory::createCart(site: $site),
        );

        // then
        $this->assertEquals("", $notice);
    }

    /**
     * @throws PropelException
     */
    public function testGetSpecialOfferNoticeBeforeStartDate()
    {
        // given
        $site = ModelFactory::createSite();
        $freeArticle = ModelFactory::createArticle();

        ModelFactory::createSpecialOffer(
            site: $site,
            targetCollection: $freeArticle->getBookCollection(),
            freeArticle: $freeArticle,
            startDate: new DateTime("+1 day"),
        );

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);

        // when
        $notice = CartHelpers::getSpecialOffersNotice(
            $currentSite,
            $urlGenerator,
            ModelFactory::createCart(site: $site),
        );

        // then
        $this->assertEquals("", $notice);
    }

    /**
     * @throws PropelException
     */
    public function testGetSpecialOfferNoticeAfterEndDate()
    {
        // given
        $site = ModelFactory::createSite();
        $freeArticle = ModelFactory::createArticle();

        ModelFactory::createSpecialOffer(
            site: $site,
            targetCollection: $freeArticle->getBookCollection(),
            freeArticle: $freeArticle,
            endDate: new DateTime("-1 day"),
        );

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);

        // when
        $notice = CartHelpers::getSpecialOffersNotice(
            $currentSite,
            $urlGenerator,
            ModelFactory::createCart(site: $site),
        );

        // then
        $this->assertEquals("", $notice);
    }

    /**
     * @throws PropelException
     */
    public function testGetSpecialOfferNotice()
    {
        // given
        $site = ModelFactory::createSite();
        $targetCollection = ModelFactory::createCollection(name: "Collection cible");
        $freeArticle = ModelFactory::createArticle(title: "Cékado", collection: $targetCollection);

        ModelFactory::createSpecialOffer(
            site: $site,
            targetCollection: $targetCollection,
            freeArticle: $freeArticle,
        );

        $cart = ModelFactory::createCart(site:$site);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate");

        // when
        $notice = CartHelpers::getSpecialOffersNotice(
            $currentSite,
            $urlGenerator,
            $cart,
        );

        // then
        $this->assertStringContainsString("Cékado", $notice);
        $this->assertStringContainsString("Offert pour 2 titres de la", $notice);
        $this->assertStringContainsString("Collection cible", $notice);
        $this->assertStringContainsString("Ajoutez encore 2 titres", $notice);
        $this->assertStringContainsString(
            '<button class="btn btn-default" disabled>Ajouter au panier</button>',
            $notice
        );
    }

    /**
     * @throws PropelException
     */
    public function testGetSpecialOfferNoticeForMultipleOffers()
    {
        // given
        $site = ModelFactory::createSite();
        $targetCollection1 = ModelFactory::createCollection(name: "Collection cible 1");
        $targetCollection2 = ModelFactory::createCollection(name: "Collection cible 2");
        $freeArticle1 = ModelFactory::createArticle(
            title: "Cékado 1", collection: $targetCollection1
        );
        $freeArticle2 = ModelFactory::createArticle(
            title: "Cékado 2", collection: $targetCollection2
        );

        ModelFactory::createSpecialOffer(
            site: $site,
            targetCollection: $targetCollection1,
            freeArticle: $freeArticle1,
        );
        ModelFactory::createSpecialOffer(
            site: $site,
            targetCollection: $targetCollection2,
            freeArticle: $freeArticle2,
        );

        $cart = ModelFactory::createCart(site:$site);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate");

        // when
        $notice = CartHelpers::getSpecialOffersNotice(
            $currentSite,
            $urlGenerator,
            $cart,
        );

        // then
        $this->assertStringContainsString("Cékado 1", $notice);
        $this->assertStringContainsString("Cékado 2", $notice);
        $this->assertStringContainsString("Collection cible 1", $notice);
        $this->assertStringContainsString("Collection cible 2", $notice);
    }

    /**
     * @throws PropelException
     */
    public function testGetSpecialOfferNoticeWhenConditionsAreMet()
    {
        // given
        $site = ModelFactory::createSite();
        $targetCollection = ModelFactory::createCollection(name: "Collection cible");
        $freeArticle = ModelFactory::createArticle(title: "Cékado", collection: $targetCollection);
        ModelFactory::createSpecialOffer(
            site: $site,
            targetCollection: $targetCollection,
            freeArticle: $freeArticle,
        );

        $cart = ModelFactory::createCart(site: $site);
        $article1 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article1, cart: $cart);
        $article2 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article2, cart: $cart);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive('getSite')->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("collection_show", ["slug" => $targetCollection->getUrl()]);
        $urlGenerator->shouldReceive("generate")
            ->with("cart_add_article", ["articleId" => $freeArticle->getId()])
            ->andReturn("/cart_url");

        // when
        $notice = CartHelpers::getSpecialOffersNotice(
            $currentSite,
            $urlGenerator,
            $cart,
        );

        // then
        $this->assertStringContainsString("Cékado", $notice);
        $this->assertStringContainsString("Offert pour 2 titres de la", $notice);
        $this->assertStringContainsString("Collection cible", $notice);
        $this->assertStringContainsString("Vous pouvez bénéficier de l’offre.", $notice);
        $this->assertStringContainsString('<form method="post" action="/cart_url">', $notice);
        $this->assertStringContainsString(
            '<button type="submit" class="btn btn-success">Ajouter au panier</button>', $notice
        );
    }
}
