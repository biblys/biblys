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


	// Par défaut
use Biblys\Legacy\LegacyCodeHelper;

if (empty($_GET["date"])) $_GET["date"] = date("Y-m-d");
	if (empty($_GET["condition"])) $_GET["condition"] = 'all';

	\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Extrait d\'inventaire au '._date($_GET['date'], 'j f Y'));

	// REQUETE DES STOCKS

	// Filtrer par date
	$_QUERY = ' AND `stock_purchase_date` <= :date
		AND (`stock_selling_date` IS NULL OR `stock_selling_date` > :date)
		AND (`stock_return_date` IS NULL OR `stock_return_date` > :date)
		AND (`stock_lost_date` IS NULL OR `stock_lost_date` > :date)
	';
	$params['date'] = $_GET['date'].' 23:59:59';

	// Filtrer par état
	if (isset($_GET['condition']))
	{
		if ($_GET['condition'] == 'new') $_QUERY .= ' AND `stock_condition` = "Neuf"';
		elseif ($_GET['condition'] == 'used') $_QUERY .= ' AND `stock_condition` != "Neuf"';
	}

	$query = $_SQL->prepare('SELECT
			`s`.`stock_id`, `stock_selling_price`, `stock_condition`, `stock_purchase_date`, `stock_purchase_price`, `stock_depot`,
			`a`.`article_id`, `article_tva`, `type_id`, `article_pubdate`, `article_links`
		FROM `stock` AS `s`
		JOIN `articles` AS `a` ON `s`.`article_id` = `a`.`article_id`
		WHERE `s`.`site_id` = '. LegacyCodeHelper::getGlobalSite()["site_id"].' '.$_QUERY);
	$query->execute($params) or error($query->errorInfo());

	$sales = $query->fetchAll();


	$total_stock = array();
	$total_purchase_ht = 0;
	$total_purchase_ttc = 0;
	$total_selling_ht = 0;
	$total_selling_ttc = 0;
	$tva = array();

	// Taux de TVA
	if ($rates = tva_rate('all'))
    {
        foreach ($rates as $r)
        {
            $tva[$r*10]['rate'] = $r;
            $tva[$r*10]['revenue_ht'] = 0;
            $tva[$r*10]['revenue_ttc'] = 0;
            $tva[$r*10]['revenue_tva'] = 0;
        }
    }

	// Types d'articles
    $types = \Biblys\Data\ArticleType::getAll();
	$type_r = array();
	foreach ($types as $t)
	{
		$ty[$t->getId()]['name'] = $t->getName();
		$ty[$t->getId()]['revenue_ht'] = 0;
		$ty[$t->getId()]['revenue_ttc'] = 0;
		$ty[$t->getId()]['sales'] = array();
	}

	// Rayons
	$rayons = $_SQL->query('SELECT `rayon_id`, `rayon_name` FROM `rayons` WHERE `site_id` = '. LegacyCodeHelper::getGlobalSite()['site_id'].' ORDER BY `rayon_order`');
	$rayons = $rayons->fetchAll(PDO::FETCH_ASSOC);
	$ra = array();
	foreach ($rayons as $r)
	{
		$ra[$r['rayon_id']]['name'] = $r['rayon_name'];
		$ra[$r['rayon_id']]['purchase_ttc'] = 0;
		$ra[$r['rayon_id']]['purchase_ht'] = 0;
		$ra[$r['rayon_id']]['selling_ttc'] = 0;
		$ra[$r['rayon_id']]['selling_ht'] = 0;
		$ra[$r['rayon_id']]['stock'] = array();
	}

	// Sans rayon
	$ra[0] = array('name' => 'Sans rayon', 'purchase_ttc' => 0, 'selling_ttc' => 0, 'purchase_ht' => 0, 'selling_ht' => 0, 'sales' => array());


	// ** Ancienneté des articles ** //

	// Moins de trois mois (nouveautés)
	$m3_purchase_ht = 0;
	$m3_purchase_ttc = 0;
	$m3_selling_ht = 0;
	$m3_selling_ttc = 0;
	$m3_stock = array();

	// Entre trois mois et un an
	$y1_purchase_ht = 0;
	$y1_purchase_ttc = 0;
	$y1_selling_ht = 0;
	$y1_selling_ttc = 0;
	$y1_stock = array();

	// Un an ou plus
	$old_purchase_ht = 0;
	$old_purchase_ttc = 0;
	$old_selling_ht = 0;
	$old_selling_ttc = 0;
	$old_stock = array();

	// Date inconnue
	$uk_purchase_ht = 0;
	$uk_purchase_ttc = 0;
	$uk_selling_ht = 0;
	$uk_selling_ttc = 0;
	$uk_stock = array();

	// ** Etat des exemplaires **//

	// Neuf
	$new_purchase_ht = 0;
	$new_purchase_ttc = 0;
	$new_selling_ht = 0;
	$new_selling_ttc = 0;
	$new_stock = array();

	// Occasion
	$used_purchase_ht = 0;
	$used_purchase_ttc = 0;
	$used_selling_ht = 0;
	$used_selling_ttc = 0;
	$used_stock = array();

	// En dépot
	$depot_purchase_ht = 0;
	$depot_purchase_ttc = 0;
	$depot_selling_ht = 0;
	$depot_selling_ttc = 0;
	$depot_stock = array();

	// Réel
	$real_purchase_ht = 0;
	$real_purchase_ttc = 0;
	$real_selling_ht = 0;
	$real_selling_ttc = 0;
	$real_stock = array();

	// Lieu de vente
	$total_shop_ht = 0;
	$total_shop_ttc = 0;
	$total_web_ht = 0;
	$total_web_ttc = 0;

	foreach ($sales as $s)
	{

		// Prix HT
		if (LegacyCodeHelper::getGlobalSite()['site_tva'])
		{
			$s['tva_rate'] = tva_rate($s['article_tva'],$s["stock_purchase_date"]) / 100;
			$s['ti'] = $s['tva_rate'] * 1000;
			$s['stock_purchase_price_ht'] = $s['stock_purchase_price'] / (1 + $s['tva_rate']);
			$s['stock_selling_price_ht'] = $s['stock_selling_price'] / (1 + $s['tva_rate']);
		}
        else
        {
            $s['stock_purchase_price_ht'] = $s['stock_purchase_price'];
            $s['stock_selling_price_ht'] = $s['stock_selling_price'];
        }

		// Total
		$total_purchase_ttc += $s['stock_purchase_price'];
		if (LegacyCodeHelper::getGlobalSite()['site_tva']) $total_purchase_ht += $s['stock_purchase_price_ht'];
		$total_selling_ttc += $s['stock_selling_price'];
		$total_selling_ht += $s['stock_selling_price_ht'];
		$total_stock[] = $s['article_id'];

		// Par taux de TVA
        if (LegacyCodeHelper::getGlobalSite()['site_tva'])
        {
    		$tva[$s['ti']]['revenue_ht'] += $s['stock_selling_price_ht'];
        	$tva[$s['ti']]['revenue_ttc'] += $s['stock_selling_price'];
        }

		// Par type d'article
		$ty[$s['type_id']]['revenue_ttc'] += $s['stock_selling_price'];
		$ty[$s['type_id']]['revenue_ht'] += $s['stock_selling_price_ht'];
		$ty[$s['type_id']]['sales'][] += $s['article_id'];

		// Par rayon
		$s['rayons'] = 0;
		if (preg_match_all('/\[rayon:([0-9]*)\]/', $s['article_links'], $matches))
		{

			foreach ($matches as $m) // Tous les rayons trouvés
			{
				foreach($m as $rm) // Pour chaque rayon trouvé
				{
					if (isset($ra[$rm])) // Si le rayon est un rayon de la librairie
					{
						$ra[$rm]['purchase_ttc'] += $s['stock_purchase_price'];
						$ra[$rm]['selling_ttc'] += $s['stock_selling_price'];
						$ra[$rm]['purchase_ht'] += $s['stock_purchase_price_ht'];
						$ra[$rm]['selling_ht'] += $s['stock_selling_price_ht'];
						$ra[$rm]['stock'][] = $s['article_id'];
						$s['rayons']++;
					}
				}
			}
		}

		// Si aucun rayon, on ajoute l'exemplaire à "Sans rayons"
		if ($s['rayons'] == 0)
		{
			$ra[0]['purchase_ttc'] += $s['stock_selling_price'];
			$ra[0]['selling_ttc'] += $s['stock_selling_price'];
			$ra[0]['purchase_ht'] += $s['stock_selling_price_ht'];
			$ra[0]['selling_ht'] += $s['stock_selling_price_ht'];
			$ra[0]['stock'][] = $s['article_id'];
		}

		// Par ancienneté des articles
		if ($s['stock_purchase_date'] < date('Y-m-d H:i:s', strtotime($s['article_pubdate'].'+ 3 months'))) // Moins de trois mois
		{
			$m3_purchase_ht += $s['stock_purchase_price_ht'];
			$m3_purchase_ttc += $s['stock_purchase_price'];
			$m3_selling_ht += $s['stock_selling_price_ht'];
			$m3_selling_ttc += $s['stock_selling_price'];
			$m3_stock[] = $s['article_id'];
		}
		elseif ($s['stock_purchase_date'] < date('Y-m-d H:i:s', strtotime($s['article_pubdate'].'+ 1 years'))) // Moins d'un an
		{
			$y1_purchase_ht += $s['stock_purchase_price_ht'];
			$y1_purchase_ttc += $s['stock_purchase_price'];
			$y1_selling_ht += $s['stock_selling_price_ht'];
			$y1_selling_ttc += $s['stock_selling_price'];
			$y1_stock[] = $s['article_id'];
		}
		elseif (!empty($s['article_pubdate']) && $s['article_pubdate'] != 0000-00-00) // Si la date n'est pas vide : plus d'un an
		{
			$old_purchase_ht += $s['stock_purchase_price_ht'];
			$old_purchase_ttc += $s['stock_purchase_price'];
			$old_selling_ht += $s['stock_selling_price_ht'];
			$old_selling_ttc += $s['stock_selling_price'];
			$old_stock[] = $s['article_id'];
		}
		else // La date est vide
		{
			$uk_purchase_ht += $s['stock_purchase_price_ht'];
			$uk_purchase_ttc += $s['stock_purchase_price'];
			$uk_selling_ht += $s['stock_selling_price_ht'];
			$uk_selling_ttc += $s['stock_selling_price'];
			$uk_stock[] = $s['article_id'];
		}

		// Par état des exemplaires (neuf/occasion)
		if ($s['stock_condition'] == 'Neuf' || empty($s['stock_condition']))
		{
			$new_purchase_ht += $s['stock_purchase_price_ht'];
			$new_purchase_ttc += $s['stock_purchase_price'];
			$new_selling_ht += $s['stock_selling_price_ht'];
			$new_selling_ttc += $s['stock_selling_price'];
			$new_stock[] = $s['article_id'];
		}
		else
		{
			$used_purchase_ht += $s['stock_purchase_price_ht'];
			$used_purchase_ttc += $s['stock_purchase_price'];
			$used_selling_ht += $s['stock_selling_price_ht'];
			$used_selling_ttc += $s['stock_selling_price'];
			$used_stock[] = $s['article_id'];
		}

		// Par appartenance à un dépot (dépot/reel)
		if ($s['stock_depot'])
		{
			$depot_purchase_ht += $s['stock_purchase_price_ht'];
			$depot_purchase_ttc += $s['stock_purchase_price'];
			$depot_selling_ht += $s['stock_selling_price_ht'];
			$depot_selling_ttc += $s['stock_selling_price'];
			$depot_stock[] = $s['article_id'];
		}
		else
		{
			$real_purchase_ht += $s['stock_purchase_price_ht'];
			$real_purchase_ttc += $s['stock_purchase_price'];
			$real_selling_ht += $s['stock_selling_price_ht'];
			$real_selling_ttc += $s['stock_selling_price'];
			$real_stock[] = $s['article_id'];
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
						<td class="right">'.price($v['revenue_ttc'], 'EUR').'</td>
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
						<td class="right">'.price($v['revenue_ttc'], 'EUR').'</td>
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
			if (!empty($v['purchase_ttc']))
			{
				//if (!empty($total_ttc)) $v['share'] = round(($v['revenue_ttc'] / $total_ttc) * 100, 2);
				//else
				$v['share'] = 0;

				$rayon_table .= '
					<tr>
						<td>'.$v['name'].'</td>

						<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'&rayon_id='.$k.'">'.count($v['stock']).'</a></td>
						<td class="right">'.percent(count($v['stock']), count($total_stock), 2).'</td>

						<td class="right">'.count(array_unique($v['stock'])).'</td>
						<td class="right">'.percent(count(array_unique($v['stock'])), count(array_unique($total_stock)), 2).'</td>

						<td class="right">
							'.price($v['purchase_ttc'], 'EUR').'<br>
							<span class="gray">'.price($v['purchase_ht'], 'EUR').'</span>
						</td>
						<td class="right">'.percent($v['purchase_ttc'], $total_purchase_ttc, 2).'</td>

						<td class="right">
							'.price($v['selling_ttc'], 'EUR').'<br>
							<span class="gray">'.price($v['selling_ht'], 'EUR').'</span>
						</td>
						<td class="right">'.percent($v['selling_ttc'], $total_selling_ttc, 2).'</td>
					</tr>
				';
			}
		}
	}

	$_ECHO .= '

		<h1><span class="fa fa-bar-chart"></span> '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>
		<form class="fieldset hidden-print">
			<fieldset>
				<legend>Options</legend>

				<p>
					<label for="date">Au :</label>
					<input type="date" name="date" id="date" placeholder="AAAA-MM-JJ" value="'.$_GET["date"].'">
				</p>

				<p>
					<label for="condition">État :</label>
					<select name="condition">
						<option value="all">Tous</option>
						<option value="new"'.($_GET['condition'] == 'new' ? ' selected' : null).'>Neuf</option>
						<option value="used"'.($_GET['condition'] == 'used' ? ' selected' : null).'>Occasion</option>
					</select>
				</p>

				<p class="text-center">
					<button type="submit" class="btn btn-primary">Afficher l\'état du stock</button>
				</p>

			</fieldset>
		</form>

		<h3>Stock total</h3>

		<table class="admin-table">
			<thead>
				<tr>
					<th></th>
					<th title="Nombre d\'exemplaires en stock">Exemplaires</th>
					<th title="Nombre d\'articles différent en stock">Articles</th>
					<th>Val. Achat</th>
					<th>Val. Vente</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Stock</td>
					<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'">'.count($total_stock).'</a></td>
					<td class="right">'.count(array_unique($total_stock)).'</td>
					<td class="right">
						'.price($total_purchase_ttc,'EUR').'<br>
						<span class="gray">'.price($total_purchase_ht,'EUR').'</span>
					</td>
					<td class="right">
						'.price($total_selling_ttc,'EUR').'<br>
						<span class="gray">'.price($total_selling_ht,'EUR').'</span>
					</td>
				</tr>
			</tbody>
		</table>

		<h3>Stock ventilé par...</h3>

        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a role="tab" data-toggle="tab" href="#rayon">Rayon</a></li>
            <li><a href="#age" role="tab" data-toggle="tab">Ancienneté</a></li>
            <li><a href="#condition" role="tab" data-toggle="tab">État</a></li>
            <li><a href="#depot" role="tab" data-toggle="tab">Dépôt</a></li>
        </ul>

		<div class="tab-content">
            <br>

			<div class="tab-pane active" id="rayon">
	            <table class="admin-table">
					<thead>
						<tr>
							<th>Rayon</th>
							<th colspan="2">Exemplaires</th>
							<th colspan="2">Articles</th>
							<th colspan="2">Val. Achat</th>
							<th colspan="2">Val. Vente</th>
						</tr>
					</thead>
					<tbody>
						'.$rayon_table.'
					</tbody>
				</table>
			</div>

			<div class="tab-pane" id="age">
				<table class="admin-table">
					<thead>
						<tr>
							<th></th>
							<th colspan=2>Exemplaires</th>
							<th colspan=2>Articles</th>
							<th colspan=2>Val. Achat</th>
							<th colspan=2>Val. Vente</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Moins de 3 mois (nouveautés)</td>
							<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'&rayon_id='.$k.'">'.count($m3_stock).'</a></td>
							<td class="right">'.percent(count($m3_stock), count($total_stock), 2).'</td>

							<td class="right">'.count(array_unique($m3_stock)).'</td>
							<td class="right">'.percent(count(array_unique($m3_stock)), count(array_unique($total_stock)), 2).'</td>

							<td class="right">
								'.price($m3_purchase_ttc, 'EUR').'<br>
								<span class="gray">'.price($m3_purchase_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($m3_purchase_ttc, $total_purchase_ttc, 2).'</td>

							<td class="right">
								'.price($m3_selling_ttc, 'EUR').'<br>
								<span class="gray">'.price($m3_selling_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($m3_selling_ttc, $total_selling_ttc, 2).'</td>
						</tr>
						<tr>
							<td>Moins d\'un an</td>
							<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'&rayon_id='.$k.'">'.count($y1_stock).'</a></td>
							<td class="right">'.percent(count($y1_stock), count($total_stock), 2).'</td>

							<td class="right">'.count(array_unique($y1_stock)).'</td>
							<td class="right">'.percent(count(array_unique($y1_stock)), count(array_unique($total_stock)), 2).'</td>

							<td class="right">
								'.price($y1_purchase_ttc, 'EUR').'<br>
								<span class="gray">'.price($y1_purchase_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($y1_purchase_ttc, $total_purchase_ttc, 2).'</td>

							<td class="right">
								'.price($y1_selling_ttc, 'EUR').'<br>
								<span class="gray">'.price($y1_selling_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($y1_selling_ttc, $total_selling_ttc, 2).'</td>
						</tr>
						<tr>
							<td>Un an ou plus (fonds)</td>
							<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'&rayon_id='.$k.'">'.count($old_stock).'</a></td>
							<td class="right">'.percent(count($old_stock), count($total_stock), 2).'</td>

							<td class="right">'.count(array_unique($old_stock)).'</td>
							<td class="right">'.percent(count(array_unique($old_stock)), count(array_unique($total_stock)), 2).'</td>

							<td class="right">
								'.price($old_purchase_ttc, 'EUR').'<br>
								<span class="gray">'.price($old_purchase_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($old_purchase_ttc, $total_purchase_ttc, 2).'</td>

							<td class="right">
								'.price($old_selling_ttc, 'EUR').'<br>
								<span class="gray">'.price($old_selling_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($old_selling_ttc, $total_selling_ttc, 2).'</td>
						</tr>
						<tr>
							<td>Date de parution inconnue</td>
							<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'&rayon_id='.$k.'">'.count($uk_stock).'</a></td>
							<td class="right">'.percent(count($uk_stock), count($total_stock), 2).'</td>

							<td class="right">'.count(array_unique($uk_stock)).'</td>
							<td class="right">'.percent(count(array_unique($uk_stock)), count(array_unique($total_stock)), 2).'</td>

							<td class="right">
								'.price($uk_purchase_ttc, 'EUR').'<br>
								<span class="gray">'.price($uk_purchase_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($uk_purchase_ttc, $total_purchase_ttc, 2).'</td>

							<td class="right">
								'.price($uk_selling_ttc, 'EUR').'<br>
								<span class="gray">'.price($uk_selling_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($uk_selling_ttc, $total_selling_ttc, 2).'</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="tab-pane" id="condition">
				<table class="admin-table">
					<thead>
						<tr>
							<th></th>
							<th colspan=2>Exemplaires</th>
							<th colspan=2>Articles</th>
							<th colspan=2>Val. Achat</th>
							<th colspan=2>Val. Vente</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Neuf</td>
							<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'&rayon_id='.$k.'">'.count($new_stock).'</a></td>
							<td class="right">'.percent(count($new_stock), count($total_stock), 2).'</td>

							<td class="right">'.count(array_unique($new_stock)).'</td>
							<td class="right">'.percent(count(array_unique($new_stock)), count(array_unique($total_stock)), 2).'</td>

							<td class="right">
								'.price($new_purchase_ttc, 'EUR').'<br>
								<span class="gray">'.price($new_purchase_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($new_purchase_ttc, $total_purchase_ttc, 2).'</td>

							<td class="right">
								'.price($new_selling_ttc, 'EUR').'<br>
								<span class="gray">'.price($new_selling_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($new_selling_ttc, $total_selling_ttc, 2).'</td>
						</tr>
						<tr>
							<td>Occasion</td>
							<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'&rayon_id='.$k.'">'.count($used_stock).'</a></td>
							<td class="right">'.percent(count($used_stock), count($total_stock), 2).'</td>

							<td class="right">'.count(array_unique($used_stock)).'</td>
							<td class="right">'.percent(count(array_unique($used_stock)), count(array_unique($total_stock)), 2).'</td>

							<td class="right">
								'.price($used_purchase_ttc, 'EUR').'<br>
								<span class="gray">'.price($used_purchase_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($used_purchase_ttc, $total_purchase_ttc, 2).'</td>

							<td class="right">
								'.price($used_selling_ttc, 'EUR').'<br>
								<span class="gray">'.price($used_selling_ht, 'EUR').'</span>
							</td>
							<td class="right">'.percent($used_selling_ttc, $total_selling_ttc, 2).'</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="tab-pane" id="depot">
			<table class="admin-table">
				<thead>
					<tr>
						<th></th>
						<th colspan=2>Exemplaires</th>
						<th colspan=2>Articles</th>
						<th colspan=2>Val. Achat</th>
						<th colspan=2>Val. Vente</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Dépôt</td>
						<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'&rayon_id='.$k.'">'.count($depot_stock).'</a></td>
						<td class="right">'.percent(count($depot_stock), count($total_stock), 2).'</td>

						<td class="right">'.count(array_unique($depot_stock)).'</td>
						<td class="right">'.percent(count(array_unique($depot_stock)), count(array_unique($total_stock)), 2).'</td>

						<td class="right">
							'.price($depot_purchase_ttc, 'EUR').'<br>
							<span class="gray">'.price($depot_purchase_ht, 'EUR').'</span>
						</td>
						<td class="right">'.percent($depot_purchase_ttc, $total_purchase_ttc, 2).'</td>

						<td class="right">
							'.price($depot_selling_ttc, 'EUR').'<br>
							<span class="gray">'.price($depot_selling_ht, 'EUR').'</span>
						</td>
						<td class="right">'.percent($depot_selling_ttc, $total_selling_ttc, 2).'</td>
					</tr>
					<tr>
						<td>Réel</td>
						<td class="right"><a href="/pages/adm_stock_detail?date='.$_GET['date'].'&rayon_id='.$k.'">'.count($real_stock).'</a></td>
						<td class="right">'.percent(count($real_stock), count($total_stock), 2).'</td>

						<td class="right">'.count(array_unique($real_stock)).'</td>
						<td class="right">'.percent(count(array_unique($real_stock)), count(array_unique($total_stock)), 2).'</td>

						<td class="right">
							'.price($real_purchase_ttc, 'EUR').'<br>
							<span class="gray">'.price($real_purchase_ht, 'EUR').'</span>
						</td>
						<td class="right">'.percent($real_purchase_ttc, $total_purchase_ttc, 2).'</td>

						<td class="right">
							'.price($real_selling_ttc, 'EUR').'<br>
							<span class="gray">'.price($real_selling_ht, 'EUR').'</span>
						</td>
						<td class="right">'.percent($real_selling_ttc, $total_selling_ttc, 2).'</td>
					</tr>
				</tbody>
			</table>
			</div>



	        <br><br><br><br><br><br><br><br><br>

		</div>




	';
