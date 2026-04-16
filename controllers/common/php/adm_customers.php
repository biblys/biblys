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


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @throws Exception
 */
return function (Request $request): Response {
    $request->attributes->set("page_title", "Clients");
    $searchParam = $request->query->get("q");

    $years = [];
    for ($y = date('Y'); $y >= 2010; $y--) {
        $sel = NULL;
        if (isset($_GET['year']) && $y == $_GET["year"]) {
            $sel = 'selected';
        }
        $years[] = '<option value="?year=' . $y . '" ' . $sel . '>' . $y . '</option>';
    }

    $where = ["1=1"];
    $params = [];
    if (!empty($_GET['year'])) {
        $where[] = "`stock_selling_date` LIKE :year";
        $params[':year'] = $_GET['year'] . '%';
    } elseif (!empty($searchParam)) {
        $where[] = "(`customer_first_name` LIKE :q1 OR `customer_last_name` LIKE :q2 OR `customer_email` LIKE :q3)";
        $params[':q1'] = $params[':q2'] = $params[':q3'] = '%' . $searchParam . '%';
    }

    $customers = EntityManager::prepareAndExecute('
        SELECT
               `customers`.`customer_id`,
               `customer_last_name`,
               `customer_first_name`,
               COUNT(`stock_id`) AS `num`,
               SUM(`stock_selling_price`) AS `CA`,
               MAX(`stock_selling_date`) AS `DateVente`
        FROM `customers`
        LEFT JOIN `stock` ON `customers`.`customer_id` = `stock`.`customer_id`
        WHERE ' . join(" AND ", $where) . '
        GROUP BY `customers`.`customer_id`
        ORDER BY `CA` DESC
    ', $params);


    $content = '
        <h1>
          <span class="fa fa-address-card"></span>
          Clients 
        </h1>
    
        <p class="buttonset text-right">
            <a class="btn btn-primary" href="/pages/adm_customer"><i class="fa fa-user"></i> Nouveau client</a>
        </p>
    
        <form class="fieldset">
            <fieldset>
                <legend>Filtres</legend>
                <p>
                    <label for="year">Année :</label>
                    <select id="year" class="goto">
                        <option value="/pages/adm_customers">Cumul</option>
                        ' . join($years) . '
                    </select>
                    <br />
                </p>
                <p>
                    <label for"query">Rechercher :</label>
                    <input type="text" name="q" id="query" class="long" value="' . $searchParam . '" placeholder="Nom, adresse-email, code postal..." /> <input type="submit" value="Rechercher" />
                </p>
            </fieldset>
        </form>
    
        <br />
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="cliquable">
                    <th></th>
                    <th>Client</th>
                    <th>Achats</th>
                    <th>C.A.</th>
                    <th>Dernier</th>
                </tr>
            </thead>
            <tbody>
  ';

    $Clients = 0;

    while ($s = $customers->fetch(PDO::FETCH_ASSOC)) {

        $Clients++;

        if (!empty($s["customer_last_name"])) {
            $user = trim($s["customer_first_name"] . ' ' . $s["customer_last_name"]);
        } else {
            $user = 'Anonyme';
        }

        $content .= '
                <tr>
                    <td class="right">' . $Clients . '.</td>
                    <td><a href="/pages/adm_customer?id=' . $s['customer_id'] . '">' . $user . '</a></td>
                    <td class="right"><a href="/pages/adm_orders_shop?customer_id=' . $s["customer_id"] . '&date1=2001-01-01&date2=' . date('Y-m-d') . '">' . $s["num"] . '</a></td>
                    <td class="right" style="width: 100px;">' . price($s["CA"], 'EUR') . '</td>
                    <td class="center"><a href="/pages/adm_customer?id=' . $s["customer_id"] . '"><i class="fa fa-edit fa-lg black"></i></td>
                </tr>
            ';
    }

    $content .= '
            </tbody>
        </table>
    ';

    return new Response($content);
};
