<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\Log;
use Biblys\Service\Pagination;
use DateTime;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use InvalidArgumentException;
use Model\Payment;
use Model\PaymentQuery;
use OrderManager;
use PaymentManager;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PaymentController extends Controller
{
    /**
     * @route GET /admin/payments
     * @throws AuthException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function index(Request $request, CurrentSite $currentSite): Response
    {
        self::authAdmin($request);
        $request->attributes->set("page_title", "Paiements");

        $paymentQuery = PaymentQuery::create()
            ->filterBySiteId($currentSite->getSite()->getId())
            ->filterByExecuted(null, Criteria::ISNOTNULL)
            ->orderByCreatedAt(Criteria::DESC);

        $modeFilter = $request->query->get("mode");
        if ($modeFilter) {
            $paymentQuery->filterByMode($modeFilter);
        }

        $startDateInput = $request->query->get("start_date");
        $startDate = $startDateInput ? new DateTime($startDateInput." 00:00:00") : new DateTime("1 month ago");
        $paymentQuery->filterByExecuted($startDate, Criteria::GREATER_EQUAL);

        $endDateInput = $request->query->get("end_date");
        $endDate = $endDateInput ? new DateTime($endDateInput." 23:59:59") : new DateTime("today 23:59:59");
        $paymentQuery->filterByExecuted($endDate, Criteria::LESS_EQUAL);

        try {
            $pageNumber = (int) $request->query->get("p", 0);
            $paymentsTotalCount = $paymentQuery->count();
            $paymentsPerPage = 1000;
            $pagination = new Pagination($pageNumber, $paymentsTotalCount, $paymentsPerPage);
            $pagination->setQueryParams(["mode" => $modeFilter, "start_date" => $startDateInput, "end_date" => $endDateInput]);
        } catch (InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        $payments = $paymentQuery
            ->limit($pagination->getLimit())
            ->offset($pagination->getOffset())
            ->find();

        $total = array_reduce($payments->getData(), function ($total, $payment) {
            return $total + $payment->getAmount();
        }, 0);

        return $this->render(
            "AppBundle:Payment:index.html.twig", [
                "modes" => Payment::getModes(),
                "selectedMode" => $modeFilter,
                "startDate" => $startDate->format("Y-m-d"),
                "endDate" => $endDate->format("Y-m-d"),
                "payments" => $payments,
                "pages" => $pagination,
                "total" => $total,
            ]);
    }

    /**
     * GET /payment/stripe-webhook
     *
     * Confirm that a payment has succeeded using order url
     * and redirect
     * @throws SignatureVerificationException
     */
    public function stripeWebhookAction(Request $request): JsonResponse
    {
        global $config;

        Log::stripe("INFO", 'Receiving new webhook from Stripe…');

        try {

            $stripe = $config->get('stripe');
            if (!$stripe) {
                throw new Exception("Stripe is not configured.");
            }

            if (empty($stripe["public_key"])) {
                throw new Exception("Missing Stripe public key.");
            }

            if (empty($stripe["secret_key"])) {
                throw new Exception("Missing Stripe secret key.");
            }

            if (empty($stripe["endpoint_secret"])) {
                throw new Exception("Missing Stripe endpoint secret.");
            }

            Stripe::setApiKey($stripe['secret_key']);

            $payload = @file_get_contents('php://input');

            $sigHeader = $request->headers->get('stripe-signature');
            if (!$sigHeader) {
                throw new BadRequestHttpException('stripe-signature header is missing');
            }

            $event = Webhook::constructEvent(
                $payload, $sigHeader, $stripe['endpoint_secret']
            );

            if ($event->type !== 'checkout.session.completed') {
                Log::stripe("INFO", 'Webhook is not of type checkout.session.completed, ignoring.');
                return new JsonResponse();
            }

            // Handle the checkout.session.completed event
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $session = $event->data->object;
            Log::stripe("INFO", 'Handling Checkout session…', ["id" => $session->id]);

            // Retrieve payment associated with session id
            $pm = new PaymentManager();
            $payment = $pm->get(["payment_provider_id" => $session->id]);
            if (!$payment) {
                throw new Exception("Could not find a payment associated with this session id");
            }
            Log::stripe("INFO", 'Associated Payment with session id', ["id" => $payment->get('id')]);

            // Retrieve order associated with payment
            $om = new OrderManager();
            $order = $om->getById($payment->get('order_id'));
            if (!$order) {
                throw new Exception("Could not find an order associated with this id");
            }
            Log::stripe("INFO", 'Associated Order with Payment', ["id" => $order->get('id')]);

            // Add payment to the order
            $om->addPayment($order, $payment);
            Log::stripe("INFO", 'Payment amount (' . $payment->get('amount') . ') was added to order ' . $order->get('id'));

        } catch(Exception $e) {
            Log::stripe("ERROR", $e->getMessage());
            throw $e;
        }

        return new JsonResponse([]);
    }
}
