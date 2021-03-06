<?php

use Biblys\Isbn\Isbn as Isbn;
use Biblys\Article\Type as Type;

class Article extends Entity
{
    private $cover = null;
    private $langOriginal = null;
    private $originCountry = null;
    private $awards;
    protected $prefix = 'article';

    public static $AVAILABILITY_DILICOM_VALUES = [
        0 => "00 - Inconnu",
        1 => "01 - Disponible",
        2 => "02 - Pas encore paru",
        3 => "03 - Réimpression en cours",
        4 => "04 - Non disponible provisoirement",
        // 5 => "Changement distributeur",
        6 => "06 - Arrêt définitif de commercialisation",
        // 7 => "Manque sans date",
        8 => "08 - À reparaître",
        9 => "09 - Bientôt épuisé",
        10 => "10 - Hors commerce"
    ];

    public static $ARTICLE_TYPES = [
        0 => "Inconnu",
        1 => "Livre papier",
        2 => "Livre numérique",
        3 => "CD",
        4 => "DVD",
        5 => "Jeu",
        6 => "Goodies",
        7 => "Drouille",
        8 => "Lot",
        9 => "BD",
        10 => "Abonnement",
        11 => "Livre audio",
        12 => "Carte à code",
        13 => "Périodique"
    ];

    public function __construct($data, $withJoins = true)
    {
        global $_SQL, $_SITE;

        /* JOINS */

        if ($withJoins) {

            // Collection (OneToMany)
            $com = new CollectionManager();
            if (isset($data['collection_id'])) {
                $data['collection'] = $com->getById($data['collection_id']);
            }

            // Publisher (OneToMany)
            $pum = new PublisherManager();
            if (isset($data['publisher_id'])) {
                $data['publisher'] = $pum->getById($data['publisher_id']);
            }

            // Publisher (OneToMany)
            $cym = new CycleManager();
            if (isset($data['cycle_id'])) {
                $data['cycle'] = $cym->getById($data['cycle_id']);
            }
        }

        parent::__construct($data);
    }

    /**
     * Display deprecated message when calling get('people')
     */
    public function get($field)
    {
        if ($field == "people") {
            trigger_error("Article->get('people') is deprecated. Use Article->getContributors() instead");
            return $this->getContributors();
        }
        return parent::get($field);
    }

    /**
     * Add a contributor to article
     * @param People $people
     * @param Integer $job_id
     */
    public function addContributor($people, $jobId)
    {
        $rm = new RoleManager();

        $roleParams = [
            'article_id' => $this->get('id'),
            'people_id' => $people->get('id'),
            'job_id' => $jobId,
        ];
        $role = $rm->get($roleParams);

        if ($role) {
            return $role;
        }

        $role = $rm->create($roleParams);
        unset($this->contributors, $this->authors, $this->otherContributors);

        return $role;
    }

    /**
     * Get all article contributors
     * @return array of People
     */
    public function getContributors()
    {
        if (!isset($this->contributors)) {
            global $_SQL;

            $result = [];
            $query = 'SELECT
                    `people_id`, `people_first_name`, `people_last_name`, `people_url`, `job_id`, `job_name`
                FROM `people` JOIN `roles` USING(`people_id`) JOIN `jobs` USING(`job_id`)
                WHERE
                    `roles`.`article_id` = ' . $this->get('id') . ' AND
                    `people_deleted` IS NULL
                ORDER BY `id`';
            $contributors = $_SQL->query($query);
            while ($c = $contributors->fetch(PDO::FETCH_ASSOC)) {
                $result[] = new People($c);
            }
            $this->contributors = $result;
        }

        return $this->contributors;
    }

    /**
     * Get all article contributors except authors
     * @return array of People
     */
    public function getAuthors()
    {
        if (!isset($this->authors)) {
            $contributors = $this->getContributors();
            $this->authors = [];
            foreach ($contributors as $contributor) {
                if ($contributor->get('job_id') == "1") {
                    $this->authors[] = $contributor;
                }
            }
        }

        return $this->authors;
    }

    /**
     * Get all article contributors except authors
     * @return array of People
     */
    public function getOtherContributors()
    {
        if (!isset($this->otherContributors)) {
            $contributors = $this->getContributors();
            $this->otherContributors = [];
            foreach ($contributors as $contributor) {
                if ($contributor->get('job_id') != "1") {
                    $this->otherContributors[] = $contributor;
                }
            }
        }

        return $this->otherContributors;
    }

    /**
     * Returns true if article has other (than authors) contributors
     * @return boolean
     */
    public function hasOtherContributors()
    {
        $otherContributors = $this->getOtherContributors();
        return !empty($otherContributors);
    }

    /**
     * Get CFRewards including this article
     */
    public function getCFRewards()
    {
        global $_SQL, $site;

        $result = array();
        $rewards = $_SQL->query('SELECT * FROM `cf_rewards` WHERE `reward_articles` LIKE "%' . $this->get('id') . '%" AND `site_id` = "' . $site->get('id') . '"');
        while ($r = $rewards->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new CFReward($r);
        }

        return $result;
    }

    /**
     * Get article linked posts
     */
    public function getPosts()
    {
        global $_SQL;
        $pm = new PostManager();

        $posts = array();
        $links = $_SQL->query('SELECT `post_id` FROM `links` WHERE `article_id` = ' . $this->get('id') . ' AND `post_id` IS NOT NULL');
        while ($l = $links->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = $pm->get(array('post_id' => $l['post_id']));
        }

        return $posts;
    }

    /**
     * Get article cover
     */
    public function getCover($size = 0)
    {
        if (!isset($this->cover)) {
            $this->cover = new Media('article', $this->get('id'));
        }

        if ($this->cover->exists() || 'DEV') {
            if ($size === 'object') {
                return $this->cover;
            } elseif ($size === 'url') {
                return $this->getCoverUrl();
            } else {
                trigger_error('Article.getCover() method is depreciated. Use Article.getCoverTag() instead.');
                return '<a href="' . $this->getCoverUrl() . '" rel="fancybox"><img src="' . $this->getCoverUrl($size) . '" alt="' . $this->get('title') . '"></a>';
            }
        }
    }

    /**
     * Returns true if article has a cover
     * @return boolean
     */
    public function hasCover()
    {
        $cover = $this->getCover("object");
        return $cover->exists();
    }

    /**
     * Returns an HTML IMG tag with link
     * @param  array $options (link, size, class)
     * @return string
     */
    public function getCoverTag(array $options = [])
    {
        $cover = $this->getCover("object");
        if (!$cover->exists()) {
            throw new Exception("No cover at " . $cover->path() . " for article " . $this->get("id"));
        }

        if (!isset($options["link"])) {
            $options["link"] = $this->getCoverUrl();
        }

        if (!isset($options["size"])) {
            $options["size"] = null;
        }

        $sizeAttribute = '';
        if (isset($options["height"])) {
            $options["size"] = "h" . $options["height"];
            $sizeAttribute = ' height="' . $options["height"] . '"';
        } elseif (isset($options["width"])) {
            $options["size"] = "w" . $options["width"];
            $sizeAttribute = ' width="' . $options["width"] . '"';
        }

        $class = "";
        if (isset($options["class"])) {
            $class = ' class="' . $options["class"] . '"';
        }

        $rel = "";
        if (isset($options["rel"])) {
            $rel = ' rel="' . $options["rel"] . '"';
        }

        $coverTag = '<img src="' . $this->getCoverUrl($options) . '"' . $class . ' alt="' . $this->get('title') . '"' . $sizeAttribute . '>';

        if ($options['link'] !== false) {
            $coverTag = '<a href="' . $options["link"] . '"' . $rel . '>' . $coverTag . '</a>';
        }

        return $coverTag;
    }

    /**
     * Return article's cover url
     */
    public function getCoverUrl(array $options = [])
    {
        if (!$this->hasCover()) {
            return null;
        }

        $urlOptions = [];

        if ($this->get('cover_version') > 1) {
            $urlOptions["version"] = $this->get('cover_version');
        }

        $size = null;
        if (isset($options["size"])) {
            $urlOptions["size"] = $options["size"];
        }

        return $this->cover->getUrl($urlOptions);
    }

    public function getIsbn()
    {
        return Isbn::convertToIsbn13($this->get('ean'));
    }

    /**
     * Returns paper linked article if ebook or ebook if papier
     */
    public function getOtherVersion()
    {
        $item = $this->get('item');
        if (!$item) {
            return false;
        }

        $am = new ArticleManager();
        return $am->get(array('article_item' => $item, 'article_id' => '!= ' . $this->get('id'), 'publisher_id' => $this->get('publisher_id')));
    }

    /**
     * Get article stock
     */
    public function getStock($mode = 'all')
    {
        global $_SITE;

        $sm = new StockManager();
        $stock = $sm->getAll([
            'article_id' => $this->get('id')
        ], [
            'order' => 'stock_insert'
        ]);
        $result = array();

        if ($mode == 'available') {
            foreach ($stock as $s) {
                if (!$s->get('purchase_date') || $s->get('selling_date') || $s->get('return_date') || $s->get('lost_date') || $s->get('site_id') != $_SITE['site_id']) {
                    continue;
                } else {
                    $uid = $s->get('selling_price') . '-' . $s->get('condition');
                    $result[$uid] = $s;
                }
            }
        } else {
            $result = $stock;
        }

        return $result;
    }

    public function getAvailableItems($condition = 'all', $options = [])
    {
        $sm = new StockManager();

        $where = ['article_id' => $this->get('id')];

        if ($condition === 'new') {
            $where['stock_condition'] = 'Neuf';
        }

        if ($condition === 'used') {
            $where['stock_condition'] = '!= Neuf';
        }

        $copies = $sm->getAll($where, $options);
        $results = [];

        foreach ($copies as $copy) {
            if ($copy->isAvailable() && !$copy->isReserved()) {
                $results[] = $copy;
            }
        }

        return $results;
    }

    public function getCheapestAvailableItem($condition = 'all')
    {
        $copies = $this->getAvailableItems($condition, [
            'order' => 'stock_selling_price'
        ]);

        if (count($copies) > 0) {
            return $copies[0];
        }

        return false;
    }

    public function getDownloadableFiles($type = "all")
    {
        if (!isset($this->files)) {
            $fm = new FileManager();
            $this->files = $fm->getAll(["article_id" => $this->get('id')]);
        }

        if ($type == "all") {
            return $this->files;
        }

        $result = [];
        foreach ($this->files as $file) {
            if (
                ($file->get('access') == "1" && $type == "paid") ||
                ($file->get('access') == "0" && $type == "free")
            ) {
                $result[] = $file;
            }
        }

        return $result;
    }

    public function hasDownloadableFiles($type = "all")
    {
        $files = $this->getDownloadableFiles($type);
        if (count($files)) {
            return true;
        }
        return false;
    }

    public function isInCart()
    {
        global $_V;
        return $_V->hasInCart('article', $this->get('id'));
    }

    public function isInWishlist()
    {
        global $_V;
        return $_V->hasAWish($this->get('id'));
    }

    public function isinAlerts()
    {
        global $_V;
        return $_V->hasAlert($this->get('id'));
    }

    public function isAvailable()
    {
        return ($this->get('availability_dilicom') == 1 || $this->get('availability_dilicom') == 9);
    }

    public function isComingSoon()
    {
        return (($this->isAvailable() && !$this->isPublished()) || $this->get('availability_dilicom') == 2);
    }

    public function isToBeReprinted()
    {
        return ($this->get('availability_dilicom') == 3);
    }

    public function isSoldOut()
    {
        return ($this->get('availability_dilicom') == 6);
    }

    public function isSoonUnavailable()
    {
        return ($this->get('availability_dilicom') == 9);
    }

    public function isPrivatelyPrinted()
    {
        return ($this->get('availability_dilicom') == 10);
    }

    public function getAvailabilityLed()
    {
        if ($this->isSoldOut()) {
            return '<img src="/common/img/square_red.png" title="Épuisé" alt="Épuisé">';
        }

        if ($this->isSoonUnavailable()) {
            return '<img src="/common/img/square_orange.png" title="Bientôt épuisé" alt="Bientôt épuisé">';
        }

        if (!$this->isPublished() && $this->isPreorderable()) {
            return '<img src="/common/img/square_blue.png" title="En précommande" alt="En précommande">';
        }

        if (!$this->isPublished()) {
            return '<img src="/common/img/square_blue.png" title="À paraître" alt="À paraître">';
        }

        if ($this->isAvailable()) {
            return '<img src="/common/img/square_green.png" title="Disponible" alt="Disponible">';
        }

        return '<img src="/common/img/square_red.png" title="Épuisé" alt="Épuisé">';
    }

    public function getAvailabilityText()
    {
        if ($this->isSoldOut()) {
            return 'Épuisé';
        }

        if ($this->isSoonUnavailable()) {
            return 'Bientôt épuisé';
        }

        if (!$this->isPublished() && $this->isPreorderable()) {
            return 'En précommande';
        }

        if ($this->isComingSoon()) {
            return 'À paraître';
        }

        if ($this->isAvailable()) {
            return 'Disponible';
        }

        return 'Épuisé';
    }

    /**
     * Test if article is Purchasable (if cart button should be displayed)
     */
    public function isPurchasable()
    {
        if ($this->isSoonUnavailable()) {
            return true;
        }

        if (!$this->isPublished() && $this->isPreorderable()) {
            return true;
        }

        if (!$this->isPublished()) {
            return false;
        }

        if ($this->isAvailable()) {
            return true;
        }

        return false;
    }

    /**
     * Returns true if pubdate is lower than today
     * @return boolean
     */
    public function isPublished()
    {
        $today = new DateTime('tomorrow');
        return ($this->get('pubdate') < $today->format('Y-m-d'));
    }

    /**
     * Returns true if article can be preorder
     * @return boolean
     */
    public function isPreorderable()
    {
        return $this->has('preorder');
    }

    public function getDeletionUser()
    {
        $deletion_user_id = $this->get('article_deletion_by');
        if ($deletion_user_id) {
            $um = new UserManager();
            $user = $um->getById($deletion_user_id);
            if ($user) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Returns true if article has a physical type
     * @return boolean
     */
    public function isPhysical()
    {
        $type = $this->get('type_id');
        if ($type == 2 || $type == 10 || $type == 11) {
            return false;
        }
        return true;
    }

    /**
     * Returns true if article is downloadable (not physical type)
     * @return boolean
     */
    public function isDownloadable()
    {
        return !$this->isPhysical();
    }

    /**
     * Returns true if article price equals 0
     * @return boolean
     */
    public function isFree()
    {
        if ($this->get('price') == 0) {
            return true;
        }
        return false;
    }

    public function getTags()
    {
        global $_SQL;

        $sql = $_SQL->prepare("SELECT `tag_id`, `tag_name`, `tag_url` FROM `links` JOIN `tags` USING(`tag_id`) WHERE `article_id` = :article_id ORDER BY `tag_name`");
        $sql->execute([':article_id' => $this->get('id')]);
        $tags = $sql->fetchAll();

        $the_tags = [];
        foreach ($tags as $tag) {
            $the_tags[] = new Tag($tag);
        }

        return $the_tags;
    }

    /**
     * @return Array of Rayon
     */
    public function getRayons()
    {
        global $_SQL, $_SITE;

        $sql = $_SQL->prepare("
            SELECT `rayon_id`, `rayon_name`, `rayon_url`
            FROM `links`
            JOIN `rayons` USING(`rayon_id`)
            WHERE `article_id` = :article_id
                AND `links`.`site_id` = :site_id
                AND `link_deleted` IS NULL
                AND `rayons`.`site_id` = :site_id
                AND `rayon_deleted` IS NULL
            ORDER BY `rayon_name`
        ");
        $sql->execute([
            ':article_id' => $this->get('id'),
            ':site_id' => $_SITE->get('id')
        ]);
        $rayons = $sql->fetchAll();

        $the_rayons = [];
        foreach ($rayons as $rayon) {
            $the_rayons[] = new Rayon($rayon);
        }

        return $the_rayons;
    }

    public function getRayonsAsJsArray()
    {
        $rayons = array_slice($this->getRayons(), 0, 5);
        $rayonNames = array_map(function ($rayon) {
            return '"' . $rayon->get('name') . '"';
        }, $rayons);
        return "[" . join($rayonNames, ",") . "]";
    }

    public function hasRayon(Rayon $rayon)
    {
        $rayons = $this->getRayons();
        foreach ($rayons as $r) {
            if ($r->get('id') === $rayon->get('id')) {
                return true;
            }
        }

        return false;
    }

    public function getCartButton($text = false)
    {
        global $urlgenerator;

        if ($this->get('price') == 0) {
            return '
                <a href="' . $urlgenerator->generate('article_free_download', ['id' => $this->get('id')]) . '" class="btn btn-primary cart-button' . ($text ? '' : ' btn-sm') . '">
                    <span class="fa fa-cloud-download"></span>' . ($text ? ' <span class="cart-button-text">' . $text . '</span>' : '') . '
                </a>
            ';
        }

        return '
            <a class="btn btn-primary' . ($text ? '' : ' btn-sm') . ' cart-button add_to_cart event"
                data-type="article" data-id="' . $this->get('id') . '">
                <span class="fa fa-shopping-cart"></span>' . ($text ? ' <span class="cart-button-text">' . $text . '</span>' : '') . '
            </a>
        ';
    }

    public function getWishlistButton($options = [])
    {
        $text = isset($options['text']) ? $options['text'] : 'Ajouter à vos envies';

        $button = '<i class="fa fa-heart-o"></i>&nbsp;' . $text . '';
        $classes = ' btn btn-default';


        if (isset($options['image'])) {
            $button = '<img src="' . $options['image'] . '" alt="' . $text . '">';
            $classes = '';
        }

        return '
            <a data-wish="' . $this->get('id') . '" class="event' . $classes . '" title="' . $text . '">
                ' . $button . '
            </a>
        ';
    }

    /**
     * Return related Language entity
     * @return Language
     */
    public function getLangOriginal()
    {
        if (!$this->langOriginal) {
            $lm = new LangManager();
            $this->langOriginal = $lm->getById($this->get('lang_original'));
        }

        return $this->langOriginal;
    }

    /**
     * Return related origin Country
     * @return Country
     */
    public function getOriginCountry()
    {
        if (!$this->originCountry) {
            $cm = new CountryManager();
            $this->originCountry = $cm->getById($this->get('origin_country'));
        }

        return $this->originCountry;
    }

    /**
     * Return awards related to this Article
     * @return Array of Awards
     */
    public function getAwards()
    {
        if (!$this->awards) {
            $am = new AwardManager();
            $this->awards = $am->getAll(['article_id' => $this->get('id')]);
        }

        return $this->awards;
    }

    public function getShareButtons(array $options = [])
    {
        global $request, $urlgenerator;

        $host = $request->getScheme() . '://' . $request->getHost();
        $url = $host . $urlgenerator->generate('article_show', ['slug' => $this->get('url')]);
        return share_buttons($url, $this->get('title') . ' de ' . $this->get('authors'), $options);
    }

    public function setType(Type $type)
    {
        $this->set('type_id', $type->getId());
    }

    public function getType()
    {
        $type_id = $this->get('type_id');
        return Type::getById($type_id);
    }

    /**
     * Calculates tax rate based product type if it were sold today
     * @param [type] $stock [description]
     */
    public function getTaxRate()
    {
        global $site;

        // If site doesn't use TVA, no tax
        if (!$site->get('site_tva')) {
            return 0;
        }
        $sellerCountry = $site->get('site_tva');
        $customerCountry = $site->get('site_tva');

        // Set product type
        $tax_type = "STANDARD";
        $type = $this->getType();
        if ($type) {
            $tax_type = $type->getTax();
        }

        // Use current date for date of sale
        $dateOfSale = new \DateTime();

        $tax = new \Biblys\EuroTax($sellerCountry, $customerCountry, constant('\Biblys\EuroTax::' . $tax_type), $dateOfSale);

        return $tax->getTaxRate();
    }

    /**
     * Set article's publisher
     * @param publisher Publisher: the publisher object to set as article's publisher
     * @return Article: the current article
     */
    public function setPublisher(Publisher $publisher)
    {
        $this->set('publisher_id', $publisher->get('id'));
        $this->set('article_publisher', $publisher->get('name'));

        return $this;
    }

    /**
     * Set article's collection
     * @param collection Collection: the collection object to set as article's collection
     * @return Article: the current article
     */
    public function setCollection(Collection $collection)
    {
        $this->set('collection_id', $collection->get('id'));
        $this->set('article_collection', $collection->get('name'));

        return $this->setPublisher($collection->getPublisher());
    }

    /**
     * Get article's formatted age min and max
     */
    public function getAgeRange()
    {
        if ($this->has('age_min') && $this->has('age_max')) {
            return 'de ' . $this->get('age_min') . ' à ' . $this->get('age_max') . ' ans';
        }

        if ($this->has('age_min')) {
            return $this->get('age_min') . ' ans et plus';
        }

        if ($this->has('age_max')) {
            return 'jusqu\'à ' . $this->get('age_max') . ' ans';
        }

        return null;
    }

    function bumpCoverVersion()
    {
        $version = $this->get('cover_version');
        $this->set('article_cover_version', $version + 1);
        return $this;
    }

    function countItemsByAvailability()
    {
        $items = $this->getStock();

        $total = count($items);
        $sold = 0;
        $lost = 0;
        $returned = 0;
        $available = 0;
        $inCart = 0;

        foreach ($items as $item) {
            if ($item->isAvailable()) {
                $available++;
            } elseif ($item->isSold()) {
                $sold++;
            } elseif ($item->isLost()) {
                $lost++;
            } elseif ($item->isReturned()) {
                $returned++;
            }

            if ($item->isInCart()) {
                $inCart++;
            }
        }

        return [
            "total" => $total,
            "available" => $available,
            "sold" => $sold,
            "lost" => $lost,
            "returned" => $returned,
            "inCart" => $inCart,
        ];
    }
}

class ArticleManager extends EntityManager
{
    protected $prefix = 'article';
    protected $table = 'articles';
    protected $object = 'Article';
    protected $ignoreSiteFilters = false;

    /**
     * If set to true, Manager will ignore site filters
     * @param boolean $setting true or false (default)
     */
    public function setIgnoreSiteFilters($setting)
    {
        $this->ignoreSiteFilters = $setting;
    }

    /**
     * Add site filters if any defined
     * @param [type] $where [description]
     */
    public function addSiteFilters($where)
    {
        if ($this->ignoreSiteFilters) {
            return $where;
        }

        global $site;

        $publisherFilter = $site->getOpt('publisher_filter');
        if ($publisherFilter && !array_key_exists('publisher_id', $where)) {
            $where['publisher_id'] = explode(',', $publisherFilter);
        }

        $collectionFilterHide = $site->getOpt('collection_filter_hide');
        if ($collectionFilterHide && !array_key_exists('collection_id', $where)) {
            $where['collection_id NOT IN'] = explode(',', $collectionFilterHide);
        }
        return $where;
    }

    public function getAll(array $where = array(), array $options =  array(), $withJoins = true)
    {
        $query = array();
        $params = array();
        $i = 0;

        foreach ($where as $key => $val) {
            if ($key == 'rayon_id') {
                $query[] = '`article_links` LIKE :rayon' . $i;
                $params['rayon' . $i] = '%[rayon:' . $val . ']%';
            }
            $i++;
        }

        $where = $this->addSiteFilters($where);

        if (!empty($query)) {
            $query = implode(' AND ', $query);
            return $this->getQuery($query, $params, $options, $withJoins);
        } else {
            return parent::getAll($where, $options, $withJoins);
        }
    }

    public function countAll()
    {
        $where = $this->addSiteFilters([]);
        $q = EntityManager::buildSqlQuery($where);
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE `' . $this->prefix . '_deleted` IS NULL AND ' . $q['where'];
        $res = $this->db->prepare($query);
        $res->execute($q['params']);
        return $res->fetchColumn();
    }

    public function countAllWithoutSearchTerms()
    {
        $where = $this->addSiteFilters([]);
        $q = EntityManager::buildSqlQuery($where);
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE `' . $this->prefix . '_deleted` IS NULL AND `article_keywords_generated` IS NULL AND `article_url` IS NOT NULL';
        if (!empty($q['where'])) {
            $query .=  ' AND ' . $q['where'];
        }
        $res = $this->db->prepare($query);
        $res->execute($q['params']);
        return $res->fetchColumn();
    }

    public function countAllFromPeople($people)
    {
        $where = ["article_links" => "LIKE %[people:" . $people->get('id') . "]%"];

        $where = $this->addSiteFilters($where);

        $q = EntityManager::buildSqlQuery($where);
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE `' . $this->prefix . '_deleted` IS NULL AND ' . $q['where'];
        $res = $this->db->prepare($query);
        $res->execute($q['params']);
        return $res->fetchColumn();
    }

    public function getAllFromPeople($people, $options = [], $withJoins = true)
    {
        $where = ["article_links" => "LIKE %[people:" . $people->get('id') . "]%"];
        return $this->getAll($where, $options, $withJoins);
    }

    public function countAllFromRayon($rayon)
    {
        $where = ["article_links" => "LIKE %[rayon:" . $rayon->get('id') . "]%"];

        $where = $this->addSiteFilters($where);

        $q = EntityManager::buildSqlQuery($where);
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE `' . $this->prefix . '_deleted` IS NULL AND ' . $q['where'];
        $res = $this->db->prepare($query);
        $res->execute($q['params']);
        return $res->fetchColumn();
    }

    public function getAllFromRayon($rayon, $options = [], $withJoins = true)
    {
        $where = ["article_links" => "LIKE %[rayon:" . $rayon->get('id') . "]%"];

        if ($rayon->has("sort_by")) {
            $options["order"] = "article_" . $rayon->get("sort_by");
        }

        if ($rayon->has("sort_order") && $rayon->get("sort_order") == 1) {
            $options["sort"] = "desc";
        }

        if ($rayon->has("show_upcoming") && $rayon->get("show_upcoming") == 1) {
            $where["article_pubdate"] = "< " . date("Y-m-d H:i:s");
        }

        return $this->getAll($where, $options, $withJoins);
    }

    public function countAllFromTag($tag)
    {
        $where = ["article_links" => "LIKE %[tag:" . $tag->get('id') . "]%"];

        $where = $this->addSiteFilters($where);

        $q = EntityManager::buildSqlQuery($where);
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE `' . $this->prefix . '_deleted` IS NULL AND ' . $q['where'];
        $res = $this->db->prepare($query);
        $res->execute($q['params']);
        return $res->fetchColumn();
    }

    /**
     * Get all articles related to this rayon
     * @param  [type]  $rayon     [description]
     * @param  [type]  $options   [description]
     * @param  boolean $withJoins [description]
     * @return array of Articles
     */
    public function getAllFromTag($tag, $options = [], $withJoins = true)
    {
        $where = ["article_links" => "LIKE %[tag:" . $tag->get('id') . "]%"];

        return $this->getAll($where, $options, $withJoins);
    }

    public function buildSearchQuery($keywords)
    {
        $query = array();
        $params = array();

        $filters = $this->addSiteFilters([]);
        if (count($filters)) {
            $filters = EntityManager::buildSqlQuery($filters);
            $query[] = $filters['where'];
            $params = array_merge($params, $filters['params']);
        }

        $i = 0;
        $keywords = explode(' ', $keywords);
        foreach ($keywords as $k) {
            $query[] = '`article_keywords` LIKE :keyword' . $i;
            $params['keyword' . $i] = '%' . $k . '%';
            $i++;
        }

        return ['query' => $query, 'params' => $params];
    }

    public function countSearchResults($keywords)
    {
        $q = $this->buildSearchQuery($keywords);

        $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE `' . $this->prefix . '_deleted` IS NULL AND ' . implode(' AND ', $q['query']);
        $res = $this->db->prepare($query);
        $res->execute($q['params']);
        return $res->fetchColumn();
    }

    public function search($keywords, $options = [])
    {
        $q = $this->buildSearchQuery($keywords);
        return $this->getQuery(implode(' AND ', $q['query']), $q['params'], $options);
    }

    /**
     * Returns articles thats need to be pushed to Biblys Data
     * @return {array} of Article
     */
    public function getArticlesToBePushedToData()
    {
        return $this->getQuery('
            `article_ean` IS NOT NULL AND `article_ean` != 0 AND
            (
                `article_pushed_to_data` < `article_updated` OR
                `article_pushed_to_data` IS NULL
            )
        ', [], [
            "limit" => 100,
            "order" => "article_updated"
        ], false);
    }

    /**
     * Pushes an article to data server
     * @param $article {Article} the article to push
     */
    public function pushToDataServer(Article $article)
    {
        global $config;

        if (!$article->has('ean')) {
            throw new Exception("Article does not have an EAN");
        }

        $dataConfig = $config->get('data');

        if (!$dataConfig) {
            return;
        }

        $book = new \Biblys\Data\Book();
        $book->setEan($article->get('ean'));
        $book->setTitle($article->get('title'));

        // Add publisher
        $pm = new PublisherManager();
        $article_publisher = $pm->getById($article->get('publisher_id'));
        $publisher = new \Biblys\Data\Publisher();
        $publisher->setName($article_publisher->get('name'));
        $book->setPublisher($publisher);

        // Add authors
        $authors = $article->getAuthors();
        foreach ($authors as $author) {
            $contributor = new \Biblys\Data\Contributor();
            $contributor->setFirstName($author->get('first_name'));
            $contributor->setLastName($author->get('last_name'));
            $book->addAuthor($contributor);
        }

        $dataParams = ["apiKey" => $dataConfig["api_key"]];
        if (isset($dataConfig["server"])) {
            $dataParams["server"] = $dataConfig["server"];
        }

        $bdc = new \Biblys\Data\Client($dataParams);
        $bdc->push($book);

        $article->set('article_pushed_to_data', date('Y-d-m H:i:s'));
        $this->update($article, null, false);
    }

    /**
     * Add rayon to article (and update article links)
     * @param $article {Article}
     * @param $rayon {Rayon}
     */
    public function addRayon($article, $rayon)
    {
        global $site;

        $lm = new LinkManager();

        // Check if article is already in rayon
        $link = $lm->get(['site_id' => $site->get('id'), 'rayon_id' => $rayon->get('id'), 'article_id' => $article->get('id')]);
        if ($link) {
            throw new Exception("L'article « " . $article->get('title') . " » est déjà dans le rayon « " . $this->get('name') . " ».");
        }

        // Create link
        $link = $lm->create(['site_id' => $site->get('id'), 'rayon_id' => $rayon->get('id'), 'article_id' => $article->get('id')]);

        // Update article metadata
        $article_links = $article->get('links') . "[rayon:" . $rayon->get('id') . "]";
        $article->set('article_links', $article_links);
        $this->update($article);

        return $link;
    }

    /**
     * Checks that ISBN is valid and isn't already used
     *
     * @param int $articleId  Article id
     * @param int $ean        Article EAN
     *
     * @return bool true if ISBN is valid and not used
     */
    public function checkIsbn($articleId, $articleEan)
    {
        global $_SQL;

        $articles = $this->search($articleEan);
        if ($articles) {
            $article = $articles[0];

            // If found article has articleId, return true
            if ($articleId === $article->get('id')) {
                return true;
            }

            throw new Exception(
                'Cet ISBN est déjà utilisé par un autre article :
                <a href="/' . $article->get('url') . '">' . $article->get('title') . '</a>'
            );
        }

        return true;
    }

    /**
     * Hook to push to data server whenever article is updated
     */
    public function update($article, $reason = null, $pushToBiblysData = true)
    {
        $article = parent::update($article);

        global $config;
        $dataConfig = $config->get('data');

        if ($dataConfig && $pushToBiblysData && $article->has('ean')) {
            $this->pushToDataServer($this->reload($article));
        }

        return $article;
    }

    public function refreshMetadata(Article $article)
    {
        global $_SQL;

        $links = null;
        $authors = array();
        $authors_alpha = array();

        $pm = new PeopleManager();

        $keywords = $article->get('title') . ' '
            . $article->get('title_original') . ' '
            . $article->get('title_others') . ' '
            . $article->get('subtitle') . ' '
            . $article->get('ean') . ' '
            . $article->get('ean_others');

        if ($article->has('collection')) {
            $collection = $article->get('collection');
            $collectionName = $collection;
            if ($collection instanceof Collection) {
                $collectionName = $collection->get('name');
            }
            $keywords .= $collectionName . ' ';
            $article->set('article_collection', $collectionName);
        }

        if ($article->has('publisher')) {
            $publisher = $article->get('publisher');
            $publisherName = $publisher;
            if ($publisher instanceof Publisher) {
                $publisherName = $publisher->get('name');
            }
            $keywords .= $publisherName . ' ';
            $article->set('article_publisher', $publisherName);
        }

        if ($article->has('cycle')) {
            $cycle = $article->get('cycle');
            if ($cycle instanceof Cycle) {
                $keywords .= $cycle->get('name') . ' ';
            } else {
                $keywords .= $cycle . ' ';
            }
        }

        $peoples = $_SQL->query("SELECT `people_id`, `job_id` FROM `roles` JOIN `people` USING(`people_id`) WHERE `article_id` = " . $article->get('id'));
        while ($p = $peoples->fetch()) {
            $people = $pm->getById($p["people_id"]);
            $keywords .= ' ' . $people->get('name');
            $links .= ' [people:' . $people->get('id') . ']';
            if ($p['job_id'] == 1) {
                $authors[] = $people->get('name');
                $authors_alpha[] = $people->get('alpha');
            }
        }

        $tags = $article->getLinked('tag');
        foreach ($tags as $tag) {
            $keywords .= ' ' . $tag->get('name');
            $links .= ' [tag:' . $tag->get('id') . ']';
        }

        $rayons = $article->getLinked('rayon');
        foreach ($rayons as $rayon) {
            $links .= ' [rayon:' . $rayon->get('id') . ']';
        }

        $hides = $_SQL->query("SELECT `site_id` FROM `links` WHERE `article_id` = " . $article->get('id') . " AND `link_hide` = 1");
        while ($h = $hides->fetch()) {
            $links .= ' [hide:' . $h["site_id"] . ']';
        }

        if ($article->has('publisher_id')) {
            $onorders = $_SQL->query("SELECT `links`.`site_id` FROM `links` JOIN `suppliers` USING(`supplier_id`) WHERE `publisher_id` = " . $article->get('publisher_id') . " AND `supplier_on_order` = 1 AND `supplier_deleted` IS NULL");
            while ($oo = $onorders->fetch()) {
                $links .= ' [onorder:' . $oo["site_id"] . ']';
            }
        }

        // EANs of downloadable files
        $fm = new FileManager();
        if ($dlfiles = $fm->getAll(["article_id" => $article->get('id')])) {
            foreach ($dlfiles as $f) {
                if ($f->has('file_ean')) {
                    $keywords .= ' ' . $f->get('ean');
                }
            }
        }

        // Bundle
        if ($article->get('type_id') == 8) {
            $bundle = $_SQL->query("SELECT `article_title`, `article_ean` FROM `articles` JOIN `links` USING(`article_id`) WHERE `bundle_id` = '" . $article->get('id') . "'");
            while ($bu = $bundle->fetch()) {
                $keywords .= ' ' . $bu["article_title"] . ' ' . $bu["article_ean"];
            }
            $keywords .= ' lot';
        }

        $article->set('article_keywords', $keywords)
            ->set('article_links', $links)
            ->set('article_authors', truncate(implode(', ', $authors), 256))
            ->set('article_authors_alphabetic', truncate(implode(', ', $authors_alpha), 256))
            ->set('article_keywords_generated', date('Y-m-d H:i:s'));

        return $article;
    }

    public function beforeDelete($article)
    {
        global $_SQL;

        if (!$article) {
            throw new Exception('Cet article n\'existe pas');
        }

        // Check for stocks on all sites
        $stock = $_SQL->prepare('SELECT `stock_id` FROM `stock` WHERE `article_id` = :id AND `stock_deleted` IS NULL');
        $stock->execute(['id' => $article->get('id')]);
        $stock = count($stock->fetchAll());

        if ($stock) {
            throw new Exception('Impossible de supprimer cet article car des exemplaires y sont associés.');
        }

        // Check for links on all sites
        $links = $_SQL->prepare('SELECT `link_id`, `tag_id`, `rayon_id` FROM `links` WHERE `article_id` = :id AND `link_deleted` IS NULL');
        $links->execute(['id' => $article->get('id')]);
        $other_links = [];
        foreach ($links as $link) {
            if (isset($link['tag_id']) || isset($link['rayon_id'])) {
                continue;
            }

            $other_links[] = $link;
        }

        if ($other_links) {
            throw new Exception('Impossible de supprimer cet article car des éléments y sont liés.');
        }
    }

    public function preprocess($article)
    {

        $ean = $article->get('ean');
        if ($ean) {
            $article->set('article_ean', Isbn::convertToEan13($ean));
        }

        // Create article slug
        if (!$article->has('url')) {
            $authors = explode(", ", $article->get('authors'));
            $num_authors = count($authors);
            if ($num_authors == 0) {
                $authors_url = "Anonyme";
            } elseif ($num_authors == 1) {
                $authors_url = $authors[0];
            } else {
                $authors_url = "Collectif";
            }
            if (empty($authors_url)) {
                $authors_url = 'anonyme';
            }
            $url = makeurl($authors_url) . '/' . makeurl($article->get('title'));

            // If slug is already used, add article id at the end
            $other = $this->get(
                [
                    'article_url' => $url,
                    'article_id' => '!= ' . $article->get('id')
                ]
            );
            if ($other) {
                $url .= '_' . $article->get('id');
            }

            $article->set('article_url', $url);
        }

        // Truncate authors fields
        $authors = mb_strcut($article->get('authors'), 0, 256);
        $article->set('article_authors', $authors);
        $authorsAlpha = mb_strcut($article->get('authors_alphabetic'), 0, 256);
        $article->set('article_authors_alphabetic', $authorsAlpha);

        return $article;
    }

    public function validate($article)
    {
        if (!$article->has('url')) {
            throw new Exception("L'article doit avoir une url.");
        }

        if (strlen($article->get('authors')) > 256) {
            throw new Exception("Le champ Auteurs ne peut pas dépasser 256 caractères.");
        }

        // If slug is already used
        $other = $this->get(
            [
                'article_url' => $article->get('url'),
                'article_id' => '!= ' . $article->get('id')
            ]
        );
        if ($other) {
            throw new Exception('Il existe déjà un article avec cette l\'url ' . $article->get('url'));
        }
    }
}
