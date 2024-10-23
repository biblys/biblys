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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @throws Exception
 */
function admArticlePeopleController(Request $request): JsonResponse
{
    $pm = new PeopleManager();
    $am = new ArticleManager();

    $am->setIgnoreSiteFilters(true);

    $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_SPECIAL_CHARS);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
    $articleId = filter_input(INPUT_POST, 'article_id', FILTER_SANITIZE_NUMBER_INT);

    /** @var Article $article */
    $article = $am->getById($articleId);
    if ($articleId && !$article) {
        throw new Exception("Article $articleId inconnu.");
    }

    $json = [];

    // Search for existing contributors
    if (isset($term)) {
        $i = 0;
        $req = null;
        $term = $request->query->get('term');
        $query = explode(" ", trim($term));
        $params = [];
        foreach ($query as $q) {
            if (isset($req)) {
                $req .= " AND ";
            }
            $req .= "`people_name` LIKE :q" . $i;
            $params['q' . $i] = '%' . $q . '%';
            $i++;
        }

        $people = LegacyCodeHelper::getGlobalDatabaseConnection()->prepare(
            "SELECT `people_id`, `people_name` FROM `people` 
        WHERE " . $req . " ORDER BY `people_alpha`"
        );
        $people->execute($params);
        while ($p = $people->fetch(PDO::FETCH_ASSOC)) {
            $json[$i]["label"] = $p["people_name"];
            $json[$i]["value"] = $p["people_name"];
            $json[$i]["id"] = $p["people_id"];
            $i++;
        }
        $json[$i]["label"] = '=> Créer un nouveau contributeur ';
        $json[$i]["value"] = $term;
        $json[$i]["create"] = 1;

    } elseif ($action === 'create') {

        $peopleFirstName = $request->request->get('people_first_name');
        $peopleLastName = $request->request->get('people_last_name');
        $people = $pm->create(
            [
                'people_first_name' => $peopleFirstName,
                'people_last_name' => $peopleLastName,
            ]
        );

        $json = [
            'people_id' => $people->get('id'),
            'people_name' => $people->get('name'),
            'job_id' => 1
        ];
    }

    // Return an updated list of authors
    if ($article) {
        $people = $article->getAuthors();
        $authors = array_map(
            function ($people) {
                return $people->getName();
            }, $people
        );
        $json['authors'] = implode(', ', $authors);
    }

    return new JsonResponse($json);
}

$request = LegacyCodeHelper::getGlobalRequest();
$response = admArticlePeopleController($request);
$response->send();
