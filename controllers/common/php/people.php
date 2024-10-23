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


use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Model\PeopleQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @throws PropelException
 */
return function (
    Request      $request,
    CurrentSite  $currentSite,
    CurrentUser  $currentUser,
    UrlGenerator $urlGenerator,
    ImagesService $imagesService,
): Response|RedirectResponse
{
    $content = "";

    $pm = new PeopleManager();
    $peopleSlug = LegacyCodeHelper::getRouteParam("slug");
    $peopleEntity = $pm->get(['people_url' => $peopleSlug]);
    if (!$peopleEntity) {
        throw new ResourceNotFoundException('No people found for url ' . htmlentities($peopleSlug));
    }

    $people = PeopleQuery::create()->findPk($peopleEntity->get("id"));

    $use_old_controller = $currentSite->getOption("use_old_people_controller");
    if (!$use_old_controller) {
        return new RedirectResponse("/p/$peopleSlug/", 301);
    }

    $_OPENGRAPH = '
    <meta property="og:title" content="' . $people->getName() . '"/>
    <meta property="og:type" content="author"/>
    <meta property="og:url" content="https://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . '"/>
    <meta property="og:description" content="' . truncate(
        $people->getBio() || "", 500, '...', true
        ) . '"/>
    <meta property="og:locale" content="fr_FR"/>
    <meta property="og:site_name" content="' . $currentSite->getSite()->getName() . '"/>
';

    if ($currentUser->isAdmin()) {
        $content .= '
        <div class="admin">
            <p>Intervenant n° ' . $people->getId() . '</p>
            <p><a href="' . $urlGenerator->generate("people_edit", ["id" => $peopleEntity->get("id")]) . '">modifier</a></p>
        </div>
    ';
    }

    $request->attributes->set("page_title", $peopleEntity->get("name"));
    $content .= '<h2>' . $peopleEntity->get("name") . '</h2>';

// Aliases
    $aliases = $pm->getAll(['people_pseudo' => $peopleEntity->get('id')]);
    $aliases = array_map(function ($alias) {
        return '<a href="/' . $alias->get('url') . '/">' . $alias->get('name') . '</a>';
    }, $aliases);
    if (count($aliases)) {
        $content .= '<p>Ses pseudonymes : ' . join(', ', $aliases) . '</p>';
    }

    // Pseudonyme de...
    if ($peopleEntity->has('pseudo')) {
        $realName = $pm->get(['people_id' => $peopleEntity->get('pseudo')]);
        if ($realName) {
            $content .= '<p>(Pseudonyme de <a href="/' . $realName["people_url"] . '/">' . $realName["people_name"] . '</a>)</p>';
        }
    }

    // Linked post
    $sql = EntityManager::prepareAndExecute(
        "SELECT * FROM `posts` JOIN `links` USING(`post_id`) WHERE `posts`.`site_id` = '" . $currentSite->getId() . "' AND `links`.`people_id` = '" . $people->getId() . "'",
        []
    );
    $posts = $sql->fetchAll();

    if ($posts) {
        $posts = array_map(function ($p) {
            return '<li><a href="/blog/' . $p["post_url"] . '">' . $p["post_title"] . '</a></li>';
        }, $posts);

        $content .= '<h3>À propos</h3><ul>' . implode($posts) . '</ul>';
    }

    $flag = null;
    if (!empty($p["people_nation"])) {
        $flag = '<img src="/common/flags/' . $p["people_nation"] . '.png" alt="Nationalité ' . $p["people_nation"] . '" /> ';
    }

    $dates = null;
    if (!empty($p["people_birth"]) and !empty($p["people_death"])) {
        $dates = $p["people_birth"] . ' - ' . $p["people_death"];
    } elseif (!empty($p["people_birth"])) {
        $dates = 'Né en ' . $p["people_birth"];
    }

    $content .= '
    <div id="people" class="clearfix">
        <div id="people-photo">
';

    if ($imagesService->imageExistsFor($people)) {
        $_OPENGRAPH .= '<meta property="og:image" content="' . $imagesService->getImageUrlFor($people) . '" />';
        $content .= '<img src="' . $imagesService->getImageUrlFor($people, width: 200) . '" alt="' . $people->getName() . '" class="frame" />';
    }

    $content .= '<p class="center">' . $flag . ' ' . $dates . '</p>';
    if (!empty($p["people_site"])) {
        $content .= '<p><a href="' . $p["people_site"] . '" rel="nofollow" target="_blank">Site officiel</a></p>';
    }

    $content .= '
        </div>
';

    $content .= '<div id="people-bio">' . $people->getBio() . '</div>';

    $_REQ = "`article_links` LIKE '%[people:" . $people->getId() . "]%'";

    $path = get_controller_path('_list');
    $content .= require_once $path;

    return new Response($content);
};
