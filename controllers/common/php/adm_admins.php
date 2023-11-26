<?php

use Biblys\Service\CurrentSite;
use Model\RightQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @throws InvalidDateFormatException
 * @throws PropelException
 */
return function (
    Request $request,
    CurrentSite $currentSite,
    \Symfony\Component\HttpFoundation\Session\Session $session
): Response|RedirectResponse
{
    $request->attributes->set("page_title", "Admininistrateur·trice·s");

    if ($request->getMethod() === "POST") {
        $userToDeleteId = $request->request->get("user_id");

        $adminRightToDelete = RightQuery::create()
            ->filterByUserId($userToDeleteId)
            ->filterBySite($currentSite->getSite())
            ->filterByIsAdmin(true)
            ->findOne();

        if ($adminRightToDelete) {
            $adminRightToDelete->delete();

            $session->getFlashBag()->add(
                "success",
                "L'accès administrateur de {$adminRightToDelete->getUser()->getEmail()} a bien été supprimé."
            );
        }
    }

    $adminRights = RightQuery::create()
        ->filterByIsAdmin(true)
        ->filterBySite($currentSite->getSite())
        ->joinWithUser()
        ->find();

    $table = NULL;
    foreach ($adminRights as $adminRight) {

        $user = $adminRight->getUser();
        $table .= '
            <tr>
                <td>' . $user->getId() . '</td>
                <td>' . $user->getEmail() . '</td>
                <td class="center">' . _date($user->getLastLoggedAt(), 'd/m/Y Hhi') . '</td>
                <td>
                    <form method="post">
                        <button
                            class="btn btn-sm btn-danger" 
                            title="supprimer" 
                            data-confirm="Voulez-vous vraiment SUPPRIMER l\'accès administrateur de ' . $user->getEmail() . '"
                        >
                            <input type="hidden" name="user_id" value="' . $user->getId() . '">
                            <span class="fa fa-trash-o"></span>
                        </button>
                    </form>
                </td>
            </tr>
        ';
    }

    $content = '
        <h1><span class="fa fa-users"></span> Administrateur·trice·s</h1>
    
        ' . ($message ?? null) . '<br>
    
        <div class="center">
            <a href="/pages/adm_admin_add" class="btn btn-primary">
                <span class="fa fa-user-plus"></span> &nbsp;
                    Ajouter un administrateur
            </a>
        </div>
        <br>
    
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th class="left">Utilisateur</th>
                    <th>Dernière connexion</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                ' . $table . '
            </tbody>
        </table>
    ';

    return new Response($content);
};
