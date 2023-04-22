<?php

use Entity\Exception\CartException;

class Cart extends Entity
{
    protected $prefix = 'cart';
    protected $stock = [];
    private $seller = null;

    public function __construct($data)
    {
        global $_SQL;

        /* JOINS */

        // Customer (OneToMany)
        if (isset($data['customer_id'])) {
            $cm = new CustomerManager();
            $data['customer'] = $cm->get(array('customer_id' => $data['customer_id']));
        }

        // Seller (OneToMany)
        if (isset($data['cart_seller_id'])) {
            $um = new UserManager();
            $seller = $um->getById($data['cart_seller_id']);
            if ($seller) {
                $this->seller = $seller;
            }
        }

        parent::__construct($data);
    }

    public function set($field, $value)
    {
        if ($field == 'cart_title' && empty($value)) $value = 'Panier n&deg; ' . $this->get('id');

        parent::set($field, $value);
    }

    public function getLine($stock)
    {
        $article = $stock->get('article');

        // Image
        $article_cover = new Media('article', $article->get('id'));
        $stock_cover   = new Media('stock',  $stock->get('id'));

        if ($article_cover->exists())   $cover = '<a href="' . $article_cover->url() . '" rel="lightbox"><img src="' . $article_cover->url('h100') . '" height=55 alt="' . $article->get('title') . '"></a>';
        elseif ($stock_cover->exists()) $cover = '<a href="' . $stock_cover->url() . '" rel="lightbox"><img src="' . $stock_cover->url('h100') . '" height=55 alt="' . $article->get('title') . '"></a>';
        else $cover = NULL;

        // Etat
        if ($stock->get('condition') == 'Neuf') $condition = '<span class="green">Neuf</span>';
        else $condition = '<span class="orange">' . $stock->get('condition') . '</span>';

        $line = '
                <tr id="stock_' . $stock->get('id') . '">
                    <td class="va-middle right"><a href="/pages/adm_stock?id=' . $stock->get('id') . '">' . $stock->get('id') . '</a></td>
                    <td class="va-middle center">' . $cover . '</td>
                    <td class="va-middle">
                        <a href="/' . $article->get('url') . '">' . $article->get('title') . '</a><br>
                        de ' . authors($article->get('authors')) . '<br>
                        Ed. ' . $article->get('publisher')->get('name') . '
                    </td>
                    <td class="va-middle right stock_selling_price" data-price="' . $stock->get('selling_price') . '">
                        ' . $stock->get('condition') . '
                        ' . currency($stock->get('selling_price') / 100) . '
                    </td>
                    <td class="center va-middle">
                        <button title="Retirer du panier" data-remove_from_cart="' . $stock->get('id') . '" class="btn btn-warning btn-sm event">
                            <i class="fa fa-close"></i>
                        </button>
                    </td>
                </tr>
            ';

        return $line;
    }

    /**
     * Get cart content
     * @return type
     */
    public function getStock()
    {
        $cm = new CartManager();
        $this->stock = $cm->getStock($this);
        return $this->stock;
    }

    /**
     * Show cart in one line
     * @return string
     */
    public function getOneLine($params = [])
    {
        return Cart::buildOneLine($this->get('count'), $this->get('amount'), $params);
    }

    public static function buildOneLine($count = 0, $amount = 0, $params = [])
    {
        $icon = '<span class="fa fa-shopping-cart"></span>';
        if (isset($params['image'])) {
            $icon = '<img src="' . $params['image'] . '" alt="Ajouter au panier">';
        }

        $content = 'Panier vide';
        $class = " empty";
        if ($count > 0) {
            $content = $count . ' article' . s($count) . ' (' . currency($amount / 100) . ')';
            $class = " not-empty";
        }

        return '<a
                href="/pages/cart"
                rel="nofollow"
                class="btn btn-default btn-sm' . $class . '">' .
            $icon . ' ' .
            $content .
            '</a>';
    }

    public static function getOneLineEmpty()
    {
        return self::buildOneLine();
    }

    /**
     * Returns true if the cart contains stock
     *
     * @param Stock $stock
     * @return bool
     */
    public function containsStock(Stock $stock): bool
    {
        $copies = $this->getStock();
        foreach ($copies as $copy) {
            if ($copy->get("id") === $stock->get("id")) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if the cart contains article
     *
     * @param Article $article
     * @return bool
     */
    public function containsArticle(Article $article): bool
    {
        $copies = $this->getStock();
        foreach ($copies as $copy) {
            if ($copy->get("article_id") === $article->get("id")) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if the cart contains reward
     *
     * @param CFReward $reward
     * @return bool
     */
    public function containsReward(CFReward $reward): bool
    {
        $copies = $this->getStock();
        foreach ($copies as $copy) {
            if ($copy->get("reward_id") === $reward->get("id")) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if cart contains at least one physical product
     * @return boolean
     */
    public function needsShipping()
    {
        $copies = $this->getStock();
        foreach ($copies as $copy) {
            $type = $copy->get('article')->getType();
            if ($type->isPhysical()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns user email if known, else user's IP address
     * @return [type] [description]
     */
    public function getUserInfo()
    {
        $user_id = $this->get('user_id');
        if (!$user_id) {
            return $this->get('cart_ip');
        }

        $um = new UserManager();
        $user = $um->getById($user_id);
        if (!$user) {
            return $this->get('cart_ip');
        }

        return $user->get('Email');
    }

    /**
     * @return bool
     */
    public function hasSeller(): bool
    {
        if ($this->seller === null) {
            return false;
        }

        return true;
    }

    /**
     * @return User|null
     */
    public function getSeller(): User
    {
        return $this->seller;
    }
}

class CartManager extends EntityManager
{
    protected $prefix = 'cart',
        $table = 'carts',
        $object = 'Cart',
        $siteAgnostic = false;

    /**
     *
     * @param string $query
     * @param type $params
     * @param type $options
     * @return object
     * @throws Exception
     */
    public function getQuery($query, $params, $options = array(), $withJoins = true)
    {
        try {
            if (isset($query)) $query = ' AND ' . $query;
            $params['site_id'] = $this->site['site_id'];

            $query = 'SELECT * FROM `carts`
                    WHERE `carts`.`site_id` = :site_id ' . $query . '
                    GROUP BY `cart_id`';
            $qu = $this->db->prepare($query);
            $qu->execute($params);
        } catch (Exception $e) {
            throw new Exception($e->getMessage() . '<br>query: ' . $query . '<br>params: ' . print_r($params, true));
        }

        $entities = array();
        while ($x = $qu->fetch(PDO::FETCH_ASSOC)) {
            $entities[] = new $this->object($x);
        }

        return $entities;
    }

    public function create(array $defaults = array())
    {
        if (empty($defaults)) $defaults = array('site_id' => $this->site['site_id'], 'cart_uid' => md5(uniqid('', true)));

        if (!isset($defaults['site_id'])) $defaults['site_id'] = $this->site['site_id'];
        if (!isset($defaults['cart_uid'])) $defaults['cart_uid'] = md5(uniqid('', true));

        try {
            return parent::create($defaults);
        } catch (Exception $e) {
            trigger_error($e->getMessage());
        }
    }

    /**
     * Vider le panier et remettre les exemplaires en vente
     * @param Cart $cart Le panier à vider
     * @return bool Le panier mis à jour
     * @throws Exception
     */
    public function vacuum(Cart $cart): bool
    {
        $copies = $this->getStock($cart);
        foreach ($copies as $copy) {
            $this->removeStock($cart, $copy);
        }

        $cart->set('customer_id', '');
        $cart->set('cart_title', '');
        $cart->set('cart_date', '');
        $cart->set('cart_count', '');
        $cart->set('cart_amount', '');

        $this->update($cart);

        return true;
    }

    /**
     * Obtenir le contenu d'un panier
     */
    public function getStock(Cart $cart, $stock_id = 'all'): array
    {
        $sm = new StockManager();
        $stock = $sm->getAll(array('cart_id' => $cart->get('id')));
        return $stock;
    }

    /**
     * Ajouter un exemplaire au panier
     * @param object $cart L'objet Cart du panier
     * @param int $stock_id L'id de l'exemplaire à ajouter
     * @param int $wish_id L'id du wish si offert (sinon, undefined)
     * @param object $reward If added as a crowdfunding reward, the reward object
     */
    public function addStock(Cart $cart, $stock, $wish_id = 'undefined', CFReward $reward = null)
    {
        global $site;
        $sm = new StockManager();

        if (!is_object($stock)) {
            $stock_id = $stock;
            $stock = $sm->getById($stock_id);
            if (!$stock) {
                throw new Exception('Exemplaire ' . $stock_id . ' introuvable.');
            }
        }

        // If stock is already in another cart, remove from it first
        if ($other_cart = $stock->get('cart')) {

            // If the other cart is a shop cart, throw error
            if ($other_cart->get('type') == 'shop') {
                throw new Exception('Cet article est réservé en magasin.');
            }

            // Else, remove it from the cart
            $this->removeStock($other_cart, $stock);
        }

        // If stock is not available
        if (!$stock->isAvailable()) {
            throw new Exception('Exemplaire ' . $stock->get('id') . ' indisponible.');
        }

        $weight_required = $site->getOpt('weight_required');
        if ($cart->get('type') == 'web' && $weight_required && (!$stock->get('weight') || $stock->get('weight') < $weight_required)) {
            throw new Exception('Cet exemplaire n\'a pas de poids et ne peut être ajouté au panier. Merci de nous contacter.');
        }

        // Is the article in the visitor's wishlist ?
        else {
            if (getLegacyVisitor()->isLogged()) {
                $wm = new WishManager();
                if ($w = $wm->get(array('article_id' => $stock->get('article_id'), 'user_id' => getLegacyVisitor()->get('id')))) {
                    $w->set('wish_bought', date('Y-m-d H:i:s'));
                    $wm->update($w);
                    $stock->set('wish_id', $w->get('id'));
                }
            }
        }

        // As a reward in a crowdfunding campaign ?
        if ($reward) {
            $stock->set('campaign_id', $reward->get('campaign_id'));
            $stock->set('reward_id', $reward->get('id'));
        }

        // Add stock to cart
        $stock->set('cart_id', $cart->get('id'));
        $stock->set('stock_cart_date', date('Y-m-d H:i:s'));
        $sm->update($stock, 'Added Stock #' . $stock->get('id') . ' to Cart #' . $cart->get('id'));

        // Add to cart's stock array
        $this->stock[] = $stock;

        return true;

        return false;
    }

    /**
     * Add an article to cart (create copy if needed)
     * @param Cart $cart
     * @param Article $article
     * @return bool
     * @throws Exception
     */
    public function addArticle(Cart $cart, $article, CFReward $reward = null): bool
    {

        $sm = new StockManager();
        $am = new ArticleManager();

        if (is_int($article)) {
            $article = $am->getById($article);
            if (!$article) {
                throw new CartException('Article ' . $article_id . ' inexistant.');
            }
        }
        $a = $article;

        if (!$article instanceof Article) {
            throw new Exception(
                'Cart->addArticle should receive an article, '.get_class($article).' received instead'
            );
        }

        if ($article->isDownloadable() && !$article->isPurchasable()) {
            throw new CartException('Cet article est indisponible.');
        }

        

        // Default : add an available copy to cart
        $stocks = $article->getAvailableItems('all', [
            'order' => 'stock_cart_date',
        ]);
        if (count($stocks) > 0) {

            // For each copy
            foreach ($stocks as $stock) {

                // If publisher site and copy price differs from article price, skip it
                if ($this->site['site_publisher'] && $stock->get('selling_price') != $a->get('price')) {
                    continue;
                }

                // If this copy is already in current user's cart
                if (getLegacyVisitor()->hasInCart('stock', $stock->get('id'))) {
                    continue;
                }

                // "Just added to cart" cooldown
                // A copy is not available if it was added to cart less than 1 hour ago
                // This is meant to prevent "stealing" copy from another cart when a lot
                // of orders are validated at the same time
                $addedToCart = strtotime($stock->get('stock_cart_date'));
                $now = strtotime('now');
                if ($now - $addedToCart < 3600) {
                    continue;
                }

                // Else, add copy to cart
                $this->addStock($cart, $stock->get('id'), null, $reward);
                return true;
            }
        }

        // Create a new copy if:
        // - Site uses virtual stock
        // - Adding a reward that is not limited
        if ($this->site->getOpt('virtual_stock') || $reward && !$reward->isLimited()) {

            if (!$article->isPublished() && !$article->isPreorderable()) {
                throw new CartException('L\'article <a href="/' . $a["article_url"] . '">' . $a["article_title"] . '</a> n\'a pas pu être ajouté au panier car il n\'est pas encore disponible.');
            }

            if ($article->isSoldOut()) {
                throw new CartException('L\'article <a href="/' . $a["article_url"] . '">' . $a["article_title"] . '</a> n\'a pas pu être ajouté au panier car il n\'est plus disponible.');
            }

            if ($article->isPrivatelyPrinted()) {
                $title = $article->get("title");
                throw new CartException(
                    "L'article $title n'a pas pu être ajouté au panier car il est hors commerce."
                );
            }

            if ($article->isToBeReprinted()) {
                $title = $article->get("title");
                throw new CartException(
                    "L'article $title n'a pas pu être ajouté au panier car il est en cours de réimpression."
                );
            }

            // Create a new copy
            $this->db->beginTransaction();
            $s = $sm->create(array('site_id' => $this->site['site_id']));
            $s->set('article_id', $a->get('id'));
            $s->set('site_id', $this->site['site_id']);
            $s->set('stock_selling_price', $a->get('price'));
            $s->set('stock_weight', $a->get('weight'));

            $sm->update($s);
            $this->addStock($cart, $s->get('id'), null, $reward);
            $this->db->commit();
            return true;
        }

        // Bookshop : on order
        if ($this->site['site_bookshop'] && strstr($a->get('links'), '[onorder:' . $this->site["site_id"] . ']')) {
            if (!$a->has('weight')) {
                throw new Exception('Le livre <a href="/' . $a["article_url"] . '">' . $a["article_title"] . '</a> n\'a pas pu être ajout&eacute; au panier car il n\'a pas de poids. Merci de <a href="/contact/">nous contacter</a>.');
            } elseif (!$a->has('price')) {
                throw new Exception('Le livre <a href="/' . $a["article_url"] . '">' . $a["article_title"] . '</a> n\'a pas pu être ajout&eacute; au panier car il n\'a pas de prix. Merci de <a href="/contact/">nous contacter</a>.');
            } elseif (!$a->has('availability')) {
                throw new Exception('Le livre <a href="/' . $a["article_url"] . '">' . $a["article_title"] . '</a> n\'a pas pu être ajout&eacute; au panier car il est indisponible. Merci de <a href="/contact/">nous contacter</a>.');
            } else {
                // Create a new on-order copy
                $this->db->beginTransaction();
                $s = $sm->create(array('site_id' => $this->site['site_id']));
                $s->set('article_id', $a->get('id'));
                $s->set('site_id', $this->site['site_id']);
                $s->set('stock_selling_price', $a->get('price'));
                $s->set('stock_weight', $a->get('weight'));
                $s->set('stock_stockage', 'Sur commande');
                $s->set('stock_condition', 'Neuf');
                $sm->update($s);
                $this->addStock($cart, $s->get('id'));
                $this->db->commit();
                return true;
            }
        }

        $articleTitle = $article->get('title');
        throw new CartException(
            "L'article $articleTitle n'a pas pu être ajouté au panier car il n'y a aucun exemplaire disponible."
        );
    }

    public function addCFReward(Cart $cart, CFReward $reward)
    {
        $am = new ArticleManager();
        $sm = new StockManager();
        $rm = new CFRewardManager();

        // If reward is limited and quantity is 0 or less
        if ($reward->get('limited') && $reward->get('quantity') <= 0) {
            trigger_error("Cette contrepartie n'est plus disponible.");
            return false;
        }

        // For each article in this reward
        $articles = json_decode($reward->get('articles'));
        foreach ($articles as $article_id) {
            if ($article = $am->get(array('article_id' => $article_id))) {
                $this->addArticle($cart, $article, $reward);
            } else {
                trigger_error('Article ' . $article_id . ' inconnu.');
            }
        }
        $this->updateFromStock($cart);
        return true;
    }

    /**
     * Retirer un exemplaire du panier
     * @param object $cart L'objet Cart du panier
     * @param int $stock L'id de l'exemplaire à retirer
     */

    public function removeStock(Cart $cart, &$stock)
    {
        $sm = new StockManager();

        if (!is_object($stock)) {
            $stock_id = $stock;
            $stock = $sm->getById($stock_id);
            if (!$stock) {
                throw new Exception('Exemplaire ' . $stock_id . ' introuvable.');
            }
        }

        // If added as a wish, re-add to wishlist
        if ($stock->has('wish_id')) {
            $wm = new WishManager();
            if ($w = $wm->get(array('wish_id' => $stock->get('wish_id')))) {
                $w->set('wish_bought', null);
                $wm->update($w);
                $stock->set('wish_id', null);
            }
        }

        // Remove stock from cart
        $stock->set('cart_id', null);
        $stock->set('stock_cart_date', null);
        $stock->set('campaign_id', null);
        $stock->set('reward_id', null);
        $sm->update($stock);
        return true;
    }

    public function removeArticle(Cart $cart, $article_id)
    {
        $stock = $this->getStock($cart);
        foreach ($stock as $s) {
            if ($s['article_id'] == $article_id) {
                $this->removeStock($cart, $s);
                return true;
            }
        }

        return false;
    }

    /**
     * Update stock_count and stock_amount from stock
     * @param Cart $cart
     * @return boolean true on success
     */
    public function updateFromStock(Cart $cart)
    {
        $amount = 0;
        $count = 0;
        $stock = $this->getStock($cart);
        foreach ($stock as $s) {
            $amount += $s['stock_selling_price'];
            $count++;
        }

        $cart->set('cart_amount', $amount);
        $cart->set('cart_count', $count);
        $this->update($cart);

        return true;
    }

    public function delete($x, $reason = null)
    {
        $x->set('cart_uid', '');
        $this->update($x);
        return parent::delete($x, $reason);
    }
}
