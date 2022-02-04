<?php

use Biblys\Service\CurrentSite;
use Symfony\Component\HttpFoundation\Response;

$_PAGE_TITLE = 'Livres les plus recherchés';

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
                <a href="/'.$a["article_url"].'">'.$a["article_title"].'</a><br>
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
    <h1><span class="fa fa-bell"></span> '.$_PAGE_TITLE.'</h1>

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
