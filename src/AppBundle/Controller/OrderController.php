<?php

namespace AppBundle\Controller;

use Biblys\Service\Log;
use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class OrderController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->auth('admin');

        // JSON Raw data
        if ($request->isXmlHttpRequest()) {
            $om = $this->entityManager('Order');

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
                $orders = $om->search($query, $where, $options, false);
                $total = 0;
            } else {
                $total = $om->count($where, [], false);
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
        $this->setPageTitle('Commandes web');

        return $this->render('AppBundle:Order:index.html.twig');
    }

    public function updateAction(Request $request, $id, $action)
    {
        $om = $this->entityManager('Order');
        $order = $om->getById($id);

        if ($action == 'payed') {
            $amount = $order->get('amount_tobepaid');
            $payment_mode = $request->request->get('payment_mode', null);
            $om->addPayment($order, $payment_mode, $amount);
            $notice = 'La commande n°&nbsp;'.$order->get('id').' de '.$order->get('firstname').' '.$order->get('lastname').' a été marquée comme payée.';
        }

        if ($action == 'shipped') {
            $tracking_number = $request->request->get('tracking_number', null);
            $om->markAsShipped($order, $tracking_number);
            $notice = 'La commande n°&nbsp;'.$order->get('id').' de '.$order->get('firstname').' '.$order->get('lastname').' a été marquée comme expédiée.';
        }

        if ($action == 'followup') {
            $om->followUp($order);
            $notice = 'Le client '.$order->get('firstname').' '.$order->get('lastname').' a été relancée pour la commande n°&nbsp;'.$order->get('id').'.';
        } elseif ($action == 'cancel') {
            $om->cancel($order);
            $notice = 'La commande n°&nbsp;'.$order->get('id').' de '.$order->get('firstname').' '.$order->get('lastname').' a été annulée.';
        }

        return new JsonResponse([
            'notice' => $notice,
            'order' => $this->_jsonOrder($order),
        ]);
    }

    public function paypalProcessAction(Request $request, $url)
    {
        $om = $this->entityManager('Order');

        // Check if order exists
        $order = $om->get(['order_url' => $url]);
        if (!$order) {
            Log::paypal("ERROR", "Order $url not found.");
            throw new NotFoundException("Order $url not found.");
        }
        Log::paypal("INFO", 'Initiating paypal process for order ' . $order->get('id') . ' from ' . $request->headers->get('referer'));

        // Get paypal parameters from request
        $paymentId = $request->query->get('paymentId');
        $payerId = $request->query->get('PayerID');
        if (!$paymentId || !$payerId) {
            Log::paypal("ERROR", 'Missing parameters.', ['order_id' => $order->get('id')]);
            throw new \Exception('Missing parameters.');
        }
        Log::paypal("INFO", "Got paymentId ($paymentId) and payerId ($payerId) for order " . $order->get('id'));

        // Execute payment
        try {
            $payment = $order->executePaypalPayment($paymentId, $payerId);
        } catch (\Exception $e) {
            Log::paypal("ERROR", $e->getMessage(), ['order_id' => $order->get('id'), 'paymentId' => $paymentId]);
            throw new \Exception('Une erreur est survenue pendant l\'execution du paiement PayPal. Merci de nous contacter.');
        }
        Log::paypal("INFO", "Got paymentId ($paymentId) and payerId ($payerId) for order " . $order->get('id'));

        // Check that payment was approved
        $state = $payment->getState();
        if ($state !== 'approved') {
            Log::paypal("ERROR", "Payment state is invalid ($state).", ['order_id' => $order->get('id'), 'paymentId' => $paymentId]);
            throw new \Exception("Payment state is invalid ($state).");
        }
        Log::paypal("INFO", "Payment ($paymentId) state is $state for order " . $order->get('id'));

        // Get invoice number from first transaction and compare to order id
        $transaction = $payment->getTransactions()[0];
        $invoice = $transaction->getInvoiceNumber();
        if ($invoice != $order->get('id')) {
            Log::paypal("ERROR", "Invoice number ($invoice) does not match order ID (" . $order->get('id') . ').', ['order_id' => $order->get('id'), 'paymentId' => $paymentId]);
            throw new \Exception('Invoice number does not match order ID.');
        }
        Log::paypal("INFO", "Invoice number ($invoice) matches order id (" . $order->get('id') . ')');

        // Get amount and add payment to current order
        $amount = $transaction->getAmount();
        $total = $amount->getTotal();
        $om->addPayment($order, 'paypal', $total * 100);
        Log::paypal("INFO", "Payment amount ($amount) was added to order " . $order->get('id'));

        return $this->redirect('/order/'.$order->get('url'));
    }

    public function payplugNotificationAction(Request $request, $url): Response
    {
        global $config;

        $payplug_config = $config->get('payplug');
        if (!$payplug_config) {
            Log::payplug("ERROR", 'Payplug configuration not found.');
            throw new \Exception('Payplug configuration not found.');
        }

        if (!isset($payplug_config['secret'])) {
            Log::payplug("ERROR", 'Missing payplug private key.');
            throw new \Exception('Missing payplug private key.');
        }

        \Payplug\Payplug::init(["secretKey" => $payplug_config['secret']]);

        $om = $this->entityManager('Order');
        $pm = $this->entityManager('Payment');

        // Check if order exists
        $order = $om->get(['order_url' => $url]);
        if (!$order) {
            Log::payplug("ERROR", "Order $url not found.");
            throw new \Exception("Order $url not found.");
        }
        Log::payplug("INFO", 'Receiving Payplug notification for order ' . $order->get('id') . ' from ' . $request->headers->get('referer'));

        // Process notification
        $input = $request->getContent();
        try {
            $resource = \Payplug\Notification::treat($input);

            if ($resource instanceof \Payplug\Resource\Refund) {
                Log::payplug("INFO", 'Ignoring resource ' . $resource->id . ' (refund)');
                return new Response();
            }

            if (!$resource instanceof \Payplug\Resource\Payment) {
                Log::payplug("ERROR", 'Resource ' . $resource->id . '  is not a Payment.');
                throw new \Exception('Resource '.$resource->id.'  is not a Payment.');
            }

            // Payment failed, log error and ignore process
            if (!$resource->is_paid) {
                Log::payplug("ERROR", 'Payment ' . $resource->id . '  is not paid.');
                return new Response('');
            }

            // Check if payment exists
            $payment = $pm->get(['payment_provider_id' => $resource->id]);
            if (!$payment) {
                Log::payplug("ERROR", 'Payment ' . $resource->id . ' not found.');
                throw new \Exception('Payment '.$resource->id.' not found.');
            }
            Log::payplug("INFO", 'Found payment ' . $payment->get('id') . ' in database.');

            // Get order id from metadata and compare to database order id
            if ($resource->metadata['order_id'] != $order->get('id')) {
                Log::payplug("ERROR", 'Order id from Payplug (' . $resource->metadata['order_id'] . ') does not match order ID (' . $order->get('id') . ').');
                throw new \Exception('Invoice number does not match order ID.');
            }
            Log::payplug("INFO", 'Received order id (' . $resource->metadata['order_id'] . ' matches order id in database.');

            // Add payment to the order
            $om->addPayment($order, $payment);
            Log::payplug("INFO", 'Payment amount (' . $payment->get('amount') . ') was added to order ' . $order->get('id'));

            return new Response('');
        } catch (\Payplug\Exception\PayplugException $exception) {
            Log::payplug("ERROR", 'Exception: ' . $exception->getMessage());
            throw new \Exception('Exception: '.$exception->getMessage());
        }
    }

    /**
     * Display conversions for recents orders
     * /admin/orders/conversions.
     *
     * @return [type] [description]
     */
    public function conversionsAction(Request $request)
    {
        $this->auth('admin');
        $this->setPageTitle('Suivi des conversions');

        $filters = ['order_utm_source' => 'NOT NULL'];

        $utmSource = $request->query->get('source');
        if ($utmSource) {
            $filters['order_utm_source'] = $utmSource;
        }

        $utmCampaign = $request->query->get('campaign');
        if ($utmCampaign) {
            $filters['order_utm_campaign'] = $utmCampaign;
        }

        $utmMedium = $request->query->get('medium');
        if ($utmSource) {
            $filters['order_utm_medium'] = $utmMedium;
        }

        $om = $this->entityManager('Order');

        // Pagination
        $page = (int) $request->query->get('p', 0);
        $totalCount = $om->count($filters);
        $pagination = new \Biblys\Service\Pagination($page, $totalCount, 100);

        $orders = $om->getAll($filters, [
            'order' => 'order_payment_date',
            'sort' => 'desc',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset(),
        ]);

        return $this->render('AppBundle:Order:conversions.html.twig', array_merge($filters, [
            'orders' => $orders,
            'pages' => $pagination,
        ]));
    }

    /**
     * Mass converts utmz property to utm_ properties.
     */
    public function convertUtmzAction()
    {
        $this->auth('admin');
        $this->setPageTitle('Conversion UTMZ');

        $om = $this->entityManager('Order');
        $order = $om->get(['order_utmz' => 'NOT NULL'], [
            'order' => 'order_payment_date',
            'sort' => 'desc',
        ]);

        $count = $om->count(['order_utmz' => 'NOT NULL']);

        if ($order) {
            $order->convertUtmz();
            $om->update($order);
        }

        $response = new Response($count);

        if ($count > 0) {
            $response->headers->set('Refresh', '1');
        }

        return $response;
    }

    private function _jsonOrder(\Order $order)
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
