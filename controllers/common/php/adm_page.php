<?php

$pm = new PageManager();

$pageId = $request->query->get('id');
if ($pageId) {
    $page = $pm->getById($pageId);
}

$_ECHO .= '<div class="admin"><p><a href="/pages/adm_pages">Gestion des pages</a></p></div>';

$delPageId = $request->query->get('del');
if ($delPageId) {
    $delPage = $pm->getById($delPageId);
    $pm->delete($delPage);
    $_ECHO .= '<p class="success">La page n°'.$delPage->get('id').' a bien &eacute;t&eacute; supprim&eacute;e.</p>';
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

    redirect("/pages/".$page->get("url"));
}

if ($pageId && $page) {
    $p = $page;
    $_PAGE_TITLE = 'Modifier &laquo; <a href="/pages/'.$p['page_url'].'">'.$p["page_title"].'</a> &raquo;';
    $page_status[$p["page_status"]] = 'selected = "selected"';
} else {
    $_PAGE_TITLE = 'Nouvelle page statique';
    $p = array();
    $p["user_id"] = $_LOG["user_id"];
    $p["page_date"] = date("Y-m-d H:i:s");
}

$_ECHO .= '
    <h1><i class="fa fa-file-o"></i> '.$_PAGE_TITLE.'</h1>
    <form method="post" class="check fieldset">
        <fieldset>
            <legend>Informations</legend>
            <p>
                <label for="page_title" class="required">Titre :</label>
                <input type="text" name="page_title" id="page_title" value="'.(isset($p["page_title"]) ? $p["page_title"] : null).'" class="long required" required>
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

            <textarea id="page_content" name="page_content" class="wysiwyg">'.(isset($p["page_content"]) ? $p["page_content"] : null).'</textarea>
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
                <input type="text" name="page_ref" id="page_ref" value="'.(isset($p["page_id"]) ? $p["page_id"] : null).'" class="short" disabled="disabled">
                <input type="hidden" name="page_id" id="page_id" value="'.(isset($p["page_id"]) ? $p["page_id"] : null).'">
            </p>
            <p>
                <label for="page_url">Adresse de la page :</label>
                <input type="text" name="page_url" id="page_url" value="'.(isset($p["page_url"]) ? $p["page_url"] : null).'" />
            </p>
        </fieldset>
    </form>
';
