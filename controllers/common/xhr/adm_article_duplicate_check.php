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


use Symfony\Component\HttpFoundation\JsonResponse;

$field = filter_input(INPUT_GET, 'field', FILTER_SANITIZE_SPECIAL_CHARS);
$value = filter_input(INPUT_GET, 'value', FILTER_SANITIZE_SPECIAL_CHARS);
$articleId = filter_input(INPUT_GET, 'article_id', FILTER_SANITIZE_NUMBER_INT);
$collectionId = filter_input(INPUT_GET, 'collection_id', FILTER_SANITIZE_NUMBER_INT);

if ($field && $value) {
    $json["content"] = '';
    $params = ['value' => $value, 'article_id' => $articleId];
    if ($field == "article_title") {
        $req = "`article_title` = :value OR `article_title_others` LIKE :like_value";
        $params['like_value'] = '%'.$value.'%';
    } elseif ($field == "article_number") {
        $req = "`collection_id` = :collection_id AND `article_number` = :value";
        $params['collection_id'] = $collectionId;
    } else {
        $params['field'] = $field;
        $req = ":field LIKE :value AND :field IS NOT NULL";
    }

    if (isset($req)) {
        $req = "SELECT `article_id`, `article_item`, `article_title`, `article_url`, `article_collection`, `article_number` 
            FROM `articles` WHERE `article_id` != :article_id AND (".$req.")";
        $articles = \Biblys\Legacy\LegacyCodeHelper::getGlobalDatabaseConnection()->prepare($req);
        $articles->execute($params);

        $i = 0;
        while ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
            if (!empty($a["article_item"])) {
                $a["linkto"] = '*';
            } else {
                $a['linkto'] = null;
            }
            $json["content"] .= '<li> <a class="btn btn-warning btn-sm linkto" data-id="'.$a["article_id"].'"">lier '.$a["linkto"].'</a> <a href="/'.$a["article_url"].'">'.$a["article_title"].'</a> ('.$a["article_collection"].numero($a["article_number"]).')</li>';
            $i++;
        }
        if (!empty($i)) {
            $json["message"] = '<a href="#duplicates_fieldset">'.$i.' doublon'.s($i).' potentiel'.s($i).' détecté'.s($i).'&nbsp;!</a>';
        }
        
        $response = new JsonResponse($json);
        $response->send();
    }
}
