<?php

use Biblys\Legacy\LegacyCodeHelper;

$cm = new CollectionManager();

if ($collection = $cm->getById($_GET['collection_id'])) {

    $query = "SELECT `articles`.`article_id`, `article_title`, `article_url`, `article_ean`,
        (SELECT `link_do_not_reorder` FROM `links` WHERE `articles`.`article_id` = `links`.`article_id` AND `link_do_not_reorder` = 1 AND `site_id` = '". LegacyCodeHelper::getGlobalSite()['site_id']."') AS `dnr`
        FROM `articles`
        WHERE `articles`.`collection_id` = '".$_GET["collection_id"]."' AND `type_id` != '2'";

    try
    {
        $articles = \Biblys\Legacy\LegacyCodeHelper::getGlobalDatabaseConnection()->query($query);
        $articles = $articles->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e)
    {
        trigger_error($e->getMessage());
    }

    $collection_articles = array();

    foreach ($articles as $a) {

        $ventes = \Biblys\Legacy\LegacyCodeHelper::getGlobalDatabaseConnection()->query("SELECT DATE_FORMAT(`stock_selling_date`,'%Y-%m-%d') AS `lastSale` FROM `stock` WHERE `article_id` = '".$a["article_id"]."' AND `site_id` = '". LegacyCodeHelper::getGlobalSite()["site_id"]."' AND `stock_condition` = 'Neuf' AND `stock_selling_date` IS NOT NULL ORDER BY `stock_selling_date` DESC");
        $v = $ventes->fetch(PDO::FETCH_ASSOC);
        $a["lastSale"] = $v["lastSale"];
        $a["sales"] = $ventes->rowCount();

        if (!$a["sales"]) {
            continue;
        }

        $stock = \Biblys\Legacy\LegacyCodeHelper::getGlobalDatabaseConnection()->query("SELECT `stock_id` FROM `stock` WHERE `stock`.`article_id` = '".$a["article_id"]."' AND `stock`.`site_id` = '". LegacyCodeHelper::getGlobalSite()["site_id"]."' AND `stock_condition` = 'Neuf' AND `stock`.`stock_selling_date` IS NULL AND `stock`.`stock_return_date` IS NULL AND `stock_lost_date` IS NULL");
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
