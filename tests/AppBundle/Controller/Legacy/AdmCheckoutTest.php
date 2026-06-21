<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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


namespace AppBundle\Controller\Legacy;

use Biblys\Service\FlashMessagesService;
use Biblys\Service\Images\ImagesService;
use Biblys\Test\EntityFactory;
use Mockery;
use Model\Payment;
use Model\PaymentQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . "/../../../setUp.php";

class AdmCheckoutTest extends TestCase
{
    private function getController(): callable
    {
        static $controller = null;
        if ($controller === null) {
            $controller = require __DIR__ . "/../../../../controllers/common/php/adm_checkout.php";
        }
        return $controller;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $_GET = [];
        $_POST = [];
    }

    /**
     * @throws PropelException
     */
    public function testValidateRecordsShopPaymentAmountInCents(): void
    {
        // given
        $controller = $this->getController();
        $cart = EntityFactory::createCart();

        $_GET["cart_id"] = $cart->get("id");
        $_POST["validate"] = "1";
        $_POST["cart_cash"] = "2100"; // 21,00 € sent in cents by the front-end JS
        $_POST["cart_cheque"] = "0";
        $_POST["cart_card"] = "0";
        $_POST["cart_topay"] = "0";
        $_POST["cart_togive"] = "0";

        $request = new Request();
        $request->query->set("cart_id", $cart->get("id"));
        $request->headers->set("X-Requested-With", "XMLHttpRequest");

        // when
        $controller(
            $request,
            Mockery::mock(ImagesService::class),
            Mockery::mock(FlashMessagesService::class),
        );

        // then
        $payment = PaymentQuery::create()
            ->filterByMode(Payment::MODE_CASH)
            ->orderById(Criteria::DESC)
            ->findOne();

        $this->assertNotNull($payment, "should have recorded a cash payment");
        $this->assertEquals(
            2100,
            $payment->getAmount(),
            "a 21 € payment should be recorded as 2100 cents, not 210000"
        );
    }
}
