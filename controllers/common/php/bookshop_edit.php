<?php
	
	if ($_V->isBookshop()) $_GET['id'] = $_V->getCurrentRight()->get('bookshop_id');
	elseif ($_V->isAdmin()) $mode = 'admin';
    else trigger_error('Vous n\'avez pas le droit d\'accéder à cette page.', E_USER_ERROR);
	
	$_B = new BookshopManager();
	$um = new UserManager();
	
	// Edit an existing bookshop
	if (isset($_GET['id']))
	{
		if ($b = $_B->get(array('bookshop_id' => $_GET['id'])))
		{
			$_PAGE_TITLE = 'Modifier <a href="/librairie/'.$b['bookshop_url'].'">'.$b['bookshop_name'].'</a>';
		}
		else trigger_error('Cette librairie n\'existe pas.', E_USER_ERROR);
	}
	
	// Create a new bookshop
	else
	{	
		$_PAGE_TITLE = 'Créer une nouvelle librairie';
        $b = new Bookshop(array());
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		
		$params = array();
		
		// Create new bookshop
		if (empty($_POST['bookshop_id']))
		{
			$b = $_B->create();
		}
		else
		{
			if ($b = $_B->get(array('bookshop_id' => $_POST['bookshop_id'])))
			{
				$_PAGE_TITLE = 'Modifier '.$b['bookshop_name'];
			}
			else trigger_error('Cette librairie n\'existe pas.', E_USER_ERROR);
		}
		
		foreach ($_POST as $key => $val)
		{
			$b->set($key, $val);
		}
		$_B->update($b);
		
		$params['success'] = $b->get('name').' a bien été mise à jour.';
		redirect('/librairie/'.$b->get('url'), $params);
		
	}
	else
	{
		
		// Images
		require_once biblysPath().'/inc/Image.class.php';
		$_IMAGES = new ImagesManager($_SQL);
        
        // Authorized users
        $rm = new RightManager();
        $rights = $rm->getAll(array('bookshop_id' => $b->get('bookshop_id')));
        $managers = array();
        foreach ($rights as $r)
        {
            if ($u = $um->get(array('user_id' => $r->get('user_id'))))
            {
                $managers[] .= $u->getUserName();
            }
        }
        if (!empty($managers))
        {
            $managers = '<h2>Utilisateurs autorisés'.'</h2>'.implode(', ', $managers);
        } else $managers = null;
		
		$_ECHO .= '
			<h2>'.$_PAGE_TITLE.'</h2>
			
			<form method="post" class="fieldset" enctype="multipart/form-data" data-uploading=0>
				<fieldset>
					<legend>Identité</legend>
					
					<p>
						<label for="bookshop_name">Nom :</label>
						<input type="text" name="bookshop_name" id="bookshop_name" value="'.(isset($b['bookshop_name']) ? $b['bookshop_name'] : null).'" class="long">
					</p>
					
				</fieldset>
				<fieldset>
					<legend>Coordonnées</legend>
					<p>
						<label for="bookshop_address">Adresse :</label>
						<textarea name="bookshop_address" id="bookshop_address" class="medium">'.(isset($b['bookshop_address']) ? $b["bookshop_address"] : null).'</textarea>
						<br>
						<label for="bookshop_postal_code">Code postal :</label>
						<input type="text" name="bookshop_postal_code" id="bookshop_postal_code" value="'.(isset($b['bookshop_postal_code']) ? $b["bookshop_postal_code"] : null).'" class="mini">
						<br>
						<label for="bookshop_city">Ville :</label>
						<input type="text" name="bookshop_city" id="bookshop_city" value="'.(isset($b['bookshop_city']) ? $b["bookshop_city"] : null).'">
						<br>
						<label for="bookshop_country">Pays :</label>
						<input type="text" name="bookshop_country" id="bookshop_country" value="'.(isset($b['bookshop_country']) ? $b["bookshop_country"] : null).'">
					</p>
					<p>
						<label for="bookshop_phone">T&eacute;l&eacute;phone :</label>
						<input type="tel" name="bookshop_phone" id="bookshop_phone" value="'.(isset($b['bookshop_phone']) ? $b["bookshop_phone"] : null).'" class="medium">
						<br>
						<label for="bookshop_fax">Fax :</label>
						<input type="tel" name="bookshop_fax" id="bookshop_fax" value="'.(isset($b['bookshop_fax']) ? $b["bookshop_fax"] : null).'" class="medium">
					</p>
					<p>
						<label for="bookshop_website">Site web :</label>
						<input type="url" name="bookshop_website" id="bookshop_website" value="'.(isset($b['bookshop_website']) ? $b["bookshop_website"] : null).'" placeholder="http://" pattern="https?://.+" title="L\'adresse doit commencer par http://" class="long">&nbsp;
						<img src="/common/icons/info.svg" width=16 title="Pour que le lien fonctionne correctement, n\'oubliez pas de faire précéder l\'adresse par http://" style="vertical-align: middle;">
						<br>
						<label for="bookshop_email">Courriel :</label>
						<input type="email" name="bookshop_email" id="bookshop_email" value="'.(isset($b['bookshop_email']) ? $b["bookshop_email"] : null).'" class="long">
					</p>
					<p>
						<label for="bookshop_facebook">Facebook :</label>
						<input type="url" name="bookshop_facebook" id="bookshop_facebook" value="'.(isset($b['bookshop_facebook']) ? $b["bookshop_facebook"] : null).'" class="long">
						<br>
						<label for="bookshop_twitter">Twitter :</label>
						@ <input type="text" name="bookshop_twitter" id="bookshop_twitter" value="'.(isset($b['bookshop_twitter']) ? $b["bookshop_twitter"] : null).'" class="medium">
					</p>
				</fieldset>
				
				<fieldset>
					<legend>Autres informations</legend>
					<p>
						<label for="bookshop_representative">'.($_SITE['site_id'] == 16 ? 'Contact LVDI' : 'Représentant légal').' :</label>
						<input type="text" name="bookshop_representative" id="bookshop_representative" value="'.(isset($b['bookshop_representative']) ? $b["bookshop_representative"] : null).'" class="long">
					</p>
					<p>
						<label for="bookshop_creation_year">Année de création :</label>
						<input type="number" name="bookshop_creation_year" id="bookshop_creation_year" value="'.(isset($b['bookshop_creation_year']) ? $b["bookshop_creation_year"] : null).'" class="mini" min=1850 max='.date('Y').'>
					</p>
					<p>
						<label for="bookshop_specialities">Spécialités :</label>
						<textarea name="bookshop_specialities" id="bookshop_specialities" class="small">'.(isset($b['bookshop_specialities']) ? $b["bookshop_specialities"] : null).'</textarea>
					</p>
					<p>
						<label for="bookshop_membership">Membre de :</label>
						<textarea name="bookshop_membership" id="bookshop_membership" class="small" placeholder="Associations éventuelles...">'.(isset($b['bookshop_membership']) ? $b["bookshop_membership"] : null).'</textarea>
					</p>
					<p>
						<label for="bookshop_motto">Devise :</label>
						<input type="text" name="bookshop_motto" id="bookshop_motto" value="'.(isset($b['bookshop_motto']) ? $b["bookshop_motto"] : null).'" class="long">
					</p>
				</fieldset>
				
				<fieldset>
					<legend>Images</legend>
					'.(isset($b) ? $_IMAGES->manager('bookshop', $b->get('id')) : '<p>Vous devez créer la librairie puis la modifier pour pouvoir ajouter des images.</p>').'
				</fieldset>
				
				<fieldset>
					<legend>Présentation</legend>
					<textarea id="bookshop_desc" name="bookshop_desc" class="wysiwyg">'.(isset($b['bookshop_desc']) ? $b["bookshop_desc"] : null).'</textarea>
				</fieldset>
				
				<fieldset class="center">
					<legend>Validation</legend>
					<input type="submit" value="Enregistrer les modifications" />
				</fieldset>
				<fieldset>
					<legend>Base de donn&eacute;es</legend>
					
					<p>
						<label for="bookshop_id"">Librairie n&deg; :</label>
						<input type="text" name="bookshop_id" id="bookshop_id" value="'.(isset($b['bookshop_id']) ? $b["bookshop_id"] : null).'" class="short" readonly>
					</p>
					<br>
					
					<p>
						<label for="bookshop_url">URL :</label>
						<input type="text" name="bookshop_url" id="bookshop_url" value="'.(isset($b['bookshop_url']) ? $b["bookshop_url"] : null).'" disabled>
					</p>
					
					<p>
						<label for="bookshop_name_alphabetic">Nom pour le tri :</label>
						<input type="text" name="bookshop_name_alphabetic" id="bookshop_name_alphabetic" value="'.(isset($b['bookshop_name_alphabetic']) ? $b["bookshop_name_alphabetic"] : null).'" class="long" disabled>
					</p>
					<br>
					
					<p>
						<label>Fiche cr&eacute;e le :</label>
						<input type="email" value="'.(isset($b['bookshop_created']) ? $b["bookshop_created"] : null).'" disabled class="long">
					</p>
					<p>
						<label>Fiche modifi&eacute;e le :</label>
						<input type="email" value="'.(isset($b['bookshop_updated']) ? $b["bookshop_updated"] : null).'" disabled class="long">
					</p>
				</fieldset>
			</form>
		
		'.$managers;
	}
