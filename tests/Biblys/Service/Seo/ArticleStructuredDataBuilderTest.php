<?php
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


namespace Biblys\Service\Seo;

use Biblys\Data\ArticleType;
use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use DateTime;
use Model\ShippingOptionQuery;
use Model\ShippingZoneQuery;
use Model\ShippingZonesCountriesQuery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Repository\ShippingOptionRepository;
use Repository\StockRepository;

require_once __DIR__ . "/../../../setUp.php";

class ArticleStructuredDataBuilderTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        ShippingOptionQuery::create()->deleteAll();
        ShippingZonesCountriesQuery::create()->deleteAll();
        ShippingZoneQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     */
    public function testBuildReturnsProductFieldsForABook()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "Éditions Paronymie");
        $article = ModelFactory::createArticle(
            title: "Citoyens de demain",
            typeId: ArticleType::BOOK,
            publisher: $publisher,
            summary: "<p>Un roman <strong>essentiel</strong>.</p>",
        );
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("https://schema.org", $data["@context"]);
        $this->assertEquals("Citoyens de demain", $data["name"]);
        $this->assertEquals("Un roman essentiel.", $data["description"]);
        $this->assertEquals("Éditions Paronymie", $data["brand"]["name"]);
    }

    /**
     * @throws PropelException
     */
    #[DataProvider("blankSummaryProvider")]
    public function testBuildOmitsDescriptionWhenArticleHasNoSummary(string $summary)
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK, summary: $summary);
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertArrayNotHasKey("description", $data);
    }

    public static function blankSummaryProvider(): array
    {
        return [
            "empty paragraph" => ["<p> </p>"],
            "html non-breaking space" => ["<p>&nbsp;</p>"],
            "several html non-breaking spaces" => ["<p>&nbsp; &nbsp;</p>"],
            "non-breaking space character" => ["<p>\u{00A0}</p>"],
        ];
    }

    /**
     * @throws PropelException
     */
    public function testBuildReturnsEmptyArrayForSubscription()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::SUBSCRIPTION);
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, null, $currentSite);

        // then
        $this->assertEquals([], $data);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsBookFieldsForABook()
    {
        // given
        $contributor = ModelFactory::createContributor(firstName: "Jean", lastName: "Dupont");
        $article = ModelFactory::createArticle(
            title: "Citoyens de demain",
            authors: [$contributor],
            ean: "9782070368228",
            typeId: ArticleType::BOOK,
        );
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, null, $currentSite);

        // then
        $this->assertEquals(["Product", "Book"], $data["@type"]);
        $this->assertEquals("Jean Dupont", $data["author"]);
        $this->assertEquals("978-2-07-036822-8", $data["isbn"]);
        $this->assertArrayNotHasKey("bookFormat", $data);
        $this->assertArrayNotHasKey("gtin13", $data);
    }

    /**
     * @throws PropelException
     */
    public function testBuildSetsBookFormatForEbookAndAudiobook()
    {
        // given
        $ebook = ModelFactory::createArticle(typeId: ArticleType::EBOOK, ean: "9782070368228");
        $audiobook = ModelFactory::createArticle(typeId: ArticleType::EAUDIOBOOK, ean: "9782070368228");
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $ebookData = $builder->build($ebook, null, $currentSite);
        $audiobookData = $builder->build($audiobook, null, $currentSite);

        // then
        $this->assertEquals("https://schema.org/EBook", $ebookData["bookFormat"]);
        $this->assertEquals("https://schema.org/AudiobookFormat", $audiobookData["bookFormat"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildUsesGtin13ForNonBookTypes()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::CD, ean: "3700123456789");
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, null, $currentSite);

        // then
        $this->assertEquals("Product", $data["@type"]);
        $this->assertEquals("3700123456789", $data["gtin13"]);
        $this->assertArrayNotHasKey("isbn", $data);
        $this->assertArrayNotHasKey("author", $data);
    }

    /**
     * @throws PropelException
     */
    public function testBuildOmitsGtin13WhenEanIsNotThirteenDigits()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::GOODIES, ean: "ABC123");
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, null, $currentSite);

        // then
        $this->assertArrayNotHasKey("gtin13", $data);
    }

    /**
     * @throws PropelException
     */
    public function testBuildOmitsOffersWhenArticleHasNoImage()
    {
        // given
        $article = ModelFactory::createArticle(
            typeId: ArticleType::EBOOK,
            price: 1290,
            availabilityDilicom: 1,
        );
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, null, $currentSite);

        // then
        $this->assertEquals(["Product", "Book"], $data["@type"]);
        $this->assertArrayNotHasKey("image", $data);
        $this->assertArrayNotHasKey("offers", $data);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsSimpleOfferForDownloadableArticle()
    {
        // given
        $article = ModelFactory::createArticle(
            typeId: ArticleType::EBOOK,
            price: 1290,
            availabilityDilicom: 1,
        );
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("Offer", $data["offers"]["@type"]);
        $this->assertEquals("12.90", $data["offers"]["price"]);
        $this->assertEquals("EUR", $data["offers"]["priceCurrency"]);
        $this->assertEquals("https://schema.org/InStock", $data["offers"]["availability"]);
        $this->assertEquals("https://schema.org/NewCondition", $data["offers"]["itemCondition"]);
    }

    /**
     * @dataProvider availabilityDilicomProvider
     * @throws PropelException
     */
    public function testBuildMapsAvailabilityDilicomToSchemaAvailability(
        int     $availabilityDilicom,
        bool    $isPreorderable,
        ?string $publicationDate,
        string  $expectedAvailability,
    ) {
        // given
        $article = ModelFactory::createArticle(
            typeId: ArticleType::EBOOK,
            availabilityDilicom: $availabilityDilicom,
            isPreorderable: $isPreorderable,
            publicationDate: $publicationDate ? new DateTime($publicationDate) : null,
        );
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals($expectedAvailability, $data["offers"]["availability"]);
    }

    public static function availabilityDilicomProvider(): array
    {
        return [
            "sold out" => [6, false, null, "https://schema.org/OutOfStock"],
            "soon unavailable" => [9, false, null, "https://schema.org/LimitedAvailability"],
            "to be reprinted" => [3, false, null, "https://schema.org/BackOrder"],
            "preorderable, not yet published" => [1, true, "2099-01-01", "https://schema.org/PreOrder"],
            "not preorderable, not yet published" => [1, false, "2099-01-01", "https://schema.org/OutOfStock"],
            "available" => [1, false, null, "https://schema.org/InStock"],
        ];
    }

    /**
     * @throws PropelException
     */
    public function testBuildMapsFcfaCurrencyOptionToXof()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::EBOOK, price: 1000);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getOption")->with("currency")->willReturn("FCFA");
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("XOF", $data["offers"]["priceCurrency"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsSimpleOfferForPhysicalArticleOnVirtualStockSite()
    {
        // given
        $article = ModelFactory::createArticle(
            typeId: ArticleType::BOOK,
            price: 1990,
            availabilityDilicom: 1,
        );
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->with("virtual_stock")->willReturn(true);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("Offer", $data["offers"]["@type"]);
        $this->assertEquals("19.90", $data["offers"]["price"]);
        $this->assertEquals("https://schema.org/InStock", $data["offers"]["availability"]);
        $this->assertEquals("https://schema.org/NewCondition", $data["offers"]["itemCondition"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildOmitsOffersWhenNoStockOnRealStockSite()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK, availabilityDilicom: 1);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertArrayNotHasKey("offers", $data);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsSimpleOfferForOneAvailableCopy()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK);
        ModelFactory::createStockItem(article: $article, sellingPrice: 1500, condition: "Neuf");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("Offer", $data["offers"]["@type"]);
        $this->assertEquals("15.00", $data["offers"]["price"]);
        $this->assertEquals("https://schema.org/NewCondition", $data["offers"]["itemCondition"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsAggregateOfferForMultipleAvailableCopies()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK);
        ModelFactory::createStockItem(article: $article, sellingPrice: 1500, condition: "Neuf");
        ModelFactory::createStockItem(article: $article, sellingPrice: 900, condition: "Bon état");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("AggregateOffer", $data["offers"]["@type"]);
        $this->assertEquals("9.00", $data["offers"]["lowPrice"]);
        $this->assertEquals("15.00", $data["offers"]["highPrice"]);
        $this->assertEquals(2, $data["offers"]["offerCount"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsSimpleOfferWhenAllAvailableCopiesShareSamePriceAndCondition()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK);
        ModelFactory::createStockItem(article: $article, sellingPrice: 600, condition: "Neuf");
        ModelFactory::createStockItem(article: $article, sellingPrice: 600, condition: "Neuf");
        ModelFactory::createStockItem(article: $article, sellingPrice: 600, condition: "Neuf");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("Offer", $data["offers"]["@type"]);
        $this->assertEquals("6.00", $data["offers"]["price"]);
        $this->assertEquals("https://schema.org/NewCondition", $data["offers"]["itemCondition"]);
        $this->assertArrayNotHasKey("offerCount", $data["offers"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsReturnWindowForEuropeanCountryToSimpleOffer()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK);
        ModelFactory::createStockItem(article: $article, sellingPrice: 1500, condition: "Neuf");
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(name: "France", code: "FR", shippingZone: $zone);
        ModelFactory::createCountry(name: "Belgique", code: "BE", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $zone);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals([[
            "@type" => "MerchantReturnPolicy",
            "applicableCountry" => ["BE", "FR"],
            "returnPolicyCategory" => "https://schema.org/MerchantReturnFiniteReturnWindow",
            "merchantReturnDays" => 14,
            "returnFees" => "https://schema.org/ReturnFeesCustomerResponsibility",
        ]], $data["offers"]["hasMerchantReturnPolicy"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildDeclaresNoReturnForNonEuropeanCountry()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK);
        ModelFactory::createStockItem(article: $article, sellingPrice: 1500, condition: "Neuf");
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(name: "Canada", code: "CA", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 1500, shippingZone: $zone);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals([[
            "@type" => "MerchantReturnPolicy",
            "applicableCountry" => ["CA"],
            "returnPolicyCategory" => "https://schema.org/MerchantReturnNotPermitted",
        ]], $data["offers"]["hasMerchantReturnPolicy"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsOneReturnPolicyPerRegimeToAggregateOffer()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK);
        ModelFactory::createStockItem(article: $article, sellingPrice: 1500, condition: "Neuf");
        ModelFactory::createStockItem(article: $article, sellingPrice: 900, condition: "Bon état");
        $franceZone = ModelFactory::createShippingZone(name: "France");
        $worldZone = ModelFactory::createShippingZone(name: "Monde");
        ModelFactory::createCountry(name: "France", code: "FR", shippingZone: $franceZone);
        ModelFactory::createCountry(name: "Canada", code: "CA", shippingZone: $worldZone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $franceZone);
        ModelFactory::createShippingOption(fee: 1500, shippingZone: $worldZone);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("AggregateOffer", $data["offers"]["@type"]);
        $policies = $data["offers"]["hasMerchantReturnPolicy"];
        $this->assertCount(2, $policies);
        $this->assertEquals(["FR"], $policies[0]["applicableCountry"]);
        $this->assertEquals(14, $policies[0]["merchantReturnDays"]);
        $this->assertEquals(["CA"], $policies[1]["applicableCountry"]);
        $this->assertEquals("https://schema.org/MerchantReturnNotPermitted", $policies[1]["returnPolicyCategory"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsReturnPolicyToOfferOnVirtualStockSite()
    {
        // given
        $article = ModelFactory::createArticle(
            typeId: ArticleType::BOOK,
            price: 1990,
            availabilityDilicom: 1,
        );
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(code: "FR", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $zone);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->with("virtual_stock")->willReturn(true);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals(
            "https://schema.org/MerchantReturnFiniteReturnWindow",
            $data["offers"]["hasMerchantReturnPolicy"][0]["returnPolicyCategory"],
        );
    }

    /**
     * @throws PropelException
     */
    public function testBuildDeclaresNoReturnForDownloadableArticleEvenInEurope()
    {
        // given
        $article = ModelFactory::createArticle(
            typeId: ArticleType::EBOOK,
            price: 1290,
            availabilityDilicom: 1,
        );
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(name: "France", code: "FR", shippingZone: $zone);
        ModelFactory::createCountry(name: "Canada", code: "CA", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $zone);
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals([[
            "@type" => "MerchantReturnPolicy",
            "applicableCountry" => ["CA", "FR"],
            "returnPolicyCategory" => "https://schema.org/MerchantReturnNotPermitted",
        ]], $data["offers"]["hasMerchantReturnPolicy"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildOmitsReturnPolicyWhenNoCountryIsServed()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK);
        ModelFactory::createStockItem(article: $article, sellingPrice: 1500, condition: "Neuf");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertArrayNotHasKey("hasMerchantReturnPolicy", $data["offers"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsShippingDetailsToSimpleOfferForPhysicalArticle()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK);
        ModelFactory::createStockItem(article: $article, sellingPrice: 1500, condition: "Neuf");
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(name: "France", code: "FR", shippingZone: $zone);
        ModelFactory::createCountry(name: "Belgique", code: "BE", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $zone);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals([[
            "@type" => "OfferShippingDetails",
            "shippingRate" => [
                "@type" => "MonetaryAmount",
                "value" => "4.50",
                "currency" => "EUR",
            ],
            "shippingDestination" => [
                ["@type" => "DefinedRegion", "addressCountry" => "BE"],
                ["@type" => "DefinedRegion", "addressCountry" => "FR"],
            ],
        ]], $data["offers"]["shippingDetails"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsOneShippingDetailsEntryPerDistinctRate()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK);
        ModelFactory::createStockItem(article: $article, sellingPrice: 1500, condition: "Neuf");
        ModelFactory::createStockItem(article: $article, sellingPrice: 900, condition: "Bon état");
        $franceZone = ModelFactory::createShippingZone(name: "France");
        $europeZone = ModelFactory::createShippingZone(name: "Europe");
        ModelFactory::createCountry(name: "France", code: "FR", shippingZone: $franceZone);
        ModelFactory::createCountry(name: "Belgique", code: "BE", shippingZone: $europeZone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $franceZone);
        ModelFactory::createShippingOption(fee: 900, shippingZone: $europeZone);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("AggregateOffer", $data["offers"]["@type"]);
        $this->assertCount(2, $data["offers"]["shippingDetails"]);
        $this->assertEquals("4.50", $data["offers"]["shippingDetails"][0]["shippingRate"]["value"]);
        $this->assertEquals("9.00", $data["offers"]["shippingDetails"][1]["shippingRate"]["value"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildAddsShippingDetailsToOfferOnVirtualStockSite()
    {
        // given
        $article = ModelFactory::createArticle(
            typeId: ArticleType::BOOK,
            price: 1990,
            availabilityDilicom: 1,
        );
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(code: "FR", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $zone);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->with("virtual_stock")->willReturn(true);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertCount(1, $data["offers"]["shippingDetails"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildOmitsShippingDetailsForDownloadableArticle()
    {
        // given
        $article = ModelFactory::createArticle(
            typeId: ArticleType::EBOOK,
            price: 1290,
            availabilityDilicom: 1,
        );
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(code: "FR", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $zone);
        $currentSite = $this->createMock(CurrentSite::class);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertArrayNotHasKey("shippingDetails", $data["offers"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildOmitsShippingDetailsWhenNoShippingOptionExists()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK);
        ModelFactory::createStockItem(article: $article, sellingPrice: 1500, condition: "Neuf");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->willReturn(false);
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertArrayNotHasKey("shippingDetails", $data["offers"]);
    }

    /**
     * @throws PropelException
     */
    public function testBuildUsesSiteCurrencyForShippingRate()
    {
        // given
        $article = ModelFactory::createArticle(typeId: ArticleType::BOOK, price: 1000, availabilityDilicom: 1);
        $zone = ModelFactory::createShippingZone();
        ModelFactory::createCountry(code: "SN", shippingZone: $zone);
        ModelFactory::createShippingOption(fee: 450, shippingZone: $zone);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("hasOptionEnabled")->with("virtual_stock")->willReturn(true);
        $currentSite->method("getOption")->with("currency")->willReturn("FCFA");
        $builder = new ArticleStructuredDataBuilder(new StockRepository(), new ShippingOptionRepository());

        // when
        $data = $builder->build($article, "/images/cover.jpg", $currentSite);

        // then
        $this->assertEquals("XOF", $data["offers"]["shippingDetails"][0]["shippingRate"]["currency"]);
    }
}
