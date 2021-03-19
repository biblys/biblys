<?php

    if (!function_exists('tva_rate'))
    {
        function tva_rate($tva, $date) {

            if ($tva == 1) // Taux reduit livre
            {
                if($date < "2013-01-01" && $date >= "2012-04-01") $rate = '7';
                else $rate = '5.5';
            }
            elseif ($tva == 2) // Taux reduit presse
            {
                $rate = '2.1';
            }
            elseif ($tva == 3)
            {
                if ($date < "2014-01-01") $rate = '19.6';
                else $rate = '20';
            }

            return $rate;

        }
    }

    $TVA = array();

    $_PAGE_TITLE = 'Ventes';

    $dates = null;
    $orders = $_SQL->prepare("SELECT DATE_FORMAT(`order_payment_date`, '%Y-%m-%d') as `date`,`order_payment_date` FROM `orders` WHERE `orders`.`site_id` = :site_id AND `order_payment_date` > SUBDATE(NOW(), INTERVAL 1 MONTH) AND `order_cancel_date` IS null GROUP BY `date` ORDER BY `date` DESC");
    $orders->execute(['site_id' => $site->get('id')]);
    while ($o = $orders->fetch(PDO::FETCH_ASSOC)) {
        $dates .= '<option value="?d='.$o["date"].'">'._date($o["date"],"l j F").'</option>';
    }

    $months = null;
    $mois = EntityManager::prepareAndExecute(
        "SELECT DATE_FORMAT(`order_payment_date`, '%Y-%m') as `date`,`order_payment_date` 
        FROM `orders` 
        WHERE `orders`.`site_id` = :site_id AND `order_cancel_date` IS null 
        GROUP BY `date` ORDER BY `date` DESC",
        ["site_id" => $site->get("id")]
    );
    while ($m = $mois->fetch(PDO::FETCH_ASSOC)) {
        $months .= '<option value="?m='.$m["date"].'">'._date($m["date"],"F Y").'</option>';
    }

    $cash_sel = null; $card_sel = null; $cheque_sel = null; $paypal_sel = null;
    if (isset($_GET['p']))
    {
        if($_GET["p"] == "order_payment_cash") $cash_sel = 'selected="selected"';
        elseif($_GET["p"] == "order_payment_card") $card_sel = 'selected="selected"';
        elseif($_GET["p"] == "order_payment_cheque") $cheque_sel = 'selected="selected"';
        elseif($_GET["p"] == "order_payment_paypal") $paypal_sel = 'selected="selected"';
    }

    $neuf_sel = null; $occaz_sel = null;
    if (isset($_GET['e']))
    {
        if($_GET["e"] == "neuf") $neuf_sel = 'selected="selected"';
        elseif($_GET["e"] == "occaz") $occaz_sel = 'selected="selected"';
    }

    // Affichage par défaut : ventes du jour
    if(empty($_GET["date1"]) && empty($_GET["d"]) && empty($_GET["m"])) $_GET["d"] = date("Y-m-d");

    // Raccourcis mois ou jour
    if (!empty($_GET["d"]))
    {
        $_GET["date1"] = $_GET["d"];
        $_GET["date2"] = $_GET["d"];
    }
    elseif(!empty($_GET["m"]))
    {
        $_GET["date1"] = $_GET["m"]."-01";
        $_GET["date2"] = $_GET["m"]."-".date("t", strtotime($_GET["m"]));
    }

    if(empty($_GET["time1"])) $_GET["time1"] = "00:00";
    if(empty($_GET["time2"])) $_GET["time2"] = "23:59";

    if(!empty($_GET["date1"])) {
        $req = "AND `order_payment_date` >= '".$_GET["date1"]." ".$_GET["time1"].":00' AND `order_payment_date` <= '".$_GET["date2"]." ".$_GET["time2"].":59'";
    }
    if(!empty($_GET["p"])) {
        $req .= " AND `".$_GET["p"]."` != '0' ";
    }
    if(!empty($_GET["e"])) {
        if($_GET["e"] == "neuf") $req .= " AND `stock_condition` = 'Neuf' ";
        else $req .= " AND `stock_condition` != 'Neuf' ";
    }

    if (isset($_GET["customer_id"]) && !empty($_GET['customer_id']))
    {
        $req .= " AND `orders`.`customer_id` = '".$_GET["customer_id"]."' ";
    } else $_GET['customer_id'] = null;

    $sql = $_SQL->prepare("SELECT `article_id`, `article_title`, `article_url`, `article_authors`, `article_collection`, `article_number`, `article_tva`,
        `stock_id`, `stock_condition`, `stock_shop`, `stock_selling_price`, `stock_selling_price_ht`, `stock_return_date`, `stock_selling_date`, `stock_tva_rate`,
        `order_id`, `order_type`, `order_url`, `order_amount`, `order_firstname`, `order_lastname`, `order_payment_date`,`order_payment_cash`, `order_payment_cheque`, `order_payment_card`, `order_payment_paypal`,  `order_payment_left`, `order_shipping`,
        `Users`.`id` AS `user_id`, `Users`.`Email` AS `user_email`, `user_nom` AS `user_last_name`, `user_prenom` AS `user_first_name`,
        `customers`.`customer_id`, `customer_first_name`, `customer_last_name`
        FROM `stock`
        JOIN `articles` USING(`article_id`)
        JOIN `orders` USING(`order_id`)
        JOIN `collections` USING(`collection_id`)
        LEFT JOIN `Users` ON `Users`.`id` = `orders`.`user_id`
        LEFT JOIN `customers` ON `orders`.`customer_id` = `customers`.`customer_id`
        WHERE `orders`.`site_id` = :site_id ".$req."
        GROUP BY `stock_id` ORDER BY `order_payment_date` ASC");
    $sql->execute(['site_id' => $site->get('id')]);
    $num = $sql->rowCount();

    $_ECHO ='

        <a href="/pages/adm_sales" class="floatR">Future interface</a>
        <h1>
            <span class="fa fa-line-chart"></span>
            Ventes
        </h1>

            <label for="d">Raccourcis :</label>
            <select name="d" class="goto">
                <option>30 derniers jours...</option>
                '.$dates.'
            </select>
            <select name="m" class="goto">
                <option>Mois de...</option>
                '.$months.'
            </select>
            <br /><br />

        <form method="get">

            <p>
                <label for="date1">Du :</label>
                <input type="date" class="date" name="date1" id="date1" value="'.$_GET["date1"].'"> &agrave;
                <input type="time" class="time" name="time1" id="time1" value="'.$_GET["time1"].'">
            </p>

            <p>
                <label for="date2">Au :</label>
                <input type="date" class="date" name="date2" id="date2" value="'.$_GET["date2"].'"> &agrave;
                <input type="time" class="time" name="time2" id="time2" value="'.$_GET["time2"].'">
            </p><br>

            <p>
                <label>Paiement :</label>
                <select name="p">
                    <option value="0">Tous</option>
                    <option value="order_payment_cash" '.$cash_sel.'>Esp&egrave;ces</option>
                    <option value="order_payment_cheque" '.$cheque_sel.'>Ch&egrave;que</option>
                    <option value="order_payment_card" '.$card_sel.'>Carte bancaire</option>
                    <option value="order_payment_paypal" '.$paypal_sel.'>Paypal</option>
                </select>
            </p>
            <p>
                <label>&Eacute;tat :</label>
                <select name="e">
                    <option value="0">Tous</option>
                    <option value="neuf" '.$neuf_sel.'>Neuf</option>
                    <option value="occaz" '.$occaz_sel.'>Occasion</option>
                </select>
            </p>
            <p>
                <label for="u">Client n&deg;</label>
                <input type="text" class="short" name="customer_id" id="customer_id" value="'.$_GET["customer_id"].'" />
                <br />
            </p>

            <div class="center"><input type="submit" value="Afficher" /></div>

        </form>
        <br /><br />

    ';

    $_ECHO .= '<table class="liste orders">
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

    $order_id = null; $TotalCash = 0; $TotalLeft = 0; $TotalCard = 0; $TotalPaypal = 0; $TotalCheque = 0;
    $Total = null; $TotalHT = null; $TotalNeuf = 0; $TotalOccasion = 0; $TotalShipping = 0; $TotalPayments = 0;
    $c['TotalHT'] = 0;
    while ($l = $sql->fetch(PDO::FETCH_ASSOC))
    {
        if ($order_id != $l["order_id"])
        {
            if (!empty($c["total"]))
            {
                if(!empty($c["shipping"])) {
                    $c["total_shipping"] = '
                        <tr>
                        <td colspan="8" class="right">Frais de port :</td>
                        <td class="right">'.price($c["shipping"],'EUR').'</td>
                        </tr>
';
                        $TotalShipping += $c["shipping"];
                    // TVA Frais de port
                    $c["tva"] = 1 + tva_rate(3,$l["order_payment_date"]) / 100;
                    $c["totalHT"] += round($c["shipping"] / $c["tva"]);
                    $TotalHT += round($c["shipping"] / $c["tva"]);
                    if (!isset($TVA[tva_rate(3,$l["order_payment_date"])])) $TVA[tva_rate(3,$l["order_payment_date"])] = 0;
                    $TVA[tva_rate(3,$l["order_payment_date"])] += $c["shipping"] - round($c["shipping"] / $c["tva"]);

                } else $c["total_shipping"] = null;
                $_ECHO .= '
                    '.$c["total_shipping"].'
                    <tr>
                        <td colspan="8" class="right">Total HT :</td>
                        <td class="right">'.price($c["totalHT"],'EUR').'</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="right"><strong>Total TTC :</strong></td>
                        <td class="right"><strong>'.price($c["total"]+$c["shipping"],'EUR').'</strong></td>
                    </tr>
                ';
            }
            $c["totalHT"] = 0;
            $c["total"] = 0;
            $l["total_payments"] = $l["order_payment_cash"]+$l["order_payment_cheque"]+$l["order_payment_card"]-$l["order_payment_left"];
            if($l["order_type"] == "web") $l["type"] = 'Commande VPC';
            else $l["type"] = 'Achat en magasin';
            $_ECHO .= '
                <tr id="order_'.$l["order_id"].'">
                    <td colspan="3">
                        <strong>'.$l["type"].' n&deg; <a href="/order/'.$l["order_url"].'">'.$l["order_id"].'</a></strong><br />';
            if (!empty($l["customer_id"])) $_ECHO .= '<p>Client&nbsp;: <a href="/pages/adm_customer?id='.$l["customer_id"].'">'.trim($l['customer_first_name'].' '.$l['customer_last_name']).'</a></p>';
            elseif(!empty($l["user_id"])) $_ECHO .= '<p><a href="/pages/adm_orders_shop?u='.$l["user_id"].'">'.user_name($l).'</a></p>';
            $_ECHO .= '
                        '._date($l["order_payment_date"],'L j F Y - H:i').'<br />
                        <a href="/pages/adm_order?order_id='.$l["order_id"].'">modifier</a> | <a href="/pages/adm_order?order_id='.$l["order_id"].'&delete=1" data-confirm="Voulez-vous vraiment ANNULER cet achat et remettre les livres en vente ?">annuler</a>
                    </td>
                    <td class="center">
                        <img src="/common/icons/cash_16.png" alt="Espèces" title="Espèces" /><br />'.price($l["order_payment_cash"],'EUR').'
                    </td>
                    <td class="center">
                        <img src="/common/icons/cheque_16.png" alt="Chèque" title="Chèque" /><br />'.price($l["order_payment_cheque"],'EUR').'
                    </td>
                    <td class="center">
                        <img src="/common/icons/card_16.png" alt="Carte bancaire" title="Carte bancaire" /><br />'.price($l["order_payment_card"],'EUR').'
                    </td>
                    <td class="center">
                        <img src="/common/icons/paypal_16.png" alt="Paypal" title="Paypal"><br />'.price($l["order_payment_paypal"],'EUR').'
                    </td>
                    <td class="center">
                        Rendu<br />'.price($l["order_payment_left"],'EUR').'
                    </td>
                    <td class"right">'.price($l["total_payments"],'EUR').'</td>
                </tr>
            ';
            $TotalCash += $l["order_payment_cash"];
            $TotalLeft += $l["order_payment_left"];
            $TotalCheque += $l["order_payment_cheque"];
            $TotalCard += $l["order_payment_card"];
            $TotalPaypal += $l["order_payment_paypal"];
            $order_id = $l["order_id"];
        }
        $c["order_shipping"] = null;

        // TVA
        $l["tva_rate"] = $l['stock_tva_rate'];
        $l["tva"] = 1 + $l["tva_rate"] / 100;
        if (!isset($TVA[$l["tva_rate"]])) $TVA[$l["tva_rate"]] = 0;
        $TVA[$l["tva_rate"]] += $l["stock_selling_price"] - round($l["stock_selling_price"] / $l["tva"]);

        $l["PrixHT"] = round($l["stock_selling_price"] / $l["tva"]);

        $c["payment_date"] = $l["order_payment_date"];
        $c["shipping"] = $l["order_shipping"];

		$c["totalHT"] += $l["PrixHT"];
		$c["total"] += $l["stock_selling_price"];

		$TotalHT += $l["PrixHT"];
		$Total += $l["stock_selling_price"];

		if($l["stock_condition"] == "Neuf") $TotalNeuf += $l["stock_selling_price"];
		else $TotalOccasion += $l["stock_selling_price"];

        $_ECHO .= '
            <tr>
                <td></td>
                <td class="center"><a href="/pages/adm_stock?id='.$l["stock_id"].'">'.$l["stock_id"].'</a></td>
                <td colspan="6">
                    <a href="/'.$l["article_url"].'">'.$l["article_title"].'</a><br />
                    <em>'.$l["article_authors"].'</em><br />
                    '.$l["article_collection"].' '.numero($l["article_number"]).'<br />
                </td>
                <td title="HT : '.currency($l['stock_selling_price_ht'], true).' / TVA : '.$l['stock_tva_rate'].'%" class="nowrap right">'.price($l["stock_selling_price"],'EUR').'</td>
            </tr>
        ';
    }

    if(!empty($c["shipping"])) {
        $c["total_shipping"] = '
            <tr>
            <td colspan="8" class="right">Frais de port :</td>
            <td class="right">'.price($c["shipping"],'EUR').'</td>
            </tr>
';
            $Total += $c["shipping"];
        $TotalShipping += $c["shipping"];

        // TVA Frais de port
        $c["tva"] = 1 + tva_rate(3,$c["payment_date"]) / 100;
        $c["totalHT"] += round($c["shipping"] / $c["tva"]);
        $TotalHT += round($c["shipping"] / $c["tva"]);
        if (!isset($TVA[tva_rate(3, $c["payment_date"])])) $TVA[tva_rate(3, $c["payment_date"])] = 0;
        $TVA[tva_rate(3, $c["payment_date"])] += $c["shipping"] - round($c["shipping"] / $c["tva"]);

    } else $c["total_shipping"] = null;

    if (!isset($c['totalHT'])) $c['totalHT'] = 0;
    if (!isset($c['total'])) $c['total'] = 0;
    if (!isset($c['shipping'])) $c['shipping'] = 0;

    $_ECHO .= '
            '.$c["total_shipping"].'
            <tr>
                <td colspan="8" class="right">Total HT :</td>
                <td class="right">'.price($c["totalHT"],'EUR').'</td>
            </tr>
            <tr>
                <td colspan="8" class="right"><strong>Total TTC :</strong></td>
                <td class="right"><strong>'.price($c["total"]+$c["shipping"],'EUR').'</strong></td>
            </tr>
        </tbody>
    </table>';

	$TotalPayments = $TotalCash + $TotalCheque + $TotalCard + $TotalPaypal - $TotalLeft;

    // TVA
    $tva_th = null; $tva_tb = null; $ti = 0;
    foreach($TVA as $rate => $amount) {
        $tva_th .= '<td class="right">TVA ('.str_replace('.','.',$rate).' %) :</td>';
        $tva_tb .= '<td class="right">'.price($amount,'EUR').'</td>';
        $ti++;
    }

    $_ECHO .= '

    <h3>R&eacute;capitulatif</h3>
    <table class="admin-table">
        <tr>
            <th colspan="'.($ti+2).'">TVA</th>
        </tr>
        <tr>
            <td class="right">Total HT :</td>
            '.$tva_th.'
            <td class="right">Total TTC :</td>
        </tr>
        <tr>
            <td class="right">'.price($TotalHT,'EUR').'</td>
            '.$tva_tb.'
            <td class="right">'.price($Total,'EUR').'</td>
        </tr>
    </table>

    <table class="admin-table">
        <tr class="center">
            <th colspan="2">Mode de paiement</th>
            <th colspan="2">Ventilation</th>
        </tr>
        <tr>
            <td class="right">Esp&egrave;ces :</td>
            <td>'.price($TotalCash-$TotalLeft,'EUR').'</td>
            <td class="right">Neuf :</td>
            <td>'.price($TotalNeuf,'EUR').'</td>
        </tr>
        <tr>
            <td class="right">Cheque :</td>
            <td>'.price($TotalCheque,'EUR').'</td>
            <td class="right">Occasion :</td>
            <td>'.price($TotalOccasion,'EUR').'</td>
        </tr>
        <tr>
            <td class="right">Carte bancaire :</td>
            <td>'.price($TotalCard,'EUR').'</td>
            <td class="right">Frais de port :</td>
            <td>'.price($TotalShipping,'EUR').'</td>
        </tr>
        <tr>
            <td class="right">Paypal :</td>
            <td>'.price($TotalPaypal,'EUR').'</td>
        </tr>
        <tr>
            <td class="right">(Rendu :</td>
            <td>'.price($TotalLeft,'EUR').')</td>
        </tr>
        <tr>
            <td class="right">Total :</td>
            <td>'.price($TotalPayments,'EUR').'</td>
        </tr>
    </table>
';

    if($_SITE["site_id"] == 5 && !empty($_GET["m"])) {
        $objectif = 12660;
        $percent = round($Total / 100 / $objectif * 100);
        $reste = $objectif - $Total / 100;
        $ppg = null;
        if($reste <= 0) $ppg = '<br /><br /><img src="http://24.media.tumblr.com/tumblr_m6b489RVkZ1qf1n9ho1_250.gif">';
        if($reste < 1000 && $reste > 0) $ppg = '<br /><img src="http://www.gifsmaniac.com/gifs-animes/personnages/pompom-girls/personnages-pompom-girls-21.gif" height="150">';
        if($reste > 0) $reste = ' (encore '.$reste.' &euro;)';
        else $reste = null;
    $_ECHO .= '
        <div class="center">
            <br />
            Objectif : <progress id="progressBar" value="'.$percent.'" max="100"></progress> '.$percent.' % '.$reste.'
            '.$ppg.'
        </div>
    ';
}
