<?php

    $wm = new WishlistManager();

    $wishlist = $wm->getById($request->query->get('id'));
    if (!$wishlist) {
        trigger_error('Liste d\'envie #'.$request->query->get('id').' inconnue.');
    }
    
    if ($wishlist->get('user_id') != getLegacyVisitor()->get('id')) {
        trigger_error("Vous n'avez pas le droit de modifier cette liste d'envies.");
    }
    
    if ($request->getMethod() == "POST") {
        
        $wishlist->set('wishlist_name', $request->request->get('wishlist_name'))
            ->set('wishlist_public', $request->request->get('wishlist_public'));
            
        $wm->update($wishlist);
        
        $session->getFlashbag()->add('success', "Votre liste d'envies &laquo;&nbsp;".$wishlist->get('name')."&nbsp;&raquo; a bien été modifiée !");
        redirect('/pages/log_mywishes');
        
    }

    $_PAGE_TITLE = "Modifier &laquo;&nbsp;".$wishlist->get('name')."&nbsp;&raquo;";

    $_ECHO .= '
        <h2>'.$_PAGE_TITLE.'</h2>
    
        <form class="fieldset" method="post">
            <fieldset>
                <div class="form-group">
                    <label for="wishlist_name" class="after">Nom de la liste :</label>
                    <input type="text" class="form-control" value="'.$wishlist->get('name').'" name="wishlist_name" id="wishlist_name">
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox"'.($wishlist->has('public') ? ' checked ' : null).'name="wishlist_public" id="wishlist_public" value=1> Publique
                        <i class="fa fa-info-circle" title="Cochez cette case pour que d\'autres puissent voir votre liste."></i>
                    </label>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </fieldset>
        </form>
    ';
