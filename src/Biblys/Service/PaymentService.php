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


namespace Biblys\Service;

use Biblys\Exception\CannotFindPayableOrderException;
use Biblys\Exception\InvalidConfigurationException;
use Exception;
use Model\Order;
use Model\OrderQuery;
use Model\Payment;
use Model\Stock;
use Payplug\Exception\ConfigurationException;
use Payplug\Exception\ConfigurationNotSetException;
use Payplug\Exception\HttpException;
use Payplug\Payplug;
use Propel\Runtime\Exception\PropelException;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Component\Routing\Generator\UrlGenerator;

class PaymentService
{
    public function __construct(
        private readonly Config        $config,
        private readonly CurrentSite   $currentSite,
        private readonly UrlGenerator  $urlGenerator,
        private readonly LoggerService $loggerService,
        private readonly ?StripeClient $stripe = null,
    )
    {
    }

    /**
     * @throws CannotFindPayableOrderException
     * @throws PropelException
     */
    public function getPayableOrderBySlug(string $slug): Order
    {
        $order = OrderQuery::create()->findOneBySlug($slug);

        if ($order === null) {
            throw new CannotFindPayableOrderException("Commande inconnue");
        }

        if ($order->isPaid()) {
            throw new CannotFindPayableOrderException("Commande déjà payée");
        }

        if ($order->isCancelled()) {
            throw new CannotFindPayableOrderException("Commande annulée");
        }

        return $order;
    }

    /**
     * @throws ApiErrorException
     * @throws InvalidConfigurationException
     * @throws PropelException
     * @throws Exception
     */
    public function createStripePaymentForOrder(Order $order): Payment
    {
        if (!$this->stripe) {
            throw new InvalidConfigurationException("Stripe n’est pas configuré.");
        }

        // Add each copy to Stripe line items
        $stockItems = $order->getStockItems();
        $lineItems = array_map(/**
         * @param Stock $stockItem
         * @return array
         * @throws ApiErrorException|PropelException
         */ function (Stock $stockItem) {
            $product = $this->stripe->products->create(["name" => $stockItem->getArticle()->getTitle()]);
            $price = $this->stripe->prices->create([
                "product" => $product->id,
                "unit_amount" => $stockItem->getSellingPrice() ?? 0,
                "currency" => "EUR",
            ]);
            return ["quantity" => 1, "price" => $price->id];
        }, $stockItems->getArrayCopy());

        $amountToPay = array_reduce($stockItems->getArrayCopy(), function ($total, Stock $current) {
            return $total + $current->getSellingPrice();
        }, 0);

        // Add shipping cost as a line item
        $shippingCost = $order->getShippingCost();
        if ($shippingCost && $shippingCost !== 0) {
            $product = $this->stripe->products->create(["name" => "Frais de port"]);
            $price = $this->stripe->prices->create([
                "product" => $product->id,
                "unit_amount" => $shippingCost,
                "currency" => "EUR",
            ]);
            $lineItems[] = ["quantity" => 1, "price" => $price->id];
            $amountToPay += $shippingCost;
        }

        if ($amountToPay !== (int)$order->getAmountTobepaid()) {
            throw new Exception("Stripe's amount to pay ($amountToPay) does not match order's amount to be paid (" . $order->getAmountTobepaid() . ").");
        }

        $session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'https://' . $_SERVER["HTTP_HOST"] . '/order/' . $order->getSlug() . '?payed=1',
            'cancel_url' => 'https://' . $_SERVER["HTTP_HOST"] . '/payment/' . $order->getSlug(),
            'customer_email' => $order->getEmail(),
        ]);

        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setMode("stripe");
        $payment->setAmount($amountToPay);
        $payment->setProviderId($session["id"]);

        return $payment;
    }

    /**
     * @param Order $order
     * @return Payment
     * @throws ConfigurationException
     * @throws ConfigurationNotSetException
     * @throws InvalidConfigurationException
     * @throws PropelException
     * @throws HttpException
     */
    public function createPayplugPaymentForOrder(Order $order): Payment
    {
        $payplug = $this->config->get('payplug');
        if (!$payplug) {
            throw new InvalidConfigurationException("Payplug is not configured.");
        }

        if (empty($payplug["secret"])) {
            throw new InvalidConfigurationException("Missing Payplug secret key.");
        }

        Payplug::init(
            [
                'secretKey' => $payplug["secret"],
                'apiVersion' => '2019-08-06',
            ]
        );

        $total_amount = $order->getTotalAmountWithShipping();

        $ipn_protocol = 'https';
        if (isset($payplug['ipn_protocol'])) {
            $ipn_protocol = $payplug['ipn_protocol'];
        }

        $ipn_host = $this->currentSite->getSite()->getDomain();
        if (isset($payplug['ipn_host'])) {
            $ipn_host = $payplug['ipn_host'];
        }

        $notification_url = $ipn_protocol . '://' . $ipn_host .
            $this->urlGenerator->generate("order_payplug_notification", ["url" => $order->getSlug()]);

        // Gather customer info
        $billing = [
            "first_name" => $order->getFirstname(),
            "last_name" => $order->getLastname(),
            "email" => $order->getEmail(),
            "address1" => $order->getAddress1(),
            "postcode" => $order->getPostalcode(),
            "city" => $order->getCity(),
            "country" => $order->getCountry()->getCode(),
        ];

        $shipping = $billing;
        $shipping["delivery_type"] = "BILLING";

        try {
            $returnUrl = $this->urlGenerator->generate("legacy_order", ["url" => $order->getSlug()]);
            $cancelUrl = $this->urlGenerator->generate("payment_pay", ["slug" => $order->getSlug()]);
            $response = \Payplug\Payment::create([
                'amount' => $total_amount,
                'currency' => 'EUR',
                'billing' => $billing,
                'shipping' => $shipping,
                'hosted_payment' => [
                    'return_url' => "https://{$this->currentSite->getSite()->getDomain()}$returnUrl",
                    'cancel_url' => "https://{$this->currentSite->getSite()->getDomain()}$cancelUrl"
                ],
                'notification_url' => $notification_url,
                'metadata' => [
                    'order_id' => $order->getId(),
                ]
            ]);

            $payment = new Payment();
            $payment->setOrder($order);
            $payment->setMode("payplug");
            $payment->setAmount($total_amount);
            $payment->setProviderId($response->id);
            $payment->setUrl($response->hosted_payment->payment_url);

            return $payment;
        } catch (HttpException $exception) {
            $this->loggerService->log(
                "payplug",
                "ERROR",
                "An error occurred while creating a Payment for order " . $order->getId(),
                [$exception->getHttpResponse()],
            );
            throw $exception;
        }
    }
}