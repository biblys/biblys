<?php

namespace AppBundle\Controller;

use Biblys\Utils\Log;
use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PaymentController extends Controller
{
    /**
     * GET /payment/stripe-webhook
     *
     * Confirm that a payment has succeeded using order url
     * and redirect
     */
    public function stripeWebhookAction(Request $request)
    {
        global $config;

        Log::stripe("INFO", 'Receiving new webhook from Stripe…');

        try {

            $stripe = $config->get('stripe');
            if (!$stripe) {
                throw new \Exception("Stripe is not configured.");
            }

            if (!isset($stripe["public_key"]) || empty($stripe["public_key"])) {
                throw new \Exception("Missing Stripe public key.");
            }

            if (!isset($stripe["secret_key"]) || empty($stripe["secret_key"])) {
                throw new \Exception("Missing Stripe secret key.");
            }

            if (!isset($stripe["endpoint_secret"]) || empty($stripe["endpoint_secret"])) {
                throw new \Exception("Missing Stripe endpoint secret.");
            }

            \Stripe\Stripe::setApiKey($stripe['secret_key']);

            $payload = @file_get_contents('php://input');

            $sigHeader = $request->headers->get('stripe-signature');
            if (!$sigHeader) {
                throw new BadRequestHttpException('stripe-signature header is missing');
            }

            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $stripe['endpoint_secret']
            );

            if ($event->type !== 'checkout.session.completed') {
                Log::stripe("INFO", 'Webhook is not of type checkout.session.completed, ignoring.');
                return;
            }

            // Handle the checkout.session.completed event
            if ($event->type == 'checkout.session.completed') {
                $session = $event->data->object;
                Log::stripe("INFO", 'Handling Checkout session…', ["id" => $session->id]);

                // Retrieve payment associated with session id
                $pm = new \PaymentManager();
                $payment = $pm->get(["payment_provider_id" => $session->id]);
                if (!$payment) {
                    throw new \Exception("Could not find a payment associated with this session id");
                }
                Log::stripe("INFO", 'Associated Payment with session id', ["id" => $payment->get('id')]);

                // Retrieve order associated with payment
                $om = new \OrderManager();
                $order = $om->getById($payment->get('order_id'));
                if (!$order) {
                    throw new \Exception("Could not find an order associated with this id");
                }
                Log::stripe("INFO", 'Associated Order with Payment', ["id" => $order->get('id')]);

                // Add payment to the order
                $om->addPayment($order, $payment);
                Log::stripe("INFO", 'Payment amount (' . $payment->get('amount') . ') was added to order ' . $order->get('id'));
            }

        } catch(\Exception $e) {
            Log::stripe("ERROR", $e->getMessage());
            throw $e;
        }

        return new JsonResponse([]);
    }
}
