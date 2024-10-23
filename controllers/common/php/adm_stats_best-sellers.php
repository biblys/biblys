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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

return function (Request $request, CurrentSite $currentSite): Response
{
    $request->attributes->set("page_title", "Best-sellers");

    $list = null;
    $query = null;

    $year = $request->query->get("year");
    if (!empty($year)) $query .= " AND `stock_selling_date` LIKE '" . $year . "%'";
    else $query .= " AND `stock_selling_date` IS NOT null ";

    $type = $request->query->get("type");
    if (!empty($type)) $query .= " AND `type_id` = " . $type;

    $query = "SELECT `article_title`, `article_url`, GROUP_CONCAT(DISTINCT `article_id`) AS `ids`, COUNT(`stock_id`) AS `Ventes`, SUM(`stock_selling_price`) AS `CA`, GROUP_CONCAT(DISTINCT `article_publisher` SEPARATOR ', ') AS `publishers`
    FROM `stock`
    JOIN `articles` USING(`article_id`)
    WHERE `stock`.`site_id` = " . $currentSite->getId() . " AND `stock_selling_price` != 0 " . $query . "
    GROUP BY `article_item`, IF (`article_item` IS null, `article_id`, null), `article_title`, `article_url`
    HAVING COUNT(`stock_id`) >= 3
    ORDER BY `Ventes` DESC, `CA`";

    $sql = EntityManager::prepareAndExecute($query, []);

    $i = 0;
    $total = 0;
    while ($x = $sql->fetch(PDO::FETCH_ASSOC)) {
        $i++;
        if ($currentSite->getSite()->getTva()) $x["CA"] = $x["CA"] / 1.055;
        $list .= '
            <tr>
                <td class="right">' . $i . '.</td>
                <td><a href="/a/' . $x["article_url"] . '">' . $x["article_title"] . '</a></td>
                <td>' . $x["publishers"] . '</td>
                <td class="right">' . $x["Ventes"] . '</td>
                <td class="right">' . price($x["CA"], 'EUR') . '</td>
            </tr>
        ';
        $total += $x["CA"];
    }

    $years = null;
    for ($y = date('Y'); $y >= 2010; $y--) {
        if (isset($year) && $y == $year) $sel = 'selected';
        else $sel = null;
        $years .= '<option value="' . $y . '" ' . $sel . '>' . $y . '</option>';
    }

    $type_options = \Biblys\Data\ArticleType::getOptions($request->query->get('type'));

    if ($currentSite->getSite()->getTva()) $HT = 'HT'; else $HT = 'TTC';

    $content = '

        <h1><span class="fa fa-sort-amount-desc"></span> Best-sellers</h1>

        <form class="fieldset">
            <fieldset>
                <legend>Filtres</legend>

                <p>
                    <label for="year">Année :</label>
                    <select name="year" id="year">
                        <option value="0">Cumul</option>
                        ' . $years . '
                    </select>
                </p>

                <p>
                    <label for="type">Type :</label>
                    <select name="type" id="type">
                        <option value="0">Tous</option>
                        ' . join($type_options) . '
                    </select>
                </p>

                <p class="center">
                    <button>Afficher</button>
                </p>

            </fieldset>
        </form>

        <table class="sortable admin-table">
            <thead class="pointer">
                <tr>
                    <th></th>
                    <th>Titre</th>
                    <th>Collections</th>
                    <th>Ventes</th>
                    <th>CA ' . $HT . '</th>
                </tr>
            </thead>
            <tbody>
                ' . $list . '
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=2></th>
                    <th colspan=2>Total ' . $HT . ' :</th>
                    <th>' . price($total, 'EUR') . '</th>
                </tr>
            </tfoot>
        </table>
    ';

    return new Response($content);
};
