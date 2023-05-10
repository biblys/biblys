<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** @var Request $request */
$request->attributes->set("page_title", "Chiffre d'affaires");

// FILTRES

// Raccourci 30 derniers jours
$dates = _getDatesOptions($_SITE->get("id"), "%Y-%m-%d", "l j f", "d");
$months = _getDatesOptions($_SITE->get("id"), "%Y-%m", "F Y", "m");
$years = _getDatesOptions($_SITE->get("id"), "%Y", "Y", "y");

// Affichage par défaut : ventes du jour courant
if (empty($_GET["date1"]) && empty($_GET["d"]) && empty($_GET["m"]) && empty($_GET['y'])) $_GET["m"] = date("Y-m");

// Raccourcis mois ou jour
if (!empty($_GET["d"])) {
    $_GET['date1'] = $_GET['d'];
    $_GET['date2'] = $_GET['d'];
    $_GET['time1'] = '00:00';
    $_GET['time2'] = '23:59';
} elseif (!empty($_GET["m"])) {
    $_GET['date1'] = $_GET['m'].'-01';
    $_GET['date2'] = $_GET['m'].'-'.date('t', strtotime($_GET['m']));
    $_GET['time1'] = '00:00';
    $_GET['time2'] = '23:59';
} elseif (isset($_GET["y"])) {
    $_GET['date1'] = $_GET['y'].'-01-01';
    $_GET['date2'] = $_GET['y'].'-12-31';
    $_GET['time1'] = '00:00';
    $_GET['time2'] = '23:59';
}

// REQUETE DES VENTES

$_QUERY = null;

// Filtrer par date
if (!empty($_GET["date1"])) {
    $_QUERY .= ' AND `order_payment_date` >= :date_1 AND `order_payment_date` <= :date_2';
    $params['date_1'] = $_GET['date1'].' '.$_GET['time1'].':00';
    $params['date_2'] = $_GET['date2'].' '.$_GET['time2'].':00';
}

// Filtrer par état
$condition = $request->query->get('condition', false);
if ($condition) {
    if ($condition == "new") {
        $_QUERY .= " AND `stock_condition` LIKE '%Neuf%' OR  `stock_condition` = '%Neuf%' ";
    } elseif ($condition == "used") {
        $_QUERY .= " AND `stock_condition` NOT LIKE '%Neuf%' ";
    }
}

/** @var PDO $_SQL */
$query = EntityManager::prepareAndExecute('SELECT
    `s`.`stock_id`, `stock_selling_price`, `stock_selling_price_ht`, `stock_selling_price_tva`, `stock_tva_rate`, `stock_condition`, `stock_selling_date`,
    `o`.`order_id`, `order_payment_date`, `order_shipping`, `order_type`,
    `a`.`article_id`, `article_tva`, `type_id`, `article_pubdate`, `article_links`,
    `customer_type`
    FROM `stock` AS `s`
    JOIN `orders` AS `o` ON `s`.`order_id` = `o`.`order_id`
    JOIN `articles` AS `a` ON `s`.`article_id` = `a`.`article_id`
    LEFT JOIN `customers` AS `c` ON `c`.`customer_id` = `o`.`customer_id`
    WHERE `s`.`site_id` = :site_id AND `o`.`site_id` = :site_id AND `o`.`order_payment_date` IS NOT NULL AND `stock_selling_date` IS NOT NULL '.$_QUERY.'
    ', array_merge($params, ["site_id" => $_SITE->get("id")])
);

$sales = $query->fetchAll();

$total_ht = 0;
$total_ttc = 0;
$total_port_ht = 0;
$total_port_ttc = 0;
$tva = array();
$total_sales = array();

// Taux de TVA
$rates = tva_rate('all');
if (!empty($rates)) {
    foreach ($rates as $r) {
        $tva[$r*10]['rate'] = $r;
        $tva[$r*10]['revenue_ht'] = 0;
        $tva[$r*10]['revenue_ttc'] = 0;
        $tva[$r*10]['revenue_tva'] = 0;
    }
}

// Types d'articles
$ty = [];
$types = Biblys\Article\Type::getAll();
$type_r = array();
foreach ($types as $t) {
    $ty[$t->getId()]['name'] = $t->getName();
    $ty[$t->getId()]['revenue_ht'] = 0;
    $ty[$t->getId()]['revenue_ttc'] = 0;
    $ty[$t->getId()]['sales'] = array();
}

// Rayons
$rayons = $_SQL->query('SELECT `rayon_id`, `rayon_name` FROM `rayons` WHERE `site_id` = '
    .$_SITE->get("id").' ORDER BY `rayon_order`');
$rayons = $rayons->fetchAll(PDO::FETCH_ASSOC);
$ra = array();
foreach ($rayons as $r) {
    $ra[$r['rayon_id']]['name'] = $r['rayon_name'];
    $ra[$r['rayon_id']]['revenue_ht'] = 0;
    $ra[$r['rayon_id']]['revenue_ttc'] = 0;
    $ra[$r['rayon_id']]['sales'] = array();
    $ra[$r['rayon_id']]['sales_stock'] = array();
}

// Sans rayon
$ra[0] = array('name' => 'Sans rayon', 'revenue_ht' => 0, 'revenue_ttc' => 0, 'sales' => array());


// Ancienneté des articles
$m3_ttc = 0; // moins de trois mois (nouveautés)
$y1_ttc = 0; // moins d'un an
$old_ttc = 0; // plus d'un an (fonds)
$uk_ttc = 0; // date de parution inconnue
$m3_ht = 0; // moins de trois mois (nouveautés)
$y1_ht = 0; // moins d'un an
$old_ht = 0; // plus d'un an (fonds)
$uk_ht = 0; // date de parution inconnue

// Etat des exemplaires
$total_new_ht = 0;
$total_new_ttc = 0;
$total_used_ht = 0;
$total_used_ttc = 0;

// Lieu de vente
$total_shop_ht = 0;
$total_shop_ttc = 0;
$total_web_ht = 0;
$total_web_ttc = 0;

// Type de clients
$part_ht = 0;
$part_ttc = 0;
$part_sales = array();
$pro_ht = 0;
$pro_ttc = 0;
$pro_sales = array();
$coll_ht = 0;
$coll_ttc = 0;
$coll_sales = array();
$lib_ht = 0;
$lib_ttc = 0;
$lib_sales = array();
$ukc_ht = 0;
$ukc_ttc = 0;
$ukc_sales = array();

foreach ($sales as $s) {

    // Prix HT
    if ($_SITE->get("tva")) {
        $rate = $s['stock_tva_rate'] * 10;

        if ($rate) {
            $tva[$rate]['revenue_ht'] += $s['stock_selling_price_ht'];
            $tva[$rate]['revenue_ttc'] += $s['stock_selling_price'];
        }

    } else {
        $s['stock_selling_price_ht'] = $s['stock_selling_price'];
    }

    // Total
    $total_ttc += $s['stock_selling_price'];
    $total_ht += $s['stock_selling_price_ht'];
    $total_sales[] = $s['article_id'];

    // Par type d'article
    $ty[$s['type_id']]['revenue_ttc'] += $s['stock_selling_price'];
    $ty[$s['type_id']]['revenue_ht'] += $s['stock_selling_price_ht'];
    $ty[$s['type_id']]['sales'][] += $s['article_id'];

    // Par rayon
    $s['rayons'] = 0;
    if (preg_match_all('/\[rayon:(\d*)]/', $s['article_links'], $matches)) {

        foreach ($matches as $m) // Tous les rayons trouvés
        {
            foreach($m as $rm) // Pour chaque rayon trouvé
            {
                if (isset($ra[$rm])) // Si le rayon est un rayon de la librairie
                {
                    $ra[$rm]['revenue_ttc'] += $s['stock_selling_price'];
                    $ra[$rm]['revenue_ht'] += $s['stock_selling_price_ht'];
                    $ra[$rm]['sales'][] = $s['article_id'];
                    $ra[$rm]['sales_stock'][] = $s['stock_id'];
                    $s['rayons']++;
                }
            }
        }
    }

    // Si aucun rayon, on ajoute l'exemplaire à "Sans rayons"
    if ($s['rayons'] == 0)
    {
        $ra[0]['revenue_ttc'] += $s['stock_selling_price'];
        $ra[0]['revenue_ht'] += $s['stock_selling_price_ht'];
        $ra[0]['sales'][] = $s['article_id'];
    }

    //if (empty($s['rayon_id'])) $s['rayon_id'] = 0;

    // Par ancienneté des articles
    if ($s['stock_selling_date'] < date('Y-m-d H:i:s', strtotime($s['article_pubdate'].'+ 3 months'))) // Moins de trois mois
    {
        $m3_ttc += $s['stock_selling_price'];
        $m3_ht += $s['stock_selling_price_ht'];
    }
    elseif ($s['stock_selling_date'] < date('Y-m-d H:i:s', strtotime($s['article_pubdate'].'+ 1 years'))) // Moins d'un an
    {
        $y1_ttc += $s['stock_selling_price'];
        $y1_ht += $s['stock_selling_price_ht'];
    }
    elseif (!empty($s['article_pubdate']) && $s['article_pubdate'] != 0000-00-00) // Si la date n'est pas vide : plus d'un an
    {
        $old_ttc += $s['stock_selling_price'];
        $old_ht += $s['stock_selling_price_ht'];
    }
    else // La date est vide
    {
        $uk_ttc += $s['stock_selling_price'];
        $uk_ht += $s['stock_selling_price_ht'];
    }

    // Par état des exemplaires (neuf/occasion)
    if ($s['stock_condition'] == 'Neuf')
    {
        $total_new_ttc += $s['stock_selling_price'];
        $total_new_ht += $s['stock_selling_price_ht'];
    }
    elseif (!empty($s['stock_condition']))
    {
        $total_used_ttc += $s['stock_selling_price'];
        $total_used_ht += $s['stock_selling_price_ht'];
    }

    // Par lieu de vente (magasin/site)
    if ($s['order_type'] == 'shop')
    {
        $total_shop_ttc += $s['stock_selling_price'];
        $total_shop_ht += $s['stock_selling_price_ht'];
    }
    elseif ($s['order_type'] == 'web')
    {
        $total_web_ttc += $s['stock_selling_price'];
        $total_web_ht += $s['stock_selling_price_ht'];
    }

    // Frais de port
    $ship[$s['order_id']]['date'] = $s['order_payment_date'];
    $ship[$s['order_id']]['fee'] = $s['order_shipping'];

    // Par type de client
    if ($s['customer_type'] == 'Particulier') $c = 'part';
    elseif ($s['customer_type'] == 'Professionnel') $c = 'pro';
    elseif ($s['customer_type'] == 'Collectivité') $c = 'coll';
    elseif ($s['customer_type'] == 'Libraire') $c = 'lib';
    else $c = 'ukc';
    $c_ht = $c.'_ht';
    $c_ttc = $c.'_ttc';
    $c_sales = $c.'_sales';
    $$c_ht += $s['stock_selling_price_ht'];
    $$c_ttc += $s['stock_selling_price'];
    array_push($$c_sales, $s['article_id']);

}

// Type de client non-libraire
$nonlib_ht = $total_ht - $lib_ht;
$nonlib_ttc = $total_ttc - $lib_ttc;
$nonlib_sales = count($total_sales) - count($lib_sales);

// Frais de port
$checked["shipping"] = null;
if (empty($_POST['hide_shipping']))
{
    if (isset($ship))
    {
        foreach ($ship as $s)
        {
            if (!empty($s['fee']))
            {

                // Port HT
                if (getLegacyCurrentSite()['site_tva'])
                {
                    $s['tva_rate'] = tva_rate(3,$s["date"]) / 100;
                    $s['ti'] = $s['tva_rate'] * 1000;
                    $s['fee_ht'] = $s['fee'] / (1 + $s['tva_rate']);
                    $tva[$s['ti']]['revenue_ht'] += $s['fee_ht']; // Par taux de TVA (HT)
                    $tva[$s['ti']]['revenue_ttc'] += $s['fee']; // Par taux de TVA (TTC)
                    $total_port_ht += $s['fee_ht'];
                }

                $total_port_ttc += $s['fee'];
            }
        }
        //$total_ht += $total_port_ht;
        //$total_ttc += $total_port_ttc;
    }
}

// Tableau par taux de TVA
$tva_table = null;
if (isset($tva))
{
    foreach ($tva as $k => $v)
    {
        if (!empty($v['revenue_ttc']))
        {
            $v['tva_amount'] = $v['revenue_ttc'] - $v['revenue_ht'];
            $tva_table .= '
                    <tr>
                        <td class="right">'.$v['rate'].' %</td>
                        <td class="right">'.price($v['tva_amount'], 'EUR').'</td>
                        <td class="right">'.price($v['revenue_ht'], 'EUR').'</td>
                        <td class="right text-success bg-success">'.price($v['revenue_ttc'], 'EUR').'</td>
                    </tr>
                ';
        }
    }
}

// Tableau par type
$type_table = null;
if (isset($ty))
{
    foreach ($ty as $k => $v)
    {
        if (!empty($v['revenue_ttc']))
        {
            $type_table .= '
                    <tr>
                        <td>'.$v['name'].'</td>
                        <td class="right">'.price($v['revenue_ht'], 'EUR').'</td>
                        <td class="right text-success bg-success">'.price($v['revenue_ttc'], 'EUR').'</td>
                    </tr>
                ';
        }
    }
}

// Tableau par rayon
$rayon_table = null;
if (isset($ra))
{
    foreach ($ra as $k => $v)
    {
        if (!empty($v['revenue_ttc']))
        {
            if (!empty($total_ttc)) $v['share'] = round(($v['revenue_ttc'] / $total_ttc) * 100, 2);
            else $v['share'] = 0;

            $rayon_table .= '
                    <tr>
                        <td>'.$v['name'].'</td>
                        <td class="right"><a href="/pages/adm_sales_detail?date1='.$_GET['date1'].'&time1='.$_GET['time1'].'&date2='.$_GET['date2'].'&time2='.$_GET['time2'].'&rayon_id='.$k.'">'.count($v['sales']).'</a></td>
                        <td class="right">'.price($v['revenue_ht'], 'EUR').'</td>
                        <td class="right text-success bg-success">'.price($v['revenue_ttc'], 'EUR').'</td>
                        <td class="right">'.$v['share'].'&nbsp;%</td>
                    </tr>
                ';
        }
    }
}

$content = '

        <h1><span class="fa fa-money"></span> '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>

        <p>
            <label for="d">Raccourcis :</label>
            <select name="d" class="goto">
                <option>30 derniers jours...</option>
                '.join($dates).'
            </select>

            <select name="m" class="goto">
                <option>Mois de...</option>
                '.join($months).'
            </select>

            <select name="y" class="goto">
                <option>Année...</option>
                '.join($years).'
            </select>
        </p>

        <form class="fieldset">
            <fieldset>
                <legend>Filtres</legend>

                <p>
                    <label for="date1">Du :</label>
                    <input type="date" name="date1" id="date1" placeholder="AAAA-MM-JJ" value="'.$_GET["date1"].'"> &agrave;
                    <input type="time" name="time1" id="time1" placeholder="HH:SS" value="'.$_GET["time1"].'">
                </p>

                <p>
                    <label for="date2">Au :</label>
                    <input type="date" name="date2" id="date2" placeholder="AAAA-MM-JJ" value="'.$_GET["date2"].'"> &agrave;
                    <input type="time" name="time2" id="time2" placeholder="HH:SS" value="'.$_GET["time2"].'">
                </p>

                <p>
                    <label for="stock_condition">État :</label>
                    <select name="condition" id="stock_condition">
                        <option value="all">Tous</a>
                        <option value="new"'.($condition == "new" ? " selected" : null).'>Neuf</a>
                        <option value="used"'.($condition == "used" ? " selected" : null).'>Occasion</a>
                    </select>
                </p>

                <p class="center">
                    <button type="submit" class="btn btn-default">Afficher le chiffre d\'affaire</button>
                </p>

            </fieldset>
        </form>

        <h3>Chiffre d\'affaires total</h3>

        <table class="admin-table">
            <thead>
                <tr>
                    <th></th>
                    <th title="Nombre d\'exemplaires vendus">Exemplaires</th>
                    <th title="Nombre de références différentes (articles) vendus">Articles</th>
                    <th>CA HT</th>
                    <th>CA TTC</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ventes</td>
                    <td class="right">'.count($total_sales).'</td>
                    <td class="right">'.count(array_unique($total_sales)).'</td>
                    <td class="right">'.price($total_ht,'EUR').'</td>
                    <td class="right text-success bg-success">'.price($total_ttc,'EUR').'</td>
                </tr>
                <tr>
                    <td>Frais de port</td>
                    <td colspan=2></td>
                    <td class="right">'.price($total_port_ht,'EUR').'</td>
                    <td class="right text-success bg-success">'.price($total_port_ttc,'EUR').'</td>
                </tr>
                <tr>
                    <td class="text-primary bg-primary">Total</td>
                    <td class="right text-primary bg-primary" colspan=2></td>
                    <td class="right text-primary bg-primary">'.price($total_ht+$total_port_ht,'EUR').'</td>
                    <td class="right text-primary bg-primary">'.price($total_ttc+$total_port_ttc,'EUR').'</td>
                </tr>
            </tbody>
        </table>


        <h3>Chiffre d\'affaires ventilé par...</h3>

        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a role="tab" data-toggle="tab" href="#tva">TVA</a></li>
            <li><a href="#customers" role="tab" data-toggle="tab">Type de client</a></li>
            <li><a href="#age" role="tab" data-toggle="tab">Ancienneté</a></li>
            <li><a href="#condition" role="tab" data-toggle="tab">État</a></li>
            <li><a href="#type" role="tab" data-toggle="tab">Type d\'article</a></li>
            <li><a href="#location" role="tab" data-toggle="tab">Lieu de vente</a></li>
            <li><a href="#rayon" role="tab" data-toggle="tab">Rayon</a></li>
        </ul>

        <div class="tab-content">
            <br>

            <div class="tab-pane active" id="tva">

                <table class="admin-table tab-pane active">
                    <thead>
                        <tr>
                            <th>Taux</th>
                            <th>Montant TVA</th>
                            <th>CA HT</th>
                            <th>CA TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        '.$tva_table.'
                    </tbody>
                </table>

            </div>

            <div class="tab-pane" id="customers">

                <table class="admin-table tab-pane">
                    <thead>
                        <tr>
                            <th>Type de client</th>
                            <th>Ventes</th>
                            <th>CA HT</th>
                            <th>CA TTC</th>
                            <th>Part</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-success">
                            <td>Libraires</td>
                            <td class="right">'.count($lib_sales).'</td>
                            <td class="right">'.price($lib_ht, 'EUR').'</td>
                            <td class="right text-success bg-success">'.price($lib_ttc, 'EUR').'</td>
                            <td class="right">'.percent($lib_ttc, $total_ttc).'</td>
                        </tr>
                        <tr class="bg-success">
                            <td>Non-Libraires</td>
                            <td class="right">'.$nonlib_sales.'</td>
                            <td class="right">'.price($nonlib_ht, 'EUR').'</td>
                            <td class="right text-success bg-success">'.price($nonlib_ttc, 'EUR').'</td>
                            <td class="right">'.percent($nonlib_ttc, $total_ttc).'</td>
                        </tr>
                        <tr>
                            <td>Particuliers</td>
                            <td class="right">'.count($part_sales).'</td>
                            <td class="right">'.price($part_ht, 'EUR').'</td>
                            <td class="right text-success bg-success">'.price($part_ttc, 'EUR').'</td>
                            <td class="right">'.percent($part_ttc, $total_ttc).'</td>
                        </tr>
                        <tr>
                            <td>Professionnels</td>
                            <td class="right">'.count($pro_sales).'</td>
                            <td class="right">'.price($pro_ht, 'EUR').'</td>
                            <td class="right text-success bg-success">'.price($pro_ttc, 'EUR').'</td>
                            <td class="right">'.percent($pro_ttc, $total_ttc).'</td>
                        </tr>
                        <tr>
                            <td>Collectivités</td>
                            <td class="right">'.count($coll_sales).'</td>
                            <td class="right">'.price($coll_ht, 'EUR').'</td>
                            <td class="right text-success bg-success">'.price($coll_ttc, 'EUR').'</td>
                            <td class="right">'.percent($coll_ttc, $total_ttc).'</td>
                        </tr>
                        <tr>
                            <td>Clients inconnus</td>
                            <td class="right">'.count($ukc_sales).'</td>
                            <td class="right">'.price($ukc_ht, 'EUR').'</td>
                            <td class="right text-success bg-success">'.price($ukc_ttc, 'EUR').'</td>
                            <td class="right">'.percent($ukc_ttc, $total_ttc).'</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="tab-pane" id="age">

                <table id="revenue-age" class="admin-table">
                    <thead>
                        <tr>
                            <th title="Par rapport à la date de parution au moment de la vente">Ancienneté</th>
                            <th>CA HT</th>
                            <th>CA TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Moins de 3 mois (nouveautés)</td>
                            <td class="right">'.price($m3_ht,'EUR').'</td>
                            <td class="right text-success bg-success">'.price($m3_ttc,'EUR').'</td>
                        </tr>
                        <tr>
                            <td>Moins d\'un an</td>
                            <td class="right">'.price($y1_ht,'EUR').'</td>
                            <td class="right text-success bg-success">'.price($y1_ttc,'EUR').'</td>
                        </tr>
                        <tr>
                            <td>Un an ou plus (fonds)</td>
                            <td class="right">'.price($old_ht,'EUR').'</td>
                            <td class="right text-success bg-success">'.price($old_ttc,'EUR').'</td>
                        </tr>
                        <tr>
                            <td>Date de parution inconnue</td>
                            <td class="right">'.price($uk_ht,'EUR').'</td>
                            <td class="right text-success bg-success">'.price($uk_ttc,'EUR').'</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="tab-pane" id="condition">

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>État des exemplaires</th>
                            <th>CA HT</th>
                            <th>CA TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Neuf</td>
                            <td class="right">'.price($total_new_ht,'EUR').'</td>
                            <td class="right text-success bg-success">'.price($total_new_ttc,'EUR').'</td>
                        </tr>
                        <tr>
                            <td>Occasion</td>
                            <td class="right">'.price($total_used_ht,'EUR').'</td>
                            <td class="right text-success bg-success">'.price($total_used_ttc,'EUR').'</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="tab-pane" id="type">

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Type d\'article</th>
                            <th>CA HT</th>
                            <th>CA TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        '.$type_table.'
                    </tbody>
                </table>

            </div>

            <div class="tab-pane" id="location">

                <table id="revenue-location" class="admin-table">
                    <thead>
                        <tr>
                            <th>Lieu de vente</th>
                            <th>CA HT</th>
                            <th>CA TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>En magasin</td>
                            <td class="right">'.price($total_shop_ht,'EUR').'</td>
                            <td class="right text-success bg-success">'.price($total_shop_ttc,'EUR').'</td>
                        </tr>
                        <tr>
                            <td>En VPC</td>
                            <td class="right">'.price($total_web_ht,'EUR').'</td>
                            <td class="right text-success bg-success">'.price($total_web_ttc,'EUR').'</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="tab-pane" id="rayon">
                <table id="revenue-rayon" class="admin-table">
                    <thead>
                        <tr>
                            <th>Rayon</th>
                            <th>Ventes</th>
                            <th>CA HT</th>
                            <th>CA TTC</th>
                            <th>Part</th>
                        </tr>
                    </thead>
                    <tbody>
                        '.$rayon_table.'
                    </tbody>
                </table>
            </div>
        </div>

        <br><br><br><br><br><br><br><br><br>


    ';

/**
 * @param int $siteId
 * @param string $queryFormat
 * @param string $displayFormat
 * @param string $parameter
 * @return string[]
 * @throws Exception
 */
function _getDatesOptions(
    int $siteId,
    string $queryFormat,
    string $displayFormat,
    string $parameter
): array
{
    $datesQuery = EntityManager::prepareAndExecute("
    SELECT 
        DATE_FORMAT(`order_payment_date`, :format) AS `date`
    FROM `orders` 
    WHERE `orders`.`site_id` = :site_id 
        AND `order_cancel_date` IS null
        AND `order_payment_date` != '0000-00-00 00:00:00'
    GROUP BY `date`
    ORDER BY `date` DESC
    LIMIT 30
", ["format" => $queryFormat, "site_id" => $siteId]);
    $datesOptions = array_map(function ($date) use ($displayFormat, $parameter) {
        return '<option value="?'.$parameter.'='.$date["date"].'">'
            ._date($date["date"], $displayFormat).
            '</option>';
    }, $datesQuery->fetchAll());
    return $datesOptions;
}

return new Response($content);
