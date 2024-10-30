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


$active_stock_query = null;
$active_stock = $globalSite->getOpt("active_stock");
if ($active_stock) {
    $active_stock = "'".implode("','",explode(",", $active_stock))."'";
    $active_stock_query = " AND `stock_stockage` IN (".$active_stock.")";
}

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('En promotion');

$articles = $_SQL->prepare("SELECT `article_id`, `article_title`, `article_url`, `article_authors`, `article_collection`, `stock_id`, `stock_selling_price`, `stock_selling_price_saved`
    FROM `articles`
    JOIN `stock` USING(`article_id`)
    WHERE `stock`.`site_id` = :site_id AND `stock_selling_price` < `stock_selling_price_saved` AND `stock_selling_date` IS NULL AND `stock_return_date` IS NULL AND `stock_lost_date` IS NULL AND `type_id` != 7 ".$active_stock_query."
    ORDER BY `stock_selling_price`");
$articles->execute(['site_id' => $globalSite->get('id')]);

$num = 0;
$table = null;

while($l = $articles->fetch(PDO::FETCH_ASSOC)) {
    $table .= '
        <tr>
            <td><a href="/a/'.$l["article_url"].'">'.$l["article_title"].'</a></td>
            <td>'.authors($l["article_authors"]).'</td>
            <td>'.$l["article_collection"].'</td>
            <td class="right"><del>'.price($l["stock_selling_price_saved"],'EUR').'</del><br />'.price($l["stock_selling_price"],'EUR').'</td>
        </tr>
    ';
    $num++;
}

$_ECHO .= '
    <h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>

    <section class="center">
        '.share_buttons($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]).'
    </section>

    <div id="liste-num"><p>'.$num.' articles en promotion</p></div>
    <table class="table">
        <thead>
            <tr>
            <th>Titre</th>
            <th>Auteurs</th>
            <th>Collection</th>
            <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            '.$table.'
        </tbody>
    </table>';
