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


use Biblys\Legacy\LegacyCodeHelper;

$cm = new CollectionManager();

if ($collection = $cm->getById($_GET['collection_id'])) {

    $query = "SELECT `articles`.`article_id`, `article_title`, `article_url`, `article_ean`,
        (SELECT `link_do_not_reorder` FROM `links` WHERE `articles`.`article_id` = `links`.`article_id` AND `link_do_not_reorder` = 1 AND `site_id` = '". LegacyCodeHelper::getGlobalSite()['site_id']."') AS `dnr`
        FROM `articles`
        WHERE `articles`.`collection_id` = '".$_GET["collection_id"]."' AND `type_id` != '2'";

    try
    {
        $articles = LegacyCodeHelper::getGlobalDatabaseConnection()->query($query);
        $articles = $articles->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e)
    {
        trigger_error($e->getMessage());
    }

    $collection_articles = array();

    foreach ($articles as $a) {

        $ventes = LegacyCodeHelper::getGlobalDatabaseConnection()->query("SELECT DATE_FORMAT(`stock_selling_date`,'%Y-%m-%d') AS `lastSale` FROM `stock` WHERE `article_id` = '".$a["article_id"]."' AND `stock_condition` = 'Neuf' AND `stock_selling_date` IS NOT NULL ORDER BY `stock_selling_date` DESC");
        $v = $ventes->fetch(PDO::FETCH_ASSOC);
        if ($v !== false) {
            $a["lastSale"] = $v["lastSale"];
        }
        $a["sales"] = $ventes->rowCount();

        if (!$a["sales"]) {
            continue;
        }

        $stock = LegacyCodeHelper::getGlobalDatabaseConnection()->query("SELECT `stock_id` FROM `stock` WHERE `stock`.`article_id` = '".$a["article_id"]."' AND `stock_condition` = 'Neuf' AND `stock`.`stock_selling_date` IS NULL AND `stock`.`stock_return_date` IS NULL AND `stock_lost_date` IS NULL");
        $a["stock"] = $stock->rowCount();

        $collection_articles[] = $a;
    }

    $result = array(
        'collection' => $collection->get('name'),
        'publisher' => $collection->get('publisher')->get('name'),
        'articles' => $collection_articles
    );

    echo json_encode($result, JSON_PRETTY_PRINT);

}
