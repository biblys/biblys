<?php /** @noinspection PhpUnhandledExceptionInspection */
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
use Biblys\Service\TemplateService;
use Model\PublisherQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function(
    CurrentUser $currentUser,
    CurrentSite $currentSite,
    Request $request,
    UrlGenerator $urlGenerator,
    TemplateService $templateService,
): Response|RedirectResponse
{
    if (!$currentUser->isAdmin() && !$currentUser->hasPublisherRight()) {
        throw new AccessDeniedHttpException("Page réservée aux éditeurs.");
    }

    $publisherId = $currentUser->getCurrentRight()?->getPublisherId();
    $publisher = PublisherQuery::create()->findPk($publisherId);
    if ($publisher && !$currentSite->allowsPublisher($publisher)) {
        throw new AccessDeniedHttpException(
            "Votre maison d'édition {$publisher->getName()} n'est pas autorisée sur ce site."
        );
    }

    $items = array();

    $request->attributes->set("page_title", "Tableau de bord");

    /* ITEMS */

    // Publisher
    if ($currentUser->hasPublisherRight()) {
        $right = $currentUser->getCurrentRight();
        $publisherId = $right->getPublisherId();
        $pm = new PublisherManager();
        $publisher = $pm->getById($publisherId);
        if ($publisher) {
            $items["Éditeur"][] = array('Fiche d\'identité', '/pages/publisher_edit', 'fa-list-alt');

            $catalogUrl = $urlGenerator->generate("article_admin_catalog");
            $items["Bibliographie"][] = array('Catalogue', $catalogUrl, 'fa-list');
            $collectionUrl = $urlGenerator->generate("collection_admin");
            $items["Bibliographie"][] = array("Collections", $collectionUrl, "fa-th-list");
            $items["Bibliographie"][] = array('Créer un nouveau livre', '/pages/article_edit', 'fa-book');

            // L'Autre Livre
            if ($currentSite->getId() == 11) {
                $postsAdminUrl = $urlGenerator->generate("posts_admin");
                $items['Contenu'][] = array('Billets', $postsAdminUrl, 'fa-newspaper');
                $items['Contenu'][] = array('Évènements', '/pages/log_events_admin', 'fa-calendar');
                $items['Contenu'][] = array('Dédicaces', '/pages/log_signings_admin', 'fa-pencil');

                $items['Assistance'][] = array('Mode d\'emploi', '/pages/doc_adherents');
            }
        }
    }

    // Biblys
    $items["Assistance"][] = array("Documentation", 'https://documentation.lautrelivre.fr/');
    $items["Assistance"][] = array('Documentation Biblys', 'https://docs.biblys.fr/');
    $items["Assistance"][] = array('Besoin d\'aide ?', 'https://www.biblys.fr/contact/');

    // Sections
    $sections = NULL;
    foreach ($items as $k => $v) {
        $sections .= '<section>
        <h3>' . $k . '</h3>
    ';
        foreach ($v as $i) {
            $i["title"] = $i[0];
            $i["link"] = $i[1];
            $i['class'] = null;
            $i['icon_path'] = '/common/icons/' . str_replace("/pages/", "", $i["link"]) . '.svg';
            if (isset($i[2]) && strstr($i[2], 'fa-')) $i['icon_link'] = '<span class="fa ' . $i[2] . '"></i>';
            else $i['icon_link'] = NULL;
            $sections .= '
        <p>
          ' . $i['icon_link'] . '
          <a href="' . $i["link"] . '"' . $i["class"] . '>' . $i["title"] . '</a>
        </p>
      ';
        }
        $sections .= '</section>';
    }


    $templateCode = '        
        <h1><span class="fa fa-dashboard"></span> Tableau de bord</h1>
    
        <div class="dashboard">
          {{sections|raw}}
        </div>
  ';

    return $templateService->renderResponseFromString($templateCode, [
        "sections" => $sections,
    ], isPrivate: true);
};
