<?php

use Biblys\Service\Config;
use Symfony\Component\HttpFoundation\Response;

$_PAGE_TITLE = 'Ventes';

$_QUERY = null;


// FILTRES

// Raccourci 30 derniers jours
$dates = null;
$days = EntityManager::prepareAndExecute(
    "SELECT DATE_FORMAT(`order_payment_date`, '%Y-%m-%d') as `date`, MAX(`order_payment_date`)
    FROM `orders`
    WHERE
        `orders`.`site_id` = :site_id AND
        `order_payment_date` > SUBDATE(NOW(), INTERVAL 1 MONTH) AND
        `order_cancel_date` IS null
    GROUP BY `date`
    ORDER BY `date` DESC",
    ["site_id" => $site->get("id")]
);
while ($o = $days->fetch()) {
    $dates .= '<option value="?d='.$o["date"].'">'._date($o["date"],"l j F").'</option>';
}

// Raccourci mois
$months = null;
$mois = EntityManager::prepareAndExecute(
    "SELECT DATE_FORMAT(`order_payment_date`, '%Y-%m') as `date`, MAX(`order_payment_date`)
    FROM `orders`
    WHERE `orders`.`site_id` = '" . getLegacyCurrentSite()["site_id"] . "' AND `order_cancel_date` IS null
    GROUP BY `date`
    ORDER BY `date` DESC",
    ["site_id" => $site->get("id")]
);
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

// REQUETE DES VENTES

// Filtrer par date
if (!empty($_GET["date1"])) {
    $_QUERY .= ' AND `order_payment_date` >= :date_1 AND `order_payment_date` <= :date_2';
    $params['date_1'] = $_GET['date1'].' '.$_GET['time1'].':00';
    $params['date_2'] = $_GET['date2'].' '.$_GET['time2'].':00';
}

$orders = EntityManager::prepareAndExecute(
    "SELECT
        `order_id`,
        `order_url`,
        `order_payment_date`,
        `order_type`,
        `o`.`user_id`,
        `user_screen_name`,
        `Email` as `user_email`,
        `user_nom` AS `user_last_name`,
        `user_prenom` AS `user_first_name`,
        `order_firstname` AS `order_first_name`,
        `order_lastname` AS `order_last_name`,
        COUNT(`stock_id`) AS `articles_num`,
        `order_amount`,
        `customer_first_name`, `customer_last_name`
    FROM `orders` AS `o`
    JOIN `stock` AS `s` USING(`order_id`)
    LEFT JOIN `users` AS `u` ON `o`.`user_id` = `u`.`id`
    LEFT JOIN `customers` AS `c` ON `o`.`customer_id` = `c`.`customer_id`
    WHERE `o`.`site_id` = :site_id'.$_QUERY.'
    GROUP BY `order_id`
    ORDER BY `order_payment_date`",
    ["site_id" => $site->get('id')]
);

$tbody = null;
$TotalAmount = 0;
while ($o = $orders->fetch(PDO::FETCH_ASSOC))
{
    $tbody .= '
            <tr>
                <td><img src="/common/icons/order_type_'.$o['order_type'].'.svg" alt="'.$o['order_type'].'" title="'.$o['order_type'].'" class="va-text-bottom" width=16> <a href="/order/'.$o['order_url'].'">'.$o['order_id'].'</a></td>
                <td title="'.$o['order_payment_date'].'">'._date($o["order_payment_date"],'d/m').'</td>
                <td>'.user_name($o).'</td>
                <td class="right">'.$o['articles_num'].'</td>
                <td class="right">'.price($o['order_amount'],'EUR').'</td>
                <td class="center">
                    <a href="/pages/adm_order?id='.$o["order_id"].'" title="Éditer"><img src="/common/icons/edit.svg" width="16" alt="Éditer" /></a>
                    <a href="/pages/adm_order?del='.$o['order_id'].'" title="Annuler" data-confirm="Voulez-vous vraiment annuler la vente n&deg; '.$o['order_id'].' et remettre '.$o['articles_num'].' exemplaire'.s($o['articles_num']).' en vente ?"><img src="/common/icons/delete.svg" width="16" alt="Éditer" /></a>
                </td>
            </tr>
        ';
    $TotalAmount += $o["order_amount"];
}
$orders->closeCursor();

$content = '
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

                <p class="center">
                    <button type="submit">Afficher les ventes</button>
                </p>

            </fieldset>
        </form>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Ref.</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Art.</th>
                    <th>Montant</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                '.$tbody.'
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3"></th>
                    <th class="right">Total :</th>
                    <th class="right">'.price($TotalAmount,'EUR').'</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    ';

return new Response($content);
