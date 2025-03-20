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

use Biblys\Exception\CannotFindPayableOrderException;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\LoggerService;
use Biblys\Service\Pagination;
use Biblys\Service\PaymentService;
use Biblys\Service\TemplateService;
use DateTime;
use Exception;
use Framework\Controller;
use InvalidArgumentException;
use Model\Payment;
use Model\PaymentQuery;
use Order;
use OrderManager;
use PaymentManager;
use Payplug\Exception\BadRequestException;
use Payplug\Exception\ConfigurationNotSetException;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PaymentController extends Controller
{
    /**
     * @route GET /admin/payments
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function index(
        Request         $request,
        CurrentSite     $currentSite,
        CurrentUser     $currentUser,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $paymentQuery = PaymentQuery::create()
            ->filterBySiteId($currentSite->getSite()->getId())
            ->filterByExecuted(null, Criteria::ISNOTNULL)
            ->orderByCreatedAt(Criteria::DESC);

        $modeFilter = $request->query->get("mode");
        if ($modeFilter) {
            $paymentQuery->filterByMode($modeFilter);
        }

        $startDateInput = $request->query->get("start_date");
        $startDate = $startDateInput ? new DateTime($startDateInput . " 00:00:00") : new DateTime("1 month ago");
        $paymentQuery->filterByExecuted($startDate, Criteria::GREATER_EQUAL);

        $endDateInput = $request->query->get("end_date");
        $endDate = $endDateInput ? new DateTime($endDateInput . " 23:59:59") : new DateTime("today 23:59:59");
        $paymentQuery->filterByExecuted($endDate, Criteria::LESS_EQUAL);

        try {
            $pageNumber = (int)$request->query->get("p", 0);
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

        return $templateService->renderResponse(
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
     * @throws TransportExceptionInterface
     */
    public function stripeWebhookAction(Request $request, Config $config): JsonResponse
    {
        $loggerService = new LoggerService();

        $loggerService->log("stripe", "INFO", 'Receiving new webhook from Stripe…');

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
                $loggerService->log("stripe", "INFO", 'Webhook is not of type checkout.session.completed, ignoring.');
                return new JsonResponse();
            }

            // Handle the checkout.session.completed event
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $session = $event->data->object;
            $loggerService->log("stripe", "INFO", 'Handling Checkout session…', ["id" => $session->id]);

            // Retrieve payment associated with session id
            $pm = new PaymentManager();
            $payment = $pm->get(["payment_provider_id" => $session->id]);
            if (!$payment) {
                throw new Exception("Could not find a payment associated with this session id");
            }
            $loggerService->log("stripe", "INFO", 'Associated Payment with session id', ["id" => $payment->get('id')]);

            // Retrieve order associated with payment
            $om = new OrderManager();
            /** @var Order $order */
            $order = $om->getById($payment->get('order_id'));
            if (!$order) {
                throw new Exception("Could not find an order associated with this id");
            }
            $loggerService->log("stripe", "INFO", 'Associated Order with Payment', ["id" => $order->get('id')]);

            // Add payment to the order
            $om->addPayment($order, $payment);
            $loggerService->log("stripe", "INFO", 'Payment amount (' . $payment->get('amount') . ') was added to order ' . $order->get('id'));

        } catch (Exception $e) {
            $loggerService->log("stripe", "ERROR", $e->getMessage());
            throw $e;
        }

        return new JsonResponse([]);
    }

    /**
     * @route GET /order/{slug}/pay
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function selectMethodAction(
        PaymentService  $paymentService,
        Config          $config,
        CurrentSite     $currentSite,
        TemplateService $templateService,
        string          $slug,
    ): Response
    {
        try {
            $order = $paymentService->getPayableOrderBySlug($slug);

            $orderWillBeCollected = $order->getShippingMode() === "magasin";
            $orderWillBeShipped = !$orderWillBeCollected;

            return $templateService->renderResponse('AppBundle:Payment:select-method.html.twig', [
                "order" => $order,
                "stripeIsAvailable" => !!$config->get("stripe"),
                "stripePublicKey" => $config->get("stripe.public_key"),
                "payplugIsAvailable" => !!$config->get("payplug"),
                "paypalIsAvailable" => $config->isPayPalEnabled(),
                "paypalClientId" => $config->get("paypal.client_id"),
                "transferIsAvailable" => !!$currentSite->getOption("payment_iban"),
                "paymentIban" => $currentSite->getOption("payment_iban"),
                "checkIsAvailable" => !!$currentSite->getOption("payment_check"),
                "nameForCheckPayment" => $currentSite->getOption("name_for_check_payment"),
                "orderWillBeShipped" => $orderWillBeShipped,
                "orderWillBeCollected" => $orderWillBeCollected,
            ]);
        } catch (CannotFindPayableOrderException $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        }
    }

    /**
     * @throws ConfigurationNotSetException
     * @throws PropelException
     */
    public function createPayplugPaymentAction(
        PaymentService       $paymentService,
        LoggerService        $loggerService,
        FlashMessagesService $flashMessagesService,
        UrlGenerator         $urlGenerator,
        string               $slug
    ): RedirectResponse
    {
        try {
            $order = $paymentService->getPayableOrderBySlug($slug);
            $orderManager = new OrderManager();
            /** @var Order $orderEntity */
            $orderEntity = $orderManager->getById($order->getId());
            $payment = $orderEntity->createPayplugPayment();
            return new RedirectResponse($payment->get("url"));
        } catch (CannotFindPayableOrderException $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        } catch (BadRequestException $exception) {
            $error = $exception->getErrorObject();
            $loggerService->log("payplug", $error["message"], $error["details"]);
            $flashMessagesService->add("error", "Une erreur est survenue lors de la création du paiement via PayPlug : " . $error["message"]);
            $paymentPageUrl = $urlGenerator->generate("payment", ["slug" => $order->getSlug()]);
            return new RedirectResponse($paymentPageUrl);
        }
    }
}
