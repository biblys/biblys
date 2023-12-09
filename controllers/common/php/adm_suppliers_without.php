<?php

use Biblys\Legacy\LegacyCodeHelper;

$publishers = $_SQL->prepare("SELECT `p`.`publisher_id`, `publisher_name` FROM `publishers` AS `p` WHERE NOT EXISTS (SELECT `link_id` FROM `links` AS `l` WHERE `l`.`publisher_id` = `p`.`publisher_id` AND `supplier_id` IS NOT NULL AND `site_id` = :site_id) ORDER BY `publisher_name_alphabetic`");
	$publishers->bindValue('site_id', LegacyCodeHelper::getGlobalSite()['site_id'],PDO::PARAM_INT);
	$publishers->execute() or error($publishers->errorInfo());
	
	while ($p = $publishers->fetch(PDO::FETCH_ASSOC))
	{
		$table .= '
			<tr>
				<td><a href="/pages/publisher_edit?id='.$p["publisher_id"].'">'.$p["publisher_name"].'</a></td>
			</tr>
		';
	}

	\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Éditeurs sans fournisseur');
	$_ECHO .= '
		<h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>
		
		<div class="admin">
			<p><a href="/pages/adm_supplier">Nouveau fournisseur</a></p>
		</div>
		
		<table class="sortable admin-table">
			<thead>
				<tr class="pointer">
					<th>Éditeur</th>
				</tr>
			</thead>
			<tbody>
				'.$table.'
			</tbody>
		</table>
	';

?>
