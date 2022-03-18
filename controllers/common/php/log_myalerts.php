<?php

use Biblys\Service\CurrentSite;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/** @var CurrentSite $currentSite */
if (!$currentSite->hasOptionEnabled("alerts")) {
    throw new ResourceNotFoundException("Alerts are not enabled for this site");
}

$am = new AlertManager();

$result = array();

/** @var Request $request */
if ($request->getMethod() === "POST") {
    $body = $request->getContent();
    $params = json_decode($body, true);
    /** @var Visitor $_V */
    if (!$_V->hasAlert($params["article_id"])) {
        $alert = $am->create();

        $alert->set('user_id', $_V->get('id'));
        $alert->set('article_id', $params["article_id"]);

        $am->update($alert);
        $result['created'] = 1;
    } else {
        $alert = $am->get(array('user_id' => $_V->get('id'), 'article_id' => $params["article_id"]));

        $am->delete($alert);
        $result['deleted'] = 1;
    }

    return new JsonResponse($result);
}

$table = null;

$_PAGE_TITLE = 'Mes alertes Biblys';

$alertToDeleteId = $request->query->get('del');
if ($alertToDeleteId) {
    $alert = $am->getById($alertToDeleteId);
    if ($alert) {
        $am->delete($alert);
    }
    redirect('/pages/log_myalerts?deleted=1');
}

$user_id = $_V->get('id');

$_SQL->query("SET SESSION sql_mode=''")->execute();
$sql = $_SQL->prepare("
    SELECT `articles`.`article_id`, `article_title`, `article_url`, `article_authors`, `article_collection`, `article_number`, `article_publisher`,
    `alert_id`, `alert_pub_year`, `alert_max_price`, `alert_condition`,
    `stock_id`
    FROM `alerts`
    JOIN `articles` USING(`article_id`)
    LEFT JOIN `stock` 
        ON `stock`.`article_id` = `articles`.`article_id` 
            AND `stock`.`site_id` = :site_id 
            AND `stock_selling_date` IS NULL 
            AND `stock_return_date` IS NULL 
            AND `stock_lost_date` IS NULL
    WHERE `alerts`.`user_id` = :user_id
    GROUP BY `alert_id`
    ORDER BY `alert_id`, `stock_purchase_date`
");
$sql->execute(['user_id' => $user_id, 'site_id' => $site->get('id')]);

while ($a = $sql->fetch(PDO::FETCH_ASSOC)) {
    if ($a["alert_max_price"]) {
        $a["price"] = price($a["alert_max_price"], 'EUR');
    } else {
        $a['price'] = null;
    }
    if ($a["stock_id"]) {
        $a["led"] = '<img src="/common/img/square_green.png" alt="Disponible" title="Disponible !" />';
    } else {
        $a["led"] = '<img src="/common/img/square_red.png" alt="inisponible" />';
    }

    $table .= '
        <tr>
            <td class="center">'.$a["led"].'</td>
            <td><a href="/'.$a["article_url"].'">'.$a["article_title"].'</a><br />'.authors($a["article_authors"]).'<br />'.$a["article_collection"].numero($a["article_number"]).' ('.$a["article_publisher"].')</td>
            <td class="right">'.$a["alert_pub_year"].'</td>
            <td class="right">'.$a["price"].'</td>
            <td class="center">'.$a["alert_condition"].'</td>
            <td class="center"><a href="?del='.$a["alert_id"].'"><img src="/common/icons/delete_16.png" alt="Supprimer" title="Supprimer cette alerte" data-confirm="Voulez-vous vraiment SUPPRIMER cette alerte ?" /></a></td>
        </tr>
    ';
    $current_article = $a["article_id"];
}

$_ECHO .= '

    <h2><img src="/common/icons/alert_32.png" /> '.$_PAGE_TITLE.'</h2>

    <h4>Ci-dessous, la liste des alertes que vous avez cr&eacute;&eacute;es.</h4>
    <p>Vous recevrez un courriel d&egrave;s qu\'ils seront disponibles aux conditions que vous avez indiqu&eacute;es dans une des librairies Biblys.</p>
';

if (isset($_GET["deleted"])) {
    $_ECHO .= '<p class="success">L\'alerte pour ce livre a bien été supprimée !</p>';
}

$_ECHO .= '
    <table class="list">
        <thead>
            <tr>
                <th></th>
                <th>Livre</th>
                <th>Ann&eacute;e</th>
                <th>Prix max.</th>
                <th>&Eacute;tat</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            '.$table.'
        </tbody>
    </table>

';
