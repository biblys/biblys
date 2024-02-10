<?php

use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @throws InvalidDateFormatException
 * @throws PropelException
 */
return function (Request $request): Response
{
    $TVA = array();

    $request->attributes->set("page_title", "Ventes");

    $dates = null;
    $orders = EntityManager::prepareAndExecute(
        "SELECT DATE_FORMAT(`order_payment_date`, '%Y-%m-%d') as `date`, MAX(`order_payment_date`)
    FROM `orders`
    WHERE
        `orders`.`site_id` = :site_id AND
        `order_payment_date` > SUBDATE(NOW(), INTERVAL 1 MONTH) AND
        `order_payment_date` IS NOT NULL AND
        `order_cancel_date` IS null
    GROUP BY `date`
    ORDER BY `date` DESC",
        ['site_id' => $GLOBALS["LEGACY_CURRENT_SITE"]->get('id')]
    );
    while ($o = $orders->fetch(PDO::FETCH_ASSOC)) {
        $dates .= '<option value="?d=' . $o["date"] . '">' . _date($o["date"], "l j F") . '</option>';
    }

    $months = null;
    $mois = EntityManager::prepareAndExecute(
        "SELECT DATE_FORMAT(`order_payment_date`, '%Y-%m') as `date`, MAX(`order_payment_date`)
    FROM `orders`
    WHERE `orders`.`site_id` = :site_id 
      AND `order_cancel_date` IS NULL
      AND `order_payment_date` IS NOT NULL
    GROUP BY `date`
    ORDER BY `date` DESC",
        ["site_id" => $GLOBALS["LEGACY_CURRENT_SITE"]->get("id")]
    );
    while ($m = $mois->fetch(PDO::FETCH_ASSOC)) {
        $months .= '<option value="?m=' . $m["date"] . '">' . _date($m["date"], "F Y") . '</option>';
    }

    $cash_sel = null;
    $card_sel = null;
    $cheque_sel = null;
    $paypal_sel = null;
    if (isset($_GET['p'])) {
        if ($_GET["p"] == "order_payment_cash") $cash_sel = 'selected="selected"';
        elseif ($_GET["p"] == "order_payment_card") $card_sel = 'selected="selected"';
        elseif ($_GET["p"] == "order_payment_cheque") $cheque_sel = 'selected="selected"';
        elseif ($_GET["p"] == "order_payment_paypal") $paypal_sel = 'selected="selected"';
    }

    $neuf_sel = null;
    $occaz_sel = null;
    if (isset($_GET['e'])) {
        if ($_GET["e"] == "neuf") $neuf_sel = 'selected="selected"';
        elseif ($_GET["e"] == "occaz") $occaz_sel = 'selected="selected"';
    }

// Affichage par défaut : ventes du jour
    if (empty($_GET["date1"]) && empty($_GET["d"]) && empty($_GET["m"])) $_GET["d"] = date("Y-m-d");

// Raccourcis mois ou jour
    if (!empty($_GET["d"])) {
        $_GET["date1"] = $_GET["d"];
        $_GET["date2"] = $_GET["d"];
    } elseif (!empty($_GET["m"])) {
        $_GET["date1"] = $_GET["m"] . "-01";
        $_GET["date2"] = $_GET["m"] . "-" . date("t", strtotime($_GET["m"]));
    }

    if (empty($_GET["time1"])) $_GET["time1"] = "00:00";
    if (empty($_GET["time2"])) $_GET["time2"] = "23:59";

    $req = null;
    $sqlParams = ['site_id' => $GLOBALS["LEGACY_CURRENT_SITE"]->get('id')];

    if (!empty($_GET["date1"])) {
        $req .= "AND `order_payment_date` >= '" . $_GET["date1"] . " " . $_GET["time1"] . ":00' AND `order_payment_date` <= '" . $_GET["date2"] . " " . $_GET["time2"] . ":59'";
    }
    if (!empty($_GET["p"])) {
        $req .= " AND `" . $_GET["p"] . "` != '0' ";
    }
    if (!empty($_GET["e"])) {
        if ($_GET["e"] == "neuf") $req .= " AND `stock_condition` = 'Neuf' ";
        else $req .= " AND `stock_condition` != 'Neuf' ";
    }

    $request = Request::createFromGlobals();
    $customerId = intval($request->query->get("customer_id"));
    if ($customerId) {
        dump($customerId);
        $req .= " AND `orders`.`customer_id` = :customer_id ";
        $sqlParams["customer_id"] = $customerId;
    }

    $sql = $GLOBALS["_SQL"]->prepare(
        "SELECT
        `article_id`, `article_title`, `article_url`, `article_authors`, `article_collection`,
        `article_number`, `article_tva`, `stock_id`, `stock_condition`, `stock_shop`,
        `stock_selling_price`, `stock_selling_price_ht`, `stock_return_date`, `stock_selling_date`,
        `stock_tva_rate`, `order_id`, `order_type`, `order_url`, `order_amount`, `order_firstname`,
        `order_lastname`, `order_payment_date`,`order_payment_cash`, `order_payment_cheque`,
        `order_payment_transfer`,
        `order_payment_card`, `order_payment_paypal`,  `order_payment_left`, `order_shipping`,
        `axys_accounts`.`axys_account_id`, `axys_account_email`,
        `axys_account_last_name`, `axys_account_first_name`,
        `customers`.`customer_id`, `customer_first_name`, `customer_last_name`
    FROM `stock`
    JOIN `articles` USING(`article_id`)
    JOIN `orders` USING(`order_id`)
    JOIN `collections` USING(`collection_id`)
    LEFT JOIN `axys_accounts` ON `axys_accounts`.`axys_account_id` = `orders`.`axys_account_id`
    LEFT JOIN `customers` ON `orders`.`customer_id` = `customers`.`customer_id`
    WHERE `orders`.`site_id` = :site_id $req
    GROUP BY `stock_id` ORDER BY `order_payment_date` ASC"
    );
    $sql->execute($sqlParams);

    $content = '
    <h1>
        <span class="fa fa-line-chart"></span>
        Ventes
    </h1>

        <label for="d">Raccourcis :</label>
        <select name="d" class="goto">
            <option>30 derniers jours...</option>
            ' . $dates . '
        </select>
        <select name="m" class="goto">
            <option>Mois de...</option>
            ' . $months . '
        </select>
        <br /><br />

    <form method="get">

        <p>
            <label for="date1">Du :</label>
            <input type="date" class="date" name="date1" id="date1" value="' . $_GET["date1"] . '"> &agrave;
            <input type="time" class="time" name="time1" id="time1" value="' . $_GET["time1"] . '">
        </p>

        <p>
            <label for="date2">Au :</label>
            <input type="date" class="date" name="date2" id="date2" value="' . $_GET["date2"] . '"> &agrave;
            <input type="time" class="time" name="time2" id="time2" value="' . $_GET["time2"] . '">
        </p><br>

        <p>
            <label>Paiement :</label>
            <select name="p">
                <option value="0">Tous</option>
                <option value="order_payment_cash" ' . $cash_sel . '>Espèces</option>
                <option value="order_payment_cheque" ' . $cheque_sel . '>Chèque</option>
                <option value="order_payment_card" ' . $card_sel . '>Carte bancaire</option>
                <option value="order_payment_paypal" ' . $paypal_sel . '>Paypal</option>
            </select>
        </p>
        <p>
            <label>État :</label>
            <select name="e">
                <option value="0">Tous</option>
                <option value="neuf" ' . $neuf_sel . '>Neuf</option>
                <option value="occaz" ' . $occaz_sel . '>Occasion</option>
            </select>
        </p>
        <p>
            <label for="u">Client n°</label>
            <input 
                type="number" 
                class="short" 
                name="customer_id" 
                id="customer_id" 
                value="' . $customerId . '" />
            <br />
        </p>

        <div class="center"><input type="submit" value="Afficher" /></div>

    </form>
    <br /><br />

';

    $content .= '<table class="liste orders">
    <thead>
    <tr>
    <th></th>
    <th class="right">Ref.</th>
    <th colspan="6">Titre</th>
    <th>Prix</th>
    </tr>
    </thead>
    <tbody>
';

    $order_id = null;
    $TotalCash = 0;
    $TotalLeft = 0;
    $TotalCard = 0;
    $TotalPaypal = 0;
    $TotalCheque = 0;
    $TotalTransfer = 0;
    $Total = null;
    $TotalHT = null;
    $TotalNeuf = 0;
    $TotalOccasion = 0;
    $TotalShipping = 0;
    $c['TotalHT'] = 0;
    while ($l = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($order_id != $l["order_id"]) {
            if (!empty($c["total"])) {
                if (!empty($c["shipping"])) {
                    $c["total_shipping"] = '
                    <tr>
                    <td colspan="8" class="right">Frais de port :</td>
                    <td class="right">' . price($c["shipping"], 'EUR') . '</td>
                    </tr>
';
                    $TotalShipping += $c["shipping"];
                    // TVA Frais de port
                    $c["tva"] = 1 + tva_rate(3, $l["order_payment_date"]) / 100;
                    $c["totalHT"] += round($c["shipping"] / $c["tva"]);
                    $TotalHT += round($c["shipping"] / $c["tva"]);
                    if (!isset($TVA[tva_rate(3, $l["order_payment_date"])])) $TVA[tva_rate(3, $l["order_payment_date"])] = 0;
                    $TVA[tva_rate(3, $l["order_payment_date"])] += $c["shipping"] - round($c["shipping"] / $c["tva"]);
                } else $c["total_shipping"] = null;
                $content .= '
                ' . $c["total_shipping"] . '
                <tr>
                    <td colspan="8" class="right">Total HT :</td>
                    <td class="right">' . price($c["totalHT"], 'EUR') . '</td>
                </tr>
                <tr>
                    <td colspan="8" class="right"><strong>Total TTC :</strong></td>
                    <td class="right"><strong>' . price($c["total"] + $c["shipping"], 'EUR') . '</strong></td>
                </tr>
            ';
            }
            $c["totalHT"] = 0;
            $c["total"] = 0;
            $l["total_payments"] = $l["order_payment_cash"] + $l["order_payment_cheque"] + $l["order_payment_card"] - $l["order_payment_left"];
            if ($l["order_type"] == "web") $l["type"] = 'Commande VPC';
            else $l["type"] = 'Achat en magasin';
            $content .= '
            <tr id="order_' . $l["order_id"] . '">
                <td colspan="3">
                    <strong>' . $l["type"] . ' n&deg; <a href="/order/' . $l["order_url"] . '">' . $l["order_id"] . '</a></strong><br />';
            if (!empty($l["customer_id"])) $content .= '<p>Client&nbsp;: <a href="/pages/adm_customer?id=' . $l["customer_id"] . '">' . trim($l['customer_first_name'] . ' ' . $l['customer_last_name']) . '</a></p>';
            elseif (!empty($l['axys_account_id'])) $content .= '<p><a href="/pages/adm_orders_shop?u=' . $l['axys_account_id'] . '">' . user_name($l) . '</a></p>';
            $content .= '
                    ' . _date($l["order_payment_date"], 'L j F Y - H:i') . '<br />
                    <a href="/pages/adm_order?order_id=' . $l["order_id"] . '">modifier</a> | <a href="/pages/adm_order?order_id=' . $l["order_id"] . '&delete=1" data-confirm="Voulez-vous vraiment ANNULER cet achat et remettre les livres en vente ?">annuler</a>
                </td>
            ';
            if ($l["order_payment_cash"]) {
                $content .= '
                    <td class="center">
                        Espèces<br />' . price($l["order_payment_cash"], 'EUR') . '
                    </td>
                ';
            }
            if ($l["order_payment_cheque"]) {
                $content .= '
                    <td class="center">
                        Chèque<br />' . price($l["order_payment_cheque"], 'EUR') . '
                    </td>
                ';
            }
            if ($l["order_payment_cheque"]) {
                $content .= '
                    <td class="center">
                        Chèque<br />' . price($l["order_payment_cheque"], 'EUR') . '
                    </td>
                ';
            }
            if ($l["order_payment_transfer"]) {
                $content .= '
                    <td class="center">
                        Virement<br />' . price($l["order_payment_cheque"], 'EUR') . '
                    </td>
                ';
            }
            if ($l["order_payment_card"]) {
                $content .= '
                    <td class="center">
                        Carte bancaire<br />' . price($l["order_payment_card"], 'EUR') . '
                    </td>
                ';
            }
            if ($l["order_payment_paypal"]) {
                $content .= '
                    <td class="center">
                        Paypal<br />' . price($l["order_payment_paypal"], 'EUR') . '
                    </td>
                ';
            }
            $content .= '
                    <td class="center">
                    Rendu<br />' . price($l["order_payment_left"], 'EUR') . '
                </td>
                <td class"right">' . price($l["total_payments"], 'EUR') . '</td>
            </tr>
        ';
            $TotalCash += $l["order_payment_cash"];
            $TotalLeft += $l["order_payment_left"];
            $TotalCheque += $l["order_payment_cheque"];
            $TotalTransfer += $l["order_payment_transfer"];
            $TotalCard += $l["order_payment_card"];
            $TotalPaypal += $l["order_payment_paypal"];
            $order_id = $l["order_id"];
        }

        // TVA
        $l["tva_rate"] = $l['stock_tva_rate'];
        $tvaRateIndex = (string) $l["tva_rate"];
        $l["tva"] = 1 + $l["tva_rate"] / 100;
        if (!isset($TVA[$tvaRateIndex])) $TVA[$tvaRateIndex] = 0;
        $TVA[$tvaRateIndex] += $l["stock_selling_price"] - round($l["stock_selling_price"] / $l["tva"]);

        $l["PrixHT"] = round($l["stock_selling_price"] / $l["tva"]);

        $c["payment_date"] = $l["order_payment_date"];
        $c["shipping"] = $l["order_shipping"];

        $c["totalHT"] += $l["PrixHT"];
        $c["total"] += $l["stock_selling_price"];

        $TotalHT += $l["PrixHT"];
        $Total += $l["stock_selling_price"];

        if ($l["stock_condition"] == "Neuf") $TotalNeuf += $l["stock_selling_price"];
        else $TotalOccasion += $l["stock_selling_price"];

        $content .= '
        <tr>
            <td></td>
            <td class="center"><a href="/pages/adm_stock?id=' . $l["stock_id"] . '">' . $l["stock_id"] . '</a></td>
            <td colspan="6">
                <a href="/a/' . $l["article_url"] . '">' . $l["article_title"] . '</a><br />
                <em>' . $l["article_authors"] . '</em><br />
                ' . $l["article_collection"] . ' ' . numero($l["article_number"]) . '<br />
            </td>
            <td 
                title="HT : ' . currency($l['stock_selling_price_ht'] ?? 0, true) . ' / TVA : ' . $l['stock_tva_rate'] . '%" 
                class="nowrap right"
            >
                ' . price($l["stock_selling_price"], 'EUR') . '
            </td>
        </tr>
    ';
    }

    if (!empty($c["shipping"])) {
        $c["total_shipping"] = '
        <tr>
        <td colspan="8" class="right">Frais de port :</td>
        <td class="right">' . price($c["shipping"], 'EUR') . '</td>
        </tr>
';
        $Total += $c["shipping"];
        $TotalShipping += $c["shipping"];

        // TVA Frais de port
        $c["tva"] = 1 + tva_rate(3, $c["payment_date"]) / 100;
        $c["totalHT"] += round($c["shipping"] / $c["tva"]);
        $TotalHT += round($c["shipping"] / $c["tva"]);
        if (!isset($TVA[tva_rate(3, $c["payment_date"])])) $TVA[tva_rate(3, $c["payment_date"])] = 0;
        $TVA[tva_rate(3, $c["payment_date"])] += $c["shipping"] - round($c["shipping"] / $c["tva"]);
    } else $c["total_shipping"] = null;

    if (!isset($c['totalHT'])) $c['totalHT'] = 0;
    if (!isset($c['total'])) $c['total'] = 0;
    if (!isset($c['shipping'])) $c['shipping'] = 0;

    $content .= '
        ' . $c["total_shipping"] . '
        <tr>
            <td colspan="8" class="right">Total HT :</td>
            <td class="right">' . price($c["totalHT"], 'EUR') . '</td>
        </tr>
        <tr>
            <td colspan="8" class="right"><strong>Total TTC :</strong></td>
            <td class="right"><strong>' . price($c["total"] + $c["shipping"], 'EUR') . '</strong></td>
        </tr>
    </tbody>
</table>';

    $TotalPayments = $TotalCash + $TotalCheque + $TotalCard + $TotalPaypal + $TotalTransfer - $TotalLeft;

// TVA
    $tva_th = null;
    $tva_tb = null;
    $ti = 0;
    foreach ($TVA as $rate => $amount) {
        $tva_th .= '<td class="right">TVA (' . str_replace('.', '.', $rate) . ' %) :</td>';
        $tva_tb .= '<td class="right">' . price($amount, 'EUR') . '</td>';
        $ti++;
    }

    $content .= '

<h3>Récapitulatif</h3>
<table class="admin-table">
    <tr>
        <th colspan="' . ($ti + 2) . '">TVA</th>
    </tr>
    <tr>
        <td class="right">Total HT :</td>
        ' . $tva_th . '
        <td class="right">Total TTC :</td>
    </tr>
    <tr>
        <td class="right">' . price($TotalHT, 'EUR') . '</td>
        ' . $tva_tb . '
        <td class="right">' . price($Total, 'EUR') . '</td>
    </tr>
</table>

<table class="admin-table">
    <tr class="center">
        <th colspan="2">Mode de paiement</th>
        <th colspan="2">Ventilation</th>
    </tr>
    <tr>
        <td class="right">Espèces :</td>
        <td>' . price($TotalCash - $TotalLeft, 'EUR') . '</td>
        <td class="right">Neuf :</td>
        <td>' . price($TotalNeuf, 'EUR') . '</td>
    </tr>
    <tr>
        <td class="right">Cheque :</td>
        <td>' . price($TotalCheque, 'EUR') . '</td>
        <td class="right">Occasion :</td>
        <td>' . price($TotalOccasion, 'EUR') . '</td>
    </tr>
    <tr>
        <td class="right">Carte bancaire :</td>
        <td>' . price($TotalCard, 'EUR') . '</td>
        <td class="right">Frais de port :</td>
        <td>' . price($TotalShipping, 'EUR') . '</td>
    </tr>
    <tr>
        <td class="right">Virement :</td>
        <td>' . price($TotalTransfer, 'EUR') . '</td>
    </tr>
    <tr>
        <td class="right">Paypal :</td>
        <td>' . price($TotalPaypal, 'EUR') . '</td>
    </tr>
    <tr>
        <td class="right">(Rendu :</td>
        <td>' . price($TotalLeft, 'EUR') . ')</td>
    </tr>
    <tr>
        <td class="right">Total :</td>
        <td>' . price($TotalPayments, 'EUR') . '</td>
    </tr>
</table>
';

    return new Response($content);
};
