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

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\TemplateService;
use CartManager;
use CustomerManager;
use DateTime;
use Framework\Controller;
use Model\UserQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;
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
        FlashMessagesService $flashMessagesService,
        TemplateService      $templateService,
        UrlGenerator         $urlGenerator,
    ): Response|RedirectResponse
    {
        $currentUser->authAdmin();

        $request->attributes->set('page_title', 'Caisse');

        $enableTemporaryAccess = $request->query->getInt("enable_temporary_access");
        if ($enableTemporaryAccess === 1) {
            $redirectResponse = new RedirectResponse("/admin/point-of-sale");
            $bypassCookie = new Cookie("bypass_cash_register_check", "1", new DateTime("tomorrow"));
            $redirectResponse->headers->setCookie($bypassCookie);
            $flashMessagesService->add("info", "La caisse a été réactivée jusqu'à demain.");
            return $redirectResponse;
        }

        $isTvaEnabled = $currentSite->getSite()->getTva() === "fr";
        $bypassCookie = $request->cookies->get("bypass_cash_register_check");
        if ($isTvaEnabled && !$bypassCookie) {
            return $templateService->renderResponse("AppBundle:PointOfSale:index.html.twig", [
                'tva_blocked' => true,
            ], isPrivate: true);
        }

        $cm = new CartManager();

        $cartId = $request->query->get("cart_id");
        if (!$cartId) {
            $cartForCurrentSeller = $cm->get([
                "cart_type" => "shop",
                "cart_seller_id" => $currentUser->getUser()->getId(),
                "cart_count" => 0,
            ]);

            if ($cartForCurrentSeller) {
                return new RedirectResponse("/admin/point-of-sale?cart_id={$cartForCurrentSeller->get('id')}");
            }

            $newCart = $cm->create();
            $newCart->set("cart_type", "shop");
            $newCart->set("cart_seller_id", $currentUser->getUser()->getId());
            $newCart = $cm->update($newCart);
            return new RedirectResponse("/admin/point-of-sale?cart_id={$newCart->get('id')}");
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

        $shopCarts = [];
        foreach ($cm->getAll() as $shopCart) {
            if ($shopCart->get('cart_count') > 0 && $shopCart->get('cart_type') === 'shop') {
                $cartSeller = null;
                if ($shopCart->has('seller_user_id')) {
                    $cartSeller = UserQuery::create()->findPk($shopCart->get('seller_user_id'));
                } elseif ($shopCart->has('seller_id')) {
                    $cartSeller = UserQuery::create()->findPk($shopCart->get('seller_id'));
                }
                $shopCarts[] = [
                    'id' => $shopCart->get('cart_id'),
                    'title' => $shopCart->get('cart_title'),
                    'seller_email' => $cartSeller?->getEmail() ?? "Vendeur inconnu",
                    'customer_name' => $shopCart->has('customer')
                        ? $shopCart->get('customer')->get('first_name') . ' ' . $shopCart->get('customer')->get('last_name')
                        : null,
                    'count' => $shopCart->get('cart_count'),
                    'amount' => $shopCart->get('cart_amount'),
                ];
            }
        }

        return $templateService->renderResponse("AppBundle:PointOfSale:index.html.twig", [
            'tva_blocked' => false,
            'is_tva_enabled' => $isTvaEnabled,
            'cart' => $cart,
            'cart_content' => $cartContent,
            'cart_count' => $cartCount,
            'cart_total' => $cartTotal,
            'seller' => $seller,
            'customer' => $customer,
            'shop_carts' => $shopCarts,
        ], isPrivate: true);
    }
}
