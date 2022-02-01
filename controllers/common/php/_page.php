<?php

use Symfony\Component\HttpFoundation\Response;

/** @var Page $page */
$_PAGE_TITLE = $page->get("title");
$errorText = null;
$content = null;

/** @var Visitor $_V */
if ($_V->isAdmin()) {
  $content .= '
    <div class="admin">
      <p>Page n&deg; '.$page->get('id').'</p>
      <p><a href="/pages/adm_page?id='.$page->get('id').'">modifier</a></p>
      <p><a href="/pages/adm_page?del='.$page->get('id').'" data-confirm="Voulez-vous vraiment SUPPRIMER la page '.$page->get('title').' ?">supprimer</a></p>
    </div>
  ';
}

if (!isset($_OPENGRAPH)) {
    /** @var Site $site */
    $_OPENGRAPH = '
    <meta property="og:title" content="'.$page->get('title').'">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://'.$site->get('domain').'/pages/'.$page->get('url').'">
    <meta property="og:description" content="'.truncate(strip_tags($page->get('content')), 500, '...', true).'">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="'.$site->get('title').'">
  ';

  // If page has content, check validity
  if ($page->has('content')) {
    $html = new DOMDocument();
    libxml_use_internal_errors(true);
    $html->loadHTML($page->get('content'));

    $errors = libxml_get_errors();
    if ($errors && $_V->isAdmin()) {
      foreach ($errors as $error) {
        $errorText .= '<li>'.$error->message.' (ligne '.$error->line.')</li>';
      }
    }

    $xpath = new DOMXPath($html);
    $src = $xpath->evaluate("string(//img/@src)");
    if (!empty($src)) {
      $_OPENGRAPH .= '<meta property="og:image" content="'.$src.'">';
    }
  }
}

if ($page->has('updated')) {
    $updated = $page->get('updated');
} else {
    $updated = $page->get('update');
}

if ($page->get('status') == 0) {
    $content .= '
        <p class="alert alert-warning">
            <span class="fa fa-warning"></span>
            Cette page est hors-ligne.
        </p>
    ';
}

if ($errorText) {
    $content = '
      <div class="alert alert-danger">
        Le code HTML de la page contient des erreurs !
        <ul>'.$errorText.'</ul>
      </div>
    ';
}

$content .= '
  <article id="'.$page->get('url').'" class="page">
    <h1 class="page-title">'.$page->get('title').'</h1>
    '.$page->get('content').'
    <footer class="page-footer">
      Page mise &agrave; jour le <time datetime="'._date($updated, 'Y-m-dTH:i:sZ').'">'._date($updated, 'j f Y').'</time>
    </footer>
  </article>
';

return new Response($content);
