<?php

$pm = new PostManager();

if ($_V->isAdmin()) $rank = 'adm_';
elseif ($_V->isPublisher()) $rank = 'pub_';

$content = '';

if ($request->getMethod() === 'POST') {

    // Creation d'un nouveau billet
    $postId = $request->request->get('post_id');
    if (!$postId) {
        $post = $pm->create();
    } else {
        $update = 1;
        $post = $pm->getById($postId);
    }

    // URL de la page
    $postUrl = makeurl($request->request->get('post_title'));
    $urls = $pm->get(['post_id' => '!= '.$post->get('id'), 'post_url' => $postUrl]);
    if ($urls) {
        $postUrl .= '_'.$postId;
    }
    $post->set('post_url', $postUrl);

    // Illustration
    if (!empty($_FILES["post_illustration_upload"]["tmp_name"])) {
        media_upload("post", $post->get("id"));
    } elseif (isset($_POST["post_illustration_delete"]) && $_POST['post_illustration_delete']) {
        media_delete("post", $post->get("id"));
        unset($_POST["post_illustration_delete"]);
    }

    $fields = $request->request->all();
    foreach ($fields as $field => $val) {
        if ($field == 'post_id' || $field == 'post_url') {
            continue;
        }
        $post->set($field, $val);
    }

    // Dates
    $date = $request->request->get("post_date");
    $time = $request->request->get("post_time");
    $post->set('post_date', $date." ".$time);
    
    $pm->update($post);

    $postUrl = $urlgenerator->generate(
        "post_show",
        ["slug" => $post->get("url")]
    );
    redirect($postUrl);

    $content .= '<p class="reussite">Le billet n&deg;<a href="/pages/adm_post?id='.$post->get("id").'">'.$post->get("id").'</a> (<a href="/blog/'.$_POST["post_url"].'">'.stripslashes($_POST["post_title"]).'</a>) a &eacute;t&eacute; mis &agrave; jour.</p>';

} elseif(!empty($_POST) AND empty($_POST["title"])) {
    $p = $_POST;
    $content .= '<p class="error">Erreur : Le titre du billet est obligatoire !</p>';
}

if (!isset($_GET['id'])) $_GET['id'] = NULL;
$status_online = NULL;
$post_selected = NULL;

$post_illustration_upload = '<input type="file" id="post_illustration_upload" name="post_illustration_upload" accept="image/jpeg" />';

$postId = $request->query->get('id');
$post = $pm->getById($postId);
if ($post) {
    $p = $post;
    $_PAGE_TITLE = 'Modifier &laquo; <a href="/blog/'.$p["post_url"].'">'.$p["post_title"].'</a> &raquo';
    $content .= '
        <div class="admin">
            <p>Billet n&deg; '.$p["post_id"].'</p>
            <p><a href="/blog/'.$p["post_url"].'">voir</a></p>
            <p><a href="/pages/links?post_id='.$p["post_id"].'">lier</a></p>
            <p><a href="'.$urlgenerator->generate('post_delete', ['id' => $p['post_id']]).'" data-confirm="Voulez-vous vraiment SUPPRIMER ce billet ?">supprimer</a></p>
            <p><a href="/pages/'.$rank.'posts">billets</a></p>
        </div>
    ';
    $author = $p["user_screen_name"];
    $date = explode(" ", $p["post_date"]);
    $p["post_date"] = $date[0];
    $p["post_time"] = substr($date[1],0,5);

    // Illustration
    if(media_exists('post',$p["post_id"])) $post_illustration_upload = '<input type="file" id="post_illustration_upload" name="post_illustration_upload" accept="image/jpeg" hidden /> <label class="after button" for="post_illustration_upload">Remplacer</label> <input type="checkbox" id="post_illustration_delete" name="post_illustration_delete" value="1" /> <label for="post_illustration_delete" class="after">Supprimer</label>';

    if ($p['post_status']) $status_online = ' selected';
    if ($p['post_selected']) $post_selected = ' checked';
}
else
{
    $_PAGE_TITLE = 'Nouveau billet';

    $content .= '
        <div class="admin">
            <p>Nouveau billet</p>
            <p><a href="/pages/'.$rank.'posts">billets</a></p>
        </div>
    ';

    // Valeurs par defaut pour un nouveau billet
    $p["user_id"] = $_LOG["user_id"];
    $p["post_date"] = date("Y-m-d");
    $p["post_time"] = date("H:i");

    // Auteur
    if ($_V->isAdmin()) {
        if(!empty($_LOG["user_screen_name"])) $author = $_LOG["user_screen_name"];
        else $author = $_SITE["site_title"];
    }
    elseif ($_V->isPublisher()) {
        $pum = new PublisherManager();
        $publisherId = $_V->getCurrentRight()->get("publisher_id");
        $publisher = $pum->getById($publisherId);
        if ($publisher) {
            $author = $publisher->get("name");
        }
    }
}

$post = false;
$post_id = $request->query->get('id', false);
if ($post_id) {
    $pm = new PostManager();
    $post = $pm->getById($post_id);

    if (!$post) {
        throw new Exception("Billet n° $post_id introuvable.");
    }
}

$content .= '
    <h1><i class="fa fa-newspaper-o"></i> '.$_PAGE_TITLE.'</h1>

    <form method="post" class="check fieldset" enctype="multipart/form-data">
        <fieldset>
            <legend>Informations</legend>
            <p>
                <label for="post_author">Auteur :</label>
                <input type="text" name="post_author" id="post_author" value="'.$author.'" class="long" disabled="disabled" />
                <input type="hidden" name="user_id" id="user_id" value="'.$p["user_id"].'" />
                <input type="hidden" name="publisher_id" id="publisher_id" value="'.(isset($p['publisher_id']) ? $p['publisher_id'] : null).'">
            </p>
';
if($_V->isAdmin()) {
    $content .= '
            <p>
                <label for="category_id">Cat&eacute;gorie :</label>
                <select name="category_id">
                    <option />
    ';
    if (isset($p['category_id'])) $selected[$p['category_id']] = ' selected'; else $selected = NULL;
    $cm = new CategoryManager();
    $categories = $cm->getAll();
    foreach ($categories as $category) {
        $c = $category;
        if (isset($p['category_id']) && $p['category_id'] == $c['category_id']) $c['selected'] = ' selected'; else $c['selected'] = NULL;
        $content .= '<option value="'.$c["category_id"].'"'.$c['selected'].'>'.$c["category_name"].'</option>';
    }

    $content .= '
                </select>
                <br />
            </p>
    ';
}
$content .= '
            <p>
                <label for="post_title" class="required">Titre :</label>
                <input type="text" name="post_title" id="post_title" value="'.(isset($p['post_title']) ? htmlentities($p['post_title']) : null).'" class="long required" required />
            </p>
            <br>

            <p>
                <label for="post_status">&Eacute;tat :</label>
                <select name="post_status">
                    <option value="0">Hors-ligne</option>
                    <option value="1" '.$status_online.'>En ligne</option>
                </select>
            </p>
            <p>
                <label for="post_date" class="required">Date de parution :</label>
                <input type="date" name="post_date" id="post_date" value="'.$p["post_date"].'" placeholder="AAAA-MM-JJ" required>
                <input type="time" name="post_time" id="post_time" value="'.$p["post_time"].'" placeholder="HH:MM" required>
            </p>
';

if (auth("admin"))
{
    $content .= '
            <label for="post_link">Lien :</label>
            <input type="url" name="post_link" id="post_link" placeholder="http://" value="'.(isset($p['post_link']) ? $p['post_link'] : null).'" class="long" />
            <br /><br />

            <label for="post_selected">&Agrave; la une :</label>
            <input type="checkbox" name="post_selected" id="post_selected" value="1"'.$post_selected.' />
            <br /><br />
    ';
}

$content .= '
        </fieldset>
        <fieldset>
            <legend>Illustration</legend>
            <p>
                <label for="post_illustration">Image :</label>
                '.(isset($post_illustration_upload) ? $post_illustration_upload : null).'
            </p>
            <p>
                <label for="post_illustration_legend">L&eacute;gende :</label>
                <input type="text" name="post_illustration_legend" id="post_illustration_legend" value="'.(isset($p['post_illustration_legend']) ? $p['post_illustration_legend'] : null).'" maxlength=64 class="long" />
            </p>
            <p>Cette image (au format JPEG) sera utilis&eacute;e comme vignette pour les aperçus du billet sur le site ou sur les r&eacute;seaux sociaux. Taille minimale conseillée pour Facebook : 1200 x 627 pixels.</p>
        </fieldset>
        <fieldset>
            <legend>Contenu</legend>
            <textarea id="post_content" name="post_content" class="wysiwyg">'.(isset($p['post_content']) ? $p['post_content'] : null).'</textarea>
        </fieldset>

        <fieldset class="center">
    ';

if ($post) {
    $content .= '
    <a class="btn btn-danger" data-confirm="Voulez-vous vraiment SUPPRIMER ce billet ?"
        href="'.$urlgenerator->generate('post_delete', [ 'id' => $post->get('id') ]).'">
        <span class="fa fa-trash-o"></span>
        Supprimer le billet
    </a>
    ';
}

$content .= '
        <button class="btn btn-primary" type="submit">
            <span class="fa fa-save"></span>
            Enregistrer le billet
        </button>
    </fieldset>

    <fieldset>
        <legend>Base de donn&eacute;es</legend>

        <p>
            <label for="post_id" class="disabled">Billet n&deg;</label>
            <input type="text" name="post_id" id="post_id" value="'.(isset($p['post_id']) ? $p['post_id'] : null).'" readonly>
        </p>

        <p>
            <label for="post_url">Adresse du billet :</label>
            <input type="hidden" name="post_url_old" value='.(isset($p['post_url']) ? $p['post_url'] : null).'>
            <input type="text" name="post_url" id="post_url" value="'.(isset($p['post_url']) ? $p['post_url'] : null).'" placeholder="Champ rempli automatiquement" class="long" />
        </p>
        <br>

        <p>
            <label for="post_insert" class="readonly">Billet cr&eacute;&eacute; le :</label>
            <input type="text" name="post_insert" id="post_insert" value="'.(isset($p['post_insert']) ? $p['post_insert'] : null).'" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" disabled>
        </p>
        <p>
            <label for="post_update" class="readonly">Billet modifi&eacute; le :</label>
            <input type="text" name="post_update" id="post_update" value="'.(isset($p['post_update']) ? $p['post_update'] : null).'" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" disabled>
        </p>
    </fieldset>
</form>
';

return new Symfony\Component\HttpFoundation\Response($content);
