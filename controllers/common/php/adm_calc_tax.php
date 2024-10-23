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


    $sm = new StockManager();

    $stock_query = array('stock_selling_price_ht' => 'NULL', 'stock_selling_price' => '!= 0');

    $count = $sm->count($stock_query);
    $stocks = $sm->getAll($stock_query, array('limit' => 100));
    $done = 0;

    \Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('('.$count.') Recalculer la TVA des exemplaires en stock');

    $rows = array_map(function($stock) {

        global $sm, $done;

        $article = $stock->get('article');
        if (!$article) {
            return '<tr><td colspan=8>article unkown</tr>';
            trigger_error("Unkown article ".$stock->get('article_id')." for stock ".$stock->get('id'));
        }

        $tax_type = 'N/A';
        if ($article) {
            $type = $article->getType();
            if ($type) {
                $tax_type = $type->getTax();
            }
        }

        $tax_rate = $sm->getTaxRate($stock);
        $stock = $sm->calculateTax($stock);

        $sm->update($stock);

        return '
            <tr>
                <td>'.$stock->get('id').'</td>
                <td class="right">'.price($stock->get('selling_price'), 'EUR').'</td>
                <td class="center">'._date($stock->get('selling_date'), 'd/m/Y').'</td>
                <td class="right">'.$tax_type.'</td>
                <td class="right">'.$tax_rate.' %</td>
                <td class="right">'.price($stock->get('selling_price_ht'), 'EUR').'</td>
                <td class="right">'.price($stock->get('selling_price_tva'), 'EUR').'</td>
            </tr>
        ';

        $done++;

    }, $stocks);

    $_ECHO .= '
        <h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Ref.</th>
                    <th>Prix TTC</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Taux</th>
                    <th>Prix HT</th>
                    <th>TVA</th>
                </tr>
            </thead>
            <tbody>
                '.implode($rows).'
            </tbody>
        </table>
    ';

    $_ECHO .= (count($rows) ? '<script>window.location.reload()</script>' : null);
