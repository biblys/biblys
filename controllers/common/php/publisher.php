<?php

use Biblys\Legacy\LegacyCodeHelper;

$pm = new PublisherManager();

$publisher = $pm->get(['publisher_url' => $request->query->get('url')]);

if (!$publisher) {

    $_ECHO = e404();

} else {

    $p = $publisher;

    $use_old_controller = $_SITE->getOpt('use_old_publisher_controller');
    if (!$use_old_controller) {
        redirect('/editeur/'.$publisher->get('url'));
    }

    // Administration
    if (getLegacyVisitor()->isAdmin()) {
        $_ECHO .= '
            <div class="admin">
                <p>&Eacute;diteur n&deg; '.$publisher->get('id').'</p>
                <p><a href="'.$urlgenerator->generate('publisher_edit', ['id' => $publisher->get('id')]).'">modifier</a></p>
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
    if($p["publisher_order_by"] == "article_pubdate") $_REQ_ORDER = 'ORDER BY `article_pubdate` DESC';
    else  $_REQ_ORDER = 'ORDER BY `'.$p["publisher_order_by"].'`';

    $path = get_controller_path('_list');
    include($path);

    $_OPENGRAPH = '
        <meta property="og:type" content="website"/>
        <meta property="og:title" content="'.$p["publisher_name"].'"/>
        <meta property="og:url" content="http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'"/>
        <meta property="og:description" content="'.strip_tags(truncate(strip_tags($p["publisher_desc"]),'500','...',true)).'"/>
        <meta property="og:locale" content="fr_FR"/>
        <meta property="og:site_name" content="'. LegacyCodeHelper::getLegacyCurrentSite()["site_title"].'"/>
    ';
}
