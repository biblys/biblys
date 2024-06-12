<?php

	$photo = null; $logo = null; $alert = null;

	$_B = new LibraryManager();
	
	require_once BIBLYS_PATH.'/inc/Image.class.php';
	$_I = new ImagesManager($_SQL);
	
	if (isset($_GET['url']) && $b = $_B->get(array('library_url' => $_GET['url'])))
	{
		$_PAGE_TITLE = $b['library_name'];
		
		if (auth('admin'))
		{
			$_ECHO .= '
				<div class="admin">
					<p>Librairie n&deg; '.$b->get('id').'</p>
					<a href="/pages/library_edit?id='.$b->get('id').'">modifier</a></p>
				</div>
			';
		}
		
		// Images
		if ($images = $_I->get(array('library_id' => $b->get('library_id'))))
		{
			foreach ($images as $i)
			{
				if ($i->get('nature') == 'photo') $photo = '<img src="'.$i->getURL().'" class="library-photo" alt="'.$i->get('legend').'">';
				elseif ($i->get('nature') == 'logo') $logo = '<img src="'.$i->getURL().'" class="library-logo" alt="'.$i->get('legend').'">';
			}
		}
		
		// Coordinates
		$coords = null;
		if (isset($logo)) $coords = '<tr><td colspan=2 class="center">'.$logo.'</span></tr>';
		if ($b->has('library_address')) $coords .= '<tr><td class="label">Adresse :</td><td>'.$b->get('address').'<br>'.$b->get('postal_code').' '.$b->get('city').'</td></tr>';
		if ($b->has('library_phone')) $coords .= '<tr><td class="label">Téléphone :</td><td>'.$b->get('phone').'</td></tr>';
		if ($b->has('library_representative')) $coords .= '<tr><td class="label">Contact :</td><td>'.$b->get('representative').'</td></tr>';
		if ($b->has('library_specialities')) $coords .= '<tr><td class="label">Spécialités :</td><td>'.$b->get('specialities').'</td></tr>';
		if ($b->has('library_membership')) $coords .= '<tr><td class="label">Membre de :</td><td>'.$b->get('membership').'</td></tr>';
		if ($b->has('library_motto')) $coords .= '<tr><td class="label">Devise :</td><td>'.$b->get('motto').'</td></tr>';
		if ($b->has('library_website')) $links[] = '<a href="'.$b->get('website').'">Site web</a> ';
		if ($b->has('library_email')) $links[] = '<a href="/contact/?library_id='.$b->get('id').'">Courriel</a> ';
		if ($b->has('library_facebook')) $links[] = '<a href="'.$b->get('facebook').'">Facebook</a> ';
		if ($b->has('library_twitter')) $links[] = '<a href="'.$b->get('twitter').'">Twitter</a> ';
		if (isset($links)) $coords .= '<tr><td class="center" colspan=2>'.implode(' | ',$links).'</td></tr>';
		if (isset($coords)) $coords = '<table class="library-details">'.$coords.'</table>';
		
		// Ils lisent
		$readings = null;
		if ($b->has('library_readings'))
		{
			$readings = explode("\n", $b->get('readings'));
			$readings = '<ul><li>'.implode('</li><li>', $readings).'</li></ul>';
			$readings = '<h2>Ils lisent, dans le cadre de La Voie des Indés</h2>'.$readings;
		}
		
		if (isset($_GET['success'])) $alert = '<p class="success">'.$_GET['success'].'</p>';
		
		$_ECHO .= '
			'.$alert.'
			<h1>'.$_PAGE_TITLE.'</h1>
			
            <div class="row">
                <div class="col-md-6">'.$b->get('desc').'</div>
                <div class="col-md-6">'.$coords.'</div>
            </div>
			
			'.$readings.'
			
			'.$photo.'
		';
		
	}
	else $_ECHO .= e404();