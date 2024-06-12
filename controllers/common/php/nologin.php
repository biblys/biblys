<?php

	use Symfony\Component\HttpFoundation\JsonResponse;

	$_PAGE_TITLE = 'Identification requise';
	$_ECHO = '
		<h2>Identification requise</h2>
		<p>Pour acc&eacute;der &agrave; cette page, vous devez vous <a href="'.$axys->getLoginUrl().'">connecter</a> ou <a href="'.$axys->getSignupUrl().'">cr&eacute;er un compte Axys</a>.</p>
	';

	if ($request->isXmlHttpRequest()) {
		$response = new JsonResponse();
		$response->setData(array('error' => 'Identification requise : <a href="'.$axys->getLoginUrl().'">connectez-vous</a> ou <a href="'.$axys->getSignupUrl().'">créez un compte Axys</a> pour continuer.'));
		$response->send();
	}
