<?php /** @noinspection SqlCheckUsingColumns */
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


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @throws InvalidDateFormatException
 */
return function(Request $request): Response
{
    $request->attributes->set("page_title", "Ventes numériques");

    $ao = array();
    $articles = EntityManager::prepareAndExecute("SELECT `article_id`, `article_title`, `article_collection` FROM `articles`
        WHERE `type_id` IN (2, 11) ORDER BY `article_collection`, `article_title_alphabetic`",
        []
    );
    while ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
        $ao[$a["article_collection"]][] = '<option value="' . $a["article_id"] . '"' . (isset($_GET["article_id"]) && $_GET["article_id"] == $a["article_id"] ? ' selected' : null) . '>' . $a["article_title"] . '</option>';
    }

    $article_options = NULL;
    foreach ($ao as $c => $a) {
        $article_options .= '<optgroup label="' . $c . '">' . implode($a) . '</optgroup>';
    }

    // People select
    $people_options = NULL;
    $people = EntityManager::prepareAndExecute("
        SELECT `people_id`, `people_name` 
        FROM `articles` 
        JOIN `roles` USING(`article_id`) 
        JOIN `people` USING(`people_id`) 
        JOIN `collections` USING(`collection_id`) 
        WHERE `type_id` = 2 AND `job_id` = 1 
        GROUP BY `people_id` 
        ORDER BY MAX(`people_last_name`)
    ", []);
    while ($p = $people->fetch(PDO::FETCH_ASSOC)) {
        $people_options .= '<option value="' . $p["people_id"] . '">' . $p["people_name"] . '</option>';
    }

    $req = NULL;
    $reqPeople = null;
    $reqParams = [];
    $reqPeopleParams = [];

    $firstDayOfCurrentMonth = date("Y-m-01");
    $lastDayOfCurrentMonth = date("Y-m-t");

    $startDate = $request->query->get("date1", $firstDayOfCurrentMonth);
    $endDate = $request->query->get("date2", $lastDayOfCurrentMonth);

    $req .= " AND `stock_selling_date` >= :date1 AND `stock_selling_date` <= :date2";
    $reqParams["date1"] = "$startDate 00:00:00";
    $reqParams["date2"] = "$endDate 23:59:59";

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
        (`type_id` = 2 OR `type_id` = 11) 
        AND (`stock`.`axys_account_id` IS NOT NULL OR `stock`.`user_id` IS NOT NULL)
        " . $req . $reqPeople . "
    GROUP BY `article_id`
    ORDER BY `article_authors_alphabetic` ", array_merge($reqParams, $reqPeopleParams));

    $tbody = NULL;
    $Ventes = null;
    $Total = null;
    $Gratuits = null;
    $subReqParams = $reqParams;
    while ($l = $ventes->fetch(PDO::FETCH_ASSOC)) {

        if (!empty($_GET["people_id"])) {
            $request->attributes->set("page_title", "Ventes numériques : " . authors($l["article_authors"]));
        }

        if (!empty($_GET["article_id"])) {
            $request->attributes->set("page_title", "Ventes numériques : {$l["article_title"]}");
        }

        $subReqParams['article_id'] = $l['article_id'];

        $numVentes = EntityManager::prepareAndExecute("
            SELECT COUNT(`stock_id`) AS `ventes`, SUM(`stock_selling_price`) AS `ca`, SUM(`stock_selling_price_ht`) AS `ca_ht` 
            FROM `stock` 
            WHERE `article_id` = :article_id 
                AND `stock_selling_price` != '0' 
                AND `stock_selling_date` " . $req . " 
                AND (`stock`.`axys_account_id` IS NOT NULL OR `stock`.`user_id` IS NOT NULL)
            ", $subReqParams);
    $v = $numVentes->fetch(PDO::FETCH_ASSOC);

        $numGratuits = EntityManager::prepareAndExecute(
            "SELECT COUNT(`stock_id`) AS `gratuits` FROM `stock` WHERE `article_id` = :article_id AND `stock_selling_price` IS NULL " . $req,
            $subReqParams);
        $g = $numGratuits->fetch(PDO::FETCH_ASSOC);

        $tbody .= '
        <tr>
            <td>' . authors($l["article_authors"]) . '</td>
            <td><a href="/a/' . $l["article_url"] . '">' . $l["article_title"] . '</a></td>
            <td class="right">' . $v["ventes"] . '</td>
            <td class="right">' . $g["gratuits"] . '</td>
            <td class="right">' . price($v["ca"], 'EUR') . '</td>
        </tr>
    ';
        $Total += $v["ca"];
        $Ventes += $v["ventes"];
        $Gratuits += $g["gratuits"];
    }

    $content = '<h1><span class="fa fa-book"></span> Ventes numériques</h1>';

    $content .= '
        <form class="fieldset" role="form">
            <fieldset>
                <legend>Filtrer les ventes</legend>

                <div class="form-group row">
                    <label for="people_id" class="col-sm-3 col-form-label text-right">Par auteur :</label>
                    <div class="col-sm-9">
                        <select name="people_id" id="people_id" class="form-control">
                            <option value="0">Tous</option>
                            ' . $people_options . '
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="article_id" class="col-sm-3 col-form-label text-right">Par titre :</label>
                    <div class="col-sm-9">
                        <select name="article_id" id="article_id" class="form-control">
                            <option value="0">Tous</option>
                            ' . $article_options . '
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date1" class="col-sm-3 col-form-label text-right">Du :</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="date1" name="date1" value="' . ($startDate ?? null) . '">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date2" class="col-sm-3 col-form-label text-right">Au :</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="date2" name="date2" value="' . ($endDate ?? null) . '">
                    </div>
                </div>
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Filtrer les ventes</button>                
                </div>
            </fieldset>
        </form>
        <br />

        <h3>Par titre</h3>
        <br />
    
        <table class="table table-striped">
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
        $content .= '<h3>Statistiques de téléchargements</h3>';

        $downloads = EntityManager::prepareAndExecute(
            "SELECT `download_filetype`, `download_version` FROM `downloads` WHERE `article_id` = :article_id",
            ['article_id' => $articleId]
        );
        $total = $downloads->rowCount();

        $content .= '
            <table class="table table-striped">
                <tr>
                    <td class="right">Total :</td>
                    <td class="right">' . $total . '</td>
                    <td class="right">100 %</td>
                </tr>
            </table>
        ';
    }

    $content .= '<h3>Toutes les ventes</h3>';

    $achats = EntityManager::prepareAndExecute("SELECT 
        `article_title`, 
        `users`.`email`,
        `stock`.`axys_account_id`,
        `stock_selling_price`,`stock_selling_date`, `stock_id`
        FROM `articles`
        JOIN `stock` USING(`article_id`)
        LEFT JOIN `users` ON `users`.`id` = `stock`.`user_id`
        WHERE `type_id` IN (2, 11) " . $req . $reqPeople . "
        GROUP BY `stock_id`
        ORDER BY `stock_selling_date` DESC",
        array_merge($reqParams, $reqPeopleParams)
    );

    $content .= '<br />
        <table class="table table-striped">
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

    while ($a = $achats->fetch(PDO::FETCH_ASSOC)) {
        $userIdentity = $a["email"] ?: "Utilisateur Axys n°" . $a["axys_account_id"];

        $content .= "
            <tr>
                <td>" . $a["stock_id"] . "</td>
                <td>" . _date($a['stock_selling_date'], "j/m/Y") . "</td>
                <td>" . $a['article_title'] . "</td>
                <td>" . $userIdentity . "</td>
                <td class=\"right\">" . price($a['stock_selling_price'], 'EUR') . "</td>
            </tr>
        ";
    }

    $content .= '</tbody></table>';

    return new Response($content);
};
