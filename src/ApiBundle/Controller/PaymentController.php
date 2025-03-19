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
use Framework\Controller;
use Model\OrderQuery;
use Order;
use OrderManager;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\Models\Builders\AmountBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\ItemBuilder;
use PaypalServerSdkLib\Models\Builders\MoneyBuilder;
use PaypalServerSdkLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSdkLib\Models\Builders\PurchaseUnitRequestBuilder;
use PaypalServerSdkLib\PaypalServerSdkClient;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PaymentController extends Controller
{
    public function paypalCreateOrderAction(Config $config, string $slug): JsonResponse
    {
        if (!$config->isPayPalEnabled()) {
            throw new NotFoundHttpException("PayPal n'est pas configuré sur ce site");
        }

        $order = OrderQuery::create()->findOneBySlug($slug);
        if (!$order) {
            throw new NotFoundHttpException("Commande non trouvée");
        }

        $client = $this->_createPayPalClient($config);

        $orderAmount = round($order->getAmountTobepaid() / 100, 2);

        $orderBody = [
            "body" => OrderRequestBuilder::init("CAPTURE", [
                PurchaseUnitRequestBuilder::init(
                    AmountWithBreakdownBuilder::init("EUR", $orderAmount)
                        ->breakdown(
                            AmountBreakdownBuilder::init()
                                ->itemTotal(
                                    MoneyBuilder::init("EUR", $orderAmount)->build()
                                )
                                ->build()
                        )
                        ->build()
                )
                    ->items([
                        ItemBuilder::init(
                            "Commande n° {$order->getId()}",
                            MoneyBuilder::init("EUR", $orderAmount)->build(),
                            "1"
                        )
                            ->description("Commande n° {$order->getId()}")
                            ->sku($order->getId())
                            ->build(),
                    ])
                    /*{shippingWrapper}*/
                    ->build(),
            ])
                /*{serverSideShippingCallback}*/
                ->build(),
        ];

        $apiResponse = $client->getOrdersController()->ordersCreate($orderBody);
        $jsonResponse = json_decode($apiResponse->getBody(), true);

        return new JsonResponse($jsonResponse, $apiResponse->getStatusCode());
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function paypalCaptureAction(Request $request, Config $config, string $slug): JsonResponse
    {
        if (!$config->isPayPalEnabled()) {
            throw new NotFoundHttpException("PayPal n'est pas configuré sur ce site");
        }

        $order = OrderQuery::create()->findOneBySlug($slug);
        if (!$order) {
            throw new NotFoundHttpException("Commande non trouvée");
        }

        $data = json_decode($request->getContent());

        $client = $this->_createPayPalClient($config);
        $apiResponse = $client->getOrdersController()->ordersCapture(["id" => $data->paypalOrderId]);
        $jsonResponse = json_decode($apiResponse->getBody(), true);

        if ($jsonResponse["status"] === "COMPLETED") {
            $paidAmount = $jsonResponse["purchase_units"][0]["payments"]["captures"][0]["amount"]["value"];
            $orderManager = new OrderManager();
            /** @var Order $orderEntity */
            $orderEntity = $orderManager->getById($order->getId());
            $orderManager->addPayment($orderEntity, "paypal", $paidAmount * 100);
        }

        return new JsonResponse($jsonResponse, $apiResponse->getStatusCode());
    }

    /**
     * @param Config $config
     * @return PaypalServerSdkClient
     */
    private function _createPayPalClient(Config $config): PaypalServerSdkClient
    {
        return PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                ClientCredentialsAuthCredentialsBuilder::init(
                    $config->get("paypal.client_id"),
                    $config->get("paypal.client_secret")
                )
            )
            ->environment(Environment::SANDBOX)
            ->build();
    }
}