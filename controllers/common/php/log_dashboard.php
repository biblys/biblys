<?php /** @noinspection PhpUnhandledExceptionInspection */

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Browser;
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

    // Check browser version
    $browser = new Browser();
    if ($browser->isUpToDate()) $browser_alert = null;
    else $browser_alert = $browser->getUpdateAlert();

    $request->attributes->set("page_title", "Tableau de bord");

    /* USER RIGHTS */

    $rm = new RightManager();

    // Get current user right
    $right = $currentUser->getCurrentRight();

    // Change selected user right
    if (isset($_GET['right_id'])) {
        $new_right = $rm->get([
            'right_id' => $_GET['right_id'],
            'user_id' => $currentUser->getUser()->getId()
        ]);
        LegacyCodeHelper::getGlobalVisitor()->setCurrentRight($new_right);
        return new RedirectResponse('/pages/log_dashboard');
    }

    // Show user rights option_se
    $rights = $rm->getAll(['user_id' => $currentUser->getUser()->getId()]);

    $rights_optgroup = array();
    foreach ($rights as $r) {
        $label = null;
        if ($r->has('publisher')) {
            $mode = 'Éditeur';
            $label = $r->get('publisher')->get('name');
        } elseif ($r->has('site_id')) {
            if ($r->get('site_id') == $currentSite->getId()) {
                $mode = 'Administrateur';
                $label = $currentSite->getTitle();
            } else continue;
        } else continue;
        $rights_optgroup[$mode][] = '<option' . ($r->has('current') ? ' selected' : null) . ' value="/pages/log_dashboard?right_id=' . $r->get('id') . '">' . $label . '</option>';
    }

    $rights_optgroups = null;
    foreach ($rights_optgroup as $k => $v) {
        $rights_optgroups .= '<optgroup label="' . $k . '">' . implode($v) . '</optgroup>';
    }

    /* ITEMS */

    // Publisher
    if ($currentUser->hasPublisherRight()) {
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
    $items["Assistance"][] = array('Documentation', 'https://www.biblys.fr/pages/doc_index');
    $items["Assistance"][] = array('Besoin d\'aide ?', 'https://clemlatz.dev/contact/');

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
        <p class="floatR">
            En tant que : 
            &nbsp;
            <select class="goto">
                {{rights_optgroups|raw}}
            </select>
        </p>
        
        <h1><i class="fa fa-dashboard"></i> Tableau de bord</h1>
    
        {{browser_alert|raw}}
    
        <div class="dashboard">
          {{sections|raw}}
        </div>
  ';

    return $templateService->renderFromString($templateCode, [
        "rights_optgroups" => $rights_optgroups,
        "browser_alert" => $browser_alert,
        "sections" => $sections,
    ]);
};
