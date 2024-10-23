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


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

$sm = new StockManager();
$am = new ArticleManager();

$action = $request->query->get("action");

$stockId = $request->request->get("stock_id");

if ($action === "delete") {
    $item = $sm->getById($stockId);
    if (!$item) {
        throw new BadRequestHttpException("Exemplaire $stockId introuvable.");
    }

    $sm->delete($item);

    $response = new JsonResponse($item->getArticle()->countItemsByAvailability());
} elseif ($action === "update") {

    $stock = $sm->getById($stockId);
    if (!$stock) {
        trigger_error('Stock #'.$stock->get('id').' inexistant.');
    }

    $article = $stock->get('article');

    if($_POST["field"] == 'stock_condition') $field = 'État';
    elseif($_POST["field"] == 'stock_purchase_price') $field = 'Prix d\'achat';
    elseif($_POST["field"] == 'stock_selling_price') $field = 'Prix de vente';
    elseif($_POST["field"] == 'stock_invoice') $field = 'Facture';
    elseif($_POST["field"] == 'stock_depot') $field = 'Dépôt';

    // Also update article_weight if field is stock_weight
    $article_weight = 0;
    if ($_POST["field"] == 'stock_weight' && !empty($_POST["value"])) {
        $article->set('article_weight', $_POST['value']);
        $am->update($article);
        $article_weight = 1;
        $field = 'Poids';
    }

    $stock->set($_POST['field'], $_POST['value']);
    $sm->update($stock);

    $response = new JsonResponse([
        'field_name' => $field,
        'stock_id' => $_POST['stock_id'],
        'article_title' => $article->get('title'),
        'article_weight' => $article_weight
    ]);
}

$response->send();