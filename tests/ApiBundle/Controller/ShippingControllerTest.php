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


namespace ApiBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Mockery;
use Model\ShippingOption;
use Model\ShippingOptionQuery;
use Model\ShippingZoneQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . "/../../setUp.php";

class ShippingControllerTest extends TestCase
{

    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        ShippingZoneQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testIndexAction()
    {
        // given
        $site = ModelFactory::createSite();
        $shippingFee1 = new ShippingOption();
        $shippingFee1->setSiteId($site->getId());
        $shippingFee1->setType("Type C");
        $shippingFee1->setZoneCode("Z2");
        $shippingFee1->setFee(100);
        $shippingFee1->save();
        $shippingFee2 = new ShippingOption();
        $shippingFee2->setSiteId($site->getId());
        $shippingFee2->setType("Type B");
        $shippingFee2->setZoneCode("Z2");
        $shippingFee2->setFee(100);
        $shippingFee2->save();
        $shippingFee3 = new ShippingOption();
        $shippingFee3->setSiteId($site->getId());
        $shippingFee3->setType("Type A");
        $shippingFee3->setZoneCode("Z2");
        $shippingFee3->setFee(100);
        $shippingFee3->save();
        $shippingFee4 = new ShippingOption();
        $shippingFee4->setSiteId($site->getId());
        $shippingFee4->setType("Type A");
        $shippingFee4->setZoneCode("Z1");
        $shippingFee4->setFee(100);
        $shippingFee4->save();
        $shippingFee5 = new ShippingOption();
        $shippingFee5->setSiteId($site->getId());
        $shippingFee5->setType("Type A");
        $shippingFee5->setZoneCode("Z1");
        $shippingFee5->setFee(90);
        $shippingFee5->save();
        $archivedShippingFee = new ShippingOption();
        $archivedShippingFee->setSiteId($site->getId());
        $archivedShippingFee->archive();
        $archivedShippingFee->save();

        $otherSite = ModelFactory::createSite();
        $otherSiteShippingFee = new ShippingOption();
        $otherSiteShippingFee->setSiteId($otherSite->getId());
        $otherSiteShippingFee->save();
        $controller = new ShippingController();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn(true);

        // when
        $response = $controller->indexAction($currentSite, $currentUser);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $returnedFeeds = json_decode($response->getContent(), true);
        $this->assertCount(5, $returnedFeeds);
        $this->assertEquals("Type A", $returnedFeeds[0]["type"]);
        $this->assertEquals("Z1", $returnedFeeds[0]["zone"]);
        $this->assertEquals(90, $returnedFeeds[0]["fee"]);
        $this->assertEquals("Type A", $returnedFeeds[1]["type"]);
        $this->assertEquals("Z1", $returnedFeeds[1]["zone"]);
        $this->assertEquals(100, $returnedFeeds[1]["fee"]);
        $this->assertEquals("Type A", $returnedFeeds[2]["type"]);
        $this->assertEquals("Z2", $returnedFeeds[2]["zone"]);
        $this->assertEquals(100, $returnedFeeds[2]["fee"]);
        $this->assertEquals("Type B", $returnedFeeds[3]["type"]);
        $this->assertEquals("Z2", $returnedFeeds[3]["zone"]);
        $this->assertEquals(100, $returnedFeeds[3]["fee"]);
        $this->assertEquals("Type C", $returnedFeeds[4]["type"]);
        $this->assertEquals("Z2", $returnedFeeds[4]["zone"]);
        $this->assertEquals(100, $returnedFeeds[4]["fee"]);
    }

    /**
     * @throws PropelException
     */
    public function testCreateAction()
    {
        // given
        $controller = new ShippingController();
        $content = '{"id":"","mode":"Colissimo","type":"colissimo","zone":"OM2","max_weight":"21","min_amount":"71","max_amount":"76","max_articles":"90","fee":"57","info":"Expedition sous 72h"}';
        $request = RequestFactory::createAuthRequestForAdminUser($content);
        $config = new Config();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn(true);

        // when
        $response = $controller->createAction($request, $config, $currentUser);

        // then
        $this->assertEquals(
            201,
            $response->getStatusCode(),
            "it should respond with http 201"
        );
        $fee = json_decode($response->getContent(), true);
        $this->assertEquals(57, $fee["fee"]);
        $this->assertEquals("Colissimo", $fee["mode"]);
        $this->assertEquals("colissimo", $fee["type"]);
        $this->assertEquals("OM2", $fee["zone"]);
        $this->assertEquals(21, $fee["max_weight"]);
        $this->assertEquals(71, $fee["min_amount"]);
        $this->assertEquals(76, $fee["max_amount"]);
        $this->assertEquals(90, $fee["max_articles"]);
        $this->assertEquals("Expedition sous 72h", $fee["info"]);
        $createdFee = ShippingOptionQuery::create()->findPk($fee["id"]);
        $this->assertEquals(
            1,
            $createdFee->getSiteId(),
            "should have created ShippingFee with site id"
        );
    }

    /**
     * @throws PropelException
     */
    public function testUpdateAction()
    {
        // given
        $controller = new ShippingController();
        $content = '{"id":"","mode":"Colissimo","type":"colissimo","zone":"OM2","max_weight":"21","min_amount":"71","max_amount":"76","max_articles":"90","fee":"57","info":"Expedition sous 72h"}';
        $request = RequestFactory::createAuthRequestForAdminUser($content);
        $shippingFee = ModelFactory::createShippingOption();
        $config = new Config();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn(true);

        // when
        $response = $controller->updateAction(
            $request,
            $config,
            $currentUser,
            $shippingFee->getId()
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $fee = json_decode($response->getContent(), true);
        $this->assertEquals($shippingFee->getId(), $fee["id"]);
        $this->assertEquals(57, $fee["fee"]);
        $this->assertEquals("Colissimo", $fee["mode"]);
        $this->assertEquals("colissimo", $fee["type"]);
        $this->assertEquals("OM2", $fee["zone"]);
        $this->assertEquals(21, $fee["max_weight"]);
        $this->assertEquals(71, $fee["min_amount"]);
        $this->assertEquals(76, $fee["max_amount"]);
        $this->assertEquals(90, $fee["max_articles"]);
        $this->assertEquals("Expedition sous 72h", $fee["info"]);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testArchiveAction(): void
    {
        // given
        $controller = new ShippingController();
        $shippingFee = ModelFactory::createShippingOption();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn(true);

        // when
        $response = $controller->archiveAction($currentUser, $shippingFee->getId());

        // then
        $this->assertEquals(
            204,
            $response->getStatusCode(),
            "it should respond with http 204"
        );
        $this->assertTrue(
            $shippingFee->isArchived(),
            "it should archive the shipping fee"
        );
    }

    /**
     * @throws PropelException
     */
    public function testDeleteAction()
    {
        // given
        $controller = new ShippingController();
        $shippingFee = ModelFactory::createShippingOption();
        $config = new Config();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn(true);

        // when
        $response = $controller->deleteAction($config, $currentUser, $shippingFee->getId());

        // then
        $this->assertEquals(
            204,
            $response->getStatusCode(),
            "it should respond with http 204"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSearch()
    {
        // given
        $site = ModelFactory::createSite();
        $shippingFee = ModelFactory::createShippingOption(
            site: $site,
            type: "colissimo",
            mode: "Colissimo",
            info: "Expedition sous 72h",
            fee: 560,
            maxWeight: 600,
            maxAmount: 10000,
            maxArticles: 5,
        );
        $country = ModelFactory::createCountry();
        $controller = new ShippingController();
        $request = new Request();
        $request->query->set("country_id", $country->getId());
        $request->query->set("order_weight", "500");
        $request->query->set("order_amount", "2000");
        $request->query->set("article_count", "2");
        $currentSite = new CurrentSite($site);
        ModelFactory::createSiteOption($site,"shipping_packaging_weight", "100");

        // when
        $response = $controller->search($request, $currentSite);

        // then
        $expectedResponse = [
            [
                "id" => $shippingFee->getId(),
                "mode" => "Colissimo",
                "fee" => 560,
                "type" => "colissimo",
                "info" => "Expedition sous 72h",
            ],
        ];
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertEquals(
            json_encode($expectedResponse),
            $response->getContent(),
            "it should return all fees for current site"
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSearchWithUnknownCountry()
    {
        // then
        $this->expectException("Symfony\Component\HttpFoundation\Exception\BadRequestException");
        $this->expectExceptionMessage("Pays inconnu");

        //given
        $request = new Request();
        $request->query->set("country_id", 99999);
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $controller = new ShippingController();

        // when
        $controller->search($request, $currentSite);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSearchWithBlockedCountry()
    {
        // then
        $this->expectException("Symfony\Component\HttpFoundation\Exception\BadRequestException");
        $this->expectExceptionMessage("Expédition non disponible pour ce pays.");

        // given
        $country = ModelFactory::createCountry();
        $request = new Request();
        $request->query->set("country_id", $country->getId());
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("countries_blocked_for_shipping", "{$country->getCode()},US,DE");
        $controller = new ShippingController();

        // when
        $controller->search($request, $currentSite);
    }

    /**
     * @throws PropelException
     */
    public function testGetAction()
    {
        // given
        $config = new Config();
        $country = ModelFactory::createCountry(zone: "Z2");
        $shippingFee = ModelFactory::createShippingOption(
            type: "Type C",
            country: $country,
            mode: "Colissimo",
            info: "A shipping fee",
            fee: 90,
            maxWeight: 1,
            minAmount: 2,
            maxAmount: 3,
            maxArticles: 4
        );
        $shippingFee->save();
        $controller = new ShippingController();

        // when
        $response = $controller->get($config, $shippingFee->getId());

        // then
        $expectedResponse = [
            "id" => $shippingFee->getId(),
            "mode" => "Colissimo",
            "type" => "Type C",
            "zone" => "Z2",
            "max_weight" => 1,
            "min_amount" => 2,
            "max_amount" => 3,
            "max_articles" => 4,
            "fee" => 90,
            "info" => "A shipping fee",
            "is_compliant_with_french_law" => false,
        ];
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertEquals(
            json_encode($expectedResponse),
            $response->getContent(),
            "it should return all fees for current site"
        );
    }

    /** Zones */

    /**
     * @throws PropelException
     */
    public function testZonesAction()
    {
        // given
        $controller = new ShippingController();
        $shippingZone1 = ModelFactory::createShippingZone(name: "Zone 1");
        $shippingZone2 = ModelFactory::createShippingZone(name: "Zone 2");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->zonesAction($currentUser);

        // then
        $this->assertEquals(
            json_encode([
                [
                    "id" => $shippingZone1->getId(),
                    "name" => $shippingZone1->getName(),
                ],
                [
                    "id" => $shippingZone2->getId(),
                    "name" => $shippingZone2->getName(),
                ],
            ]),
            $response->getContent(),
            "returns all shipping zones"
        );
    }
}
