<?php

use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

$the_categories = null;

$request = Request::createFromGlobals();
$config = Config::load();
$currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);

$rank = "log_";
if ($currentUser->isAdmin()) {
    $cm = new CategoryManager();
    $categories = $cm->getAll();
    foreach ($categories as $category) {
        $c = $category;
        $the_categories .= '<option value="?category_id='.$c["category_id"].'" '.(isset($_GET['category_id']) && $_GET['category_id'] == $c['category_id'] ? ' selected' : null).'>'.$c["category_name"].'</option>';
    }
    if(isset($the_categories)) $the_categories = '
        <select name="category_id" id="category_id" class="goto">
            <option value="?">Filtrer par catégorie...</option>
            '.$the_categories.'
        </select>
    ';
    $rank = 'adm_';
}
elseif ($currentUser->hasPublisherRight()) $rank = 'pub_';

$req = NULL;
$params = ["site_id" => $globalSite->get("id")];
if (isset($_GET["category_id"])) {
    $req .= 'AND `category_id` = :category_id';
    $params["category_id"] = $_GET["category_id"];
}

if(!$currentUser->isAdmin() && $currentUser->hasPublisherRight()) {
    $req .= 'AND `posts`.`publisher_id` = :publisher_id';
    $params["publisher_id"] = $currentUser->getCurrentRight()->getPublisherId();
}

$config = Config::load();
$posts = EntityManager::prepareAndExecute(
    "SELECT
        `post_id`, `post_title`, `post_content`, `post_url`, `post_status`, `post_date`, `axys_account_email`,
        `axys_account_screen_name`, `category_name`, `publishers`.`publisher_id`, `publisher_name`
    FROM `posts`
    JOIN `axys_accounts` ON `axys_accounts`.`axys_account_id` = `posts`.`axys_account_id`
    LEFT JOIN `categories` USING(`category_id`)
    LEFT JOIN `publishers` ON `posts`.`publisher_id` = `publishers`.`publisher_id`
    WHERE `posts`.`site_id` = :site_id $req
    ORDER BY `post_date` DESC, `post_id` DESC",
    $params
);

if(isset($_BIBLYS_TYS)) $post_url = 'post';
else $post_url = 'blog';

$table = NULL;
while($p = $posts->fetch(PDO::FETCH_ASSOC)) {
    if($p["post_status"] == 1) $p["status"] = '<img src="/common/img/square_green.png" alt="En ligne" />';
    else $p["status"] = '<img src="/common/img/square_red.png" alt="Hors ligne" />';
    if(!empty($p["axys_account_screen_name"])) $p["user"] = $p["axys_account_screen_name"];
    else $p["user"] = $p["axys_account_email"];
    if(empty($p["post_title"])) $p["post_title"] = truncate(strip_tags($p["post_content"]),50);
    if(!empty($p["publisher_id"])) $p["user"] = $p["publisher_name"];
    /** @var UrlGenerator $urlgenerator */
    $table .= '
        <tr>
            <td class="right">'.$p["status"].'</td>
            <td><a href="/'.$post_url.'/'.$p["post_url"].'">'.$p["post_title"].'</a></td>
            <td class="nowrap">'.$p["user"].'</td>
            <td>'.$p["category_name"].'</td>
            <td>'._date($p["post_date"],'d/m/Y').'</td>
            <td class="nowrap">
                <a href="/pages/'.$rank.'post?id='.$p["post_id"].'" title="Éditer">
                    <span class="fa fa-edit fa-lg"></span>
                </a>
                <a href="'.$urlgenerator->generate('post_delete', ['id' => $p['post_id']]).'" title="Supprimer" data-confirm="Voulez-vous vraiment SUPPRIMER ce billet ?">
                    <span class="fa fa-trash-o fa-lg">
                    </span>
                </a>
            </td>
        </tr>
    ';
}

$request->attributes->set("page_title", "Gestion des billets");
$content = '
    <h1><span class="fa fa-newspaper-o"></span> Gestion des billets</h1>

    <p class="buttonset">
        <a href="/pages/'.$rank.'post" class="btn btn-primary"><i class="fa fa-newspaper-o"></i> Nouveau billet</a></a>
        '.$the_categories.'
    </p>

    <div class="admin">
        <p>Billets</p>
        <p><a href="/pages/'.$rank.'post">nouveau</a></p>
    </div>

    <br />

    <table class="admin-table">

        <thead>
            <tr>
                <th></th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Date</th>
                <th></th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            '.$table.'
        </tbody>

    </table>';

return new Response($content);
