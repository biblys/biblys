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
use Biblys\Service\Images\ImagesService;
use Model\UserQuery;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/** @var CurrentSite $currentSite */
/** @var CurrentUser $currentUser */
/** @var Request $request */

/**
 * @throws PropelException
 */
return function (
    Request       $request,
    CurrentUser   $currentUser,
    CurrentSite   $currentSite,
    ImagesService $imagesService,
): Response|JsonResponse|RedirectResponse {
    $_SQL = LegacyCodeHelper::getGlobalDatabaseConnection();

    $cm = new CartManager();

    $cartId = $request->query->get("cart_id");
    if (!$cartId) {
        $cartForCurrentSeller = $cm->get([
            "cart_type" => "shop",
            "cart_seller_id" => $currentUser->getUser()->getId(),
            "cart_count" => 0
        ]);

        if ($cartForCurrentSeller) {
            return new RedirectResponse("/pages/adm_checkout?cart_id={$cartForCurrentSeller->get('id')}");
        }

        $newCartForCurrentSeller = $cm->create();
        $newCartForCurrentSeller->set("cart_type", "shop");
        $newCartForCurrentSeller->set("cart_seller_id", $currentUser->getUser()->getId());
        $newCartForCurrentSeller = $cm->update($newCartForCurrentSeller);
        return new RedirectResponse("/pages/adm_checkout?cart_id={$newCartForCurrentSeller->get('id')}");
    }

    /** @var Cart $cart */
    $cart = $cm->get(array('cart_id' => $cartId));
    if (!$cart) {
        throw new NotFoundHttpException("Panier $cartId introuvable");
    }

    /* ACTIONS */

// Enregistrer la vente
    if (isset($_POST['validate'])) {

        try {
            $_SQL->beginTransaction();

            // Create new order
            $_O = new OrderManager();
            $order = $_O->create();

            // Update order info
            if (!empty($_POST['seller_id'])) $order->set('seller_id', $_POST['seller_id']);
            if (!empty($_POST['customer_id'])) $order->set('customer_id', $_POST['customer_id']);

            $order->set('order_type', 'shop');
            $order->set('order_payment_cash', $_POST['cart_cash']);
            $order->set('order_payment_cheque', $_POST['cart_cheque']);
            $order->set('order_payment_card', $_POST['cart_card']);
            $order->set('order_amount_tobepaid', $_POST['cart_topay']);
            $order->set('order_payment_left', $_POST['cart_togive']);
            $order->set('order_payment_date', date('Y-m-d H:i:s'));

            // Hydrate order from cart
            $_O->hydrateFromCart($order, $cart);

            // Update order
            $order = $_O->update($order);

            // Reset cart
            $cm->vacuum($cart);
            $cm->delete($cart);

            $_SQL->commit();
        } catch (Exception $e) {
            $_SQL->rollBack();
            throw $e;
        }

        $r = array('created_order' => $order->get('order_id'));

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($r);
        }
    }

// Changer le nom du panier
    if (isset($_POST['set_title'])) {
        $cart->set('cart_title', $_POST['set_title']);
        $cart = $cm->update($cart);
        if ($cart->get('cart_title') == $_POST['set_title']) $params['success'] = "Le nom du panier a bien été modifié.";
        else $params['error'] = "Le nom du panier n'a pas pu être modifié.";
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($params);
        }
    } // Changer le client du panier
    elseif (isset($_POST['set_customer'])) {
        $params = [];
        try {
            $cart->set('customer_id', $_POST['set_customer']);
            $cm->update($cart);
        } catch (Exception $e) {
            $error = $e;
        }

        if (isset($error)) {
            $params['error'] = $error;
        } elseif ($cart->get('customer_id') == $_POST['set_customer']) {
            $params['success'] = "Le client du panier a bien été modifié.";
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($params);
        } else {
            return new RedirectResponse(sprintf("/pages/adm_checkout?%s", http_build_query($params)));
        }
    }

    $copyToRemoveId = (int)$request->query->get('remove_stock', false);

    $add = $request->query->get("add");
    $stockItemToAddId = (int)$request->query->get("id");
    if ($add && $stockItemToAddId) {
        if ($add === "stock") {
            try {
                $cm->addStock($cart, $_GET["id"]);
                $params["success"] = "L'exemplaire n&deg; $stockItemToAddId a été ajouté au panier.";
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
            } else {
                return new RedirectResponse(sprintf("/pages/adm_checkout?%s", http_build_query($params)));
            }
        }
    } // Retirer du panier
    elseif ($copyToRemoveId) {
        $sm = new StockManager();
        /** @var Stock $copyToRemove */
        $copyToRemove = $sm->getById($copyToRemoveId);
        $params = [];
        if ($copyToRemove) {
            if ($cm->removeStock($copyToRemove)) {
                $params['success'] = 'L\'exemplaire n&deg; ' . $copyToRemove->get('id') . ' a été retiré du panier et remis en stock.';
                $cm->updateFromStock($cart);
            }
        } else {
            $params['error'] = 'L\'exemplaire n&deg; ' . $_GET['id'] . ' n\'a pas pu être supprimé du panier.';
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($params);
        } else {
            return new RedirectResponse(sprintf("/pages/adm_checkout?%s", http_build_query($params)));
        }
    } // Vider le panier
    elseif (isset($_GET['vacuum_cart'])) {

        if ($cm->vacuum($cart)) {
            $params['success'] = 'Le panier n&deg; ' . $cart->get('cart_id') . ' a été vidé !';
        } else {
            $params['error'] = 'Le panier n&deg; ' . $cart->get('cart_id') . ' n\'a pas pu être vidé.';
        }

        return new RedirectResponse(sprintf("/pages/adm_checkout?%s", http_build_query($params)));
    }


    /* AUTRES */

// Client du panier en cours
    $customer_field = '
    <input type="text" name="customer" id="customer" placeholder="Rechercher un client..." class="event verylong">
    <input type="hidden" name="customer_id" id="customer_id" required>
    <span id="newsletter2" class="hidden">
        <input type="checkbox" name="customer_mailing" id="customer_mailing" value="1" disabled>
        <label for="customer_mailing" class="after">Abonné à la newsletter</label>
    </span>
';

    if ($cart->has('customer_id')) {
        $_CUSTOMERS = new CustomerManager();

        if ($customer = $_CUSTOMERS->get(array('customer_id' => $cart->get('customer_id')))) {

            if ($customer->get('customer_newsletter') == 1) $newsletter_checked = ' checked';
            else $newsletter_checked = NULL;

            $customer_field = '
            <input type="text" name="customer" id="customer" placeholder="Rechercher un client..." value="' . $customer->get('customer_last_name') . ', ' . $customer->get('customer_first_name') . ' (' . $customer->get('customer_email') . ')' . '" readonly class="pointer event verylong">
            <input type="hidden" name="customer_id" id="customer_id" value="' . $customer->get('customer_id') . '" required>
            <span id="newsletter">
                <input type="checkbox" name="customer_mailing" id="customer_mailing" value="1" disabled="disabled" ' . $newsletter_checked . '>
                <label for="customer_mailing" class="after">Abonné à la newsletter</label>
            </span>
        ';
        }
    }

// Current cart's seller
    $seller_field = null;
    if ($cart->has('seller_id')) {
        $seller = UserQuery::create()->findPk($cart->get('seller_id'));
        if ($seller) {
            $seller_field = '
            <input type="text" name="seller" id="seller" value="' . $seller->getEmail() . '" class="long" required readonly>
            <input type="hidden" name="seller_id" id="seller_id" value="' . $seller->getId() . '" required>
        ';
        }
    } else trigger_error('No seller id.');

// Livres du panier en cours
    $cart_content = NULL;
    $cart_count = 0;
    $cart_total = 0;
    $stock = $cm->getStock($cart);
    foreach ($stock as $s) {
        $cart_content .= $cart->getLine($imagesService, $s);
        $cart_count++;
        $cart_total += $s->get('selling_price');
    }

    $cm->updateFromStock($cart);

    // Paniers sauvegardés & VPC
    $s_carts = $cm->getAll();

    $shop_carts = NULL;
    foreach ($s_carts as $shopCart) {
        $seller = "Vendeur inconnu";
        if ($shopCart->has('seller_id')) {
            $seller = "Utilisateur Axys n°{$shopCart->get('seller_id')}";
        }
        if ($shopCart->has('seller_user_id')) {
            $sellerUser = UserQuery::create()->findPk($shopCart->get('seller_user_id'));
            $seller = $sellerUser->getEmail();
        }

        $s_cart = '
        <tr>
            <td><a href="/pages/adm_checkout?cart_id=' . $shopCart->get('cart_id') . '">' . $shopCart->get('cart_title') . '</a></td>
            <td>' . ($seller) . '</td>
            <td>' . ($shopCart->has('customer') ? $shopCart->get('customer')->get('first_name') . ' ' . $shopCart->get('customer')->get('last_name') : null) . '</td>
            <td class="right">' . $shopCart->get('cart_count') . '</td>
            <td class="right">' . currency($shopCart->get('cart_amount') / 100) . '</td>
            <td class="center">
                <a href="/pages/adm_checkout?cart_id=' . $shopCart->get('cart_id') . '&vacuum_cart=1" data-confirm="Voulez-vous vraiment VIDER ce panier et remettre ' . $shopCart->get('cart_count') . ' exemplaire' . s($shopCart->get('cart_count')) . ' en vente ?" title="Vider le panier et remettre ' . $shopCart->get('cart_count') . ' exemplaire' . s($shopCart->get('cart_count')) . ' en vente." class="btn btn-danger btn-sm">
                    <i class="fa fa-trash-o"></i>
                </a>
            </td>
        </tr>
    ';
        if ($shopCart->get('cart_count') > 0) {
            if ($shopCart->get('cart_type') == 'shop') $shop_carts .= $s_cart;
        }
    }

    if (isset($_GET['success'])) $alert = '<p class="success">' . $_GET['success'] . '</p>';
    elseif (isset($_GET['error'])) $alert = '<p class="error">' . $_GET['error'] . '</p>';
    elseif (isset($_GET['order_created'])) $alert = '<p class="success">La vente a été enregistrée sous le numéro ' . $_GET['order_created'] . '.</p>';
    else $alert = NULL;

    $request->attributes->set('page_title', 'Caisse');
    $content = '';

    if ($currentSite->getSite()->getTva() === 'fr') {
        $content .= '
        <div class="alert alert-warning">
            <p>
            
                <span class="fa fa-warning"></span>
                ' . "<strong>La caisse Biblys n'est pas un logiciel de caisse certifié.</strong><br/>
            </p>
            <p>
                <em><small>
                    Le fait, pour une personne assujettie à la taxe sur la valeur ajoutée, de ne pas 
                    justifier, par la production de l'attestation ou du certificat prévus au 3° bis 
                    du I de l'article 286, que le ou les logiciels ou systèmes de caisse qu'elle 
                    détient satisfont aux conditions d'inaltérabilité, de sécurisation, de conservation 
                    et d'archivage des données prévues par ces mêmes dispositions 
                    <strong>est sanctionné par une amende de 7 500 € par logiciel ou système de 
                    caisse concerné</strong>.
                    (<a href='https://www.legifrance.gouv.fr/codes/article_lc/LEGIARTI000037100434/2018-06-23'>Article 1770 du Code général des impôts</a>)
                </small></em>
            </p>
            " . '
            <p>
                <a class="btn btn-warning" href="https://www.economie.gouv.fr/entreprises/professionnels-logiciels-caisse">
                    En savoir plus
                </a>
            </p>
        </div>';
    }

    $content .= '
    <h1><span class="fa fa-money"></span> Caisse</h1>

    ' . $alert . '

    <form id="createCustomer" hidden>
        <fieldset>
            <p>
                <label for="customer_last_name">Nom :</label>
                <input type="text" id="customer_last_name" name="customer_last_name" required>
            </p>
            <p>
                <label for="customer_first_name">Prénom :</label>
                <input type="text" id="customer_first_name" name="customer_first_name">
            </p>
            <p>
                <label for="customer_email">Adresse e-mail :</label>
                <input type="email" id="customer_email" name="customer_email" class="long">
            </p>
            <p>
                <label for="customer_phone">Téléphone :</label>
                <input type="number" id="customer_phone" name="customer_phone">
            </p>
            <p class="center">
                <input type="checkbox" name="customer_newsletter" id="customer_newsletter" value="1" checked>
                <label for="customer_newsletter" class="after">Abonner à la newsletter</label>
            </p>
            <div class="right"><input type="submit" value="Valider" /></div>
        </fieldset>
    </form>

    <h3>
        <span id="cart_title" class="event pointer" contenteditable>' . $cart->get('cart_title') . '</span>
    </h3>

    <form>
        <fieldset>

            <input type="hidden" id="cart_id" name="cart_id" value=' . $cart->get('cart_id') . '>

            <p>
                <label for="seller">Libraire :</label>
                ' . $seller_field . '
            <p>

            <p>
                <label for="customer">Client :</label>
                ' . $customer_field . '
            </p>

        </fieldset>
    </form>

    <h3>Encaissement</h3>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Montant</th>
                <th>Espèces</th>
                <th>Chèque</th>
                <th>Carte</th>
                <th>À régler</th>
                <th>À rendre</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="right" id="cart_total" data-value="' . $cart_total . '">' . currency($cart_total / 100) . '</td>
                <td class="center"><input id="cart_cash" class="cart_payment" type="number" step="0.01" min=0 max=999 style="text-align: right;"></td>
                <td class="center"><input id="cart_cheque" class="cart_payment" type="number" step="0.01" min=0 max=999 style="text-align: right;"></td>
                <td class="center"><input id="cart_card" class="cart_payment" type="number" step="0.01" min=0 max=999 style="text-align: right;"></td>
                <td id="cart_topay" data-value=' . $cart_total . ' class="right">' . currency($cart_total / 100) . '</td>
                <td id="cart_togive" data-value=0 class="right">' . currency(0 / 100) . '</td>
            </tr>
        </tbody>
    </table>

    <br>
    <form id="checkout" class="event">
        <fieldset class="center">
            <button class="btn btn-primary" type="submit">Enregistrer la vente</button>
        </fieldset>
    </form>

    <h3>Panier en cours (<span id="cart_count">' . $cart_count . '</span> article<span id="cart_count_s">' . s($cart_count) . '</span>)</h3>

    <form id="checkout_add" class="event">
        <fieldset>

            <p>
                <label for="checkout_add_input">Ajouter un article :</label>
                <input type="text" name="checkout_add_input" id="checkout_add_input" class="verylong event" autocomplete="off" autofocus>
            </p>

        </fieldset>
    </form>

    <table id="checkout_add_results" class="admin-table">
    </table>

    <table id="checkout_cart" class="admin-table">
        <thead>
            <tr>
                <th>Ref.</th>
                <th></th>
                <th>Article</th>
                <th>Prix</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            ' . $cart_content . '
        </tbody>
    </table>

    <br>
    <h3>Autres paniers</h3>
    <p><a href="/pages/adm_checkout?new_cart" class="btn btn-primary">Nouveau panier</a></p>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Libraire</th>
                <th>Client</th>
                <th>Articles</th>
                <th>Montant</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            ' . $shop_carts . '
        </tbody>
    </table>

';

    return new Response($content);
};
