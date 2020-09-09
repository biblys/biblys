<?php

$_JS_CALLS[] = '/common/js/fancybox/jquery.fancybox.pack.js?v=2.0.5';
$_CSS_CALLS[] = 'screen:/common/js/fancybox/jquery.fancybox.css';

$pm = new PeopleManager();

if ($request->getMethod() == "POST") {

    $updated_fields = null;

    // Uppercase last name
    $_POST["people_last_name"] = mb_strtoupper($_POST["people_last_name"], 'UTF-8');

    // Full and alphabetic names
    $_POST["people_name"] = trim($_POST["people_first_name"].' '.$_POST["people_last_name"]);
    $_POST["people_alpha"] = trim($_POST["people_last_name"].' '.$_POST["people_first_name"]);

    // Slug
    $_POST["people_url"] = makeurl($_POST["people_name"]);

    if (empty($_POST["people_id"])) {
        $people = $pm->create(
            ['people_last_name' => $request->request->get('people_last_name')]
        );
        $_POST["people_id"] = $people->get('id');
        $params['created'] = 1;
    } else {
        $people = $pm->getById($_POST["people_id"]);
        $params['updated'] = 1;
    }

    // Photo
    $photo = new Media('people',$_POST['people_id']);

    // Upload photo
    if (!empty($_FILES["people_photo"]['tmp_name'])) {
        if ($photo->exists()) $photo->delete();
        $photo->upload($_FILES["people_photo"]['tmp_name']);
        $updated_fields = 'photo ajoutée';
    }

    // Suppression photo
    if (isset($_POST["people_photo_delete"])) {
        if ($photo->exists()) $photo->delete();
        unset($_POST["people_photo_delete"]);
        $updated_fields = 'photo supprimée';
    }

    // Verification site officiel
    if (!empty($_POST["people_site"]) && !preg_match('#^http://#',$_POST["people_site"])) {
        $_POST["people_site"] = 'http://'.$_POST["people_site"];
    }

    $fields = $request->request->all();
    foreach ($fields as $field => $val) {
        if ($field == 'people_id') {
            continue;
        }
        $people->set($field, $val);
    }
    $pm->update($people);

    // Mise a jour des articles liés
    $am = new ArticleManager();
    $articlesToUpdate = $_SQL->prepare('SELECT `article_id` FROM `roles` WHERE `people_id` = :people_id');
    $articlesToUpdate->execute(['people_id' => $people->get('id')]);
    while ($ua = $articlesToUpdate->fetch(PDO::FETCH_ASSOC)) {
        $article = $am->getById($ua['article_id']);
        if ($article) {
            $article = $am->refreshMetadata($article);
            $am->update($article);
        }
    }

    // Redirection
    redirect('/'.$_POST["people_url"].'/',$params);

}

$photo_field = null;
$photo_delete = null;
$people = $pm->getById($request->query->get('id', 0));
if ($people) {

    // Illustration
    $photo = new Media('people', $people->get('id'));
    if ($photo->exists()) {
        $photo_field = '
                <a href="'.$photo->url().'" rel="fancybox">
                    <div class="people_photo" style="background-image: url('.$photo->url('h100').')"></div>
                </a>
            ';
        $photo_delete = '<input type="checkbox" name="people_photo_delete" id="people_photo_delete" value="1"> <label for="people_photo_delete" class="after">Supprimer la photo</label>';
    } else {
        $photo_field = '<p class="center">Télécharger une image : <input type="file" id="people_photo" accept="image/jpeg" name="people_photo"></p>';
    }

    $_PAGE_TITLE = '<a href="/'.$people->get("url").'/">'.$people->get("name").'</a>';
} else {
    $_PAGE_TITLE = 'Nouveau contributeur';
    $people = new People(array());
}

$country_options = '<option><option>';
$cm = new CountryManager();
$countries = $cm->getAll();
foreach ($countries as $country) {
    $c = $country;
    if($c["country_code"] == $people->get("nation")) $c["sel"] = " selected"; else $c["sel"] = NULL;
    $country_options .= '<option value="'.$c["country_code"].'"'.$c["sel"].'>'.$c["country_name"].'</option>';
}

$g_sel['M'] = NULL; $g_sel['F'] = NULL;
$g_sel[$people->get("gender")] = ' selected';

$_ECHO .= '
        <h1><span class="fa fa-user"></span> '.$_PAGE_TITLE.'</h1>

        <form method="post" class="check fieldset" enctype="multipart/form-data">
            <fieldset>
                <legend>Informations</legend>

                <label for="people_first_name">Pr&eacute;nom :</label>
                <input type="text" name="people_first_name" id="people_first_name" value="'.$people->get("first_name").'">
                <br>
                <label for="people_last_name" class="required">Nom :</label>
                <input type="text" name="people_last_name" id="people_last_name" value="'.$people->get("last_name").'" class="required" required>
                <br><br>

                <label for="people_nation">Nationalit&eacute; :</label>
                <select name="people_nation" id="people_nation">'.$country_options.'</select>
                <br>
                <label for="people_birth">Année de naissance :</label>
                <input type="number" step=1 min=0 max='.date("Y").' name="people_birth" id="people_birth" value="'.$people->get("birth").'" class="short" maxlength="4">
                <br>
                <label for="people_birth">Année de mort :</label>
                <input type="number" step=1 min=0 max='.date("Y").' name="people_death" id="people_death" value="'.$people->get("death").'" class="short" maxlength="4">
                <br>
                <label for="people_gender">Sexe :</label>
                <select name="people_gender" id="people_gender">
                    <option></option>
                    <option value="M"'.$g_sel['M'].'>Masculin</option>
                    <option value="F"'.$g_sel['F'].'>Féminin</option>
                </select>
                <br><br>

                <label for="people_site">Site officiel :</label>
                <input type="url" name="people_site" id="people_site" value="'.$people->get("site").'" placeholder="http://" title="L\'adresse doit commencer par http://" class="verylong">
                <br>
                <label for="people_facebook">Page Facebook :</label>
                <input type="url" name="people_facebook" id="people_facebook" value="'.$people->get("facebook").'" placeholder="https://www.facebook.com/..." class="verylong">
                <br>
                <label for="people_twitter">Compte Twitter :</label>
                <input type="url" name="people_twitter" id="people_twitter" value="'.$people->get("twitter").'" placeholder="@..." pattern="@([A-Za-z0-9_]{1,15})" class="verylong">
                <br><br>
            </fieldset>
            <fieldset>
                <legend>Illustration</legend>
                '.$photo_field.'
                '.$photo_delete.'
                <p>Cette image (au format JPEG) sera utilis&eacute;e comme vignette pour les aperçus du billet sur le site ou sur les r&eacute;seaux sociaux. Taille minimale conseillée pour Facebook : 1200 x 627 pixels.</p>
            </fieldset>
            <fieldset>
                <legend>Biographie Biblys</legend>
                <textarea name="people_bio" class="wysiwyg">'.$people->get("bio").'</textarea>
                <p class="warning-mini">Les biographies entrées sur cette page sont susceptibles d\'être affichées sur d\'autres sites Biblys et modifiées par d\'autres utilisateurs. Pour une biographie propre à votre site, créez plutôt un billet bibliographique.</p>
            </fieldset>
            <fieldset>
                <legend>Enregistrement</legend>
                <div class="text-center">
                    <button class="btn btn-primary">
                        Enregistrer les modifications
                    </button>
                </div>
            </fieldset>
            <fieldset>
                <label for="people_id">Contributeur n&deg;</label>
                <input type="number" name="people_id" id="people_id" value="'.$people->get("id").'" class="mini" readonly>
                <br><br>
                <label for="people_name" class="disabled">Nom complet :</label>
                <input type="text" name="people_name" id="people_name" disabled="disabled" value="'.$people->get("name").'">
                <br>
                <label for="people_alpha" class="disabled">Nom pour le tri :</label>
                <input type="text" name="people_alpha" id="people_alpha" disabled="disabled" value="'.$people->get("alpha").'">
                <br><br>
                <label for="people_url">URL :</label>
                <input type="text" name="people_url" id="people_url" value="'.$people->get("url").'">
                <br>
                <label for="people_noosfere_id">Ref. nooSFere :</label>
                <input type="text" name="people_noosfere_id" id="people_noosfere_id" value="'.$people->get("noosfere_id").'" class="court">
                <br>
                <label for="people_pseudo">Pseudo du n&deg;&nbsp;:</label>
                <input type="text" name="people_pseudo" id="people_pseudo" value="'.$people->get("pseudo").'" class="court">
                <br><br>
                <legend>Base de donn&eacute;es</legend>
                <label for="people_insert">Date de cr&eacute;ation :</label>
                <input type="datetime" value="'.$people->get("created").'" class="datetime" disabled>
                <br>
                <label for="people_update">Date de modification :</label>
                <input type="datetime" value="'.$people->get("updated").'" class="datetime" disabled>
                <br>
            </fieldset>
        </form>
    ';

