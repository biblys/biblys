<?php

    use Biblys\Isbn\Isbn as Isbn;

    $am = new ArticleManager();

    if (BIBLYS_VERSION < 2) {
        echo '<script type="text/javascript" src="/common/js/adm_publisher_stock.js?'.BIBLYS_VERSION.'.'.$_SITE["site_version"].'"></script>';
    } else {
        $_JS_CALLS[] = "/common/js/adm_publisher_stock.js";
    }

    $_JS_CALLS[] = "/common/js/sorttable.js";

    if ($_SITE["site_publisher"] && $_V->isAdmin()) {
        $_REQ = "`publisher_id` = '".$_SITE["publisher_id"]."'";
    } elseif ($_V->isPublisher()) {
        $_REQ = "`publisher_id` = '".$_V->getCurrentRight()->get('publisher_id')."'";
    } else {
        trigger_error('Vous n\'avez pas le droit d\'accéder à cette page.');
    }

    $query = "SELECT `articles`.`article_id`, `article_title`, `article_preorder`, `article_title_alphabetic`, `article_url`, `article_authors`, `article_authors_alphabetic`, `article_collection`, `article_publisher`, `article_ean`, `article_pubdate`, `article_availability`,
            GROUP_CONCAT(`file_ean`) AS `eans`
        FROM `articles`
        LEFT JOIN `files` ON `files`.`article_id` = `articles`.`article_id`
        WHERE ".$_REQ."
        GROUP BY `articles`.`article_id`
        ORDER BY `collection_id`, `article_pubdate` DESC";

    $articles = $_SQL->query($query);

    $L = array();
    while ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
        $article = $am->getById($a["article_id"]);

        // Default availability
        $a['cart'] = false;
        $a["dispo"] = '<img src="/common/img/square_red.png" title="Épuisé" alt="Épuisé">';

        // Will soon be unavailable
        if ($article->isSoonUnavailable()) {
            $a["dispo"] = '<img src="/common/img/square_orange.png" title="Bientôt épuisé" alt="Bientôt épuisé">';
            $a["cart"] = 1;

        // Can be preordered
        } elseif (!$article->isPublished() && $article->isPreorderable()) {
            $a["dispo"] = '<img src="/common/img/square_blue.png" title="En précommande" alt="En précommande">';
            $a["cart"] = 1;

        // To be published but not preorederable
        } elseif (!$article->isPublished()) {
            $a["dispo"] = '<img src="/common/img/square_blue.png" title="À paraître" alt="À paraître">';

        // Is available
        } elseif ($article->isAvailable()) {
            $a["dispo"] = '<img src="/common/img/square_green.png" title="Disponible" alt="Disponible">';
            $a["cart"] = 1;
        }

        if (!empty($a['eans'])) {
            $a['isbn'] = array();
            $eans = explode(',', $a['eans']);
            foreach ($eans as $ean) {
                $a['isbn'][] = Isbn::convertToIsbn13($ean);
            }
            $a['isbn'] = implode('<br>', $a['isbn']);
        } elseif (!empty($a["article_ean"])) {
            $a["isbn"] = Isbn::convertToIsbn13($a["article_ean"]);
        } elseif (!empty($a["article_pdf_ean"])) {
            $a["isbn"] = Isbn::convertToIsbn13($a["article_pdf_ean"]);
        } elseif (!empty($a["article_epub_ean"])) {
            $a["isbn"] = Isbn::convertToIsbn13($a["article_epub_ean"]);
        } else {
            $a['isbn'] = null;
        }

        if (!isset($L[$a["article_collection"]])) {
            $L[$a["article_collection"]] = null;
        }

        $L[$a["article_collection"]] .= '
            <tr id="article_'.$a["article_id"].'">
                <td>'.$a["dispo"].'</td>
                <td sorttable_customkey="'.$a["article_title_alphabetic"].'"><a href="/'.$a["article_url"].'">'.$a["article_title"].'</a></td>
                <td sorttable_customkey="'.$a["article_ean"].'">'.$a["isbn"].'</td>
                <td sorttable_customkey="'.$a["article_pubdate"].'">'._date($a["article_pubdate"], 'd/m/Y').'</td>
                <td>
                    <a href="/pages/log_article?id='.$a["article_id"].'" class="btn btn-default"><i class="fa fa-edit"></i> Éditer</a>
                </td>
            </tr>
        ';
        $publisher = $a["article_publisher"];
    }

    $_PAGE_TITLE = 'Catalogue '.$publisher;

    $_ECHO .= '<h1><img src="/common/icons/pub_articles.svg" alt="'.$_PAGE_TITLE.'" width=32> '.$_PAGE_TITLE.'</h1>';


    foreach ($L as $k => $v) {
        $_ECHO .= '
            <h2>'.$k.'</h2>
            <table class="sortable admin-table publisher_stock" cellpadding=0 cellspacing=0>
                <thead class="pointer">
                    <tr>
                        <th></th>
                        <th>Titre</th>
                        <th>ISBN</th>
                        <th title="Date de parution">Parution</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    '.$v.'
                </tbody>
            </table>
        ';
    }
