<?php /** @noinspection PhpUnhandledExceptionInspection */

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Model\PublisherQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return function(
    CurrentUser $currentUser,
    CurrentSite $currentSite,
    Request $request,
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

            $items["Bibliographie"][] = array('Catalogue', '/pages/log_articles', 'fa-books');
            $items["Bibliographie"][] = array('Créer un nouveau livre', '/pages/article_edit', 'fa-book');

            // L'Autre Livre
            if ($currentSite->getId() == 11) {
                $items['Contenu'][] = array('Billets', '/pages/pub_posts', 'fa-newspaper-o');
                $items['Contenu'][] = array('Évènements', '/pages/log_events_admin', 'fa-calendar');
                $items['Contenu'][] = array('Dédicaces', '/pages/log_signings_admin', 'fa-pencil');

                $items['Assistance'][] = array('Mode d\'emploi', '/pages/doc_adherents');
            }
        }
    }

    // Biblys
    $items["Assistance"][] = array('Documentation', 'https://docs.biblys.fr/');
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
            if (isset($i[2]) && strstr($i[2], 'fa-')) $i['icon_link'] = '<i class="fa ' . $i[2] . '"></i>';
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
        <h1><i class="fa fa-dashboard"></i> Tableau de bord</h1>
    
        <div class="dashboard">
          {{sections|raw}}
        </div>
  ';

    return $templateService->renderFromString($templateCode, [
        "sections" => $sections,
    ]);
};
