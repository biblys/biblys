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


// Search a publisher
$searchTerm = $request->query->get('term');
if ($searchTerm) {
    $i = 0;
    
    $publisher = \Biblys\Legacy\LegacyCodeHelper::getGlobalDatabaseConnection()->prepare(
        "SELECT `publisher_id`, `publisher_name` 
        FROM `publishers` 
        WHERE `publisher_name` LIKE :term
        ORDER BY `publisher_name`"
    );
    $publisher->bindValue(':term', '%'.$searchTerm.'%', PDO::PARAM_STR);
    $publisher->execute();

    while ($p = $publisher->fetch()) {
        $json[$i]["label"] = $p["publisher_name"];
        $json[$i]["value"] = $p["publisher_name"];
        $json[$i]["publisher_name"] = $p["publisher_name"];
        $json[$i]["publisher_id"] = $p["publisher_id"];
                $json[$i]["allowed_on_site"] = \Biblys\Legacy\LegacyCodeHelper::getGlobalSite()->allowsPublisherWithId($p["publisher_id"]) ? 1 : 0;
        $i++;
    }
    $json[$i]["label"] = '=> Créer : '.$_GET["term"];
    $json[$i]["value"] = $_GET["term"];
    $json[$i]["create"] = 1;
    
    echo json_encode($json);
} elseif ($_POST) { // Creer un nouvel editeur
    
    $name = $request->request->get('publisher_name');

    $pm = new PublisherManager();
    $publisher = $pm->create(['publisher_name' => $name]);
    echo json_encode(
        array_merge(
            ['publisher_id' => $publisher->get('id')], 
            $_POST
        )
    );
}
