<?php

global $request;
$_SITE = LegacyCodeHelper::getGlobalSite();

use Biblys\Legacy\LegacyCodeHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$um = new AxysAccountManager();
$wlm = new WishlistManager();

$wishlistSlug = LegacyCodeHelper::getRouteParam("slug");
$user = $um->get(['axys_account_slug' => $wishlistSlug]);
if (!$user) {
    throw new ResourceNotFoundException('Utilisateur '. $wishlistSlug .' inconnu');
}

$wishlist = $wlm->get(array('axys_account_id' => $user->get('id')));
if (!$wishlist) {
    throw new ResourceNotFoundException('Liste d\'envies introuvable.');
}
if (!$wishlist->has('public')) {
    throw new Exception('Cette liste d\'envies n\'est pas publique.');
}

\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle($wishlist->get('name'));

$wishes = $user->getWishes();

$am = new ArticleManager();
$articles = array();

$table = null; $og_images = array();
foreach ($wishes as $w) {

    if (!$w['wish_bought']) {

        $a = $am->get(array('article_id' => $w['article_id']));

        if ($_SITE->has("publisher_id")) {
            if ($a->get('publisher_id') == $_SITE->get('publisher_id')) {
                $table .= '
                    <tr>
                        <td class="center">'.$a->getPhotoTag(['size' => 'h50']).'</td>
                        <td>
                            <p><strong><a href="/'.$a->get('url').'">'.$a->get('title').'</a></strong></p>
                            <p>'.authors($a->get('authors')).'</p>
                            <p>'.$a->get('collection')->get('name').' / '.$a->get('publisher')->get('name').'</p>
                        </td>
                        <td class="center">
                            '.$article->getCartButton("Offrir ce livre").'
                        </td>
                    </tr>
                ';
            }
        } else {
            $stock = $a->getStock('available');
            foreach ($stock as $s)
            {
                $photo = null;
                if ($s->hasPhoto()) {
                    $photo = $s->getPhotoTag(['size' => 'h50']);
                    $og_images[] = $s->getPhotoUrl();
                }

                $table .= '
                    <tr>
                        <td class="center">'.$photo.'</td>
                        <td>
                            <p><strong><a href="/'.$a->get('url').'">'.$a->get('title').'</a></strong></p>
                            <p>'.authors($a->get('authors')).'</p>
                            <p>'.$a->get('collection')->get('name').' / '.$a->get('publisher')->get('name').'</p>
                        </td>
                        <td class="center">
                            '.$s->getCartButton("Offrir ce livre").'
                        </td>
                    </tr>
                ';
            }
        }
    }
}

$_OPENGRAPH = '
    <meta property="og:type" content="website">
    <meta property="og:title" content="'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'">
    <meta property="og:url" content="http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'">
    <meta property="og:description" content="Offrez un livre à '.$user->get('screen_name').' en soutenant l\'édition et la librairie indépendante !">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="'.$_SITE->get("name").'">
';
shuffle($og_images);
if (!empty($og_images)) $_OPENGRAPH .= '<meta property="og:image" content="'.$og_images[0].'">';

$content = '
    <h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>
    <p>Liste d\'envies de '.$user->get('screen_name').'</p>
    
    <table class="biblys-table">
        <thead>
            <tr>
                <th colspan=2>Article</th>
                <th>Achat</th>
            </tr>
        </thead>
        <tbody>
            '.$table.'
        </tbody>
    </table>
';

return new Response($content);
