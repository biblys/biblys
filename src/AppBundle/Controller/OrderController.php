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
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\InvalidSiteIdException;
use Biblys\Service\Mailer;
use Biblys\Service\ShippingVatAllocationService;
use Biblys\Service\TemplateService;
use Exception;
use Framework\Controller;
use Model\OrderQuery;
use Model\Payment;
use Model\StockQuery;
use Order;
use OrderManager;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Repository\PaymentRepository;
use Usecase\AddArticleToUserLibraryUsecase;
use Usecase\AddPaymentToOrderAndExecuteUsecase;
use Usecase\BusinessRuleException;
use Usecase\CancelOrderUsecase;
use Usecase\MarkOrderAsPaidUsecase;
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
        Request         $request,
        CurrentUser     $currentUser,
        Config          $config,
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

            $payment = $request->query->get('payment', false);
            if ($payment) {
                $where['order_payment_mode'] = $payment;
            }

            $shipping = $request->query->get('shipping', false);
            if ($shipping) {
                $where['order_shipping_mode'] = $shipping;
            }

            $offset = $request->query->get('offset', 0);
            $options = [
                'limit' => 100,
                'offset' => $offset,
                'order' => 'order_created',
                'sort' => 'desc',
            ];

            $query = $request->query->get('query', false);
            if ($query) {
                $orders = $om->search($query, $where, $options);
                $total = 0;
            } else {
                $total = $om->count($where);
                $orders = $om->getAll($where, $options, false);
            }

            $orders = array_map([$this, '_jsonOrderFromEntity'], $orders);

            $response = [
                'results' => count($orders),
                'total' => $total,
                'orders' => $orders,
            ];

            return new JsonResponse($response);
        }

        $request->attributes->set("page_title", "Commandes web");

        return $templateService->renderResponse("AppBundle:Order:index.html.twig", [
            "display_mondial_relay_export_button" => $config->isMondialRelayEnabled(),
        ], isPrivate: true);
    }

    /**
     * @throws PropelException
     */
    public function show(
        CurrentUser $currentUser,
        int         $id,
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $order = OrderQuery::create()
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
        CurrentUser     $currentUser,
        TemplateService $templateService,
        Mailer          $mailer,
        UrlGenerator    $urlGenerator,
                        $id,
                        $action
    ): JsonResponse
    {
        $currentUser->authAdmin();

        $notice = "";

        /** @var Order $orderEntity */
        $om = new OrderManager();
        $orderEntity = $om->getById($id);

        /** @var \Model\Base\Order $order */
        $order = OrderQuery::create()->findPk($id);

        $requestBody = json_decode($request->getContent());

        if ($action == "payed") {
            $amount = $orderEntity->get('amount_tobepaid');

            $payment = new Payment();
            $payment->setOrder($order);
            $payment->setMode($requestBody->payment_mode);
            $payment->setAmount($amount);
            $payment->save();

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
            $usecase = new AddPaymentToOrderAndExecuteUsecase(
                markOrderAsPaidUsecase: $markOrderAsPaidUsecase,
            );
            $usecase->execute($order, $payment);
            $notice = 'La commande n°&nbsp;'.$orderEntity->get('id').' de '.$orderEntity->get('firstname').' '.$orderEntity->get('lastname').' a été marquée comme payée.';
        }

        if ($action === "shipped") {
            $trackingNumber = $requestBody->tracking_number;
            if ($trackingNumber !== null && strlen($trackingNumber) > 16) {
                throw new BadRequestHttpException("Le numéro de suivi ne peut pas dépasser 16 caractères.");
            }

            $markOrderAsPaidUsecase = new MarkOrderAsShippedUsecase($currentSite, $templateService, $mailer);
            $markOrderAsPaidUsecase->execute($order, $trackingNumber);

            $notice = "La commande n°&nbsp;{$order->getId()} de {$order->getFirstname()} {$order->getLastname()} a été marquée comme expédiée.";
        }

        if ($action == 'followup') {
            $om->followUp($orderEntity);
            $notice = 'Le client '.$orderEntity->get('firstname').' '.$orderEntity->get('lastname').' a été relancée pour la commande n°&nbsp;'.$orderEntity->get('id').'.';
        } elseif ($action == 'cancel') {
            try {
                $cancelOrderUsecase = new CancelOrderUsecase(new PaymentRepository());
                $cancelOrderUsecase->execute($id);
            } catch (BusinessRuleException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
            $notice = 'La commande n°&nbsp;'.$orderEntity->get('id').' de '.$orderEntity->get('firstname').' '.$orderEntity->get('lastname').' a été annulée.';
        }

        /** @var Order $orderEntity */
        $updatedOrder = OrderQuery::create()->findPk($id);
        $updatedOrder->reload();
        return new JsonResponse([
            "notice" => $notice,
            "order" => $this->_jsonOrder($updatedOrder),
        ]);
    }

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function invoiceAction(
        Request         $request,
        CurrentUser     $currentUser,
        CurrentSite     $currentSite,
        TemplateService $templateService,
        string          $slug,
    ): Response
    {
        $order = OrderQuery::create()->filterBySlug($slug)->findOne();
        if (!$order) {
            throw new ResourceNotFoundException();
        }

        if (
            !$this->_isAnonymousOrder($order)
            && !$this->_orderBelongsToVisitor($order, $currentUser)
            && !$currentUser->isAdmin()
        ) {
            throw new AccessDeniedHttpException();
        }

        $pageTitle = "Facture n° {$order->getId()}";
        $request->attributes->set("page_title", $pageTitle);

        $stocks = StockQuery::create()->filterByOrderId($order->getId())->find();

        $lines = [];
        $totalVat = 0;
        $totalWeight = 0;
        $vatBreakdown = [];
        foreach ($stocks as $stock) {
            $article = $stock->getArticle();
            $totalWeight += (int) $stock->getWeight();
            $priceHt = (int) $stock->getSellingPriceHt();
            $priceVat = (int) $stock->getSellingPriceTva();
            $vatRate = $stock->getTvaRate();
            $totalVat += $priceVat;

            $breakdownKey = $vatRate !== null ? (string) $vatRate : "unknown";
            if (!isset($vatBreakdown[$breakdownKey])) {
                $vatBreakdown[$breakdownKey] = ["rate" => $vatRate, "ht" => 0, "vat" => 0, "ttc" => 0];
            }
            $vatBreakdown[$breakdownKey]["ht"] += $priceHt;
            $vatBreakdown[$breakdownKey]["vat"] += $priceVat;
            $vatBreakdown[$breakdownKey]["ttc"] += $priceHt + $priceVat;

            $notices = [];
            if ($article->getTypeId() == 2) {
                $notices[] = "numérique";
            }
            if ($article->getPubdate() && $article->getPubdate() > $order->getCreatedAt()) {
                $notices[] = "précommande";
            }

            $lines[] = [
                "title" => $article->getTitle(),
                "notices" => $notices,
                "collectionName" => $article->getCollectionName(),
                "number" => $article->getNumber(),
                "condition" => $stock->getCondition(),
                "price" => (int) $stock->getSellingPrice(),
                "priceHt" => $priceHt,
                "vatRate" => $vatRate,
            ];
        }

        $shippingParts = [];
        if ($order->getShippingCost() > 0) {
            $htByRate = array_map(fn($group) => $group["ht"], $vatBreakdown);
            $shippingParts = ShippingVatAllocationService::allocate($htByRate, (int) $order->getShippingCost());
            $vatBreakdown = ShippingVatAllocationService::mergeIntoBreakdown($vatBreakdown, $shippingParts);
            $totalVat += array_sum(array_column($shippingParts, "vat"));
        }

        $totalHt = array_sum(array_column($vatBreakdown, "ht"));

        uasort($vatBreakdown, function ($a, $b) {
            if ($a["rate"] === null) {
                return 1;
            }
            if ($b["rate"] === null) {
                return -1;
            }
            return $a["rate"] <=> $b["rate"];
        });

        $shippingVat = ShippingVatAllocationService::summarizeForDisplay($shippingParts, $vatBreakdown);

        $hasNonNewCondition = false;
        foreach ($lines as $line) {
            if ($line["condition"] !== "Neuf") {
                $hasNonNewCondition = true;
                break;
            }
        }

        $payment = null;
        if ($order->getPaymentDate()) {
            $payment = [
                "date" => $order->getPaymentDate("d/m/Y"),
                "mode" => ucwords($order->getPaymentMode() ?? ""),
            ];
        }

        return $templateService->renderResponse("AppBundle:Order:invoice.html.twig", [
            "page_title" => $pageTitle,
            "order" => $order,
            "lines" => $lines,
            "article_count" => count($lines),
            "total_weight" => $totalWeight > 0 ? $totalWeight : null,
            "total_ht" => $totalHt,
            "total_vat" => $totalVat,
            "vat_breakdown" => $vatBreakdown,
            "shipping_vat" => $shippingVat,
            "show_condition" => $hasNonNewCondition,
            "has_vat" => (bool) $currentSite->getSite()->getTva(),
            "payment" => $payment,
            "invoice_notice" => $currentSite->getOption("invoice_notice"),
            "site_title" => $currentSite->getTitle(),
            "site_address" => $currentSite->getSite()->getAddress(),
        ], isPrivate: true);
    }

    private function _isAnonymousOrder(\Model\Order $order): bool
    {
        return !$order->getUserId();
    }

    private function _orderBelongsToVisitor(\Model\Order $order, CurrentUser $currentUser): bool
    {
        if (!$currentUser->isAuthenticated()) {
            return false;
        }

        return $order->getUserId() === $currentUser->getUser()->getId();
    }

    /**
     * @throws InvalidSiteIdException
     * @throws PropelException
     */
    private function _jsonOrderFromEntity(Order $order): array
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

    /**
     * @throws InvalidSiteIdException
     * @throws PropelException
     */
    private function _jsonOrder(\Model\Order $order): array
    {
        return [
            'id' => $order->getId(),
            'url' => $order->getSlug(),
            'customer' => $order->getFirstname().' '.$order->getLastname(),
            'amount' => currency($order->getTotalAmount() / 100),
            'total' => currency(($order->getTotalAmountWithShipping()) / 100),
            'created' => $order->getCreatedAt()?->format("Y-m-d H:i:s"),
            'payment_mode' => $order->getPaymentMode(),
            'payment_date' => $order->getPaymentDate()?->format("Y-m-d H:i:s"),
            'shipping_mode' => $order->getShippingMode(),
            'shipping_date' => $order->getShippingDate()?->format("Y-m-d H:i:s"),
            'shipping_amount' => currency($order->getShippingCost() / 100),
            'followup_date' => $order->getFollowupDate()?->format("Y-m-d H:i:s"),
            'cancel_date' => $order->getCancelDate()?->format("Y-m-d H:i:s"),
        ];
    }
}
