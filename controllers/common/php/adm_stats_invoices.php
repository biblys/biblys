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
return function(Request $request): Response
{
    $request->attributes->set("page_title", "Résultats par facture");

    $stockItemsQuery = EntityManager::prepareAndExecute('
        SELECT 
            `stock_invoice`, 
            `stock_selling_price`, 
            `stock_selling_date`, 
            `stock_return_date`
	    FROM `stock` 
	    WHERE `stock_invoice` IS NOT NULL 
	    ORDER BY `stock_invoice`', []
    );

    $invoices = array();
    while ($stockItem = $stockItemsQuery->fetch(PDO::FETCH_ASSOC)) {

        $uid = md5($stockItem["stock_invoice"]);

        if (!isset($invoices[$uid]["sales"])) $invoices[$uid]["sales"] = 0;
        if (!isset($invoices[$uid]["sales_value"])) $invoices[$uid]["sales_value"] = 0;
        if (!isset($invoices[$uid]["returns"])) $invoices[$uid]["returns"] = 0;
        if (!isset($invoices[$uid]["returns_value"])) $invoices[$uid]["returns_value"] = 0;

        $invoices[$uid]["invoice"] = $stockItem["stock_invoice"];

        // Total count
        if (!isset($invoices[$uid]['total'])) $invoices[$uid]["total"] = 0;
        $invoices[$uid]["total"]++;

        // Ventes
        if ($stockItem["stock_selling_date"]) {
            $invoices[$uid]["sales"]++;
            $invoices[$uid]["sales_value"] += $stockItem["stock_selling_price"];
        } elseif ($stockItem["stock_return_date"]) {
            $invoices[$uid]["returns"]++;
            $invoices[$uid]["returns_value"] += $stockItem["stock_selling_price"];
        }
    }
    $stockItemsQuery->closeCursor();

    $tbody = NULL;
    foreach ($invoices as $invoice) {
        $tbody .= '
			<tr>
				<td><a href="/pages/adm_stocks?stock_invoice=' . $invoice["invoice"] . '">' . $invoice["invoice"] . '</a></td>
				<td class="right">' . $invoice["total"] . '</td>
				<td class="right">' . $invoice["sales"] . '</td>
				<td class="right">' . price($invoice["sales_value"], "EUR") . '</td>
				<td class="right">' . $invoice["returns"] . '</td>
				<td class="right">' . price($invoice["returns_value"], "EUR") . '</td>
			</tr>
		';
    }


    return new Response('
		<h1>
		  <i class="fa-solid fa-file-invoice"></i> 
		  Résultats par facture
        </h1>

		<table class="table table-striped mt-4">
			<thead>
				<tr>
					<th>Facture</th>
					<th>Total</th>
					<th colspan=2>Ventes</th>
					<th colspan=2>Retours</th>
				</tr>
			</thead>
			<tbody>
				' . $tbody . '
			</tbody>
		</table>
	');
};
