<?php

use Biblys\Legacy\LegacyCodeHelper;

error_reporting(E_ALL ^ E_NOTICE);
	ini_set('display_errors','On');

	\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Résultats par facture');

	$stock = $_SQL->prepare('SELECT
		`stock_invoice`, `stock_selling_price`, `stock_selling_date`, `stock_return_date`
	FROM `stock` WHERE `site_id` = :site_id AND `stock_invoice` IS NOT NULL ORDER BY `stock_invoice`');
	$stock->bindValue('site_id', LegacyCodeHelper::getLegacyCurrentSite()['site_id'],PDO::PARAM_INT);
	$stock->execute() or error($stock->errorInfo());

	$inv = array();
	while ($s = $stock->fetch(PDO::FETCH_ASSOC))
	{

		$uid = md5($s['stock_invoice']);

		if (!isset($inv[$uid]['sales'])) $inv[$uid]['sales'] = 0;
		if (!isset($inv[$uid]['sales_value'])) $inv[$uid]['sales_value'] = 0;
		if (!isset($inv[$uid]['returns'])) $inv[$uid]['returns'] = 0;
		if (!isset($inv[$uid]['returns_value'])) $inv[$uid]['returns_value'] = 0;

		$inv[$uid]['invoice'] = $s['stock_invoice'];

		// Total count
		if (!isset($inv[$uid]['total'])) $inv[$uid]['total'] = 0;
		$inv[$uid]['total']++;

		// Ventes
		if ($s['stock_selling_date'])
		{
			$inv[$uid]['sales']++;
			$inv[$uid]['sales_value'] += $s['stock_selling_price'];
		}
		elseif ($s['stock_return_date'])
		{

			$inv[$uid]['returns']++;
			$inv[$uid]['returns_value'] += $s['stock_selling_price'];
		}


	}
	$stock->closeCursor();

	$tbody = NULL;
	foreach ($inv as $i)
	{
		$tbody .= '
			<tr>
				<td><a href="/pages/adm_stocks?stock_invoice='.$i['invoice'].'">'.$i['invoice'].'</a></td>
				<td class="right">'.$i['total'].'</td>
				<td class="right">'.$i['sales'].'</td>
				<td class="right">'.price($i['sales_value'],'EUR').'</td>
				<td class="right">'.$i['returns'].'</td>
				<td class="right">'.price($i['returns_value'],'EUR').'</td>
			</tr>
		';
	}


	$_ECHO .= '
		<h1><span class="fa fa-file"></span> '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>

		<table class="admin-table">
			<thead>
				<tr>
					<th>Facture</th>
					<th>Total</th>
					<th colspan=2>Ventes</th>
					<th colspan=2>Retours</th>
				</tr>
			</thead>
			<tbody>
				'.$tbody.'
			</tbody>
		</table>
	';
