<?php

$_PAGE_TITLE = 'Éditeurs';

$letters = '<p class="center">';
for ($L = "A"; $L != "AA"; $L++) {
    $letters .= '<a href="/pages/editeurs?l='.$L.'">'.$L.'</a>';
    if ($L != "Z") {
        $letters .= ' | ';
    }
}

$query = $request->query->get('q', null);

$params = [];
$qi = 0;
if ($query) {
    $req = null;
    foreach (explode(" ", $query) as $q) {
        $qi++;
        if (!empty($req)) {
            $req .= ' AND ';
        }
        $req .= '`publisher_name` LIKE :q'.$qi;
        $params['q'.$qi] = '%'.$q.'%';
    }
    $req = ' WHERE '.$req;
    $subtitle = '<h3>Recherche de &laquo; '.htmlentities($query).' &raquo;</h3>';
} elseif (!empty($_GET["l"])) {
    $req = " WHERE `publisher_name_alphabetic` LIKE :letter ORDER BY `publisher_name_alphabetic`";
    $params = array("letter" => $_GET["l"].'%');
} else {
    $req = ' ORDER BY `publisher_insert` DESC, `publisher_id` DESC LIMIT 50';
    $subtitle = '<h3>R&eacute;cemment ajout&eacute;s</h3>';
}

$publishers = $_SQL->prepare('SELECT `publisher_name`,`publisher_url` FROM `publishers`'.$req);
$publishers->execute($params);
$colls = null;
while ($x = $publishers->fetch()) {
    $colls .= '<p><a href="/editeur/'.$x["publisher_url"].'">'.$x["publisher_name"].'</a></p>';
}

$_ECHO .= '
    <h2 class="floatR"><a href="/pages/collections">Collections</a></h2>
    <h2>'.$_PAGE_TITLE.'</h2>
    <form class="center" method="get">
      <fieldset>
        <p>Rechercher un &eacute;diteur :
          <input type="text" name="q" value="'.htmlentities($query).'" />
          <input type="submit" value="&#187;" />
        </p>
      </fieldset>
    </form>
    '.$letters.'
    '.$subtitle.'
    '.$colls.'
    <br />
';
