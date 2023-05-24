<?php

use Biblys\Isbn\Isbn as Isbn;
use Model\PublisherQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


if (!getLegacyVisitor()->isAdmin() && !getLegacyVisitor()->isPublisher()) {
    throw new AccessDeniedHttpException("Page réservée aux éditeurs.");
}

/** @var Site $site */
$publisherId = getLegacyVisitor()->getCurrentRight()->get("publisher_id");
if (!$site->allowsPublisherWithId($publisherId)) {
    $pm = new PublisherManager();
    throw new AccessDeniedHttpException("Votre maison d'édition n'est pas autorisée sur ce site.");
}

$am = new ArticleManager();

$content = "";

$publisherId = getLegacyVisitor()->getCurrentRight()->get('publisher_id');
$publisher = PublisherQuery::create()->findPk($publisherId);

$query = "SELECT `articles`.`article_id`, `article_title`, `article_preorder`, `article_title_alphabetic`, `article_url`, `article_authors`, `article_authors_alphabetic`, `article_collection`, `article_publisher`, `article_ean`, `article_pubdate`, `article_availability`,
            GROUP_CONCAT(`file_ean`) AS `eans`
        FROM `articles`
        LEFT JOIN `files` ON `files`.`article_id` = `articles`.`article_id`
        WHERE `publisher_id` = :publisher_id
        GROUP BY `articles`.`article_id`
        ORDER BY `collection_id`, `article_pubdate` DESC";

$articles = EntityManager::prepareAndExecute($query, ["publisher_id" => $publisher->getId()]);

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
                <td><a href="/'.$a["article_url"].'">'.$a["article_title"].'</a></td>
                <td>'.$a["isbn"].'</td>
                <td>'._date($a["article_pubdate"], 'd/m/Y').'</td>
                <td>
                    <a href="/pages/log_article?id='.$a["article_id"].'" class="btn btn-default"><i class="fa fa-edit"></i> Éditer</a>
                </td>
            </tr>
        ';
}

$_PAGE_TITLE = 'Catalogue '.$publisher->getName();

$content .= '<h1><img src="/common/icons/pub_articles.svg" alt="'.$_PAGE_TITLE.'" width=32> '.$_PAGE_TITLE.'</h1>';


foreach ($L as $k => $v) {
    $content .= '
        <h2>'.$k.'</h2>
        <table class="sortable admin-table publisher_stock">
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

return new Response($content);
