<?php

/** @noinspection PhpUnhandledExceptionInspection */

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentSite;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

$cm = new CollectionManager();

/** @var $request */
$collectionUrl = $request->query->get("url");
$collection = $cm->get(["collection_url" => $collectionUrl]);

$content = '';

if ($collection) {
    $c = $collection;

    /** @var CurrentSite $currentSite */
    $use_old_controller = $currentSite->getOption('use_old_collection_controller');
    if (!$use_old_controller) {
        return new RedirectResponse('/collection/'.$c['collection_url'], 301);
    }

    $pageTitle = 'Collection &laquo;&nbsp;'.$c["collection_name"].'&nbsp;&raquo;';
    if (LegacyCodeHelper::getGlobalSite()->has("publisher")) {
        $pageTitle .= ' ('.$c["collection_publisher"].')';
    }
    $request->attributes->set("page_title", $pageTitle);

    $content .= '
        <h2>Collection &laquo;&nbsp;'.$c["collection_name"].'&nbsp;&raquo;</h2>
        <span class="hidden" id="search_terms">collection:'.$c["collection_id"].'</span>
    ';

    if (LegacyCodeHelper::getGlobalVisitor()->isAdmin()) {
        /** @var $urlgenerator */
        $content .= '
            <div class="admin">
                <p>Collection n&deg; '.$c["collection_id"].'</p>
                <p><a href="'.$urlgenerator->generate('collection_edit', ['id' => $c['collection_id']]).'">modifier</a></p>
        ';
        if (LegacyCodeHelper::getGlobalSite()->has("shop")) {
            $content .= '<p><a href="/pages/adm_stocks?collection_id='.$c["collection_id"].'">stock</a></p>';
        }

        if (!empty($c["pricegrid_id"])) {
            $content .= '<p><a href="/pages/adm_prices?pricegrid_id='.$c["pricegrid_id"].'">grille de prix</a></p>';
            $content .= '<p><a href="/pages/adm_catprices?id='.$c["pricegrid_id"].'">catégorie</a></p>';
        }
        $content .= '
            </div>
        ';
    }

    if (!empty($c["collection_desc"])) {
        $content .= '<p>'.$c["collection_desc"].'</p>';
    }

    $_REQ = "`collection_id` = '".$c["collection_id"]."'";

    // Trier par numero de collection
    $defaultOrderBy = 'article_number';
    $defaultSortOrder = 0;
    $_REQ_ORDER = 'ORDER BY `article_number`, `article_pubdate`';

    $path = get_controller_path('_list');
    $content .= include($path);
} else {
    e404(sprintf("Collection %s not found", $collectionUrl));
}

return new Response($content);
