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
use CustomerManager;
use Framework\Controller;
use Model\CartQuery;
use Model\UserQuery;
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
        ImagesService        $imagesService,
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
            $cartForCurrentSeller = $cm->get([
                "cart_type" => "shop",
                "cart_seller_id" => $currentUser->getUser()->getId(),
                "cart_count" => 0,
            ]);

            if ($cartForCurrentSeller) {
                return new RedirectResponse("/admin/caisse?cart_id={$cartForCurrentSeller->get('id')}");
            }

            $newCart = $cm->create();
            $newCart->set("cart_type", "shop");
            $newCart->set("cart_seller_id", $currentUser->getUser()->getId());
            $newCart = $cm->update($newCart);
            return new RedirectResponse("/admin/caisse?cart_id={$newCart->get('id')}");
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
        if ($cart->has('customer_id')) {
            $customerManager = new CustomerManager();
            $customer = $customerManager->get(['customer_id' => $cart->get('customer_id')]);
        }

        $cartContent = '';
        $cartCount = 0;
        $cartTotal = 0;
        foreach ($cm->getStock($cart) as $stockEntity) {
            $cartContent .= $cart->getLine($imagesService, $stockEntity);
            $cartCount++;
            $cartTotal += $stockEntity->get('selling_price');
        }
        $cm->updateFromStock($cart);

        $pointOfSaleCarts = [];
        foreach ($cm->getAll() as $pointOfSaleCart) {
            if ($pointOfSaleCart->get('cart_count') > 0 && $pointOfSaleCart->get('cart_type') === 'shop') {
                $cartSeller = null;
                if ($pointOfSaleCart->has('seller_user_id')) {
                    $cartSeller = UserQuery::create()->findPk($pointOfSaleCart->get('seller_user_id'));
                } elseif ($pointOfSaleCart->has('seller_id')) {
                    $cartSeller = UserQuery::create()->findPk($pointOfSaleCart->get('seller_id'));
                }
                $pointOfSaleCarts[] = [
                    'id' => $pointOfSaleCart->get('cart_id'),
                    'title' => $pointOfSaleCart->get('cart_title'),
                    'seller_email' => $cartSeller?->getEmail() ?? "Vendeur inconnu",
                    'customer_name' => $pointOfSaleCart->has('customer')
                        ? $pointOfSaleCart->get('customer')->get('first_name') . ' ' . $pointOfSaleCart->get('customer')->get('last_name')
                        : null,
                    'count' => $pointOfSaleCart->get('cart_count'),
                    'amount' => $pointOfSaleCart->get('cart_amount'),
                ];
            }
        }

        return $templateService->renderResponse("AppBundle:PointOfSale:index.html.twig", [
            'is_tva_enabled' => $isTvaEnabled,
            'cart' => $cart,
            'cart_content' => $cartContent,
            'cart_count' => $cartCount,
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
