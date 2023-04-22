<?php

use Biblys\Service\Browser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


if (!getLegacyVisitor()->isAdmin() && !getLegacyVisitor()->isPublisher()) {
    throw new AccessDeniedHttpException("Page réservée aux éditeurs.");
}

/** @var Site $site */
$publisherId = getLegacyVisitor()->getCurrentRight()->get("publisher_id");
if (!$site->allowsPublisherWithId($publisherId)) {
    $pm = new PublisherManager();
    throw new AccessDeniedHttpException("Votre maison d'édition n'est pas autorisée sur ce site.");
}

if (!getLegacyVisitor()->isAdmin() && !getLegacyVisitor()->isPublisher() && !getLegacyVisitor()->isBookshop() && !getLegacyVisitor()->isLibrary()) {
    trigger_error('Accès non autorisé pour ' . getLegacyVisitor()->get('user_email'));
}

$items = array();
$sections = array();

// Check browser version
$browser = new Browser();
if ($browser->isUpToDate()) $browser_alert = null;
else $browser_alert = $browser->getUpdateAlert();

$_PAGE_TITLE = 'Tableau de bord';
$_PAGE_TITLE_HTML = 'Tableau de bord';

/* USER RIGHTS */

$rm = new RightManager();

// Get current user right
$right = getLegacyVisitor()->getCurrentRight();

// Change selected user right
if (isset($_GET['right_id'])) {
    $new_right = $rm->get(array('right_id' => $_GET['right_id'], 'user_id' => getLegacyVisitor()->get('id')));
    getLegacyVisitor()->setCurrentRight($new_right);
    return new RedirectResponse('/pages/log_dashboard');
}

// Show user rights option
$rights = $rm->getAll(
    array('user_id' => getLegacyVisitor()->get('id'))
);

$rights_optgroup = array();
foreach ($rights as $r) {
    $mode = null;
    $label = null;
    if ($r->has('publisher')) {
        $mode = 'Éditeur';
        $label = $r->get('publisher')->get('name');
    } elseif ($r->has('bookshop')) {
        $mode = 'Librairie';
        $label = $r->get('bookshop')->get('name');
    } elseif ($r->has('library')) {
        $mode = 'Bibliothèque';
        $label = $r->get('library')->get('name');
    } elseif ($r->has('site_id')) {
        if ($r->get('site_id') == $_SITE['site_id']) {
            $mode = 'Administrateur';
            $label = $_SITE['site_title'];
        } else continue;
    } else continue;
    $rights_optgroup[$mode][] = '<option' . ($r->has('current') ? ' selected' : null) . ' value="/pages/log_dashboard?right_id=' . $r->get('id') . '">' . $label . '</option>';
}

$rights_optgroups = null;
foreach ($rights_optgroup as $k => $v) {
    $rights_optgroups .= '<optgroup label="' . $k . '">' . implode($v) . '</optgroup>';
}

$rights_select = '<p class="floatR">En tant que : &nbsp;<select class="goto">'
    . $rights_optgroups
    . '</select></p>';

/* ITEMS */

// Publisher
if (getLegacyVisitor()->isPublisher()) {
    $publisherId = $right->get('publisher_id');
    $pm = new PublisherManager();
    $publisher = $pm->getById($publisherId);
    if ($publisher) {
        $items["Éditeur"][] = array('Fiche d\'identité', '/pages/publisher_edit', 'fa-list-alt');

        $items["Bibliographie"][] = array('Catalogue', '/pages/log_articles', 'fa-books');
        $items["Bibliographie"][] = array('Créer un nouveau livre', '/pages/log_article', 'fa-book');

        // L'Autre Livre
        if ($_SITE['site_id'] == 11) {
            $items['Contenu'][] = array('Billets', '/pages/pub_posts', 'fa-newspaper-o');
            $items['Contenu'][] = array('Évènements', '/pages/log_events_admin', 'fa-calendar');
            $items['Contenu'][] = array('Dédicaces', '/pages/log_signings_admin', 'fa-pencil');

            $items['Assistance'][] = array('Mode d\'emploi', '/pages/doc_adherents');
        }
    }
}

// Bookshop
if (getLegacyVisitor()->isBookshop()) {
    $bookshop = $_SQL->query('SELECT `bookshop_name` FROM `bookshops` WHERE `bookshop_id` = ' . $right->get('bookshop')->get('id'));
    if ($b = $bookshop->fetch(PDO::FETCH_ASSOC)) {
        $items["Librairie"][] = array('Fiche d\'identité', '/pages/bookshop_edit', 'fa-list-alt');
        $items["Librairie"][] = array('Évènements', '/pages/log_events_admin', 'fa-calendar');
    }
}

// Library
if (getLegacyVisitor()->isLibrary()) {
    $library = $_SQL->query('SELECT `library_name` FROM `libraries` WHERE `library_id` = ' . $right->get('library')->get('id'));
    if ($b = $library->fetch(PDO::FETCH_ASSOC)) {
//			$_PAGE_TITLE_HTML .= ' '.$b['library_name'];
        $items["Bibliothèque"][] = array('Fiche d\'identité', '/pages/library_edit', 'fa-list-alt');
        $items["Bibliothèque"][] = array('Évènements', '/pages/log_events_admin', 'fa-calendar');

        // LVDI
        if ($_SITE['site_id'] == 16) $items['Assistance'][] = array('Mode d\'emploi', '/pages/doc_partenaires');
    }
}

// Biblys
$items["Assistance"][] = array('Documentation', 'http://www.biblys.fr/pages/doc_index');
$items["Assistance"][] = array('Besoin d\'aide ?', 'http://nokto.net/contact');

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
        $i['icon_link'] = null;
//			if(!empty($i[2])) $i["class"] = ' class="'.$i[2].'"'; else $i['class'] = NULL;
        if (isset($i[2]) && strstr($i[2], 'fa-')) $i['icon_link'] = '<i class="fa ' . $i[2] . '"></i>';
        elseif (file_exists(biblysPath() . '/sites' . $i["icon_path"])) $i['icon_link'] = '<a href="' . $i["link"] . '"' . $i["class"] . '><img src="' . $i['icon_path'] . '" style="vertical-align: middle;" width=16 height=16></a>';
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


$content = '
        ' . $rights_select . '
    <h1><i class="fa fa-dashboard"></i> ' . $_PAGE_TITLE_HTML . '</h1>

    ' . $browser_alert . '

    <div class="dashboard">
      ' . $sections . '
    </div>
  ';

return new Response($content);













