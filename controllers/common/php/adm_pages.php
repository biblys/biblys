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


/** @var Request $request */
/** @var UrlGenerator $urlGenerator */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

$request->attributes->set("page_title", "Gestion des pages");

$content = null;

$content .= '
    <h1><span class="fa fa-file"></span> Gestion des pages</h1>

    <p class="buttonset"><a href="/pages/adm_page" class="btn btn-primary"><i class="fa fa-file"></i> Nouvelle page</a></p>

    <table class="table">

        <tr>
        <th></th>
        <th>Titre</th>
        <th></th>
        </tr>
';

$pm = new PageManager();
$pages = $pm->getAll();
foreach ($pages as $page) {
    $p = $page;
    if($p["page_status"] == 1) $p["status"] = '<span class="fa fa-square led-green"></span>';
    else $p["status"] = '<span class="fa fa-square led-red"></span>';
    $staticPageUrl = $urlGenerator->generate("static_page_show", ["slug" => $page->get("url")]);
    $content .= '
        <tr>
            <td>'.$p["status"].'</td>
            <td><a href="'.$staticPageUrl.'">'.$p["page_title"].'</a></td>
            <td class="right nowrap">
                <a href="/pages/adm_page?id='.$p["page_id"].'" class="button" title="Éditer"><i class="fa fa-edit fa-lg"></i></a>
                <a href="/pages/adm_page?del='.$p["page_id"].'" class="button" title="Supprimer" data-confirm="Êtes-vous sûr de vouloir supprimer la page '.$p["page_title"].' ?"><i class="fa fa-trash-can fa-lg"></i></a>
            </td>
        </tr>
    ';
}

$content .= '</table>';

return new Response($content);
