<?php /** @noinspection PhpUnhandledExceptionInspection */
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @throws InvalidDateFormatException
 */
return function (Request $request, CurrentSite $currentSite): Response
{
    $request->attributes->set("page_title", "Stock détaillé");

    // REQUETE DES STOCK

    $_QUERY = null;

    // Filtrer par date
    if (!isset($_GET['date'])) {
        $_GET['date'] = date('Y-m-d');
    }
    $_QUERY .= ' AND `stock_purchase_date` <= :date
        AND (`stock_selling_date` IS null OR `stock_selling_date` > :date)
        AND (`stock_return_date` IS null OR `stock_return_date` > :date)
        AND (`stock_lost_date` IS null OR `stock_lost_date` > :date)
    ';
    $params['date'] = $_GET['date'] . ' 23:59:59';

    $rayonId = $request->query->get('rayon_id');
    if ($rayonId) {
        $_QUERY .= "AND `article_links` LIKE '%[rayon:" . $rayonId . "]%'";
    }

    // Filtrer par dépôt
    if (isset($_GET['stock_depot'])) {
        if ($_GET['stock_depot'] == 1) {
            $_QUERY .= ' AND `stock_depot` = 0';
        } elseif ($_GET['stock_depot'] == 2) {
            $_QUERY .= ' AND `stock_depot` = 1';
        }
    } else {
        $_GET['stock_depot'] = null;
    }

    $params["site_id"] = $currentSite->getSite()->getId();

    $stock = EntityManager::prepareAndExecute(
        'SELECT
        `article_title`, `article_ean`, `article_authors`, `article_url`, `article_tva`,
        `stock_id`, `stock_purchase_price`, `stock_selling_price`, 
        `stock_condition`, `stock_purchase_date`
    FROM `stock` AS `s`
    JOIN `articles` AS `a` USING(`article_id`)
    WHERE `s`.`site_id` = :site_id' . $_QUERY . '
    GROUP BY `s`.`stock_id`
    ORDER BY `stock_purchase_date`', $params
    );

    // Export to CSV
    $export = array();
    $header = array('Ref.', 'Titre', 'État', 'Prix d\'achat HT', 'Prix de vente TTC');

    $tbody = null;
    $stock_total = 0;
    $purchase_total = 0;
    $selling_total = 0;
    while ($s = $stock->fetch(PDO::FETCH_ASSOC)) {
        if ($s['stock_condition'] != 'Neuf') {
            $s['stock_condition'] = 'Occasion';
        }
        // Prix HT
        $stockPurchasePrice = $s['stock_purchase_price'] ?? 0;
        if ($currentSite->getSite()->getTva() == 1) {
            $s['tva_rate'] = tva_rate(
                    $s['article_tva'], $s["stock_purchase_date"]
                ) / 100;
            $s['ti'] = $s['tva_rate'] * 1000;
            $stockPurchasePrice
                = $stockPurchasePrice / (1 + $s['tva_rate']);
        }

        $tbody .= '
            <tr>
                <td>
                    <a href="/pages/adm_stock?id=' . $s['stock_id'] . '">
                        ' . $s['stock_id'] . '
                    </a>
                </td>
                <td>
                    '.$s["article_ean"].'
                </td>
                <td title="' . $s['article_authors'] . '">
                    <a href="/a/' . $s['article_url'] . '">' . $s['article_title'] . '</a>
                </td>
                <td>' . $s['stock_condition'] . '</td>
                <td class="right">' . price($stockPurchasePrice, 'EUR') . '</td>
                <td class="right">' . price($s['stock_selling_price'], 'EUR') . '</td>
            </tr>
        ';

        $stock_total++;
        $selling_total += $s["stock_selling_price"];
        $purchase_total += $stockPurchasePrice;

        $export[] = [
            $s['stock_id'],
            $s['article_title'],
            $s['stock_condition'],
            price(round($stockPurchasePrice, 2)),
            price($s['stock_selling_price'])
        ];
    }
    $stock->closeCursor();

    $content = '
        <h1><span class="fa fa-line-chart"></span> Stock détaillé</h1>
    
        <form class="fieldset">
            <fieldset>
                <legend>Options</legend>
    
                <p>
                    <label for="date">Au :</label>
                    <input type="date" name="date" id="date" placeholder="AAAA-MM-JJ" 
                        value="' . $_GET["date"] . '">
                </p>
    
                <p>
                    <label for="stock_depot">Dépots :</label>
                    <select name="stock_depot" id="stock_depot" required>
                        <option value="0">Tous</option>
                        <option value="1"' . (
        $_GET['stock_depot'] == 1 ? ' selected' : null
        ) . '>Sans les dépots</option>
                    <option value="2"' . (
        $_GET['stock_depot'] == 2 ? ' selected' : null
        ) . '>Dépots uniquement</option>
                        </select>
                </p>
    
                <p class="center">
                    <button type="submit">Afficher le stock</button>
                </p>
    
            </fieldset>
        </form>
    
        <h3>' . $stock_total . ' exemplaires en stock au ' . _date(
                $_GET['date'],
                'j f Y'
            ) . '</h3>

        <form action="/pages/export_to_csv" method="post">
            <fieldset class="right">
                <input type="hidden" name="filename" 
                    value="stock_' . $currentSite->getSite()->getName() . '_au_' . $_GET["date"] . '">
                <input type="hidden" name="header" 
                    value="' . htmlentities(json_encode($header)) . '">
                <input type="hidden" name="data" 
                    value="' . htmlentities(json_encode($export)) . '">
                <button type="submit">Télécharger au format CSV</button>
            </fieldset>
        </form>
        <br>
    
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Ref.</th>
                    <th>EAN</th>
                    <th>Titre</th>
                    <th>État</th>
                    <th title="Prix d\'achat HT">PdA HT</th>
                    <th title="Prix de vente TTC">PdV TTC</th>
                </tr>
            </thead>
            <tbody>
                ' . $tbody . '
            </tbody>
            <tfoot>
                <tr>
                    <th class="right" colspan="3">Total&nbsp;:</th>
                    <th class="right">' . price($purchase_total, "EUR") . '</th>
                    <th class="right">' . price($selling_total, "EUR") . '</th>
                </tr>
            </tfoot>
        </table>
    ';

    return new Response($content);
};
