<?php /** @noinspection PhpUnhandledExceptionInspection */
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
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\Pagination;
use Biblys\Service\Slug\SlugService;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

$request = LegacyCodeHelper::getGlobalRequest();

$sm = new StockManager();

$pdo = array(); // PDO search query
$pqp = array(); // PDO search query params

$slugService = new SlugService();

$config = Config::load();
$currentSite = CurrentSite::buildFromConfig($config);
$currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
$imagesService = new ImagesService($config, $currentSite, new Filesystem());

$filters = null;

$sel_etat = null;
$_og_image = null;
$_og_description = null;
if (!isset($_GET['q'])) {
    $_GET['q'] = null;
}

if (!isset($defaultOrderBy)) {
    $defaultOrderBy = null;
}
$listOrderBy = $request->query->get('o', $defaultOrderBy);
// Une chaîne vide (ex. paramètre `o` dupliqué dans l'URL : `?o=x&o=`) est
// traitée comme absente pour éviter un `ORDER BY ``` invalide.
if ($listOrderBy === '') {
    $listOrderBy = $defaultOrderBy;
}

if (!isset($defaultSortOrder)) {
    $defaultSortOrder = 1;
}
$listSortOrder = $request->query->get('d', $defaultSortOrder);

if ($listOrderBy != null && !in_array($listOrderBy, [
        'article_title_alphabetic', 'article_authors_alphabetic', 'article_collection',
        'article_number', 'article_cycle', 'article_tome', 'article_pubdate',
        'stock_purchase_date', 'best_price', 'random'
    ])) {
    throw new BadRequestHttpException('Unauthorized list sort order parameter '
        .htmlentities($listOrderBy));
}

// Requête de recherche
if (!empty($_GET["q"])) {
    $queries = explode(" ", $_GET["q"]);

    $sql = array();
    foreach ($queries as $q) {
        $q = str_replace('+', ' ', $q);

        if (str_contains($q, 'titre:')) { // Recherche par titre
            $title = str_replace("titre:", "", $q);
            $sql[] = "`article_title` LIKE '%".$title."%'";
            $filters .= ' du titre '.$title;
        } elseif (str_contains($q, 'auteur:')) { // Recherche par auteur
            $author = str_replace("auteur:", "", $q);
            $sql[] = "`article_authors` LIKE '%".$author."%'";
            $filters .= ' de l\'auteur '.$author;
        } elseif (str_contains($q, 'collection:')) { // Recherche par collection
            $collection = str_replace("collection:", "", $q);
            $sql[] = "`article_collection` LIKE '%".$collection."%'";
            $filters .= ' de la collection '.$collection;
        } elseif (strstr($q, 'editeur:') || strstr($q, 'éditeur:')) { // Recherche par editeur
            $editeur = str_replace(array("editeur:","éditeur:"), "", $q);
            $sql[] = "`article_publisher` LIKE '%".$editeur."%'";
            $filters .= ' de l\'éditeur '.$editeur;
        } elseif (str_contains($q, 'date:')) { // Ajoute au stock le
            $date = str_replace("date:", "", $q);
            $sql[] = "`stock_purchase_date` LIKE '".$date."%' ";
            $filters .= ' ajoutés le '._date($date, 'j f Y');
        } elseif (str_contains($q, 'date<')) { // Ajoute au stock avant le
            $date = str_replace("date<", "", $q);
            $sql[] = "`stock_purchase_date` < '".$date." 00:00:00' ";
            $filters .= ' ajoutés avant le '._date($date, 'j f Y');
        } elseif (str_contains($q, 'date>')) { // Ajoute au stock avant le
            $date = str_replace("date>", "", $q);
            $sql[] = "`stock_purchase_date` > '".$date." 00:00:00' ";
            $filters .= ' ajoutés après le '._date($date, 'j f Y');
        } elseif (strstr($q, 'etat:') || strstr($q, 'état:')) {
            $q = str_replace(array("etat:","état:"), '', $q);
            $sel_etat = $q;
            if ($q == "neuf") {
                $sql[] = "`stock_condition` = 'Neuf'";
                $filters .= ' neufs';
            } elseif ($q == "occasion") {
                $sql[] = "`stock_condition` != 'Neuf'";
                $filters .= ' d\'occasion';
            } elseif ($q == "indisp") {
                $sql[] = "`stock_id` IS NULL";
                $filters .= ' pas en stock';
            }
        } elseif (str_contains($q, 'type:')) {
            $type = str_replace("type:", "", $q);

            switch ($type) {
                case 'papier': $type = 'Livres papiers'; $type_id = 1; break;
                case 'numérique': $type = 'Livres numériques'; $type_id = 2; break;
                case 'CD': $type = 'CDs'; $type_id = 3; break;
                case 'DVD': $type = 'DVDs'; $type_id = 4; break;
                case 'lot': $type = 'Lots'; $type_id = 8; break;
                case 'BD': $type = 'BDs'; $type_id = 9; break;
                default: $type = 'Livres'; $type_id = 0;
            }

            $sql[] = "`type_id` = ".$type_id;
            $filters .= ' de type '.$type;
            if (!isset($listOrderBy)) {
                $listOrderBy = "article_pubdate";
            }
        } elseif (!empty($q)) {
            if (isset($terms)) {
                $terms .= ' ';
            }
            $terms .= $q;
            $sql[] .= "`article_keywords` LIKE '%".addslashes($q)."%'";
        }
    }

    foreach ($sql as $offset) {
        if (isset($_REQ)) {
            $_REQ .= ' AND ';
        }
        $_REQ .= $offset;
    }
}

if (!empty($_REQ)) {
    $_REQ .= " AND ";
}

// Tri
if (isset($listOrderBy)) {
    if ($listOrderBy == "random") {
        $_REQ_ORDER = " ORDER BY RAND()";
    } else {
        $sort_field = $listOrderBy;
        if ($sort_field == 'article_tome' || $sort_field == 'article_number') {
            $sort_field = ' CAST(`'.$listOrderBy.'` AS SIGNED)';
        } else {
            $sort_field = '`'.$listOrderBy.'`';
        }

        $_REQ_ORDER = " ORDER BY ".$sort_field;
        if (isset($listSortOrder) && $listSortOrder == 1) {
            $_REQ_ORDER .= " DESC";
        } else {
            $listSortOrder = 0;
        }
    }
} else {
    if (!isset($_REQ_ORDER)) {
        $_REQ_ORDER = "ORDER BY `stock_purchase_date` DESC";
    }
    $listOrderBy = "stock_purchase_date";
    $listSortOrder = 1;
}

// Pagination
$npp = 10; // nombre par page
$articles_per_page = $currentSite->getOption('articles_per_page');
if ($articles_per_page) {
    $npp = $articles_per_page;
}

$active_stock_query = null;
$active_stock = $currentSite->getOption("active_stock");
if ($active_stock) {
    $active_stock = "'".implode("','", explode(",", $active_stock))."'";
    $active_stock_query = " AND `stock_stockage` IN (".$active_stock.")";
}

$sql_query = "
    FROM `articles`
    LEFT JOIN `stock` ON 
        `stock`.`article_id` = `articles`.`article_id` AND 
        `stock_selling_date` IS NULL AND 
        `stock_return_date` IS NULL AND 
        `stock_lost_date` IS NULL".$active_stock_query."
    WHERE $_REQ `type_id` != 2
    GROUP BY `articles`.`article_id`";

// Compter le nombre de résultats
$numQ = EntityManager::prepareAndExecute("SELECT `articles`.`article_id` ".$sql_query, []);
$num = count($numQ->fetchAll());

// Pagination
$pageIndex = max(0, (int) $request->query->get("p", 0));
$pagination = new Pagination($pageIndex, $num, $npp);
$pagination->setQueryParams(array_diff_key($request->query->all(), array_flip(['p', 's', '_FORMAT'])));
$offset = $pagination->getOffset();
$_REQ_LIMIT = ' LIMIT '.$npp.' OFFSET '.$offset;

// Requête de résultat
$sql = EntityManager::prepareAndExecute("
    SELECT 
        MAX(`articles`.`article_id`) AS `article_id`, 
        MAX(`article_title`) AS `article_title`, 
        MAX(`article_title_alphabetic`) AS `article_title_alphabetic`, 
        MAX(`article_url`) AS `article_url`, 
        MAX(`article_authors`) AS `article_authors`, 
        MAX(`article_authors_alphabetic`) AS `article_authors_alphabetic`, 
        MAX(`article_collection`) AS `article_collection`, 
        MAX(`article_publisher`) AS `article_publisher`, 
        MAX(`article_number`) AS `article_number`, 
        MAX(`article_cycle`) AS `article_cycle`, 
        MAX(`article_tome`) AS `article_tome`, 
        MAX(`article_availability`) AS `article_availability`, 
        MAX(`article_copyright`) AS `article_copyright`,
        MAX(`article_pubdate`) AS `article_pubdate`, 
        MAX(`article_price`) AS `article_price`, 
        MAX(`article_copyright`) AS `article_copyright`, 
        MAX(`article_ean`) AS `article_ean`, 
        MAX(`article_links`) AS `article_links`,
        MAX(`stock_id`) AS `stock_id`,
        MAX(`stock_selling_date`) AS `stock_selling_date`, 
        MAX(`stock_return_date`) AS `stock_return_date`, 
        MAX(`stock_lost_date`) AS `stock_lost_date`, 
        MAX(`stock_purchase_date`) AS `stock_purchase_date`, 
        GROUP_CONCAT(`stock_condition` SEPARATOR '/') AS `stock_conditions`, 
        MIN(`stock_selling_price`) AS `best_price`, 
        MAX(`stock_stockage`)
    ".$sql_query." 
    ".$_REQ_ORDER." 
    ".$_REQ_LIMIT,
    []
);

$table = null;
while ($x = $sql->fetch(PDO::FETCH_ASSOC)) {
    $x['new'] = 0;
    $x['used'] = 0;
    $x['availability'] = null;

    $article = new Article($x, false);
    $articleModel = new \Model\Article();
    $articleModel->setId($article->get("id"));
    $copy = $article->getCheapestAvailableItem();

    // Exemplaire en stock
    if ($copy) {
        $conditions = explode("/", $x["stock_conditions"]);
        foreach ($conditions as $c) {
            if ($c == "Neuf") {
                $x["new"]++;
            } else {
                $x["used"]++;
            }
        }
        if ($x["new"]) {
            $x["availability"] = '<img src="/common/img/square_green.png" alt="En stock" title="En stock (neuf)" />';
            $x["condition"] = ' new';
        }
        if ($x["used"]) {
            $x["availability"] .= ' <img src="/common/img/square_orange.png" alt="En stock" title="En stock (occasion)" />';
            $x["condition"] = ' used';
        }
        $x["dispo_order"] = 1;
        $x["price"] = currency($x['best_price'] / 100);

        // Indisponible
    } else {
        $x["availability"] = '<img src="/common/img/square_red.png" alt="Pas en stock" title="Pas en stock" />';
        $x["price"] = "pas en stock";
        $x["dispo_order"] = 0;
        $x["best_price"] = 0;
        $x["condition"] = ' soldout';
    }

    $x["article_url"] = LegacyCodeHelper::getGlobalUrlGenerator()->generate("article_show", ["slug" => $x["article_url"]]);

    // Auteurs
    $x["authors"] = authors($x["article_authors"], 'url');

    // Lien collection
    $x["collection_url"] = $slugService->createForBookCollection($x['article_collection'], $x['article_publisher']);

    // Numero de collection
    $x["number"] = '<span class="nowrap">'.numero($x["article_number"]).'</span>';

    // Cycle et Tome
    $x['cycle'] = '';
    if (!empty($x['article_cycle'])) {
        $x['cycle'] = '<br><span class="article_cycle">(<a href="/serie/'.$slugService->slugify($x['article_cycle']) .'">'.$x['article_cycle'].'</a>'.numero($x['article_tome'], ' - ').')</span>';
    }

    // Bouton panier
    $x['cart'] = '<td></td>';
    if (!empty($x['stock_id']) && $copy) {
        $cart_class = 'black';
        if ($currentUser->hasArticleInCart($articleModel)) {
            $cart_class = 'green';
        }
        $x['cart'] = '
            <td>
                <a class="event pointer add_to_cart" data-type="stock" data-id="'.$copy->get('id').'"><i class="fa fa-lg fa-shopping-cart '.$cart_class.'"></i></a>
            </td>
        ';
    }

    // Wishlist button
    $wish_icon = 'fa-heart';
    $wish_text = 'Ajouter <em>'.$x['article_title'].'</em> à vos envies';
    if ($currentUser->hasArticleInWishlist($articleModel)) {
        $wish_icon = 'fa-heart red';
        $wish_text = 'Retirer <em>'.$x['article_title'].'</em> de vos envies';
    }
    $x['wish'] = '
        <td>
            <a data-wish="'.$x['article_id'].'" class="pointer event" title="'.$wish_text.'">
                <i class="fa '.$wish_icon.'"></i>
            </a>
        </td>
    ';

    // Alert button
    $alert_icon = 'fa-bell';
    $alert_text = 'Créer une alerte pour <em>'.$x['article_title'].'</em>';
    if ($currentUser->hasAlertForArticle($articleModel)) {
        $alert_icon = 'fa-bell orange';
        $alert_text = 'Retirer <em>'.$x['article_title'].'</em> de vos alertes';
    }
    $x['alert'] = '
        <td>
            <a data-alert="'.$x['article_id'].'" class="pointer event" title="'.$alert_text.'">
                <i class="fa '.$alert_icon.'"></i>
            </a>
        </td>
    ';

    $table .= '
        <tr class="item'.$x["condition"].'">
            <td><a href="'.$x["article_url"].'" class="article_title">'.$x["article_title"].'</a>'.$x['cycle'].'</td>
            <td title="'.$x["article_authors"].'">'.$x["authors"].'</td>
            <td class="right"><a href="/collection/'.$x['collection_url'].'">'.$x["article_collection"].'</a>'.$x["number"].'</td>
            <td class="right nowrap">'.$x["availability"].'</td>
            <td>'.$x["price"].'</td>
            '.$x['cart'].'
            '.$x['wish'].'
            '.$x['alert'].'
        </tr>
    ';

    if (isset($_og_description)) {
        $_og_description .= ', ';
    }
    $_og_description .= $x['article_title'];
}
$sql->closeCursor();

// Pagination
$paginationNav = null;
if ($pagination->getTotal() > 1) {
    if ($pagination->getTotal() <= 10) {
        $paginationNav = '<nav class="Pagination text-center mt-2 mb-4" aria-label="Page navigation"><ul class="pagination justify-content-center">';

        if ($pagination->getPrevious() !== false) {
            $paginationNav .= '
                <li class="Pagination__previous page-item">
                    <a href="'.htmlspecialchars($pagination->getPreviousQuery() ?: '?').'" aria-label="Précédent" class="page-link">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            ';
        }

        for ($pageNumber = 1; $pageNumber <= $pagination->getTotal(); $pageNumber++) {
            if ($pagination->getCurrent() === $pageNumber) {
                $paginationNav .= '<li class="Pagination__page Pagination__page--current active page-item"><span class="pagination-page pagination-page-current page-link">'.$pageNumber.'</span></li>';
            } else {
                $paginationNav .= '<li class="Pagination__page page-item"><a href="'.htmlspecialchars($pagination->getQueryForPageNumber($pageNumber) ?: '?').'" class="page-link">'.$pageNumber.'</a></li>';
            }
        }

        if ($pagination->getNext() !== false) {
            $paginationNav .= '
                <li class="Pagination__next page-item">
                    <a href="'.htmlspecialchars($pagination->getNextQuery()).'" aria-label="Suivant" class="page-link">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            ';
        }

        $paginationNav .= '</ul></nav>';
    } else {
        $paginationNav = '<nav class="Pagination Pagination--with-menu mt-2 mb-4">';

        if ($pagination->getPrevious() !== false) {
            $paginationNav .= '<a href="'.htmlspecialchars($pagination->getPreviousQuery() ?: '?').'" aria-label="Aller à la page précédente" class="btn btn-outline-primary">&laquo; Précédent</a>';
        }

        $paginationNav .= '
            <form class="form-inline mb-0" method="get">
                <div class="Pagination__page-selector form-group text-center">
                    <label for="target-page">Page</label>
                    <select name="p" class="form-control" id="target-page" aria-label="Aller à la page" onchange="this.form.submit()">
        ';
        for ($pageNumber = 1; $pageNumber <= $pagination->getTotal(); $pageNumber++) {
            $selected = $pagination->getCurrent() === $pageNumber ? ' selected' : '';
            $paginationNav .= '<option value="'.($pageNumber - 1).'"'.$selected.'>'.$pageNumber.'</option>';
        }
        $paginationNav .= '
                    </select>
                    sur '.$pagination->getTotal().'
                    <button type="submit" class="btn btn-primary">Aller</button>
                </div>
            </form>
        ';

        if ($pagination->getNext() !== false) {
            $paginationNav .= '<a href="'.htmlspecialchars($pagination->getNextQuery()).'" aria-label="Aller à la page suivante" class="btn btn-outline-primary">Suivant &raquo;</a>';
        }

        $paginationNav .= '</nav>';
    }
}

if (!isset($_ITEM_NAME)) {
    $_ITEM_NAME = "livre";
}

    $sel = array('all' => null, 'neuf' => null, 'occasion' => null, 'command' => null, 'indisp' => null, 'article_title_alphabetic0' => null, 'article_authors_alphabetic0' => null,
        'article_collection0' => null, 'article_number0' => null, 'article_cycle0' => null, 'article_tome0' => null, 'article_pubdate1' => null, 'stock_purchase_date1' => null,
        'best_price0' => null, 'best_price1' => null, 'random0' => null);

    $sel[$listOrderBy.$listSortOrder] = ' data-selected="true"';
    $sel[$sel_etat] = ' data-selected="true"';

    $filterLabels = [
        'all' => 'tous les livres',
        'neuf' => 'livres neufs',
        'occasion' => 'livres d\'occasion',
        'indisp' => 'pas en stock',
    ];
    $filterLabel = $filterLabels[$sel_etat] ?? $filterLabels['all'];

    $filterColors = ['all' => 'black', 'neuf' => 'green', 'occasion' => 'orange', 'indisp' => 'red'];
    $filterColor = $filterColors[$sel_etat] ?? $filterColors['all'];

    $sortLabels = [
        'article_title_alphabetic0' => 'titre',
        'article_authors_alphabetic0' => 'auteur',
        'article_collection0' => 'collection',
        'article_number0' => 'numéro de collection',
        'article_cycle0' => 'série',
        'article_tome0' => 'numéro de volume',
        'article_pubdate1' => 'date de parution',
        'stock_purchase_date1' => 'date d\'ajout au stock',
        'best_price0' => 'prix, du - cher au + cher',
        'best_price1' => 'prix, du + cher au - cher',
        'random0' => 'ordre aléatoire',
    ];
    $sortLabel = $sortLabels[$listOrderBy.$listSortOrder] ?? $sortLabels['stock_purchase_date1'];

    // Liens de filtre et de tri (rechargement de page, sans AJAX)
    $baseQueryParams = $request->query->all();
    unset($baseQueryParams['p'], $baseQueryParams['s'], $baseQueryParams['_FORMAT'], $baseQueryParams['q']);

    $filterQueries = [];
    foreach (['all', 'neuf', 'occasion', 'indisp'] as $filter) {
        $filterTerms = trim(preg_replace('/ ?etat:\S+/', '', (string) $_GET['q']));
        if ($filter !== 'all') {
            $filterTerms = trim($filterTerms.' etat:'.$filter);
        }
        $filterParams = $baseQueryParams;
        if ($filterTerms !== '') {
            $filterParams['q'] = $filterTerms;
        }
        $filterQueries[$filter] = count($filterParams) ? '?'.http_build_query($filterParams) : '?';
    }

    $sortQueries = [];
    foreach ([
        'article_title_alphabetic0', 'article_authors_alphabetic0', 'article_collection0',
        'article_number0', 'article_cycle0', 'article_tome0', 'article_pubdate1',
        'stock_purchase_date1', 'best_price0', 'best_price1', 'random0',
    ] as $sortKey) {
        $sortParams = $baseQueryParams;
        if ((string) $_GET['q'] !== '') {
            $sortParams['q'] = $_GET['q'];
        }
        $sortParams['o'] = substr($sortKey, 0, -1);
        $sortParams['d'] = substr($sortKey, -1);
        $sortQueries[$sortKey] = '?'.http_build_query($sortParams);
    }

    $listContent = '

        <div id="listOptions" class="d-flex justify-content-between align-items-center flex-wrap">
            <span>
                <span id="listCount">'.$num.'</span> '.$_ITEM_NAME.s($num). '
            </span>

            <span class="d-flex align-items-center flex-wrap">

            Afficher :

            <span id="listFilter" class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-square '.$filterColor.'"></i>&nbsp; '.$filterLabel.' <span class="caret"></span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="'.htmlspecialchars($filterQueries['all']).'" data-color="black"'.$sel['all'].'><i class="fa fa-square black"></i>&nbsp; tous les livres</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($filterQueries['neuf']).'" data-color="green"'.$sel['neuf'].'><i class="fa fa-square green"></i>&nbsp; livres neufs</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($filterQueries['occasion']).'" data-color="orange"'.$sel['occasion'].'><i class="fa fa-square orange"></i>&nbsp; livres d\'occasion</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($filterQueries['indisp']).'" data-color="red"'.$sel['indisp'].'><i class="fa fa-square red"></i>&nbsp; pas en stock</a>
                </div>
            </span>

            &nbsp;

            Trier par :

            <span id="listSort" class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown">
                    '.$sortLabel.' <span class="caret"></span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['article_title_alphabetic0']).'"'.$sel['article_title_alphabetic0'].'>titre</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['article_authors_alphabetic0']).'"'.$sel['article_authors_alphabetic0'].'>auteur</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['article_collection0']).'"'.$sel['article_collection0'].'>collection</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['article_number0']).'"'.$sel['article_number0'].'>numéro de collection</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['article_cycle0']).'"'.$sel['article_cycle0'].'>série</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['article_tome0']).'"'.$sel['article_tome0'].'>numéro de volume</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['article_pubdate1']).'"'.$sel['article_pubdate1'].'>date de parution</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['stock_purchase_date1']).'"'.$sel['stock_purchase_date1'].'>date d\'ajout au stock</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['best_price0']).'"'.$sel['best_price0'].'>prix, du - cher au + cher</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['best_price1']).'"'.$sel['best_price1'].'>prix, du + cher au - cher</a>
                    <a class="dropdown-item" href="'.htmlspecialchars($sortQueries['random0']).'"'.$sel['random0'].'>ordre aléatoire</a>
                </div>
            </span>

            &nbsp;
            <button type="button" id="listApply" class="btn btn-primary btn-sm">Actualiser</button>

            </span>

        </div>

        <table id="articleList" class="table list">
            <tbody>
                '.$table.'
            </tbody>
            <tfooter>
            </tfooter>
        </table>

        <div id="listPagination">'.$paginationNav.'</div>

    ';

if (!empty($_ECHO)) {
    $_ECHO .= $listContent;
}

return $listContent;
