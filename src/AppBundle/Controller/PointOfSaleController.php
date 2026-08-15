<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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

use Biblys\Exception\CannotAddStockItemToCartException;
use Biblys\Exception\CannotFindCustomerException;
use Biblys\Exception\CannotRemoveStockItemFromCartException;
use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use CartManager;
use Framework\Controller;
use Model\Cart;
use Model\CartQuery;
use Model\CustomerQuery;
use Model\StockQuery;
use Model\UserQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Usecase\AddStockItemToPointOfSaleCartUsecase;
use Usecase\ClearPointOfSaleCartUsecase;
use Usecase\CreatePointOfSaleOrderUsecase;
use Usecase\RemoveStockItemFromPointOfSaleCartUsecase;
use Usecase\UpdatePointOfSaleCartUsecase;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PointOfSaleController extends Controller
{
    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(
        Request              $request,
        CurrentUser          $currentUser,
        CurrentSite          $currentSite,
        TemplateService      $templateService,
        UrlGenerator         $urlGenerator,
        QueryParamsService   $queryParams,
    ): Response|RedirectResponse
    {
        $currentUser->authAdmin();

        $queryParams->parse([
            "cart_id" => ["type" => "numeric", "default" => null],
        ]);

        $isTvaEnabled = $currentSite->getSite()->getTva() === "fr";

        $cm = new CartManager();

        $cartId = $queryParams->getInteger("cart_id");
        if (!$cartId) {
            $cartForCurrentSeller = CartQuery::create()
                ->filterByType('shop')
                ->filterBySellerId($currentUser->getUser()->getId())
                ->filterByCount(0)
                ->findOne();

            if ($cartForCurrentSeller) {
                return new RedirectResponse("/admin/caisse?cart_id={$cartForCurrentSeller->getId()}");
            }

            $newCart = new Cart();
            $newCart->setType('shop');
            $newCart->setSellerId($currentUser->getUser()->getId());
            $newCart->save();
            return new RedirectResponse("/admin/caisse?cart_id={$newCart->getId()}");
        }

        $cart = $cm->get(['cart_id' => $cartId]);
        if (!$cart) {
            throw new NotFoundHttpException("Panier $cartId introuvable");
        }

        $seller = null;
        if ($cart->has('seller_id')) {
            $seller = UserQuery::create()->findPk($cart->get('seller_id'));
        }

        $customer = null;
        $customerId = $cart->get('customer_id');
        if ($customerId) {
            $customer = CustomerQuery::create()->findPk($customerId);
        }

        $cartStockItems = StockQuery::create()->filterByCartId($cartId)->find();
        $cartStockItemCount = count($cartStockItems);
        $cartTotal = 0;
        foreach ($cartStockItems as $stockItem) {
            $cartTotal += $stockItem->getSellingPrice() ?? 0;
        }
        $cm->updateFromStock($cart);

        $pointOfSaleCarts = [];
        $openShopCarts = CartQuery::create()
            ->filterByType('shop')
            ->filterByCount(0, Criteria::GREATER_THAN)
            ->find();
        foreach ($openShopCarts as $pointOfSaleCart) {
            $cartSeller = null;
            if ($pointOfSaleCart->getSellerUserId()) {
                $cartSeller = UserQuery::create()->findPk($pointOfSaleCart->getSellerUserId());
            } elseif ($pointOfSaleCart->getSellerId()) {
                $cartSeller = UserQuery::create()->findPk($pointOfSaleCart->getSellerId());
            }
            $customerForCart = $pointOfSaleCart->getCustomerId()
                ? CustomerQuery::create()->findPk($pointOfSaleCart->getCustomerId())
                : null;
            $pointOfSaleCarts[] = [
                'id' => $pointOfSaleCart->getId(),
                'title' => $pointOfSaleCart->getTitle(),
                'seller_email' => $cartSeller?->getEmail() ?? "Vendeur inconnu",
                'customer_name' => $customerForCart?->getFullName(),
                'count' => $pointOfSaleCart->getCount(),
                'amount' => $pointOfSaleCart->getAmount(),
            ];
        }

        return $templateService->renderResponse("AppBundle:PointOfSale:index.html.twig", [
            'is_tva_enabled' => $isTvaEnabled,
            'cart' => $cart,
            'cart_stock_items' => $cartStockItems,
            'cart_count' => $cartStockItemCount,
            'cart_total' => $cartTotal,
            'seller' => $seller,
            'customer' => $customer,
            'point_of_sale_carts' => $pointOfSaleCarts,
        ], isPrivate: true);
    }

    /**
     * @throws PropelException
     */
    public function addItemAction(
        Request           $request,
        CurrentUser       $currentUser,
        ImagesService     $imagesService,
        BodyParamsService $bodyParams,
        int               $cartId,
    ): Response
    {
        $currentUser->authAdmin();

        $cm = new CartManager();
        $cart = $cm->getById($cartId);
        if (!$cart) {
            throw new NotFoundHttpException("Panier $cartId introuvable");
        }

        $bodyParams->parse(["stock_id" => ["type" => "numeric", "default" => 0]]);
        $stockItemId = $bodyParams->getInteger("stock_id");

        try {
            $usecase = new AddStockItemToPointOfSaleCartUsecase();
            $usecase->execute($cartId, $stockItemId);
        } catch (CannotAddStockItemToCartException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        if (in_array("application/json", $request->getAcceptableContentTypes())) {
            $line = "";
            foreach ($cm->getStock($cart) as $stockItem) {
                if ($stockItem->get("id") == $stockItemId) {
                    $line = $cart->getLine($imagesService, $stockItem);
                }
            }
            return new JsonResponse([
                "success" => "L'exemplaire n° $stockItemId a été ajouté au panier.",
                "line" => $line,
            ]);
        }

        return new RedirectResponse("/admin/caisse?cart_id=$cartId");
    }

    /**
     * @throws PropelException
     */
    public function removeItemAction(
        Request     $request,
        CurrentUser $currentUser,
        int         $cartId,
        int         $stockId,
    ): Response
    {
        $currentUser->authAdmin();

        $cm = new CartManager();
        $cart = $cm->getById($cartId);
        if (!$cart) {
            throw new NotFoundHttpException("Panier $cartId introuvable");
        }

        try {
            $usecase = new RemoveStockItemFromPointOfSaleCartUsecase();
            $usecase->execute($cartId, $stockId);
        } catch (CannotRemoveStockItemFromCartException $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        }

        if (in_array("application/json", $request->getAcceptableContentTypes())) {
            return new JsonResponse([
                "success" => "L'exemplaire n° $stockId a été retiré du panier et remis en stock.",
            ]);
        }

        return new RedirectResponse("/admin/caisse?cart_id=$cartId");
    }

    /**
     * @throws PropelException
     */
    public function clearCartAction(
        CurrentUser $currentUser,
        int         $cartId,
    ): Response
    {
        $currentUser->authAdmin();

        if (!CartQuery::create()->filterById($cartId)->exists()) {
            throw new NotFoundHttpException("Panier $cartId introuvable");
        }

        $usecase = new ClearPointOfSaleCartUsecase();
        $usecase->execute($cartId);

        return new JsonResponse([
            "success" => "Le panier a été vidé.",
        ]);
    }

    /**
     * @throws PropelException
     */
    public function updateCartAction(
        Request     $request,
        CurrentUser $currentUser,
        int         $cartId,
    ): Response
    {
        $currentUser->authAdmin();

        if (!CartQuery::create()->filterById($cartId)->exists()) {
            throw new NotFoundHttpException("Panier $cartId introuvable");
        }

        $bodyParams = new BodyParamsService($request);
        $bodyParams->parse([
            "title" => ["type" => "string", "default" => null],
            "customer_id" => ["type" => "numeric", "default" => null],
        ]);

        try {
            $usecase = new UpdatePointOfSaleCartUsecase();
            $usecase->execute(
                $cartId,
                title: $bodyParams->get("title"),
                customerId: $bodyParams->getInteger("customer_id"),
            );
        } catch (CannotFindCustomerException $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        }

        return new JsonResponse([
            "success" => "Le panier a été mis à jour.",
        ]);
    }

    /**
     * @throws PropelException
     */
    public function createSaleAction(
        Request               $request,
        CurrentUser           $currentUser,
        FlashMessagesService  $flashMessagesService,
        int                   $cartId,
    ): Response
    {
        $currentUser->authAdmin();

        if (!CartQuery::create()->filterById($cartId)->exists()) {
            throw new NotFoundHttpException("Panier $cartId introuvable");
        }

        $bodyParams = new BodyParamsService($request);
        $bodyParams->parse([
            "seller_id" => ["type" => "numeric", "default" => null],
            "customer_id" => ["type" => "numeric", "default" => null],
            "cart_cash" => ["type" => "numeric", "default" => 0],
            "cart_cheque" => ["type" => "numeric", "default" => 0],
            "cart_card" => ["type" => "numeric", "default" => 0],
            "cart_topay" => ["type" => "numeric", "default" => 0],
            "cart_togive" => ["type" => "numeric", "default" => 0],
        ]);

        $usecase = new CreatePointOfSaleOrderUsecase();
        $orderId = $usecase->execute(
            $cartId,
            sellerId: $bodyParams->getInteger("seller_id"),
            customerId: $bodyParams->getInteger("customer_id"),
            cashAmount: $bodyParams->getInteger("cart_cash"),
            chequeAmount: $bodyParams->getInteger("cart_cheque"),
            cardAmount: $bodyParams->getInteger("cart_card"),
            amountToBePaid: $bodyParams->getInteger("cart_topay"),
            paymentLeft: $bodyParams->getInteger("cart_togive"),
        );

        $flashMessagesService->add("success", "La vente a été enregistrée.");

        if (in_array("application/json", $request->getAcceptableContentTypes())) {
            return new JsonResponse([
                "success" => "La vente a été enregistrée.",
                "order_id" => $orderId,
            ]);
        }

        return new RedirectResponse("/admin/caisse");
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function legacyRedirectAction(
        Request         $request,
        CurrentUser     $currentUser,
        UrlGenerator    $urlGenerator,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $newUrl = $urlGenerator->generate("point_of_sale_index");
        $cartId = $request->query->get("cart_id");
        if ($cartId) {
            $newUrl .= "?cart_id=$cartId";
        }

        return $templateService->renderResponse("AppBundle:PointOfSale:moved.html.twig", [
            "new_url" => $newUrl,
        ], isPrivate: true);
    }
}
