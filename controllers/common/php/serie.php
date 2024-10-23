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


use Biblys\Legacy\LegacyCodeHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$cycleSlug = LegacyCodeHelper::getRouteParam("slug");

$cm = new CycleManager();
$cycle = $cm->get(["cycle_url" => $cycleSlug]);

if (!$cycle) {
    throw new ResourceNotFoundException(
        sprintf("Cannot find a cycle for url %s", htmlentities($cycleSlug))
    );
}

$useOldController = $globalSite->getOpt('use_old_cycle_controller');
if (!$useOldController) {
    return new RedirectResponse("/cycle/".$cycle->get("url"), 301);
}

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('La série &laquo; '.$cycle->get('name').' &raquo;');

$_ECHO .= '<h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>';

if ($cycle->has('desc')) {
    $_ECHO .= '<p>'.$cycle->get('desc').'</p>';
}

if (LegacyCodeHelper::getGlobalVisitor()->isAdmin()) {
    $_ECHO .= '
        <div class="admin">
            <p>Cycle n&deg; '.$cycle->get('id').'</p>
            <p><a href="/pages/adm_cycle?id='.$cycle->get('id').'">modifier</a></p>
        </div>
    ';
}

$_REQ = "`cycle_id` = '".$cycle->get('id')."'";

$path = get_controller_path('_list');
include $path;
