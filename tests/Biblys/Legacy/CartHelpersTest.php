<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Biblys\Legacy;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use DateTime;
use Mockery;
use Model\SpecialOfferQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

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

        // when
        $notice = CartHelpers::getSpecialOfferNotice(
            $currentSite,
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

        // when
        $notice = CartHelpers::getSpecialOfferNotice(
            $currentSite,
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

        // when
        $notice = CartHelpers::getSpecialOfferNotice(
            $currentSite,
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

        // when
        $notice = CartHelpers::getSpecialOfferNotice(
            $currentSite,
            $cart,
        );

        // then
        $this->assertStringContainsString("Cékado", $notice);
        $this->assertStringContainsString("Offert pour 2 titres de la", $notice);
        $this->assertStringContainsString("collection Collection cible achetés&nbsp;!", $notice);
        $this->assertStringContainsString("Ajoutez encore 2 titres de la collection", $notice);
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


        // when
        $notice = CartHelpers::getSpecialOfferNotice(
            $currentSite,
            $cart,
        );

        // then
        $this->assertStringContainsString("Cékado", $notice);
        $this->assertStringContainsString("Offert pour 2 titres de la", $notice);
        $this->assertStringContainsString("collection Collection cible achetés&nbsp;!", $notice);
        $this->assertStringContainsString("Si vous ne souhaitez pas bénéficier de l'offre, vous pourrez", $notice);
    }
}
