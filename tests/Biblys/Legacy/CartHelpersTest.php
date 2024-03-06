<?php

namespace Biblys\Legacy;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../../setUp.php";

class CartHelpersTest extends TestCase
{

    /**
     * @throws PropelException
     */
    public function testGetSpecialOfferNotice()
    {
        // given
        $site = ModelFactory::createSite();
        $targetCollection = ModelFactory::createCollection(name: "Collection cible");
        $freeArticle = ModelFactory::createArticle(title: "Cékado", collection: $targetCollection);
        $cart = ModelFactory::createCart(site:$site);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("special_offer_amount")->andReturn(2);
        $currentSite->shouldReceive("getOption")
            ->with("special_offer_article")->andReturn($freeArticle->getId());
        $currentSite->shouldReceive("getOption")
            ->with("special_offer_collection")->andReturn($targetCollection->getId());

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

        $cart = ModelFactory::createCart(site: $site);
        $article1 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article1, cart: $cart);
        $article2 = ModelFactory::createArticle(collection: $targetCollection);
        ModelFactory::createStockItem(site: $site, article: $article2, cart: $cart);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getOption")
            ->with("special_offer_amount")->andReturn(2);
        $currentSite->shouldReceive("getOption")
            ->with("special_offer_article")->andReturn($freeArticle->getId());
        $currentSite->shouldReceive("getOption")
            ->with("special_offer_collection")->andReturn($targetCollection->getId());

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
