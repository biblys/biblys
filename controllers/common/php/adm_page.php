<?php

use Biblys\Legacy\LegacyCodeHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

/** @var UrlGenerator $urlGenerator */

$pm = new PageManager();

/** @var Request $request */
$pageId = $request->query->get('id');
if ($pageId) {
    $page = $pm->getById($pageId);
}

$content = '<div class="admin"><p><a href="/pages/adm_pages">Gestion des pages</a></p></div>';

$delPageId = $request->query->get('del');
if ($delPageId) {
    $delPage = $pm->getById($delPageId);
    $pm->delete($delPage);
    $content .= '<p class="success">La page n°'.$delPage->get('id').' a bien été supprimée.</p>';
} elseif($request->getMethod() === "POST") {

    // Création d'une nouvelle page
    if (!$pageId || !$page) {
        $page = $pm->create(["page_title" => $request->request->get("page_title")]);
    }

    $page->set("page_title", $request->request->get("page_title"))
        ->set("page_url", $request->request->get("page_url"))
        ->set('page_content', $request->request->get('page_content'))
        ->set('page_status', $request->request->get('page_status'));
    $page = $pm->update($page);

    $staticPageUrl = $urlGenerator->generate("static_page_show", ["slug" => $page->get("url")]);
    return new RedirectResponse($staticPageUrl);
}

if ($pageId && $page) {
    $p = $page;
    $staticPageUrl = $urlGenerator->generate("static_page_show", ["slug" => $page->get("url")]);
    $pageTitle = "Modifier &laquo; <a href=\"$staticPageUrl\">{$p["page_title"]}</a> &raquo;";
    $page_status[$p["page_status"]] = 'selected = "selected"';
} else {
    $pageTitle = 'Nouvelle page statique';
    $p = array();
    
    $p["axys_user_id"] = LegacyCodeHelper::getGlobalVisitor()->get("id");
    $p["page_date"] = date("Y-m-d H:i:s");
}

$request->attributes->set("page_title", $pageTitle);

$content .= '
    <h1><i class="fa fa-file-o"></i> '.$pageTitle.'</h1>
    <form method="post" class="check fieldset">
        <fieldset>
            <legend>Informations</legend>
            <p>
                <label for="page_title" class="required">Titre :</label>
                <input type="text" name="page_title" id="page_title" value="'.($p["page_title"] ?? null).'" class="long required" required>
            </p>
            <p>
                <label for="page_status">État :</label>
                <select name="page_status">
                    <option value="0"'.(isset($p["page_status"]) && $p["page_status"] == 0 ? ' selected' : null).'>Hors-ligne</option>
                    <option value="1"'.(isset($p["page_status"]) && $p["page_status"] == 1 ? ' selected' : null).'>En ligne</option>
                </select>
            </p>
        </fieldset>
        <fieldset>
            <legend>Contenu</legend>

            <textarea id="page_content" name="page_content" class="wysiwyg">'.($p["page_content"] ?? null).'</textarea>
            <br />
        </fieldset>
        <fieldset>
            <legend>Validation</legend>
            <div class="center"><button type="submit">Enregistrer la page</button></div>
        </fieldset>
        <fieldset>
            <legend>Base de données</legend>
            <p>
                <label for="page_ref" class="disabled">Page n&deg;</label>
                <input type="text" name="page_ref" id="page_ref" value="'.($p["page_id"] ?? null).'" class="short" disabled="disabled">
                <input type="hidden" name="page_id" id="page_id" value="'.($p["page_id"] ?? null).'">
            </p>
            <p>
                <label for="page_url">Adresse de la page :</label>
                <input type="text" name="page_url" id="page_url" value="'.($p["page_url"] ?? null).'" />
            </p>
        </fieldset>
    </form>
';

return new Response($content);
