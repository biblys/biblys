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


use Biblys\Data\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @throws Exception
 */
return function (Request $request): Response
{
    $request->attributes->set("page_title", "Ventes par article en stock");

    // FILTRES
    $last30daysOptions = _getDatesOptions("%Y-%m-%d", "l j f", "d");
    $months = _getDatesOptions("%Y-%m", "F Y", "m");
    $years = _getDatesOptions("%Y", "Y", "y");

    // Affichage par défaut : ventes du jour courant
    if (empty($_GET["date1"]) && empty($_GET["d"]) && empty($_GET["m"]) && empty($_GET["y"])) $_GET["d"] = date("Y-m-d");

    // Raccourcis mois ou jour
    /** @noinspection DuplicatedCode */
    if (!empty($_GET["d"])) {
        $_GET['date1'] = $_GET['d'];
        $_GET['date2'] = $_GET['d'];
        $_GET['time1'] = '00:00';
        $_GET['time2'] = '23:59';
    } elseif (!empty($_GET["m"])) {
        $_GET['date1'] = $_GET['m'] . '-01';
        $_GET['date2'] = $_GET['m'] . '-' . date('t', strtotime($_GET['m']));
        $_GET['time1'] = '00:00';
        $_GET['time2'] = '23:59';
    } elseif (isset($_GET["y"])) {
        $_GET['date1'] = $_GET['y'] . '-01-01';
        $_GET['date2'] = $_GET['y'] . '-12-31';
        $_GET['time1'] = '00:00';
        $_GET['time2'] = '23:59';
    }

    // REQUÊTE DES VENTES
    $params = [];
    $_QUERY = null;

    // Filtrer par date
    if (!empty($_GET["date1"])) {
        // Les livres sont en stock entre date1 et date2 si :
        $_QUERY .= ' AND `stock_purchase_date` < :date_2'; // Ils ont été achetés avant date2

        $params['date_1'] = $_GET['date1'] . ' ' . $_GET['time1'] . ':00';
        $params['date_2'] = $_GET['date2'] . ' ' . $_GET['time2'] . ':00';
    }

    // Filtrer par type de livre
    if (!empty($_GET["type_id"])) {
        $_QUERY .= ' AND `type_id` = :type_id';
        $params['type_id'] = $_GET['type_id'];
    }

    $stock = EntityManager::prepareAndExecute('SELECT
        `article_title`, `article_url`, `article_authors`, `article_collection`, `article_publisher`,
        COUNT(`stock_id`) AS `stock2`,
        SUM(IF((`stock_selling_date` IS null OR `stock_selling_date` > :date_1) AND
        (`stock_return_date` IS null OR `stock_return_date` > :date_1) AND
        (`stock_lost_date` IS null OR `stock_lost_date` > :date_1), 1, 0)) AS `stock`,
        SUM(IF(`stock_selling_date` BETWEEN :date_1 AND :date_2, 1, 0)) AS `sales`,
        SUM(IF(`stock_selling_date` BETWEEN :date_1 AND :date_2, `stock_selling_price`, 0)) AS `revenue`
        FROM `stock` AS `s`
        JOIN `articles` AS `a` ON `a`.`article_id` = `s`.`article_id`
        WHERE 1 ' . $_QUERY . '
        GROUP BY `a`.`article_id`
        ORDER BY `sales` DESC', $params);

    // Types
    $type_options = ArticleType::getOptions($request->query->get('type_id'));

    $tbody = null;
    $total_articles = 0;
    $total_stock = null;
    $total_sales = null;
    $total_revenue = null;
    while ($s = $stock->fetch(PDO::FETCH_ASSOC)) {
        if ($s['stock']) {
            $tbody .= '
                <tr>
                    <td title="' . $s['article_authors'] . '"><a href="/a/' . $s['article_url'] . '">' . $s['article_title'] . '</a></td>
                    <td title="' . $s['article_publisher'] . '">' . $s['article_collection'] . '</a></td>
                    <td class="right">' . $s['stock'] . '</td>
                    <td class="right">' . $s['sales'] . '</td>
                    <td class="right">' . price($s['revenue'], 'EUR') . '</td>
                </tr>
            ';

            $total_articles++;
            $total_stock += $s["stock"];
            $total_sales += $s["sales"];
            $total_revenue += $s["revenue"];
        }
    }
    $stock->closeCursor();

    $content = '
        <a href="/pages/adm_orders_shop">Revenir à l\'interface actuelle</a>
        <h1>
          <i class="fa-solid fa-book"></i>
          Ventes par article en stock 
        </h1>

        <p>
            <label for="d">Raccourcis :</label>
            <select name="d" class="goto">
                <option>30 derniers jours...</option>
                ' . join($last30daysOptions) . '
            </select>

            <select name="m" class="goto">
                <option>Mois de...</option>
                ' . join($months) . '
            </select>

            <select name="y" class="goto">
                <option>Année...</option>
                ' . join($years) . '
            </select>
        </p>

        <form class="fieldset">
            <fieldset>
                <legend>Filtres</legend>

                <p>
                    <label for="date1">Du :</label>
                    <input type="date" name="date1" id="date1" placeholder="AAAA-MM-JJ" value="' . $_GET["date1"] . '"> &agrave;
                    <input type="time" name="time1" id="time1" placeholder="HH:SS" value="' . $_GET["time1"] . '">
                </p>

                <p>
                    <label for="date2">Au :</label>
                    <input type="date" name="date2" id="date2" placeholder="AAAA-MM-JJ" value="' . $_GET["date2"] . '"> &agrave;
                    <input type="time" name="time2" id="time2" placeholder="HH:SS" value="' . $_GET["time2"] . '">
                </p>

                <p>
                    <label for="type_id">Type :</label>
                    <select name="type_id" id="type_id" required>
                        <option value="0"></option>
                        ' . join($type_options) . '
                     </select>
                </p>

                <p class="center">
                    <button type="submit">Afficher les ventes</button>
                </p>

            </fieldset>
        </form>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Collection</th>
                    <th>Stock</th>
                    <th>Ventes</th>
                    <th>C.A.</th>
                </tr>
            </thead>
            <tbody>
                ' . $tbody . '
            </tbody>
            <tfoot>
                <tr>
                    <th class="right">Total :</th>
                    <th>' . $total_articles . '</th>
                    <th>' . $total_stock . '</th>
                    <th>' . $total_sales . '</th>
                    <th class="right">' . price($total_revenue, 'EUR') . '</th>
                </tr>
            </tfoot>
        </table>
    ';

    return new Response($content);
};

/**
 * @return string[]
 * @throws Exception
 */
function _getDatesOptions(
    string $queryFormat,
    string $displayFormat,
    string $parameter
): array
{
    $datesQuery = EntityManager::prepareAndExecute("
    SELECT 
        DATE_FORMAT(`order_payment_date`, :format) AS `date`
    FROM `orders` 
    WHERE `order_cancel_date` IS null
        AND `order_payment_date` IS NOT NULL
    GROUP BY `date`
    ORDER BY `date` DESC
    LIMIT 30
", ["format" => $queryFormat]);
    return array_map(function ($date) use ($displayFormat, $parameter) {
        return '<option value="?'.$parameter.'='.$date["date"].'">'
            ._date($date["date"], $displayFormat).
            '</option>';
    }, $datesQuery->fetchAll());
}


