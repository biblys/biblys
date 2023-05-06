<?php

	$publishers = $_SQL->prepare("SELECT `p`.`publisher_id`, `publisher_name` FROM `publishers` AS `p` WHERE NOT EXISTS (SELECT `link_id` FROM `links` AS `l` WHERE `l`.`publisher_id` = `p`.`publisher_id` AND `supplier_id` IS NOT NULL AND `site_id` = :site_id) ORDER BY `publisher_name_alphabetic`");
	$publishers->bindValue('site_id',getLegacyCurrentSite()['site_id'],PDO::PARAM_INT);
	$publishers->execute() or error($publishers->errorInfo());
	
	while ($p = $publishers->fetch(PDO::FETCH_ASSOC))
	{
		$table .= '
			<tr>
				<td><a href="/pages/publisher_edit?id='.$p["publisher_id"].'">'.$p["publisher_name"].'</a></td>
			</tr>
		';
	}

	$_PAGE_TITLE = '&Eacute;diteurs sans fournisseur';
	$_ECHO .= '
		<h2>'.$_PAGE_TITLE.'</h2>
		
		<div class="admin">
			<p><a href="/pages/adm_supplier">Nouveau fournisseur</a></p>
		</div>
		
		<table class="sortable admin-table">
			<thead>
				<tr class="pointer">
					<th>&Eacute;diteur</th>
				</tr>
			</thead>
			<tbody>
				'.$table.'
			</tbody>
		</table>
	';

?>
