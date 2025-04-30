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

/** @noinspection BadExpressionStatementJS */

use Biblys\Service\CurrentUser;
use Biblys\Service\Slug\SlugService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @throws Exception
 */
return function (
    CurrentUser $currentUser,
    Request     $request
): Response|RedirectResponse {
    $lm = new ListeManager();
    $sm = new StockManager();
    $linkManager = new LinkManager();

    $request->attributes->set("page_title", "Listes");

    $content = '<h1><span class="fa fa-list"></span> Listes</h1>';

    $newList = $request->request->get('new_list');
    if ($newList) {
        /** @var Liste $list */
        $list = $lm->create(['user_id' => $currentUser->getUser()->getId()]);

        $listUrl = $request->request->get('list_url', false);
        if (!$listUrl) {
            $slugService = new SlugService();
            $listUrl = $slugService->slugify($newList);
        }

        // Append id to url if it already exists
        $listWithSameUrl = $lm->get(['list_url' => $listUrl]);
        if ($listWithSameUrl) {
            $listUrl = '_' . $list->get('id');
        }

        $list->set('list_title', $newList);
        $list->set('list_url', $listUrl);
        $lm->update($list);

        return new RedirectResponse("/pages/adm_list?list=$listUrl");
    }

    $content .= '
        <label>Mes listes :</label>
        <select class="goto">
            <option />
    ';

    $lm = new ListeManager();
    $lists = $lm->getAll([], ['order' => 'list_title']);

    foreach ($lists as $list) {
        $content .= '<option value="/pages/adm_list?list=' . $list->get('url') . '">' . $list->get('title') . '</option>';
    }

    $content .= '
        </select>

        <form method="post" class="mb-4">
            <fieldset>
                <label for="new_list">Nouvelle liste :</label>
                <input type="text" name="new_list" id="new_list" autofocus>
                <button class="btn btn-primary" type="submit">Créer</button>
            </fieldset>
        </form>
    ';

    if (isset($_GET['emptied'])) {
        $content .= '<p class="success">La liste a bien été vidée</p>';
    }
    if (isset($_GET['deleted'])) {
        $content .= '<p class="success">La liste a bien été supprimée</p>';
    }
    if (isset($_GET['returned'])) {
        $content .= '<p class="success">Les exemplaires de la liste ont bien été retournés</p>';
    }

    $slug = $request->query->get('list');
    $list = $lm->get(['list_url' => $slug]);
    if ($list) {
        $l = $list;

        if ($currentUser->isAdmin()) {
            $content .= '
                <div class="admin">
                    Liste n° ' . $list->get('id') . '
                </div>
            ';
        }

        $action = $request->query->get('action');
        if ($action == 'delete') {
            $links = $linkManager->getAll(['list_id' => $list->get('id')]);
            foreach ($links as $link) {
                $linkManager->delete($link);
            }
            $lm->delete($list);
            return new RedirectResponse('/pages/pages/adm_list?deleted=1');
        }

        $Total = 0;
        $TotalPrice = 0;
        $export = [];
        $articles_in_list = null;
        $articles = EntityManager::prepareAndExecute('
            SELECT 
               `link_id`, `stock_id`, 
                "1" AS `count`, "0" AS `total`,
               `stock`.`article_id`, `article_title`, `article_ean`, `article_url`, `article_collection`, `article_publisher`, 
                `stock_selling_price`, `stock_return_date`, `stock_selling_date`, `stock_cart_date`, `stock_lost_date`, `stock_stockage`
            FROM `links`
            JOIN `stock` USING(`stock_id`)
            JOIN `articles` ON `stock`.`article_id` = `articles`.`article_id`
            WHERE `list_id` = :list_id
            ORDER BY `link_id` DESC',
            ["list_id" => $list->get('id')]
        );

        while ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
            if (isset($_GET['action']) && $_GET['action'] == 'return' && $currentUser->isAdmin()) {
                /** @var Stock $stock */
                $stock = $sm->getById($a['stock_id']);
                $stock->setReturned();
                $sm->update($stock);
                $a['led'] = 'square_orange';
            }

            $copy = new Stock($a);

            /** @noinspection JSVoidFunctionReturnValueUsed */
            $articles_in_list .= '
                <tr id="link_' . $a['link_id'] . '">
                    <td class="right">
                        ' . $copy->getAvailabilityDot() . '
                    </td>
                    <td><a href="/a/' . $a['article_url'] . '">' . $a['article_title'] . '</a></td>
                    <td>' . $a['article_collection'] . '</td>
                    <td data-price=' . $a['stock_selling_price'] . ' data-stock_id=' . $a['stock_id'] . ' data-article_id=' . $a['article_id'] . ' data-article_title="' . $a['article_title'] . '" class="right pointer changePriceInList e">' . price($a['stock_selling_price'], 'EUR') . '</td>
                    <td class="text-right">' . $a['count'] . '</td>
                    <td class="text-right">' . price($a['total'], 'EUR') . '</td>
                    <td class="center nowrap">
                        <a class="btn btn-primary btn-sm" href="/pages/adm_stock?id=' . $a['stock_id'] . '" title="Modifier l\'exemplaire"><i class="fa-solid fa-edit"></i></a>
                        <a class="btn btn-danger btn-sm" onClick="delFromList(' . $a['link_id'] . ')"><i class="fa-solid fa-xmark" class="pointer" title="Retirer de la liste"></i></a>
                    </td>
                </tr>
            ';

            $export[] = [$a['article_publisher'], $a['article_title'], $a['article_ean'], price($a['stock_selling_price']), $a['count'], price($a['total'])];

            ++$Total;
            $TotalPrice += $a['stock_selling_price'];
        }

        if (isset($_GET['action']) && $_GET['action'] == 'return' and $currentUser->isAdmin()) {
            return new RedirectResponse('/pages/adm_list?list=' . $l['list_url'] . '?returned=1');
        }

        $request->attributes->set("page_title", $list->get("title"));
        $content .= '
            <h2><a href="/pages/adm_list?list=' . $l['list_url'] . '">' . $list->get("title") . '</a></h2>

            <label for="list">Ajouter &agrave; la liste :</label>
            <input 
                type="text" 
                name="list" 
                id="list" 
                class="autocomplete event verylong" 
                placeholder="Titre, auteur, éditeur, ISBN..." 
                data-list_id="' . $l['list_id'] . '" 
                data-autocomplete-url="/pages/list_xhr"
                autofocus 
            />
            <input type="hidden" id="var1" value="' . $l['list_id'] . '" />
            <input type="hidden" id="list_id" value="' . $l['list_id'] . '" />
            <br><br>

            <form action="/pages/export_to_csv" method="post">
                <fieldset class="center">
                    <input type="hidden" name="filename" value="' . $l['list_url'] . '">
                    <input type="hidden" name="data" value="' . htmlentities(json_encode($export)) . '">

                    <button class="btn btn-primary" type="submit" title="Télécharger la liste au format CSV"><i class="fa fa-cloud-download"></i> télécharger</button>
                    <a href="/pages/adm_list?list=' . $list->get('url') . '&regroup=1" class="btn btn-info" title="Regrouper les exemplaire par article"><i class="fa fa-compress"></i> regrouper</a>
                    <a href="/pages/adm_stock_bulk?list_id=' . $list->get('id') . '" class="btn btn-success" title="Éditer chaque exemplaire de la liste"><i class="fa fa-edit"></i> éditer tout</a>
                    <a href="/pages/adm_list?list=' . $list->get('url') . '&action=return" class="btn btn-warning" data-confirm="Voulez-vous vraiment RETOURNER tous les exemplaires de la liste ?" title="Retourner tous les exemplaires de la liste"><i class="fa fa-hand-point-left"></i> retourner</a>
                    <a href="/pages/adm_list?list=' . $list->get('url') . '&action=delete" class="btn btn-danger" data-confirm="Voulez-vous vraiment VIDER et SUPPRIMER cette liste ?" title="Supprimer la liste"><i class="fa fa-trash-can"></i> supprimer</a>
                </fieldset>
            </form>
            <br>

            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Titre</th>
                        <th>Collection</th>
                        <th>Prix</th>
                        <th>Qté</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    ' . $articles_in_list . '
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-center">Total : ' . $Total . ' ex. | ' . price($TotalPrice, 'EUR') . '</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <form action="/pages/export_to_csv" method="post" class="my-4">
                <fieldset class="center">
                    <input type="hidden" name="filename" value="' . $l['list_url'] . '">
                    <input type="hidden" name="data" value="' . htmlentities(json_encode($export)) . '">

                    <button class="btn btn-primary" type="submit" title="Télécharger la liste au format CSV"><i class="fa fa-cloud-download"></i> télécharger</button>
                    <a href="/pages/adm_list?list=' . $list->get('url') . '&regroup=1" class="btn btn-info" title="Regrouper les exemplaire par article"><i class="fa fa-compress"></i> regrouper</a>
                    <a href="/pages/adm_stock_bulk?list_id=' . $list->get('id') . '" class="btn btn-success" title="Éditer chaque exemplaire de la liste"><i class="fa fa-edit"></i> éditer tout</a>
                    <a href="/pages/adm_list?list=' . $list->get('url') . '&action=return" class="btn btn-warning" data-confirm="Voulez-vous vraiment RETOURNER tous les exemplaires de la liste ?" title="Retourner tous les exemplaires de la liste"><i class="fa fa-hand-point-left"></i> retourner</a>
                    <a href="/pages/adm_list?list=' . $list->get('url') . '&action=delete" class="btn btn-danger" data-confirm="Voulez-vous vraiment VIDER et SUPPRIMER cette liste ?" title="Supprimer la liste"><i class="fa fa-trash-can"></i> supprimer</a>
                </fieldset>
            </form>
        ';
    }

    return new Response($content);
};
