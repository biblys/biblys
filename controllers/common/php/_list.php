<?php

/** @noinspection PhpUnhandledExceptionInspection */

global $urlgenerator, $request;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Slug\SlugService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

$request = Request::createFromGlobals();
$config = Config::load();
$currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
$currentSite = CurrentSite::buildFromConfig($config);
$slugService = new SlugService();

$sm = new StockManager();

$pdo = array(); // PDO search query
$pqp = array(); // PDO search query params

$json = null; // JSON response
$filters = null;
$coverLane = null;

$sel_etat = null;
$_og_image = null;
$_og_description = null;
if (!isset($_GET['q'])) {
    $_GET['q'] = null;
}

$_REQ = $_REQ ?? "";
$terms = $terms ?? "";

if (!isset($defaultOrderBy)) {
    $defaultOrderBy = null;
}
$listOrderBy = $request->query->get('o', $defaultOrderBy);

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
            } elseif ($q == "commande") {
                $sql[] = "`stock_id` IS NULL AND `article_links` LIKE '%[onorder:".
                    $currentSite->getSite()->getId()
                    ."]%' AND `article_availability` = 1";
                $filters .= ' sur commande';
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
            } else {
                $terms = "";
            }
            $terms .= $q;
            $sql[] .= "`article_keywords` LIKE '%".addslashes($q)."%'";
        }
    }

    foreach ($sql as $offset) {
        if (isset($_REQ)) {
            $_REQ .= ' AND ';
        } else {
            $_REQ = '';
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
$articlesPerPage = $currentSite->getOption('articles_per_page', 10);

$offset = (int) $request->query->get("s", 0);
$_REQ_LIMIT = ' LIMIT '.$articlesPerPage.' OFFSET '.$offset;
$nextPageNum = $offset + $articlesPerPage;

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
        `site_id` = :site_id AND 
        `stock_selling_date` IS NULL AND 
        `stock_return_date` IS NULL AND 
        `stock_lost_date` IS NULL".$active_stock_query."
    WHERE $_REQ `type_id` != 2
    GROUP BY `articles`.`article_id`";

// Compter le nombre de résultats
$numQ = EntityManager::prepareAndExecute("SELECT `articles`.`article_id` ".$sql_query, [
    "site_id" => $currentSite->getSite()->getId(),
]);
$num = count($numQ->fetchAll());

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
        MAX(`article_keywords`) AS `article_keywords`,
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
    ["site_id" => $currentSite->getSite()->getId()]
);

$ix = $offset;
$covers = [];
$table = null;
while ($x = $sql->fetch(PDO::FETCH_ASSOC)) {
    $x['new'] = 0;
    $x['used'] = 0;
    $x['availability'] = null;

    $articleEntity = new Article($x, false);
    $article = new \Model\Article();
    $article->setId($articleEntity->get("id"));
    $copy = $articleEntity->getCheapestAvailableItem();

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


        // Sur commande
    } elseif (strstr($x["article_links"], '[onorder:'. $currentSite->getSite()->getId() .']') && $x["article_availability"] == 1) {
        $x["availability"] = '<img src="/common/img/square_blue.png" alt="Sur commande" title="Sur commande" />';
        $x["price"] = price($x["article_price"], 'EUR');
        $x["condition"] = ' onorder';
    }

    // Indisponible
    else {
        $x["availability"] = '<img src="/common/img/square_red.png" alt="Pas en stock" title="Pas en stock" />';
        $x["price"] = "pas en stock";
        $x["dispo_order"] = 0;
        $x["best_price"] = 0;
        $x["condition"] = ' soldout';
    }

    // Couverture
    $media = new Media("article", $x["article_id"]);
    if ($media->exists() && count($covers) < 12) {
        $covers[] = $x;
    }

    $x["article_url"] = $urlgenerator->generate("article_show", ["slug" => $x["article_url"]]);

    // Auteurs
    $x["authors"] = authors($x["article_authors"], 'url');

    // Lien collection
    $x["collection_url"] = $slugService->createForBookCollection($x['article_collection'], $x['article_publisher']);

    // Numero de collection
    $x["number"] = '<span class="nowrap">'.numero($x["article_number"]).'</span>';

    // Cycle et Tome
    $x['cycle'] = '';
    if (!empty($x['article_cycle'])) {
        $x['cycle'] = '<br><span class="article_cycle">(<a href="/serie/'.
            $slugService->slugify($x['article_cycle'])
            .'">'.$x['article_cycle'].'</a>'.numero($x['article_tome'], ' - ').')</span>';
    }

    // Bouton panier
    $x['cart'] = '<td></td>';
    if (!empty($x['stock_id']) && $copy) {
        $cart_class = 'black';
        if ($currentUser->hasArticleInCart($article)) {
            $cart_class = 'green';
        }
        $x['cart'] = '
            <td>
                <a class="event pointer add_to_cart" data-type="stock" data-id="'.$copy->get('id').'"><i class="fa fa-lg fa-shopping-cart '.$cart_class.'"></i></a>
            </td>
        ';
    }

    // Wishlist button
    $wish_icon = 'fa-heart-o';
    $wish_text = 'Ajouter <em>'.$x['article_title'].'</em> à vos envies';
    if ($currentUser->hasArticleInWishlist($article)) {
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
    $alert_icon = 'fa-bell-o';
    $alert_text = 'Créer une alerte pour <em>'.$x['article_title'].'</em>';
    if ($currentUser->hasAlertForArticle($article)) {
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
        <tr class="item'.$x["condition"].'" data-keywords="'.$x["article_keywords"].'">
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

    $ix++;

    $json[] = $x;
}
$sql->closeCursor();

// Page suivante
if ($ix < $num) {
    $nextPage = '<div class="center"><br><button id="nextPage" data-next_page="'.$nextPageNum.'">Afficher plus de résultats</button></div>';
} else {
    $nextPage = null;
}

// Couvertures (6 au hasard)
if (count($covers) >= 12) {
    $coverLane = "";
    $coverImages = [];
    for ($ic = 0; $ic < 7; $ic++) {
        $c = $covers[rand(0, count($covers)-1)];
        $cover = new Media("article", $c["article_id"]);
        $coverImages[] = ' <a href="/a/'.$c['article_url'].'"><img src="'.
            $cover->getUrl(["size" => "h125"]).
            '" style="max-width: 90px;" alt="'.$c['article_title'].' de '.authors($c['article_authors']).'" title="'.$c['article_title'].' de '.authors($c['article_authors']).'"></a> ';
    }
    if (count($coverImages) > 0) {
        $coverLane = '<div id="coverLane" class="right">'.join($coverImages).'</div>';
    }
} else {
    $covers = [];
}

if (isset($_GET['_FORMAT']) && $_GET['_FORMAT'] == "json") {
    $_WS["query"] = $_GET["q"];
    $_WS["results"] = $num;
    if ($ix < $num) {
        $_WS["nextPage"] = $nextPageNum;
    } else {
        $_WS["nextPage"] = 0;
    }
    $_WS["covers"] = $covers;
    $_WS["articles"] = $json;

    $response = new JsonResponse();
    $response->setData($_WS);
    $response->send();
    die();
} else {
    if (!isset($_ITEM_NAME)) {
        $_ITEM_NAME = "livre";
    }

    $sel = array('all' => null, 'neuf' => null, 'occasion' => null, 'command' => null, 'indisp' => null, 'article_title_alphabetic0' => null, 'article_authors_alphabetic0' => null,
        'article_collection0' => null, 'article_number0' => null, 'article_cycle0' => null, 'article_tome0' => null, 'article_pubdate1' => null, 'stock_purchase_date1' => null,
        'best_price0' => null, 'best_price1' => null, 'random0' => null);

    $sel[$listOrderBy.$listSortOrder] = ' data-selected="true"';
    $sel[$sel_etat] = ' data-selected="true"';

    $listContent = $coverLane.'

        <div id="listOptions">
            <span>
                <span id="listCount">'.$num.'</span> '.$_ITEM_NAME.s($num).'
            </span>

            Afficher :

            <div id="listFilter" class="btn-group">
                <button class="btn btn-default btn-sm" data-toggle="dropdown">
                    <i class="fa fa-square"></i>&nbsp; tous les livres <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li class="pointer" data-filter="all'.$sel['all'].'"><a><i class="fa fa-square black"></i>&nbsp; tous les livres</a></li>
                    <li class="pointer" data-filter="neuf"'.$sel['neuf'].'><a><i class="fa fa-square green"></i>&nbsp; livres neufs</a></li>
                    <li class="pointer" data-filter="occasion"'.$sel['occasion'].'><a><i class="fa fa-square orange"></i>&nbsp; livres d\'occasion</a></li>
                    <li class="pointer" data-filter="commande"'.$sel['command'].'><a><i class="fa fa-square blue"></i>&nbsp; dispo. sur commande</a></li>
                    <li class="pointer" data-filter="indisp"'.$sel['indisp'].'><a><i class="fa fa-square red"></i>&nbsp; pas en stock</a></li>
                </ul>
            </div>

            &nbsp;

            Trier par :

            <div id="listSort" class="btn-group">
                <button class="btn btn-default btn-sm" data-toggle="dropdown">
                    date d\'ajout au stock <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li class="pointer" data-sort="article_title_alphabetic" data-order=0'.$sel['article_title_alphabetic0'].'><a>titre</a></li>
                    <li class="pointer" data-sort="article_authors_alphabetic" data-order=0'.$sel['article_authors_alphabetic0'].'><a>auteur</a></li>
                    <li class="pointer" data-sort="article_collection" data-order=0'.$sel['article_collection0'].'><a>collection</a></li>
                    <li class="pointer" data-sort="article_number" data-order=0'.$sel['article_number0'].'><a>numéro de collection</a></li>
                    <li class="pointer" data-sort="article_cycle" data-order=0'.$sel['article_cycle0'].'><a>série</a></li>
                    <li class="pointer" data-sort="article_tome" data-order=0'.$sel['article_tome0'].'><a>numéro de volume</a></li>
                    <li class="pointer" data-sort="article_pubdate" data-order=1'.$sel['article_pubdate1'].'><a>date de parution</a></li>
                    <li class="pointer" data-sort="stock_purchase_date" data-order=1'.$sel['stock_purchase_date1'].'><a>date d\'ajout au stock</a></li>
                    <li class="pointer" data-sort="best_price" data-order=0'.$sel['best_price0'].'><a>prix, du - cher au + cher</a></li>
                    <li class="pointer" data-sort="best_price" data-order=1'.$sel['best_price1'].'><a>prix, du + cher au - cher</a></li>
                    <li class="pointer" data-sort="random" data-order=0'.$sel['random0'].'><a>ordre aléatoire</a></li>
                </ul>
            </div>

            &nbsp;<input type="search" id="listSearch" placeholder="Filtrer la liste...">

        </div>

        <table id="articleList" class="table list" data-search_terms="'.htmlspecialchars($_GET['q']).'" data-sort="'.htmlspecialchars($listOrderBy).'" data-order='.htmlspecialchars($listSortOrder).'>
            <tbody>
                '.$table.'
            </tbody>
            <tfooter>
            </tfooter>
        </table>

        '.$nextPage.'

    ';
}

if (!empty($_ECHO)) {
    $_ECHO .= $listContent;
}

return $listContent;
