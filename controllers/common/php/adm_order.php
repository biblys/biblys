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
use Model\StockQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @throws PropelException
 */
return function (Request $request, UrlGenerator $urlGenerator): Response|RedirectResponse
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
        <div class="form-group row">
            <label for="customer_id" class="col-sm-3 col-form-label">Client n°</label>
            <div class="col-sm-3">
                <input type="text" id="customer_id" name="customer_id" class="form-control" readonly>
                <small class="form-text text-muted"><a href="#customer">Associer un client</a></small>
            </div>
        </div>
    </fieldset>
';
    if ($c = $cm->get(array('customer_id' => $o['customer_id']))) {
        $customer = '
        <fieldset>
            <legend>Client</legend>
            <div class="form-group row">
                <label for="customer_id" class="col-sm-3 col-form-label">Client n°</label>
                <div class="col-sm-3">
                    <input type="text" id="customer_id" name="customer_id" class="form-control" value="' . $c->get('id') . '">
                    <small class="form-text text-muted"><a href="/pages/adm_customer?id=' . $c->get('id') . '">' . trim($c->get('first_name') . ' ' . $c->get('last_name')) . '</a></small>
                </div>
            </div>
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

    $stockItemCount = StockQuery::create()->filterByOrderId($order->get('id'))->count();

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

    $paymentModes = \Model\Payment::getModes();
    $paymentModesHtml = array_map(function ($mode) {
        return '<option value="' . $mode . '">' . $mode . '</option>';
    }, $paymentModes);
    $paymentCreateUrl = $urlGenerator->generate("payment_create");

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
            <div class="form-group row">
                <label for="order_id" class="col-sm-3 col-form-label">' . $order_type . ' n°</label>
                <div class="col-sm-3">
                    <input type="number" name="order_id" id="order_id" value="' . $o["order_id"] . '" class="form-control" readonly />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_insert" class="col-sm-3 col-form-label">Date</label>
                <div class="col-sm-4">
                    <input type="datetime-local" name="order_insert" id="order_insert" value="' . $o["order_insert"] . '" class="form-control" />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_type" class="col-sm-3 col-form-label">Type</label>
                <div class="col-sm-3">
                    <select name="order_type" id="order_type" class="form-control">
                        <option></option>
                        <option value="web"' . ($o->get('type') == 'web' ? ' selected' : null) . '>Commande VPC</option>
                        <option value="shop"' . ($o->get('type') == 'shop' ? ' selected' : null) . '>Vente magasin</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_amount" class="col-sm-3 col-form-label">Montant</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_amount" id="order_amount" value="' . $o["order_amount"] . '" class="form-control" />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_amount_tobepaid" class="col-sm-3 col-form-label">Montant à payer</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_amount_tobepaid" id="order_amount_tobepaid" value="' . $o["order_amount_tobepaid"] . '" class="form-control" />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_discount" class="col-sm-3 col-form-label">Remise</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_discount" id="order_discount" value="' . $o["order_discount"] . '" class="form-control" />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
        </fieldset>

        ' . $customer . '

        <fieldset>
            <legend>Coordonnées</legend>
            <div class="form-group row">
                <label for="order_firstname" class="col-sm-3 col-form-label">Prénom</label>
                <div class="col-sm-4">
                    <input type="text" name="order_firstname" id="order_firstname" value="' . $o["order_firstname"] . '" class="form-control" />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_lastname" class="col-sm-3 col-form-label">Nom</label>
                <div class="col-sm-4">
                    <input type="text" name="order_lastname" id="order_lastname" value="' . $o["order_lastname"] . '" class="form-control" />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_address1" class="col-sm-3 col-form-label">Adresse 1</label>
                <div class="col-sm-5">
                    <input type="text" name="order_address1" id="order_address1" value="' . $o["order_address1"] . '" class="form-control" />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_address2" class="col-sm-3 col-form-label">Adresse 2</label>
                <div class="col-sm-5">
                    <input type="text" name="order_address2" id="order_address2" value="' . $o["order_address2"] . '" class="form-control" />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_postalcode" class="col-sm-3 col-form-label">Code postal</label>
                <div class="col-sm-3">
                    <input type="text" name="order_postalcode" id="order_postalcode" value="' . $o["order_postalcode"] . '" class="form-control" />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_city" class="col-sm-3 col-form-label">Ville</label>
                <div class="col-sm-4">
                    <input type="text" name="order_city" id="order_city" value="' . $o["order_city"] . '" class="form-control" />
                </div>
            </div>
            <div class="form-group row">
                <label for="country_id" class="col-sm-3 col-form-label">Pays</label>
                <div class="col-sm-4">
                    <select name="country_id" id="country_id" class="form-control">
                        <option></option>
                        ' . implode($countries) . '
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_phone" class="col-sm-3 col-form-label">Téléphone</label>
                <div class="col-sm-4">
                    <input type="text" name="order_phone" id="order_phone" value="' . $o["order_phone"] . '" class="form-control" />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_email" class="col-sm-3 col-form-label">E-mail</label>
                <div class="col-sm-5">
                    <input type="text" name="order_email" id="order_email" value="' . $o["order_email"] . '" class="form-control" />
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Paiement</legend>
            <div class="form-group row">
                <label for="order_payment_mode" class="col-sm-3 col-form-label">Mode</label>
                <div class="col-sm-3">
                    <input type="text" name="order_payment_mode" id="order_payment_mode" value="' . $o["order_payment_mode"] . '" class="form-control" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_payment_date" class="col-sm-3 col-form-label">Date</label>
                <div class="col-sm-5">
                    <input type="datetime-local" name="order_payment_date" id="order_payment_date" value="' . $o["order_payment_date"] . '" class="form-control" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_payment_cash" class="col-sm-3 col-form-label">Espèces</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_payment_cash" id="order_payment_cash" value="' . $o["order_payment_cash"] . '" class="form-control" disabled />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_payment_cheque" class="col-sm-3 col-form-label">Chèque</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_payment_cheque" id="order_payment_cheque" value="' . $o["order_payment_cheque"] . '" class="form-control" disabled />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_payment_transfer" class="col-sm-3 col-form-label">Virement</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_payment_transfer" id="order_payment_transfer" value="' . $order->get('payment_transfer') . '" class="form-control" disabled />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_payment_card" class="col-sm-3 col-form-label">Carte bancaire</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_payment_card" id="order_payment_card" value="' . $o["order_payment_card"] . '" class="form-control" disabled />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_payment_paypal" class="col-sm-3 col-form-label">Paypal</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_payment_paypal" id="order_payment_paypal" value="' . $o["order_payment_paypal"] . '" class="form-control" disabled />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_payment_payplug" class="col-sm-3 col-form-label">Payplug</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_payment_payplug" id="order_payment_payplug" value="' . $o["order_payment_payplug"] . '" class="form-control" disabled />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_payment_left" class="col-sm-3 col-form-label">Monnaie rendue</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_payment_left" id="order_payment_left" value="' . $o["order_payment_left"] . '" class="form-control" disabled />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Relance</legend>
            <div class="form-group row">
                <label for="order_followup_date" class="col-sm-3 col-form-label">Date</label>
                <div class="col-sm-5">
                    <input type="datetime-local" name="order_followup_date" id="order_followup_date" value="' . $o["order_followup_date"] . '" class="form-control" />
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Expédition</legend>
            <div class="form-group row">
                <label for="order_shipping_mode" class="col-sm-3 col-form-label">Mode</label>
                <div class="col-sm-3">
                    <input type="text" name="order_shipping_mode" id="order_shipping_mode" value="' . $o["order_shipping_mode"] . '" class="form-control" />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_shipping" class="col-sm-3 col-form-label">Port</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="number" name="order_shipping" id="order_shipping" value="' . $o["order_shipping"] . '" class="form-control" />
                        <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="order_shipping_date" class="col-sm-3 col-form-label">Date</label>
                <div class="col-sm-5">
                    <input type="datetime-local" name="order_shipping_date" id="order_shipping_date" value="' . $o["order_shipping_date"] . '" class="form-control" />
                </div>
            </div>
            <div class="form-group row">
                <label for="order_track_number" class="col-sm-3 col-form-label">N° de suivi</label>
                <div class="col-sm-5">
                    <input type="text" name="order_track_number" id="order_track_number" value="' . $o["order_track_number"] . '" class="form-control" />
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Confirmation</legend>
            <div class="form-group row">
                <label for="order_confirmation_date" class="col-sm-3 col-form-label">Date</label>
                <div class="col-sm-4">
                    <input type="datetime-local" name="order_confirmation_date" id="order_confirmation_date" value="' . $o["order_confirmation_date"] . '" class="form-control" />
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Annulation</legend>
            <div class="form-group row">
                <label for="order_cancel_date" class="col-sm-3 col-form-label">Date</label>
                <div class="col-sm-4">
                    <input type="datetime-local" name="order_cancel_date" id="order_cancel_date" value="' . $o["order_cancel_date"] . '" class="form-control" />
                </div>
            </div>
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
            <div class="form-group row">
                <label for="customer_id" class="col-sm-3 col-form-label">Client n°</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input name="customer_id" id="customer_id" value="' . $order->get("customer_id") . '" class="form-control">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-success">Associer</button>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
    
    <form id="add_payment" class="fieldset" action="'.$paymentCreateUrl.'" method="post">
        <fieldset>
            <legend>Ajouter un paiement</legend>
            <input type="hidden" name="order_id" value="'.$order->get('id').'">
            <div class="form-inline">
                <label for="payment_amount" class="col-sm-3 col-form-label">Nouveau paiement</label>
                <select name="payment_mode" id="payment_mode" class="form-control mr-2" required>
                    <option></option>
                    '.join('', $paymentModesHtml).'
                </select>
                <div class="input-group mr-2">
                    <input type="number" min="0" name="payment_amount" id="payment_amount" class="form-control" placeholder="Montant" required>
                    <div class="input-group-append"><span class="input-group-text">centimes</span></div>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </fieldset>
    </form>

    <form class="fieldset">
        <fieldset>
            <legend>Modifier le mode d\'expédition</legend>
            <input type="hidden" name="order_id" value="' . $o["order_id"] . '">
            <div class="form-group row">
                <label for="shipping_fee" class="col-sm-3 col-form-label">Nouveau mode</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <select name="shipping_fee" id="shipping_fee" class="form-control">
                            <option/>
                            ' . join($feesList) . '
                        </select>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Valider</button>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>

    <form class="fieldset">
        <fieldset>
            <legend>Retirer un article</legend>
            <input type="hidden" name="order_id" value="' . $o["order_id"] . '">
            <div class="form-group row">
                <label for="stock_remove" class="col-sm-3 col-form-label">Articles</label>
                <div class="col-sm-6">
                    <div class="input-group">
                        <select name="stock_remove" id="stock_remove" class="form-control">' . $article_list . '</select>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-warning">Retirer</button>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>

    <form class="fieldset">
        <fieldset>
            <legend>Ajouter un article</legend>
            <input type="hidden" name="order_id" value="' . $o["order_id"] . '">
            <div class="form-group row">
                <label for="stock_add" class="col-sm-3 col-form-label">Exemplaire n°</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input name="stock_add" id="stock_add" class="form-control">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-success">Ajouter</button>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>

';

    return new Response($content);
};
