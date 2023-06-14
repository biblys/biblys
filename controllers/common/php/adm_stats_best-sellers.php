<?php

use Biblys\Legacy\LegacyCodeHelper;

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Best-sellers');

$list = null;
$query = null;

// Fitrer par année
if (isset($_GET["year"]) && !empty($_GET['year'])) $query .= " AND `stock_selling_date` LIKE '".$_GET["year"]."%'";
else $query .= " AND `stock_selling_date` IS NOT null ";

// Fitrer par année
if (isset($_GET["type"]) && !empty($_GET['type'])) $query .= " AND `type_id` = ".$_GET["type"]."";
//else $query .= " AND `stock_selling_date` IS NOT null ";

$query = "SELECT `article_title`, `article_url`, GROUP_CONCAT(DISTINCT `article_id`) AS `ids`, COUNT(`stock_id`) AS `Ventes`, SUM(`stock_selling_price`) AS `CA`, GROUP_CONCAT(DISTINCT `article_publisher` SEPARATOR ', ') AS `publishers`
    FROM `stock`
    JOIN `articles` USING(`article_id`)
    WHERE `stock`.`site_id` = ". LegacyCodeHelper::getLegacyCurrentSite()["site_id"]." AND `stock_selling_price` != 0 ".$query."
    GROUP BY `article_item`, IF (`article_item` IS null, `article_id`, null)
    HAVING COUNT(`stock_id`) >= 3
    ORDER BY `Ventes` DESC, `CA`";

$sql = $_SQL->query($query);

$i = 0; $total = 0;
while ($x = $sql->fetch(PDO::FETCH_ASSOC)) {
    $i++;
    if (LegacyCodeHelper::getLegacyCurrentSite()['site_tva']) $x["CA"] = $x["CA"] / 1.055;
    $list .= '
            <tr>
                <td class="right">'.$i.'.</td>
                <td><a href="/'.$x["article_url"].'">'.$x["article_title"].'</a></td>
                <td>'.$x["publishers"].'</td>
                <td class="right">'.$x["Ventes"].'</td>
                <td sorttable_customkey="'.$x["CA"].'" class="right">'.price($x["CA"],'EUR').'</td>
            </tr>
        ';
    $total += $x["CA"];
}

$years = null;
for($y = date('Y'); $y >= 2010; $y--) {
    if (isset($_GET['year']) && $y == $_GET["year"]) $sel = 'selected';
    else $sel = null;
    $years .= '<option value="'.$y.'" '.$sel.'>'.$y.'</option>';
}

// Types d'articles
$type_options = Biblys\Article\Type::getOptions($request->query->get('type'));

if (LegacyCodeHelper::getLegacyCurrentSite()['site_tva']) $HT = 'HT'; else $HT = 'TTC';

$_ECHO .= '

        <h1><span class="fa fa-sort-amount-desc"></span> '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>

        <form class="fieldset">
            <fieldset>
                <legend>Filtres</legend>

                <p>
                    <label for="year">Année :</label>
                    <select name="year" id="year">
                        <option value="0">Cumul</option>
                        '.$years.'
                    </select>
                </p>

                <p>
                    <label for="type">Type :</label>
                    <select name="type" id="type">
                        <option value="0">Tous</option>
                        '.join($type_options).'
                    </select>
                </p>

                <p class="center">
                    <button>Afficher</button>
                </p>

            </fieldset>
        </form>

        <!--p>Livres vendus &agrave trois exemplaires ou plus</p-->

        <table class="sortable admin-table">
            <thead class="pointer">
                <tr>
                    <th></th>
                    <th>Titre</th>
                    <th>Collections</th>
                    <th>Ventes</th>
                    <th>CA '.$HT.'</th>
                </tr>
            </thead>
            <tbody>
                '.$list.'
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=2></th>
                    <th colspan=2>Total '.$HT.' :</th>
                    <th>'.price($total,'EUR').'</th>
                </tr>
            </tfoot>
        </table>
    ';

?>
