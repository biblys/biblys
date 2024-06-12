<?php

use Symfony\Component\HttpFoundation\JsonResponse;

$pm = new PeopleManager();
$rm = new RoleManager();
$am = new ArticleManager();

$am->setIgnoreSiteFilters(true);

$term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_SPECIAL_CHARS);

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
$articleId = filter_input(INPUT_POST, 'article_id', FILTER_SANITIZE_NUMBER_INT);
$peopleId = filter_input(INPUT_POST, 'people_id', FILTER_SANITIZE_NUMBER_INT);
$jobId = filter_input(INPUT_POST, 'job_id', FILTER_SANITIZE_NUMBER_INT);
$roleId = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);
$peopleName = filter_input(INPUT_POST, 'people_name', FILTER_SANITIZE_SPECIAL_CHARS);

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
        $req .= "`people_name` LIKE :q".$i."";
        $params['q'.$i] = '%'.$q.'%';
        $i++;
    }
    
    $people = $_SQL->prepare(
        "SELECT `people_id`, `people_name` FROM `people` 
        WHERE ".$req." AND `people_deleted` IS NULL ORDER BY `people_alpha`"
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

} elseif ($action === 'remove') {

    // Remove a role (contributor / article / job association)
    $role = $rm->getById($roleId);
    if ($role) {
        $rm->delete($role);
    }

} elseif ($action === 'update') {

    // Update a role to change contributor's role
    $role = $rm->getById($roleId);
    if ($role) {
        $role->set('job_id', $jobId);
        $rm->update($role);
    }

} elseif ($action === 'add') {

    // Add a new role (contributor / article / job association)
    $people = $pm->getById($peopleId);
    $role = $article->addContributor($people, $jobId);

    $json["role_id"] = $role->get('id');
    $json["job_id"] = $role->get('job_id');
    $json["people"] = '
        <p id="role_'.$json["role_id"].'" class="article_role">
            <label for="job_id_'.$json["role_id"].'">'.$peopleName.'&nbsp;:</label>
            <select id="job_id_'.$json["role_id"].'" 
                class="change_role" data-role_id="'.$json["role_id"].'"></select>
            <a class="btn btn-danger btn-xs remove_people" 
                data-role_id="'.$json["role_id"].'">
                    <span class="fa fa-remove"></span>
            </a>
        </p>
    ';

} elseif ($action === 'create') {
    
    $peopleFirstName = $request->request->get('people_first_name');
    $peopleLastName = $request->request->get('people_last_name');
    
    try {
        $people = $pm->create(
            [
                'people_first_name' => $peopleFirstName,
                'people_last_name' => $peopleLastName,
            ]
        );
    } catch (Exception $e) {
        trigger_error(
            'Impossible de créer le contributeur 
                '.$peopleFirstName.' '.$peopleLastName.' : 
                '.$e->getMessage()
        );
    }
    
    $json = [
        'people_id' => $people->get('id'),
        'people_name' => $people->get('name'),
        'job_id' => 1
    ];
}

// Return updated list of authors
if ($article) {
    $people = $article->getAuthors();
    $authors = array_map(
        function ($people) {
            return $people->getName();
        }, $people
    );
    $json['authors'] = implode(', ', $authors);
}
    
$response = new JsonResponse($json);
$response->send();

