<?php

	$photo = null; $logo = null; $alert = null; $links = array();

	$_B = new BookshopManager();
	
	require_once biblysPath().'/inc/Image.class.php';
	$_I = new ImagesManager($_SQL);
	
	if (isset($_GET['url']) && $b = $_B->get(array('bookshop_url' => $_GET['url'])))
	{
		\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle($b['bookshop_name']);
		
		if (auth('admin'))
		{
			$_ECHO .= '
				<div class="admin">
					<p>Librairie n&deg; '.$b->get('id').'</p>
					<a href="/pages/bookshop_edit?id='.$b->get('id').'">modifier</a></p>
				</div>
			';
		}
		
		// Images
		if ($images = $_I->get(array('bookshop_id' => $b->get('bookshop_id'))))
		{
			foreach ($images as $i)
			{
				if ($i->get('nature') == 'photo') $photo = '<img src="'.$i->getURL().'" class="bookshop-photo" alt="'.$i->get('legend').'">';
				elseif ($i->get('nature') == 'logo') $logo = '<img src="'.$i->getURL().'" class="bookshop-logo" alt="'.$i->get('legend').'">';
			}
		}
		
		// Details
		$coords = null;
		if (isset($logo)) $coords = '<tr><td colspan=2 class="center">'.$logo.'</span></tr>';
		if ($b->has('bookshop_address')) $coords .= '<tr><td class="label">Adresse :</td><td>'.$b->get('address').'<br>'.$b->get('postal_code').' '.$b->get('city').'</td></tr>';
		if ($b->has('bookshop_phone')) $coords .= '<tr><td class="label">Téléphone :</td><td>'.$b->get('phone').'</td></tr>';
		if ($b->has('bookshop_representative')) $coords .= '<tr><td class="label">Contact :</td><td>'.$b->get('representative').'</td></tr>';
		if ($b->has('bookshop_specialities')) $coords .= '<tr><td class="label">Spécialités :</td><td>'.$b->get('specialities').'</td></tr>';
		if ($b->has('bookshop_membership')) $coords .= '<tr><td class="label">Membre de :</td><td>'.$b->get('membership').'</td></tr>';
		if ($b->has('bookshop_motto')) $coords .= '<tr><td class="label">Devise :</td><td>'.$b->get('motto').'</td></tr>';
		
		if ($b->has('bookshop_website')) $links[] = '<a href="'.$b->get('website').'">Site web</a> ';
		if ($b->has('bookshop_email')) $links[] = '<a href="/contact/?bookshop_id='.$b->get('id').'">Courriel</a> ';
		if ($b->has('bookshop_facebook')) $links[] = '<a href="'.$b->get('facebook').'">Facebook</a> ';
		if ($b->has('bookshop_twitter')) $links[] = '<a href="'.$b->get('twitter').'">Twitter</a> ';
		if (isset($links)) $coords .= '<tr><td class="center" colspan=2>'.implode(' | ',$links).'</td></tr>';
		
		if (isset($coords)) $coords = '<table class="bookshop-details">'.$coords.'</table>';
		
		if (isset($_GET['success'])) $alert = '<p class="success">'.$_GET['success'].'</p>';
		
		$_ECHO .= '
			'.$alert.'
			<h1>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>
			
			<div class="biblys-col-6">'.$b->get('desc').'</div><div class="biblys-col-6">'.$coords.'</div>
			
			'.$photo.'
		';
		
	}
	else $_ECHO .= e404();