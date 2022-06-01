<?php

use Biblys\EuroTax as Tax;

class Stock extends Entity
{
    private $cover = null;
    private $article_cover = null;
    protected $prefix = 'stock';

    public function __construct($data, $withJoins = true)
    {
        global $_SQL, $_SITE;

        /* JOINS */

        if ($withJoins) {
            // Article (OneToMany)
            $am = new ArticleManager();
            if (isset($data['article_id'])) {
                $data['article'] = $am->get(['article_id' => $data['article_id']]);
            }

            // Cart (OneToMany)
            $cm = new CartManager();
            if (isset($data['cart_id'])) {
                $data['cart'] = $cm->get(['cart_id' => $data['cart_id']]);
            }

            // Order (OneToMany)
            $om = new OrderManager();
            if (isset($data['order_id'])) {
                $data['order'] = $om->get(['order_id' => $data['order_id']]);
            }

            // CFReward (OneToMany)
            $cfrm = new CFRewardManager();
            if (isset($data['reward_id'])) {
                $data['reward'] = $cfrm->get(['reward_id' => $data['reward_id']]);
            }

            // CFCampaign (OneToMany)
            $cfcm = new CFCampaignManager();
            if (isset($data['campaign_id'])) {
                $data['campaign'] = $cfcm->get(['campaign_id' => $data['campaign_id']]);
            }
        }
        parent::__construct($data);
    }

    /**
     * Test if a copy has a date of sale.
     *
     * @return {boolean}: true if it has
     */
    public function isSold()
    {
        return $this->has('selling_date');
    }

    /**
     * Test if a copy has a lost date.
     *
     * @return {boolean}: true if it has
     */
    public function isLost()
    {
        return $this->has('lost_date');
    }

    /**
     * Test if a copy has a return date.
     *
     * @return {boolean}: true if it has
     */
    public function isReturned()
    {
        return $this->has('return_date');
    }

    /**
     * Test if a copy has a cart date.
     *
     * @return {boolean}: true if it has
     */
    public function isInCart()
    {
        return $this->has('cart_date');
    }

    /**
     * Test if a copy is available. Returns true if:
     * - copy has no selling date
     * - copy has no lost date
     * - copy has no return date
     * - copy is not from an unactive stock
     * /!\ a copy in a cart is considered available.
     */
    public function isAvailable()
    {
        global $site;

        if (!$this->has('id')) {
            return false;
        }

        // Not available if stock is sold, lost or returned
        if ($this->isSold() || $this->isLost() || $this->isReturned()) {
            return false;
        }

        // Not available if from unactive stock
        $active_stock = $site->getOpt('active_stock');
        if ($active_stock) {
            $active_stock = explode(',', $active_stock);
            if (!in_array($this->get('stockage'), $active_stock)) {
                return false;
            }
        }

        // Else, copy is available
        return true;
    }

    /**
     * Test if a copy is reserved for a customer.
     *
     * @return {boolean} true if copy is in a shop cart
     */
    public function isReserved()
    {
        $cart_id = $this->get('cart_id');
        if ($cart_id) {
            $cm = new CartManager();
            $cart = $cm->getById($cart_id);
            if ($cart && 'shop' === $cart->get('type')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get article.
     */
    public function getArticle()
    {
        $_A = new ArticleManager();
        if ($a = $_A->get(['article_id' => $this->get('article_id')])) {
            return $a;
        } else {
            return false;
        }
    }

    /**
     * Test if item has a cover image.
     */
    public function hasCover()
    {
        trigger_error("Stock->hasCover is deprecated. Use Stock->hasPhoto instead.", E_USER_DEPRECATED);
        return $this->hasPhoto();
    }

    /**
     * Get stock cover.
     */
    public function getCover($size = null)
    {
        if ('url' == $size) {
            trigger_error("Stock->getCover is deprecated. Use Stock->getPhotoUrl instead.", E_USER_DEPRECATED);
            return $this->getPhotoUrl();
        }

        trigger_error("Stock->getCover is deprecated. Use Stock->getPhotoTag instead.", E_USER_DEPRECATED);
        return $this->getPhotoTag();
    }

    /**
     * Get stock photo object.
     */
    public function getPhoto()
    {
        if (isset($this->photo)) {
            return $this->photo;
        }

        $id = $this->get('id');

        $this->photo = new Media('stock', $id);

        if (!$this->photo->exists()) {
            throw new Exception("Stock item $id doesn't have a photo.");
        }

        return $this->photo;
    }

    /**
     * Test if stock photo exists.
     */
    public function hasPhoto()
    {
        try {
            $this->getPhoto();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getPhotoUrl($size = null)
    {
        $photo = $this->getPhoto();

        $urlOptions = [];

        $photoVersion = $this->get('photo_version');
        if ($photoVersion > 1) {
            $urlOptions['version'] = $photoVersion;
        }

        if ($size) {
            $urlOptions['size'] = $size;
        }

        return $photo->getUrl($urlOptions);
    }

    public function getPhotoTag(array $options = [])
    {
        $size = null;
        if (isset($options['size'])) {
            $size = $options['size'];
        }

        $class = $options["class"] ?? "";

        return '
            <a href="'.$this->getPhotoUrl().'" class="'.$class.'" rel="lightbox">
                <img src="' .$this->getPhotoUrl($size).'">
            </a>
        ';
    }

    /**
     * Returns the html code of the cart button for this item.
     *
     * @param {string} $text the cart button text
     *
     * @return {string} the html code for the button
     */
    public function getCartButton($text = false)
    {
        return '
            <a class="btn btn-primary'.($text ? '' : ' btn-sm').' add_to_cart event"
                data-type="stock" data-id="'.$this->get('id').'">
                <span class="fa fa-shopping-cart"></span>'.($text ? ' '.$text : '').'
            </a>
        ';
    }

    /**
     * Set a copy as returned.
     *
     * @return bool true if success
     */
    public function setReturned($date = false)
    {
        // Can't set returned if not available
        if (!$this->isAvailable()) {
            return false;
        }

        // Remove from cart if necessary
        $cart_id = $this->get('cart_id');
        if ($cart_id) {
            $cm = new CartManager();
            $cart = $cm->getById($cart_id);
            $cm->removeStock($cart, $this);
        }

        // Default date: now
        if (!$date) {
            $date = (new DateTime())->format('Y-m-d H:i:s');
        }

        // Set return date
        $this->set('stock_return_date', $date);

        return true;
    }

    /**
     * Set a copy as unreturned.
     */
    public function cancelReturn()
    {
        if (!$this->isReturned()) {
            throw new \Exception("Copy $stockId is not returned");
        }

        // Set return date
        $this->set('stock_return_date', null);
    }

    /**
     * Get difference between purchase and selling price.
     *
     * @return float
     */
    public function getDiscountRate()
    {
        $purchase_price = (int) $this->get('purchase_price');

        $selling_price = $this->get('selling_price_ht');
        if (!$selling_price) {
            $sm = new StockManager();
            $stock = $sm->calculateTax($this);
            $selling_price = (int) $stock->get('selling_price_ht');
        }

        $rate = 100 - $purchase_price / $selling_price * 100;

        return round($rate);
    }

    /**
     * Replace selling price with saved price.
     *
     * @return undefined
     */
    public function restoreSavedPrice()
    {
        if (!$this->has('stock_selling_price_saved')) {
            return;
        }

        $this->set('stock_selling_price', $this->get('selling_price_saved'));
        $this->set('stock_selling_price_saved', null);
    }

    /**
     * Returns availability dot according to copy availability.
     *
     * @return string
     */
    public function getAvailabilityDot()
    {
        $title = 'Inconnu';
        $color = 'black';
        $letter = 'i';

        if ($this->isAvailable()) {
            $title = 'Disponible';
            $color = 'green';
            $letter = 'd';
        }

        if ($this->isSold()) {
            $title = 'Vendu';
            $color = 'blue';
            $letter = 'v';
        }

        if ($this->isInCart()) {
            $title = 'En panier';
            $color = 'red';
            $letter = 'p';
        }

        if ($this->isReturned()) {
            $title = 'Retourné';
            $color = 'orange';
            $letter = 'r';
        }

        if ($this->isLost()) {
            $title = 'Perdu';
            $color = 'purple';
            $letter = '?';
        }

        return '
            <span
                title="'.$title.'"
                class="availability-dot availability-dot-'.$color.'"
            >
                '.$letter.'
            </span>
        ';
    }

    /**
     * @param int $newPrice
     * @throws Exception
     */
    public function editFreePrice(int $newPrice): void
    {
        $article = $this->getArticle();

        if (!$article->has('price_editable')) {
            throw new Exception("Le prix de cet article n'est pas libre.");
        }

        if ($newPrice < $article->get('price')) {
            throw new Exception("Le prix doit être supérieur à ".currency($article->get('price') / 100));
        }

        // TODO: Check that stock item is in cart

        $this->set("stock_selling_price", $newPrice);
    }
}

class StockManager extends EntityManager
{
    protected $prefix = 'stock';
    protected $table = 'stock';
    protected $object = 'Stock';

    public function create(array $defaults = [])
    {
        if (!isset($defaults['site_id'])) {
            $defaults['site_id'] = $this->site['site_id'];
        }

        return parent::create($defaults);
    }

    public function getAll(array $where = [], array $options = [], $withJoins = true)
    {
        if (!isset($where['site_id'])) {
            $where['stock`.`site_id'] = $this->site['site_id'];
        }

        return parent::getAll($where, $options, $withJoins);
    }

    public function count(array $where = [])
    {
        $where['stock`.`site_id'] = $this->site['site_id'];

        return parent::count($where);
    }

    /**
     * Calculates price without VAT & VAT based on price and tax rate.
     *
     * @param [type] $stock [description]
     */
    public function calculateTax($stock)
    {
        $rate = $this->getTaxRate($stock);
        $price = $stock->get('selling_price');
        $coeff = 1 + ($rate / 100);
        $price_without_tax = round($price / $coeff);
        $tax = $price - $price_without_tax;

        $stock->set('stock_tva_rate', $rate);
        $stock->set('stock_selling_price_ht', $price_without_tax);
        $stock->set('stock_selling_price_tva', $tax);

        return $stock;
    }

    /**
     * Calculates tax rate based product type and date of sell.
     *
     * @param [type] $stock [description]
     */
    public function getTaxRate($stock)
    {
        // If site doesn't use TVA, no tax
        if (!$this->site['site_tva']) {
            return 0;
        }
        $sellerCountry = $this->site['site_tva'];

        // If customer country is unknown, use seller's
        $customerCountry = $sellerCountry;
        $order = $stock->get('order');
        if ($order) {
            $country = $order->get('country');
            if (is_object($country)) {
                $customerCountry = $country->get('code');
            }
        }

        // Set product type
        $tax_type = 'STANDARD';
        $article = $stock->get('article');
        if ($article) {
            $type = $article->getType();
            if ($type) {
                $tax_type = $type->getTax();
            }
        }

        // If no selling_date defined, use current date
        $dateOfSale = new \DateTime($stock->get('selling_date'));
        if (!$dateOfSale) {
            $dateOfSale = new \DateTime();
        }

        $tax = new Tax(
            $sellerCountry,
            $customerCountry,
            constant('\Biblys\EuroTax::' . $tax_type),
            $dateOfSale
        );

        return $tax->getTaxRate();
    }

    /**
     * Return an array of copies grouped by article.
     */
    public static function groupByArticles(array $copies)
    {
        $articles = [];
        foreach ($copies as $copy) {
            $articleId = $copy->get('article_id');
            if (!array_key_exists($articleId, $articles)) {
                $articles[$articleId] = [
                    'article' => $copy->getArticle(),
                    'unit_price' => $copy->get('selling_price'),
                    'quantity' => 1,
                ];
            } else {
                ++$articles[$articleId]['quantity'];
            }
        }

        return $articles;
    }
}
