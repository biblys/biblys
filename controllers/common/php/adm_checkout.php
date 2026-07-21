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


use Biblys\Exception\CannotAddStockItemToCartException;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Images\ImagesService;
use Model\Payment;
use Usecase\RecordPointOfSalePaymentsUsecase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/** @var Request $request */

/**
 * @throws PropelException
 */
return function (
    Request              $request,
    ImagesService        $imagesService,
    FlashMessagesService $flashMessagesService,
): JsonResponse|RedirectResponse {
    $_SQL = LegacyCodeHelper::getGlobalDatabaseConnection();

    $cm = new CartManager();
    $cartId = $request->query->get("cart_id");

    if (!$cartId) {
        return new RedirectResponse("/admin/caisse");
    }

    /** @var Cart $cart */
    $cart = $cm->get(['cart_id' => $cartId]);
    if (!$cart) {
        throw new NotFoundHttpException("Panier $cartId introuvable");
    }

    // Enregistrer la vente
    if (isset($_POST['validate'])) {
        try {
            $_SQL->beginTransaction();

            $_O = new OrderManager();
            $order = $_O->create();

            if (!empty($_POST['seller_id'])) $order->set('seller_id', $_POST['seller_id']);
            if (!empty($_POST['customer_id'])) $order->set('customer_id', $_POST['customer_id']);

            $order->set('order_type', 'shop');
            $order->set('order_payment_cash', $_POST['cart_cash']);
            $order->set('order_payment_cheque', $_POST['cart_cheque']);
            $order->set('order_payment_card', $_POST['cart_card']);
            $order->set('order_amount_tobepaid', $_POST['cart_topay']);
            $order->set('order_payment_left', $_POST['cart_togive']);
            $order->set('order_payment_date', date('Y-m-d H:i:s'));

            $_O->hydrateFromCart($order, $cart);
            $order = $_O->update($order);

            $shopPaymentsUsecase = new RecordPointOfSalePaymentsUsecase();
            $shopPaymentsUsecase->execute((int) $order->get('order_id'), [
                Payment::MODE_CASH  => (int) $_POST['cart_cash'],
                Payment::MODE_CHECK => (int) $_POST['cart_cheque'],
                Payment::MODE_CARD  => (int) $_POST['cart_card'],
            ]);

            $cm->vacuum($cart);
            $cm->delete($cart);

            $_SQL->commit();
        } catch (Exception $e) {
            $_SQL->rollBack();
            throw $e;
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['created_order' => $order->get('order_id')]);
        }

        return new RedirectResponse("/admin/caisse");
    }

    // Changer le nom du panier
    if (isset($_POST['set_title'])) {
        $params = [];
        $cart->set('cart_title', $_POST['set_title']);
        $cart = $cm->update($cart);
        if ($cart->get('cart_title') == $_POST['set_title']) {
            $params['success'] = "Le nom du panier a bien été modifié.";
        } else {
            $params['error'] = "Le nom du panier n'a pas pu être modifié.";
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($params);
        }
        return new RedirectResponse("/admin/caisse?cart_id=$cartId");
    }

    // Changer le client du panier
    if (isset($_POST['set_customer'])) {
        $params = [];
        try {
            $cart->set('customer_id', $_POST['set_customer']);
            $cm->update($cart);
        } catch (Exception $e) {
            $error = $e;
        }

        if (isset($error)) {
            $params['error'] = ['message' => $error->getMessage()];
        } elseif ($cart->get('customer_id') == $_POST['set_customer']) {
            $params['success'] = "Le client du panier a bien été modifié.";
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($params);
        }
        return new RedirectResponse("/admin/caisse?cart_id=$cartId");
    }

    $copyToRemoveId = (int) $request->query->get('remove_stock', false);
    $add = $request->query->get("add");
    $stockItemToAddId = (int) $request->query->get("id");

    // Ajouter un exemplaire au panier
    if ($add && $stockItemToAddId) {
        if ($add === "stock") {
            try {
                $cm->addStock($cart, $_GET["id"]);
                $params["success"] = "L'exemplaire n° $stockItemToAddId a été ajouté au panier.";
                $cm->updateFromStock($cart);
            } catch (CannotAddStockItemToCartException $exception) {
                throw new BadRequestHttpException($exception->getMessage(), $exception);
            }

            if (in_array("application/json", $request->getAcceptableContentTypes())) {
                $stocks = $cm->getStock($cart);
                foreach ($stocks as $stock) {
                    if ($stock->get('id') == $_GET['id']) {
                        $params['line'] = $cart->getLine($imagesService, $stock);
                    }
                }
                return new JsonResponse($params);
            }

            return new RedirectResponse("/admin/caisse?cart_id=$cartId");
        }
    }

    // Retirer un exemplaire du panier
    if ($copyToRemoveId) {
        $sm = new StockManager();
        /** @var Stock $copyToRemove */
        $copyToRemove = $sm->getById($copyToRemoveId);
        $params = [];
        if ($copyToRemove) {
            if ($cm->removeStock($copyToRemove)) {
                $params['success'] = "L'exemplaire n° {$copyToRemove->get('id')} a été retiré du panier et remis en stock.";
                $cm->updateFromStock($cart);
            }
        } else {
            $params['error'] = "L'exemplaire n° $copyToRemoveId n'a pas pu être supprimé du panier.";
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($params);
        }
        return new RedirectResponse("/admin/caisse?cart_id=$cartId");
    }

    // Vider le panier
    if (isset($_GET['vacuum_cart'])) {
        if ($cm->vacuum($cart)) {
            $flashMessagesService->add("success", "Le panier a été vidé.");
        } else {
            $flashMessagesService->add("error", "Le panier n'a pas pu être vidé.");
        }
        return new RedirectResponse("/admin/caisse?cart_id=$cartId");
    }

    // Requête non-AJAX sans action : rediriger vers la nouvelle URL
    return new RedirectResponse("/admin/caisse?cart_id=$cartId");
};
