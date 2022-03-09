<?php

namespace ApiBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Framework\Exception\AuthException;
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
     * @throws AuthException
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
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();

        // when
        $response = $controller->indexAction($request, $config);

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
     * @throws AuthException
     * @throws PropelException
     */
    public function testCreateAction()
    {
        // given
        $controller = new ShippingController();
        $content = '{"id":"","mode":"Colissimo","type":"suivi","zone":"OM2","max_weight":"21","min_amount":"71","max_amount":"76","max_articles":"90","fee":"57","info":"Expedition sous 72h"}';
        $request = RequestFactory::createAuthRequestForAdminUser($content);
        $config = new Config();

        // when
        $response = $controller->createAction($request, $config);

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
     * @throws AuthException
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

        // when
        $response = $controller->updateAction($request, $config, $shippingFee->getId());

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
     * @throws AuthException
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

        // then
        $this->expectException("Symfony\Component\Routing\Exception\ResourceNotFoundException");
        $this->expectExceptionMessage(
            sprintf("Cannot find shipping fee with id %s", $shippingFee->getId())
        );

        // when
        $controller->updateAction($request, $config, $shippingFee->getId());
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDeleteAction()
    {
        // given
        $controller = new ShippingController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $shippingFee = ModelFactory::createShippingFee();
        $config = new Config();

        // when
        $response = $controller->deleteAction($request, $config, $shippingFee->getId());

        // then
        $this->assertEquals(
            204,
            $response->getStatusCode(),
            "it should respond with http 204"
        );
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function testDeleteFeeFromOtherSite()
    {
        // given
        $controller = new ShippingController();
        $content = '{"id":"","mode":"Colissimo","type":"suivi","zone":"OM2","max_weight":"21","min_amount":"71","max_amount":"76","max_articles":"90","fee":"57","info":"Expedition sous 72h"}';
        $request = RequestFactory::createAuthRequestForAdminUser($content);
        $shippingFee = ModelFactory::createShippingFee();
        $shippingFee->setSiteId(2);
        $shippingFee->save();
        $config = new Config();

        // then
        $this->expectException("Symfony\Component\Routing\Exception\ResourceNotFoundException");
        $this->expectExceptionMessage(
            sprintf("Cannot find shipping fee with id %s", $shippingFee->getId())
        );

        // when
        $controller->deleteAction($request, $config, $shippingFee->getId());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testSearch()
    {
        // given
        $site = ModelFactory::createSite();
        $shippingFee = ModelFactory::createShippingFee([
            "fee" => 560,
            "mode" => "Colissimo",
            "type" => "suivi",
            "info" => "Expedition sous 72h",
            "site_id" => $site->getId(),
            "max_weight" => 2000,
            "max_amount" => 10000,
        ]);
        $country = ModelFactory::createCountry();
        $controller = new ShippingController();
        $request = new Request();
        $request->query->set("country_id", $country->getId());
        $request->query->set("order_weight", "500");
        $request->query->set("order_amount", "2000");
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
     * @throws AuthException
     */
    public function testGetAction()
    {
        // given
        $config = new Config();
        $shippingFee = ModelFactory::createShippingFee([
            "mode" => "Colissimo",
            "type" => "Type C",
            "zone" => "Z2",
            "max_weight" => 1,
            "min_amount" => 2,
            "max_amount" => 3,
            "max_articles" => 4,
            "fee" => 90,
            "info" => "A shipping fee",
        ]);
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
