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
use Payplug\Exception\ConfigurationException;
use Payplug\Exception\ConfigurationNotSetException;
use Payplug\Exception\HttpException;
use Payplug\Payplug;
use Propel\Runtime\Exception\PropelException;
use Stripe\Customer as StripeCustomer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
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
     * @returns array the payement intent and customer session client secrets returned by Stripe
     */
    public function createStripePaymentForOrder(Order $order): array
    {
        if (!$this->stripe) {
            throw new InvalidConfigurationException("Stripe n’est pas configuré.");
        }

        $customer = $this->_getOrCreateStripeCustomerForOrder($order);
        $paymentIntent = $this->_createStripePaymentIntentForOrder($order, $customer);

        $customerSession = $this->stripe->customerSessions->create([
            "customer" => $customer->id,
            "components" => [
                "payment_element" => [
                    "enabled" => true,
                    "features" => [
                        "payment_method_redisplay" => "enabled",
                        "payment_method_save" => "enabled",
                        "payment_method_save_usage" => "on_session",
                        "payment_method_remove" => "enabled",
                    ]
                ]
            ]
        ]);

        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setMode("stripe");
        $payment->setAmount($order->getAmountTobepaid());
        $payment->setProviderId($paymentIntent->id);
        $payment->save();

        return [
            "payment_intent_client_secret" => $paymentIntent->client_secret,
            "customer_session_client_secret" => $customerSession->client_secret,
        ];
    }

    /**
     * @throws ApiErrorException
     * @throws InvalidConfigurationException
     * @throws PropelException
     */
    private function _getOrCreateStripeCustomerForOrder(Order $order): StripeCustomer
    {
        if (!$this->stripe) {
            throw new InvalidConfigurationException("Stripe n’est pas configuré.");
        }

        $searchResults = $this->stripe->customers->search(["query" => "metadata['customer_id']:'" . $order->getCustomerId() . "'"]);
        if (count($searchResults->data) === 0) {
            return $this->stripe->customers->create([
                "name" => $order->getCustomer()->getFullName(),
                "email" => $order->getCustomer()->getEmail(),
                "metadata" => ["customer_id" => $order->getCustomerId()],
            ]);
        }

        return $this->stripe->customers->update($searchResults->data[0]->id, [
            "name" => $order->getCustomer()->getFullName(),
            "email" => $order->getCustomer()->getEmail(),
            "metadata" => ["customer_id" => $order->getCustomerId()],
        ]);
    }

    /**
     * @throws InvalidConfigurationException
     * @throws ApiErrorException
     */
    private function _createStripePaymentIntentForOrder(Order $order, StripeCustomer $customer): PaymentIntent
    {
        if (!$this->stripe) {
            throw new InvalidConfigurationException("Stripe n’est pas configuré.");
        }

        return $this->stripe->paymentIntents->create([
            "amount" => $order->getAmountTobepaid(),
            "currency" => "eur",
            "customer" => $customer->id,
            "payment_method_types" => ["card"],
            "metadata" => [
                "order_id" => $order->getId(),
            ],
        ]);
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
            $payment->save();

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