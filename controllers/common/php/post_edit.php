<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUnhandledExceptionInspection */

use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\Slug\SlugService;
use Model\PostQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @throws PropelException
 */
return function (
    Request $request,
    CurrentUser $currentUser,
    UrlGenerator $urlGenerator,
    ImagesService $imagesService,
): Response|RedirectResponse
{
    $currentUser->authPublisher();

    $pm = new PostManager();
    $content = '';

    if ($request->getMethod() === 'POST') {

        // Creation d'un nouveau billet
        $postId = $request->request->get('post_id');
        if (!$postId) {
            /** @var @Post $post */
            $postEntity = $pm->create();
        } else {
            /** @var @Post $post */
            $postEntity = $pm->getById($postId);
        }
        $post = PostQuery::create()->findPk($postEntity->get('id'));

        // URL de la page
        $slugService = new SlugService();
        $postUrl = $slugService->slugify($request->request->get('post_title'));
        $urls = $pm->get(['post_id' => '!= ' . $postEntity->get('id'), 'post_url' => $postUrl]);
        if ($urls) {
            $postUrl .= '_' . $postId;
        }
        $postEntity->set('post_url', $postUrl);

        // Illustration
        if (!empty($_FILES["post_illustration_upload"]["tmp_name"])) {
            $imagesService->addImageFor($post, $_FILES["post_illustration_upload"]["tmp_name"]);
        } elseif (isset($_POST["post_illustration_delete"]) && $_POST['post_illustration_delete']) {
            $imagesService->deleteImageFor($post);
        }

        $fields = $request->request->all();
        foreach ($fields as $field => $val) {
            if (in_array($field, [
                "post_id",
                "post_url",
                "post_url_old",
                "post_time",
                "post_illustration_delete",
            ])) {
                continue;
            }
            $postEntity->set($field, $val);
        }

        // Dates
        $date = $request->request->get("post_date");
        $time = $request->request->get("post_time");
        $postEntity->set('post_date', $date . " " . $time);

        // Selected checkbox
        $selected = $request->request->get("post_selected") ? 1 : 0;
        $postEntity->set('post_selected', $selected);

        $postEntity->remove("author_id");

        $pm->update($postEntity);

        $postUrl = $urlGenerator->generate(
            "post_show",
            ["slug" => $postEntity->get("url")]
        );
        return new RedirectResponse($postUrl);

    } elseif (!empty($_POST) and empty($_POST["title"])) {
        $p = $_POST;
        $content .= '<p class="error">Erreur : Le titre du billet est obligatoire !</p>';
    }

    if (!isset($_GET['id'])) $_GET['id'] = NULL;
    $status_online = NULL;
    $post_selected = NULL;

    $postId = $request->query->get('id');
    $postEntity = $pm->getById($postId);
    $author = null;

    $pageTitle = 'Nouveau billet';

    $request = Request::createFromGlobals();
    $config = Config::load();
    $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

    // Valeurs par défaut pour un nouveau billet
    $p["user_id"] = $currentUser->getUser()->getId();
    $p["post_date"] = date("Y-m-d");
    $p["post_time"] = date("H:i");

    // Auteur
    if ($currentUser->isAdmin()) {
        $author = $currentUser->getUser()->getEmail();
    } elseif ($currentUser->hasPublisherRight()) {
        $pum = new PublisherManager();
        $publisherId = $currentUser->getCurrentRight()->getPublisherId();
        $publisher = $pum->getById($publisherId);
        if ($publisher) {
            $author = $publisher->get("name");
        }
    }

    $existingPostIllustration = '';
    if ($postEntity) {
        $post = PostQuery::create()->findPk($postEntity->get('id'));
        $p = $postEntity;
        $pageTitle = 'Modifier « <a href="/blog/' . $p["post_url"] . '">' . $p["post_title"] . '</a> »';
        $content .= '
            <div class="admin">
                <p>Billet n° ' . $p["post_id"] . '</p>
                <p><a href="/blog/' . $p["post_url"] . '">voir</a></p>
                <p><a href="/pages/links?post_id=' . $p["post_id"] . '">lier</a></p>
                <p><a href="' . $urlGenerator->generate("post_delete", ["id" => $p["post_id"]]) . '" data-confirm="Voulez-vous vraiment SUPPRIMER ce billet ?">supprimer</a></p>
                <p><a href="' . $urlGenerator->generate("posts_admin") . '">billets</a></p>
            </div>
        ';
        $author = $currentUser->getUser()->getEmail();
        $date = explode(" ", $p["post_date"]);
        $p["post_date"] = $date[0];
        $p["post_time"] = substr($date[1], 0, 5);

        // Illustration
        if ($imagesService->imageExistsFor($post)) {
            $existingPostIllustration = '
                <div class="text-center">
                    <img src="'.$imagesService->getImageUrlFor($post, height: 300).'" height="300" alt="">
                    <br />
                    <input type="checkbox" value=1 name="post_illustration_delete" id="illustration_delete" />
                    <label for="illustration_delete">Supprimer</label>
                </div>
            ';
        }

        if ($p['post_status']) $status_online = ' selected';
        if ($p['post_selected']) $post_selected = ' checked';
    }

    $postEntity = false;
    $post_id = $request->query->get('id', false);
    if ($post_id) {
        $pm = new PostManager();
        $postEntity = $pm->getById($post_id);

        if (!$postEntity) {
            throw new Exception("Billet n° $post_id introuvable.");
        }
    }

    $content .= '
        <h1><i class="fa fa-newspaper"></i> ' . $pageTitle . '</h1>
    
        <form method="post" class="check fieldset" enctype="multipart/form-data">
            <fieldset>
                <legend>Informations</legend>
                <p>
                    <label class="floating" for="post_author">Auteur :</label>
                    <input type="text" name="post_author" id="post_author" value="' . $author . '" class="long" disabled="disabled" />
                    <input type="hidden" name="user_id" id="user_id" value="' . $p["user_id"] . '" />
                    <input type="hidden" name="publisher_id" id="publisher_id" value="' . ($p['publisher_id'] ?? null) . '">
                </p>
    ';
    if ($currentUser->isAdmin()) {
        $content .= '
            <p>
                <label class="floating" for="category_id">Catégorie :</label>
                <select name="category_id">
                    <option />
    ';
        $cm = new CategoryManager();
        $categories = $cm->getAll();
        foreach ($categories as $category) {
            $c = $category;
            if (isset($p['category_id']) && $p['category_id'] == $c['category_id']) $c['selected'] = ' selected'; else $c['selected'] = NULL;
            $content .= '<option value="' . $c["category_id"] . '"' . $c['selected'] . '>' . $c["category_name"] . '</option>';
        }

        $content .= '
                </select>
                <br />
            </p>
    ';
    }
    $content .= '
            <p>
                <label class="floating" for="post_title" class="required">Titre :</label>
                <input type="text" name="post_title" id="post_title" value="' . (isset($p['post_title']) ? htmlentities($p['post_title']) : null) . '" class="long required" required />
            </p>
            <br>

            <p>
                <label class="floating" for="post_status">État :</label>
                <select name="post_status">
                    <option value="0">Hors-ligne</option>
                    <option value="1" ' . $status_online . '>En ligne</option>
                </select>
            </p>
            <p>
                <label class="floating" for="post_date" class="required">Date de parution :</label>
                <input type="date" name="post_date" id="post_date" value="' . $p["post_date"] . '" placeholder="AAAA-MM-JJ" required>
                <input type="time" name="post_time" id="post_time" value="' . $p["post_time"] . '" placeholder="HH:MM" required>
            </p>
    ';

    if ($currentUser->isAdmin()) {
        $content .= '
            <label class="floating" for="post_link">Lien :</label>
            <input 
                type="url" 
                name="post_link" 
                id="post_link" 
                placeholder="https://" 
                value="' . ($p['post_link'] ?? null) . '" 
                class="long" 
            />
            <br /><br />

            <label class="floating" for="post_selected">À la une :</label>
            <input type="checkbox" name="post_selected" id="post_selected" value="1"' . $post_selected . ' />
            <br /><br />
      ';
    }

    $content .= '
        </fieldset>
        <fieldset>
            <legend>Illustration</legend>
              <div class="form-group">
                '.$existingPostIllustration.'
              
                <label for="post_illustration_upload">Image</label>
                <input type="file" id="post_illustration_upload" name="post_illustration_upload" accept="image/jpeg, image/png, image/webp">
                <p class="help-block">Image au format JPEG, PNG ou WebP affichée en prévisualisation sur les réseaux sociaux.</p>
              </div>
            <p>
                <label class="floating" for="post_illustration_legend">Texte alternatif :</label>
                <input type="text" name="post_illustration_legend" id="post_illustration_legend" value="' . ($p['post_illustration_legend'] ?? null) . '" maxlength=64 class="long" />
            </p>
        </fieldset>
        <fieldset>
            <legend>Contenu</legend>
            <textarea id="post_content" name="post_content" class="wysiwyg">' . ($p['post_content'] ?? null) . '</textarea>
        </fieldset>

        <fieldset class="center">
    ';

    if ($postEntity) {
        $content .= '
            <a class="btn btn-danger" data-confirm="Voulez-vous vraiment SUPPRIMER ce billet ?"
                href="' . $urlGenerator->generate('post_delete', ['id' => $postEntity->get('id')]) . '">
                <span class="fa fa-trash-can"></span>
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
                <legend>Base de données</legend>
        
                <p>
                    <label class="floating" for="post_id" class="disabled">Billet n°</label>
                    <input type="text" name="post_id" id="post_id" value="' . ($p['post_id'] ?? null) . '" readonly>
                </p>
        
                <p>
                    <label class="floating" for="post_url">Adresse du billet :</label>
                    <input type="hidden" name="post_url_old" value=' . ($p['post_url'] ?? null) . '>
                    <input type="text" name="post_url" id="post_url" value="' . ($p['post_url'] ?? null) . '" placeholder="Champ rempli automatiquement" class="long" />
                </p>
                <br>
        
                <p>
                    <label class="floating" for="post_insert" class="readonly">Billet créé le :</label>
                    <input type="text" name="post_insert" id="post_insert" value="' . ($p['post_insert'] ?? null) . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" disabled>
                </p>
                <p>
                    <label class="floating" for="post_update" class="readonly">Billet modifié le :</label>
                    <input type="text" name="post_update" id="post_update" value="' . ($p['post_update'] ?? null) . '" placeholder="AAAA-MM-DD HH:MM:SS" class="datetime" disabled>
                </p>
            </fieldset>
        </form>
    ';

    $request->attributes->set("page_title", $pageTitle);

    return new Symfony\Component\HttpFoundation\Response($content);
};
