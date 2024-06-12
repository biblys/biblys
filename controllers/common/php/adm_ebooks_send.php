<?php

$am = new ArticleManager();
$um = new UserManager();

$_PAGE_TITLE = 'Envoyer des livres numériques';

$result = null;

if ($request->getMethod() == "POST") {
    function extract_email_address ($string) {
        $emails = array();
        $string = str_replace("\r\n",' ',$string);
        $string = str_replace("\n",' ',$string);

        foreach(preg_split('/ /', $string) as $token) {
            $email = filter_var($token, FILTER_VALIDATE_EMAIL);
            if ($email !== false) {
                $emails[] = $email;
            }
        }
        return $emails;
    }

    // Predownload option
    $predownload = $request->request->get('predownload', false);
    if ($predownload) {
        $predownload = true;
    }

    // Send e-mail option
    $send_email = $request->request->get('send_email', false);
    if ($send_email) {
        $send_email = true;
    }

    // Create user option
    $create_user = $request->request->get('create_user', false);

    // Get the article object
    $articles = $am->getByIds($_POST['articles']);

    // Get an array of emails in the posted string
    $emails = extract_email_address($_POST['emails']);

    // For each email in the array
    foreach($emails as $email) {
        $erreur = NULL;
        if(!empty($email)) {

            // Check if there is already an user with this address
            $user = $um->get(array('user_email' => $email));

            // Else, create a new one
            if (!$user && $create_user) {
                $user = $um->create(array('user_email' => $email));
                $result .= '<p class="success">Compte Axys créé pour '.$email.'.</p>';
            }

            if (!$user) {
              $result .= '<p class="warning">Adresse inconnue '.$email.' (ignorée).</p>';
              continue;
            }

            // At last, add the articles to user's library
            $um->addToLibrary($user, $articles, array(), $predownload, ['send_email' => $send_email]);
            $result .= '<p class="success">'.count($articles).' livre'.s(count($articles)).' ajouté à la bibliothèque de '.$email.'.</p>';
        }
    }
}

$downloadable_types = Biblys\Article\Type::getAllDownloadableTypes();
$types = array_map(function($type) {
  return $type->getId();
}, $downloadable_types);

$articles = $am->getAll(['type_id' => $types], ['order' => 'article_title']);
$articles_options = array_map(function($article) {
  return '<option value="'.$article->get('id').'">'.$article->get('title').'</option>';
}, $articles);

$_ECHO .= '
    <h1><span class="fa fa-send"></span> '.$_PAGE_TITLE.'</h1>

    '.$result.'

    <form method="post" class="check">
        <fieldset>

            <p>
                <label for="emails" class="after">Adresse e-mail du ou des destinataires :</label>
                <textarea name="emails" id="emails" class="form-control" rows="5" required data-toggle="popover" data-trigger="focus" data-placement="bottom" data-content="Si les adresses ne correspondent pas déjà à un compte Axys, ils seront créés automatiquement avec un mot de passe aléatoire envoyés par e-mail. Entrer une ou plusieurs adresses e-mail, séparées par un espace.">'.(isset($_GET['emails']) ? urldecode($_GET["emails"]) : null).'</textarea>
            </p>

            <p>
                <label for="article_id" class="after">Articles à ajouter :</label>
                <select name="articles[]" class="form-control" id="articles" multiple required style="height: 250px;" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="Sélectionner le ou les articles à ajouter à la bibliothèque des destinataires. Sélectionnez plusieurs articles avec la touche Ctrl (Windows) ou Cmd (Mac).">
                    '.join($articles_options).'
                </select>
            </p>

            <p>
                <input type="checkbox" name="predownload" id="predownload"> &nbsp; <label for="predownload" class="after">Autoriser le téléchargement des articles avant leur date de parution.</label><br>
                <input type="checkbox" name="send_email" id="send_email" checked> &nbsp; <label for="send_email" class="after">Prévenir le destinataire par courriel de l\'ajout des articles à sa bibliothèque.</label><br>
                <input type="checkbox" name="create_user" id="create_user" checked> &nbsp; <label for="create_user" class="after">Créer les comptes utilisateurs si nécessaires (sinon, les adresses inconnues seront ignorées).</label>
            </p>

            <p class="center">
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </p>

        </fieldset>
    </form>
';
