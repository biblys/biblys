<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$cycleSlug = $request->query->get("url");

$cm = new CycleManager();
$cycle = $cm->get(["cycle_url" => $cycleSlug]);

if (!$cycle) {
    throw new ResourceNotFoundException(
        sprintf("Cannot find a cycle for url %s", htmlentities($cycleSlug))
    );
}

$useOldController = $_SITE->getOpt('use_old_cycle_controller');
if (!$useOldController) {
    return new RedirectResponse("/cycle/".$cycle->get("url"), 301);
}

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('La série &laquo; '.$cycle->get('name').' &raquo;');

$_ECHO .= '<h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>';

if ($cycle->has('desc')) {
    $_ECHO .= '<p>'.$cycle->get('desc').'</p>';
}

if (getLegacyVisitor()->isAdmin()) {
    $_ECHO .= '
        <div class="admin">
            <p>Cycle n&deg; '.$cycle->get('id').'</p>
            <p><a href="/pages/adm_cycle?id='.$cycle->get('id').'">modifier</a></p>
        </div>
    ';
}

$_REQ = "`cycle_id` = '".$cycle->get('id')."'";

// Trier par tome
$defaultOrderBy = 'article_tome';
$defaultSortOrder = 0;
$_REQ_ORDER = 'ORDER BY CAST(`article_tome` AS SIGNED), `article_pubdate`';

$path = get_controller_path('_list');
include $path;
