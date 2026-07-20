<?php
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


use Biblys\Service\CurrentSite;
use Biblys\Service\QueryParamsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

return function (Request $request, CurrentSite $currentSite, QueryParamsService $queryParams): Response
{
    global $_SQL;

    $request->attributes->set("page_title", "Chiffre d'affaires");

    // FILTRES

    // Raccourci 30 derniers jours
    $dates = _getDatesOptions($currentSite->getId(), "%Y-%m-%d", "l j f", "d");
    $months = _getDatesOptions($currentSite->getId(), "%Y-%m", "F Y", "m");
    $years = _getDatesOptions($currentSite->getId(), "%Y", "Y", "y");

    $queryParams->parse([
        "d" => ["type" => "date", "default" => null],
        "m" => ["default" => null],
        "y" => ["type" => "numeric", "default" => null],
        "date1" => ["type" => "date", "default" => null],
        "date2" => ["type" => "date", "default" => null],
        "time1" => ["default" => null],
        "time2" => ["default" => null],
        "condition" => ["default" => null],
    ]);

    $d = $queryParams->get('d');
    $m = $queryParams->get('m');
    $y = $queryParams->get('y');
    $date1 = $queryParams->get('date1');
    $date2 = $queryParams->get('date2');
    $time1 = $queryParams->get('time1');
    $time2 = $queryParams->get('time2');

    // Affichage par défaut : ventes du jour courant
    if (empty($date1) && empty($d) && empty($m) && empty($y)) $m = date("Y-m");

    // Raccourcis mois ou jour
    if (!empty($d)) {
        $date1 = $d;
        $date2 = $d;
        $time1 = '00:00';
        $time2 = '23:59';
    } elseif (!empty($m)) {
        $date1 = $m.'-01';
        $date2 = $m.'-'.date('t', strtotime($m));
        $time1 = '00:00';
        $time2 = '23:59';
    } elseif (isset($y)) {
        $date1 = $y.'-01-01';
        $date2 = $y.'-12-31';
        $time1 = '00:00';
        $time2 = '23:59';
    }

    // REQUETE DES VENTES

    $_QUERY = null;

    // Filtrer par date
    if (!empty($date1)) {
        $_QUERY .= ' AND `order_payment_date` >= :date_1 AND `order_payment_date` <= :date_2';
        $params['date_1'] = $date1.' '.$time1.':00';
        $params['date_2'] = $date2.' '.$time2.':00';
    }

    // Filtrer par état
    $condition = $queryParams->get('condition');
    if ($condition) {
        if ($condition == "new") {
            $_QUERY .= " AND `stock_condition` LIKE '%Neuf%' OR  `stock_condition` = '%Neuf%' ";
        } elseif ($condition == "used") {
            $_QUERY .= " AND `stock_condition` NOT LIKE '%Neuf%' ";
        }
    }

    $query = EntityManager::prepareAndExecute('SELECT
        `s`.`stock_id`, `stock_selling_price`, `stock_selling_price_ht`, `stock_selling_price_tva`, `stock_tva_rate`, `stock_condition`, `stock_selling_date`,
        `o`.`order_id`, `order_payment_date`, `order_shipping`, `order_type`,
        `a`.`article_id`, `article_tva`, `type_id`, `article_pubdate`, `article_links`,
        `customer_type`
        FROM `stock` AS `s`
        JOIN `orders` AS `o` ON `s`.`order_id` = `o`.`order_id`
        JOIN `articles` AS `a` ON `s`.`article_id` = `a`.`article_id`
        LEFT JOIN `customers` AS `c` ON `c`.`customer_id` = `o`.`customer_id`
        WHERE  `o`.`order_payment_date` IS NOT NULL AND `stock_selling_date` IS NOT NULL '.$_QUERY.'
        ', $params
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
    $types = \Biblys\Data\ArticleType::getAll();
    $type_r = array();
    foreach ($types as $t) {
        $ty[$t->getId()]['name'] = $t->getName();
        $ty[$t->getId()]['revenue_ht'] = 0;
        $ty[$t->getId()]['revenue_ttc'] = 0;
        $ty[$t->getId()]['sales'] = array();
    }

    // Rayons
    $rayons = $_SQL->query('SELECT `rayon_id`, `rayon_name` FROM `rayons` ORDER BY `rayon_order`');
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
        if ($currentSite->getSite()->getTva()) {
            $rate = $s['stock_tva_rate'] * 10;

            if ($rate) {
                if (!isset($tva[$rate])) {
                    $tva[$rate] = [
                        'rate' => $s['stock_tva_rate'],
                        'revenue_ht' => 0,
                        'revenue_ttc' => 0,
                        'revenue_tva' => 0,
                    ];
                }
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
    if (empty($request->request->get('hide_shipping')))
    {
        if (isset($ship))
        {
            foreach ($ship as $s)
            {
                if (!empty($s['fee']))
                {

                    // Port HT
                    if ($currentSite->getSite()->getTva())
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
                            <td class="right table-success">'.price($v['revenue_ttc'], 'EUR').'</td>
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
                            <td class="right table-success">'.price($v['revenue_ttc'], 'EUR').'</td>
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
                            <td class="right"><a href="/pages/adm_sales_detail?date1='.$date1.'&time1='.$time1.'&date2='.$date2.'&time2='.$time2.'&rayon_id='.$k.'">'.count($v['sales']).'</a></td>
                            <td class="right">'.price($v['revenue_ht'], 'EUR').'</td>
                            <td class="right table-success">'.price($v['revenue_ttc'], 'EUR').'</td>
                            <td class="right">'.$v['share'].'&nbsp;%</td>
                        </tr>
                    ';
            }
        }
    }

    $content = '

            <h1 class="mb-3">
              <i class="fa-solid fa-money-bills"></i>
              Chiffre d’affaires
            </h1>

            <div class="row g-2 align-items-center mb-3">
                <div class="col-2">
                    <label for="d" class="col-form-label">Raccourcis :</label>
                </div>
                <div class="col-auto">
                    <select name="d" class="goto custom-select">
                        <option>30 derniers jours...</option>
                        '.join($dates).'
                    </select>
                </div>
                <div class="col-auto">
                    <select name="m" class="goto custom-select">
                        <option>Mois de...</option>
                        '.join($months).'
                    </select>
                </div>
                <div class="col-auto">
                    <select name="y" class="goto custom-select">
                        <option>Année...</option>
                        '.join($years).'
                    </select>
                </div>
            </div>

            <form>
                <fieldset>
                    <legend>Filtres</legend>

                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-2">
                            <label for="date1" class="col-form-label">Du :</label>
                        </div>
                        <div class="col-auto">
                            <input type="date" name="date1" id="date1" class="form-control" placeholder="AAAA-MM-JJ" value="'.$date1.'">
                        </div>
                        <div class="col-auto">
                            <input type="time" name="time1" id="time1" class="form-control" placeholder="HH:SS" value="'.$time1.'">
                        </div>
                    </div>

                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-2">
                            <label for="date2" class="col-form-label">Au :</label>
                        </div>
                        <div class="col-auto">
                            <input type="date" name="date2" id="date2" class="form-control" placeholder="AAAA-MM-JJ" value="'.$date2.'">
                        </div>
                        <div class="col-auto">
                            <input type="time" name="time2" id="time2" class="form-control" placeholder="HH:SS" value="'.$time2.'">
                        </div>
                    </div>

                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-2">
                            <label for="stock_condition" class="col-form-label">État :</label>
                        </div>
                        <div class="col-auto">
                            <select name="condition" id="stock_condition" class="custom-select">
                                <option value="all">Tous</option>
                                <option value="new"'.($condition == "new" ? " selected" : null).'>Neuf</option>
                                <option value="used"'.($condition == "used" ? " selected" : null).'>Occasion</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col offset-2">
                            <button type="submit" class="btn btn-primary">Afficher le chiffre d\'affaire</button>
                        </div>
                    </div>

                </fieldset>
            </form>

            <h2 class="mt-5 mb-4">Chiffre d\'affaires total</h2>

            <table class="table table-striped table-bordered table-hover">
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
                        <td class="right table-success">'.price($total_ttc,'EUR').'</td>
                    </tr>
                    <tr>
                        <td>Frais de port</td>
                        <td colspan=2></td>
                        <td class="right">'.price($total_port_ht,'EUR').'</td>
                        <td class="right table-success">'.price($total_port_ttc,'EUR').'</td>
                    </tr>
                    <tr>
                        <td class="table-primary">Total</td>
                        <td class="right table-primary" colspan=2></td>
                        <td class="right table-primary">'.price($total_ht+$total_port_ht,'EUR').'</td>
                        <td class="right table-primary">'.price($total_ttc+$total_port_ttc,'EUR').'</td>
                    </tr>
                </tbody>
            </table>


            <h2 class="mt-5 mb-4">Chiffre d\'affaires ventilé par...</h2>

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" role="tab" data-toggle="tab" href="#tva">TVA</a></li>
                <li class="nav-item"><a class="nav-link" href="#customers" role="tab" data-toggle="tab">Type de client</a></li>
                <li class="nav-item"><a class="nav-link" href="#age" role="tab" data-toggle="tab">Ancienneté</a></li>
                <li class="nav-item"><a class="nav-link" href="#condition" role="tab" data-toggle="tab">État</a></li>
                <li class="nav-item"><a class="nav-link" href="#type" role="tab" data-toggle="tab">Type d\'article</a></li>
                <li class="nav-item"><a class="nav-link" href="#location" role="tab" data-toggle="tab">Lieu de vente</a></li>
                <li class="nav-item"><a class="nav-link" href="#rayon" role="tab" data-toggle="tab">Rayon</a></li>
            </ul>

            <div class="tab-content">
                <br>

                <div class="tab-pane active" id="tva">

                    <table class="table table-striped table-bordered table-hover">
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

                    <table class="table table-striped table-bordered table-hover">
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
                            <tr class="table-success">
                                <td>Libraires</td>
                                <td class="right">'.count($lib_sales).'</td>
                                <td class="right">'.price($lib_ht, 'EUR').'</td>
                                <td class="right table-success">'.price($lib_ttc, 'EUR').'</td>
                                <td class="right">'.percent($lib_ttc, $total_ttc).'</td>
                            </tr>
                            <tr class="table-success">
                                <td>Non-Libraires</td>
                                <td class="right">'.$nonlib_sales.'</td>
                                <td class="right">'.price($nonlib_ht, 'EUR').'</td>
                                <td class="right table-success">'.price($nonlib_ttc, 'EUR').'</td>
                                <td class="right">'.percent($nonlib_ttc, $total_ttc).'</td>
                            </tr>
                            <tr>
                                <td>Particuliers</td>
                                <td class="right">'.count($part_sales).'</td>
                                <td class="right">'.price($part_ht, 'EUR').'</td>
                                <td class="right table-success">'.price($part_ttc, 'EUR').'</td>
                                <td class="right">'.percent($part_ttc, $total_ttc).'</td>
                            </tr>
                            <tr>
                                <td>Professionnels</td>
                                <td class="right">'.count($pro_sales).'</td>
                                <td class="right">'.price($pro_ht, 'EUR').'</td>
                                <td class="right table-success">'.price($pro_ttc, 'EUR').'</td>
                                <td class="right">'.percent($pro_ttc, $total_ttc).'</td>
                            </tr>
                            <tr>
                                <td>Collectivités</td>
                                <td class="right">'.count($coll_sales).'</td>
                                <td class="right">'.price($coll_ht, 'EUR').'</td>
                                <td class="right table-success">'.price($coll_ttc, 'EUR').'</td>
                                <td class="right">'.percent($coll_ttc, $total_ttc).'</td>
                            </tr>
                            <tr>
                                <td>Clients inconnus</td>
                                <td class="right">'.count($ukc_sales).'</td>
                                <td class="right">'.price($ukc_ht, 'EUR').'</td>
                                <td class="right table-success">'.price($ukc_ttc, 'EUR').'</td>
                                <td class="right">'.percent($ukc_ttc, $total_ttc).'</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="tab-pane" id="age">

                    <table id="revenue-age" class="table table-striped table-bordered table-hover">
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
                                <td class="right table-success">'.price($m3_ttc,'EUR').'</td>
                            </tr>
                            <tr>
                                <td>Moins d\'un an</td>
                                <td class="right">'.price($y1_ht,'EUR').'</td>
                                <td class="right table-success">'.price($y1_ttc,'EUR').'</td>
                            </tr>
                            <tr>
                                <td>Un an ou plus (fonds)</td>
                                <td class="right">'.price($old_ht,'EUR').'</td>
                                <td class="right table-success">'.price($old_ttc,'EUR').'</td>
                            </tr>
                            <tr>
                                <td>Date de parution inconnue</td>
                                <td class="right">'.price($uk_ht,'EUR').'</td>
                                <td class="right table-success">'.price($uk_ttc,'EUR').'</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="tab-pane" id="condition">

                    <table class="table table-striped table-bordered table-hover">
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
                                <td class="right table-success">'.price($total_new_ttc,'EUR').'</td>
                            </tr>
                            <tr>
                                <td>Occasion</td>
                                <td class="right">'.price($total_used_ht,'EUR').'</td>
                                <td class="right table-success">'.price($total_used_ttc,'EUR').'</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="tab-pane" id="type">

                    <table class="table table-striped table-bordered table-hover">
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

                    <table id="revenue-location" class="table table-striped table-bordered table-hover">
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
                                <td class="right table-success">'.price($total_shop_ttc,'EUR').'</td>
                            </tr>
                            <tr>
                                <td>En VPC</td>
                                <td class="right">'.price($total_web_ht,'EUR').'</td>
                                <td class="right table-success">'.price($total_web_ttc,'EUR').'</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="tab-pane" id="rayon">
                    <table id="revenue-rayon" class="table table-striped table-bordered table-hover">
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

    return new Response($content);
};

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
    WHERE `order_cancel_date` IS null
        AND `order_payment_date` IS NOT NULL
    GROUP BY `date`
    ORDER BY `date` DESC
    LIMIT 30
", ["format" => $queryFormat]);
    $datesOptions = array_map(function ($date) use ($displayFormat, $parameter) {
        return '<option value="?'.$parameter.'='.$date["date"].'">'
            ._date($date["date"], $displayFormat).
            '</option>';
    }, $datesQuery->fetchAll());
    return $datesOptions;
}
