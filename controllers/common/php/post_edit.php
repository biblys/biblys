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

use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Model\Base\BlogCategoryQuery;
use Model\BlogCategory;
use Model\PostQuery;
use Model\Post;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function (
    Request            $request,
    QueryParamsService $queryParams,
    BodyParamsService  $bodyParams,
    CurrentUser        $currentUser,
    UrlGenerator       $urlGenerator,
    ImagesService      $imagesService,
    TemplateService    $templateService,
): Response|RedirectResponse {
    $currentUser->authPublisher();

    $queryParams->parse(["id" => ["type" => "numeric", "default" => null]]);
    $postId = $queryParams->getInteger("id");

    /** @var Post $post */
    $post = PostQuery::create()->findPk($postId);
    if (!$post) {
        $post = new Post();
        $post->setUserId($currentUser->getUser()->getId());
    }

    $content = '';

    $isPostPublished = null;
    $isPostSelected = null;

    $pageTitle = "Nouveau billet";
    $request->attributes->set("page_title", $pageTitle);

    // Valeurs par défaut pour un nouveau billet
    $postDate = date("Y-m-d");
    $postTime = date("H:i");

    $author = $currentUser->getUser()->getEmail();
    if ($currentUser->hasPublisherRight()) {
        $publisher = $currentUser->getCurrentRight()->getPublisher();
        if ($publisher) {
            $author = $publisher->getName();
        }
    }

    $existingPostIllustration = '';
    if (!$post->isNew()) {
        $pageTitle = "Modifier « <a href=\"/blog/{$post->getUrl()}\">{$post->getTitle()}</a> »";
        $request->attributes->set("page_title", "Modifier « {$post->getTitle()} »");

        $content .= '
            <div class="admin">
                <p>Billet n° ' . $post->getId() . '</p>
                <p><a href="/blog/' . $post->getUrl() . '">voir</a></p>
                <p><a href="/pages/links?post_id=' . $post->getId() . '">lier</a></p>
                <p><a href="' . $urlGenerator->generate("post_delete", ["id" => $post->getId()]) . '" data-confirm="Voulez-vous vraiment SUPPRIMER ce billet ?">supprimer</a></p>
                <p><a href="' . $urlGenerator->generate("posts_admin") . '">billets</a></p>
            </div>
        ';
        $author = $currentUser->getUser()->getEmail();
        $date = explode(" ", $post->getDate()->format("Y-m-d H:i:s"));
        $postDate = $date[0];
        $postTime = substr($date[1], 0, 5);

        // Illustration
        if ($imagesService->imageExistsFor($post)) {
            $existingPostIllustration = '
                <div class="text-center">
                    <img src="' . $imagesService->getImageUrlFor($post, height: 300) . '" height="300" alt="">
                    <br />
                    <input type="checkbox" value=1 name="post_illustration_delete" id="illustration_delete" />
                    <label for="illustration_delete">Supprimer</label>
                </div>
            ';
        }

        if ($post->getStatus()) $isPostPublished = ' selected';
        if ($post->getSelected()) $isPostSelected = ' checked';
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

    $categories = BlogCategoryQuery::create()->find();
    /** @var BlogCategory $category */
    $categoriesOptions = '';
    foreach ($categories as $category) {
        $selected = "";
        if ($post->getCategoryId() == $category->getId()) $selected = ' selected';
        $categoriesOptions .= '<option value="' . $category->getId() . '"' . $selected . '>' . $category->getName() . '</option>';
    }

    $deleteButton = '';
    if ($postEntity) {
        $deleteButton = '
            <a class="btn btn-danger" data-confirm="Voulez-vous vraiment SUPPRIMER ce billet ?"
                href="' . $urlGenerator->generate('post_delete', ['id' => $post->getId()]) . '">
                <span class="fa fa-trash-can"></span>
                Supprimer le billet
            </a>
        ';
    }

    $formActionUrl = $urlGenerator->generate("post_create");
    if (!$post->isNew()) {
        $formActionUrl = $urlGenerator->generate("post_update", ["id" => $post->getId()]);
    }

    $content .= '
        <h1><i class="fa fa-newspaper"></i> ' . $pageTitle . '</h1>
    
        <form action="'.$formActionUrl.'" method="post" class="check fieldset" enctype="multipart/form-data">
            <fieldset>
                <legend>Informations</legend>
                <p>
                    <label class="floating" for="post_author">Auteur :</label>
                    <input type="text" name="post_author" id="post_author" value="' . $author . '" class="long" disabled="disabled" />
                    <input type="hidden" name="user_id" id="user_id" value="' . $post->getUserId() . '" />
                    <input type="hidden" name="publisher_id" id="publisher_id" value="' . ($post->getPublisherId() ?? null) . '">
                </p>
            <p>
                <label class="floating" for="category_id">Catégorie :</label>
                <select name="category_id">
                    <option value="0" />
                    ' . $categoriesOptions . '
                </select>
                <br />
            </p>
            <p>
                <label class="floating" for="post_title" class="required">Titre :</label>
                <input type="text" name="post_title" id="post_title" value="' . ($post->getTitle() ? htmlentities($post->getTitle()) : null) . '" class="long required" required />
            </p>
            <br>

            <p>
                <label class="floating" for="post_status">État :</label>
                <select name="post_status">
                    <option value="0">Hors-ligne</option>
                    <option value="1" ' . $isPostPublished . '>En ligne</option>
                </select>
            </p>
            <p>
                <label class="floating" for="post_date" class="required">Date de parution :</label>
                <input type="date" name="post_date" id="post_date" value="' . $postDate . '" placeholder="AAAA-MM-JJ" required>
                <input type="time" name="post_time" id="post_time" value="' . $postTime . '" placeholder="HH:MM" required>
            </p>

            <label class="floating" for="post_link">Lien :</label>
            <input 
                type="url" 
                name="post_link" 
                id="post_link" 
                placeholder="https://" 
                value="' . ($post->getLink()) . '" 
                class="long" 
            />
            <br /><br />

            <label class="floating" for="post_selected">À la une :</label>
            <input type="checkbox" name="post_selected" id="post_selected" value="1"' . $isPostSelected . ' />
            <br /><br />
        </fieldset>
        <fieldset>
            <legend>Illustration</legend>
              <div class="form-group">
                ' . $existingPostIllustration . '
              
                <label for="post_illustration_upload">Image</label>
                <input type="file" id="post_illustration_upload" name="post_illustration_upload" accept="image/jpeg, image/png, image/webp">
                <p class="help-block">Image au format JPEG, PNG ou WebP affichée en prévisualisation sur les réseaux sociaux.</p>
              </div>
            <p>
                <label class="floating" for="post_illustration_legend">Texte alternatif :</label>
                <input type="text" name="post_illustration_legend" id="post_illustration_legend" value="' . ($post->getIllustrationLegend()) . '" maxlength=64 class="long" />
            </p>
        </fieldset>
        <fieldset>
            <legend>Contenu</legend>
            <textarea id="post_content" name="post_content" class="wysiwyg">' . ($post->getContent()) . '</textarea>
        </fieldset>

        <fieldset class="center">
            ' . $deleteButton . '

                <button class="btn btn-primary" type="submit">
                    <span class="fa fa-save"></span>
                    Enregistrer le billet
                </button>
            </fieldset>
        </form>
    ';

    return $templateService->renderResponseFromString($content, [
        "post" => $post,
    ]);
};
