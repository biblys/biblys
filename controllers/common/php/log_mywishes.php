<?php

	$wlm = new WishlistManager();
	$wm = new WishManager();
	$am = new ArticleManager();

	// Get or create current wishlist
	$wishlist = $wlm->get(array('user_id' => $_V->get('id'), 'wishlist_current' => 1));
	if (!$wishlist)
	{

		// Create a current wishlist for current user
		$wishlist = $wlm->create(array(
			'user_id' => $_V->get('id'),
			'wishlist_current' => 1
		));

		// Add wishes
		$wishes = $wm->getAll(array('user_id' => $_V->get('id'), 'wishlist_id' => 'NULL'));
		foreach ($wishes as $wish)
		{
			$wish->set('wishlist', $wishlist);
			$wm->update($wish);
		}
	}

	if (isset($_POST['article_id']))
	{

		$article = $am->getById($_POST['article_id']);
		if (!$article) {
			trigger_error('Article #'.$_POST['article_id'].' introuvable.');
		}

		$wish = $wm->get(array('article_id' => $article->get('id'), 'wishlist_id' => $wishlist->get('id')));

		// If wish exists, delete it
		if ($wish) {
			$wm->delete($wish);
			$p['deleted'] = 1;
			$p['message'] = "&laquo;&nbsp;".$article->get('title')."&nbsp;&raquo a bien été retiré de votre <a href='/pages/log_mywishes'>liste d'envies</a>.";
		}

		// Else create it
		else {
			$wish = $wm->create();
			$wish->set('user_id', $_V->get('id'));
			$wish->set('article', $article);
			$wish->set('wishlist', $wishlist);
			$wm->update($wish);
			$p['created'] = 1;
			$p['message'] = "&laquo;&nbsp;".$article->get('title')."&nbsp;&raquo a bien été ajouté à votre <a href='/pages/log_mywishes'>liste d'envies</a>.";
		}

		if ($request->isXmlHttpRequest()) {
            die(json_encode($p));
		}
        redirect('/pages/log_wishes', $p);
	}

	// Show wish list
	else
	{

		$_PAGE_TITLE = $wishlist->get('name');

	    $messages = null;
	    foreach ($session->getFlashBag()->get('success') as $message) {
	        $messages .= '<p class="alert alert-success">'.$message.'</p>';
	    }

		// Is user name set ?
		if (!$_V->has('screen_name')) {
			$share = '<p class="alert alert-warning"><i class="fa fa-info-circle"></i> Pour pouvoir partager votre liste d\'envies, commencez par <a href="'.$axys->getLoginUrl().'">choisir un nom d\'utilisateur</a>.</p>';
		}

		// Is wishlist public ?
		elseif (!$wishlist->has('public')) {
			$share = '<p class="alert alert-warning">Cette liste d\'envies n\'est pas publique. Cliquez sur <strong>Modifier</strong> pour changer sa visibilité.</p>';
		}

		// Show wishlist url & share buttons
		else {
			$url = 'http://'.$_SITE['site_domain'].'/wishlist/'.$_V->get('slug');
			$share = '
			<br>

			<p class="center">Adresse publique de votre liste :<br><a href="/wishlist/'.$_V->get('slug').'">'.$url.'</a></p>

			<div class="text-center">
				'.share_buttons($url, $wishlist->get('name')).'
			</div>

			<p class="center">
				Partagez cette adresse pour vous faire offrir des livres !
			</p>
			';
		}


		$_ECHO .= '

			<div class="pull-right">
				<button class="btn btn-success" data-toggle="share">
					<i class="fa fa-share-square-o"></i> Partager
				</button>

				<a href="/pages/log_wishlist?id='.$wishlist->get('id').'" class="btn btn-info">
					<i class="fa fa-cog"></i> Modifier
				</a>
			</div>

			<h2>'.$_PAGE_TITLE.'</h2>

			'.$messages.'

			<div id="share" class="hidden">
				'.$share.'
			</div>
		';

		$wishes = $_V->getWishes();

		if (!count($wishes))
		{
			$_ECHO .= '
				<p class="center">Votre liste d\'envies est vide !</p>
				<p class="center">Rendez-vous sur la fiche d\'un livre<br>qui vous intéresse et cliquez sur :<br></p>
				<p class="center">
					<a class="button">
						<img src="/common/icons/wish.svg" width="14" alt="Ajouter à vos envies">&nbsp; Ajouter à mes envies
					</a>
				</p>
				<p class="center">
					Vous pourrez ensuite retrouver votre liste<br>
					sur tous les sites <a href="http://www.biblys.fr/">Biblys</a> et la partager<br>
					pour vous faire offrir des livres !
				</p>
			';
		}
		else
		{
			$_ECHO .= '
				<div class="center">

				</div><br>
			';
			foreach ($wishes as $w)
			{

				if (!isset($_REQ)) $_REQ = '(';
				else $_REQ .= ' OR';
				$_REQ .= ' `articles`.`article_id` = '.$w['article_id'];
			}
			$_REQ .= ')';
			if ($_SITE['site_publisher']) $_REQ .= ' AND `articles`.`publisher_id` = '.$_SITE['publisher_id'];
			require_once '_list.php';
		}


	}
