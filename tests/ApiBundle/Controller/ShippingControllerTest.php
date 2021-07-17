<?php

namespace ApiBundle\Controller;

use Biblys\Test\Factory;
use Framework\Controller;
use Framework\Exception\AuthException;
use Model\ShippingFee;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../setUp.php";

class ShippingControllerTest extends TestCase
{

    /**
     * @throws PropelException
     */
    public function testIndexAction()
    {
        // given
        $shipping1 = new ShippingFee();
        $shipping1->save();
        $shipping2 = new ShippingFee();
        $shipping2->save();
        $controller = new ShippingController();

        // when
        $response = $controller->indexAction();

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertEquals(
            '[{"id":1,"mode":null,"type":null,"zone":null,"max_weight":null,"min_amount":null,"max_amount":null,"max_articles":null,"fee":null,"info":null},{"id":2,"mode":null,"type":null,"zone":null,"max_weight":null,"min_amount":null,"max_amount":null,"max_articles":null,"fee":null,"info":null}]',
            $response->getContent(),
            "it should return all fees"
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
        $request = Factory::createAuthRequestForAdminUser($content);

        // when
        $response = $controller->createAction($request);

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
        $request = Factory::createAuthRequestForAdminUser($content);
        $shippingFee = Factory::createShippingFee();

        // when
        $response = $controller->updateAction($request, $shippingFee->getId());

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
    public function testDeleteAction()
    {
        // given
        $controller = new ShippingController();
        $request = Factory::createAuthRequestForAdminUser();
        $shippingFee = Factory::createShippingFee();

        // when
        $response = $controller->deleteAction($request, $shippingFee->getId());

        // then
        $this->assertEquals(
            204,
            $response->getStatusCode(),
            "it should respond with http 204"
        );
    }
}
