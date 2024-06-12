<?php

    $um = new UserManager();

    if (!isset($_GET['wish_id']) || (!isset($_GET['stock_id']) && !isset($_GET['article_id']))) $_ECHO .= e404();
    else
    {
        // Check if wish exists
        $wish = $_SQL->prepare('SELECT `wish_id`, `user_id` FROM `wishes` WHERE `wish_id` = :wish LIMIT 1');
        $wish->execute(array('wish' => (int) $_GET['wish_id']));
        if ($w = $wish->fetch(PDO::FETCH_ASSOC))
        {
            
            if (isset($_GET['stock_id']))
            {
                $sm = new StockManager();
                if ($s = $sm->get(array('stock_id' => $_GET['stock_id'], 'site_id' => $_SITE['site_id'])))
                {
                    $gift_type = 'stock';
                    $gift_id = $s->get('id');
                    $gift_price = $s->get('selling_price');
                    $a = $s->getArticle();
                } else trigger_error('Exemplaire inexistant.');
            }
            elseif (isset($_GET['article_id']))
            {
                $am = new ArticleManager();
                if ($a = $am->get(array('article_id' => $_GET['article_id'])))
                {
                    $gift_type = 'article';
                    $gift_id = $a->get('id');
                    $gift_price = $a->get('price');
                } else trigger_error('Article inexistant.');
            }
            
            if ($a->get('type_id') == 2 || $a->get('type_id') == 11)
            {
                $_ECHO .= '<p class="warning"> Il n\'est pas possible d\'offrir des livres numériques ou audio pour l\'instant.</p>';
            }
            else
            {

                // Check if user exists
                if ($u = $um->get(array('user_id' => $w['user_id'])))
                {
                    $_PAGE_TITLE = 'Offrir &laquo; <a href="/'.$a->get('url').'">'.$a->get('title').'</a> &raquo; à <a href="/wishlist/'.$u->get('user_slug').'">'.$u->get('user_screen_name').'</a> ('.price($gift_price, 'EUR').')';

                    $buy = '<div class="col-md-4 center">
                                <p>
                                    <a class="btn btn-primary add_to_cart event" data-type="'.$gift_type.'" data-id="'.$gift_id.'"" data-wish_id="'.$w['wish_id'].'" title="Ajouter <em>'.$a->get('title').'</em> au panier"><i class="fa fa-shopping-cart"></i> L\'offrir moi-même</a></p>
                                </p>
                                <p>Choisissez cette option si vous comptez acheter puis faire parvenir vous-même le cadeau à '.$u->get('user_screen_name').'.</p>
                            </div>';

                    if (false)
                    {
                        $give = '
                            <div class="col-md-4 center">
                                <p><a class="btn btn-primary" data-wish_id="'.$w['wish_id'].'"><img src="/common/icons/cart.svg" width=14> Le faire envoyer</button></p>
                                <p>Choisissez cette option si vous souhaitez que nous fassions parvenir cet article avec un emballage cadeau à l\'adresse spécifiée par '.$u->get('user_screen_name').'.</p>
                            </div>';
                    }
                    else 
                    {
                        $give = '
                            <div class="col-md-4 center">
                                <p><button class="btn btn-primary" disabled class="disabled">Le faire envoyer</button></p>
                                <p>Cette option n\'est pas disponible pour l\'instant. <!-- car '.$u->get('user_screen_name').' n\'a pas renseigné d\'adresse d\'expédition. //--></p>
                            </div>';
                    }

                    // Birthday privatization
                    $birthday = null;
                    $cm = new CustomerManager();
                    if ($c = $cm->get(array('user_id' => $u->get('id'))))
                    {
                        if ($c->has('customer_privatization'))
                        {
                            $birthday = '
                            <div class="col-md-4 center">
                                <p><button class="btn btn-primary add_to_cart event" data-type='.$gift_type.' data-id='.$gift_id.' data-wish_id="'.$w['wish_id'].'" data-as_a_gift="party"><i class="fa fa-gift"></i> Option anniversaire</button></p>
                                <p>Choisissez cette option pour que cet article soit disponible en magasin avec un emballage cadeau le '._date($c->get('customer_privatization'), 'd f Y').' lorsque '.$u->get('user_screen_name').' y fêtera son anniversaire.</p>
                            </div>';
                        }
                    }
                    
                    $_ECHO .= '
                        <h1>'.$_PAGE_TITLE.'</h1>

                        <h2>Comment souhaitez vous offrir cet article ?</h2>

                        <p class="warning">Attention, si vous continuez, &laquo; '.$a->get('title').' &raquo; sera définitivement supprimé de la liste d\'envie de '.$u->get('user_screen_name').', ceci afin d\'éviter que cet article lui soit offert en double.</p>

                        <div class="center">
                            '.$buy.$give.$birthday.'
                        </div>
                        
                        <br>
                        <div class="center">
                            <a href="/wishlist/'.$u->get('slug').'" class="btn btn-default"><i class="fa fa-chevron-left"></i> Retourner à la wishlist</a>
                            <a href="/pages/cart" class="btn btn-primary">Aller au panier <i class="fa fa-chevron-right"></i></a>
                        </div>

                    ';

                } else $_ECHO .= e404();
            }
        }
        else $_ECHO .= e404();
    }
