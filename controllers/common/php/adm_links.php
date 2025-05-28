<?php /** @noinspection PhpUnhandledExceptionInspection */
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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @throws Exception
 */
return function (Request $request, CurrentSite $currentSite): JsonResponse
{
    $j = array();
    $req = null;

    $lm = new LinkManager();
    $am = new ArticleManager();
    $pm = new PeopleManager();
    $em = new EventManager();
    $pom = new PostManager();
    $pum = new PublisherManager();

    $am->setIgnoreSiteFilters(true);

    if ($request->getMethod() == "POST") {
        $elementType = $request->request->get('element_type') . '_id';
        $linkToType = $request->request->get('linkto_type') . '_id';
        $elementId = $request->request->get('element_id');
        $linkToId = $request->request->get('linkto_id');
        $req = 'INSERT INTO `links`(`' . $elementType . '`, `' . $linkToType . '`) VALUES(:elementId, :linkToId)';
        EntityManager::prepareAndExecute(
            $req,
            ["elementId" => $elementId, "linkToId" => $linkToId]
        );
        $link_id = LegacyCodeHelper::getGlobalDatabaseConnection()->lastInsertId();

        if ($_POST["linkto_type"] == "article") {
            $article = $am->getById($linkToId);
            if ($article) {
                $a = $article;
                $j["link"] = '<li id="link_' . $link_id . '" class="new" style="display: none;"><span data-link_id="' . $link_id . '" class="fa-solid fa-xmark-circle red deleteLink pointer"></span> <a href="/' . $a["article_url"] . '">' . $a["article_title"] . '</a> de ' . $a["article_authors"] . ' (' . $a["article_collection"] . ')</li>';
            }
        } elseif ($_POST["linkto_type"] == "people") {
            $people = $pm->getById($linkToId);
            if ($people) {
                $p = $people;
                $j["link"] = '<li id="link_' . $link_id . '" ><img alt="" src="/common/icons/delete_16.png" data-link_id="' . $link_id . '" class="deleteLink pointer" /> <a href="/' . $p["people_url"] . '">' . $p["people_name"] . '</a></li>';
            }
        } elseif ($_POST["linkto_type"] == "event") {
            $event = $em->getById($linkToId);
            if ($event) {
                $e = $event;
                $j["link"] = '<li id="link_' . $link_id . '" ><img alt="" src="/common/icons/delete_16.png" data-link_id="' . $link_id . '" class="deleteLink pointer" /> <a href="/programme/' . $e["event_url"] . '">' . $e["event_title"] . '</a></li>';
            }
        } elseif ($_POST["linkto_type"] == "post") {
            $post = $pom->getById($linkToId);
            if ($post) {
                $p = $post;
                $j["link"] = '<li id="link_' . $link_id . '" ><img alt="" src="/common/icons/delete_16.png" data-link_id="' . $link_id . '" class="deleteLink pointer" /> <a href="/post/' . $p["post_url"] . '">' . $p["post_title"] . '</a></li>';
            }
        } elseif ($_POST["linkto_type"] == "publisher") {
            $publisher = $pum->getByid($linkToId);
            if ($publisher) {
                $p = $publisher;
                $j["link"] = '<li id="link_' . $link_id . '" ><img alt="" src="/common/icons/delete_16.png" data-link_id="' . $link_id . '" class="deleteLink pointer" /> <a href="/editeur/' . $p["publisher_url"] . '">' . $p["publisher_name"] . '</a></li>';
            }
        }
    } elseif (isset($_GET['type'])) {
        $term = $request->query->get('term');
        if ($_GET["type"] == "articles") {
            $i = 0;
            $articles = $am->search($term, ["limit" => 100]);
            foreach ($articles as $article) {
                $a = $article;
                $j[$i]["label"] = $a["article_title"] . ' (' . $a["article_collection"] . ')';
                $j[$i]["value"] = '';
                $j[$i]["id"] = $a["article_id"];
                $i++;
            }
        } elseif ($_GET["type"] == "people") {
            $i = 0;
            $query = explode(' ', addslashes(trim($_GET["term"])));
            foreach ($query as $q) {
                if (isset($req)) {
                    $req .= " AND ";
                }
                $req .= "`people_name` LIKE '%" . $q . "%'";
            }
            $people = EntityManager::prepareAndExecute(
                "SELECT `people_id`, `people_name` FROM `people` WHERE " . $req . " 
                ORDER BY `people_last_name`, `people_first_name` LIMIT 100",
                [],
            );
            while ($p = $people->fetch(PDO::FETCH_ASSOC)) {
                $j[$i]["label"] = $p["people_name"];
                $j[$i]["value"] = '';
                $j[$i]["id"] = $p["people_id"];
                $i++;
            }
        } elseif ($_GET["type"] == "publisher") {
            $i = 0;
            $query = explode(" ", addslashes(trim($_GET["term"])));
            foreach ($query as $q) {
                if (isset($req)) {
                    $req .= " AND ";
                }
                $req .= "`publisher_name` LIKE '%" . $q . "%'";
            }
            $publishers = EntityManager::prepareAndExecute(
                "SELECT `publisher_id`, `publisher_name` FROM `publishers` WHERE " . $req . " ORDER BY `publisher_name_alphabetic` LIMIT 100",
                [],
            );
            while ($p = $publishers->fetch(PDO::FETCH_ASSOC)) {
                $j[$i]["label"] = $p["publisher_name"];
                $j[$i]["value"] = '';
                $j[$i]["id"] = $p["publisher_id"];
                $i++;
            }
        } elseif ($_GET["type"] == "event") {
            $i = 0;
            $query = explode(" ", addslashes(trim($_GET["term"])));
            foreach ($query as $q) {
                if (isset($req)) {
                    $req .= " AND ";
                }
                $req .= "`event_title` LIKE '%" . $q . "%'";
            }
            $events = EntityManager::prepareAndExecute(
                "SELECT `event_id`, `event_title` FROM `events` WHERE " . $req . " AND `site_id` = '"
                . $currentSite->getId()
                . "' ORDER BY `event_title` LIMIT 100",
                [],
            );
            while ($e = $events->fetch(PDO::FETCH_ASSOC)) {
                $j[$i]["label"] = $e["event_title"];
                $j[$i]["value"] = '';
                $j[$i]["id"] = $e["event_id"];
                $i++;
            }
        } elseif ($_GET["type"] == "post") {
            $i = 0;
            $query = explode(" ", addslashes(trim($_GET["term"])));
            foreach ($query as $q) {
                if (isset($req)) {
                    $req .= " AND ";
                }
                $req .= "`post_title` LIKE '%" . $q . "%'";
            }
            $posts = EntityManager::prepareAndExecute(
                "SELECT `post_id`, `post_title` FROM `posts` WHERE " . $req . " AND `site_id` = '"
                . $currentSite->getId()
                . "' ORDER BY `post_title` LIMIT 100",
                [],
            );
            while ($p = $posts->fetch(PDO::FETCH_ASSOC)) {
                $j[$i]["label"] = $p["post_title"];
                $j[$i]["value"] = '';
                $j[$i]["id"] = $p["post_id"];
                $i++;
            }
        }
    }

    return new JsonResponse($j);
};
