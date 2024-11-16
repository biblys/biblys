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
use Model\ShippingFee;
use Model\ShippingFeeQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . "/../../setUp.php";

class ShippingControllerTest extends TestCase
{

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testIndexAction()
    {
        // given
        $shippingFee1 = new ShippingFee();
        $shippingFee1->setSiteId(1);
        $shippingFee1->setType("Type C");
        $shippingFee1->setZone("Z2");
        $shippingFee1->setFee(100);
        $shippingFee1->save();
        $shippingFee2 = new ShippingFee();
        $shippingFee2->setSiteId(1);
        $shippingFee2->setType("Type B");
        $shippingFee2->setZone("Z2");
        $shippingFee2->setFee(100);
        $shippingFee2->save();
        $shippingFee3 = new ShippingFee();
        $shippingFee3->setSiteId(1);
        $shippingFee3->setType("Type A");
        $shippingFee3->setZone("Z2");
        $shippingFee3->setFee(100);
        $shippingFee3->save();
        $shippingFee4 = new ShippingFee();
        $shippingFee4->setSiteId(1);
        $shippingFee4->setType("Type A");
        $shippingFee4->setZone("Z1");
        $shippingFee4->setFee(100);
        $shippingFee4->save();
        $shippingFee5 = new ShippingFee();
        $shippingFee5->setSiteId(1);
        $shippingFee5->setType("Type A");
        $shippingFee5->setZone("Z1");
        $shippingFee5->setFee(90);
        $shippingFee5->save();
        $otherSiteShippingFee = new ShippingFee();
        $otherSiteShippingFee->setSiteId(2);
        $otherSiteShippingFee->save();
        $controller = new ShippingController();
        $config = new Config();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn(true);

        // when
        $response = $controller->indexAction($currentUser, $config);

        // then
        $expectedResponse = [
            [
                'id' => 5,
                'mode' => NULL,
                'type' => 'Type A',
                'zone' => 'Z1',
                'max_weight' => NULL,
                'min_amount' => NULL,
                'max_amount' => NULL,
                'max_articles' => NULL,
                'fee' => 90,
                'info' => NULL,
                'is_compliant_with_french_law' => false,
            ],
            [
                'id' => 4,
                'mode' => NULL,
                'type' => 'Type A',
                'zone' => 'Z1',
                'max_weight' => NULL,
                'min_amount' => NULL,
                'max_amount' => NULL,
                'max_articles' => NULL,
                'fee' => 100,
                'info' => NULL,
                'is_compliant_with_french_law' => false,
            ],
            [
                'id' => 3,
                'mode' => NULL,
                'type' => 'Type A',
                'zone' => 'Z2',
                'max_weight' => NULL,
                'min_amount' => NULL,
                'max_amount' => NULL,
                'max_articles' => NULL,
                'fee' => 100,
                'info' => NULL,
                'is_compliant_with_french_law' => false,
            ],
            [
                'id' => 2,
                'mode' => NULL,
                'type' => 'Type B',
                'zone' => 'Z2',
                'max_weight' => NULL,
                'min_amount' => NULL,
                'max_amount' => NULL,
                'max_articles' => NULL,
                'fee' => 100,
                'info' => NULL,
                'is_compliant_with_french_law' => false,
            ],
            [
                'id' => 1,
                'mode' => NULL,
                'type' => 'Type C',
                'zone' => 'Z2',
                'max_weight' => NULL,
                'min_amount' => NULL,
                'max_amount' => NULL,
                'max_articles' => NULL,
                'fee' => 100,
                'info' => NULL,
                'is_compliant_with_french_law' => false,
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
     */
    public function testCreateAction()
    {
        // given
        $controller = new ShippingController();
        $content = '{"id":"","mode":"Colissimo","type":"suivi","zone":"OM2","max_weight":"21","min_amount":"71","max_amount":"76","max_articles":"90","fee":"57","info":"Expedition sous 72h"}';
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
        $this->assertEquals("suivi", $fee["type"]);
        $this->assertEquals("OM2", $fee["zone"]);
        $this->assertEquals(21, $fee["max_weight"]);
        $this->assertEquals(71, $fee["min_amount"]);
        $this->assertEquals(76, $fee["max_amount"]);
        $this->assertEquals(90, $fee["max_articles"]);
        $this->assertEquals("Expedition sous 72h", $fee["info"]);
        $createdFee = ShippingFeeQuery::create()->findPk($fee["id"]);
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
        $content = '{"id":"","mode":"Colissimo","type":"suivi","zone":"OM2","max_weight":"21","min_amount":"71","max_amount":"76","max_articles":"90","fee":"57","info":"Expedition sous 72h"}';
        $request = RequestFactory::createAuthRequestForAdminUser($content);
        $shippingFee = ModelFactory::createShippingFee();
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
        $this->assertEquals("suivi", $fee["type"]);
        $this->assertEquals("OM2", $fee["zone"]);
        $this->assertEquals(21, $fee["max_weight"]);
        $this->assertEquals(71, $fee["min_amount"]);
        $this->assertEquals(76, $fee["max_amount"]);
        $this->assertEquals(90, $fee["max_articles"]);
        $this->assertEquals("Expedition sous 72h", $fee["info"]);
    }

    /**
     * @throws PropelException
     */
    public function testUpdateFeeFromOtherSite()
    {
        // given
        $controller = new ShippingController();
        $content = '{"id":"","mode":"Colissimo","type":"suivi","zone":"OM2","max_weight":"21","min_amount":"71","max_amount":"76","max_articles":"90","fee":"57","info":"Expedition sous 72h"}';
        $request = RequestFactory::createAuthRequestForAdminUser($content);
        $shippingFee = ModelFactory::createShippingFee();
        $shippingFee->setSiteId(2);
        $shippingFee->save();
        $config = new Config();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn(true);

        // then
        $this->expectException("Symfony\Component\Routing\Exception\ResourceNotFoundException");
        $this->expectExceptionMessage(
            sprintf("Cannot find shipping fee with id %s", $shippingFee->getId())
        );

        // when
        $controller->updateAction($request, $config, $currentUser, $shippingFee->getId());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testArchiveAction(): void
    {
        // given
        $controller = new ShippingController();
        $shippingFee = ModelFactory::createShippingFee();
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
        $shippingFee = ModelFactory::createShippingFee();
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
     */
    public function testDeleteFeeFromOtherSite()
    {
        // given
        $controller = new ShippingController();
        $shippingFee = ModelFactory::createShippingFee();
        $shippingFee->setSiteId(2);
        $shippingFee->save();
        $config = new Config();

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn(true);

        // then
        $this->expectException("Symfony\Component\Routing\Exception\ResourceNotFoundException");
        $this->expectExceptionMessage(
            sprintf("Cannot find shipping fee with id %s", $shippingFee->getId())
        );

        // when
        $controller->deleteAction($config, $currentUser, $shippingFee->getId());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSearch()
    {
        // given
        $site = ModelFactory::createSite();
        $shippingFee = ModelFactory::createShippingFee(
            site: $site,
            type: "suivi",
            mode: "Colissimo",
            info: "Expedition sous 72h",
            fee: 560,
            maxWeight: 2000,
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

        // when
        $response = $controller->search($request, $currentSite);

        // then
        $expectedResponse = [
            [
                "id" => $shippingFee->getId(),
                "mode" => "Colissimo",
                "fee" => 560,
                "type" => "suivi",
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
        $shippingFee = ModelFactory::createShippingFee(
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
}
