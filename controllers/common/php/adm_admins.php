<?php

use Biblys\Service\Config;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** @var PDO $_SQL */
/** @var Request $request */

$request->attributes->set("page_title", "Admininistrateurs");

// Supprimer une right
if (isset($_GET['delete']))
{
    $rm = new RightManager();
    $right = $rm->getById($_GET['delete']);
    $rm->delete($right);
    return new RedirectResponse('/pages/adm_admins?deleted=1&email='.$_GET['email']);
}

// Permission supprimée
if (isset($_GET['deleted'])) {
    $message = '<p class="success">L\'utilisateur '.$_GET['email'].' a été supprimé.</p>';
}

// Utilisateur ajouté
if (isset($_GET['added'])) {
    if (isset($_GET['created'])) $messageC = '<p class="success">Un compte Axys a été créé pour l\'utilisateur '.$_GET['email'].'.</p>'; else $messageC = null;
    $message = $messageC.'<p class="success">L\'utilisateur '.$_GET['email'].' a été ajouté aux administrateurs.</p>';
}

$rights = $_SQL->prepare("
    SELECT `users`.`id` as `user_id`, `users`.`Email` AS `user_email`, `user_screen_name`, `DateConnexion`, `right_id`
        FROM `rights`
        JOIN `users` ON `users`.`id` = `rights`.`user_id`
    WHERE `rights`.`site_id` = :site_id
    ORDER BY `DateConnexion` DESC
");
$rights->execute(["site_id" => $_SITE->get("id")]);

$table = NULL;
while ($p = $rights->fetch(PDO::FETCH_ASSOC)) {

    $table .= '
        <tr>
            <td>'.$p["user_id"].'</td>
            <td>'.$p["user_email"].'<br>'.$p["user_screen_name"].'</td>
            <td class="center">'._date($p["DateConnexion"],'d/m/Y Hhi').'</td>
            <td>
                <a class="btn btn-sm btn-danger" href="/pages/adm_admins?delete='.$p['right_id'].'&email='.$p['user_email'].'" title="supprimer" data-confirm="Voulez-vous vraiment SUPPRIMER l\'accès administrateur de '.$p['user_email'].'">
                    <span class="fa fa-trash-o"></span>
                </a>
            </td>
        </tr>
    ';
}

$content = '
    <h1><span class="fa fa-users"></span> '.$_PAGE_TITLE.'</h1>

    '.($message ?? null).'<br>

    <div class="center">
        <a href="/pages/adm_admin_add" class="btn btn-primary">
            <span class="fa fa-user-plus"></span> &nbsp;
                Ajouter un administrateur
        </a>
    </div>
    <br>

    <table class="liste sortable admin-table">
        <thead>
            <tr>
                <th></th>
                <th class="left">Utilisateur</th>
                <th>Dernière connexion</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            '.$table.'
        </tbody>
    </table>
';

return new Response($content);
