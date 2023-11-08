<?php /** @noinspection PhpUnhandledExceptionInspection */

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @param CurrentUser $currentUserService
 * @param Request $request
 * @param CurrentSite $currentSiteService
 * @return JsonResponse|RedirectResponse|Response
 * @throws PropelException
 */
return function (
    Request     $request,
    CurrentUser $currentUserService,
    CurrentSite $currentSiteService
): Response|JsonResponse|RedirectResponse
{
    $wlm = new WishlistManager();
    $wm = new WishManager();
    $am = new ArticleManager();

    $content = "";

    // Get or create current wishlist
    $wishlist = $wlm->get([
        "axys_account_id" => $currentUserService->getAxysAccount()->getId(),
        "wishlist_current" => 1
    ]);
    if (!$wishlist) {

        // Create a current wishlist for current user
        $wishlist = $wlm->create(array(
            'axys_account_id' => $currentUserService->getAxysAccount()->getId(),
            'wishlist_current' => 1
        ));

        // Add wishes
        $wishes = $wm->getAll(array('axys_account_id' => $currentUserService->getAxysAccount()->getId(), 'wishlist_id' => 'NULL'));
        foreach ($wishes as $wish) {
            $wish->set('wishlist', $wishlist);
            $wm->update($wish);
        }
    }

    if ($request->getMethod() === "POST") {

        $content = $request->getContent();
        $params = json_decode($content, true);
        $articleId = $params["article_id"];
        $article = $am->getById($articleId);

        if (!$article) {
            trigger_error('Article #' . $articleId . ' introuvable.');
        }

        $wish = $wm->get(array('article_id' => $article->get('id'), 'wishlist_id' => $wishlist->get('id')));

        // If wish exists, delete it
        if ($wish) {
            $wm->delete($wish);
            $p['deleted'] = 1;
            $p['message'] = "&laquo;&nbsp;" . $article->get('title') . "&nbsp;&raquo a bien été retiré de votre <a href='/pages/log_mywishes'>liste d'envies</a>.";
        } // Else create it
        else {
            $wish = $wm->create();
            $wish->set('axys_account_id', $currentUserService->getAxysAccount()->getId());
            $wish->set('article', $article);
            $wish->set('wishlist', $wishlist);
            $wm->update($wish);
            $p['created'] = 1;
            $p['message'] = "&laquo;&nbsp;" . $article->get('title') . "&nbsp;&raquo a bien été ajouté à votre <a href='/pages/log_mywishes'>liste d'envies</a>.";
        }

        if ($request->headers->get("Accept") === "application/json") {
            return new JsonResponse($p);
        }

        return new RedirectResponse(sprintf("/pages/log_wishes?%s", http_build_query($p)));
    } // Show wish list
    else {

        $request->attributes->set("page_title", $wishlist->get('name'));

        // Is username set ?
        if (!$currentUserService->getAxysAccount()->getUsername()) {
            $share = '<p class="alert alert-warning"><i class="fa fa-info-circle"></i> Pour pouvoir partager votre liste d\'envies, commencez par <a href="https://axys.me/#Profil">choisir un nom d\'utilisateur</a>.</p>';
        } // Is wishlist public ?
        elseif (!$wishlist->has('public')) {
            $share = '<p class="alert alert-warning">'
                . "Cette liste d'envies n'est pas publique. Cliquez sur <strong>Modifier</strong> pour changer sa visibilité." .
                '</p>';
        } // Show wishlist url & share buttons
        else {
            $url = 'https://' . $currentSiteService->getSite()->getDomain() . '/wishlist/' .
                $currentUserService->getAxysAccount()->getSlug();
            $share = '
			<br>

			<p class="center">Adresse publique de votre liste :<br><a href="/wishlist/' . $currentUserService->getAxysAccount()->getSlug() . '">' . $url . '</a></p>

			<div class="text-center">
				' . share_buttons($url, $wishlist->get('name')) . '
			</div>

			<p class="center">
				Partagez cette adresse pour vous faire offrir des livres !
			</p>
			';
        }


        $content .= '

			<div class="pull-right">
				<button class="btn btn-success" data-toggle="share">
					<i class="fa fa-share-square-o"></i> Partager
				</button>

				<a href="/pages/log_wishlist?id=' . $wishlist->get('id') . '" class="btn btn-info">
					<i class="fa fa-cog"></i> Modifier
				</a>
			</div>

			<h2>' . $wishlist->get("name") . '</h2>

			<div id="share" class="hidden">
				' . $share . '
			</div>
		';

        $wishes = $currentUserService->getAxysAccount()->getWishes();
        if (!count($wishes)) {
            $content .= '
				<p class="center">Votre liste d\'envies est vide !</p>
				<p class="center">Rendez-vous sur la fiche d\'un livre<br>qui vous intéresse et cliquez sur :<br></p>
				<p class="center">
					<a class="button">
						<img src="/common/icons/wish.svg" width="14" alt="Ajouter à vos envies">&nbsp; Ajouter à mes envies
					</a>
				</p>
				<p class="center">
					Vous pourrez ensuite retrouver votre liste<br>
					sur tous les sites <a href="https://www.biblys.fr/">Biblys</a> et la partager<br>
					pour vous faire offrir des livres !
				</p>
			';
        } else {
            $content .= '
				<div class="center">

				</div><br>
			';
            $criterias = [];
            foreach ($wishes->getData() as $wish) {
                $criterias[] = '`articles`.`article_id` = ' . $wish->getArticleId();
            }
            $_REQ = "(" . join(" OR ", $criterias) . ")";
            $publisherId = $currentSiteService->getSite()->getPublisherId();
            if ($publisherId) $_REQ .= ' AND `articles`.`publisher_id` = ' . $publisherId;
            $content .= require_once '_list.php';
        }
    }

    return new Response($content);
};
