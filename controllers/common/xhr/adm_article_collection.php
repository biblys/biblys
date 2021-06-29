<?php

use Symfony\Component\HttpFoundation\JsonResponse;

$getTerm = trim($request->query->get('term'));

// Look for a collection
if ($getTerm) {
    $params = [];

    $_REQ_SITE = null;
    $publisherId = null;

    // Si on est sur un site editeur
    if (!empty($_SITE["publisher_id"])) {
        $_REQ_SITE = "AND `publisher_id` = :publisher_id";
        $publisherId = $site->get('publisher_id');
        $params["publisher_id"] = $publisherId;
    }

    // Si on est en mode gestion editeur
    elseif (!$_V->isAdmin() && $_V->isPublisher()) {
        $_REQ_SITE = "AND `publisher_id` = :publisher_id";
        $publisherId = $_V->getCurrentRight()->get('publisher_id');
        $params["publisher_id"] = $publisherId;
    }
    $terms = explode(" ", $getTerm);
    $_REQ = null;
    $i = 0;
    $termsParams = [];
    foreach ($terms as $term) {
        $termId = 'term_'.$i;
        if (isset($_REQ)) {
            $_REQ .= ' AND ';
        }
        $_REQ .= "(`collection_name` LIKE :".$termId." 
            OR `publisher_name` LIKE :".$termId.")";
        $termsParams[$termId] = '%'.$term.'%';
        $i++;
    }

    $i = 0;
    $j_colls[] = 0;

    $qu1 = "SELECT `collection_id`, `collection_name`, `publisher_id`, 
            `publisher_name`, `pricegrid_id` 
        FROM `collections` JOIN `publishers` USING(`publisher_id`) 
        WHERE `collection_deleted` IS NULL AND 
            `collection_name` LIKE :query 
            ".$_REQ_SITE." 
        ORDER BY `collection_name`";
    $collectionQuery = $_SQL->prepare($qu1);
    if ($publisherId) {
        $collectionQuery->bindParam('publisher_id', $params['publisher_id']);
    }
    $collectionQuery->bindValue('query', '%'.$getTerm.'%');
    $collectionQuery->execute();
    
    $qu2 = "SELECT `collection_id`, `collection_name`, `publisher_id`, 
            `publisher_name`, `pricegrid_id` 
        FROM `collections` JOIN `publishers` USING(`publisher_id`) 
        WHERE `collection_deleted` IS NULL AND ".$_REQ." ".$_REQ_SITE." 
        ORDER BY `collection_name`";
    $collectionsQuery = $_SQL->prepare($qu2);
    $collectionsQuery->execute(array_merge($params, $termsParams));

    while ($c = $collectionQuery->fetch() or $c = $collectionsQuery->fetch()) {
        
        // If collection is already in array, skip (deduplication)
        if (in_array($c["collection_id"], $j_colls)) {
            continue;
        }

        $json[$i]["label"] = $c["collection_name"].' ('.$c["publisher_name"].')';
        $json[$i]["value"] = $c["collection_name"];
        $json[$i]["collection_name"] = $c["collection_name"];
        $json[$i]["collection_publisher"] = $c["publisher_name"];
        $json[$i]["collection_id"] = $c["collection_id"];
        $json[$i]["publisher_id"] = $c["publisher_id"];
        $json[$i]["pricegrid_id"] = $c["pricegrid_id"];
        /** @var Site $site */
        $json[$i]["publisher_allowed_on_site"] = $site->allowsPublisherWithId($c["publisher_id"]) ? 1 : 0;

        $i++;
        $j_colls[] = $c["collection_id"];
    }
    $json[$i]["label"] = '=> Créer : '.$getTerm;
    $json[$i]["value"] = $getTerm;
    $json[$i]["create"] = 1;

    $response = new JsonResponse($json);
    $response->send();

// Create a new collection
} elseif ($_POST) {
    if (empty($_POST["collection_publisher_id"])) {
        json_error(0, "Éditeur non défini !");
    } else {
        $collectionName = $request->request->get('collection_name');
        $publisherId = $request->request->get('collection_publisher_id');

        $cm = new CollectionManager();
        $collection = $cm->create([
            'collection_name' => $collectionName,
            'publisher_id' => $publisherId,
        ]);
        echo json_encode(array_merge([
            'collection_id' => $collection->get('id'),
        ], $_POST));
    }
}
