<?php

namespace ApiBundle\Controller;

use Biblys\Service\Config;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Framework\Exception\AuthException;
use Model\ShippingFee;
use Model\ShippingFeeQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

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
        $shippingFee1->save();
        $shippingFee2 = new ShippingFee();
        $shippingFee2->setSiteId(1);
        $shippingFee2->save();
        $otherSiteShippingFee = new ShippingFee();
        $otherSiteShippingFee->setSiteId(2);
        $otherSiteShippingFee->save();
        $controller = new ShippingController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $config = new Config();

        // when
        $response = $controller->indexAction($request, $config);

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertEquals(
            '[{"id":1,"mode":null,"type":null,"zone":null,"max_weight":null,"min_amount":null,"max_amount":null,"max_articles":null,"fee":null,"info":null},{"id":2,"mode":null,"type":null,"zone":null,"max_weight":null,"min_amount":null,"max_amount":null,"max_articles":null,"fee":null,"info":null}]',
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
}
