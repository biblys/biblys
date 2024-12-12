<?php
/*
 * Copyright (C) 2024 Clément Latzarus
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


namespace AppBundle\Controller;

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\LoggerService;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Exception;
use Framework\Controller;
use Model\OrderQuery;
use Order;
use OrderManager;
use PaymentManager;
use PayPal\Exception\PayPalConnectionException;
use Payplug\Exception\ConfigurationException;
use Payplug\Exception\PayplugException;
use Payplug\Exception\UnknownAPIResourceException;
use Payplug\Notification;
use Payplug\Payplug;
use Payplug\Resource\Payment;
use Payplug\Resource\Refund;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Usecase\MarkOrderAsShippedUsecase;

class OrderController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function indexAction(
        Request $request,
        CurrentUser $currentUser,
        TemplateService $templateService,
    ): JsonResponse|Response
    {
        $currentUser->authAdmin();

        // JSON Raw data
        if ($request->isXmlHttpRequest()) {
            $om = new OrderManager();

            $where = ['order_type' => 'web', 'order_cancel_date' => 'NULL'];

            // Status filter
            $status = $request->query->get('status', false);
            if ($status == 1) {
                $where['order_payment_date'] = 'NULL';
            } elseif ($status == 2) {
                $where['order_payment_date'] = 'NOT NULL';
                $where['order_shipping_date'] = 'NULL';
                $where['order_shipping_mode'] = '!= magasin';
            } elseif ($status == 3) {
                $where['order_shipping_date'] = 'NULL';
                $where['order_shipping_mode'] = 'magasin';
            } elseif ($status == 4) {
                $where['order_cancel_date'] = 'NOT NULL';
            }

            // Payment filter
            $payment = $request->query->get('payment', false);
            if ($payment) {
                $where['order_payment_mode'] = $payment;
            }

            // Shipping filter
            $shipping = $request->query->get('shipping', false);
            if ($shipping) {
                $where['order_shipping_mode'] = $shipping;
            }

            // Options
            $offset = $request->query->get('offset', 0);
            $options = [
                'limit' => 100,
                'offset' => $offset,
                'order' => 'order_created',
                'sort' => 'desc',
            ];

            // Query filter
            $query = $request->query->get('query', false);
            if ($query) {
                $orders = $om->search($query, $where, $options);
                $total = 0;
            } else {
                $total = $om->count($where);
                $orders = $om->getAll($where, $options, false);
            }

            $orders = array_map([$this, '_jsonOrder'], $orders);

            $response = [
                'results' => count($orders),
                'total' => $total,
                'orders' => $orders,
            ];

            return new JsonResponse($response);
        }

        // Index view
        $request->attributes->set("page_title", "Commandes web");

        return $templateService->renderResponse("AppBundle:Order:index.html.twig");
    }

    /**
     * @throws PropelException
     */
    public function show(
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        int         $id,
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $order = OrderQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterById($id)
            ->findOne();

        if (!$order) {
            throw new ResourceNotFoundException();
        }

        return new RedirectResponse("/order/{$order->getSlug()}");
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function updateAction(
        Request         $request,
        CurrentSite     $currentSite,
        TemplateService $templateService,
        Mailer          $mailer,
                        $id,
                        $action
    ): JsonResponse
    {
        $notice = "";

        /** @var Order $orderEntity */
        $om = new OrderManager();
        $orderEntity = $om->getById($id);

        /** @var \Model\Base\Order $order */
        $order = OrderQuery::create()->findPk($id);

        if ($action == 'payed') {
            $amount = $orderEntity->get('amount_tobepaid');
            $payment_mode = $request->request->get('payment_mode');
            $om->addPayment($orderEntity, $payment_mode, $amount);
            $notice = 'La commande n°&nbsp;'.$orderEntity->get('id').' de '.$orderEntity->get('firstname').' '.$orderEntity->get('lastname').' a été marquée comme payée.';
        }

        if ($action == 'shipped') {
            $trackingNumber = $request->request->get("tracking_number");
            if (strlen($trackingNumber) > 16) {
                throw new BadRequestHttpException("Le numéro de suivi ne peut pas dépasser 16 caractères.");
            }

            $usecase = new MarkOrderAsShippedUsecase($currentSite, $templateService, $mailer);
            $usecase->execute($order, $trackingNumber);

            $notice = 'La commande n°&nbsp;'.$orderEntity->get('id').' de '.$orderEntity->get('firstname').' '.$orderEntity->get('lastname').' a été marquée comme expédiée.';
        }

        if ($action == 'followup') {
            $om->followUp($orderEntity);
            $notice = 'Le client '.$orderEntity->get('firstname').' '.$orderEntity->get('lastname').' a été relancée pour la commande n°&nbsp;'.$orderEntity->get('id').'.';
        } elseif ($action == 'cancel') {
            $om->cancel($orderEntity);
            $notice = 'La commande n°&nbsp;'.$orderEntity->get('id').' de '.$orderEntity->get('firstname').' '.$orderEntity->get('lastname').' a été annulée.';
        }

        return new JsonResponse([
            'notice' => $notice,
            'order' => $this->_jsonOrder($orderEntity),
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function paypalProcessAction(
        Request $request,
        LoggerService $loggerService,
        $url
    ): RedirectResponse|Response
    {
        $om = new OrderManager();

        // Check if order exists
        $order = $om->get(['order_url' => $url]);
        if (!$order) {
            $loggerService->log("paypal", "ERROR", "Order $url not found.");
            throw new NotFoundException("Order $url not found.");
        }
        $loggerService->log("paypal", "INFO", 'Initiating paypal process for order ' . $order->get('id') . ' from ' . $request->headers->get('referer'));

        // Get paypal parameters from request
        $paymentId = $request->query->get('paymentId');
        $payerId = $request->query->get('PayerID');
        if (!$paymentId || !$payerId) {
            $loggerService->log("paypal", "ERROR", 'Missing parameters.', ['order_id' => $order->get('id')]);
            throw new Exception('Missing parameters.');
        }
        $loggerService->log("paypal", "INFO", "Got paymentId ($paymentId) and payerId ($payerId) for order " . $order->get('id'));

        // Execute payment
        try {
            $payment = $order->executePaypalPayment($paymentId, $payerId);
        }
        catch (PayPalConnectionException $exception) {
            $loggerService->log("paypal", 
                "ERROR",
                $exception->getMessage(),
                ['order_id' => $order->get('id'), 'paymentId' => $paymentId]
            );
            $data = json_decode($exception->getData());

            return $this->render(
                "AppBundle:Order:payment-error.html.twig",
                [
                    "order" => $order,
                    "code" => $data->name,
                    "message" => $data->message
                ]
            );
        }
        catch (Exception $e) {
            dump($e);
            $loggerService->log("paypal", "ERROR", $e->getMessage(), ['order_id' => $order->get('id'), 'paymentId' => $paymentId]);
            throw new Exception('Une erreur est survenue pendant l\'execution du paiement PayPal. Merci de nous contacter.');
        }
        $loggerService->log("paypal", "INFO", "Got paymentId ($paymentId) and payerId ($payerId) for order " . $order->get('id'));

        // Check that payment was approved
        $state = $payment->getState();
        if ($state !== 'approved') {
            $loggerService->log("paypal", "ERROR", "Payment state is invalid ($state).", ['order_id' => $order->get('id'), 'paymentId' => $paymentId]);
            throw new Exception("Payment state is invalid ($state).");
        }
        $loggerService->log("paypal", "INFO", "Payment ($paymentId) state is $state for order " . $order->get('id'));

        // Get invoice number from first transaction and compare to order id
        $transaction = $payment->getTransactions()[0];
        $invoice = $transaction->getInvoiceNumber();
        if ($invoice != $order->get('id')) {
            $loggerService->log("paypal", "ERROR", "Invoice number ($invoice) does not match order ID (" . $order->get('id') . ').', ['order_id' => $order->get('id'), 'paymentId' => $paymentId]);
            throw new Exception('Invoice number does not match order ID.');
        }
        $loggerService->log("paypal", "INFO", "Invoice number ($invoice) matches order id (" . $order->get('id') . ')');

        // Get amount and add payment to current order
        $amount = $transaction->getAmount();
        $total = $amount->getTotal();
        $om->addPayment($order, 'paypal', $total * 100);
        $loggerService->log("paypal", "INFO", "Payment amount ($amount) was added to order " . $order->get('id'));

        return new RedirectResponse("/order/{$order->get("url")}");
    }

    /**
     * @throws ConfigurationException
     * @throws UnknownAPIResourceException
     * @throws PayplugException
     * @throws Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function payplugNotificationAction(
        Request $request,
        LoggerService $loggerService,
        $url
    ): Response
    {
        $config = \Biblys\Legacy\LegacyCodeHelper::getGlobalConfig();;

        $payplug_config = $config->get('payplug');
        if (!$payplug_config) {
            $loggerService->log("payplug", "ERROR", 'Payplug configuration not found.');
            throw new Exception('Payplug configuration not found.');
        }

        if (!isset($payplug_config['secret'])) {
            $loggerService->log("payplug", "ERROR", 'Missing payplug private key.');
            throw new Exception('Missing payplug private key.');
        }

        Payplug::init(["secretKey" => $payplug_config['secret']]);

        $om = new OrderManager();
        $pm = new PaymentManager();

        // Check if order exists
        $order = $om->get(['order_url' => $url]);
        if (!$order) {
            $loggerService->log("payplug", "ERROR", "Order $url not found.");
            throw new Exception("Order $url not found.");
        }
        $loggerService->log("payplug", "INFO", 'Receiving Payplug notification for order ' . $order->get('id') . ' from ' . $request->headers->get('referer'));

        // Process notification
        $input = $request->getContent();
        try {
            $resource = Notification::treat($input);

            if ($resource instanceof Refund) {
                $loggerService->log("payplug", "INFO", 'Ignoring resource ' . $resource->id . ' (refund)');
                return new Response();
            }

            if (!$resource instanceof Payment) {
                $loggerService->log("payplug", "ERROR", 'Resource ' . $resource->id . '  is not a Payment.');
                throw new Exception('Resource '.$resource->id.'  is not a Payment.');
            }

            // Payment failed, log error and ignore process
            if (!$resource->is_paid) {
                $loggerService->log("payplug", "ERROR", 'Payment ' . $resource->id . '  is not paid.');
                return new Response('');
            }

            // Check if payment exists
            $payment = $pm->get(['payment_provider_id' => $resource->id]);
            if (!$payment) {
                $loggerService->log("payplug", "ERROR", 'Payment ' . $resource->id . ' not found.');
                throw new Exception('Payment '.$resource->id.' not found.');
            }
            $loggerService->log("payplug", "INFO", 'Found payment ' . $payment->get('id') . ' in database.');

            // Get order id from metadata and compare to database order id
            if ($resource->metadata['order_id'] != $order->get('id')) {
                $loggerService->log("payplug", "ERROR", 'Order id from Payplug (' . $resource->metadata['order_id'] . ') does not match order ID (' . $order->get('id') . ').');
                throw new Exception('Invoice number does not match order ID.');
            }
            $loggerService->log("payplug", "INFO", 'Received order id (' . $resource->metadata['order_id'] . ' matches order id in database.');

            // Add payment to the order
            $om->addPayment($order, $payment);
            $loggerService->log("payplug", "INFO", 'Payment amount (' . $payment->get('amount') . ') was added to order ' . $order->get('id'));

            return new Response('');
        } catch (UnknownAPIResourceException $exception) {
            $loggerService->log("payplug", "ERROR", 'UnknownAPIResourceException: ' . $exception->getMessage());
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        } catch (PayplugException $exception) {
            $loggerService->log("payplug", "ERROR", 'PayplugException: ' . $exception->getMessage());
            throw $exception;
        }
    }

    private function _jsonOrder(Order $order): array
    {
        return [
            'id' => $order->get('id'),
            'url' => $order->get('url'),
            'customer' => $order->get('firstname').' '.$order->get('lastname'),
            'amount' => currency($order->get('amount') / 100),
            'total' => currency(($order->get('amount') + $order->get('shipping')) / 100),
            'created' => $order->get('created'),
            'payment_mode' => $order->get('payment_mode'),
            'payment_date' => $order->get('payment_date'),
            'shipping_mode' => $order->get('shipping_mode'),
            'shipping_date' => $order->get('shipping_date'),
            'shipping_amount' => currency($order->get('shipping') / 100),
            'followup_date' => $order->get('followup_date'),
            'cancel_date' => $order->get('cancel_date'),
        ];
    }
}
