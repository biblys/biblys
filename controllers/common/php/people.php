<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

/** @var UrlGenerator $urlgenerator */

$content = "";

$pm = new PeopleManager();
/** @var Request $request */
$url = $request->query->get('url');
$people = $pm->get(['people_url' => $url]);
if (!$people) {
    throw new ResourceNotFoundException('No people found for url '.htmlentities($url));
}

/** @var Site $site */
$use_old_controller = $site->getOpt("use_old_people_controller");
if (!$use_old_controller) {
    return new RedirectResponse("/p/$url/", 301);
}


$p = $people;

$_OPENGRAPH = '
    <meta property="og:title" content="'.$p["people_name"].'"/>
    <meta property="og:type" content="author"/>
    <meta property="og:url" content="https://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'"/>
    <meta property="og:description" content="'.truncate($p["people_bio"], '500', '...', true).'"/>
    <meta property="og:locale" content="fr_FR"/>
    <meta property="og:site_name" content="'.getLegacyCurrentSite()["site_name"].'"/>
';

$photo = new Media("people", $p["people_id"]);
if ($photo->exists()) {
    $_OPENGRAPH .= '<meta property="og:image" content="'.$photo->getUrl().'" />';
}

if (auth("admin")) {
    $content .= '
        <div class="admin">
            <p>Intervenant n° '.$p["people_id"].'</p>
            <p><a href="'.$urlgenerator->generate("people_edit", ["id" => $people->get("id")]).'">modifier</a></p>
        </div>
    ';
}

$request->attributes->set("page_title", $people->get("name"));
$content .= '<h2>'.$people->get("name").'</h2>';

// Aliases
$aliases = $pm->getAll(['people_pseudo' => $people->get('id')]);
$aliases = array_map(function ($alias) {
    return '<a href="/'.$alias->get('url').'/">'.$alias->get('name').'</a>';
}, $aliases);
if (count($aliases)) {
    $content .= '<p>Ses pseudonymes : '.join(', ', $aliases).'</p>';
}

// Pseudonyme de...
if ($people->has('pseudo')) {
    $realName = $pm->get(['people_id' => $people->get('pseudo')]);
    if ($realName) {
        $content .= '<p>(Pseudonyme de <a href="/'.$realName["people_url"].'/">'.$realName["people_name"].'</a>)</p>';
    }
}

// Linked post
/** @var PDO $_SQL */
$sql = $_SQL->query("SELECT * FROM `posts` JOIN `links` USING(`post_id`) WHERE `posts`.`site_id` = '".getLegacyCurrentSite()["site_id"]."' AND `links`.`people_id` = '".$p["people_id"]."'");
$posts = $sql->fetchAll();

if ($posts) {
    $posts = array_map(function ($p) {
        return '<li><a href="/blog/'.$p["post_url"].'">'.$p["post_title"].'</a></li>';
    }, $posts);

    $content .= '<h3>À propos</h3><ul>'.implode($posts).'</ul>';
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

$content .= '
    <div id="people" class="clearfix">
        <div id="people-photo">
';
$photo = new Media("people", $p["people_id"]);
if ($photo->exists()) {
    $content .= '<img src="'.$photo->getUrl(["size" => "w200"]).'" alt="'.$p["people_name"].'" class="frame" />';
}
$content .= '<p class="center">'.$flag.' '.$dates.'</p>';
if (!empty($p["people_site"])) {
    $content .= '<p><a href="'.$p["people_site"].'" rel="nofollow" target="_blank">Site officiel</a></p>';
}

$content .= '
        </div>
';

$content .= '<div id="people-bio">'.$p["people_bio"].'</div>';

$_REQ = "`article_links` LIKE '%[people:".$p["people_id"]."]%'";

$_REQ_ORDER = "ORDER BY `article_copyright` DESC, `article_pubdate` DESC";

$path = get_controller_path('_list');
$content .= require_once $path;

return new Response($content);