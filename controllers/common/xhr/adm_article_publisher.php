<?php

// Search a publisher
$searchTerm = $request->query->get('term');
if ($searchTerm) {
    $i = 0;
    
    $publisher = $_SQL->prepare(
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
        /** @var Site $site */
        $json[$i]["allowed_on_site"] = $site->allowsPublisherWithId($p["publisher_id"]) ? 1 : 0;
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
