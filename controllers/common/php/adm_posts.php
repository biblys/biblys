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


use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Model\BlogCategoryQuery;
use Model\PostQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function (
    Request                            $request,
    Config                             $config,
    CurrentUser                        $currentUser,
    UrlGenerator                       $urlGenerator,
    QueryParamsService $queryParams,
    TemplateService $templateService,
): Response {
    $currentUser->authPublisher();
    $rank = $currentUser->isAdmin() ? "adm_" : "pub_";

    $queryParams->parse(["category_id" => ["type" => "numeric", "default" => 0]]);
    $currentCategoryId = $queryParams->getInteger("category_id");

    $postQuery = PostQuery::create();

    if ($currentCategoryId) {
        $postQuery->filterByCategoryId($_GET["category_id"]);
    }

    if (!$currentUser->isAdmin() && $currentUser->hasPublisherRight()) {
        $postQuery->filterByPublisherId($currentUser->getCurrentRight()->getPublisherId());
    }

    $posts = $postQuery->find();

    $rows = "";
    foreach ($posts as $post) {
        $userIdentity = $post->getUser()->getEmail();

        $publishedStatus = '<i class="fa-solid fa-square red" aria-label="En ligne"></i>';
        if ($post->isPublished()) {
            $publishedStatus = '<i class="fa-solid fa-square green" aria-label="Hors ligne"></i>';
        }

        $postShowUrl = $urlGenerator->generate("post_show", ["slug" => $post->getUrl()]);
        $postEditUrl = "/pages/{$rank}post?id={$post->getId()}";
        $postDeleteUrl = $urlGenerator->generate("post_delete", ["id" => $post->getId()]);
        $rows .= '
            <tr>
                <td class="right">' . $publishedStatus . '</td>
                <td><a href="' . $postShowUrl . '">' . $post->getTitle() . '</a></td>
                <td class="nowrap">' . $userIdentity . '</td>
                <td>' . $post->getBlogCategory()?->getName() . '</td>
                <td>' . _date($post->getDate(), 'd/m/Y') . '</td>
                <td class="nowrap">
                    <a class="btn btn-sm btn-primary" href="' . $postEditUrl . '">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Éditer
                    </a>
                    <a class="btn btn-sm btn-danger" href="' . $postDeleteUrl . '" 
                      aria-label="Supprimer" 
                      data-confirm="Voulez-vous vraiment SUPPRIMER ce billet ?"
                    >
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        ';
    }

    $request->attributes->set("page_title", "Gestion des billets");

    $categories = BlogCategoryQuery::create()->find();
    /** @noinspection HtmlUnknownAttribute */
    $content = $templateService->renderFromString('
        <h1>
          <i class="fa-solid fa-newspaper"></i> 
          Gestion des billets
        </h1>
    
        <div class="mt-4">
            <a href="/pages/{{ rank }}post" class="btn btn-primary">
              <i class="fa-solid fa-newspaper"></i> Nouveau billet</a>
            </a>
        </div>
        
        <form class="form-inline mt-4 mb-2">
          <label class="my-1 mr-2" for="category_filter">Filtrer par catégorie</label>
          <select class="custom-select my-1 mr-sm-2" id="category_filter" name="category_id">
            <option value="0">Catégorie...</option>
            {% for category in categories %}
                <option value="{{ category.id }}"
                  {% if category.id == currentCategoryId %} selected {% endif %}
                >{{ category.name }}</option>
            {% endfor %}
          </select>
        
          <button type="submit" class="btn btn-primary my-1">
            <i class="fa-solid fa-arrows-rotate"></i>
          </button>
      </form>
    
        <div class="admin">
            <p>Billets</p>
            <p><a href="/pages/{{ rank }}post">nouveau</a></p>
        </div>
    
        <table class="table">
    
            <thead>
                <tr>
                    <th></th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Catégorie</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
    
            <tbody>
                {{rows|raw}}
            </tbody>
    
        </table>
    ', [
        "rows" => $rows,
        "rank" => $rank,
        "categories" => $categories,
        "currentCategoryId" => $currentCategoryId,
    ]);

    return new Response($content);
};
