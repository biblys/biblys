<?php

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/** @var Request $request */
/** @var CurrentUser $currentUser */
/** @var Session $session */

$wm = new WishlistManager();

$wishlist = $wm->getById($request->query->get('id'));
if (!$wishlist) {
    throw new NotFoundHttpException("Liste d'envie #{$request->query->get('id')} inconnue.");
}

if ($wishlist->get('user_id') !== $currentUser->getUser()->getId()) {
    throw new AccessDeniedHttpException("Vous n'avez pas le droit de modifier cette liste d'envies.");
}

if ($request->getMethod() == "POST") {

    $wishlist->set('wishlist_name', $request->request->get('wishlist_name'));
    $wm->update($wishlist);

    $session->getFlashbag()->add('success', "Votre liste d'envies &laquo;&nbsp;" . $wishlist->get('name') . "&nbsp;&raquo; a bien été modifiée !");
    redirect('/pages/log_mywishes');

}

LegacyCodeHelper::setGlobalPageTitle("Modifier &laquo;&nbsp;" . $wishlist->get('name') . "&nbsp;&raquo;");

$_ECHO .= '
        <h2>' . LegacyCodeHelper::getGlobalPageTitle() . '</h2>
    
        <form class="fieldset" method="post">
            <fieldset>
                <div class="form-group">
                    <label for="wishlist_name" class="after">Nom de la liste :</label>
                    <input type="text" class="form-control" value="' . $wishlist->get('name') . '" name="wishlist_name" id="wishlist_name">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </fieldset>
        </form>
    ';
