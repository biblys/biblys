<?php

	//$_LOG['user_id'] = 199;

	$_JS_CALLS[] =  '//cdn.biblys.fr/fancybox/2.1.5/jquery.fancybox.pack.js';
	$_CSS_CALLS[] = 'screen://cdn.biblys.fr/fancybox/2.1.5/jquery.fancybox.css';

	$_PAGE_TITLE = 'Mes achats';

	$req = "SELECT `stock_id`, `stock_selling_date`,
			`article_id`, `article_title`, `article_url`, `article_authors`, `article_publisher`, `article_ean`, `lang_iso_639-2`
		FROM `articles`
		JOIN `stock` USING(`article_id`)
		LEFT JOIN `langs` ON `article_lang_current` = `lang_id`
		WHERE `stock`.`site_id` = :site_id AND `stock`.`user_id` = :user_id AND `stock_selling_date` IS NOT NULL
		GROUP BY `stock_id`
	ORDER BY `stock_selling_date` DESC";
	
	$export = array();
	$header = array('Ref.','Titre','Auteurs','Editeur','Langue','ISBN',"Date d'achat");
	
	$stock = $_SQL->prepare($req);
	$stock->bindValue('site_id',$_SITE['site_id'],PDO::PARAM_INT);
	$stock->bindValue('user_id',$_LOG['user_id'],PDO::PARAM_INT);
	$stock->execute() or error($stock->errorInfo());
	while ($s = $stock->fetch(PDO::FETCH_ASSOC))
	{
		
		// Image
		$s['article_cover'] = new Media('article',$s['article_id']);
		$s['stock_cover']   = new Media('stock',  $s['stock_id']);
		if ($s['article_cover']->exists())   $s['cover'] = '<a href="'.$s['article_cover']->url().'" rel="fancybox"><img src="'.$s['article_cover']->url('h100').'" height=55 alt="'.$s['article_title'].'"></a>';
		elseif ($s['stock_cover']->exists()) $s['cover'] = '<a href="'.$s['stock_cover']->url().'" rel="fancybox"><img src="'.$s['stock_cover']->url('h100').'"   height=55 alt="'.$s['article_title'].'"></a>';
		else $s['cover'] = NULL;
		
		$mybooks .= '
			<tr>
				<td class="center va-middle">'.$s['cover'].'</td>
				<td class="va-middle">
					<a href="/'.$s['article_url'].'">'.$s['article_title'].'</a><br>
					de '.authors($s['article_authors']).'<br>
					Ed. '.$s['article_publisher'].'
				</td>
				<td class="center va-middle">'._date($s['stock_selling_date'],'d/m/Y').'</td>
			</tr>
		';
		
		$export[] = array($s['stock_id'],$s['article_title'],authors($s['article_authors']),$s['article_publisher'],$s['lang_iso_639-2'],$s['article_ean'],$s['stock_selling_date']);
		
	}
	
	$_ECHO = '
		<h2>'.$_PAGE_TITLE.'</h2>
		
		<form action="/pages/export_to_csv" method="post">
			<fieldset class="center">
				<input type="hidden" name="filename" value="achats-'.$_SITE['site_name'].'-'.makeurl($_LOG['user_screen_name']).'">
				<input type="hidden" name="header" value="'.htmlentities(json_encode($header)).'">
				<input type="hidden" name="data" value="'.htmlentities(json_encode($export)).'">
				<button type="submit">T&eacute;l&eacute;charger au format CSV</button>
			</fieldset>
		</form>
		<br />
		
		<table class="admin-table">
			<thead>
				<tr>
					<th></th>
					<th>Titre</th>
					<th>Date d\'achat</th>
				</tr>
			</thead>
			<tbody>
				'.$mybooks.'
			</tbody>
		</table>
		
	';
	
?>
		