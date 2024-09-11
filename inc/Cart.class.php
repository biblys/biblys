<?php

use Biblys\Exception\CannotAddStockItemToCartException;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Entity\Exception\CartException;
use Model\ArticleQuery;
use Model\StockQuery;
use Model\WishQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

class Cart extends Entity
{
    protected $prefix = 'cart';
    protected array $stock = [];

    public function __construct($data)
    {
        // Customer (OneToMany)
        if (isset($data['customer_id'])) {
            $cm = new CustomerManager();
            $data['customer'] = $cm->get(array('customer_id' => $data['customer_id']));
        }

        parent::__construct($data);
    }

    public function set($field, $value): void
    {
        if ($field == 'cart_title' && empty($value)) $value = 'Panier n&deg; ' . $this->get('id');

        parent::set($field, $value);
    }

    /**
     * @throws PropelException
     * @throws Exception
     * @throws Exception
     */
    public function getLine(ImagesService $imagesService, Stock $stockEntity): string
    {
        
        $stock = StockQuery::create()->findPk($stockEntity->get('id'));

        /** @var Article $articleEntity */
        $articleEntity = $stockEntity->get('article');
        $articleModel = ArticleQuery::create()->findPk($articleEntity->get("id"));

        // Image
        $articleCoverUrl = $imagesService->getImageUrlFor($articleModel, height: 100);
        $stockItemPhotoUrl = $imagesService->getImageUrlFor($stock, height: 100);

        if ($articleCoverUrl) $cover = '<img src="' . $articleCoverUrl . '" height=55 alt="' . $articleEntity->get('title') . '">';
        elseif ($stockItemPhotoUrl) $cover = '<img src="' . $stockItemPhotoUrl . '" height=55 alt="' . $articleEntity->get('title') . '">';
        else $cover = NULL;

        $articleUrl = \Biblys\Legacy\LegacyCodeHelper::getGlobalUrlGenerator()->generate("article_show", ["slug" => $articleEntity->get("url")]);

        return '
                <tr id="stock_' . $stockEntity->get('id') . '">
                    <td class="va-middle right"><a href="/pages/adm_stock?id=' . $stockEntity->get('id') . '">' . $stockEntity->get('id') . '</a></td>
                    <td class="va-middle center">' . $cover . '</td>
                    <td class="va-middle">
                        <a href="' . $articleUrl . '">' . $articleEntity->get('title') . '</a><br>
                        de ' . authors($articleEntity->get('authors')) . '<br>
                        Ed. ' . $articleEntity->get('publisher')->get('name') . '
                    </td>
                    <td class="va-middle right stock_selling_price" data-price="' . $stockEntity->get('selling_price') . '">
                        ' . $stockEntity->get('condition') . '
                        ' . currency($stockEntity->get('selling_price') / 100) . '
                    </td>
                    <td class="center va-middle">
                        <button title="Retirer du panier" data-remove_from_cart="' . $stockEntity->get('id') . '" class="btn btn-warning btn-sm event">
                            <i class="fa fa-close"></i>
                        </button>
                    </td>
                </tr>
            ';
    }

    /**
     * Get cart content
     * @return array
     */
    public function getStock(): array
    {
        $cm = new CartManager();
        $this->stock = $cm->getStock($this);
        return $this->stock;
    }

    /**
     * Show cart in one line
     * @throws PropelException
     */
    public function getOneLine($params = []): string
    {
        return Cart::buildOneLine($this->get('count'), $this->get('amount'), $params);
    }

    /**
     * @throws PropelException
     */
    public static function buildOneLine($count = 0, $amount = 0, $params = []): string
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

    /**
     * @throws PropelException
     */
    public static function getOneLineEmpty(): string
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
     * @throws Exception
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
     * @return bool
     */
    public function needsShipping(): bool
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
}

class CartManager extends EntityManager
{
    protected $prefix = 'cart',
        $table = 'carts',
        $object = 'Cart',
        $siteAgnostic = false;

    /**
     * @throws Exception
     */
    public function create(array $defaults = array()): Entity
    {
        if (empty($defaults)) $defaults = array('site_id' => $this->site['site_id'], 'cart_uid' => md5(uniqid('', true)));

        if (!isset($defaults['site_id'])) $defaults['site_id'] = $this->site['site_id'];
        if (!isset($defaults['cart_uid'])) $defaults['cart_uid'] = md5(uniqid('', true));

        return parent::create($defaults);
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
            $this->removeStock($copy);
        }

        $cart->set('customer_id', '');
        $cart->set('cart_title', '');
        $cart->set('cart_date', '');
        $cart->set('cart_count', 0);
        $cart->set('cart_amount', 0);

        $this->update($cart);

        return true;
    }

    /**
     * Obtenir le contenu d'un panier
     */
    public function getStock(Cart $cart): array
    {
        $sm = new StockManager();
        return $sm->getAll(array('cart_id' => $cart->get('id')));
    }

    /**
     * Ajouter un exemplaire au panier
     * @throws CannotAddStockItemToCartException
     * @throws PropelException
     * @throws Exception
     */
    public function addStock(
        Cart      $cart,
        Stock|int $stock,
        CFReward  $reward = null
    ): bool
    {
        $globalSite = LegacyCodeHelper::getGlobalSite(ignoreDeprecation: true);
        $request = Request::createFromGlobals();
        $config = Config::load();
        $currentUser = CurrentUser::buildFromRequestAndConfig($request, $config);
        $sm = new StockManager();

        if (!is_object($stock)) {
            $stock_id = $stock;
            $stock = $sm->getById($stock_id);
            if (!$stock) {
                throw new Exception('Exemplaire ' . $stock_id . ' introuvable.');
            }
        }

        // If stock is already in another cart, remove from it first
        if ($otherCart = $stock->get("cart")) {

            // If the other cart is a shop cart, throw error
            if ($otherCart->get("type") === "shop") {
                $errorMessage = "Cet article est réservé en magasin.";
                if ($cart->get('type') === "shop") {
                    $otherCartTitle = $otherCart->get('cart_title');
                    $errorMessage = "Impossible d'ajouter l'article car il est déjà dans le panier caisse '$otherCartTitle'.";
                }

                throw new CannotAddStockItemToCartException($errorMessage);
            }

            // Else, remove it from the cart
            $this->removeStock($stock);
        }

        // If stock is not available
        if (!$stock->isAvailable()) {
            throw new Exception('Exemplaire ' . $stock->get('id') . ' indisponible.');
        }

        $weight_required = $globalSite->getOpt('weight_required');
        if ($cart->get('type') == 'web' && $weight_required && (!$stock->get('weight') || $stock->get('weight') < $weight_required)) {
            throw new Exception('Cet exemplaire n’a pas de poids et ne peut être ajouté au panier. Merci de nous contacter.');
        }

        if ($currentUser->isAuthentified()) {
            $wishForArticle = WishQuery::create()
                ->filterByArticleId($stock->get('article_id'))
                ->filterByUserId($currentUser->getUser()->getId())
                ->findOne();
            if ($wishForArticle) {
                $wishForArticle->setBought(new DateTime());
                $wishForArticle->save();
                $stock->set('wish_id', $wishForArticle->getId());
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

        return true;
    }

    /**
     * Add an article to cart (create copy if needed)
     * @throws CartException
     * @throws PropelException
     * @throws Exception
     */
    public function addArticle(Cart $cart, Article|int $article, CFReward $reward = null): bool
    {

        $sm = new StockManager();
        $am = new ArticleManager();

        if (is_int($article)) {
            $article = $am->getById($article);
            if (!$article) {
                throw new CartException('Article inexistant.');
            }
        }
        $a = $article;

        if (!$article instanceof Article) {
            throw new Exception(
                'Cart->addArticle should receive an article, ' . get_class($article) . ' received instead'
            );
        }

        if ($article->isDownloadable()) {
            if (!$article->isPurchasable() && !$article->isPrivatelyPrinted())
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
                if (LegacyCodeHelper::getGlobalVisitor()->hasInCart('stock', $stock->get('id'))) {
                    continue;
                }

                // "Just added to cart" cooldown
                // A copy is not available if it was added to cart less than 1 hour ago
                // This is meant to prevent "stealing" copy from another cart when a lot
                // of orders are validated at the same time
                if ($stock->has("stock_cart_date")) {
                    $addedToCart = strtotime($stock->get('stock_cart_date'));
                    $now = strtotime('now');
                    if ($now - $addedToCart < 3600) {
                        continue;
                    }
                }

                // Else, add copy to cart
                $this->addStock($cart, $stock->get('id'), $reward);
                return true;
            }
        }

        // Create a new copy if:
        // - Site uses virtual stock
        // - Adding a reward that is not limited
        if ($this->site->getOpt('virtual_stock') || $reward && !$reward->isLimited()) {

            if (!$article->isPublished() && !$article->isPreorderable()) {
                throw new CartException('L\'article <a href="/a/' . $a["article_url"] . '">' .
                    $a["article_title"] . '</a> n’a pas pu être ajouté au panier, car il n’est pas encore disponible.');
            }

            if ($article->isSoldOut()) {
                throw new CartException('L\'article <a href="/a/' . $a["article_url"] . '">' .
                    $a["article_title"] . '</a> n’a pas pu être ajouté au panier, car il n’est plus disponible.');
            }

            if ($article->isToBeReprinted()) {
                $title = $article->get("title");
                throw new CartException(
                    "L'article $title n'a pas pu être ajouté au panier car il est en cours de réimpression."
                );
            }

            // Create a new copy
            $s = $this->_createANewCopy($sm, $a);

            $sm->update($s);
            $this->addStock($cart, $s->get('id'), $reward);
            $this->db->commit();
            return true;
        }

        $articleTitle = $article->get('title');
        throw new CartException(
            "L'article $articleTitle n'a pas pu être ajouté au panier car il n'y a aucun exemplaire disponible."
        );
    }

    /**
     * @throws PropelException
     * @throws CartException
     * @throws Exception
     */
    public function addCFReward(Cart $cart, CFReward $reward): bool
    {
        $am = new ArticleManager();

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
     * @throws Exception
     */

    public function removeStock(&$stock): bool
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

    /**
     * @throws Exception
     */
    public function removeArticle(Cart $cart, $article_id): bool
    {
        $stock = $this->getStock($cart);
        foreach ($stock as $s) {
            if ($s['article_id'] == $article_id) {
                $this->removeStock($s);
                return true;
            }
        }

        return false;
    }

    /**
     * Update stock_count and stock_amount from stock
     * @throws Exception
     */
    public function updateFromStock(Cart $cart): bool
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

    /**
     * @throws Exception
     */
    public function delete($entity, $reason = null): bool
    {
        $entity->set('cart_uid', '');
        $this->update($entity);
        return parent::delete($entity, $reason);
    }

    /**
     * @throws Exception
     */
    private function _createANewCopy(StockManager $sm, Article|false|int|Entity $a): Entity
    {
        $this->db->beginTransaction();
        $s = $sm->create(array('site_id' => $this->site['site_id']));
        $s->set('article_id', $a->get('id'));
        $s->set('site_id', $this->site['site_id']);
        $s->set('stock_selling_price', $a->get('price'));
        $s->set('stock_weight', $a->get('weight'));
        return $s;
    }
}
