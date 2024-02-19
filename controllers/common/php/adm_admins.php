<?php

use Biblys\Service\Config;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** @var PDO $_SQL */
/** @var Request $request */

$request->attributes->set("page_title", "Admininistrateur·trice·s");

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

$rights = $_SQL->prepare("
    SELECT `axys_accounts`.`axys_account_id`, `axys_account_email`, `axys_account_screen_name`, `axys_account_login_date`, `right_id`
        FROM `rights`
        JOIN `axys_accounts` ON `axys_accounts`.`axys_account_id` = `rights`.`axys_account_id`
    WHERE `rights`.`site_id` = :site_id
    ORDER BY `axys_account_login_date` DESC
");
$rights->execute(["site_id" => $globalSite->get("id")]);

$table = NULL;
while ($p = $rights->fetch(PDO::FETCH_ASSOC)) {

    $table .= '
        <tr>
            <td>'.$p["axys_account_id"].'</td>
            <td>'.$p["axys_account_email"].'<br>'.$p["axys_account_screen_name"].'</td>
            <td class="center">'._date($p["axys_account_login_date"],'d/m/Y Hhi').'</td>
            <td>
                <a class="btn btn-sm btn-danger" href="/pages/adm_admins?delete='.$p['right_id'].'&email='.$p['axys_account_email'].'" title="supprimer" data-confirm="Voulez-vous vraiment SUPPRIMER l\'accès administrateur de '.$p['axys_account_email'].'">
                    <span class="fa fa-trash-o"></span>
                </a>
            </td>
        </tr>
    ';
}

$content = '
    <h1><span class="fa fa-users"></span> Administrateur·trice·s</h1>

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
