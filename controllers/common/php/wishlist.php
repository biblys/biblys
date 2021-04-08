<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$um = new UserManager();
$wlm = new WishlistManager();

$user = $um->get(array('user_slug' => $request->query->get('slug')));
if (!$user) {
    throw new ResourceNotFoundException('Utilisateur '.$request->query->get('slug').' inconnu');
}

$wishlist = $wlm->get(array('user_id' => $user->get('id')));
if (!$wishlist) {
    throw new ResourceNotFoundException('Liste d\'envies introuvable.');
}
if (!$wishlist->has('public')) {
    throw new Exception('Cette liste d\'envies n\'est pas publique.');
}

$_PAGE_TITLE = $wishlist->get('name');

$wishes = $user->getWishes();

$am = new ArticleManager();
$articles = array();

$table = null; $og_images = array();
foreach ($wishes as $w)
{

    if (!$w['wish_bought'])
    {

        $a = $am->get(array('article_id' => $w['article_id']));

        if ($_SITE['site_publisher'])
        {
            if ($a->get('publisher_id') == $_SITE['publisher_id'])
            {
                $table .= '
                    <tr>
                        <td class="center">'.$a->getPhotoTag(['size' => 'h50']).'</td>
                        <td>
                            <p><strong><a href="/'.$a->get('url').'">'.$a->get('title').'</a></strong></p>
                            <p>'.authors($a->get('authors')).'</p>
                            <p>'.$a->get('collection')->get('name').' / '.$a->get('publisher')->get('name').'</p>
                        </td>
                        <td class="center">
                            <p class="center">'.price($a->get('price'), 'EUR').'</p>
                            <a class="btn btn-primary btn-sm" href="/pages/log_gift?wish_id='.$w['wish_id'].'&article_id='.$a->get('id').'">Offrir cet article</a>
                        </td>
                    </tr>
                ';
            }
        }
        else
        {
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
                            <p class="center"><span class="'.($s->get('condition') == 'Neuf' ? 'green' : 'orange').'">'.$s->get('condition').'</span> '.price($s->get('selling_price'),'EUR').'</p>
                            <a class="btn btn-primary btn-sm" href="/pages/log_gift?wish_id='.$w['wish_id'].'&stock_id='.$s->get('id').'"><i class="fa fa-gift"></i> Offrir cet article</a>
                        </td>
                    </tr>
                ';
            }
        }
    }
}

$_OPENGRAPH = '
    <meta property="og:type" content="website">
    <meta property="og:title" content="'.$_PAGE_TITLE.'">
    <meta property="og:url" content="http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'">
    <meta property="og:description" content="Offrez un livre à '.$user->get('screen_name').' en soutenant l\'édition et la librairie indépendante !">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="'.$_SITE["site_name"].'">
';
shuffle($og_images);
if (!empty($og_images)) $_OPENGRAPH .= '<meta property="og:image" content="'.$og_images[0].'">';

$content = '
    <h2>'.$_PAGE_TITLE.'</h2>
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
