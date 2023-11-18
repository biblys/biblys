<?php

/** @noinspection HtmlUnknownTarget */

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$config = Config::load();
$currentSiteService = CurrentSite::buildFromConfig($config);
$currentSite = $currentSiteService->getSite();
/** @noinspection PhpUnhandledExceptionInspection */
if (!$currentSiteService->hasOptionEnabled("alerts")) {
    throw new ResourceNotFoundException("Alerts are not enabled for this site");
}

$am = new AlertManager();

$result = array();

/** @var Request $request */
if ($request->getMethod() === "POST") {
    $body = $request->getContent();
    $params = json_decode($body, true);
    
    if (!LegacyCodeHelper::getGlobalVisitor()->hasAlert($params["article_id"])) {
        /** @noinspection PhpUnhandledExceptionInspection */
        $alert = $am->create();

        $alert->set('axys_account_id', LegacyCodeHelper::getGlobalVisitor()->get('id'));
        $alert->set('article_id', $params["article_id"]);

        /** @noinspection PhpUnhandledExceptionInspection */
        $am->update($alert);
        $result['created'] = 1;
    } else {
        $alert = $am->get(array('axys_account_id' => LegacyCodeHelper::getGlobalVisitor()->get('id'), 'article_id' => $params["article_id"]));

        /** @noinspection PhpUnhandledExceptionInspection */
        $am->delete($alert);
        $result['deleted'] = 1;
    }

    return new JsonResponse($result);
}

$table = null;

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Mes alertes Biblys');

$content = null;

$alertToDeleteId = $request->query->get('del');
if ($alertToDeleteId) {
    $alert = $am->getById($alertToDeleteId);
    if ($alert) {
        /** @noinspection PhpUnhandledExceptionInspection */
        $am->delete($alert);
    }
    return new RedirectResponse('/pages/log_myalerts?deleted=1');
}

/** @noinspection PhpUnhandledExceptionInspection */
$currentUserService = CurrentUser::buildFromRequestAndConfig($request, $config);
/** @noinspection PhpUnhandledExceptionInspection */
$currentUser = $currentUserService->getAxysAccount();

/** @var PDO $_SQL */
$_SQL->query("SET SESSION sql_mode=''")->execute();
/** @noinspection SqlCheckUsingColumns */
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
    WHERE `alerts`.`axys_account_id` = :axys_account_id
    GROUP BY `alert_id`
    ORDER BY `alert_id`, `stock_purchase_date`
");
$sql->execute(['axys_account_id' => $currentUser->getId(), 'site_id' => $currentSite->getId()]);

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

$content .= '
    <h2>
        <span class="fa fa-bell-o"></span>
        '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'
    </h2>

    <p><strong>Ci-dessous, la liste des alertes que vous avez créées.</strong></p>
    <p>Vous recevrez un courriel dès qu\'ils seront disponibles aux conditions que vous avez indiquées dans une des librairies Biblys.</p>
';

if (isset($_GET["deleted"])) {
    $content .= '<p class="success">L\'alerte pour ce livre a bien été supprimée !</p>';
}

$content .= '
    <table class="list">
        <thead>
            <tr>
                <th></th>
                <th>Livre</th>
                <th>Année</th>
                <th>Prix max.</th>
                <th>État</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            '.$table.'
        </tbody>
    </table>
';

return new Response($content);
