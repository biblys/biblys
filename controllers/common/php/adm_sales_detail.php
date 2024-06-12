<?php

$_PAGE_TITLE = 'Ventes détaillées';

/* RACCOURCIS */

// Raccourci 30 derniers jours
$dates = null;
$days = $_SQL->query("SELECT DATE_FORMAT(`order_payment_date`, '%Y-%m-%d') as `date`,`order_payment_date` FROM `orders` WHERE `orders`.`site_id` = '".$_SITE["site_id"]."' AND `order_payment_date` > SUBDATE(NOW(), INTERVAL 1 MONTH) AND `order_cancel_date` IS null GROUP BY `date` ORDER BY `date` DESC");
while ($s = $days->fetch()) {
    $dates .= '<option value="?d='.$s["date"].'">'._date($s["date"],"l j F").'</option>';
}

// Raccourci mois
$months = null;
$mois = $_SQL->query("SELECT DATE_FORMAT(`order_payment_date`, '%Y-%m') as `date`,`order_payment_date` FROM `orders` WHERE `orders`.`site_id` = '".$_SITE["site_id"]."' AND `order_cancel_date` IS null GROUP BY `date` ORDER BY `date` DESC");
while ($m = $mois->fetch()) {
    if (!empty($m["date"])) $months .= '<option value="?m='.$m["date"].'">'._date($m["date"],"F Y").'</option>';
}

// Affichage par défaut : ventes du jour courant
if (empty($_GET["date1"]) && empty($_GET["d"]) && empty($_GET["m"])) $_GET["d"] = date("Y-m-d");

// Raccourcis mois ou jour
if (!empty($_GET["d"])) {
    $_GET['date1'] = $_GET['d'];
    $_GET['date2'] = $_GET['d'];
    $_GET['time1'] = '00:00';
    $_GET['time2'] = '23:59';
} elseif(!empty($_GET["m"])) {
    $_GET['date1'] = $_GET['m'].'-01';
    $_GET['date2'] = $_GET['m'].'-'.date('t', strtotime($_GET['m']));
    $_GET['time1'] = '00:00';
    $_GET['time2'] = '23:59';
}

/* REQUETE DES VENTES */

$_QUERY = null;

// Filtrer par date
if (!empty($_GET["date1"])) {
    $_QUERY .= ' AND `order_payment_date` >= :date_1 AND `order_payment_date` <= :date_2';
    $params['date_1'] = $_GET['date1'].' '.$_GET['time1'].':00';
    $params['date_2'] = $_GET['date2'].' '.$_GET['time2'].':00';
}

// Filtrer par type de livre
if (!empty($_GET["type_id"])) {
    $_QUERY .= ' AND `type_id` = :type_id';
    $params['type_id'] = $_GET['type_id'];
}

// Filtrer par rayon
if (!empty($_GET["rayon_id"]) && $_GET['rayon_id'] != 'without') {
    $_QUERY .= ' AND `article_links` LIKE :rayon';
    $params['rayon'] = '%[rayon:'.$_GET['rayon_id'].']%';
}

// Filtrer par taux de TVA
$tva = $request->query->get('tva', null);
if ($tva) {
    $_QUERY .= ' AND `stock_tva_rate` = :tva';
    $params['tva'] = $tva;
}

$stock = $_SQL->prepare('SELECT
    `article_title`, `article_url`, `article_authors`, `article_collection`, `article_publisher`,
    `stock_id`, `stock_selling_price`, `stock_condition`, `stock_selling_date`,
    `order_id`, `order_url`
    FROM `orders` AS `o`
    JOIN `stock` AS `s` USING(`order_id`)
    JOIN `articles` AS `a` ON `a`.`article_id` = `s`.`article_id`
    WHERE `o`.`site_id` = :site_id AND `order_payment_date` IS NOT null'.$_QUERY.'
    GROUP BY `s`.`stock_id`
    ORDER BY `order_payment_date`');
$params['site_id'] = $_SITE["site_id"];
$stock->execute($params) or error($orders->errorInfo());

// Export to CSV
$export = array();
$header = array('Ref.','Titre','État','Prix');

// Types
$type_options = Biblys\Article\Type::getOptions($request->query->get('type_id'));

// Rayons
$rayon_options = null;
$site_rayon = array();
$rayons = $_SQL->query("SELECT `rayon_id`, `rayon_name` FROM `rayons` WHERE `site_id` = ".$_SITE['site_id']." ORDER BY `rayon_order`");
while ($r = $rayons->fetch(PDO::FETCH_ASSOC))
{
    if (isset($_GET['rayon_id']) && $r["rayon_id"] == $_GET["rayon_id"]) $r["selected"] = ' selected'; else $r['selected'] = null;
    $rayon_options .= '<option value="'.$r["rayon_id"].'"'.$r["selected"].'>'.$r["rayon_name"].'</option>';
    $site_rayons[] = $r['rayon_id'];
}

$tbody = null;
$total_sales = 0;
$TotalAmount = 0;
while ($s = $stock->fetch(PDO::FETCH_ASSOC)) {

    //if (in_array());

    if (empty($s['stock_condition'])) $s['stock_condition'] = 'Neuf';
    elseif ($s['stock_condition'] != 'Neuf') $s['stock_condition'] = 'Occasion';

    $tbody .= '
            <tr>
                <td><a href="/order/'.$s['order_url'].'">'.$s['order_id'].'</a></td>
                <td><a href="/pages/adm_stock?id='.$s['stock_id'].'">'.$s['stock_id'].'</a></td>
                <td title="'.$s['article_authors'].'"><a href="/'.$s['article_url'].'">'.$s['article_title'].'</a></td>
                <td>'.$s['stock_condition'].'</td>
                <td class="right">'.price($s['stock_selling_price'],'EUR').'</td>
                <td>'._date($s['stock_selling_date'],'d/m').'</td>
            </tr>
        ';
    $total_sales++;
    $TotalAmount += $s["stock_selling_price"];

    $export[] = array($s['stock_id'], $s['article_title'], $s['stock_condition'], price($s['stock_selling_price']));

}
$stock->closeCursor();

$_ECHO .= '
        <a href="/pages/adm_orders_shop" class="floatR">Revenir à l\'interface actuelle</a>
        <h1><span class="fa fa-line-chart"></span> '.$_PAGE_TITLE.'</h1>

        <p>
            <label for="d">Raccourcis :</label>
            <select name="d" class="goto">
                <option>30 derniers jours...</option>
                '.$dates.'
            </select>

            <select name="m" class="goto">
                <option>Mois de...</option>
                '.$months.'
            </select>
        </p>

        <form class="fieldset">
            <fieldset>
                <legend>Options</legend>

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
                    <label for="type_id">Type :</label>
                    <select name="type_id" id="type_id" required>
                        <option value="0"></option>
                        '.join($type_options).'
                     </select>
                </p>

                <p>
                    <label for="rayon_id">Rayon :</label>
                    <select name="rayon_id" id="rayon_id" required>
                        <option value="0"></option>
                        '.$rayon_options.'
                     </select>
                </p>

                <p>
                    <label for="tva">TVA :</label>
                    <input type="number" name="tva" id="tva" step="0.1" min=0 max=100 value="'.$request->query->get('tva', false).'"> %
                </p>

                <p class="center">
                    <button class="btn btn-primary" type="submit">Afficher les ventes</button>
                </p>

            </fieldset>
        </form>

        <form action="/pages/export_to_csv" method="post" class="floatR">
            <fieldset>
                <input type="hidden" name="filename" value="ventes_'.$_SITE['site_name'].'_'.$_GET["date1"].'_'.$_GET["date2"].'">
                <input type="hidden" name="header" value="'.htmlentities(json_encode($header)).'">
                <input type="hidden" name="data" value="'.htmlentities(json_encode($export)).'">
                <button class="btn btn-default" type="submit">T&eacute;l&eacute;charger au format CSV</button>
            </fieldset>
        </form>
        <h2>'.$total_sales.' vente'.s($total_sales).'</h2><br>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Vente</th>
                    <th>Ref.</th>
                    <th>Titre</th>
                    <th>État</th>
                    <th>Prix</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                '.$tbody.'
            </tbody>
            <tfoot>
                <tr>
                    <th class="right" colspan="4">Total&nbsp;:</th>
                    <th class="right">'.price($TotalAmount,'EUR').'</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    ';

?>
