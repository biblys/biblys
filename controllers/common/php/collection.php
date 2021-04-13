<?php

use Symfony\Component\HttpFoundation\RedirectResponse;

$cm = new CollectionManager();

/** @var $request */
$collectionUrl = $request->query->get("url");
$collection = $cm->get(["collection_url" => $collectionUrl]);

if ($collection) {
    $c = $collection;

    /** @var Site $site */
    $use_old_controller = $site->getOpt('use_old_collection_controller');
    if (!$use_old_controller) {
        return new RedirectResponse('/collection/'.$c['collection_url'], 301);
    }

    $_PAGE_TITLE = 'Collection &laquo;&nbsp;'.$c["collection_name"].'&nbsp;&raquo;';

    if ($site->has("publisher")) {
        $_PAGE_TITLE .= ' ('.$c["collection_publisher"].')';
    }

    $_ECHO .= '
        <h2>Collection &laquo;&nbsp;'.$c["collection_name"].'&nbsp;&raquo;</h2>
        <span class="hidden" id="search_terms">collection:'.$c["collection_id"].'</span>
    ';

    if (auth("admin")) {
        /** @var $urlgenerator */
        $_ECHO .= '
            <div class="admin">
                <p>Collection n&deg; '.$c["collection_id"].'</p>
                <p><a href="'.$urlgenerator->generate('collection_edit', ['id' => $c['collection_id']]).'">modifier</a></p>
        ';
        if ($site->has("shop")) {
            $_ECHO .= '<p><a href="/pages/adm_stocks?collection_id='.$c["collection_id"].'">stock</a></p>';
        }

        if (!empty($c["pricegrid_id"])) {
            $_ECHO .= '<p><a href="/pages/adm_prices?pricegrid_id='.$c["pricegrid_id"].'">grille de prix</a></p>';
            $_ECHO .= '<p><a href="/pages/adm_catprices?id='.$c["pricegrid_id"].'">catégorie</a></p>';
        }
        $_ECHO .= '
            </div>
        ';
    }

    if (!empty($c["collection_desc"])) {
        $_ECHO .= '<p>'.$c["collection_desc"].'</p>';
    }

    if (auth("admin")) {
        $_ECHO .= '
        <div class="admin">
            <p>Collection n&deg; '.$c["collection_id"].'</p>
            <p><a href="/admin/collection/'.$c["collection_id"].'/edit">modifier</a></p>
    ';
        if ($site->has("shop")) {
            $_ECHO .= '<p><a href="/pages/adm_stocks?collection_id='.$c["collection_id"].'">stock</a></p>';
        }
        if (!empty($c["pricegrid_id"])) {
            $_ECHO .= '<p><a href="/pages/adm_prices?pricegrid_id='.$c["pricegrid_id"].'">grille de prix</a></p>';
            $_ECHO .= '<p><a href="/pages/adm_catprices?id='.$c["pricegrid_id"].'">catégorie</a></p>';
        }
        $_ECHO .= '
        </div>
    ';
    }

    $_REQ = "`collection_id` = '".$c["collection_id"]."'";

    // Trier par numero de collection
    $defaultOrderBy = 'article_number';
    $defaultSortOrder = 0;
    $_REQ_ORDER = 'ORDER BY `article_number`, `article_pubdate`';

    $path = get_controller_path('_list');
    include($path);
} else {
    e404(sprintf("Collection %s not found", $collectionUrl));
}
