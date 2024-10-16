<?php /** @noinspection SqlCheckUsingColumns */


/** @var Request $request */
/** @var PDO $_SQL */

use Biblys\Legacy\LegacyCodeHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

if (!LegacyCodeHelper::getGlobalVisitor()->isAdmin() && !LegacyCodeHelper::getGlobalVisitor()->isPublisher()) {
    throw new AccessDeniedHttpException("Vous n'avez pas le droit d'effectuer cette opération.");
}

$lm = new LinkManager();
$am = new ArticleManager();

$params = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $link = $lm->getById($_POST['delete']);
        if ($link) {
            $lm->delete($link);
            $params['deleted'] = $link->get('id');

            // Refresh article metadata
            $article = $am->getById($link->get('article_id'));
            if ($article) {
                $article = $am->refreshMetadata($article);
                $am->update($article);
            }
        } else {
            trigger_error('Link not found');
        }

        if ($request->isXmlHttpRequest()) {
            die(json_encode($params['deleted']));
        }
    }
}

$pageTitle = 'Liens';
$_WHERE = null;

$eventId = $request->query->get('event_id');
$postId = $request->query->get('post_id');
if ($postId) {
    $posts = $_SQL->query("SELECT `post_id`, `post_title`, `post_url` FROM `posts` WHERE `post_id` = '".$_GET["post_id"]."' LIMIT 1");
    if ($p = $posts->fetch(PDO::FETCH_ASSOC)) {
        $pageTitle = 'Lier au billet <a href="/blog/'.$p["post_url"].'" id="element" data-type="post" data-id="'.$p["post_id"].'">'.$p["post_title"].'</a>';
        $_WHERE = "`post_id` = ".$_GET["post_id"];
    }
} elseif ($eventId) {
    $em = new EventManager();
    $event = $em->getById($eventId);
    if ($event) {
        $e = $event;
        $pageTitle = 'Lier l\'évènement <a href="/programme/'.$e["event_url"].'" id="element" data-type="event" data-id="'.$e["event_id"].'">'.$e["event_title"].'</a>';
        $_WHERE = "`event_id` = ".$_GET["event_id"];
    }
}

$content = '<h2>'.$pageTitle.'</h2>';

// Articles
$articleId = $request->query->get('article_id');
if (!$articleId) {
    $articles = $_SQL->prepare("SELECT `article_title`, `article_url`, `article_authors`, `article_collection`, `link_id` FROM `articles` JOIN `links` USING(`article_id`) WHERE ".$_WHERE." ORDER BY `link_id`");
    $articles->execute();
    $a_links = null;
    while ($article = $articles->fetch(PDO::FETCH_ASSOC)) {
        $a = $article;
        $a_links .= '<li id="link_'.$a["link_id"].'" ><img alt="Delete" src="/common/icons/delete_16.png" data-link_id="'.$a["link_id"].'" class="deleteLink pointer" /> <a href="/'.$a["article_url"].'">'.$a["article_title"].'</a> de '.$a["article_authors"].' ('.$a["article_collection"].')</li>';
    }
    $content .= '
            <h3>Articles liés</h3>
            <ul id="linked_articles">
                '.$a_links.'
            </ul>
            <ul>
                <li><input type="text" id="article" placeholder="Lier à un article..." class="long" /></li>
            </ul>
        ';
}

// Contributeurs
$peopleId = $request->query->get('people_id');
if (!$peopleId) {
    $people = $_SQL->prepare("SELECT `people_name`, `people_url`, `link_id` FROM `people` JOIN `links` USING(`people_id`) WHERE ".$_WHERE." ORDER BY `link_id`");
    $people->execute();
    $p_links = null;
    while ($p = $people->fetch(PDO::FETCH_ASSOC)) {
        $p_links .= '<li id="link_'.$p["link_id"].'" ><img alt="Supprimer" src="/common/icons/delete_16.png" data-link_id="'.$p["link_id"].'" class="deleteLink pointer" /> <a href="/'.$p["people_url"].'">'.$p["people_name"].'</a></li>';
    }
    $content .= '
            <h3>Contributeurs liés</h3>
            <ul id="linked_peoples">
                '.$p_links.'
            </ul>
            <ul>
                <li><input type="text" id="people" placeholder="Lier à un contributeur..." class="long" /></li>
            </ul>
        ';
}

// Publishers
$publisherId = $request->query->get('publisher_id');
if (!$publisherId) {
    $people = $_SQL->query("SELECT `publisher_name`, `publisher_url`, `link_id` FROM `publishers` JOIN `links` USING(`publisher_id`) WHERE ".$_WHERE." ORDER BY `link_id`");

    $p_links = null;
    while ($p = $people->fetch(PDO::FETCH_ASSOC)) {
        $p_links .= '<li id="link_'.$p["link_id"].'" ><img alt="Supprimer" src="/common/icons/delete_16.png" data-link_id="'.$p["link_id"].'" class="deleteLink pointer" /> <a href="/editeur/'.$p["publisher_url"].'">'.$p["publisher_name"].'</a></li>';
    }

    $content .= '
            <h3>Éditeurs liés</h3>
            <ul id="linked_publishers">
                '.$p_links.'
            </ul>
            <ul>
                <li><input type="text" id="publisher" placeholder="Lier à un éditeur..." class="long" /></li>
            </ul>
        ';
}

// Billets
$postId = $request->query->get('post_id');
if (!$postId) {
    $posts = $_SQL->query("SELECT `link_id`, `post_url`, `post_title` FROM `posts` JOIN `links` USING(`post_id`) WHERE ".$_WHERE." ORDER BY `link_id`");
    $po_links = null;
    while ($po = $posts->fetch(PDO::FETCH_ASSOC)) {
        $po_links .= '<li id="link_'.$po["link_id"].'" ><img alt="Supprimer" src="/common/icons/delete_16.png" data-link_id="'.$po["link_id"].'" class="deleteLink pointer" /> <a href="/blog/'.$po["post_url"].'">'.$po["post_title"].'</a></li>';
    }
    $content .= '
            <h3>Billet liés</h3>
            <ul id="linked_posts">
                '.$po_links.'
            </ul>
            <ul>
                <li><input type="text" id="post" placeholder="Lier à un billet..." class="long" /></li>
            </ul>
        ';
}

// Evenements
$eventId = $request->query->get('event_id');
if ($eventId) {
    $events = $_SQL->query("SELECT `link_id`, `event_title`, `event_url` FROM `events` JOIN `links` USING(`event_id`) WHERE ".$_WHERE." ORDER BY `link_id`");
    $e_links = null;
    while ($e = $events->fetchAll(PDO::FETCH_ASSOC)) {
        $e_links .= '<li id="link_'.$e["link_id"].'" ><img alt="Supprimer" src="/common/icons/delete_16.png" data-link_id="'.$e["link_id"].'" class="deleteLink pointer" /> <a href="/programme/'.$e["event_url"].'">'.$e["event_title"].'</a></li>';
    }
    $content .= '
            <h3>Évènements liés</h3>
            <ul id="linked_events">
                '.$e_links.'
            </ul>
            <ul>
                <li><input type="text" id="event" placeholder="Lier à un évènement..." class="long" /></li>
            </ul>
        ';
}

$content .= '<script type="text/javascript" src="/common/js/adm_links.js?v3"></script>';

$request->attributes->set("page_title", $pageTitle);
return new Response($content);

