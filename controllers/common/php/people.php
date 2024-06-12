<?php

use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$pm = new PeopleManager();
$url = $request->query->get('url');
$people = $pm->get(['people_url' => $url]);
if (!$people) {
    throw new ResourceNotFoundException('No people found for url '.htmlentities($url));
}

$use_old_controller = $site->getOpt("use_old_people_controller");
if (!$use_old_controller) {
    redirect("/p/$url/");
}


$p = $people;

$_OPENGRAPH = '
    <meta property="og:title" content="'.$p["people_name"].'"/>
    <meta property="og:type" content="author"/>
    <meta property="og:url" content="http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'"/>
    <meta property="og:description" content="'.truncate($p["people_bio"], '500', '...', true).'"/>
    <meta property="og:locale" content="fr_FR"/>
    <meta property="og:site_name" content="'.$_SITE["site_name"].'"/>
';

if (media_exists('people', $p["people_id"])) {
    $_OPENGRAPH .= '<meta property="og:image" content="'.media_url('people', $p["people_id"]).'" />';
}

if (auth("admin")) {
    $_ECHO .= '
        <div class="admin">
            <p>Intervenant n° '.$p["people_id"].'</p>
            <p><a href="/pages/adm_people?id='.$p["people_id"].'">modifier</a></p>
        </div>
    ';
}

$_PAGE_TITLE = $p["people_name"];
$_ECHO .= '
    <h2>'.$_PAGE_TITLE.'</h2>
';

$p = $people;

if (auth("admin")) {
    $_ECHO .= '
        <div class="admin">
            <p>Intervenant n° '.$p["people_id"].'</p>
            <p><a href="/pages/adm_people?id='.$p["people_id"].'">modifier</a></p>
        </div>
    ';
}

// Aliases
$aliases = $pm->getAll(['people_pseudo' => $people->get('id')]);
$aliases = array_map(function ($alias) {
    return '<a href="/'.$alias->get('url').'/">'.$alias->get('name').'</a>';
}, $aliases);
if (count($aliases)) {
    $_ECHO .= '<p>Ses pseudonymes : '.join($aliases, ', ').'</p>';
}

// Pseudonyme de...
if ($people->has('pseudo')) {
    $realName = $pm->get(['people_id' => $people->get('pseudo')]);
    if ($realName) {
        $_ECHO .= '<p>(Pseudonyme de <a href="/'.$realName["people_url"].'/">'.$realName["people_name"].'</a>)</p>';
    }
}

// Linked post
$sql = $_SQL->query("SELECT * FROM `posts` JOIN `links` USING(`post_id`) WHERE `posts`.`site_id` = '".$_SITE["site_id"]."' AND `links`.`people_id` = '".$p["people_id"]."'");
$posts = $sql->fetchAll();

if ($posts) {
    $posts = array_map(function ($p) {
        return '<li><a href="/blog/'.$p["post_url"].'">'.$p["post_title"].'</a></li>';
    }, $posts);

    $_ECHO .= '<h3>À propos</h3><ul>'.implode($posts).'</ul>';
}

$flag = null;
if (!empty($p["people_nation"])) {
    $flag = '<img src="/common/flags/'.$p["people_nation"].'.png" alt="Nationalité '.$p["people_nation"].'" /> ';
}

$dates = null;
if (!empty($p["people_birth"]) and !empty($p["people_death"])) {
    $dates = $p["people_birth"].' - '.$p["people_death"];
} elseif (!empty($p["people_birth"])) {
    $dates = 'Né en '.$p["people_birth"];
}

$_ECHO .= '
    <div id="people" class="clearfix">
        <div id="people-photo">
';
if (media_exists('people', $p["people_id"])) {
    $_ECHO .= '<img src="'.media_url('people', $p["people_id"], "w200").'" alt="'.$p["people_name"].'" class="frame" />';
}
$_ECHO .= '<p class="center">'.$flag.' '.$dates.'</p>';
if (!empty($p["people_site"])) {
    $_ECHO .= '<p><a href="'.$p["people_site"].'" rel="nofollow" target="_blank">Site officiel</a></p>';
}

$_ECHO .= '
        </div>
';

$_ECHO .= '<div id="people-bio">'.$p["people_bio"].'</div>';

// Linked post
$sql = $_SQL->query("SELECT * FROM `posts` JOIN `links` USING(`post_id`) WHERE `posts`.`site_id` = '".$_SITE["site_id"]."' AND `links`.`people_id` = '".$p["people_id"]."'");
$posts = $sql->fetchAll();

if ($posts) {
    $posts = array_map(function ($p) {
        return '<li><a href="/blog/'.$p["post_url"].'">'.$p["post_title"].'</a></li>';
    }, $posts);

    $_ECHO .= '<h3>À propos</h3><ul>'.implode($posts).'</ul>';
}

$_REQ = "`article_links` LIKE '%[people:".$p["people_id"]."]%'";

$_REQ_ORDER = "ORDER BY `article_copyright` DESC, `article_pubdate` DESC";

$path = get_controller_path('_list');
include($path);
