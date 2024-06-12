<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

$error_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$parse_url = parse_url($error_url);
$redirection_old = $parse_url["path"];
if (!empty($parse_url["query"])) {
    $redirection_old .= '?'.$parse_url["query"];
}

$_SITE["site_id"] = 1;

$redirections = $_SQL->prepare(
    "SELECT `redirection_id`, `redirection_new`
    FROM `redirections` WHERE (`redirection_old` = :redirection_old) 
        AND `redirection_old` != `redirection_new`
        AND (`site_id` = :site_id OR `site_id` IS NULL) LIMIT 1"
);
$redirections->execute(
    [
        'redirection_old' => $redirection_old,
        'site_id' => $site->get('id')
    ]
);
if ($r = $redirections->fetch(PDO::FETCH_ASSOC)) {
    $response = new RedirectResponse($r['redirection_new']);
    $response->send();
    die();
} else {
    $response = new Response();
    $response->setStatusCode(404);

    $_PAGE_TITLE = 'Erreur 404';
    $_ECHO = '
        <h2>Erreur 404</h2>
        <p>Cette page  n\'existe pas !</p>
    ';

    $error_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    if ($_V->isRoot()) {
        $_ECHO .= '

            '.(isset($debug) ? '<p>Debug info: '.$debug.'</p>' : null).'

            <p>Page : '.$redirection_old.'</p>

            <h3>Créer une redirection</h3>
            <form method="post" action="http://www.biblys.fr/pages/adm_404">
                <fieldset>
                    <input type="hidden" name="old_url" value="'.$redirection_old.'">
                    <input type="text" name="new_url" class="long" autofocus> &nbsp; <input type="text" name="site_id" class="nano" placeholder="Site"> &nbsp; <button type="submit">Continuer &#187;</button>
                </fiedlset>
            </form>
        ';
    }
}
