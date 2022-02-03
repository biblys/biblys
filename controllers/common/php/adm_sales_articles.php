<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$_PAGE_TITLE = 'Ventes par article en stock';

$content = null;

// FILTRES

// Raccourci 30 derniers jours
$dates = null;
/** @var PDO $_SQL */
/** @var Site $_SITE */
$days = $_SQL->query("
    SELECT 
           DATE_FORMAT(`order_payment_date`, '%Y-%m-%d') AS `date`, 
           `order_payment_date` 
    FROM `orders` 
    WHERE 
          `orders`.`site_id` = '".$_SITE["site_id"]."' 
          AND `order_payment_date` > SUBDATE(NOW(), INTERVAL 1 MONTH) 
          AND `order_cancel_date` IS null 
    GROUP BY `date`, `order_payment_date`  
    ORDER BY `date` DESC
");
while ($o = $days->fetch()) {
    $dates .= '<option value="?d='.$o["date"].'">'._date($o["date"],"l j F").'</option>';
}

$year = date("Y");

// Raccourci mois
$months = null;
$mois = $_SQL->query("
    SELECT 
           DATE_FORMAT(`order_payment_date`, '%Y-%m') AS `date`, 
           DATE_FORMAT(`order_payment_date`, '%Y') AS `year`, 
           `order_payment_date` 
    FROM `orders` 
    WHERE `orders`.`site_id` = '".$_SITE["site_id"]."' 
        AND `order_cancel_date` IS null 
    GROUP BY `date`, `year`, `order_payment_date`  
    ORDER BY `date` DESC");
while ($m = $mois->fetch()) {
    if (!empty($m["date"])) $months .= '<option value="?m='.$m["date"].'">'._date($m["date"],"F Y").'</option>';
    if (!empty($m['year']) && $m['year'] != '0000') $year = $m['year'];
}

// Raccourcis années
$years = null;
while ($year <= date('Y')) {
    $years .= '<option value="?y='.$year.'">'.$year.'</option>';
    $year++;
}

// Affichage par défaut : ventes du jour courant
if (empty($_GET["date1"]) && empty($_GET["d"]) && empty($_GET["m"]) && empty($_GET["y"])) $_GET["d"] = date("Y-m-d");

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
if (!empty($_GET["date1"]))
{
    // Les livres sont en stock entre date1 et date2 si :
    $_QUERY .= ' AND `stock_purchase_date` < :date_2'; // Ils ont été achetés avant date2
    //$_QUERY .= ' AND (`stock_selling_date` IS null OR `stock_selling_date` > :date_2)'; // Ils ont été vendus jamais ou après date2
    //$_QUERY .= ' AND (`stock_return_date` IS null OR `stock_return_date` > :date_2)'; // Ils ont été retournés jamais ou après date2
    //$_QUERY .= ' AND (`stock_lost_date` IS null OR `stock_lost_date` > :date_2)'; // Ils ont été perdus jamais ou après date2

    $params['date_1'] = $_GET['date1'].' '.$_GET['time1'].':00';
    $params['date_2'] = $_GET['date2'].' '.$_GET['time2'].':00';
}

// Filtrer par type de livre
if (!empty($_GET["type_id"]))
{
    $_QUERY .= ' AND `type_id` = :type_id';
    $params['type_id'] = $_GET['type_id'];
}

$stock = $_SQL->prepare('SELECT
    `article_title`, `article_url`, `article_authors`, `article_collection`, `article_publisher`,
    COUNT(`stock_id`) AS `stock2`,
    SUM(CASE WHEN
    (`stock_selling_date` IS null OR `stock_selling_date` > :date_1) AND
    (`stock_return_date` IS null OR `stock_return_date` > :date_1) AND
    (`stock_lost_date` IS null OR `stock_lost_date` > :date_1)
    THEN 1 ELSE 0 END) AS `stock`,
    SUM(CASE WHEN `stock_selling_date` BETWEEN :date_1 AND :date_2 THEN 1 ELSE 0 END) AS `sales`,
    SUM(CASE WHEN `stock_selling_date` BETWEEN :date_1 AND :date_2 THEN `stock_selling_price` ELSE 0 END) AS `revenue`
    FROM `stock` AS `s`
    JOIN `articles` AS `a` ON `a`.`article_id` = `s`.`article_id`
    WHERE `s`.`site_id` = :site_id'.$_QUERY.'
    GROUP BY `a`.`article_id`
    ORDER BY `sales` DESC');
$params['site_id'] = $_SITE["site_id"];
$stock->execute($params) or error($stock->errorInfo());

// Types
/** @var Request $request */
$type_options = Biblys\Article\Type::getOptions($request->query->get('type_id'));

$tbody = null;
$total_articles = 0; $total_stock = null; $total_sales = null; $total_revenue = null;
while ($s = $stock->fetch(PDO::FETCH_ASSOC)) {
    if ($s['stock']) {
        $tbody .= '
                <tr>
                    <td title="'.$s['article_authors'].'"><a href="/'.$s['article_url'].'">'.$s['article_title'].'</a></td>
                    <td title="'.$s['article_publisher'].'">'.$s['article_collection'].'</a></td>
                    <td class="right">'.$s['stock'].'</td>
                    <td class="right">'.$s['sales'].'</td>
                    <td class="right">'.price($s['revenue'], 'EUR').'</td>
                </tr>
            ';

        $total_articles++;
        $total_stock += $s["stock"];
        $total_sales += $s["sales"];
        $total_revenue += $s["revenue"];
    }
}
$stock->closeCursor();

$content = '
        <a href="/pages/adm_orders_shop">Revenir à l\'interface actuelle</a>
        <h1><span class="fa fa-book"></span> '.$_PAGE_TITLE.'</h1>

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

            <select name="y" class="goto">
                <option>Année...</option>
                '.$years.'
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
                    <label for="type_id">Type :</label>
                    <select name="type_id" id="type_id" required>
                        <option value="0"></option>
                        '.join($type_options).'
                     </select>
                </p>

                <p class="center">
                    <button type="submit">Afficher les ventes</button>
                </p>

            </fieldset>
        </form>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Collection</th>
                    <th>Stock</th>
                    <th>Ventes</th>
                    <th>C.A.</th>
                </tr>
            </thead>
            <tbody>
                '.$tbody.'
            </tbody>
            <tfoot>
                <tr>
                    <th class="right">Total :</th>
                    <th>'.$total_articles.'</th>
                    <th>'.$total_stock.'</th>
                    <th>'.$total_sales.'</th>
                    <th class="right">'.price($total_revenue,'EUR').'</th>
                </tr>
            </tfoot>
        </table>
    ';

return new Response($content);
