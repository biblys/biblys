<?php

	\Biblys\Legacy\LegacyCodeHelper::setLegacyGlobalPageTitle("Évolution des stocks");

	if (!isset($_GET['date'])) $_GET['date'] = (date('Y')-1).'-01-01';
	if (!isset($_GET['period'])) $_GET['period'] = 'month';
	if (!isset($_GET['count'])) $_GET['count'] = '12';
	if (!isset($_GET['type'])) $_GET['type'] = '0';
	if (!isset($_GET['depot'])) $_GET['depot'] = '0';

	$date = $_GET['date'].' 23:59:59';

	$stock = $_SQL->query('
		SELECT `stock_id`, `stock_selling_price`, `stock_purchase_price`, `stock_depot`, `stock_purchase_date`, `stock_selling_date`, `stock_return_date`, `stock_lost_date`,
				`article_id`, `article_pubdate`, `type_id`, `article_tva`
		FROM `stock`
		JOIN `articles` USING(`article_id`)
		WHERE `site_id` = '.getLegacyCurrentSite()['site_id']);
	$stock = $stock->fetchAll(PDO::FETCH_ASSOC);

	$table = NULL;
	for ($ic = 0; $ic < $_GET['count']; $ic++) // For each period $p
	{
		$p = array('stock' => 0, 'purchase_value_ht' => 0, 'selling_value_ht' => 0, 'purchase_value_ttc' => 0, 'selling_value_ttc' => 0, 'articles' => array(), 'articles_fonds' => array());

		$date_f = date('Y-m-d H:i:s', strtotime($date.'- 1 years')); // date de fonds : deux ans plus tôt

		foreach ($stock as $s) // For each copy
		{
			// If buying date is inferior & selling, return, lost date are null or superior : in stock
			if ($s['stock_purchase_date'] <= $date
					&& (!($s['stock_selling_date']) || $s['stock_selling_date'] > $date)
					&& (!($s['stock_return_date']) || $s['stock_return_date'] > $date)
					&& (!($s['stock_lost_date']) || $s['stock_lost_date'] > $date)
				)
			{
				// HT Price
				if (getLegacyCurrentSite()['site_tva'])
				{
					$s['tva_rate'] = tva_rate($s['article_tva'],$s["stock_purchase_date"]) / 100;
					$s['stock_selling_price_ht'] = $s['stock_selling_price'] / (1 + $s['tva_rate']);
					$s['stock_purchase_price_ht'] = $s['stock_purchase_price'] / (1 + $s['tva_rate']);
				}
				else
				{
					$s['stock_selling_price_ht'] = $s['stock_selling_price'];
					$s['stock_purchase_price_ht'] = $s['stock_purchase_price'];
				}

				// Type filter
				if (!empty($_GET['type']) && $_GET['type'] != $s['type_id']) continue;

				// Filtrer par dépôt
				if ($_GET['depot'] == 1 && $s['stock_depot'] == 1) continue;
				elseif ($_GET['depot'] == 2 && $s['stock_depot'] == 0) continue;

				// Add to stock
				$p['articles'][] = $s['article_id'];
				$p['stock']++;
				$p['purchase_value_ht'] += $s['stock_purchase_price_ht'];
				$p['selling_value_ht'] += $s['stock_selling_price_ht'];
				$p['purchase_value_ttc'] += $s['stock_purchase_price'];
				$p['selling_value_ttc'] += $s['stock_selling_price'];

				// If publication date is inferior to fonds date : fonds
				if (!empty($s['article_pubdate']) && $s['article_pubdate'] != '0000-00-00' && $s['article_pubdate'] <= $date_f)
				{
					$p['articles_fonds'][] = $s['article_id'];
				}
			}
		}

		$p['articles'] = count(array_unique($p['articles']));
		$p['articles_fonds'] = count(array_unique($p['articles_fonds']));
		$p['articles_fonds_part'] = round($p['articles_fonds']  / $p['articles'] * 100);

		$table .= '
			<tr>
				<td>'._date($date, 'j f Y').'</td>
				<td class="right">'.$p['articles'].' <span title="Parus avant le '._date($date_f, 'd/m/Y').'">('.$p['articles_fonds'].' - '.$p['articles_fonds_part'].'%)</span></td>
				<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'&stock_depot='.$_GET['depot'].'">'.$p['stock'].'</a></td>
				<td class="right">
					'.price($p['purchase_value_ttc'], 'EUR').'<br>
					<span class="gray">'.price($p['purchase_value_ht'], 'EUR').'</span>
				</td>
				<td class="right">
					'.price($p['selling_value_ttc'], 'EUR').'<br>
					<span class="gray">'.price($p['selling_value_ht'], 'EUR').'</span>
				</td>
			</tr>
		';

		$date = date('Y-m-d H:i:s', strtotime($date.'+ 1 '.$_GET['period']));
	}

	// Article types
    $type_options = Biblys\Article\Type::getOptions($request->query->get('type_id'));

	// Selected period
	$ps['year'] = null; $ps['month'] = null; $ps['week'] = null; $ps['day'] = null;
	if ($_GET['period'] == 'year') $ps['year'] = ' selected';
	if ($_GET['period'] == 'month') $ps['month'] = ' selected';
	if ($_GET['period'] == 'week') $ps['week'] = ' selected';
	if ($_GET['period'] == 'day') $ps['day'] = ' selected';

	$_ECHO .= '
		<h2><img src="/common/icons/adm_stock_status.svg" width=32 alt="'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'"> '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>

		<form class="fieldset">
			<fieldset>
				<legend>Options</legend>

				<p>
					<label for="date">À partir du :</label>
					<input type="date" name="date" id="date" value="'.$_GET['date'].'">
				</p>

				<p>
					<label for="count">Et pendant :</label>
					<input type="number" name="count" id="count" min=1 max=99 maxlength=2 value="'.$_GET['count'].'">
					<select name="period">
						<option value="day"'.$ps['day'].'>jours</option>
						<option value="week"'.$ps['week'].'>semaines</option>
						<option value="month"'.$ps['month'].'>mois</option>
						<option value="year"'.$ps['year'].'>années</option>
					</select>
				</p>

				<p>
					<label for="type">Type :</label>
					<select name="type">
						<option value="0">Tous</option>
						'.join($type_options).'
					</select>
				</p>

				<p>
					<label for="depot">Dépots :</label>
					<select name="depot" id="depot" required>
						<option value="0">Tous</option>
						<option value="1"'.($_GET['depot'] == 1 ? ' selected' : null).'>Sans les dépots</option>
						<option value="2"'.($_GET['depot'] == 2 ? ' selected' : null).'>Dépots uniquement</option>
					 </select>
				</p>

				<p class="center">
					<button type="submit">Afficher</button>
				</p>

			</fieldset>
		</form>

		<table class="admin-table">
			<thead>
				<tr>
					<th>Date</th>
					<th>Articles (dont fonds)</th>
					<th>Exemplaires</th>
					<th title="Valeur en prix d\'achat">Val. Achat</th>
					<th title="Valeur en prix de vente">Val. Vente</th>
				</tr>
			</thead>
			<tbody>
				'.$table.'
			</tbody>
		</table>
	';

?>
