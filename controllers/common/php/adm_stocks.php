<?php

use Biblys\Data\ArticleType;
use Biblys\Service\CurrentSite;
use Biblys\Service\Images\ImagesService;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @throws InvalidDateFormatException
 * @throws PropelException
 */
return function (
    Request $request,
    CurrentSite $currentSite,
    ImagesService $imagesService
): Response|RedirectResponse
{
    $lm = new ListeManager();
    $sm = new StockManager();

    $request->attributes->set("page_title", "Stock");

    $content = '<h1><span class="fa fa-cubes"></span> Stock</h1>';

    if (empty($_GET['stock_created'])) {
        $_GET['stock_created'] = null;
    }
    if (empty($_GET['stock_invoice'])) {
        $_GET['stock_invoice'] = null;
    }
    if (empty($_GET['stock_stockage'])) {
        $_GET['stock_stockage'] = null;
    }
    if (empty($_GET['availability'])) {
        $_GET['availability'] = null;
    }
    if (empty($_GET['collection_id'])) {
        $_GET['collection_id'] = null;
    }
    if (empty($_GET['article_id'])) {
        $_GET['article_id'] = null;
    }
    if (empty($_GET['collection_id'])) {
        $_GET['collection_id'] = null;
    }
    if (empty($_GET['publisher_id'])) {
        $_GET['publisher_id'] = null;
    }
    $dates = null;

    $stockDates = EntityManager::prepareAndExecute(
        "SELECT
        DATE_FORMAT(`stock_created`, '%Y-%m-%d') as `date`
    FROM `stock`
    WHERE `stock`.`site_id` = :site_id AND `stock_created` > SUBDATE(NOW(), INTERVAL 1 MONTH)
    GROUP BY `date`
    ORDER BY `date` DESC",
        ['site_id' => $currentSite->getId()]
    );
    foreach ($stockDates as $stockDate) {
        if ($_GET['stock_created'] == $stockDate['date']) {
            $stockDate['selected'] = 'selected="selected"';
        } else {
            $stockDate['selected'] = null;
        }
        $dates .= '
        <option value="' . $stockDate['date'] . '" ' . $stockDate['selected'] . '>
            ' . _date($stockDate['date'], 'L j f') . '
        </option>
    ';
    }

    $a['ventes'] = null;
    $a['stock'] = null;
    $a['retours'] = null;
    $a['panier'] = null;
    $a['perdus'] = null;
    $a[$_GET['availability']] = ' selected';
    if (isset($_GET['stock_condition'])) {
        $a[$_GET['stock_condition']] = ' selected';
    }

    $types = ArticleType::getAll();
    $types_options = array_map(function ($type) {
        global $request;

        return '<option value="' . $type->getId() . '"' . ($type->getId() == $request->query->get('type_id', 0) ? ' selected' : null) . '>' . $type->getName() . '</option>';
    }, $types);

    $content .= '
    <form method="get">
        <p>
            <label for="stock_created">Ajoutés le :</label>
            <select name="stock_created" id="stock_created">
                <option />
                ' . $dates . '
            </select>
        </p>

        <p>
            <label for="availability">Disponibilité :</label>
            <select name="availability" id="availability">
                <option value="all">Tous</a>
                <option value="stock"' . $a['stock'] . '>En stock</a>
                <option value="panier"' . $a['panier'] . '>En panier</a>
                <option value="ventes"' . $a['ventes'] . '>Vendus</a>
                <option value="retours"' . $a['retours'] . '>Retournés</a>
                <option value="perdus"' . $a['perdus'] . '>Perdus</a>
            </select>
        </p>

        <p>
            <label for="stock_invoice">Facture :</label>
            <input type="text" name="stock_invoice" id="stock_invoice" value="' . $_GET['stock_invoice'] . '" />
            <input type="checkbox" name="stock_without_invoice" value="1" /> Sans
        </p>

        <p>
            <label for="stock_stockage">Emplacement :</label>
            <input type="text" name="stock_stockage" id="stock_stockage" value="' . ($_GET['stock_stockage'] ?? null) . '" />
        </p>

        <p>
            <label for="publisher_id">Éditeur n&deg; :</label>
            <input type="number" class="short" name="publisher_id" id="publisher_id" value="' . ($_GET['publisher_id'] ?? null) . '" />
        </p>

        <p>
            <label for="collection_id">Collection n&deg; :</label>
            <input type="number" class="short" name="collection_id" id="collection_id" value="' . $_GET['collection_id'] . '" />
        </p>

        <p>
            <label for="article_id">Article n&deg; :</label>
            <input type="number" class="short" name="article_id" id="article_id" value="' . $_GET['article_id'] . '" />
        </p>

        <p>
            <label for="type_id">Types :</label>
            <select name="type_id" id="type_id">
                <option value="">Tous</a>
                ' . implode($types_options) . '
            </select>
        </p>

        <p>
            <label for="stock_condition">État :</label>
            <select name="stock_condition" id="stock_condition">
                <option value="all">Tous</a>
                <option value="new">Neuf</a>
                <option value="used">Occasion</a>
            </select>
        </p>

        </p>
            <label for="stock_limit">Limiter &agrave; :</label>
            <input type="number" class="mini" name="stock_limit" id="stock_limit" value="' . ($_GET['stock_limit'] ?? null) . '" /> exemplaires
        </p>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="promo" value=1' . ($request->query->get('promo', false) == 1 ? ' checked' : null) . '> En promotion
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="depot" value=1' . ($request->query->get('depot', false) == 1 ? ' checked' : null) . '> En dépôt
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="weightless" value=1' . ($request->query->get('weightless', false) == 1 ? ' checked' : null) . '> Sans poids
                    </label>
                </div>
            </div>
        </div>

        <div class="center">
            <button type="submit" class="btn btn-primary">Afficher les exemplaires</button>
        </div>
    </form>
';

// Build query
    $req = null;
    if (isset($_GET['stock_created'])) {
        $req .= " AND `stock_created` LIKE '" . $_GET['stock_created'] . "%' ";
    }
    if (isset($_GET['stock_invoice'])) {
        $req .= " AND `stock_invoice` LIKE '%" . addslashes($_GET['stock_invoice']) . "%' ";
    }
    if (isset($_GET['stock_stockage'])) {
        $req .= " AND `stock_stockage` LIKE '%" . addslashes($_GET['stock_stockage']) . "%' ";
    }
    if (isset($_GET['collection_id'])) {
        $req .= " AND `collection_id` = '" . $_GET['collection_id'] . "' ";
    }
    if (isset($_GET['publisher_id'])) {
        $req .= " AND `articles`.`publisher_id` = '" . $_GET['publisher_id'] . "' ";
    }
    if (isset($_GET['article_id'])) {
        $req .= " AND `article_id` = '" . $_GET['article_id'] . "' ";
    }
    if (isset($_GET['availability'])) {
        if ($_GET['availability'] == 'stock') {
            $req .= ' AND `stock_selling_date` IS NULL AND `stock_cart_date` IS NULL AND `stock_return_date` IS NULL AND `stock_lost_date` IS NULL';
        } elseif ($_GET['availability'] == 'panier') {
            $req .= ' AND `stock_cart_date` IS NOT NULL ';
        } elseif ($_GET['availability'] == 'ventes') {
            $req .= ' AND `stock_selling_date` IS NOT NULL ';
        } elseif ($_GET['availability'] == 'retours') {
            $req .= ' AND `stock_return_date` IS NOT NULL ';
        } elseif ($_GET['availability'] == 'perdus') {
            $req .= ' AND `stock_lost_date` IS NOT NULL ';
        }
    }

    if ($request->query->get('promo', false)) {
        $req .= ' AND `stock_selling_price_saved` IS NOT NULL ';
    }

    if ($request->query->get('depot', false)) {
        $req .= ' AND `stock_depot` = 1 ';
    }

    if ($request->query->get('weightless', false)) {
        $req .= ' AND `stock_weight` = 0 ';
    }

    $type_id = $request->query->get('type_id', 0);
    if ($type_id) {
        $req .= " AND `type_id` = '" . $type_id . "' ";
    }

    if (isset($_GET['stock_condition'])) {
        if ($_GET['stock_condition'] == 'new') {
            $req .= " AND `stock_condition` LIKE '%Neuf%'";
        } elseif ($_GET['stock_condition'] == 'used') {
            $req .= " AND `stock_condition` NOT LIKE '%Neuf%'";
        }
    }

    if (isset($_GET['stock_without_invoice']) && $_GET['stock_without_invoice']) {
        $req .= " AND `stock_invoice` = ''";
    }

    if (!empty($_GET['stock_limit'])) {
        $limit = 'LIMIT ' . $_GET['stock_limit'];
    } else {
        $limit = null;
    }

    if ($req) {
        $req = 'WHERE `stock_id` IS NOT NULL ' . $req;
    } else {
        $req = 'WHERE `stock_id` IS NULL';
    }

    $sql_query = 'SELECT `article_id`, `article_title`, `article_title_alphabetic`, `article_number`, `article_url`, `article_authors`, `article_collection`,
    `stock_id`, `stock_selling_price`, `stock_purchase_price`, `stock_weight`, `stock_condition`, `stock_pub_year`, `stock_purchase_date`, `stock_invoice`, `stock_selling_date`, `stock_return_date`, `stock_cart_date`, `stock_lost_date`
    ,`customer_id`, `customer_last_name`, `customer_first_name`, `customer_email`
    FROM `articles`
    JOIN `stock` USING(`article_id`)
    JOIN `sites` USING(`site_id`)
    LEFT JOIN `customers` USING(`customer_id`)
    ' . $req . ' AND `stock`.`site_id` = :site_id
    GROUP BY `stock_id`
    ORDER BY `stock`.`article_id`, `stock_id` DESC
    ' . $limit . '
';

    $sql = EntityManager::prepareAndExecute(
        $sql_query,
        ['site_id' => $currentSite->getId()]
    );
    $num = $sql->rowCount();

    if (isset($_GET['article_id'])) {
        $content .= '';
    }
    $content .= '<h3>' . $num . ' exemplaire' . s($num) . '</h3>';

    $article_title = null;
    $list = null;
    $ventes = 0;
    $stocks = 0;
    $retours = 0;
    $paniers = 0;
    $perdus = 0;
    $total_weight = 0;
    $total_purchase_price = 0;
    $total_selling_price = 0;
    $add_to_list = [];
    $i = 0;

    foreach ($sql as $x) {
        $line = null;
        if ($article_title != $x['article_title']) {
            $line .= '
            <tr class="article">
                <td colspan="7">
                    <p class="floatR"><a href="/pages/adm_stock?add=' . $_GET['article_id'] . '" class="btn btn-success">Ajouter un exemplaire</a></p>
                    <h4>
                        <a href="/a/' . $x['article_url'] . '">' . $x['article_title'] . '</a> de ' . $x['article_authors'] . '<br />
                        (' . $x['article_collection'] . numero($x['article_number']) . ')
                    </h4>
                    <p id="stocks_' . $x['article_id'] . '" class="hidden"></p> </td>
            </tr>
        ';
        }
        ++$i;
        $article_title = $x['article_title'];
        if (!empty($x['stock_id'])) {
            /** @var Stock $stock */
            $stock = $sm->getById($x['stock_id']);

            $x['copyButton'] = '
            <a href="/pages/adm_stock?copy=' . $x['stock_id'] . '">
                <span class="fa fa-clone fa-lg black" aria-label="Dupliquer"
                    title="Dupliquer"></span>
            </a>
        ';
            $x['returnButton'] = '
            <a href="/pages/adm_stock?return=' . $x['stock_id'] . '">
                <span class="fa fa-undo fa-lg black" aria-label="Retourner"
                    title="Retourner"></span>
            </a>
        ';
            $x['lostButton'] = '
            <a href="/pages/adm_stock?lost=' . $x['stock_id'] . '">
                <span class="fa fa-question fa-lg black"
                    aria-label="Marquer comme perdu"
                    title="Marquer comme perdu">
                </span>
            </a>
        ';
            $x['soldButton'] = '
            <a href="/pages/adm_stock?sold=' . $x['stock_id'] . '">
                <span class="fa fa-shopping-bag fa-lg black" title="Vendu en magasin" />
            </a>
        ';

            if ($x['stock_return_date']) { // Retourné
                $x['led'] = 'square_orange';
                $x['status'] = 'Retourné&nbsp;le<br />' . _date($x['stock_return_date'], 'd/m/Y');
                unset($x['returnButton'], $x['soldButton']);
                ++$retours;
            } elseif ($x['stock_selling_date']) { // Vendu
                $x['led'] = 'square_blue';
                $x['status'] = 'Vendu le<br />' . _date($x['stock_selling_date'], 'd/m/Y');

                // Sold in shop
                if ($x['customer_id'] === $currentSite->getOption("fake_shop_customer")) {
                    $x['status'] .= '<span class="fa fa-shopping-bag fa-lg"  title="Vendu en magasin" />';
                } else {
                    $x['status'] .= '<span class="fa fa- fa-lg" aria-label="Vendu en ligne" title="Vendu en ligne" />';
                }

                $x['soldButton'] = null;
                ++$ventes;
            } elseif ($x['stock_cart_date']) { // En panier
                $x['led'] = 'square_gray';
                $x['status'] = 'En panier le<br />' . _date($x['stock_cart_date'], 'd/m/Y');
                ++$paniers;
            } elseif ($x['stock_lost_date']) { // Perdu
                $x['led'] = 'square_purple';
                $x['status'] = 'Perdu le<br />' . _date($x['stock_lost_date'], 'd/m/Y');
                $x['lostButton'] = '';
                ++$perdus;
            } else { // En stock
                $x['led'] = 'square_green';
                $x['status'] = 'En&nbsp;stock';
                ++$stocks;
            }

            if (empty($x['stock_invoice'])) {
                $x['stock_invoice'] = '<em>N/A</em>';
            }

            $line .= '
                <tr id="stock_' . $x['stock_id'] . '">
                    <td>
                        <a href="/pages/adm_stock?id=' . $x['stock_id'] . '">' . $x['stock_id'] . '</a><br />
                        ' . $x['copyButton'] . '
                        ' . ($x['soldButton'] ?? null) . '
                        ' . ($x['returnButton'] ?? null) . '
                        ' . $x['lostButton'] . '
                        <span class="fa fa-trash fa-lg deleteStock pointer"
                            aria-label="Supprimer"
                            data-stock_id="' . $x['stock_id'] . '"
                            data-article_id="' . $x['article_id'] . '"
                            title="Supprimer"></span>
                    </td>
            ';

            $stockModel = new \Model\Stock();
            $stockModel->setId($x["stock_id"]);
            if ($num < 700 && $imagesService->imageExistsFor($stockModel)) {
                $line .= '<td class="center">
                     <a href="' . $imagesService->getImageUrlFor($stockModel) . '" rel="lightbox">
                            <img src="' . $imagesService->getImageUrlFor($stockModel, height: 50) . '" alt="" height=50>
                        </a>
                    </td>';
            } else {
                $line .= '<td></td>';
            }

            $saved = null;
            if ($stock->has('selling_price_saved')) {
                $saved = '<span class="cell saved-price">'
                    . currency($stock->get('stock_selling_price_saved') / 100) .
                    '</span>';
            }

            $line .= '
                <td>
                    <span id="span-stock_condition-' . $x['stock_id'] . '" class="cell pointer">' . $x['stock_condition'] . '</span>
                    <form id="form-stock_condition-' . $x['stock_id'] . '" data-stock_id="' . $x['stock_id'] . '" data-field="stock_condition" data-article_id="' . $x['article_id'] . '" class="update" style="display: none;">
                        <input id="input-stock_condition-' . $x['stock_id'] . '" class="field nano" value="' . $x['stock_condition'] . '" />
                    </form>
                </td>
                <td class="right">
                    <span id="span-stock_weight-' . $x['stock_id'] . '" class="cell pointer">' . $x['stock_weight'] . '</span>
                    <form id="form-stock_weight-' . $x['stock_id'] . '" data-stock_id="' . $x['stock_id'] . '" data-field="stock_weight" data-article_id="' . $x['article_id'] . '" class="update" style="display: none;">
                        <input id="input-stock_weight-' . $x['stock_id'] . '" class="field nano" value="' . $x['stock_weight'] . '" />
                    </form>g
                </td>
                <td class="right">

                    <span class="pull-left">
                        <i class="fa fa-map-marker"></i> ' . $stock->get('stockage') . '
                    </span>

                    <span id="span-stock_purchase_price-' . $x['stock_id'] . '" class="cell pointer">' . currency($x['stock_purchase_price'] / 100) . '</span>
                    <form id="form-stock_purchase_price-' . $x['stock_id'] . '" data-stock_id="' . $x['stock_id'] . '" data-field="stock_purchase_price" data-article_id="' . $x['article_id'] . '" class="update" style="display: none;">
                        <input id="input-stock_purchase_price-' . $x['stock_id'] . '" class="field nano" value="' . $x['stock_purchase_price'] . '" />&nbsp;cts
                    </form><br />

                    ' . _date($x['stock_purchase_date'], 'd/m/Y') . '<br />

                    <span class="pull-left">
                            <input type="checkbox" class="changeBool" data-field="stock_depot" data-stock_id="' . $stock->get('id') . '" id="stock_depot_' . $stock->get('id') . '"' . ($stock->has('depot') ? ' checked' : null) . '>
                        <label for="stock_depot_' . $stock->get('id') . '" class="after">Dépôt</label>
                    </span>

                    <span id="span-stock_invoice-' . $x['stock_id'] . '" class="cell pointer">' . $x['stock_invoice'] . '</span>
                    <form id="form-stock_invoice-' . $x['stock_id'] . '" data-stock_id="' . $x['stock_id'] . '" data-field="stock_invoice" data-article_id="' . $x['article_id'] . '" class="update" style="display: none;">
                        <input id="input-stock_invoice-' . $x['stock_id'] . '" class="field short" value="' . $x['stock_invoice'] . '" />
                    </form>&nbsp;<a href="/pages/adm_stocks?stock_invoice=' . $x['stock_invoice'] . '" title="Voir les autres exemplaires correspondant &agrave; cette facture.">&raquo;</a>
                </td>
                <td class="right">
                    <span id="span-stock_selling_price-' . $x['stock_id'] . '" class="cell pointer">' . currency($x['stock_selling_price'] / 100) . '</span>
                    <form id="form-stock_selling_price-' . $x['stock_id'] . '" data-stock_id="' . $x['stock_id'] . '" data-field="stock_selling_price" data-article_id="' . $x['article_id'] . '" class="update" style="display: none;">
                        <input id="input-stock_selling_price-' . $x['stock_id'] . '" class="field nano" value="' . $x['stock_selling_price'] . '" />&nbsp;cts
                    </form><br />
                    ' . $saved . '
                </td>
                <td class="center">
                    <img src="/common/img/' . $x['led'] . '.png" width="8" height="8" alt="" />&nbsp;' . $x['status'] . '
                </td>
            </tr>
        ';

            if (!empty($x["customer_id"])) {

                $customerIdentity = "Client n°{$x["customer_id"]}";
                if (!empty($x["customer_email"])) {
                    $customerIdentity = $x["customer_email"];
                }
                if (!empty($x["customer_last_name"])) {
                    $customerIdentity = trim($x["customer_first_name"] . " " . $x["customer_last_name"]);
                }

                $line .= '<tr>
                <td colspan="10">
                    <span class="fa fa-user"></span> 
                    Client : <a href="/pages/adm_customer?id=' . $x['customer_id'] . '">
                        ' . $customerIdentity . '
                    </a>
                </td>
            </tr>';
            }

            $list .= $line;

            $total_weight += $x['stock_weight'];
            $total_purchase_price += $x['stock_purchase_price'];
            $total_selling_price += $x['stock_selling_price'];

            if (isset($_POST['list_id'])) {
                $add_to_list[] = $x['stock_id'];
            }
        }
    }

    // Add to list
    if (isset($_POST['list_id'])) {
        /** @var Liste $theList */
        $theList = $lm->getById($_POST['list_id']);
        if ($theList) {
            $stocks = $sm->getByIds($add_to_list);
            $lm->addStock($theList, $stocks);

            return new RedirectResponse("/list/{$theList->get('url')}");
        }
    }

    $tfoot = '
        <tr>
            <td colspan="3" class="right">Total :</td>
            <td class="right">' . round($total_weight / 1000, 1) . '&nbsp;kg</td>
            <td class="right">' . currency($total_purchase_price / 100) . '</td>
            <td class="right">' . currency($total_selling_price / 100) . '</td>
            <td></td>
        </tr>
    ';

    $tbody = $list;

    $lists = $lm->getAll(["site_id" => $currentSite->getId()]);
    $lists = array_map(function ($list) {
        return '<option value=' . $list->get('id') . '>' . $list->get('title') . '</option>';
    }, $lists);

    $content .= '
        <p>
            <img src="/common/img/square_green.png" alt="" /> ' . $stocks . ' en stock<br />
            <img src="/common/img/square_gray.png" alt="" /> ' . $paniers . ' en panier' . s($paniers) . '<br />
            <img src="/common/img/square_blue.png" alt="" /> ' . $ventes . ' vendu' . s($ventes) . '<br />
            <img src="/common/img/square_orange.png" alt="" /> ' . $retours . ' retourné' . s($retours) . '<br />
            <img src="/common/img/square_purple.png" alt="" /> ' . $perdus . ' perdus' . s($perdus) . '
        </p>
        <table class="inventory">
            <thead>
                <tr>
                    <th style="width: 100px;">Ref.</th>
                    <th></th>
                    <th>Etat</th>
                    <th>Poids</th>
                    <th>Achat</th>
                    <th>Prix</th>
                    <th style="width: 90px;">Statut</th>
                </tr>
            </thead>
            <tbody>
                ' . $tbody . '
            </tbody>
            <tfoot>
                ' . $tfoot . '
            </tfoot>
        </table>
    
        <form method="post" class="fieldset">
            <fieldset>
                <legend>Ajouter les exemplaires affichés à une liste</legend>
    
                <label for="list_id">Liste :</label>
                <select id="list_id" name="list_id">
                    ' . implode($lists) . '
                </select>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </fieldset>
        </form>
    ';

    return new Response($content);
};
