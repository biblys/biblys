<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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

use Biblys\Service\PaymentService;
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class PaymentControllerTest extends TestCase
{

    /**
     * @throws PropelException
     */
    public function testCreateStripePaymentAction()
    {
        // given
        $controller = new PaymentController();
        $order = ModelFactory::createOrder();

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);
        $paymentService->expects("createStripePaymentForOrder")->with($order)
            ->andReturn([
                "payment_intent_client_secret" => "pi_1234_secret_abcd",
                "customer_session_client_secret" => "cuss_secret_abcd",
            ]);

        // when
        $response = $controller->createStripePaymentAction($paymentService, $order->getSlug());

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            '{"payment_intent_client_secret":"pi_1234_secret_abcd","customer_session_client_secret":"cuss_secret_abcd"}',
            $response->getContent()
        );
    }
}
