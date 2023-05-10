<?php

	$sites = array(
		array(
			'id' => 5,
			'name' => 'Charybde',
			'login' => 'CHARYBDE-PDL',
			'passw' => '4445-Pdl',
			'shop_id' => '5553',
			'message' => null
		),
		array(
			'id' => 8,
			'name' => 'Scylla',
			'login' => 'SCYLLA-PDL',
			'passw' => '1065-Pdl',
			'shop_id' => '5655',
			'message' => null
		)
	);
	
	$ftp_server = 'ftp.titelive.com';
	$stream_context = stream_context_create(array('ftp' => array('overwrite' => true))); 

	\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Exportation Place des libraires');
	
	$_ECHO .= '<h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>';
	
	foreach ($sites as $site) {
	
		$sql = $_SQL->prepare('
			SELECT COUNT(`stock_id`) AS `qty`, `article_ean`, `stock_selling_price`
			FROM `stock`
			JOIN `articles` USING(`article_id`)
			WHERE `site_id` = :site_id
				AND `article_ean` IS NOT NULL AND `stock_condition` = "Neuf"
				AND `stock_selling_date` IS NULL
				AND `stock_return_date` IS NULL
				AND `stock_lost_date` IS NULL
			GROUP BY `article_id`
		');
		$sql->execute(['site_id' => $site['id']]);
		
		$stock = $sql->fetchAll(PDO::FETCH_ASSOC);
		$export = 'EXTRACTION STOCK DU '.date('d/m/Y')."\r\n";
		
		$stock_count = 0;
		$article_count = 0;
		
		foreach ($stock as $s) {
			$export .= $site['shop_id'].''.
						$s['article_ean'].''.
						str_pad($s['qty'], 4, '0', STR_PAD_LEFT).''.
						str_pad($s['stock_selling_price'], 10, '0', STR_PAD_LEFT)
					."\r\n";
			$stock_count += $s['qty'];
			$article_count++;
		}
		
		// FTP Upload
		$ftp = 'ftp://'.$site['login'].':'.$site['passw'].'@'.$ftp_server.'/'.$site['shop_id'].'_ART.asc';
		file_put_contents('ftp://'.$site['login'].':'.$site['passw'].'@'.$ftp_server.'/'.$site['shop_id'].'_ART.asc', $export, 0, $stream_context);
		
		if (isset($error)) {
			$_ECHO .= '<p class="error">'.$error.'</p>';
		} else {
			$_ECHO .= '<p class="success">Le fichier de stock '.$site['name'].' ('.$stock_count.' exemplaires de '.$article_count.' articles) a bien été transmis à Tite-Live.</p>';
		}
	}
