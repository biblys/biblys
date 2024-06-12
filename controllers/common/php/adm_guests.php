<?php

	$events = $_SQL->prepare("SELECT `event_id`, `event_title`, `event_url` FROM `events` WHERE `event_id` = :event_id LIMIT 1");
	$events->execute(array("event_id" => $_GET["event_id"]));
	if($e = $events->fetch()) {

		// Ajouter un invite
		if (!empty($_POST["adm_people_id"]))
		{

			$sql  = "REPLACE INTO `roles`(`people_id`,`event_id`,`job_id`) VALUES(:adm_people_id,:event_id,:job_id);";
			$sql .= "UPDATE `events` SET `event_update` = NOW() WHERE `event_id` = :event_id LIMIT 1;";
			$sql .= "UPDATE `people` SET `people_update` = NOW() WHERE `people_id` = :adm_people_id LIMIT 1;";

			$queries = $_SQL->prepare($sql);
			$queries->bindValue('adm_people_id',$_POST["adm_people_id"],PDO::PARAM_INT);
			$queries->bindValue('event_id',$e["event_id"],PDO::PARAM_INT);
			$queries->bindValue('job_id',$_POST["job_id"],PDO::PARAM_INT);
			$queries->execute() or error($queries->errorInfo());

			redirect("/pages/adm_guests?event_id=".$e["event_id"]."&added=1");
		}
		elseif (isset($_GET["added"]))
		{
			$_ECHO .= '<p class="success">L\'intervenant a bien été ajouté à cet évènement.</p>';
		}

		// Supprimer un invite
		if (!empty($_GET["del"]))
		{

			$sql  = "DELETE FROM `roles` WHERE `id` = :id LIMIT 1;";
			$sql .= "UPDATE `events` SET `event_update` = NOW() WHERE `event_id` = :event_id LIMIT 1;";
			$sql .= "UPDATE `people` SET `people_update` = NOW() WHERE `people_id` = :people_id LIMIT 1;";

			$queries = $_SQL->prepare($sql);
			$queries->bindValue('id',$_GET["del"],PDO::PARAM_INT);
			$queries->bindValue('event_id',$e["event_id"],PDO::PARAM_INT);
			$queries->bindValue('people_id',$_GET["people_id"],PDO::PARAM_INT);
			$queries->execute() or error(pdo_error());

			redirect("/pages/adm_guests?event_id=".$e["event_id"]."&deleted=1");
		}
		elseif (isset($_GET["deleted"]))
		{
			$_ECHO .= '<p class="success">L\'intervenant a bien été retiré de cet évènement.</p>';
		}

		// Menu déroulant job
		$jobs_options = null;
		$jobs = $_SQL->query("SELECT `job_id`, `job_name`, `job_other_names` FROM `jobs` WHERE `job_event` = '1'");
		while($j = $jobs->fetch()) {
			if(!empty($j["job_other_names"])) $j["job_name"] = $j["job_other_names"];
			$jobs_options .= '<option value="'.$j["job_id"].'">'.$j["job_name"].'</option>';
		}

		// Tous les participants
		$guest_list = null;
		$guests = $_SQL->query("SELECT `id` AS `role_id`, `people_id`, `people_name`, `people_url`, `job_name`, `job_other_names` FROM `roles` JOIN `people` USING(`people_id`) LEFT JOIN `jobs` USING(`job_id`) WHERE `event_id` = '".$_GET["event_id"]."' ORDER BY `people_alpha`");
		while($g = $guests->fetch()) {
			if(!empty($g["job_other_names"])) $g["job_name"] = $g["job_other_names"];
			$guest_list .= '<li><a href="/'.$g["people_url"].'/">'.$g["people_name"].'</a> ('.$g["job_name"].') <a href="/pages/adm_guests?event_id='.$_GET["event_id"].'&del='.$g["role_id"].'&people_id='.$g["people_id"].'"><img src="/common/icons/delete_16x16.png"></li>';
		}

		$_PAGE_TITLE = 'Gestion des participants';
		$_ECHO .= '
			<h2>Gestion des participants</h2>
			<h3>Ajouter un participant à &laquo; <a href="/programme/'.$e["event_url"].'">'.$e["event_title"].'</a> &raquo;</h3>
			<form method="post" action="/pages/adm_guests?event_id='.$e["event_id"].'">
				<fieldset>
					<label for="adm_people">Nom :</label>
					<input type="text" id="adm_people" name="adm_people" class="autocomplete" autofocus autocomplete="off">
					<input type="hidden" id="adm_people_id" name="adm_people_id" />
					<br />
					<label for="job_id">Rôle :</label>
					<select id="job_id" name="job_id">
						'.$jobs_options.'
					</select><br />
					<div class="center">
						<br />
						<button type="submit" class="btn btn-primary" style="display: none;">Ajouter le participant</button>
					</div>
				</fieldset>
			</form>

			<h3>Tous les participants de &laquo; <a href="/programme/'.$e["event_url"].'">'.$e["event_title"].'</a> &raquo;</h3>
				<ul>'.$guest_list.'</ul>
		';

	}

?>
