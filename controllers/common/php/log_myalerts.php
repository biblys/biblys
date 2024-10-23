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
use Biblys\Service\CurrentUser;
use Model\AlertQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @throws Exception
 */
return function (
    CurrentSite $currentSiteService,
    CurrentUser $currentUserService,
    Request     $request,
): Response|JsonResponse|RedirectResponse {
    global $_SQL;

    if (!$currentSiteService->hasOptionEnabled("alerts")) {
        throw new ResourceNotFoundException("Alerts are not enabled for this site");
    }
    $currentSite = $currentSiteService->getSite();
    $currentUser = $currentUserService->getUser();

    $am = new AlertManager();

    $result = array();

    if ($request->getMethod() === "POST") {
        $body = $request->getContent();
        $params = json_decode($body, true);

        $alert = AlertQuery::create()
            ->filterBySite($currentSite)
            ->filterByUser($currentUser)
            ->filterByArticleId($params['article_id'])
            ->findOne();

        if($alert) {
            $alert->delete();
            $result['deleted'] = 1;
        } else {
            $alert = new \Model\Alert();
            $alert->setSite($currentSite);
            $alert->setUser($currentUser);
            $alert->setArticleId($params['article_id']);
            $alert->save();

            $result['created'] = 1;
        }

        return new JsonResponse($result);
    }

    $table = null;

    $request->attributes->set("page_title", "Mes alertes");
    $content = null;

    $alertToDeleteId = $request->query->get('del');
    if ($alertToDeleteId) {
        $alert = $am->getById($alertToDeleteId);
        if ($alert) {
            $am->delete($alert);
        }
        return new RedirectResponse('/pages/log_myalerts?deleted=1');
    }

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
    $sql->execute(['user_id' => $currentUser->getId(), 'site_id' => $currentSite->getId()]);

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
            <td class="center">' . $a["led"] . '</td>
            <td><a href="/' . $a["article_url"] . '">' . $a["article_title"] . '</a><br />' . authors($a["article_authors"]) . '<br />' . $a["article_collection"] . numero($a["article_number"]) . ' (' . $a["article_publisher"] . ')</td>
            <td class="right">' . $a["alert_pub_year"] . '</td>
            <td class="right">' . $a["price"] . '</td>
            <td class="center">' . $a["alert_condition"] . '</td>
            <td class="center"><a href="?del=' . $a["alert_id"] . '"><img src="/common/icons/delete_16.png" alt="Supprimer" title="Supprimer cette alerte" data-confirm="Voulez-vous vraiment SUPPRIMER cette alerte ?" /></a></td>
        </tr>
    ';

    }

    $content .= '
    <h2>
        <span class="fa fa-bell-o"></span>
        Mes alertes
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
            ' . $table . '
        </tbody>
    </table>
';

    return new Response($content);
};
