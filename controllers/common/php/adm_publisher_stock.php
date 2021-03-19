<?php

$_JS_CALLS[] = "/common/js/adm_publisher_stock.js";
$_JS_CALLS[] = "/common/js/sorttable.js";

$_PAGE_TITLE = 'Gestion du stock';

$am = new ArticleManager();
$articles = $am->getAll(
    ["publisher_id" => $site->get("publisher_id")],
    ["order" => "article_pubdate", "sort" => "desc"]
);

// Minimal virtual stock
$minimum = 3;
$site_minimum = $site->getOpt('minimum_virtual_stock');
if ($site_minimum) {
    $minimum = $site_minimum;
}

$collections = [];
foreach ($articles as $article) {
    $td_class = null;

    if ($article->get("publisher_stock") <= $minimum) {
        $td_class = " alert";
    }

    if ($article->isSoldout() || !$article->isPhysical()) {
        continue;
    }

    if (!$article->isPublished() && !$article->isPreorderable()) {
        continue;
    }

    $collection = $article->get("collection")->get("name");
    if (!array_key_exists($collection, $collections)) {
        $collections[$collection] = null;
    }

    $collections[$collection] .= '
            <tr id="article_' . $article->get("id") . '">
                <td sorttable_customkey="' . $article->get("title_alphabetic") . '"><a href="/' . $article->get("url") . '">' . $article->get("title") . '</a></td>
                <td sorttable_customkey="' . $article->get("authors_alphabetic") . '">' . authors($article->get("authors"), 'url') . '</td>
                <td class="right stock' . $td_class . '" data-title="' . $article->get("title") . '" data-id="' . $article->get("id") . '" data-stock="' . $article->get("publisher_stock") . '" contenteditable>' . $article->get("publisher_stock") . '</td>
            </tr>
        ';
}

$_ECHO .= '<h2>' . $_PAGE_TITLE . '</h2>';

foreach ($collections as $collection => $articles) {
    $_ECHO .= '
            <h3>' . $collection . '</h3>
            <table class="sortable admin-table publisher_stock" cellpadding=0 cellspacing=0>
                <thead class="pointer">
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $articles . '
                </tbody>
            </table>
        ';
}
