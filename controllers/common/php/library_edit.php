<?php

use Biblys\Legacy\LegacyCodeHelper;

if (LegacyCodeHelper::getGlobalVisitor()->isLibrary()) $_GET['id'] = LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('library_id');
	elseif (LegacyCodeHelper::getGlobalVisitor()->isAdmin()) $mode = 'admin';
	else trigger_error('Vous n\'avez pas le droit d\'accéder à cette page.', E_USER_ERROR);
	
	$_L = new LibraryManager();
	$um = new UserManager();
	
	// Edit an existing library
	if (isset($_GET['id']))
	{
		if ($getLibrary = $_L->get(array('library_id' => $_GET['id'])))
		{
			$l = $getLibrary;
			\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Modifier <a href="/bibliotheque/'.$l['library_url'].'">'.$l['library_name'].'</a>');
		}
		else trigger_error('Cette bibliothèque n\'existe pas.', E_USER_ERROR);
	}
	
	// Create a new library
	else
	{	
		\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Créer une nouvelle bibliothèque');
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		
		$params = array();
		
		// Create new library
		if (empty($_POST['library_id']))
		{
			$l = $_L->create();
			$_POST['library_id'] = $l->get('id');
		}

		if ($getLibrary = $_L->get(array('library_id' => $_POST['library_id'])))
		{
			$l = $getLibrary;
		}
		else trigger_error('Cette librairie n\'existe pas.', E_USER_ERROR);
		
		foreach ($_POST as $key => $val)
		{
			$l->set($key, $val);
		}
		$_L->update($l);
		
		$params['success'] = $l->get('name').' a bien été mise à jour.';
		redirect('/bibliotheque/' . $l->get('url'), $params);
		
	}
	else
	{	
		
		// Images
		require_once biblysPath().'/inc/Image.class.php';
		$_IMAGES = new ImagesManager($_SQL);
        
        // Authorized users
        $rm = new RightManager();
        $rights = $rm->getAll(array('library_id' => $l->get('library_id')));
        $managers = array();
        foreach ($rights as $r)
        {
            if ($user = $um->getById($r->get('user_id')))
            {
                $managers[] .= $user->getUserName();
            }
        }
        if (!empty($managers))
        {
            $managers = '<h2>Utilisateurs autorisés'.'</h2>'.implode(', ', $managers);
        } else $managers = null;
		
		$_ECHO .= '
			<h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>
			
			<form method="post" class="fieldset" enctype="multipart/form-data" data-uploading=0>
				<fieldset>
					<legend>Identité</legend>
					
					<p>
						<label for="library_name">Nom :</label>
						<input type="text" name="library_name" id="library_name" value="'.(isset($l['library_name']) ? $l['library_name'] : null).'" class="long">
					</p>
					
				</fieldset>
				<fieldset>
					<legend>Coordonnées</legend>
					<p>
						<label for="library_address">Adresse :</label>
						<textarea name="library_address" id="library_address" class="medium">'.(isset($l['library_address']) ? $l["library_address"] : null).'</textarea>
						<br>
						<label for="library_postal_code">Code postal :</label>
						<input type="text" name="library_postal_code" id="library_postal_code" value="'.(isset($l['library_postal_code']) ? $l["library_postal_code"] : null).'" class="mini">
						<br>
						<label for="library_city">Ville :</label>
						<input type="text" name="library_city" id="library_city" value="'.(isset($l['library_city']) ? $l["library_city"] : null).'">
						<br>
						<label for="library_country">Pays :</label>
						<input type="text" name="library_country" id="library_country" value="'.(isset($l['library_country']) ? $l["library_country"] : null).'">
					</p>
						<label for="library_phone">T&eacute;l&eacute;phone :</label>
						<input type="tel" name="library_phone" id="library_phone" value="'.(isset($l['library_phone']) ? $l["library_phone"] : null).'" class="long">
						<br>
						<label for="library_fax">Fax :</label>
						<input type="tel" name="library_fax" id="library_fax" value="'.(isset($l['library_fax']) ? $l["library_fax"] : null).'" class="long">
					</p>
					<p>
						<label for="library_website">Site web :</label>
						<input type="url" name="library_website" id="library_website" value="'.(isset($l['library_website']) ? $l["library_website"] : null).'" placeholder="http://" pattern="https?://.+" title="L\'adresse doit commencer par http://" class="long">&nbsp;
						<img src="/common/icons/info.svg" width=16 title="Pour que le lien fonctionne correctement, n\'oubliez pas de faire précéder l\'adresse par http://" style="vertical-align: middle;">
						<br>
						<label for="library_email">Courriel :</label>
						<input type="email" name="library_email" id="library_email" value="'.(isset($l['library_email']) ? $l["library_email"] : null).'" class="long">
					</p>
					<p>
						<label for="library_facebook">Facebook :</label>
						<input type="url" name="library_facebook" id="library_facebook" value="'.(isset($b['library_facebook']) ? $b["library_facebook"] : null).'" class="long">
						<br>
						<label for="library_twitter">Twitter :</label>
						@ <input type="text" name="library_twitter" id="library_twitter" value="'.(isset($b['library_twitter']) ? $b["library_twitter"] : null).'" class="medium">
					</p>
				</fieldset>
				
				<fieldset>
					<legend>Autres informations</legend>
					<p>
						<label for="library_representative">'.(LegacyCodeHelper::getLegacyCurrentSite()['site_id'] == 16 ? 'Contact LVDI' : 'Représentant légal').' :</label>
						<input type="text" name="library_representative" id="library_representative" value="'.(isset($l['library_representative']) ? $l["library_representative"] : null).'" class="long">
					</p>
					<p>
						<label for="library_creation_year">Année de création :</label>
						<input type="number" name="library_creation_year" id="library_creation_year" value="'.(isset($l['library_creation_year']) ? $l["library_creation_year"] : null).'" class="mini" min=1850 max='.date('Y').'>
					</p>
					<p>
						<label for="library_specialities">Spécialités :</label>
						<textarea name="library_specialities" id="library_specialities" class="small">'.(isset($l['library_specialities']) ? $l["library_specialities"] : null).'</textarea>
					</p>
					<p>
						<label for="library_readings">Ils lisent :</label>
						<textarea name="library_readings" id="library_readings" class="medium">'.(isset($l['library_readings']) ? $l["library_readings"] : null).'</textarea>
					</p>
				</fieldset>
				
				<fieldset>
					<legend>Images</legend>
					'.$_IMAGES->manager('library', $l->get('id')).'
				</fieldset>
				
				<fieldset>
					<legend>Présentation</legend>
					<textarea id="library_desc" name="library_desc" class="wysiwyg">'.$l["library_desc"].'</textarea>
				</fieldset>
				
				<fieldset class="center">
					<legend>Validation</legend>
					<input type="submit" value="Enregistrer les modifications" />
				</fieldset>
				<fieldset>
					<legend>Base de donn&eacute;es</legend>
					
					<p>
						<label for="library_id"">Librairie n&deg; :</label>
						<input type="text" name="library_id" id="library_id" value="'.(isset($l['library_id']) ? $l["library_id"] : null).'" class="short" readonly>
					</p>
					<br>
					
					<p>
						<label for="library_url">URL :</label>
						<input type="text" name="library_url" id="library_url" value="'.(isset($l['library_url']) ? $l["library_url"] : null).'" disabled>
					</p>
					
					<p>
						<label for="library_name_alphabetic">Nom pour le tri :</label>
						<input type="text" name="library_name_alphabetic" id="library_name_alphabetic" value="'.(isset($l['library_name_alphabetic']) ? $l["library_name_alphabetic"] : null).'" class="long" disabled>
					</p>
					<br>
					
					<p>
						<label>Fiche cr&eacute;e le :</label>
						<input type="email" value="'.(isset($l['library_created']) ? $l["library_created"] : null).'" disabled class="long">
					</p>
					<p>
						<label>Fiche modifi&eacute;e le :</label>
						<input type="email" value="'.(isset($l['library_updated']) ? $l["library_updated"] : null).'" disabled class="long">
					</p>
				</fieldset>
			</form>
		
		'.$managers;
	}
