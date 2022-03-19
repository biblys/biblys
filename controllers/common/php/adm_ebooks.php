<?php

use Biblys\Service\Config;
use Symfony\Component\HttpFoundation\Response;

$_JS_CALLS[] = "/common/js/sorttable.js";

$_PAGE_TITLE = "Ventes numériques";

if (!$site->getOpt('downloadable_publishers')) {
    throw new Exception("L'option de site `downloadable_publishers` doit être définie.");
}

// Article select
$ao = array();
$articles = $_SQL->query("SELECT `article_id`, `article_title`, `article_collection` FROM `articles`
    WHERE
        `publisher_id` IN (" . $site->getOpt('downloadable_publishers') . ")
        AND (`type_id` = 2 OR `type_id` = 11) ORDER BY `article_collection`, `article_title_alphabetic`");
while ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
    $ao[$a["article_collection"]][] = '<option value="' . $a["article_id"] . '"' . (isset($_GET["article_id"]) && $_GET["article_id"] == $a["article_id"] ? ' selected' : null) . '>' . $a["article_title"] . '</option>';
}

$article_options = NULL;
foreach ($ao as $c => $a) {
    $article_options .= '<optgroup label="' . $c . '">' . implode($a) . '</optgroup>';
}

// People select
$people_options = NULL;
$people = $_SQL->prepare("SELECT `people_id`, `people_name` FROM `articles` JOIN `roles` USING(`article_id`) JOIN `people` USING(`people_id`) JOIN `collections` USING(`collection_id`) WHERE `collections`.`site_id` = :site_id AND `type_id` = 2 AND `job_id` = 1 GROUP BY `people_id` ORDER BY `people_last_name`");
$people->execute(['site_id' => $site->get('id')]);
while ($p = $people->fetch(PDO::FETCH_ASSOC)) {
    $people_options .= '<option value="' . $p["people_id"] . '">' . $p["people_name"] . '</option>';
}

$req = NULL;
$reqPeople = null;
$reqParams = ['site_id' => $site->get('id')];
$reqPeopleParams = [];

if (!empty($_GET["date1"])) {
    $req .= " AND `stock_selling_date` >= :date1 AND `stock_selling_date` <= :date2";
    $reqParams['date1'] = $request->query->get('date1') . ' 00:00:00';
    $reqParams['date2'] = $request->query->get('date2') . ' 23:59:59';
}

$articleId = $request->query->get('article_id');
if ($articleId) {
    $req .= " AND `stock`.`article_id` = :article_id";
    $reqParams['article_id'] = $articleId;
}

$peopleId = $request->query->get('people_id');
if ($peopleId) {
    $reqPeople = " AND `article_links` LIKE :people_id";
    $reqPeopleParams['people_id'] = '%[people:' . $peopleId . ']%';
}

// Ventes numériques
$ventes = EntityManager::prepareAndExecute("
    SELECT
        `article_id`, `article_title`, `article_url`, `article_authors`, `article_ean`,
        `article_publisher`, `article_authors`, `article_price`
    FROM `articles`
    JOIN `stock` USING(`article_id`)
    WHERE
        `site_id` = :site_id AND (`type_id` = 2 OR `type_id` = 11) AND `stock`.`user_id` IS NOT NULL
        " . $req . $reqPeople . "
    GROUP BY `article_id`
    ORDER BY `article_authors_alphabetic` ", array_merge($reqParams, $reqPeopleParams));

$tbody = NULL;
$Ventes = null;
$Total = null;
$Gratuits = null;
$export = [];
$subReqParams = $reqParams;
while ($l = $ventes->fetch(PDO::FETCH_ASSOC)) {

    if (!empty($_GET["people_id"])) $_PAGE_TITLE = "Ventes numériques : " . authors($l["article_authors"]);
    if (!empty($_GET["article_id"])) $_PAGE_TITLE = "Ventes numériques : " . $l["article_title"];

    $subReqParams['article_id'] = $l['article_id'];

    $numVentes = $_SQL->prepare("SELECT COUNT(`stock_id`) AS `ventes`, SUM(`stock_selling_price`) AS `ca`, SUM(`stock_selling_price_ht`) AS `ca_ht` FROM `stock` WHERE `article_id` = :article_id AND `stock_selling_price` != '0' AND `stock_selling_date` AND `site_id` = :site_id " . $req . " AND `stock`.`user_id` IS NOT NULL");
    $numVentes->execute($subReqParams);
    $v = $numVentes->fetch(PDO::FETCH_ASSOC);

    $numGratuits = $_SQL->prepare("SELECT COUNT(`stock_id`) AS `gratuits` FROM `stock` WHERE `article_id` = :article_id AND `stock_selling_price` IS NULL AND `site_id` = :site_id " . $req);
    $numGratuits->execute($subReqParams);
    $g = $numGratuits->fetch(PDO::FETCH_ASSOC);

    $tbody .= '
        <tr>
            <td>' . authors($l["article_authors"]) . '</td>
            <td><a href="/' . $l["article_url"] . '">' . $l["article_title"] . '</a></td>
            <td class="right">' . $v["ventes"] . '</td>
            <td class="right">' . $g["gratuits"] . '</td>
            <td class="right">' . price($v["ca"], 'EUR') . '</td>
        </tr>
    ';
    $Total += $v["ca"];
    $Ventes += $v["ventes"];
    $Gratuits += $g["gratuits"];

    $export[] = [
        $l["stock_selling_date"],
        $l["stock_selling_date"],
        $l["article_ean"],
        null,
        $l["article_title"],
        $l["article_publisher"],
        $l["article_authors"],
        null,
        price($l["article_price"]),
        "EUR",
        $l["stock_tva_rate"],
        $v["ventes"],
        null,
        $site->get('name'),
        null,
        null,
        $l["stock_selling_price_ht"],
        null,
        $l["stock_selling_price"],
        $v["ca_ht"],
        $v["ca"]
    ];
}

$content = '<h1><span class="fa fa-book"></span> Ventes numériques</h1>';

$content .= '
        <form class="fieldset form-horizontal" role="form">
            <fieldset>
                <legend>Filter les ventes</legend>

                <div class="form-group">
                    <label for="people_id" class="col-sm-3 control-label">Par auteur :</label>
                    <div class="col-sm-9">
                        <select name="people_id" id="people_id" class="form-control">
                            <option value="0">Tous</option>
                            ' . $people_options . '
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="article_id" class="col-sm-3 control-label">Par titre :</label>
                    <div class="col-sm-9">
                        <select name="article_id" id="article_id" class="form-control">
                            <option value="0">Tous</option>
                            ' . $article_options . '
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="date1" class="col-sm-3 control-label">Du :</label>
                    <div class="col-sm-9">
                        <input type="date" class="date" id="date1" name="date1" value="' . (isset($_GET["date1"]) ? $_GET["date1"] : null) . '">
                    </div>
                </div>

                <div class="form-group">
                    <label for="date2" class="col-sm-3 control-label">Au :</label>
                    <div class="col-sm-9">
                        <input type="date" class="date" id="date2" name="date2" value="' . (isset($_GET["date2"]) ? $_GET["date2"] : null) . '">
                    </div>
                </div>

                <div class="center">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </fieldset>
        </form>
        <br />

    <h3>Par titre</h3>
    <br />

    <table class="admin-table sortable">
        <thead>
            <tr class="cliquable">
                <th>Auteur</th>
                <th>Titre</th>
                <th class="right">Ventes</th>
                <th class="right">Gratuits</th>
                <th class="right">C.A.&nbsp;total</th>
            </tr>
        </thead>
        <tbody>
            ' . $tbody . '
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td class="right">Total :</td>
                <td class="right">' . $Ventes . '</td>
                <td class="right">' . $Gratuits . '</td>
                <td class="right">' . price($Total, 'EUR') . '</td>
            </tr>
        </tfoot>
    </table>
';

$articleId = $request->query->get('article_id');
if ($articleId) {
    $content .= '<h3>Statistiques de t&eacute;l&eacute;chargements</h3>';

    $downloads = $_SQL->prepare("SELECT `download_filetype`, `download_version` FROM `downloads` WHERE `article_id` = :article_id");
    $downloads->execute(['article_id' => $articleId]);
    $total = $downloads->rowCount();

    $content .= '
        <table class="admin-table sortable">
            <tr>
                <td class="right">Total :</td>
                <td class="right">' . $total . '</td>
                <td class="right">100 %</td>
            </tr>
        </table>
    ';
}

$config = new Config();
$usersTableName = $config->get("users_table_name");

$content .= '<h3>Toutes les ventes</h3>';

$achats = $_SQL->prepare("SELECT `article_title`, `Email`,`stock_selling_price`,`stock_selling_date`, `stock_id`
    FROM `articles`
    JOIN `stock` USING(`article_id`)
    JOIN `$usersTableName` ON `$usersTableName`.`id` = `stock`.`user_id`
    WHERE `stock`.`site_id` = :site_id AND (`type_id` = '2' OR `type_id` = 11) ".$req.$reqPeople."
    GROUP BY `stock_id`
ORDER BY `stock_selling_date` DESC");
$achats->execute(array_merge($reqParams, $reqPeopleParams));

$content .= '<br />
    <table class="admin-table sortable">
        <thead>
            <tr class="cliquable">
                <th>Ref.</th>
                <th>Quand&nbsp;?</th>
                <th>Quoi&nbsp;?</th>
                <th>Qui&nbsp;?</th>
                <th>Combien&nbsp;?</th>
            </tr>
        </thead>
        <tbody>
';

//$customers = array('0' => NULL);
while ($a = $achats->fetch(PDO::FETCH_ASSOC)) {
    $content .= '
        <tr>
            <td>' . $a["stock_id"] . '</td>
            <td>' . _date($a['stock_selling_date'], "j/m/Y") . '</td>
            <td>' . $a['article_title'] . '</td>
            <td>' . $a['Email'] . '</td>
            <td class="right">' . price($a['stock_selling_price'], 'EUR') . '</td>
        </tr>
    ';
    $customers[] = $a["Email"] . ', ';
}

$content .= '</tbody></table>';

if (!empty($customers)) {
    $customers = array_unique($customers);
    $count = count($customers);
    $content .= '<h3>Tous les clients (' . $count . ')</h3><p>';
    foreach ($customers as $c) {
        $content .= $c;
    }
    $content .= '</p>';
}

return new Response($content);
