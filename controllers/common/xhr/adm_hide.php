<?php

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		$am = new ArticleManager();
		$lm = new LinkManager();
		
		$article = $am->getById($_POST['article_id']);
		if (!$article) {
			trigger_error('Article '.$_POST['article_id'].' inconnu.');
		}
		
		$link = $lm->get(array('article_id' => $_POST['article_id'], 'site_id' => getLegacyCurrentSite()['site_id'], 'link_hide' => 1));
		
		// Article already hidden ?
		if ($link) {
			$lm->delete($link);
			$result = 'show';
			$message = "&laquo;&nbsp;".$article->get('title')."&nbsp;&raquo; sera à nouveau affiché.";
		}
		
		// If not, hide it
		else {
			$lm->create(array(
				'article_id' => $article->get('id'),
				'site_id' => getLegacyCurrentSite()['site_id'],
				'link_hide' => 1
			));
			$result = 'hide';
			$message = "&laquo;&nbsp;".$article->get('title')."&nbsp;&raquo; ne sera plus affiché.";
		}
		
		// Refresh article metadata
		$article = $am->refreshMetadata($article);
		$am->update($article);
		
		echo json_encode(array(
			'result' => $result,
			'message' => $message
		));
	}
