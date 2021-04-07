<?php

use Biblys\Isbn\Isbn;

$lm = new LinkManager();

$query = $request->query->get("query");
$query = $request->request->get("query", $query);
$listId = $request->request->get("list_id");
$stockId = $request->request->get("stock_id");
$del = $request->request->get("del");

if ($query) {
    $req = null;
    $params = [];
    if (Isbn::isParsable($query)) {
        $req = "`article_ean` = :ean";
        $params["ean"] = Isbn::convertToEan13($query);
    } else {
        $qex = explode(" ", addslashes($query));
        $i = 0;
        foreach ($qex as $qexa) {
            if (isset($req)) {
                $req .= " AND ";
            }
            $req .= "(`article_keywords` LIKE :keyword$i)";
            $params["keyword$i"] = "%$qexa%";
            $i++;
        }
    }
    $req = "
        SELECT `article_id`, `article_title`, `article_authors`, `article_collection`, `article_number`,
            `stock_id`, `stock_selling_price`, `stock_condition`, `stock_stockage`
        FROM `articles`
        JOIN `stock` USING(`article_id`)
        WHERE ".$req." AND `stock`.`site_id` = :site_id
            AND `stock_selling_date` IS NULL
            AND `stock_return_date` IS NULL
            AND `stock_lost_date` IS NULL
    ";
    $params["site_id"] = $site->get("id");

    // EXEMPLAIRES
    $num = 0;
    $multiple = null;
    $articles = EntityManager::prepareAndExecute($req, $params);
    while ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
        if (media_exists('book', $a["article_id"])) {
            $a["article_cover"] = '<img src="'.media_url('book', $a["article_id"], "h60").'" />';
        } else {
            $a["article_cover"] = null;
        }

        if (!empty($a["stock_cart_date"])) {
            $a["led"] = "square_gray";
            $a["led_title"] = "En panier";
        } else {
            $a["led"] = "square_green";
            $a["led_title"] = "En stock";
        }

        // If item is already in a list, skip it
        $listed = $lm->get(["list_id" => $listId, "stock_id" => $a["stock_id"]]);
        if ($listed) {
            continue;
        }

        echo '
            <li class="choose clearL" onClick="addToList(\''.$a["stock_id"].'\',\''.addslashes($a["article_title"]).'\')">
                '.$a["article_cover"].'
                <img src="/common/img/'.$a["led"].'.png" width="8" height="8" title="'.$a["led_title"].'" /> <strong>'.$a["article_title"].'</strong><br />
                '.$a["article_authors"].'<br />
                '.$a["article_collection"].' '.numero($a["article_number"]).'<br />
                '.price($a["stock_selling_price"], 'EUR').' ('.$a["stock_condition"].')<br />
                Ref. '.$a["stock_id"].'<br />
                Empl. : '.$a["stock_stockage"].'<br />
                <div class="clearL"></div>
            </li>
        ';
        $num++;
        if (isset($multiple)) {
            $multiple .= '-';
        }
        $multiple .= $a["stock_id"];
    }

    // ARTICLES

    if ($num > 1) {
        echo '<li class="choose clearL" onClick="addMultipleToList(\''.$multiple.'\')"><strong>Ajouter les '.$num.' exemplaires à la liste</strong></li>';
    }
} elseif ($stockId) {
    $sm = new StockManager();
    $stock = $sm->getById($stockId);

    if ($stock) {
        // If item is already in a list, skip it
        $listed = $lm->get(["list_id" => $listId, "stock_id" => $stockId]);
        if (!$listed) {
            $lim = new LinkManager();
            $link = $lim->create(
                ["list_id" => $listId, "stock_id" => $stockId]
            );

            if (!empty($stock->get("return_date"))) {
                $led = "square_orange";
                $ledTitle = "Retourné";
            } elseif (!empty($stock->get("selling_date"))) {
                $led = "square_blue";
                $ledTitle = "Vendu";
            } elseif (!empty($stock->get("lost_date"))) {
                $led = "square_purple";
                $ledTitle = "Perdu";
            } elseif (!empty($stock->get("cart_date"))) {
                $led = "square_gray";
                $ledTitle = "En panier";
            } else {
                $led = "square_green";
                $ledTitle = "En stock";
            }

            $article = $stock->getArticle();

            echo '
                <tr id="link_'.$stock->get("link_id").'">
                    <td>
                        <img src="/common/img/'.$led.'.png" width="8" height="8"
                            title="'.$ledTitle.'">
                    </td>
                    <td>
                        <a href="/'.$article->get("url").'">
                            '.$article->get("title").'
                        </a>
                    </td>
                    <td>'.$article->get("collection")->get("name").'</td>
                    <td
                        data-price='.$article->get("selling_price").'
                        data-stock_id='.$stock->get("id").'
                        data-article_title="'.$article->get("title").'"
                        class="right pointer changePriceInList e">'
                            .price($stock->get("selling_price"), 'EUR').'
                    </td>
                    <td class="text-right">1</td>
                    <td class="text-right">
                        '.price($stock->get("selling_price"), 'EUR').'
                    </td>
                    <td style="width: 50px;">
                        <a href="/pages/adm_stock?id='.$stock->get("id").'">
                            <img src="/common/icons/edit.svg" width=16
                                alt="Modifier l\'exemplaire"
                                title="Modifier l\'exemplaire">
                        </a>
                        <img src="/common/icons/delete.svg" width=16 class="pointer"
                            alt="Retirer de la liste" title="Retirer de la liste"
                            onClick="delFromList('.$stock->get("link_id").')" />
                    </td>
                </tr>
            ';

        } else {
            echo 'ERROR > Cet exemplaire est déjà dans la liste !';
        }
    }

} elseif ($del) {
    $link = $lm->getById($del);
    $lm->delete($link);
}
