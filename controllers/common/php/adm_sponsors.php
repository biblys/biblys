<?php

	\Biblys\Legacy\LegacyCodeHelper::setLegacyGlobalPageTitle('Parrainages');

	if (!empty($_POST))
	{
		if (!$um->get(array("user_id" => $_POST['user_id'])))
		{
			$error = 'L\'identifiant client '.$_POST['user_id'].' du filleul n\'est pas valide';
		}
		elseif (!$um->get(array("user_id" => $_POST['link_sponsor_user_id'])))
		{
			$error = 'L\'identifiant client '.$_POST['link_sponsor_user_id'].' du parrain n\'est pas valide';
		}
		elseif ($_POST['user_id'] == $_POST['link_sponsor_user_id'])
		{
			$error = 'Le parrain et le filleul ne peuvent être identiques.';
		}
		else
		{
			$insert = $_SQL->prepare('INSERT INTO `links`(`site_id`,`user_id`,`link_sponsor_user_id`,`link_date`) VALUES(:site_id,:user_id,:link_sponsor_user_id,NOW())');
			$insert->bindValue('site_id',getLegacyCurrentSite()['site_id'],PDO::PARAM_INT);
			$insert->bindValue('user_id',$_POST['user_id'],PDO::PARAM_INT);
			$insert->bindValue('link_sponsor_user_id',$_POST['link_sponsor_user_id'],PDO::PARAM_INT);
			$insert->execute() or error($insert->errorInfo());
			
			redirect('/pages/adm_sponsors', array('success' => 'Le nouveau parrainage a été créé.'));
		}
		if (isset($error)) redirect('/pages/adm_sponsors', array('error' => $error, 'user_id' => $_POST['user_id'], 'link_sponsor_user_id' => $_POST['link_sponsor_user_id']));
	}
	elseif (isset($_GET['delete']))
	{
		$delete = $_SQL->prepare('DELETE FROM `links` WHERE `link_id` = :link_id LIMIT 1');
		$delete->bindValue('link_id',$_GET['delete'],PDO::PARAM_INT);
		$delete->execute() or error($delete->errorInfo());
		
		redirect('/pages/adm_sponsors', array('success' => 'Le parrainage a été supprimé.'));
	}
	
	if (isset($_GET['success'])) $message = '<p class="success">'.$_GET['success'].'</p>';
	elseif (isset($_GET['error'])) $message = '<p class="error">'.$_GET['error'].'</p>';
	
	$q = $_SQL->prepare('SELECT `link_id`, `links`.`user_id`, `link_sponsor_user_id`, SUM(`stock_selling_price`) AS `CA`
		FROM `links`
		LEFT JOIN `stock` ON `stock`.`user_id` = `links`.`user_id` AND `stock`.`site_id` = :site_id
		WHERE `links`.`site_id` = :site_id AND `link_sponsor_user_id` IS NOT NULL
		GROUP BY `link_id`
		ORDER BY `link_sponsor_user_id`
	');
	$q->bindValue('site_id',getLegacyCurrentSite()['site_id'],PDO::PARAM_INT);
	$q->execute() or error($q->errorInfo());
	$sponsors = $q->fetchAll(PDO::FETCH_ASSOC);
	$q->closeCursor();
	
	if (count($sponsors))
	{
		
		// On récupère tous les user_id pour extraire les infos de la BBD
		foreach($sponsors as $s)
		{
			$user_ids[] = $s['user_id'];
			$user_ids[] = $s['link_sponsor_user_id'];
		}
		$um = new UserManager();
		$users = $um->getByIds($user_ids);
		reset($sponsors);
		
		// Boucle pour construire le tableau	
		foreach($sponsors as $s)
		{
			$sponsors_table .= '
				<tr>
					<td>'.$users[$s['user_id']]->getUserName().'</td>
					<td>'.$users[$s['link_sponsor_user_id']]->getUserName().'</td>
					<td>'.price($s['CA'],'EUR').'</td>
					<td><a href="/pages/adm_sponsors?delete='.$s['link_id'].'" data-confirm="Voulez-vous vraiment SUPPRIMER ce lien de parrainage ?"><img src="/common/icons/delete.svg" width=16 alt="Supprimer le parrainage" title="Supprimer le parrainage"></a></td>
				</tr>
			';
		}
	}
	
	$_ECHO .= '
		<h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>
		
		'.$message.$error.'
		
		<form action="/pages/adm_sponsors" method="post" class="fieldset">
			<fieldset>
				<legend>Créer un parrainage</legend>
			
				<p>
					<label for="user_id">Id. filleul :</label>
					<input type="number" id="user_id" name="user_id" value="'.$_GET['user_id'].'">
				</p>
			
				<p>
					<label for="link_sponsor_user_id">Id. parrain :</label>
					<input type="number" id="link_sponsor_user_id" name="link_sponsor_user_id" value="'.$_GET['link_sponsor_user_id'].'">
				</p>
				
				<div class="center">
					<button type="submit">Ajouter</button>
				</div>
			</fieldset>
		</form>
		
		<table class="admin-table sortable">
			<thead>
				<tr>
					<th>Filleul</th>
					<th>Parrain</th>
					<th>CA du filleul</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				'.$sponsors_table.'
			</tbody>
		</table>
	';

?>
