<?php

use Biblys\Service\Config;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$pm = new PostManager();

$postUrl = $request->query->get("url");
$postUrlHtmlSafe = filter_var($postUrl, FILTER_SANITIZE_STRING);
$post = $pm->get(["post_url" => $postUrl]);

if (!$post) {
    throw new ResourceNotFoundException("No post for url $postUrlHtmlSafe");
}

// Unless site option is defined to use old post controller
// redirect to to new post controller immediately
$use_old_controller = $_SITE->getOpt('use_old_post_controller');
if (!$use_old_controller) {
    $newUrl = $urlgenerator->generate('post_show', ['slug' => $post->get('url')]);
    return new RedirectResponse($newUrl);
}

if ($post->get('status') == 0) {
    if (!getLegacyVisitor()->isAdmin()) {
        throw new ResourceNotFoundException("Post is offline.");
    }

    $content = '
        <p class="alert alert-warning">
            <span class="fa fa-warning"></span>
            Ce billet est hors-ligne.
        </p>
    ';
}

$query = "SELECT `post_id`, `post_title`, `post_url`, `post_content`, `post_date`, `post_update`,
        `category_name`, `category_url`,
        `user_screen_name`, `user_slug`
    FROM `posts`
    LEFT JOIN `categories` USING(`category_id`)
    LEFT JOIN `users` ON `user_id` = `users`.`id`
    WHERE `post_id` = :post_id";

$posts = $_SQL->prepare($query);
$posts->execute(['post_id' => $post->get('id')]);

$p = $posts->fetch(PDO::FETCH_ASSOC);

if (getLegacyVisitor()->isAdmin()) {
    $content = '
        <div class="admin">
            <p>Billet n&deg; '.$p["post_id"].'</p>
            <p><a href="/pages/adm_post?id='.$p["post_id"].'">modifier</a></p>
            <p><a href="/pages/links?post_id='.$p["post_id"].'">lier</a></p>
            <p><a href="/pages/adm_post?del='.$p["post_id"].'" data-confirm="Voulez-vous vraiment SUPPRIMER ce billet ?">supprimer</a></p>
            <p><a href="/pages/adm_posts">tous les billets</a></p>
        </div>
    ';
}
$_PAGE_TITLE = $p["post_title"];

if(!empty($p["category_name"])) $dans = 'dans la rubrique <a href="/blog/'.$p["category_url"].'/">'.$p["category_name"].'</a>.'; else $dans = null;
if(!empty($p["user_screen_name"])) $par = "par ".$p["user_screen_name"]; else $par = null;
if(media_exists("post",$p["post_id"]))
{
    $_og_image = media_url("post",$p["post_id"]);
    $p["illustration"] = '<img src="'.media_url("post",$p["post_id"]).'" alt="'.(isset($p["post_illustration_legend"]) ? $p["post_illustration_legend"] : null).'">';
} else $p['illustration'] = null;

// Articles lies
$articles = $post->getArticles();
foreach ($articles as $article) {
    if ($article->hasCover()) {
        $opengraph['og:image'] = $article->getCover('object')->url();
    }
}

// Get linked people
$people = $_SQL->query("SELECT * FROM `people` JOIN `links` USING(`people_id`) WHERE `links`.`post_id` = ".$post->get('id'));
$people = $people->fetchAll(PDO::FETCH_ASSOC);

$people = array_map( function($people) {
    return new People($people);
}, $people);

// Get linked publisher
$publishers = $_SQL->query("SELECT * FROM `publishers` JOIN `links` USING(`publisher_id`) WHERE `links`.`post_id` = ".$post->get('id'));
$publishers = $publishers->fetchAll(PDO::FETCH_ASSOC);

$publishers = array_map( function($publisher) {
    return new Publisher($publisher);
}, $publishers);

$image = new Media('post', $post->get('id'));

// Render template
$html = get_template('post', array(
    'post' => $post,
    'articles' => $articles,
    'peoples' => $people,
    'publishers' => $publishers,
    'image' => $image));

if ($html)
{
    $content = $html;

    // OPENGRAPH
    $opengraph['og:type'] = 'article';
    $opengraph['og:url'] = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    $opengraph['og:site_name'] = getLegacyCurrentSite()["site_title"];
    $opengraph['og:title'] = $post->get('title');
    $opengraph['og:description'] = truncate($post->get('content'), '500', '...', true);
    if ($post->has('category')) $opengraph['article:section'] = $post->get('category')->get('name');
    $opengraph['article:published_time'] = $post->get('date');
    if ($post->has('updated')) $opengraph['article:modified_time'] = $post->get('updated');
    if ($image->exists()) $opengraph['og:image'] = $image->url();

}
else
{
    $content = '
        <article id="p'.$p["post_id"].'" class="post">
            <header class="post-header">
                <h1><a href="/blog/'.$p["post_url"].'">'.$p["post_title"].'</a></h1>
                <p class="post-infos">Publié <span class="post-infos-par">par '.$p["user_screen_name"].'</span> le&nbsp;<time datetime="'._date($p["post_date"],'Y-m-dTH:i:sZ').'">'._date($p["post_date"],'j f Y').'</time> '.$dans.'</p>
            </header>
            <section class="post-content">
                '.$p["illustration"].'
                '.$p["post_content"].'
            </section>
            <footer class="post-footer">

            </footer>
        </article>
    ';
}

// OPENGRAPH

$_OPENGRAPH = '
    <meta property="og:type"                 content="article">
    <meta property="og:url"                  content="http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'">
    <meta property="og:site_name"            content="'.getLegacyCurrentSite()["site_title"].'">
    <meta property="og:title"                content="'.$p["post_title"].'">
    <meta property="og:description"          content="'.truncate($p["post_content"],'500','...',true).'">
    <meta property="article:published_time"  content="'.$p["post_date"].'">
    <meta property="article:modified_time"   content="'.$p["post_update"].'">
    <meta property="article:section"         content="'.$p["category_name"].'">
';

// Recuperation de la première image
preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $p["post_content"], $image);
if(!empty($image['src'])) $_og_image = $image["src"];

if(isset($_og_image)) $_OPENGRAPH .= '<meta property="og:image" content="'.$_og_image.'">';

return new Response($content);
