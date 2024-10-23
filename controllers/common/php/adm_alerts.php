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


use Biblys\Service\CurrentSite;
use Symfony\Component\HttpFoundation\Response;

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Livres les plus recherchés');

/** @var PDO $_SQL */
$alerts = $_SQL->query("SELECT COUNT(`alert_id`) AS `num`, `article_title`, `article_url`, `article_authors`, `article_collection`, `article_number`, `article_publisher`
    FROM `alerts`
    JOIN `articles` USING(`article_id`)
    GROUP BY `article_id`
    ORDER BY COUNT(`alert_id`) DESC");

$table = null;
while($a = $alerts->fetch()) {
    $table .= '
        <tr>
            <td>
                <a href="/a/'.$a["article_url"].'">'.$a["article_title"].'</a><br>
                '.authors($a["article_authors"]).'
            </td>
            <td>'.$a["article_collection"].numero($a["article_number"]).'</td>
            <td class="center">'.$a["num"].'</td>
        </tr>
    ';
}

$disabledAlertsWarning = null;
/** @var CurrentSite $currentSite */
if (!$currentSite->hasOptionEnabled("alerts")) {
    $disabledAlertsWarning = '
        <div class="alert alert-warning">
            <span class="fa fa-exclamation-triangle"></span>
            <strong>Les envois d\'alertes ne sont pas activés.</strong><br />
            Aucun e-mail ne sera envoyé d\'alerte ne sera envoyé lors de l\'ajout d\'exemplaires au stock.
        </div>
    ';
}

$content = '
    <h1><span class="fa fa-bell"></span> '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>

    '.$disabledAlertsWarning.'

    <table class="admin-table">
        <thead>
            <tr>
                <th>Livre</th>
                <th>Collection</th>
                <th>Alertes</th>
            </tr>
        </thead>
        <tbody>
            '.$table.'
        </tbody>
    </table>
';

return new Response($content);
