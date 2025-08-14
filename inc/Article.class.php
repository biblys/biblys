<?php /** @noinspection SqlCheckUsingColumns */
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


/** @noinspection PhpParameterNameChangedDuringInheritanceInspection */

use Biblys\Data\ArticleType;
use Biblys\Contributor\Contributor;
use Biblys\Contributor\Job;
use Biblys\Contributor\UnknownJobException;
use Biblys\EuroTax;
use Biblys\Exception\ArticleAlreadyInRayonException;
use Biblys\Exception\InvalidEntityException;
use Biblys\Exception\InvalidEntityFetchedException;
use Biblys\Isbn\Isbn;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Slug\SlugService;
use Model\PeopleQuery;
use Symfony\Component\HttpFoundation\Request;

class Article extends Entity
{
    private ?Media $cover = null;
    private ?Lang $langOriginal = null;
    private ?Country $originCountry = null;
    private array $awards = [];
    protected $prefix = 'article';

    public static array $AVAILABILITY_DILICOM_VALUES = [
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

    public static array $ARTICLE_TYPES = [
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
    private array $files;
    private array $otherContributors;
    private array $authors;
    private array $contributors;

    public function __construct($data, $withJoins = true)
    {
        /* JOINS */

        if ($withJoins) {

            // Collection (OneToMany)
            if (isset($data['collection_id'])) {
                $com = new CollectionManager();
                $com->disableSiteFilters();
                $data['collection'] = $com->getById($data['collection_id']);
                if ($data['collection'] === false) {
                    throw new Exception(
                        "Invalid collection ".$data["collection_id"]." for article ".$data["article_id"]
                    );
                }
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
     * @throws Exception
     */
    public function getModel(): \Model\Article
    {
        $model = new \Model\Article();
        $model->setId($this->get("id"));
        $model->setItem($this->get("item"));
        $model->setTypeId($this->get("type_id"));
        $model->setPrice($this->get("price"));
        $model->setPubdate($this->get("pubdate"));
        $model->setAvailabilityDilicom($this->get("availability_dilicom"));

        $model->setPublisherId($this->get("publisher_id"));
        $model->setCollectionId($this->get("collection_id"));
        $model->setCycleId($this->get("cycle_id"));

        return $model;
    }

    /**
     * @throws InvalidEntityFetchedException
     */
    public function validateOnFetch(): void
    {
        if ($this->isBeingCreated()) {
            return;
        }

        if (!$this->has("publisher_id")) {
            throw new InvalidEntityFetchedException(
                "missing publisher_id",
                "Article",
                $this
            );
        }
    }

    /**
     * Display deprecated message when calling get('people')
     * @throws Exception
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
     * Get all article contributors
     * @return Contributor[]
     * @throws InvalidEntityException
     * @throws UnknownJobException
     * @throws Exception
     */
    public function getContributors(): array
    {
        if (isset($this->contributors)) {
            return $this->contributors;
        }

        $this->contributors = [];

        $rm = new RoleManager();
        $roles = $rm->getAll(["article_id" => $this->get("id")]);
        foreach ($roles as $role) {
            $people = PeopleQuery::create()->findPk($role->get("people_id"));

            if ($people === null) {
                throw new InvalidEntityException(
                    sprintf(
                        "Cannot load article %s with invalid contribution: contributor %s does not exist",
                        $this->get("id"),
                        $role->get("people_id"),
                    )
                );
            }

            $job = Job::getById($role->get("job_id"));
            $this->contributors[] = new Contributor($people, $job, $role->get("id"));
        }

        return $this->contributors;
    }

    /**
     * Get all article contributors except authors
     * @return Contributor[]
     * @throws InvalidEntityException
     * @throws UnknownJobException
     */
    public function getAuthors(): array
    {
        if (!isset($this->authors)) {
            $contributors = $this->getContributors();
            $this->authors = [];
            foreach ($contributors as $contributor) {
                if ($contributor->isAuthor()) {
                    $this->authors[] = $contributor;
                }
            }
        }

        return $this->authors;
    }

    /**
     * Returns true if article has other (than authors) contributors
     * @return bool
     * @throws InvalidEntityException
     * @throws UnknownJobException
     */
    public function hasAuthors(): bool
    {
        return !empty($this->getAuthors());
    }

    /**
     * Get all article contributors except authors
     * @return Contributor[]
     * @throws InvalidEntityException
     * @throws UnknownJobException
     */
    public function getOtherContributors(): array
    {
        if (!isset($this->otherContributors)) {
            $contributors = $this->getContributors();
            $this->otherContributors = [];
            foreach ($contributors as $contributor) {
                if (!$contributor->isAuthor()) {
                    $this->otherContributors[] = $contributor;
                }
            }
        }

        return $this->otherContributors;
    }

    /**
     * Returns true if article has other (than authors) contributors
     * @return bool
     * @throws InvalidEntityException
     * @throws UnknownJobException
     */
    public function hasOtherContributors(): bool
    {
        $otherContributors = $this->getOtherContributors();
        return !empty($otherContributors);
    }

    /**
     * Get CFRewards including this article
     * @return CFReward[]
     * @throws Exception
     */
    public function getCFRewards(): array
    {
        global $_SQL;
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $result = array();
        $rewards = $_SQL->query('SELECT * FROM `cf_rewards` WHERE `reward_articles` LIKE "%' . $this->get('id') . '%" AND `site_id` = "' . $globalSite->get('id') . '"');
        while ($r = $rewards->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new CFReward($r);
        }

        return $result;
    }

    /**
     * Get article linked posts
     * @return Post[]
     * @throws Exception
     */
    public function getPosts(): array
    {
        global $_SQL;
        $pm = new PostManager();

        $posts = [];
        $linksQuery = $_SQL->query('SELECT `post_id` FROM `links` WHERE `article_id` = ' . $this->get('id') . ' AND `post_id` IS NOT NULL');
        while ($link = $linksQuery->fetch(PDO::FETCH_ASSOC)) {
            $post = $pm->get(["post_id" => $link["post_id"]]);
            if ($post) {
                $posts[] = $post;
            }
        }

        return $posts;
    }

    /**
     * Get article cover
     * @throws Exception
     * @deprecated Article->getCover is deprecated. Use ImagesService instead.
     */
    public function getCover($size = null, $ignoreDeprecation = false): Media|string|null
    {
        if (!$ignoreDeprecation) {
            trigger_deprecation(
                "biblys/biblys",
                "2.83.0",
                "Article->getCover is deprecated, use ImagesService instead."
            );
        }

        if (!isset($this->cover)) {
            $this->cover = new Media("article", $this->get("id"), ignoreDeprecation: true);
        }

        if ($size === null) {
            return $this->cover;
        }

        if ($size === "object") {
            return $this->cover;
        }

        if ($size === "url") {
            return $this->getCoverUrl();
        }

        return $this->getCoverTag(["size" => $size]);
    }

    /**
     * Returns true if article has a cover
     * @return bool
     * @throws Exception
     * @deprecated Article->getCover is deprecated, use ImagesService instead.
     */
    public function hasCover(): bool
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.83.0",
            "Article->hasCover is deprecated, use ImagesService instead."
        );

        $cover = $this->getCover(ignoreDeprecation: true);
        return $cover->exists();
    }

    /**
     * Returns an HTML IMG tag with link
     * @param array $options (link, size, class)
     * @return string
     * @throws Exception
     *
     * @deprecated Article->getCoverTag is deprecated. Use
     *             {% include "AppBundle:Article:_cover.html.twig"%} instead
     */
    public function getCoverTag(array $options = []): string
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.67.0",
            "Article->getCoverTag is deprecated. Use {% include \"AppBundle:Article:_cover.html.twig\" %} instead",
        );

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
     * @throws Exception
     * @deprecated
     */
    public function getCoverUrl(array $options = []): ?string
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.83.0",
            "Article->getCoverUrl is deprecated, use ImagesService instead."
        );

        if (!$this->hasCover()) {
            return null;
        }

        $urlOptions = [];

        if ($this->get('cover_version') > 1) {
            $urlOptions["version"] = $this->get('cover_version');
        }

        if (isset($options["size"])) {
            $urlOptions["size"] = $options["size"];
        }

        return $this->cover->getUrl($urlOptions);
    }

    /**
     * @throws Exception
     */
    public function getIsbn(): string
    {
        return Isbn::convertToIsbn13($this->get('ean'));
    }

    /**
     * Returns paper linked article if ebook or ebook if papier
     * @throws Exception
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
     * @return Stock[]
     * @throws Exception
     */
    public function getStock($mode = 'all'): array
    {
        

        $sm = new StockManager();
        $stock = $sm->getAll([
            'article_id' => $this->get('id')
        ], [
            'order' => 'stock_insert'
        ]);
        $result = array();

        if ($mode == 'available') {
            foreach ($stock as $s) {
                if (!$s->get('purchase_date') || $s->get('selling_date') || $s->get('return_date') || $s->get('lost_date') || $s->get('site_id') != LegacyCodeHelper::getGlobalSite()['site_id']) {
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

    /**
     * @return Stock[]
     * @throws Exception
     */
    public function getAvailableItems($condition = 'all', $options = []): array
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

    /**
     * @throws Exception
     */
    public function getCheapestAvailableItem($condition = 'all'): ?Stock
    {
        $copies = $this->getAvailableItems($condition, [
            'order' => 'stock_selling_price'
        ]);

        if (count($copies) > 0) {
            return $copies[0];
        }

        return null;
    }

    /**
     * @return File[]
     * @throws Exception
     */
    public function getDownloadableFiles($type = "all"): array
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

    /**
     * @throws Exception
     */
    public function hasDownloadableFiles($type = "all"): bool
    {
        $files = $this->getDownloadableFiles($type);
        if (count($files)) {
            return true;
        }
        return false;
    }

    /**
     * @throws Exception
     */
    public function isInCart(): bool
    {

        return LegacyCodeHelper::getGlobalVisitor()->hasInCart('article', $this->get('id'));
    }

    /**
     * @throws Exception
     */
    public function isInWishlist(): bool
    {

        return LegacyCodeHelper::getGlobalVisitor()->hasAWish($this->get('id'));
    }

    /**
     * @throws Exception
     */
    public function isinAlerts(): bool
    {

        return LegacyCodeHelper::getGlobalVisitor()->hasAlert($this->get('id'));
    }

    /**
     * @throws Exception
     */
    public function isAvailable(): bool
    {
        return ($this->get('availability_dilicom') == 1 || $this->get('availability_dilicom') == 9);
    }

    /**
     * @throws Exception
     */
    public function isComingSoon(): bool
    {
        return (($this->isAvailable() && !$this->isPublished()) || $this->get('availability_dilicom') == 2);
    }

    /**
     * @throws Exception
     */
    public function isToBeReprinted(): bool
    {
        return ($this->get('availability_dilicom') == 3);
    }

    /**
     * @throws Exception
     */
    public function isSoldOut(): bool
    {
        return ($this->get('availability_dilicom') == 6);
    }

    /**
     * @throws Exception
     */
    public function isSoonUnavailable(): bool
    {
        return ($this->get('availability_dilicom') == 9);
    }

    /**
     * @throws Exception
     */
    public function isPrivatelyPrinted(): bool
    {
        return ($this->get('availability_dilicom') == 10);
    }

    /**
     * @throws Exception
     */
    public function getAvailabilityLed(): string
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

    /**
     * @throws Exception
     */
    public function getAvailabilityText(): string
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
     * @throws Exception
     */
    public function isPurchasable(): bool
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
     * @throws Exception
     */
    public function isPublished(): bool
    {
        if (!$this->has('article_pubdate')) {
            return true;
        }

        $today = new DateTime('today');
        $releaseDate = new DateTime($this->get('pubdate'));
        return $releaseDate <= $today;
    }

    /**
     * Returns true if article can be preordered
     * @return bool
     */
    public function isPreorderable(): bool
    {
        return $this->has('preorder');
    }

    /**
     * Returns true if article has a physical type
     * @return bool
     * @throws Exception
     */
    public function isPhysical(): bool
    {
        $type = $this->get('type_id');
        if ($type == 2 || $type == 10 || $type == 11) {
            return false;
        }
        return true;
    }

    /**
     * Returns true if article is downloadable (not physical type)
     * @return bool
     * @throws Exception
     */
    public function isDownloadable(): bool
    {
        return !$this->isPhysical();
    }

    /**
     * Returns true if article price equals 0
     * @return bool
     * @throws Exception
     */
    public function isFree(): bool
    {
        if ($this->get('price') == 0) {
            return true;
        }
        return false;
    }

    /**
     * @return Tag[]
     * @throws Exception
     */
    public function getTags(): array
    {
        global $_SQL;

        /** @noinspection SqlCheckUsingColumns */
        $sql = $_SQL->prepare("
            SELECT `tag_id`, `tag_name`, `tag_url` 
            FROM `tags_articles` 
            JOIN `tags` ON `tags_articles`.`tag_id` = `tags`.`tag_id` 
            WHERE `tags_articles`.`article_id` = :article_id 
            ORDER BY `tag_name`
        ");
        $sql->execute([':article_id' => $this->get('id')]);
        $tags = $sql->fetchAll();

        $the_tags = [];
        foreach ($tags as $tag) {
            $the_tags[] = new Tag($tag);
        }

        return $the_tags;
    }

    /**
     * @return Rayon[]
     * @throws Exception
     */
    public function getRayons(): array
    {
        global $_SQL;

        $sql = $_SQL->prepare("
            SELECT `rayon_id`, `rayon_name`, `rayon_url`
            FROM `links`
            JOIN `rayons` USING(`rayon_id`)
            WHERE `article_id` = :article_id
                AND `links`.`site_id` = :site_id
                AND `rayons`.`site_id` = :site_id
            ORDER BY `rayon_name`
        ");
        $sql->execute([
            ':article_id' => $this->get('id'),
            ':site_id' => LegacyCodeHelper::getGlobalSite()->get('id')
        ]);
        $rayons = $sql->fetchAll();

        $the_rayons = [];
        foreach ($rayons as $rayon) {
            $the_rayons[] = new Rayon($rayon);
        }

        return $the_rayons;
    }

    /**
     * @throws Exception
     */
    public function hasRayon(Rayon $rayon): bool
    {
        $rayons = $this->getRayons();
        foreach ($rayons as $r) {
            if ($r->get('id') === $rayon->get('id')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws Exception
     * @deprecated Article->getCartButton is deprecated. Use
     *             {% include "AppBundle:Article:_cartButton.html.twig"%} instead
     */
    public function getCartButton($text = false): string
    {
        
        trigger_deprecation(
            "biblys/biblys",
            "2.67.0",
            "Article->getCartButton is deprecated. Use {% include \"AppBundle:Article:_cartButton.html.twig\" %} instead",
        );

        if ($this->get('price') == 0) {
            return '
                <a 
                    href="' . \Biblys\Legacy\LegacyCodeHelper::getGlobalUrlGenerator()->generate('article_free_download', ['id' => $this->get('id')]) . '" 
                    class="btn btn-primary cart-button' . ($text ? '' : ' btn-sm') . '"
                    rel="nofollow"
                >
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

    /**
     * @throws Exception
     */
    public function getWishlistButton($options = []): string
    {
        $text = $options['text'] ?? 'Ajouter à vos envies';

        $button = '<i class="fa-regular fa-heart"></i>&nbsp;' . $text;
        $classes = ' btn btn-outline-secondary';


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
     * @throws Exception
     */
    public function getLangOriginal(): Lang
    {
        if (!$this->langOriginal) {
            $lm = new LangManager();
            /** @var Lang $langOriginal */
            $langOriginal = $lm->getById($this->get('lang_original'));
            $this->langOriginal = $langOriginal;
        }

        return $this->langOriginal;
    }

    /**
     * Return related origin Country
     * @throws Exception
     */
    public function getOriginCountry(): Country
    {
        if (!$this->originCountry) {
            $cm = new CountryManager();
            /** @var Country $originCountry */
            $originCountry = $cm->getById($this->get('origin_country'));
            $this->originCountry = $originCountry;
        }

        return $this->originCountry;
    }

    /**
     * Return awards related to this Article
     * @return Award[]
     * @throws Exception
     */
    public function getAwards(): array
    {
        if (!$this->awards) {
            $am = new AwardManager();
            $this->awards = $am->getAll(['article_id' => $this->get('id')]);
        }

        return $this->awards;
    }

    /**
     * @throws Exception
     */
    public function getShareButtons(array $options = []): string
    {
        
        $request = LegacyCodeHelper::getGlobalRequest();

        $host = $request->getScheme() . '://' . $request->getHost();
        $url = $host . \Biblys\Legacy\LegacyCodeHelper::getGlobalUrlGenerator()->generate('article_show', ['slug' => $this->get('url')]);
        return share_buttons($url, $this->get('title') . ' de ' . $this->get('authors'), $options);
    }

    public function setType(ArticleType $type): void
    {
        $this->set('type_id', $type->getId());
    }

    /**
     * @throws Exception
     */
    public function getType(): bool|ArticleType
    {
        $type_id = $this->get('type_id');
        return ArticleType::getById($type_id);
    }

    /**
     * Calculates tax rate based product type if it were sold today
     * @param [type] $stock [description]
     * @throws Exception
     */
    public function getTaxRate(): bool|int
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        // If site doesn't use TVA, no tax
        if (!$globalSite->get('site_tva')) {
            return 0;
        }
        $sellerCountry = $globalSite->get('site_tva');
        $customerCountry = $globalSite->get('site_tva');

        // Set product type
        $tax_type = "STANDARD";
        $type = $this->getType();
        if ($type) {
            $tax_type = $type->getTax();
        }

        // Use current date for date of sale
        $dateOfSale = new DateTime();

        $tax = new EuroTax($sellerCountry, $customerCountry, constant('\Biblys\EuroTax::' . $tax_type), $dateOfSale);

        return $tax->getTaxRate();
    }

    /**
     * Get article's formatted age min and max
     * @throws Exception
     */
    public function getAgeRange(): ?string
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

    /**
     * @throws Exception
     */
    public function bumpCoverVersion(): Article
    {
        $version = $this->get('cover_version');
        $this->set('article_cover_version', $version + 1);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function countItemsByAvailability(): array
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

    public function isBeingCreated(): bool
    {
        return $this->has("article_editing_user");
    }

    /**
     * METHODS FROM MODEL
     */

    /**
     * @throws Exception
     */
    public function isBundle(): bool
    {
        return $this->getModel()->isBundle();
    }

    /**
     * @throws Exception
     */
    public function getArticlesFromBundle(): \Propel\Runtime\Collection\Collection
    {
        return $this->getModel()->getArticlesFromBundle();
    }

    /**
     * @throws Exception
     */
    public function isInABundle(): bool
    {
        return $this->getModel()->isInABundle();
    }

    /**
     * @throws Exception
     */
    public function getBundles(): \Propel\Runtime\Collection\Collection
    {
        return $this->getModel()->getBundles();
    }

}

class ArticleManager extends EntityManager
{
    protected $prefix = 'article';
    protected $table = 'articles';
    protected $object = 'Article';
    protected bool $ignoreSiteFilters = false;

    /**
     * If set to true, Manager will ignore site filters
     * @param bool $setting true or false (default)
     */
    public function setIgnoreSiteFilters(bool $setting): void
    {
        $this->ignoreSiteFilters = $setting;
    }

    /**
     * Add site filters if any defined
     * @param $where
     * @return mixed
     * @throws Exception
     */
    public function addSiteFilters($where): mixed
    {
        if ($this->ignoreSiteFilters) {
            return $where;
        }

        $currentSite = LegacyCodeHelper::getGlobalSite(ignoreDeprecation: true);

        $publisherFilter = $currentSite->getOpt('publisher_filter');
        if ($publisherFilter && !array_key_exists('publisher_id', $where)) {
            $where['publisher_id'] = explode(',', $publisherFilter);
        }

        $collectionFilterHide = $currentSite->getOpt('collection_filter_hide');
        if ($collectionFilterHide && !array_key_exists('collection_id', $where)) {
            $where['collection_id NOT IN'] = explode(',', $collectionFilterHide);
        }
        return $where;
    }

    /**
     * @throws Exception
     */
    public function getAll(array $where = array(), array $options = array(), $withJoins = true): array
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

    /**
     * @throws Exception
     */
    public function countAll()
    {
        $where = $this->addSiteFilters([]);
        $q = EntityManager::buildSqlQuery($where);
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE ' . $q['where'];
        $res = $this->db->prepare($query);
        $res->execute($q['params']);
        return $res->fetchColumn();
    }

    /**
     * @throws Exception
     */
    public function countAllWithoutSearchTerms()
    {
        $where = $this->addSiteFilters([]);
        $q = EntityManager::buildSqlQuery($where);
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE `article_keywords_generated` IS NULL AND `article_url` IS NOT NULL';
        if (!empty($q['where'])) {
            $query .= ' AND ' . $q['where'];
        }
        $res = $this->db->prepare($query);
        $res->execute($q['params']);
        return $res->fetchColumn();
    }

    /**
     * @throws Exception
     */
    public function countAllFromPeople($people)
    {
        $where = ["article_links" => "LIKE %[people:" . $people->get('id') . "]%"];

        return $this->_countAllForWhere($where);
    }

    /**
     * @throws Exception
     */
    public function getAllFromPeople($people, $options = [], $withJoins = true): array
    {
        $where = ["article_links" => "LIKE %[people:" . $people->get('id') . "]%"];
        return $this->getAll($where, $options, $withJoins);
    }

    /**
     * @throws Exception
     */
    public function countAllFromRayon($rayon)
    {
        $where = ["article_links" => "LIKE %[rayon:" . $rayon->get('id') . "]%"];

        return $this->_countAllForWhere($where);
    }

    /**
     * @throws Exception
     */
    public function getAllFromRayon($rayon, $options = [], $withJoins = true): array
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

    /**
     * @throws Exception
     */
    public function countAllFromTag($tag)
    {
        $where = ["article_links" => "LIKE %[tag:" . $tag->get('id') . "]%"];

        return $this->_countAllForWhere($where);
    }

    /**
     * Get all articles related to this rayon
     * @throws Exception
     */
    public function getAllFromTag($tag, $options = [], bool $withJoins = true): array
    {
        $where = ["article_links" => "LIKE %[tag:" . $tag->get('id') . "]%"];

        return $this->getAll($where, $options, $withJoins);
    }

    /**
     * @throws Exception
     */
    public function _buildSearchQuery($keywords): array
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

    /**
     * @throws Exception
     */
    public function _buildSearchQueryForAvailableStock(
        string      $keywords,
        array       $options = []
    ): array
    {
        $queryWithParams = $this->_buildSearchQuery($keywords);

        $searchCriteria = implode(" AND ", $queryWithParams["query"]);
        $stockCriteria = " AND `stock_selling_date` IS NULL AND `stock_return_date` IS NULL AND `stock_lost_date` IS NULL";

        $options["fields"] = "`articles`.`article_id`, `article_url`, `article_title`, `article_authors`, `publisher_id`, `collection_id`, `cycle_id`, `article_tome`";
        $options["join"] = [["table" => "stock", "key" => "article_id"]];
        $options["group-by"] = "article_id";

        return [
            "query" => $searchCriteria . $stockCriteria,
            "params" => $queryWithParams["params"],
            "options" => $options,
        ];
    }

    /**
     * @throws Exception
     */
    public function countSearchResults($keywords)
    {
        $q = $this->_buildSearchQuery($keywords);

        $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE ' . implode(' AND ', $q['query']);
        $res = $this->db->prepare($query);
        $res->execute($q['params']);
        return $res->fetchColumn();
    }

    /**
     * @throws Exception
     */
    public function countSearchResultsForAvailableStock(string $keywords): int
    {
        $queryWithParamsAndOptions = $this->_buildSearchQueryForAvailableStock($keywords);

        $query = "
            SELECT COUNT(DISTINCT(`article_id`)) 
            FROM `$this->table` 
            JOIN `stock` USING(`article_id`)
            WHERE {$queryWithParamsAndOptions["query"]}
        ";
        $res = EntityManager::prepareAndExecute($query, $queryWithParamsAndOptions['params']);
        return $res->fetchColumn();
    }

    /**
     * @throws Exception
     */
    public function search($keywords, $options = []): array
    {
        $q = $this->_buildSearchQuery($keywords);
        return $this->getQuery(implode(' AND ', $q['query']), $q['params'], $options);
    }

    /**
     * @throws Exception
     * @throws Exception
     */
    public function searchWithAvailableStock(
        string      $keywords,
        array       $options = []
    ): array
    {
        $queryWithParamsAndOptions = $this->_buildSearchQueryForAvailableStock($keywords, $options);

        return $this->getQuery(
            $queryWithParamsAndOptions["query"],
            $queryWithParamsAndOptions["params"],
            $queryWithParamsAndOptions["options"]
        );
    }

    /**
     * Add rayon to article (and update article links)
     * @param $article {Article}
     * @param $rayon {Rayon}
     * @throws ArticleAlreadyInRayonException
     * @throws Exception
     */
    public function addRayon($article, $rayon): Entity
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $lm = new LinkManager();

        // Check if article is already in rayon
        $link = $lm->get(['site_id' => $globalSite->get('id'), 'rayon_id' => $rayon->get('id'), 'article_id' => $article->get('id')]);
        if ($link) {
            throw new ArticleAlreadyInRayonException($article->get("title"), $rayon->get("name"));
        }

        // Create link
        $link = $lm->create(['site_id' => $globalSite->get('id'), 'rayon_id' => $rayon->get('id'), 'article_id' => $article->get('id')]);

        // Update article metadata
        $article_links = $article->get('links') . "[rayon:" . $rayon->get('id') . "]";
        $article->set('article_links', $article_links);
        $this->update($article);

        return $link;
    }

    /**
     * Checks that ISBN is valid and isn't already used
     *
     * @param int $articleId Article id
     * @param $articleEan
     * @return bool true if ISBN is valid and not used
     * @throws Exception
     */
    public function checkIsbn(int $articleId, $articleEan): bool
    {
        $articles = $this->search($articleEan);
        if ($articles) {
            $article = $articles[0];

            // If found article has articleId, return true
            if ($articleId === (int)$article->get('id')) {
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
     * @throws Exception
     */
    public function refreshMetadata(Article $article): Article
    {
        global $_SQL;

        $links = "";
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

        $article->set('article_keywords', truncate(text: $keywords, maxLength: 1024, truncateAtSpace: true, lengthInBytes: true))
            ->set('article_links', truncate(text: $links, maxLength: 1024, truncateAtSpace: true, lengthInBytes: true))
            ->set('article_authors', truncate(implode(', ', $authors), 256))
            ->set('article_authors_alphabetic', truncate(implode(', ', $authors_alpha), 256))
            ->set('article_keywords_generated', date('Y-m-d H:i:s'));

        return $article;
    }

    public function beforeDelete($article): void
    {
        global $_SQL;

        if (!$article) {
            throw new Exception('Cet article n\'existe pas');
        }

        // Check for stocks on all sites
        $stock = $_SQL->prepare('SELECT `stock_id` FROM `stock` WHERE `article_id` = :id');
        $stock->execute(['id' => $article->get('id')]);
        $stock = count($stock->fetchAll());

        if ($stock) {
            throw new Exception('Impossible de supprimer cet article car des exemplaires y sont associés.');
        }

        // Check for links on all sites
        $links = $_SQL->prepare('SELECT `link_id`, `tag_id`, `rayon_id` FROM `links` WHERE `article_id` = :id');
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

    /**
     * @throws Exception
     */
    public function preprocess($article): Entity
    {
        /** @var Article $article */
        $ean = $article->get('ean');
        if ($ean) {
            $article->set('article_ean', Isbn::convertToEan13($ean));
        }

        if (!$article->hasAuthors()) {
            return $article;
        }

        // Create article slug
        $slug = $this->_createArticleSlug($article);
        $article->set('article_url', $slug);

        // Truncate authors fields
        $authors = mb_strcut($article->get('authors') ?: "", 0, 256);
        $article->set('article_authors', $authors);
        $authorsAlpha = mb_strcut($article->get('authors_alphabetic') ?: "", 0, 256);
        $article->set('article_authors_alphabetic', $authorsAlpha);

        return $article;
    }

    /**
     * Create an article slug from authors and title
     * @throws Exception
     */
    private function _createArticleSlug(Article $article): string
    {
        if ($article->has('url')) {
            return $article->get('url');
        }

        $authors = $article->getAuthors();


        $slugService = new SlugService();
        $authorsSlug = $slugService->slugify(self::_getAuthorsSegmentForSlug($authors));
        $titleSlug = $slugService->slugify($article->get('title'));
        $slug = "$authorsSlug/$titleSlug";

        // If slug is already used, add article id at the end
        $other = $this->get([
            'article_url' => $slug,
            'article_id' => '!= ' . $article->get('id')
        ]);
        if ($other) {
            $slug .= '_' . $article->get('id');
        }

        return $slug;
    }

    /**
     * @throws Exception
     */
    public static function _getAuthorsSegmentForSlug($authors): string
    {
        if (count($authors) === 1) {
            $firstAuthor = $authors[0];
            return $firstAuthor->getName();
        }

        if (count($authors) > 1) {
            return "collectif";
        }

        throw new Exception("Cannot create url for an article without authors");
    }

    public function validate($article): void
    {
        if ($article->has('authors') && strlen($article->get('authors')) > 256) {
            throw new InvalidEntityException("Le champ Auteurs ne peut pas dépasser 256 caractères.");
        }

        if ($article->has('article_tome') && strlen($article->get('article_tome')) > 12) {
            throw new InvalidEntityException("Le champ Tome ne peut pas dépasser 12 caractères.");
        }

        // If slug is already used
        $other = $this->get(
            [
                'article_url' => $article->get('url'),
                'article_id' => '!= ' . $article->get('id')
            ]
        );
        if ($other) {
            throw new InvalidEntityException('Il existe déjà un article avec l\'url ' . $article->get('url'));
        }
    }

    /**
     * @param Article $article
     * @throws InvalidEntityException
     * @throws Exception
     */
    public function validateBeforeUpdate($article): void
    {
        parent::validateBeforeUpdate($article);

        if ($article->isBeingCreated()) {
            return;
        }

        if (!$article->has("url")) {
            throw new InvalidEntityException("L'article doit avoir une url.");
        }
    }

    /**
     * @param array $where
     * @return mixed
     * @throws Exception
     * @throws Exception
     */
    private function _countAllForWhere(array $where): mixed
    {
        $where = $this->addSiteFilters($where);

        $q = EntityManager::buildSqlQuery($where);
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '` WHERE ' . $q['where'];
        $res = $this->db->prepare($query);
        $res->execute($q['params']);
        return $res->fetchColumn();
    }

    public static function buildUnknownArticle(): Article
    {
        $article = new Article([]);
        $article->set("article_title", "Article inconnu");
        $article->set("type_id", 1);

        return $article;
    }
}
