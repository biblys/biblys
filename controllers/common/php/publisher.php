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

$pm = new PublisherManager();

$publisherSlug = LegacyCodeHelper::getRouteParam("url");
$publisher = $pm->get(['publisher_url' => $publisherSlug]);

if (!$publisher) {

    $_ECHO = e404();

} else {

    $p = $publisher;

    $use_old_controller = $globalSite->getOpt('use_old_publisher_controller');
    if (!$use_old_controller) {
        redirect('/editeur/'.$publisher->get('url'));
    }

    // Administration
    if (LegacyCodeHelper::getGlobalVisitor()->isAdmin()) {
        $_ECHO .= '
            <div class="admin">
                <p>Éditeur n&deg; '.$publisher->get('id').'</p>
                <p><a href="'.\Biblys\Legacy\LegacyCodeHelper::getGlobalUrlGenerator()->generate('publisher_edit', ['id' => $publisher->get('id')]).'">modifier</a></p>
                <p><a href="/pages/adm_stocks?publisher_id='.$publisher->get('id').'">stock</a></p>
            </div>
        ';
    }

    \Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle($p["publisher_name"]);

    $cm = new CollectionManager();
    $collections = $cm->getAll(['publisher_id' => $publisher->get('id')]);
    $colls = array_map(function($collection) {
        return '<li><a href="/collection/'.$collection->get('url').'">'.$collection->get('name').'</a></li>';
    }, $collections);
    if (count($colls)) $colls = '<h3>Collections</h3><ul>'.join($colls).'</ul>';

    $_ECHO .= '
        <article class="publisher">
            <h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>
            '.($publisher->has('desc') ? $publisher->get('desc') : null).'
        </article>
    '.$colls;

    // Catalogue
    $_REQ = "`articles`.`publisher_id` = '".$p["publisher_id"]."'";

    $path = get_controller_path('_list');
    include($path);

    $_OPENGRAPH = '
        <meta property="og:type" content="website"/>
        <meta property="og:title" content="'.$p["publisher_name"].'"/>
        <meta property="og:url" content="http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'"/>
        <meta property="og:description" content="'.strip_tags(truncate(strip_tags($p["publisher_desc"]),'500','...',true)).'"/>
        <meta property="og:locale" content="fr_FR"/>
        <meta property="og:site_name" content="'. LegacyCodeHelper::getGlobalSite()["site_title"].'"/>
    ';
}
