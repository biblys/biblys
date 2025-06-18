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


use Biblys\Service\CurrentSite;
use Model\CountryQuery;
use Model\ShippingOptionQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @throws PropelException
 */
return function (Request $request, CurrentSite $currentSite): Response|RedirectResponse
{
    $cm = new CustomerManager();
    $om = new OrderManager();
    $sm = new StockManager();
    $shm = new ShippingManager();

    $order_id = $request->query->get('order_id', false);
    $order = $om->get(array('order_id' => $order_id));

    $content = null;

    // Order has been deleted ?
    $deleted_id = $request->query->get('deleted', false);
    if ($deleted_id) {
        $content .= '<p class="success">La vente n° ' . $deleted_id . ' a été annulée.</p>';
    }

    if (!$order) {
        throw new NotFoundHttpException("Commande n° $order_id inconnue");
    }

    $o = $order;
    $message = NULL;

    $order_type = 'Commande';
    if ($order->get('type') == 'shop') $order_type = 'Vente';

    // Modifier la commande
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Setting customer
        $action = $request->request->get('action', false);
        if ($action == "customer") {

            $customer_id = $request->request->get('customer_id', false);

            // Set customer
            if ($customer_id) {
                /** @var Customer $customer */
                $customer = $cm->getById($customer_id);
                if (!$customer) {
                    throw new Exception("Client n° $customer_id inconnu");
                }
                $om->setCustomer($order, $customer);
            } else {
                $om->setCustomer($order);
            }
        } // Using main form
        else {
            foreach ($_POST as $key => $val) {
                $order->set($key, $val);
            }
            $om->update($order);
        }

        return new RedirectResponse('/order/' . $order->get('url') . '?updated=1');
    }

// Delete order
    if ($request->query->get('delete', false)) {
        $om->cancel($order);
        return new RedirectResponse("/pages/adm_order?deleted={$order->get("id")}");
    } // Ajouter un exemplaire
    elseif (isset($_GET['stock_add'])) {
        if ($stock = $sm->get(array('stock_id' => $_GET['stock_add']))) {

            try {
                $om->addStock($order, $stock);
                $om->updateFromStock($order);
            } catch (Exception $e) {
                trigger_error($e->getMessage());
            }

            return new RedirectResponse("/pages/adm_order?order_id={$order->get("order_id")}&stock_added={$_GET["stock_add"]}");
        } else trigger_error("L'exemplaire " . $_GET['stock_add'] . " n'existe pas !");
    } elseif (isset($_GET["stock_added"])) {
        $message = '<p class="success">L\'exemplaire n°&nbsp;' . $_GET["stock_added"] . ' a bien été ajouté à la commande.</p>';
    } // Retirer un exemplaire
    elseif (isset($_GET["stock_remove"])) {
        if ($stock = $sm->get(array('stock_id' => $_GET['stock_remove']))) {
            try {
                $om->removeStock($order, $stock);
                $om->updateFromStock($order);
            } catch (Exception $e) {
                trigger_error($e->getMessage());
            }
        } else trigger_error("L'exemplaire " . $_GET['stock_remove'] . " n'existe pas !");

        return new RedirectResponse("/pages/adm_order?order_id={$order->get("order_id")}&stock_removed={$_GET["stock_remove"]}");
    } elseif (isset($_GET["stock_removed"])) {
        $message = '<p class="success">L\'exemplaire n°&nbsp;' . $_GET["stock_removed"] . ' a bien été retiré de la commande.</p>';
    }

    $new_shipping_fee_id = $request->query->get('shipping_fee');
    if ($new_shipping_fee_id) {
        $fee = $shm->getById($new_shipping_fee_id);

        $error = false;
        try {
            $order->setShippingFee($fee);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $message = '<p class="alert alert-danger">' . $e->getMessage() . '</p>';
        }

        if (!$error) {
            $om->update($order);
            return new RedirectResponse("/pages/adm_order?order_id={$order->get('order_id')}&shipping_updated=1");
        }
    }

    $shipping_updated = $request->query->get('shipping_updated');
    if ($shipping_updated) {
        $message = '<p class="alert alert-success">Le mode d\'expédition de la commande a été mis à jour.</p>';
    }

// Articles de la commande
    $articles = EntityManager::prepareAndExecute(
        "SELECT `stock_id`, `article_title`, `stock_selling_price` FROM `stock` JOIN `articles` ON `stock`.`article_id` = `articles`.`article_id` WHERE `order_id` = :order_id ORDER BY `article_title_alphabetic`",
        ["order_id" => $o["order_id"]],
    );
    $article_list = NULL;
    while ($a = $articles->fetch()) {
        $article_list .= '<option value=' . $a["stock_id"] . '>' . $a["article_title"] . ' (' . price($a["stock_selling_price"], 'EUR') . ')</option>';
    }

// Customer
    /** @noinspection HtmlUnknownAnchorTarget */
    $customer = '
    <fieldset>
        <legend>Client</legend>
        <p>
            <label for="customer_id">Client n°</label>
            <input type="text" id="customer_id" name="customer_id" readonly> (<a href="#customer">Associer un client</a>)
        <p>
    </fieldset>
';
    if ($c = $cm->get(array('customer_id' => $o['customer_id']))) {
        $customer = '
        <fieldset>
            <legend>Client</legend>
            <p>
                <label for="customer_id">Client n°</label>
                <input type="text" id="customer_id" name="customer_id" value="' . $c->get('id') . '"> (<a href="/pages/adm_customer?id=' . $c->get('id') . '">' . trim($c->get('first_name') . ' ' . $c->get('last_name')) . '</a>)
            <p>
        </fieldset>
    ';
    }

    $buttons = '<button type="submit" class="btn btn-primary" form="order"><i class="fa fa-save"></i> Enregistrer</button>
        <a href="/pages/adm_order?order_id=' . $order->get('id') . '&delete=1" class="btn btn-warning" data-confirm="Voulez-vous vraiment ANNULER cette ' . $order_type . ' ?"><i class="fa fa-trash-can"></i> Annuler</a>';

    $cm = new CountryManager();
    $countries = $cm->getAll();

    $countries = array_map(function ($country) use ($order) {
        return '<option value="' . $country->get('id') . '"' . ($country == $order->get('country') ? ' selected' : null) . '>' . $country->get('name') . '</option>';
    }, $countries);

    $stockItemCount = \Model\StockQuery::create()->filterByOrderId($order->get('id'))->count();

    $feesList = [];
    $country = $order->get("country");
    if ($country instanceof Country) {
        $countryModel = CountryQuery::create()->findPk($country->get("id"));
        $fees = ShippingOptionQuery::getForCountryAndWeightAndAmountAndArticleCount(
            $countryModel,
            $order->getTotalWeight(),
            $order->get('amount'),
            articleCount: $stockItemCount,
        );
        $feesList = array_map(function ($fee) {
            return '<option value="' . $fee->getId() . '">' . $fee->getMode() . ' [' . currency($fee->getFee(), true) . ']</option>';
        }, $fees);
    }

    $pageTitle = $order_type . ' n° <a href="/order/' . $o['order_url'] . '">' . $o["order_id"] . '</a>';
    $request->attributes->set("page_title", "$order_type n° {$o["order_id"]}");
    $content .= '
    <h2>' . $pageTitle . '</h2>

    <p class="buttonset">
        ' . $buttons . '
    </p>

    <div class="admin">
        <p>' . $order_type . ' n°' . $o["order_id"] . '</p>
        <p><a href="/order/' . $o["order_url"] . '">voir</a></p>
    </div>

    ' . $message . '

    <form method="post" id="order" class="fieldset">
        <fieldset>

            <input type="hidden" name="order_url" value="' . $o["order_url"] . '" />

            <legend>Général</legend>
            <label for="order" class="disabled">' . $order_type . ' n°</label>
            <input type="number" name="order_id" id="order_id" value= "' . $o["order_id"] . '" class="short" readonly />
            <br />
            <label for="order_insert">Date :</label>
            <input type="text" name="order_insert" id="order_insert" value="' . $o["order_insert"] . '" class="datetime" />
            <br />
            <p>
                <label for="order_type">Type :</label>
                <select name="order_type" id="order_type">
                    <option></option>
                    <option value="web"' . ($o->get('type') == 'web' ? ' selected' : null) . '>Commande VPC</option>
                    <option value="shop"' . ($o->get('type') == 'shop' ? ' selected' : null) . '>Vente magasin</option>
                </select>
            </p>
            <br />
            <label for="order_amount">Montant :</label>
            <input type="number" name="order_amount" id="order_amount" value="' . $o["order_amount"] . '" class="mini" /> centimes
            <br />
            <label for="order_amount_tobepaid">Montant à payer :</label>
            <input type="number" name="order_amount_tobepaid" id="order_amount_tobepaid" value="' . $o["order_amount_tobepaid"] . '" class="mini" /> centimes
            <br />
            <label for="order_discount">Remise :</label>
            <input type="number" name="order_discount" id="order_discount" value="' . $o["order_discount"] . '" class="mini" /> centimes
        </fieldset>

        ' . $customer . '

        <fieldset>
            <legend>Coordonnées</legend>

            <label for="order_firstname">Prénom :</label>
            <input type="text" name="order_firstname" id="order_firstname" value="' . $o["order_firstname"] . '" />
            <br />
            <label for="order_lastname">Nom :</label>
            <input type="text" name="order_lastname" id="order_lastname" value="' . $o["order_lastname"] . '" />
            <br /><br>
            <label for="order_address1">Adresse 1 :</label>
            <input type="text" name="order_address1" id="order_address1" value="' . $o["order_address1"] . '" />
            <br />
            <label for="order_address2">Adresse 2 :</label>
            <input type="text" name="order_address2" id="order_address2" value="' . $o["order_address2"] . '" />
            <br />
            <label for="order_postalcode">Code postal :</label>
            <input type="text" name="order_postalcode" id="order_postalcode" value="' . $o["order_postalcode"] . '" />
            <br />
            <label for="order_city">Ville :</label>
            <input type="text" name="order_city" id="order_city" value="' . $o["order_city"] . '" />
            <br />
            <label for="country_id">Pays :</label>
            <select name="country_id" id="country_id">
                <option></option>
                ' . implode($countries) . '
            </select>
            <br /><br>
            <label for="order_phone">Téléphone :</label>
            <input type="text" name="order_phone" id="order_phone" value="' . $o["order_phone"] . '" />
            <br />
            <label for="order_email">E-mail :</label>
            <input type="text" name="order_email" id="order_email" value="' . $o["order_email"] . '">
            <br />

        </fieldset>

        <fieldset>
            <legend>Paiement</legend>
            <label for="order_payment_mode">Mode :</label>
            <input type="text" name="order_payment_mode" id="order_payment_cash" value="' . $o["order_payment_mode"] . '" class="mini" />
            <br />
            <label for="order_payment_date">Date :</label>
            <input type="text" name="order_payment_date" id="order_payment_date" value="' . $o["order_payment_date"] . '" class="datetime" />
            <br />
            <label for="order_payment_cash">Espèces :</label>
            <input type="number" name="order_payment_cash" id="order_payment_cash" value="' . $o["order_payment_cash"] . '" class="mini" /> centimes
            <br />
            <label for="order_payment_cheque">Chèque :</label>
            <input type="number" name="order_payment_cheque" id="order_payment_cheque" value="' . $o["order_payment_cheque"] . '" class="mini" /> centimes
            <br />
            <label for="order_payment_transfer">Virement :</label>
            <input type="number" name="order_payment_transfer" id="order_payment_transfer" value="' . $order->get('payment_transfer') . '" class="mini" /> centimes
            <br />
            <label for="order_payment_card">Carte bancaire :</label>
            <input type="number" name="order_payment_card" id="order_payment_card" value="' . $o["order_payment_card"] . '" class="mini" /> centimes
            <br />
            <label for="order_payment_paypal">Paypal :</label>
            <input type="number" name="order_payment_paypal" id="order_payment_paypal" value="' . $o["order_payment_paypal"] . '" class="mini" /> centimes
            <br />
            <label for="order_payment_payplug">Payplug :</label>
            <input type="number" name="order_payment_payplug" id="order_payment_payplug" value="' . $o["order_payment_payplug"] . '" class="mini" /> centimes
            <br />
            <label for="order_payment_left">Monnaie rendue :</label>
            <input type="number" name="order_payment_left" id="order_payment_left" value="' . $o["order_payment_left"] . '" class="mini" /> centimes
            <br />
        </fieldset>

        <fieldset>
            <legend>Relance</legend>
            <label for="order_followup_date">Date :</label>
            <input type="text" name="order_followup_date" id="order_followup_date" value="' . $o["order_followup_date"] . '" class="datetime" />
            <br />
        </fieldset>

        <fieldset>
            <legend>Expédition</legend>
            <label for="order_shipping_mode">Mode :</label>
            <input type="text" name="order_shipping_mode" id="order_shipping_mode" value="' . $o["order_shipping_mode"] . '" class="medium" />
            <br />
            <label for="order_shipping">Port :</label>
            <input type="number" name="order_shipping" id="order_shipping" value="' . $o["order_shipping"] . '" class="mini" /> centimes
            <br />
            <label for="order_shipping_date">Date :</label>
            <input type="text" name="order_shipping_date" id="order_shipping_date" value="' . $o["order_shipping_date"] . '" class="datetime" />
            <br />
            <label for="order_track_number">N° de suivi :</label>
            <input type="text" name="order_track_number" id="order_track_number" value="' . $o["order_track_number"] . '" class="datetime" />
            <br />
        </fieldset>

        <fieldset>
            <legend>Confirmation</legend>
            <label for="order_confirmation_date">Date :</label>
            <input type="text" name="order_confirmation_date" id="order_confirmation_date" value="' . $o["order_confirmation_date"] . '" class="datetime" />
            <br />
        </fieldset>

        <fieldset>
            <legend>Annulation</legend>
            <label for="order_cancel_date">Date :</label>
            <input type="text" name="order_cancel_date" id="order_cancel_date" value="' . $o["order_cancel_date"] . '" class="datetime" />
            <br />
        </fieldset>

        <fieldset class="center">
            ' . $buttons . '
        </fieldset>
    </form>

    <h3>Modifier la commande</h3>

    <form id="customer" class="fieldset" method="post">
        <fieldset>
            <legend>Associer un client</legend>
            <input type="hidden" name="action" value="customer">
            <p>
                <label for="customer_id">Client n°&nbsp;:</label>
                <input name="customer_id" id="customer_id" value="' . $order->get("customer_id") . '">
                <button type="submit" class="btn btn-success btn-sm">Associer</button>
            </p>
        </fieldset>
    </form>

    <form class="fieldset">
        <fieldset>
            <legend>Modifier le mode d\'expédition</legend>
            <input type="hidden" name="order_id" value="' . $o["order_id"] . '">
            <p>
                <label for="shipping_fee">Nouveau mode :</label>
                <select name="shipping_fee" id="shipping_fee">
                    <option/>
                    ' . join($feesList) . '
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Valider</button>
            </p>
        </fieldset>
    </form>

    <form class="fieldset">
        <fieldset>
            <legend>Retirer un article</legend>
            <input type="hidden" name="order_id" value="' . $o["order_id"] . '">
            <p>
                <label for="stock_remove">Articles :</label>
                <select name="stock_remove" id="stock_remove">' . $article_list . '</select>
                <button type="submit" class="btn btn-warning btn-sm">Retirer</button>
            </p>
        </fieldset>
    </form>

    <form class="fieldset">
        <fieldset>
            <legend>Ajouter un article</legend>
            <input type="hidden" name="order_id" value="' . $o["order_id"] . '">
            <p>
                <label for="stock_add">Exemplaire n°&nbsp;:</label>
                <input name="stock_add" id="stock_add">
                <button type="submit" class="btn btn-success btn-sm">Ajouter</button>
            </p>
        </fieldset>
    </form>

';

    return new Response($content);
};