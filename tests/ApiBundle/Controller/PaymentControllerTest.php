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

use Biblys\Service\Config;
use Biblys\Service\LoggerService;
use Biblys\Service\PaymentService;
use Biblys\Test\ModelFactory;
use Mockery;
use Model\Payment;
use Model\PaymentQuery;
use PaypalServerSdkLib\Controllers\OrdersController;
use PaypalServerSdkLib\Http\ApiResponse;
use PaypalServerSdkLib\PaypalServerSdkClient;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PaymentControllerTest extends TestCase
{

    /* createStripePaymentAction */

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

    /* paypalCaptureAction */

    /**
     * @throws PropelException
     * @throws TransportExceptionInterface
     */
    public function testPaypalCaptureAction(): void
    {
        // given
        $order = ModelFactory::createOrder(amountToBePaid: 2500);

        $config = Mockery::mock(Config::class);
        $config->expects("isPayPalEnabled")->andReturn(true);

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->expects("getPayableOrderBySlug")->andReturn($order);

        $request = new Request([], [], [], [], [], [], json_encode(["paypalOrderId" => "PAYPAL_ORDER_123"]));

        $logger = Mockery::mock(LoggerService::class);
        $logger->expects("log")->andReturnNull();

        $captureResponseData = [
            "status" => "COMPLETED",
            "purchase_units" => [
                [
                    "payments" => [
                        "captures" => [
                            ["amount" => ["value" => "25.00"]],
                        ],
                    ],
                ],
            ],
        ];

        $mockApiResponse = Mockery::mock(ApiResponse::class);
        $mockApiResponse->allows("getBody")->andReturn(json_encode($captureResponseData));
        $mockApiResponse->allows("getStatusCode")->andReturn(201);

        $mockOrdersController = Mockery::mock(OrdersController::class);
        $mockOrdersController->expects("captureOrder")
            ->with(["id" => "PAYPAL_ORDER_123"])
            ->andReturn($mockApiResponse);

        $mockPaypalClient = Mockery::mock(PaypalServerSdkClient::class);
        $mockPaypalClient->allows("getOrdersController")->andReturn($mockOrdersController);

        $controller = Mockery::mock(PaymentController::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $controller->shouldAllowMockingProtectedMethods();
        $controller->expects("_createPayPalClient")->andReturn($mockPaypalClient);

        // when
        $response = $controller->paypalCaptureAction($config, $paymentService, $request, $logger, $order->getSlug());

        // then
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($captureResponseData), $response->getContent());
        $order->reload();
        $this->assertTrue($order->isPaid(), "the order should have been marked as paid");
        $payment = PaymentQuery::create()->findOneByOrderId($order->getId());
        $this->assertNotNull($payment, "a payment should have been created");
        $this->assertEquals(Payment::MODE_PAYPAL, $payment->getMode());
        $this->assertEquals(2500, $payment->getAmount());
        $this->assertTrue($payment->isExecuted(), "the payment should have been marked as executed");
    }
}
