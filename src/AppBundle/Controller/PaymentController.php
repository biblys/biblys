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
use Biblys\Exception\InvalidConfigurationException;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Exception\UnreachableExternalServiceException;
use Biblys\Service\BodyParamsService;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\InvalidSiteIdException;
use Biblys\Service\LoggerService;
use Biblys\Service\Mailer;
use Biblys\Service\Pagination;
use Biblys\Service\PaymentService;
use Biblys\Service\TemplateService;
use DateTime;
use Exception;
use Framework\Controller;
use InvalidArgumentException;
use Model\OrderQuery;
use Model\Payment;
use Model\PaymentQuery;
use Payplug\Exception\BadRequestException;
use Payplug\Exception\ConfigurationException;
use Payplug\Exception\ConfigurationNotSetException;
use Payplug\Exception\HttpException;
use Payplug\Exception\PayplugException;
use Payplug\Exception\UnknownAPIResourceException;
use Payplug\Notification;
use Payplug\Payplug;
use Payplug\Resource\Refund;
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
use Repository\PaymentRepository;
use Usecase\AddArticleToUserLibraryUsecase;
use Usecase\AddPaymentToOrderAndExecuteUsecase;
use Usecase\BusinessRuleException;
use Usecase\MarkOrderAsPaidUsecase;
use Usecase\MarkOrderAsUnpaidUsecase;
use Usecase\RefundPaymentUsecase;

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
        CurrentUser     $currentUser,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $paymentQuery = PaymentQuery::create()
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
        ], isPrivate: true);
    }

    /**
     * POST /admin/payments/{id}/refund
     *
     * @throws PropelException
     */
    public function refundAction(
        int                  $id,
        CurrentUser          $currentUser,
        FlashMessagesService $flashMessages,
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $paymentRepository = new PaymentRepository();
        $payment = $paymentRepository->findById($id);
        if ($payment === null) {
            throw new NotFoundHttpException("Paiement #$id introuvable.");
        }

        try {
            $usecase = new RefundPaymentUsecase($paymentRepository, new MarkOrderAsUnpaidUsecase($paymentRepository));
            $usecase->execute($payment);
            $flashMessages->add("success", "Le paiement a été remboursé.");
        } catch (BusinessRuleException $e) {
            $flashMessages->add("danger", $e->getMessage());
        }

        return new RedirectResponse("/admin/payments/");
    }

    /**
     * POST /admin/payments
     *
     * @throws BusinessRuleException
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws UnreachableExternalServiceException
     * @throws InvalidSiteIdException
     */
    public function createAction(
        BodyParamsService    $bodyParams,
        CurrentUser          $currentUser,
        Mailer               $mailer,
        CurrentSite          $currentSite,
        UrlGenerator         $urlGenerator,
        TemplateService      $templateService,
        FlashMessagesService $flashMessages,
    ): Response
    {
        $currentUser->authAdmin();

        $bodyParams->parse([
            "order_id" => ["type" => "numeric"],
            "payment_mode" => ["type" => "string"],
            "payment_amount" => ["type" => "numeric"],
        ]);

        $orderId = $bodyParams->getInteger("order_id");
        $order = OrderQuery::create()->findPk($orderId);
        $paymentAmount = $bodyParams->getInteger("payment_amount");

        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setMode($bodyParams->get("payment_mode"));
        $payment->setAmount($paymentAmount);

        $usecase = $this->getPaymentUsecase(
            mailer: $mailer,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
            templateService: $templateService,
        );
        $usecase->execute($order, $payment);

        $formattedAmount = currency($paymentAmount, true);
        $flashMessages->add(
            "success",
            "Un paiement de $formattedAmount a été ajouté à la commande {$order->getId()}."
        );

        return new RedirectResponse("/pages/adm_order?order_id=$orderId");
    }

    /**
     * GET /payment/stripe-webhook
     *
     * Confirm that a payment has succeeded using order url
     * and redirect
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SignatureVerificationException
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws InvalidEmailAddressException
     * @throws UnreachableExternalServiceException
     * @throws BusinessRuleException
     */
    public function stripeWebhookAction(
        Request $request,
        Config $config,
        Mailer $mailer,
        CurrentSite $currentSite,
        UrlGenerator $urlGenerator,
        TemplateService $templateService,
    ): JsonResponse
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

            $payload = $request->getContent();

            $sigHeader = $request->headers->get('stripe-signature');
            if (!$sigHeader) {
                throw new BadRequestHttpException('stripe-signature header is missing');
            }

            $event = Webhook::constructEvent($payload, $sigHeader, $stripe['endpoint_secret']);

            if ($event->type !== "payment_intent.succeeded") {
                $loggerService->log("stripe", "INFO", "Webhook is not of type payment_intent.succeeded, ignoring.");
                return new JsonResponse();
            }

            // Handle the payment_intent.succeeded event
            $session = $event->data->object;
            $loggerService->log("stripe", "INFO", 'Handling payment intent…', ["id" => $session->id]);

            // Retrieve payment associated with session id
            $payment = PaymentQuery::create()->findOneByProviderId($session->id);
            if (!$payment) {
                throw new Exception("Could not find a payment associated with this session id");
            }
            $loggerService->log("stripe", "INFO", 'Associated Payment with session id', ["id" => $payment->getId()]);

            // Retrieve order associated with payment
            $order = $payment->getOrder();
            if (!$order) {
                throw new Exception("Could not find an order associated with this id");
            }
            $loggerService->log("stripe", "INFO", 'Associated Order with Payment', ["id" => $order->getId()]);

            $usecase = $this->getPaymentUsecase($mailer, $currentSite, $urlGenerator, $templateService);
            $usecase->execute($order, $payment);

            $loggerService->log("stripe", "INFO", 'Payment amount (' . $payment->getAmount() . ') was added to order ' . $order->getId());

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

            $stripeIsAvailable = !!$config->get("stripe");
            $payPlugIsAvailable = !!$config->get("payplug");
            $payplugApplePayIsAvailable = !!$config->get("payplug.apple_pay");
            $paypalIsAvailable = $config->isPayPalEnabled();
            $paypalOnlyIsAvailable = $paypalIsAvailable && !$payPlugIsAvailable && !$stripeIsAvailable;

            return $templateService->renderResponse('AppBundle:Payment:select-method.html.twig', [
                "order" => $order,
                "stripeIsAvailable" => $stripeIsAvailable,
                "stripePublicKey" => $config->get("stripe.public_key"),
                "payplugIsAvailable" => $payPlugIsAvailable,
                "payplugApplePayIsAvailable" => $payplugApplePayIsAvailable,
                "paypalIsAvailable" => $paypalIsAvailable,
                "paypalOnlyIsAvailable" => $paypalOnlyIsAvailable,
                "paypalClientId" => $config->get("paypal.client_id"),
                "transferIsAvailable" => !!$currentSite->getOption("payment_iban"),
                "paymentIban" => $currentSite->getOption("payment_iban"),
                "checkIsAvailable" => !!$currentSite->getOption("payment_check"),
                "nameForCheckPayment" => $currentSite->getOption("name_for_check_payment"),
                "orderWillBeShipped" => $orderWillBeShipped,
                "orderWillBeCollected" => $orderWillBeCollected,
            ], isPrivate: true);
        } catch (CannotFindPayableOrderException $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        }
    }

    /**
     * @throws ConfigurationNotSetException
     * @throws PropelException
     * @throws InvalidConfigurationException
     * @throws ConfigurationException
     * @throws HttpException
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

            try {
                $payment = $paymentService->createPayplugPaymentForOrder($order);
                return new RedirectResponse($payment->getURL());
            } catch (BadRequestException $exception) {
                $order = $paymentService->getPayableOrderBySlug($slug);
                $error = $exception->getErrorObject();
                $loggerService->log("payplug", "CRITICAL", $error["message"], $error["details"]);
                $flashMessagesService->add("error", "Une erreur est survenue lors de la création du paiement via PayPlug : " . $error["message"]);
                $paymentPageUrl = $urlGenerator->generate("payment_pay", ["slug" => $order->getSlug()]);
                return new RedirectResponse($paymentPageUrl);
            }

        } catch (CannotFindPayableOrderException $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        }
    }

    /**
     * @throws ConfigurationException
     * @throws PayplugException
     * @throws TransportExceptionInterface
     * @throws UnknownAPIResourceException
     * @throws Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function payplugNotificationAction(
        Request $request,
        LoggerService $loggerService,
        Config $config,
        Mailer $mailer,
        CurrentSite $currentSite,
        UrlGenerator $urlGenerator,
        TemplateService $templateService,
        $url
    ): Response
    {
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

        // Check if order exists
        $order = OrderQuery::create()->findOneBySlug($url);
        if (!$order) {
            $loggerService->log("payplug", "ERROR", "Order $url not found.");
            throw new Exception("Order $url not found.");
        }
        $loggerService->log("payplug", "INFO", 'Receiving Payplug notification for order ' . $order->getId() . ' from ' . $request->headers->get('referer'));

        // Process notification
        $input = $request->getContent();
        try {
            $resource = Notification::treat($input);

            if ($resource instanceof Refund) {
                $loggerService->log("payplug", "INFO", 'Ignoring resource ' . $resource->id . ' (refund)');
                return new Response();
            }

            if (!$resource instanceof \Payplug\Resource\Payment) {
                $loggerService->log("payplug", "ERROR", 'Resource ' . $resource->id . '  is not a Payment.');
                throw new Exception('Resource '.$resource->id.'  is not a Payment.');
            }

            // Payment failed, log error and ignore process
            if (!$resource->is_paid) {
                $loggerService->log("payplug", "ERROR", 'Payment ' . $resource->id . '  is not paid.');
                return new Response('');
            }

            // Check if payment exists
            $payment = PaymentQuery::create()->findOneByProviderId($resource->id);
            if (!$payment) {
                $loggerService->log("payplug", "ERROR", 'Payment ' . $resource->id . ' not found.');
                throw new Exception('Payment '.$resource->id.' not found.');
            }
            $loggerService->log("payplug", "INFO", 'Found payment ' . $payment->getId() . ' in database.');

            // Get order id from metadata and compare to database order id
            if ($resource->metadata['order_id'] != $order->getId()) {
                $loggerService->log("payplug", "ERROR", 'Order id from Payplug (' . $resource->metadata['order_id'] . ') does not match order ID (' . $order->getId() . ').');
                throw new Exception('Invoice number does not match order ID.');
            }
            $loggerService->log("payplug", "INFO", 'Received order id (' . $resource->metadata['order_id'] . ' matches order id in database.');

            $usecase = $this->getPaymentUsecase($mailer, $currentSite, $urlGenerator, $templateService);
            $usecase->execute($order, $payment);

            // Add payment to the order
            $loggerService->log("payplug", "INFO", 'Payment amount (' . $payment->getAmount() . ') was added to order ' . $order->getId());

            return new Response('');
        } catch (UnknownAPIResourceException $exception) {
            $loggerService->log("payplug", "ERROR", 'UnknownAPIResourceException: ' . $exception->getMessage());
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }
    }

    /**
     * @param Mailer $mailer
     * @param CurrentSite $currentSite
     * @param UrlGenerator $urlGenerator
     * @param TemplateService $templateService
     * @return AddPaymentToOrderAndExecuteUsecase
     */
    private function getPaymentUsecase(Mailer $mailer, CurrentSite $currentSite, UrlGenerator $urlGenerator, TemplateService $templateService): AddPaymentToOrderAndExecuteUsecase
    {
        $addArticleToLibraryUsecase = new AddArticleToUserLibraryUsecase(
            mailer: $mailer,
            currentSite: $currentSite,
            urlGenerator: $urlGenerator,
        );
        $markOrderAsPaidUsecase = new MarkOrderAsPaidUsecase(
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            mailer: $mailer,
            addArticleToUserLibraryUsecase: $addArticleToLibraryUsecase,
        );
        return new AddPaymentToOrderAndExecuteUsecase(
            markOrderAsPaidUsecase: $markOrderAsPaidUsecase,
        );
    }
}
