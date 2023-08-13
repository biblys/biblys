<?php

use Biblys\Legacy\LegacyCodeHelper;

$um = new UserManager();

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Le Blog');

$pp = null;

// Use new post controller and redirect ?
$use_old_controller = $_SITE->getOpt('use_old_post_controller');

if (LegacyCodeHelper::getGlobalVisitor()->isAdmin())
{
    $_ECHO .= '
        <div class="admin">
            <p>Blog</p>
            <p><a href="/pages/adm_posts">Gestion du blog</a></p>
            <p><a href="/pages/adm_post">Nouveau billet</a></p>
        </div>
    ';
}

// Posts from a category
$cat_req = "AND (`category_hidden` = 0 OR `category_hidden` IS NULL)";
if (isset($_GET["category"]))
{
    $cm = new CategoryManager();
    if ($c = $cm->get(array('category_url' => $_GET['category']))) {
        $cat_req = " AND `posts`.`category_id` = '".$c->get('id')."'";
        \Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle($c->get('name'));

        if (!$use_old_controller) {
            redirect($urlgenerator->generate('category_show', ['slug' => $request->query->get('category')]));
        }
    }
}

// Posts from an author
$aut_req = null;
if (isset($_GET["author"]))
{
    if ($u = $um->get(array('user_slug' => $_GET['author'])))
    {
        $aut_req = " AND `posts`.`user_id` = '".$u->get('id')."'";
        if (isset(\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle())) \Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle() .= ' de '.$u->get('screen_name');
    }
}

if (!$use_old_controller) {
    redirect($urlgenerator->generate('post_index'));
}

$_ECHO .= '<h1>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>';

// Requête
$posts_query = "SELECT `post_id`, `post_title`, `post_url`, `post_content`, `post_date`, `post_illustration_legend`,
                `category_name`, `category_url`,
                `user_screen_name`, `user_slug`
    FROM `posts`
    LEFT JOIN `categories` USING(`category_id`)
    LEFT JOIN `axys_users` ON `posts`.`user_id` = `axys_users`.`id`
    LEFT JOIN `links` USING(`post_id`)
WHERE `posts`.`site_id` = '" . LegacyCodeHelper::getLegacyCurrentSite()["site_id"]."' AND `post_date` <= NOW() AND `post_status` = '1' ".$aut_req." ".$cat_req.""
        . "GROUP BY `post_id`";

// Pagination
$ppp = 10; // Nombre de billet par page
$post_count = count($_SQL->query($posts_query)->fetchAll()); // Nombre total de billets
$page_count = round($post_count / $ppp); // Nombre total de pages
if(empty($_GET["p"])) $_GET["p"] = 0; // Page en cours
$page_start = $_GET["p"] * $ppp; // Premier post de la page

$link_params = array();
if (isset($_GET['author'])) $link_params['author'] = $_GET['author'];
if (isset($_GET['category'])) $link_params['category'] = $_GET['category'];
$base_url = '/pages/posts?';

if ($_GET["p"] > 0)
{
    $link_params['p'] = $_GET["p"] - 1;
    $pp = '<a href="'.$base_url.http_build_query($link_params).'">&laquo; Billets plus récents</a> | '; // Page précédente
}
if ($_GET["p"] < $page_count)
{
    $link_params['p'] = $_GET["p"] + 1;
    $np = ' | <a href="'.$base_url.http_build_query($link_params).'">Billets plus anciens &raquo;</a>'; // Page suivante
}

$page_select = null;
for ($ip = 0; $ip < ($page_count+1); $ip++)
{
    $link_params['p'] = $ip;
    $page_select .= '<option value="'.$base_url.http_build_query($link_params).'"'.($ip == $_GET['p'] ? ' selected' : null).'>'.($ip+1).'</option>';
}

$posts = $_SQL->query($posts_query." ORDER BY `post_date` DESC LIMIT ".$page_start.",".$ppp."");

$pm = new PostManager();
while ($p = $posts->fetch())
{

    $post = $pm->getById($p['post_id']);

    // Linked articles
    $articles = $post->getArticles();

    $html = get_template('post', ['post' => $post, 'articles' => $articles]);
    if ($html) {
        $_ECHO .= $html;
    } else {

        if(!empty($p["category_name"])) $p["dans"] = 'dans la rubrique <a href="/blog/'.$p["category_url"].'/">'.$p["category_name"].'</a>.'; else $p['dans'] = null;
        if(!empty($p["user_screen_name"])) $p["par"] = '<span class="post-infos-par">par '.$p["user_screen_name"].'</span>'; else $p['par'] = null;
        if(media_exists("post",$p["post_id"])) $p["illustration"] = '<img src="'.media_url("post",$p["post_id"]).'" alt="'.$p["post_illustration_legend"].'">';
        else $p['illustration'] = null;

        $_ECHO .= '
            <article id="p'.$p["post_id"].'" class="post">
                <header class="post-header">
                    <h1><a href="/blog/'.$p["post_url"].'">'.$p["post_title"].'</a></h1>
                    <p class="notice">Publié '.$p["par"].' le <time datetime="'._date($p["post_date"],'Y-m-dTH:i:sZ').'">'._date($p["post_date"],'j f Y').'</time> '.$p["dans"].'</p>
                </header>
                <section class="post-content">
                    '.$p["illustration"].'
                    '.$p["post_content"].'
                </section>
            </article>
        ';
    }
}
$posts->closeCursor();

if($page_count > 1) $_ECHO .= '<nav class="center">'.$pp.' Page <select class="goto">'.$page_select.'</select> sur '.($page_count+1).' '.$np.'</nav>';

