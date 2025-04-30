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
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @throws CannotAddStockItemToCartException
 * @throws PropelException
 */
return function (Request $request): Response {
    $lm = new ListeManager();
    $sm = new StockManager();
    $cm = new CartManager();

    $request->attributes->set("page_title", "Modifier les exemplaires de la liste");

    $content = "";

    $listId = $request->query->get("list_id");
    $action = $request->request->get("action", "preview");

    $list = $lm->getById($listId);
    if (!$list) {
        throw new NotFoundHttpException("Liste n°$listId inconnue.");
    }

    $stocks = EntityManager::prepareAndExecute(
        'SELECT `stock_id`, `stock`.`site_id` FROM `stock` JOIN `links` USING(`stock_id`) WHERE `list_id` = :list_id',
        ["list_id" => $listId]
    );
    $stocks = $stocks->fetchAll(PDO::FETCH_ASSOC);

    $changes = [];
    if ($request->getMethod() === "POST") {

        $cart = null;
        $addToCart = $request->request->get("add_to_cart");
        if (!empty($addToCart)) {
            /** @var Cart $cart */
            $cart = $cm->getById($addToCart);
            if (!$cart) {
                throw new Exception("Aucun panier avec le numéro $addToCart");
            }
        }

        $updated = 0;
        foreach ($stocks as $s) {
            $stock = $sm->get(['stock_id' => $s['stock_id'], 'site_id' => $s['site_id']]);

            // Keep a clone old object
            $oldStock = clone $stock;

            // Update fields
            if (!empty($_POST['stock_invoice'])) {
                $stock->set('stock_invoice', $_POST['stock_invoice']);
            }

            if (!empty($_POST['stock_stockage'])) {
                $stock->set('stock_stockage', $_POST['stock_stockage']);
            }

            if (!empty($_POST['stock_purchase_price'])) {
                $stock->set('stock_purchase_price', $_POST['stock_purchase_price']);
            }

            if (!empty($_POST['stock_selling_price'])) {
                $stock->set('stock_selling_price', $_POST['stock_selling_price']);
            }

            if (!empty($_POST['stock_purchase_date'])) {
                $stock->set('stock_purchase_date', $_POST['stock_purchase_date']);
            }

            // Bulk actions

            // Add to cart
            if ($cart) {
                $cm->addStock($cart, $stock);
            }

            // Promotion
            $promo = $request->request->get('price_promo', false);
            if ($promo) {
                $current_price = $stock->get('selling_price');
                $new_price = (int)$current_price - ($current_price / 100 * $promo);
                $stock->set('stock_selling_price_saved', $current_price);
                $stock->set('stock_selling_price', $new_price);
            }

            // Restore saved price
            $restore = $request->request->get('restore_saved_price', false);
            if ($restore) {
                $stock->restoreSavedPrice();
            }

            // Persist changes
            if ($action === "go") {
                $sm->update($stock);
            } else {
                // Compare each property
                foreach ($stock as $k => $v) {
                    if ($stock->get($k) != $oldStock->get($k)) {
                        $row = '
                                <tr>
                                    <td>' . $stock->get('id') . '</td>
                                    <td>' . $stock->get('article')->get('title') . '</td>
                                    <td>' . $k . '</td>
                                    <td>' . $oldStock->get($k) . '</td>
                                    <td>' . $stock->get($k) . '</td>
                                </tr>
                            ';
                        $changes[] = $row;
                    }
                }
            }

            $updated++;
        }

        if ($action === "go") {
            $query = http_build_query([
                "list_id" => $listId,
                "updated" => $updated
            ]);
            return new RedirectResponse("/pages/adm_stock_bulk?" . $query);
        }
    }

    $previewTable = "";
    if ($request->getMethod() === "POST" && $action === "preview") {
        $previewTable = '
            <table class="table">
                <thead>
                    <th>Ex. n°</th>
                    <th>Titre</th>
                    <th>Champ modifié</th>
                    <th>Valeur actuelle</th>
                    <th>Nouvelle valeure</th>
                </thead>
                <tbody>
                    ' . join($changes) . '
                </tbody>
            </table>
        ';
    }

    $content .= '
            <h1>Modifier les exemplaires de la liste</h1>
            <h2>Liste : <a href="/pages/adm_list?list=' . $list->get('url') . '">' . $list->get('title') . '</a> (' . count($stocks) . ' exemplaires)</h2>
        
            ' . (isset($_GET['updated']) ? '<p class="success">' . $_GET['updated'] . ' exemplaires modifiés.</p>' : null) . '
        ' . $previewTable . '                
        <form action="/pages/adm_stock_bulk?list_id=' . $list->get('id') . '" method="post" class="fieldset" role="form">
            
            <fieldset>
                <legend>Champs à modifier pour TOUS les exemplaires</legend>
                <p class="alert alert-info">Les champs renseignés seront modifiés pour chaque exemplaire de la liste. Si le champ est laissé vide sur cette page, le contenu original sera conservé.</p>
            
                <div class="form-group row">
                    <label for="stock_invoice" class="col-sm-3 col-form-label">Lot / Facture :</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="stock_invoice" name="stock_invoice" value="' . $request->request->get('stock_invoice') . '">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="stock_stockage" class="col-sm-3 col-form-label">Emplacement :</label>
                    <div class="col-sm-9">
                        <input id="stock_stockage" name="stock_stockage" type="text" class="form-control" value="' . $request->request->get('stock_stockage') . '">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="stock_purchase_price" class="col-sm-3 col-form-label">Prix d\'achat :</label>
                    <div class="col-sm-9">
                        <input id="stock_purchase_price" name="stock_purchase_price" type="number" class="short" value="' . $request->request->get('stock_purchase_price') . '"> centimes
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="stock_selling_price" class="col-sm-3 col-form-label">Prix de vente :</label>
                    <div class="col-sm-9">
                        <input id="stock_selling_price" name="stock_selling_price" type="number" class="short" value="' . $request->request->get('stock_selling_price') . '"> centimes
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="stock_purchase_date" class="col-sm-3 col-form-label">Date d\'achat :</label>
                    <div class="col-sm-9">
                        <input id="stock_purchase_date" name="stock_purchase_date" type="date" class="form-control long" value="' . $request->request->get('stock_purchase_date') . '">
                    </div>
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Actions à appliquer à TOUS les exemplaires</legend>
                <p class="alert alert-info">Pour chaque champ renseigné, l\'action sera appliquée à chaque exemplaire de la liste. Si le champ est laissé vide, aucune action ne sera appliquée.</p>
            
                <div class="form-group row">
                    <label for="add_to_cart" class="col-sm-5 col-form-label">Ajouter au panier n° :</label>
                    <div class="col-sm-7">
                        <input id="add_to_cart" name="add_to_cart" type="number" class="form-control short" value="' . $request->request->get('add_to_cart') . '">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="price_promo" class="col-sm-5 col-form-label">Appliquer une réduction de (%) :</label>
                    <div class="col-sm-7">
                        <input id="price_promo" name="price_promo" type="number" class="form-control short" value="' . $request->request->get('price_promo') . '">
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-7">
                        <div class="checkbox">
                            <label class="after">
                                <input type="checkbox" name="restore_saved_price" value=1' . ($request->request->get('restore_saved_price') ? ' checked' : null) . '> Remettre les prix sauvegardés
                            </label>
                        </div>
                    </div>
                </div>
                
            </fieldset>
            <fieldset class="text-center">
                <button name="action" value="preview" class="btn btn-info" type="submit">Prévisualiser les modifications</button>
                <button name="action" value="go" class="btn btn-success" type="submit" data-confirm="Voulez-vous vraiment modifier tous les exemplaires de la liste ?">Modifier tous les exemplaires</button>
            </fieldset>
        </form>
    ';

    return new Response($content);
};
