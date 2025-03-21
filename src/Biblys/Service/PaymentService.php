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
use Propel\Runtime\Exception\PropelException;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class PaymentService
{
    public function __construct(private readonly Config $config)
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
        $stripe = $this->config->get('stripe');
        if (!$stripe) {
            throw new InvalidConfigurationException("Stripe is not configured.");
        }

        if (empty($stripe["public_key"])) {
            throw new InvalidConfigurationException("Missing Stripe public key.");
        }

        if (empty($stripe["secret_key"])) {
            throw new InvalidConfigurationException("Missing Stripe secret key.");
        }

        if (empty($stripe["endpoint_secret"])) {
            throw new InvalidConfigurationException("Missing Stripe endpoint secret.");
        }

        Stripe::setApiKey($stripe['secret_key']);

        // Add each copy to Stripe line items
        $stockItems = $order->getStockItems();
        $lineItems = array_map(/**
         * @param Stock $stockItem
         * @return array
         * @throws ApiErrorException|PropelException
         */ function(Stock $stockItem) {
            $product = Product::create(["name" => $stockItem->getArticle()->getTitle()]);
            $price = Price::create([
                "product" => $product->id,
                "unit_amount" => $stockItem->getSellingPrice() ?? 0,
                "currency" => "EUR",
            ]);
            return [ "quantity" => 1, "price" => $price->id ];
        }, $stockItems->getArrayCopy());

        $amountToPay = array_reduce($stockItems->getArrayCopy(), function($total, Stock $current) {
            return $total + $current->getSellingPrice();
        }, 0);

        // Add shipping cost as a line item
        $shippingCost = $order->getShippingCost();
        if ($shippingCost && $shippingCost !== 0) {
            $product = Product::create(["name" => "Frais de port"]);
            $price = Price::create([
                "product" => $product->id,
                "unit_amount" => $shippingCost,
                "currency" => "EUR",
            ]);
            $lineItems[] = [ "quantity" => 1, "price" => $price->id ];
            $amountToPay += $shippingCost;
        }

        if ($amountToPay !== (int) $order->getAmountTobepaid()) {
            throw new Exception("Stripe's amount to pay ($amountToPay) does not match order's amount to be paid (".$order->getAmountTobepaid().").");
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'https://' .$_SERVER["HTTP_HOST"].'/order/'.$order->getSlug().'?payed=1',
            'cancel_url' => 'https://' .$_SERVER["HTTP_HOST"].'/payment/'.$order->getSlug(),
            'customer_email' => $order->getEmail(),
        ]);

        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setMode("stripe");
        $payment->setAmount($amountToPay);
        $payment->setProviderId($session["id"]);

        return $payment;
    }
}