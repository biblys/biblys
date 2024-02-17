<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\ArticleCategory as ChildArticleCategory;
use Model\ArticleCategoryQuery as ChildArticleCategoryQuery;
use Model\Cart as ChildCart;
use Model\CartQuery as ChildCartQuery;
use Model\CrowdfundingCampaign as ChildCrowdfundingCampaign;
use Model\CrowdfundingCampaignQuery as ChildCrowdfundingCampaignQuery;
use Model\CrowfundingReward as ChildCrowfundingReward;
use Model\CrowfundingRewardQuery as ChildCrowfundingRewardQuery;
use Model\Invitation as ChildInvitation;
use Model\InvitationQuery as ChildInvitationQuery;
use Model\Option as ChildOption;
use Model\OptionQuery as ChildOptionQuery;
use Model\Order as ChildOrder;
use Model\OrderQuery as ChildOrderQuery;
use Model\Page as ChildPage;
use Model\PageQuery as ChildPageQuery;
use Model\Payment as ChildPayment;
use Model\PaymentQuery as ChildPaymentQuery;
use Model\Right as ChildRight;
use Model\RightQuery as ChildRightQuery;
use Model\Session as ChildSession;
use Model\SessionQuery as ChildSessionQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\Stock as ChildStock;
use Model\StockQuery as ChildStockQuery;
use Model\Map\ArticleCategoryTableMap;
use Model\Map\CartTableMap;
use Model\Map\CrowdfundingCampaignTableMap;
use Model\Map\CrowfundingRewardTableMap;
use Model\Map\InvitationTableMap;
use Model\Map\OptionTableMap;
use Model\Map\OrderTableMap;
use Model\Map\PageTableMap;
use Model\Map\PaymentTableMap;
use Model\Map\RightTableMap;
use Model\Map\SessionTableMap;
use Model\Map\SiteTableMap;
use Model\Map\StockTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'sites' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Site implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\SiteTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var bool
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var bool
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = [];

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = [];

    /**
     * The value for the site_id field.
     *
     * @var        int
     */
    protected $site_id;

    /**
     * The value for the site_name field.
     *
     * Note: this column has a database default value of: ''
     * @var        string|null
     */
    protected $site_name;

    /**
     * The value for the site_pass field.
     *
     * Note: this column has a database default value of: ''
     * @var        string|null
     */
    protected $site_pass;

    /**
     * The value for the site_title field.
     *
     * Note: this column has a database default value of: ''
     * @var        string|null
     */
    protected $site_title;

    /**
     * The value for the site_domain field.
     *
     * @var        string|null
     */
    protected $site_domain;

    /**
     * The value for the site_version field.
     *
     * @var        string|null
     */
    protected $site_version;

    /**
     * The value for the site_tag field.
     *
     * Note: this column has a database default value of: ''
     * @var        string|null
     */
    protected $site_tag;

    /**
     * The value for the site_flag field.
     *
     * @var        string|null
     */
    protected $site_flag;

    /**
     * The value for the site_contact field.
     *
     * Note: this column has a database default value of: ''
     * @var        string|null
     */
    protected $site_contact;

    /**
     * The value for the site_address field.
     *
     * Note: this column has a database default value of: ''
     * @var        string|null
     */
    protected $site_address;

    /**
     * The value for the site_tva field.
     *
     * @var        string|null
     */
    protected $site_tva;

    /**
     * The value for the site_html_renderer field.
     *
     * @var        boolean|null
     */
    protected $site_html_renderer;

    /**
     * The value for the site_axys field.
     *
     * Note: this column has a database default value of: true
     * @var        boolean|null
     */
    protected $site_axys;

    /**
     * The value for the site_noosfere field.
     *
     * @var        boolean|null
     */
    protected $site_noosfere;

    /**
     * The value for the site_amazon field.
     *
     * @var        boolean|null
     */
    protected $site_amazon;

    /**
     * The value for the site_event_id field.
     *
     * @var        int|null
     */
    protected $site_event_id;

    /**
     * The value for the site_event_date field.
     *
     * @var        int|null
     */
    protected $site_event_date;

    /**
     * The value for the site_shop field.
     *
     * @var        boolean|null
     */
    protected $site_shop;

    /**
     * The value for the site_vpc field.
     *
     * @var        boolean|null
     */
    protected $site_vpc;

    /**
     * The value for the site_shipping_fee field.
     *
     * @var        string|null
     */
    protected $site_shipping_fee;

    /**
     * The value for the site_wishlist field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $site_wishlist;

    /**
     * The value for the site_payment_cheque field.
     *
     * Note: this column has a database default value of: true
     * @var        boolean|null
     */
    protected $site_payment_cheque;

    /**
     * The value for the site_payment_paypal field.
     *
     * @var        string|null
     */
    protected $site_payment_paypal;

    /**
     * The value for the site_payment_payplug field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $site_payment_payplug;

    /**
     * The value for the site_payment_transfer field.
     *
     * @var        boolean|null
     */
    protected $site_payment_transfer;

    /**
     * The value for the site_bookshop field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $site_bookshop;

    /**
     * The value for the site_bookshop_id field.
     *
     * @var        int|null
     */
    protected $site_bookshop_id;

    /**
     * The value for the site_publisher field.
     *
     * @var        boolean|null
     */
    protected $site_publisher;

    /**
     * The value for the site_publisher_stock field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $site_publisher_stock;

    /**
     * The value for the publisher_id field.
     *
     * @var        int|null
     */
    protected $publisher_id;

    /**
     * The value for the site_ebook_bundle field.
     *
     * @var        int|null
     */
    protected $site_ebook_bundle;

    /**
     * The value for the site_fb_page_id field.
     *
     * @var        string|null
     */
    protected $site_fb_page_id;

    /**
     * The value for the site_fb_page_token field.
     *
     * @var        string|null
     */
    protected $site_fb_page_token;

    /**
     * The value for the site_analytics_id field.
     *
     * @var        string|null
     */
    protected $site_analytics_id;

    /**
     * The value for the site_piwik_id field.
     *
     * @var        int|null
     */
    protected $site_piwik_id;

    /**
     * The value for the site_sitemap_updated field.
     *
     * @var        DateTime|null
     */
    protected $site_sitemap_updated;

    /**
     * The value for the site_monitoring field.
     *
     * Note: this column has a database default value of: true
     * @var        boolean|null
     */
    protected $site_monitoring;

    /**
     * The value for the site_created field.
     *
     * @var        DateTime|null
     */
    protected $site_created;

    /**
     * The value for the site_updated field.
     *
     * @var        DateTime|null
     */
    protected $site_updated;

    /**
     * @var        ObjectCollection|ChildCart[] Collection to store aggregation of ChildCart objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildCart> Collection to store aggregation of ChildCart objects.
     */
    protected $collCarts;
    protected $collCartsPartial;

    /**
     * @var        ObjectCollection|ChildCrowdfundingCampaign[] Collection to store aggregation of ChildCrowdfundingCampaign objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildCrowdfundingCampaign> Collection to store aggregation of ChildCrowdfundingCampaign objects.
     */
    protected $collCrowdfundingCampaigns;
    protected $collCrowdfundingCampaignsPartial;

    /**
     * @var        ObjectCollection|ChildCrowfundingReward[] Collection to store aggregation of ChildCrowfundingReward objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildCrowfundingReward> Collection to store aggregation of ChildCrowfundingReward objects.
     */
    protected $collCrowfundingRewards;
    protected $collCrowfundingRewardsPartial;

    /**
     * @var        ObjectCollection|ChildInvitation[] Collection to store aggregation of ChildInvitation objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildInvitation> Collection to store aggregation of ChildInvitation objects.
     */
    protected $collInvitations;
    protected $collInvitationsPartial;

    /**
     * @var        ObjectCollection|ChildOption[] Collection to store aggregation of ChildOption objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildOption> Collection to store aggregation of ChildOption objects.
     */
    protected $collOptions;
    protected $collOptionsPartial;

    /**
     * @var        ObjectCollection|ChildOrder[] Collection to store aggregation of ChildOrder objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildOrder> Collection to store aggregation of ChildOrder objects.
     */
    protected $collOrders;
    protected $collOrdersPartial;

    /**
     * @var        ObjectCollection|ChildPage[] Collection to store aggregation of ChildPage objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildPage> Collection to store aggregation of ChildPage objects.
     */
    protected $collPages;
    protected $collPagesPartial;

    /**
     * @var        ObjectCollection|ChildPayment[] Collection to store aggregation of ChildPayment objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildPayment> Collection to store aggregation of ChildPayment objects.
     */
    protected $collPayments;
    protected $collPaymentsPartial;

    /**
     * @var        ObjectCollection|ChildArticleCategory[] Collection to store aggregation of ChildArticleCategory objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildArticleCategory> Collection to store aggregation of ChildArticleCategory objects.
     */
    protected $collArticleCategories;
    protected $collArticleCategoriesPartial;

    /**
     * @var        ObjectCollection|ChildRight[] Collection to store aggregation of ChildRight objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildRight> Collection to store aggregation of ChildRight objects.
     */
    protected $collRights;
    protected $collRightsPartial;

    /**
     * @var        ObjectCollection|ChildSession[] Collection to store aggregation of ChildSession objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildSession> Collection to store aggregation of ChildSession objects.
     */
    protected $collSessions;
    protected $collSessionsPartial;

    /**
     * @var        ObjectCollection|ChildStock[] Collection to store aggregation of ChildStock objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildStock> Collection to store aggregation of ChildStock objects.
     */
    protected $collStocks;
    protected $collStocksPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCart[]
     * @phpstan-var ObjectCollection&\Traversable<ChildCart>
     */
    protected $cartsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCrowdfundingCampaign[]
     * @phpstan-var ObjectCollection&\Traversable<ChildCrowdfundingCampaign>
     */
    protected $crowdfundingCampaignsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCrowfundingReward[]
     * @phpstan-var ObjectCollection&\Traversable<ChildCrowfundingReward>
     */
    protected $crowfundingRewardsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildInvitation[]
     * @phpstan-var ObjectCollection&\Traversable<ChildInvitation>
     */
    protected $invitationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOption[]
     * @phpstan-var ObjectCollection&\Traversable<ChildOption>
     */
    protected $optionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOrder[]
     * @phpstan-var ObjectCollection&\Traversable<ChildOrder>
     */
    protected $ordersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPage[]
     * @phpstan-var ObjectCollection&\Traversable<ChildPage>
     */
    protected $pagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPayment[]
     * @phpstan-var ObjectCollection&\Traversable<ChildPayment>
     */
    protected $paymentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildArticleCategory[]
     * @phpstan-var ObjectCollection&\Traversable<ChildArticleCategory>
     */
    protected $articleCategoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRight[]
     * @phpstan-var ObjectCollection&\Traversable<ChildRight>
     */
    protected $rightsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSession[]
     * @phpstan-var ObjectCollection&\Traversable<ChildSession>
     */
    protected $sessionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildStock[]
     * @phpstan-var ObjectCollection&\Traversable<ChildStock>
     */
    protected $stocksScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
        $this->site_name = '';
        $this->site_pass = '';
        $this->site_title = '';
        $this->site_tag = '';
        $this->site_contact = '';
        $this->site_address = '';
        $this->site_axys = true;
        $this->site_wishlist = false;
        $this->site_payment_cheque = true;
        $this->site_payment_payplug = false;
        $this->site_bookshop = false;
        $this->site_publisher_stock = false;
        $this->site_monitoring = true;
    }

    /**
     * Initializes internal state of Model\Base\Site object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return bool True if the object has been modified.
     */
    public function isModified(): bool
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param string $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return bool True if $col has been modified.
     */
    public function isColumnModified(string $col): bool
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns(): array
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return bool True, if the object has never been persisted.
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param bool $b the state of the object.
     */
    public function setNew(bool $b): void
    {
        $this->new = $b;
    }

    /**
     * Whether this object has been deleted.
     * @return bool The deleted state of this object.
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param bool $b The deleted state of this object.
     * @return void
     */
    public function setDeleted(bool $b): void
    {
        $this->deleted = $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified(?string $col = null): void
    {
        if (null !== $col) {
            unset($this->modifiedColumns[$col]);
        } else {
            $this->modifiedColumns = [];
        }
    }

    /**
     * Compares this with another <code>Site</code> instance.  If
     * <code>obj</code> is an instance of <code>Site</code>, delegates to
     * <code>equals(Site)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param mixed $obj The object to compare to.
     * @return bool Whether equal to the object specified.
     */
    public function equals($obj): bool
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns(): array
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return bool
     */
    public function hasVirtualColumn(string $name): bool
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return mixed
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getVirtualColumn(string $name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of nonexistent virtual column `%s`.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @param mixed $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn(string $name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param string $msg
     * @param int $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log(string $msg, int $priority = Propel::LOG_INFO): void
    {
        Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param \Propel\Runtime\Parser\AbstractParser|string $parser An AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME, TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to TableMap::TYPE_PHPNAME.
     * @return string The exported data
     */
    public function exportTo($parser, bool $includeLazyLoadColumns = true, string $keyType = TableMap::TYPE_PHPNAME): string
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray($keyType, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     *
     * @return array<string>
     */
    public function __sleep(): array
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [site_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->site_id;
    }

    /**
     * Get the [site_name] column value.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->site_name;
    }

    /**
     * Get the [site_pass] column value.
     *
     * @return string|null
     */
    public function getPass()
    {
        return $this->site_pass;
    }

    /**
     * Get the [site_title] column value.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->site_title;
    }

    /**
     * Get the [site_domain] column value.
     *
     * @return string|null
     */
    public function getDomain()
    {
        return $this->site_domain;
    }

    /**
     * Get the [site_version] column value.
     *
     * @return string|null
     */
    public function getVersion()
    {
        return $this->site_version;
    }

    /**
     * Get the [site_tag] column value.
     *
     * @return string|null
     */
    public function getTag()
    {
        return $this->site_tag;
    }

    /**
     * Get the [site_flag] column value.
     *
     * @return string|null
     */
    public function getFlag()
    {
        return $this->site_flag;
    }

    /**
     * Get the [site_contact] column value.
     *
     * @return string|null
     */
    public function getContact()
    {
        return $this->site_contact;
    }

    /**
     * Get the [site_address] column value.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->site_address;
    }

    /**
     * Get the [site_tva] column value.
     *
     * @return string|null
     */
    public function getTva()
    {
        return $this->site_tva;
    }

    /**
     * Get the [site_html_renderer] column value.
     *
     * @return boolean|null
     */
    public function getHtmlRenderer()
    {
        return $this->site_html_renderer;
    }

    /**
     * Get the [site_html_renderer] column value.
     *
     * @return boolean|null
     */
    public function isHtmlRenderer()
    {
        return $this->getHtmlRenderer();
    }

    /**
     * Get the [site_axys] column value.
     *
     * @return boolean|null
     */
    public function getAxys()
    {
        return $this->site_axys;
    }

    /**
     * Get the [site_axys] column value.
     *
     * @return boolean|null
     */
    public function isAxys()
    {
        return $this->getAxys();
    }

    /**
     * Get the [site_noosfere] column value.
     *
     * @return boolean|null
     */
    public function getNoosfere()
    {
        return $this->site_noosfere;
    }

    /**
     * Get the [site_noosfere] column value.
     *
     * @return boolean|null
     */
    public function isNoosfere()
    {
        return $this->getNoosfere();
    }

    /**
     * Get the [site_amazon] column value.
     *
     * @return boolean|null
     */
    public function getAmazon()
    {
        return $this->site_amazon;
    }

    /**
     * Get the [site_amazon] column value.
     *
     * @return boolean|null
     */
    public function isAmazon()
    {
        return $this->getAmazon();
    }

    /**
     * Get the [site_event_id] column value.
     *
     * @return int|null
     */
    public function getEventId()
    {
        return $this->site_event_id;
    }

    /**
     * Get the [site_event_date] column value.
     *
     * @return int|null
     */
    public function getEventDate()
    {
        return $this->site_event_date;
    }

    /**
     * Get the [site_shop] column value.
     *
     * @return boolean|null
     */
    public function getShop()
    {
        return $this->site_shop;
    }

    /**
     * Get the [site_shop] column value.
     *
     * @return boolean|null
     */
    public function isShop()
    {
        return $this->getShop();
    }

    /**
     * Get the [site_vpc] column value.
     *
     * @return boolean|null
     */
    public function getVpc()
    {
        return $this->site_vpc;
    }

    /**
     * Get the [site_vpc] column value.
     *
     * @return boolean|null
     */
    public function isVpc()
    {
        return $this->getVpc();
    }

    /**
     * Get the [site_shipping_fee] column value.
     *
     * @return string|null
     */
    public function getShippingFee()
    {
        return $this->site_shipping_fee;
    }

    /**
     * Get the [site_wishlist] column value.
     *
     * @return boolean|null
     */
    public function getWishlist()
    {
        return $this->site_wishlist;
    }

    /**
     * Get the [site_wishlist] column value.
     *
     * @return boolean|null
     */
    public function isWishlist()
    {
        return $this->getWishlist();
    }

    /**
     * Get the [site_payment_cheque] column value.
     *
     * @return boolean|null
     */
    public function getPaymentCheque()
    {
        return $this->site_payment_cheque;
    }

    /**
     * Get the [site_payment_cheque] column value.
     *
     * @return boolean|null
     */
    public function isPaymentCheque()
    {
        return $this->getPaymentCheque();
    }

    /**
     * Get the [site_payment_paypal] column value.
     *
     * @return string|null
     */
    public function getPaymentPaypal()
    {
        return $this->site_payment_paypal;
    }

    /**
     * Get the [site_payment_payplug] column value.
     *
     * @return boolean|null
     */
    public function getPaymentPayplug()
    {
        return $this->site_payment_payplug;
    }

    /**
     * Get the [site_payment_payplug] column value.
     *
     * @return boolean|null
     */
    public function isPaymentPayplug()
    {
        return $this->getPaymentPayplug();
    }

    /**
     * Get the [site_payment_transfer] column value.
     *
     * @return boolean|null
     */
    public function getPaymentTransfer()
    {
        return $this->site_payment_transfer;
    }

    /**
     * Get the [site_payment_transfer] column value.
     *
     * @return boolean|null
     */
    public function isPaymentTransfer()
    {
        return $this->getPaymentTransfer();
    }

    /**
     * Get the [site_bookshop] column value.
     *
     * @return boolean|null
     */
    public function getBookshop()
    {
        return $this->site_bookshop;
    }

    /**
     * Get the [site_bookshop] column value.
     *
     * @return boolean|null
     */
    public function isBookshop()
    {
        return $this->getBookshop();
    }

    /**
     * Get the [site_bookshop_id] column value.
     *
     * @return int|null
     */
    public function getBookshopId()
    {
        return $this->site_bookshop_id;
    }

    /**
     * Get the [site_publisher] column value.
     *
     * @return boolean|null
     */
    public function getPublisher()
    {
        return $this->site_publisher;
    }

    /**
     * Get the [site_publisher] column value.
     *
     * @return boolean|null
     */
    public function isPublisher()
    {
        return $this->getPublisher();
    }

    /**
     * Get the [site_publisher_stock] column value.
     *
     * @return boolean|null
     */
    public function getPublisherStock()
    {
        return $this->site_publisher_stock;
    }

    /**
     * Get the [site_publisher_stock] column value.
     *
     * @return boolean|null
     */
    public function isPublisherStock()
    {
        return $this->getPublisherStock();
    }

    /**
     * Get the [publisher_id] column value.
     *
     * @return int|null
     */
    public function getPublisherId()
    {
        return $this->publisher_id;
    }

    /**
     * Get the [site_ebook_bundle] column value.
     *
     * @return int|null
     */
    public function getEbookBundle()
    {
        return $this->site_ebook_bundle;
    }

    /**
     * Get the [site_fb_page_id] column value.
     *
     * @return string|null
     */
    public function getFbPageId()
    {
        return $this->site_fb_page_id;
    }

    /**
     * Get the [site_fb_page_token] column value.
     *
     * @return string|null
     */
    public function getFbPageToken()
    {
        return $this->site_fb_page_token;
    }

    /**
     * Get the [site_analytics_id] column value.
     *
     * @return string|null
     */
    public function getAnalyticsId()
    {
        return $this->site_analytics_id;
    }

    /**
     * Get the [site_piwik_id] column value.
     *
     * @return int|null
     */
    public function getPiwikId()
    {
        return $this->site_piwik_id;
    }

    /**
     * Get the [optionally formatted] temporal [site_sitemap_updated] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getSitemapUpdated($format = null)
    {
        if ($format === null) {
            return $this->site_sitemap_updated;
        } else {
            return $this->site_sitemap_updated instanceof \DateTimeInterface ? $this->site_sitemap_updated->format($format) : null;
        }
    }

    /**
     * Get the [site_monitoring] column value.
     *
     * @return boolean|null
     */
    public function getMonitoring()
    {
        return $this->site_monitoring;
    }

    /**
     * Get the [site_monitoring] column value.
     *
     * @return boolean|null
     */
    public function isMonitoring()
    {
        return $this->getMonitoring();
    }

    /**
     * Get the [optionally formatted] temporal [site_created] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getCreatedAt($format = null)
    {
        if ($format === null) {
            return $this->site_created;
        } else {
            return $this->site_created instanceof \DateTimeInterface ? $this->site_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [site_updated] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getUpdatedAt($format = null)
    {
        if ($format === null) {
            return $this->site_updated;
        } else {
            return $this->site_updated instanceof \DateTimeInterface ? $this->site_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [site_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_id !== $v) {
            $this->site_id = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_name !== $v) {
            $this->site_name = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_pass] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPass($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_pass !== $v) {
            $this->site_pass = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_PASS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_title] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_title !== $v) {
            $this->site_title = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_TITLE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_domain] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDomain($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_domain !== $v) {
            $this->site_domain = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_DOMAIN] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_version] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_version !== $v) {
            $this->site_version = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_VERSION] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_tag] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTag($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_tag !== $v) {
            $this->site_tag = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_TAG] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_flag] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFlag($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_flag !== $v) {
            $this->site_flag = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_FLAG] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_contact] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setContact($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_contact !== $v) {
            $this->site_contact = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_CONTACT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_address] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_address !== $v) {
            $this->site_address = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_ADDRESS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_tva] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTva($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_tva !== $v) {
            $this->site_tva = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_TVA] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_html_renderer] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setHtmlRenderer($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_html_renderer !== $v) {
            $this->site_html_renderer = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_HTML_RENDERER] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_axys] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setAxys($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_axys !== $v) {
            $this->site_axys = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_AXYS] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_noosfere] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setNoosfere($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_noosfere !== $v) {
            $this->site_noosfere = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_NOOSFERE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_amazon] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setAmazon($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_amazon !== $v) {
            $this->site_amazon = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_AMAZON] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_event_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEventId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_event_id !== $v) {
            $this->site_event_id = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_EVENT_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_event_date] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEventDate($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_event_date !== $v) {
            $this->site_event_date = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_EVENT_DATE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_shop] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setShop($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_shop !== $v) {
            $this->site_shop = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_SHOP] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_vpc] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setVpc($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_vpc !== $v) {
            $this->site_vpc = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_VPC] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_shipping_fee] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setShippingFee($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_shipping_fee !== $v) {
            $this->site_shipping_fee = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_SHIPPING_FEE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_wishlist] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setWishlist($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_wishlist !== $v) {
            $this->site_wishlist = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_WISHLIST] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_payment_cheque] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentCheque($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_payment_cheque !== $v) {
            $this->site_payment_cheque = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_PAYMENT_CHEQUE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_payment_paypal] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentPaypal($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_payment_paypal !== $v) {
            $this->site_payment_paypal = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_PAYMENT_PAYPAL] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_payment_payplug] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentPayplug($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_payment_payplug !== $v) {
            $this->site_payment_payplug = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_PAYMENT_PAYPLUG] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_payment_transfer] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentTransfer($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_payment_transfer !== $v) {
            $this->site_payment_transfer = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_PAYMENT_TRANSFER] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_bookshop] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setBookshop($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_bookshop !== $v) {
            $this->site_bookshop = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_BOOKSHOP] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_bookshop_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setBookshopId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_bookshop_id !== $v) {
            $this->site_bookshop_id = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_BOOKSHOP_ID] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_publisher] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setPublisher($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_publisher !== $v) {
            $this->site_publisher = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_PUBLISHER] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [site_publisher_stock] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setPublisherStock($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_publisher_stock !== $v) {
            $this->site_publisher_stock = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_PUBLISHER_STOCK] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPublisherId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_id !== $v) {
            $this->publisher_id = $v;
            $this->modifiedColumns[SiteTableMap::COL_PUBLISHER_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_ebook_bundle] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEbookBundle($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_ebook_bundle !== $v) {
            $this->site_ebook_bundle = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_EBOOK_BUNDLE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_fb_page_id] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFbPageId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_fb_page_id !== $v) {
            $this->site_fb_page_id = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_FB_PAGE_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_fb_page_token] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFbPageToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_fb_page_token !== $v) {
            $this->site_fb_page_token = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_FB_PAGE_TOKEN] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_analytics_id] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAnalyticsId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->site_analytics_id !== $v) {
            $this->site_analytics_id = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_ANALYTICS_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_piwik_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPiwikId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_piwik_id !== $v) {
            $this->site_piwik_id = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_PIWIK_ID] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [site_sitemap_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setSitemapUpdated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->site_sitemap_updated !== null || $dt !== null) {
            if ($this->site_sitemap_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->site_sitemap_updated->format("Y-m-d H:i:s.u")) {
                $this->site_sitemap_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[SiteTableMap::COL_SITE_SITEMAP_UPDATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of the [site_monitoring] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setMonitoring($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->site_monitoring !== $v) {
            $this->site_monitoring = $v;
            $this->modifiedColumns[SiteTableMap::COL_SITE_MONITORING] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [site_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->site_created !== null || $dt !== null) {
            if ($this->site_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->site_created->format("Y-m-d H:i:s.u")) {
                $this->site_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[SiteTableMap::COL_SITE_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [site_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->site_updated !== null || $dt !== null) {
            if ($this->site_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->site_updated->format("Y-m-d H:i:s.u")) {
                $this->site_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[SiteTableMap::COL_SITE_UPDATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return bool Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues(): bool
    {
            if ($this->site_name !== '') {
                return false;
            }

            if ($this->site_pass !== '') {
                return false;
            }

            if ($this->site_title !== '') {
                return false;
            }

            if ($this->site_tag !== '') {
                return false;
            }

            if ($this->site_contact !== '') {
                return false;
            }

            if ($this->site_address !== '') {
                return false;
            }

            if ($this->site_axys !== true) {
                return false;
            }

            if ($this->site_wishlist !== false) {
                return false;
            }

            if ($this->site_payment_cheque !== true) {
                return false;
            }

            if ($this->site_payment_payplug !== false) {
                return false;
            }

            if ($this->site_bookshop !== false) {
                return false;
            }

            if ($this->site_publisher_stock !== false) {
                return false;
            }

            if ($this->site_monitoring !== true) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    }

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by DataFetcher->fetch().
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param bool $rehydrate Whether this object is being re-hydrated from the database.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int next starting column
     * @throws \Propel\Runtime\Exception\PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate(array $row, int $startcol = 0, bool $rehydrate = false, string $indexType = TableMap::TYPE_NUM): int
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SiteTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SiteTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SiteTableMap::translateFieldName('Pass', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_pass = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SiteTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SiteTableMap::translateFieldName('Domain', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_domain = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : SiteTableMap::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_version = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : SiteTableMap::translateFieldName('Tag', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_tag = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : SiteTableMap::translateFieldName('Flag', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_flag = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : SiteTableMap::translateFieldName('Contact', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_contact = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : SiteTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : SiteTableMap::translateFieldName('Tva', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_tva = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : SiteTableMap::translateFieldName('HtmlRenderer', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_html_renderer = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : SiteTableMap::translateFieldName('Axys', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_axys = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : SiteTableMap::translateFieldName('Noosfere', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_noosfere = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : SiteTableMap::translateFieldName('Amazon', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_amazon = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : SiteTableMap::translateFieldName('EventId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_event_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : SiteTableMap::translateFieldName('EventDate', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_event_date = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : SiteTableMap::translateFieldName('Shop', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_shop = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : SiteTableMap::translateFieldName('Vpc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_vpc = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : SiteTableMap::translateFieldName('ShippingFee', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_shipping_fee = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : SiteTableMap::translateFieldName('Wishlist', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_wishlist = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : SiteTableMap::translateFieldName('PaymentCheque', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_payment_cheque = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : SiteTableMap::translateFieldName('PaymentPaypal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_payment_paypal = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : SiteTableMap::translateFieldName('PaymentPayplug', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_payment_payplug = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : SiteTableMap::translateFieldName('PaymentTransfer', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_payment_transfer = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : SiteTableMap::translateFieldName('Bookshop', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_bookshop = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 26 + $startcol : SiteTableMap::translateFieldName('BookshopId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_bookshop_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 27 + $startcol : SiteTableMap::translateFieldName('Publisher', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_publisher = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 28 + $startcol : SiteTableMap::translateFieldName('PublisherStock', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_publisher_stock = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 29 + $startcol : SiteTableMap::translateFieldName('PublisherId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 30 + $startcol : SiteTableMap::translateFieldName('EbookBundle', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_ebook_bundle = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 31 + $startcol : SiteTableMap::translateFieldName('FbPageId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_fb_page_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 32 + $startcol : SiteTableMap::translateFieldName('FbPageToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_fb_page_token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 33 + $startcol : SiteTableMap::translateFieldName('AnalyticsId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_analytics_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 34 + $startcol : SiteTableMap::translateFieldName('PiwikId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_piwik_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 35 + $startcol : SiteTableMap::translateFieldName('SitemapUpdated', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->site_sitemap_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 36 + $startcol : SiteTableMap::translateFieldName('Monitoring', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_monitoring = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 37 + $startcol : SiteTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->site_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 38 + $startcol : SiteTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->site_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 39; // 39 = SiteTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Site'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function ensureConsistency(): void
    {
    }

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param bool $deep (optional) Whether to also de-associated any related objects.
     * @param ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload(bool $deep = false, ?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SiteTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSiteQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCarts = null;

            $this->collCrowdfundingCampaigns = null;

            $this->collCrowfundingRewards = null;

            $this->collInvitations = null;

            $this->collOptions = null;

            $this->collOrders = null;

            $this->collPages = null;

            $this->collPayments = null;

            $this->collArticleCategories = null;

            $this->collRights = null;

            $this->collSessions = null;

            $this->collStocks = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Site::setDeleted()
     * @see Site::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SiteTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSiteQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    public function save(?ConnectionInterface $con = null): int
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SiteTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(SiteTableMap::COL_SITE_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(SiteTableMap::COL_SITE_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(SiteTableMap::COL_SITE_UPDATED)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                SiteTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con): int
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->cartsScheduledForDeletion !== null) {
                if (!$this->cartsScheduledForDeletion->isEmpty()) {
                    foreach ($this->cartsScheduledForDeletion as $cart) {
                        // need to save related object because we set the relation to null
                        $cart->save($con);
                    }
                    $this->cartsScheduledForDeletion = null;
                }
            }

            if ($this->collCarts !== null) {
                foreach ($this->collCarts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->crowdfundingCampaignsScheduledForDeletion !== null) {
                if (!$this->crowdfundingCampaignsScheduledForDeletion->isEmpty()) {
                    foreach ($this->crowdfundingCampaignsScheduledForDeletion as $crowdfundingCampaign) {
                        // need to save related object because we set the relation to null
                        $crowdfundingCampaign->save($con);
                    }
                    $this->crowdfundingCampaignsScheduledForDeletion = null;
                }
            }

            if ($this->collCrowdfundingCampaigns !== null) {
                foreach ($this->collCrowdfundingCampaigns as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->crowfundingRewardsScheduledForDeletion !== null) {
                if (!$this->crowfundingRewardsScheduledForDeletion->isEmpty()) {
                    foreach ($this->crowfundingRewardsScheduledForDeletion as $crowfundingReward) {
                        // need to save related object because we set the relation to null
                        $crowfundingReward->save($con);
                    }
                    $this->crowfundingRewardsScheduledForDeletion = null;
                }
            }

            if ($this->collCrowfundingRewards !== null) {
                foreach ($this->collCrowfundingRewards as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->invitationsScheduledForDeletion !== null) {
                if (!$this->invitationsScheduledForDeletion->isEmpty()) {
                    \Model\InvitationQuery::create()
                        ->filterByPrimaryKeys($this->invitationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->invitationsScheduledForDeletion = null;
                }
            }

            if ($this->collInvitations !== null) {
                foreach ($this->collInvitations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->optionsScheduledForDeletion !== null) {
                if (!$this->optionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->optionsScheduledForDeletion as $option) {
                        // need to save related object because we set the relation to null
                        $option->save($con);
                    }
                    $this->optionsScheduledForDeletion = null;
                }
            }

            if ($this->collOptions !== null) {
                foreach ($this->collOptions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ordersScheduledForDeletion !== null) {
                if (!$this->ordersScheduledForDeletion->isEmpty()) {
                    foreach ($this->ordersScheduledForDeletion as $order) {
                        // need to save related object because we set the relation to null
                        $order->save($con);
                    }
                    $this->ordersScheduledForDeletion = null;
                }
            }

            if ($this->collOrders !== null) {
                foreach ($this->collOrders as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pagesScheduledForDeletion !== null) {
                if (!$this->pagesScheduledForDeletion->isEmpty()) {
                    foreach ($this->pagesScheduledForDeletion as $page) {
                        // need to save related object because we set the relation to null
                        $page->save($con);
                    }
                    $this->pagesScheduledForDeletion = null;
                }
            }

            if ($this->collPages !== null) {
                foreach ($this->collPages as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->paymentsScheduledForDeletion !== null) {
                if (!$this->paymentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->paymentsScheduledForDeletion as $payment) {
                        // need to save related object because we set the relation to null
                        $payment->save($con);
                    }
                    $this->paymentsScheduledForDeletion = null;
                }
            }

            if ($this->collPayments !== null) {
                foreach ($this->collPayments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->articleCategoriesScheduledForDeletion !== null) {
                if (!$this->articleCategoriesScheduledForDeletion->isEmpty()) {
                    foreach ($this->articleCategoriesScheduledForDeletion as $articleCategory) {
                        // need to save related object because we set the relation to null
                        $articleCategory->save($con);
                    }
                    $this->articleCategoriesScheduledForDeletion = null;
                }
            }

            if ($this->collArticleCategories !== null) {
                foreach ($this->collArticleCategories as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->rightsScheduledForDeletion !== null) {
                if (!$this->rightsScheduledForDeletion->isEmpty()) {
                    foreach ($this->rightsScheduledForDeletion as $right) {
                        // need to save related object because we set the relation to null
                        $right->save($con);
                    }
                    $this->rightsScheduledForDeletion = null;
                }
            }

            if ($this->collRights !== null) {
                foreach ($this->collRights as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->sessionsScheduledForDeletion !== null) {
                if (!$this->sessionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->sessionsScheduledForDeletion as $session) {
                        // need to save related object because we set the relation to null
                        $session->save($con);
                    }
                    $this->sessionsScheduledForDeletion = null;
                }
            }

            if ($this->collSessions !== null) {
                foreach ($this->collSessions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->stocksScheduledForDeletion !== null) {
                if (!$this->stocksScheduledForDeletion->isEmpty()) {
                    foreach ($this->stocksScheduledForDeletion as $stock) {
                        // need to save related object because we set the relation to null
                        $stock->save($con);
                    }
                    $this->stocksScheduledForDeletion = null;
                }
            }

            if ($this->collStocks !== null) {
                foreach ($this->collStocks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    }

    /**
     * Insert the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con): void
    {
        $modifiedColumns = [];
        $index = 0;

        $this->modifiedColumns[SiteTableMap::COL_SITE_ID] = true;
        if (null !== $this->site_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SiteTableMap::COL_SITE_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SiteTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'site_name';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PASS)) {
            $modifiedColumns[':p' . $index++]  = 'site_pass';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'site_title';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_DOMAIN)) {
            $modifiedColumns[':p' . $index++]  = 'site_domain';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'site_version';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_TAG)) {
            $modifiedColumns[':p' . $index++]  = 'site_tag';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_FLAG)) {
            $modifiedColumns[':p' . $index++]  = 'site_flag';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_CONTACT)) {
            $modifiedColumns[':p' . $index++]  = 'site_contact';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'site_address';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_TVA)) {
            $modifiedColumns[':p' . $index++]  = 'site_tva';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_HTML_RENDERER)) {
            $modifiedColumns[':p' . $index++]  = 'site_html_renderer';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_AXYS)) {
            $modifiedColumns[':p' . $index++]  = 'site_axys';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_NOOSFERE)) {
            $modifiedColumns[':p' . $index++]  = 'site_noosfere';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_AMAZON)) {
            $modifiedColumns[':p' . $index++]  = 'site_amazon';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_EVENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_event_id';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_EVENT_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'site_event_date';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_SHOP)) {
            $modifiedColumns[':p' . $index++]  = 'site_shop';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_VPC)) {
            $modifiedColumns[':p' . $index++]  = 'site_vpc';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_SHIPPING_FEE)) {
            $modifiedColumns[':p' . $index++]  = 'site_shipping_fee';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_WISHLIST)) {
            $modifiedColumns[':p' . $index++]  = 'site_wishlist';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PAYMENT_CHEQUE)) {
            $modifiedColumns[':p' . $index++]  = 'site_payment_cheque';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PAYMENT_PAYPAL)) {
            $modifiedColumns[':p' . $index++]  = 'site_payment_paypal';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PAYMENT_PAYPLUG)) {
            $modifiedColumns[':p' . $index++]  = 'site_payment_payplug';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PAYMENT_TRANSFER)) {
            $modifiedColumns[':p' . $index++]  = 'site_payment_transfer';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_BOOKSHOP)) {
            $modifiedColumns[':p' . $index++]  = 'site_bookshop';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_BOOKSHOP_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_bookshop_id';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PUBLISHER)) {
            $modifiedColumns[':p' . $index++]  = 'site_publisher';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PUBLISHER_STOCK)) {
            $modifiedColumns[':p' . $index++]  = 'site_publisher_stock';
        }
        if ($this->isColumnModified(SiteTableMap::COL_PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_id';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_EBOOK_BUNDLE)) {
            $modifiedColumns[':p' . $index++]  = 'site_ebook_bundle';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_FB_PAGE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_fb_page_id';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_FB_PAGE_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = 'site_fb_page_token';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_ANALYTICS_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_analytics_id';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PIWIK_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_piwik_id';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_SITEMAP_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'site_sitemap_updated';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_MONITORING)) {
            $modifiedColumns[':p' . $index++]  = 'site_monitoring';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'site_created';
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'site_updated';
        }

        $sql = sprintf(
            'INSERT INTO sites (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'site_name':
                        $stmt->bindValue($identifier, $this->site_name, PDO::PARAM_STR);

                        break;
                    case 'site_pass':
                        $stmt->bindValue($identifier, $this->site_pass, PDO::PARAM_STR);

                        break;
                    case 'site_title':
                        $stmt->bindValue($identifier, $this->site_title, PDO::PARAM_STR);

                        break;
                    case 'site_domain':
                        $stmt->bindValue($identifier, $this->site_domain, PDO::PARAM_STR);

                        break;
                    case 'site_version':
                        $stmt->bindValue($identifier, $this->site_version, PDO::PARAM_STR);

                        break;
                    case 'site_tag':
                        $stmt->bindValue($identifier, $this->site_tag, PDO::PARAM_STR);

                        break;
                    case 'site_flag':
                        $stmt->bindValue($identifier, $this->site_flag, PDO::PARAM_STR);

                        break;
                    case 'site_contact':
                        $stmt->bindValue($identifier, $this->site_contact, PDO::PARAM_STR);

                        break;
                    case 'site_address':
                        $stmt->bindValue($identifier, $this->site_address, PDO::PARAM_STR);

                        break;
                    case 'site_tva':
                        $stmt->bindValue($identifier, $this->site_tva, PDO::PARAM_STR);

                        break;
                    case 'site_html_renderer':
                        $stmt->bindValue($identifier, (int) $this->site_html_renderer, PDO::PARAM_INT);

                        break;
                    case 'site_axys':
                        $stmt->bindValue($identifier, (int) $this->site_axys, PDO::PARAM_INT);

                        break;
                    case 'site_noosfere':
                        $stmt->bindValue($identifier, (int) $this->site_noosfere, PDO::PARAM_INT);

                        break;
                    case 'site_amazon':
                        $stmt->bindValue($identifier, (int) $this->site_amazon, PDO::PARAM_INT);

                        break;
                    case 'site_event_id':
                        $stmt->bindValue($identifier, $this->site_event_id, PDO::PARAM_INT);

                        break;
                    case 'site_event_date':
                        $stmt->bindValue($identifier, $this->site_event_date, PDO::PARAM_INT);

                        break;
                    case 'site_shop':
                        $stmt->bindValue($identifier, (int) $this->site_shop, PDO::PARAM_INT);

                        break;
                    case 'site_vpc':
                        $stmt->bindValue($identifier, (int) $this->site_vpc, PDO::PARAM_INT);

                        break;
                    case 'site_shipping_fee':
                        $stmt->bindValue($identifier, $this->site_shipping_fee, PDO::PARAM_STR);

                        break;
                    case 'site_wishlist':
                        $stmt->bindValue($identifier, (int) $this->site_wishlist, PDO::PARAM_INT);

                        break;
                    case 'site_payment_cheque':
                        $stmt->bindValue($identifier, (int) $this->site_payment_cheque, PDO::PARAM_INT);

                        break;
                    case 'site_payment_paypal':
                        $stmt->bindValue($identifier, $this->site_payment_paypal, PDO::PARAM_STR);

                        break;
                    case 'site_payment_payplug':
                        $stmt->bindValue($identifier, (int) $this->site_payment_payplug, PDO::PARAM_INT);

                        break;
                    case 'site_payment_transfer':
                        $stmt->bindValue($identifier, (int) $this->site_payment_transfer, PDO::PARAM_INT);

                        break;
                    case 'site_bookshop':
                        $stmt->bindValue($identifier, (int) $this->site_bookshop, PDO::PARAM_INT);

                        break;
                    case 'site_bookshop_id':
                        $stmt->bindValue($identifier, $this->site_bookshop_id, PDO::PARAM_INT);

                        break;
                    case 'site_publisher':
                        $stmt->bindValue($identifier, (int) $this->site_publisher, PDO::PARAM_INT);

                        break;
                    case 'site_publisher_stock':
                        $stmt->bindValue($identifier, (int) $this->site_publisher_stock, PDO::PARAM_INT);

                        break;
                    case 'publisher_id':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);

                        break;
                    case 'site_ebook_bundle':
                        $stmt->bindValue($identifier, $this->site_ebook_bundle, PDO::PARAM_INT);

                        break;
                    case 'site_fb_page_id':
                        $stmt->bindValue($identifier, $this->site_fb_page_id, PDO::PARAM_INT);

                        break;
                    case 'site_fb_page_token':
                        $stmt->bindValue($identifier, $this->site_fb_page_token, PDO::PARAM_STR);

                        break;
                    case 'site_analytics_id':
                        $stmt->bindValue($identifier, $this->site_analytics_id, PDO::PARAM_STR);

                        break;
                    case 'site_piwik_id':
                        $stmt->bindValue($identifier, $this->site_piwik_id, PDO::PARAM_INT);

                        break;
                    case 'site_sitemap_updated':
                        $stmt->bindValue($identifier, $this->site_sitemap_updated ? $this->site_sitemap_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'site_monitoring':
                        $stmt->bindValue($identifier, (int) $this->site_monitoring, PDO::PARAM_INT);

                        break;
                    case 'site_created':
                        $stmt->bindValue($identifier, $this->site_created ? $this->site_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'site_updated':
                        $stmt->bindValue($identifier, $this->site_updated ? $this->site_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @return int Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con): int
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName(string $name, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SiteTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos Position in XML schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition(int $pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();

            case 1:
                return $this->getName();

            case 2:
                return $this->getPass();

            case 3:
                return $this->getTitle();

            case 4:
                return $this->getDomain();

            case 5:
                return $this->getVersion();

            case 6:
                return $this->getTag();

            case 7:
                return $this->getFlag();

            case 8:
                return $this->getContact();

            case 9:
                return $this->getAddress();

            case 10:
                return $this->getTva();

            case 11:
                return $this->getHtmlRenderer();

            case 12:
                return $this->getAxys();

            case 13:
                return $this->getNoosfere();

            case 14:
                return $this->getAmazon();

            case 15:
                return $this->getEventId();

            case 16:
                return $this->getEventDate();

            case 17:
                return $this->getShop();

            case 18:
                return $this->getVpc();

            case 19:
                return $this->getShippingFee();

            case 20:
                return $this->getWishlist();

            case 21:
                return $this->getPaymentCheque();

            case 22:
                return $this->getPaymentPaypal();

            case 23:
                return $this->getPaymentPayplug();

            case 24:
                return $this->getPaymentTransfer();

            case 25:
                return $this->getBookshop();

            case 26:
                return $this->getBookshopId();

            case 27:
                return $this->getPublisher();

            case 28:
                return $this->getPublisherStock();

            case 29:
                return $this->getPublisherId();

            case 30:
                return $this->getEbookBundle();

            case 31:
                return $this->getFbPageId();

            case 32:
                return $this->getFbPageToken();

            case 33:
                return $this->getAnalyticsId();

            case 34:
                return $this->getPiwikId();

            case 35:
                return $this->getSitemapUpdated();

            case 36:
                return $this->getMonitoring();

            case 37:
                return $this->getCreatedAt();

            case 38:
                return $this->getUpdatedAt();

            default:
                return null;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param bool $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array An associative array containing the field names (as keys) and field values
     */
    public function toArray(string $keyType = TableMap::TYPE_PHPNAME, bool $includeLazyLoadColumns = true, array $alreadyDumpedObjects = [], bool $includeForeignObjects = false): array
    {
        if (isset($alreadyDumpedObjects['Site'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Site'][$this->hashCode()] = true;
        $keys = SiteTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getPass(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getDomain(),
            $keys[5] => $this->getVersion(),
            $keys[6] => $this->getTag(),
            $keys[7] => $this->getFlag(),
            $keys[8] => $this->getContact(),
            $keys[9] => $this->getAddress(),
            $keys[10] => $this->getTva(),
            $keys[11] => $this->getHtmlRenderer(),
            $keys[12] => $this->getAxys(),
            $keys[13] => $this->getNoosfere(),
            $keys[14] => $this->getAmazon(),
            $keys[15] => $this->getEventId(),
            $keys[16] => $this->getEventDate(),
            $keys[17] => $this->getShop(),
            $keys[18] => $this->getVpc(),
            $keys[19] => $this->getShippingFee(),
            $keys[20] => $this->getWishlist(),
            $keys[21] => $this->getPaymentCheque(),
            $keys[22] => $this->getPaymentPaypal(),
            $keys[23] => $this->getPaymentPayplug(),
            $keys[24] => $this->getPaymentTransfer(),
            $keys[25] => $this->getBookshop(),
            $keys[26] => $this->getBookshopId(),
            $keys[27] => $this->getPublisher(),
            $keys[28] => $this->getPublisherStock(),
            $keys[29] => $this->getPublisherId(),
            $keys[30] => $this->getEbookBundle(),
            $keys[31] => $this->getFbPageId(),
            $keys[32] => $this->getFbPageToken(),
            $keys[33] => $this->getAnalyticsId(),
            $keys[34] => $this->getPiwikId(),
            $keys[35] => $this->getSitemapUpdated(),
            $keys[36] => $this->getMonitoring(),
            $keys[37] => $this->getCreatedAt(),
            $keys[38] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[35]] instanceof \DateTimeInterface) {
            $result[$keys[35]] = $result[$keys[35]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[37]] instanceof \DateTimeInterface) {
            $result[$keys[37]] = $result[$keys[37]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[38]] instanceof \DateTimeInterface) {
            $result[$keys[38]] = $result[$keys[38]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCarts) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'carts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cartss';
                        break;
                    default:
                        $key = 'Carts';
                }

                $result[$key] = $this->collCarts->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCrowdfundingCampaigns) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'crowdfundingCampaigns';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cf_campaignss';
                        break;
                    default:
                        $key = 'CrowdfundingCampaigns';
                }

                $result[$key] = $this->collCrowdfundingCampaigns->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCrowfundingRewards) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'crowfundingRewards';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cf_rewardss';
                        break;
                    default:
                        $key = 'CrowfundingRewards';
                }

                $result[$key] = $this->collCrowfundingRewards->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInvitations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invitations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invitationss';
                        break;
                    default:
                        $key = 'Invitations';
                }

                $result[$key] = $this->collInvitations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOptions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'options';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'optionss';
                        break;
                    default:
                        $key = 'Options';
                }

                $result[$key] = $this->collOptions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrders) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orders';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orderss';
                        break;
                    default:
                        $key = 'Orders';
                }

                $result[$key] = $this->collOrders->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPages) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'pages';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'pagess';
                        break;
                    default:
                        $key = 'Pages';
                }

                $result[$key] = $this->collPages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPayments) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'payments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'paymentss';
                        break;
                    default:
                        $key = 'Payments';
                }

                $result[$key] = $this->collPayments->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collArticleCategories) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'articleCategories';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'rayonss';
                        break;
                    default:
                        $key = 'ArticleCategories';
                }

                $result[$key] = $this->collArticleCategories->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRights) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'rights';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'rightss';
                        break;
                    default:
                        $key = 'Rights';
                }

                $result[$key] = $this->collRights->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSessions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'sessions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'sessions';
                        break;
                    default:
                        $key = 'Sessions';
                }

                $result[$key] = $this->collSessions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStocks) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'stocks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'stocks';
                        break;
                    default:
                        $key = 'Stocks';
                }

                $result[$key] = $this->collStocks->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this
     */
    public function setByName(string $name, $value, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SiteTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        $this->setByPosition($pos, $value);

        return $this;
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return $this
     */
    public function setByPosition(int $pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setPass($value);
                break;
            case 3:
                $this->setTitle($value);
                break;
            case 4:
                $this->setDomain($value);
                break;
            case 5:
                $this->setVersion($value);
                break;
            case 6:
                $this->setTag($value);
                break;
            case 7:
                $this->setFlag($value);
                break;
            case 8:
                $this->setContact($value);
                break;
            case 9:
                $this->setAddress($value);
                break;
            case 10:
                $this->setTva($value);
                break;
            case 11:
                $this->setHtmlRenderer($value);
                break;
            case 12:
                $this->setAxys($value);
                break;
            case 13:
                $this->setNoosfere($value);
                break;
            case 14:
                $this->setAmazon($value);
                break;
            case 15:
                $this->setEventId($value);
                break;
            case 16:
                $this->setEventDate($value);
                break;
            case 17:
                $this->setShop($value);
                break;
            case 18:
                $this->setVpc($value);
                break;
            case 19:
                $this->setShippingFee($value);
                break;
            case 20:
                $this->setWishlist($value);
                break;
            case 21:
                $this->setPaymentCheque($value);
                break;
            case 22:
                $this->setPaymentPaypal($value);
                break;
            case 23:
                $this->setPaymentPayplug($value);
                break;
            case 24:
                $this->setPaymentTransfer($value);
                break;
            case 25:
                $this->setBookshop($value);
                break;
            case 26:
                $this->setBookshopId($value);
                break;
            case 27:
                $this->setPublisher($value);
                break;
            case 28:
                $this->setPublisherStock($value);
                break;
            case 29:
                $this->setPublisherId($value);
                break;
            case 30:
                $this->setEbookBundle($value);
                break;
            case 31:
                $this->setFbPageId($value);
                break;
            case 32:
                $this->setFbPageToken($value);
                break;
            case 33:
                $this->setAnalyticsId($value);
                break;
            case 34:
                $this->setPiwikId($value);
                break;
            case 35:
                $this->setSitemapUpdated($value);
                break;
            case 36:
                $this->setMonitoring($value);
                break;
            case 37:
                $this->setCreatedAt($value);
                break;
            case 38:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param array $arr An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return $this
     */
    public function fromArray(array $arr, string $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = SiteTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPass($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTitle($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDomain($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setVersion($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setTag($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setFlag($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setContact($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setAddress($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setTva($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setHtmlRenderer($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setAxys($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setNoosfere($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setAmazon($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setEventId($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setEventDate($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setShop($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setVpc($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setShippingFee($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setWishlist($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setPaymentCheque($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setPaymentPaypal($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setPaymentPayplug($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setPaymentTransfer($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setBookshop($arr[$keys[25]]);
        }
        if (array_key_exists($keys[26], $arr)) {
            $this->setBookshopId($arr[$keys[26]]);
        }
        if (array_key_exists($keys[27], $arr)) {
            $this->setPublisher($arr[$keys[27]]);
        }
        if (array_key_exists($keys[28], $arr)) {
            $this->setPublisherStock($arr[$keys[28]]);
        }
        if (array_key_exists($keys[29], $arr)) {
            $this->setPublisherId($arr[$keys[29]]);
        }
        if (array_key_exists($keys[30], $arr)) {
            $this->setEbookBundle($arr[$keys[30]]);
        }
        if (array_key_exists($keys[31], $arr)) {
            $this->setFbPageId($arr[$keys[31]]);
        }
        if (array_key_exists($keys[32], $arr)) {
            $this->setFbPageToken($arr[$keys[32]]);
        }
        if (array_key_exists($keys[33], $arr)) {
            $this->setAnalyticsId($arr[$keys[33]]);
        }
        if (array_key_exists($keys[34], $arr)) {
            $this->setPiwikId($arr[$keys[34]]);
        }
        if (array_key_exists($keys[35], $arr)) {
            $this->setSitemapUpdated($arr[$keys[35]]);
        }
        if (array_key_exists($keys[36], $arr)) {
            $this->setMonitoring($arr[$keys[36]]);
        }
        if (array_key_exists($keys[37], $arr)) {
            $this->setCreatedAt($arr[$keys[37]]);
        }
        if (array_key_exists($keys[38], $arr)) {
            $this->setUpdatedAt($arr[$keys[38]]);
        }

        return $this;
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this The current object, for fluid interface
     */
    public function importFrom($parser, string $data, string $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria(): Criteria
    {
        $criteria = new Criteria(SiteTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SiteTableMap::COL_SITE_ID)) {
            $criteria->add(SiteTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_NAME)) {
            $criteria->add(SiteTableMap::COL_SITE_NAME, $this->site_name);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PASS)) {
            $criteria->add(SiteTableMap::COL_SITE_PASS, $this->site_pass);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_TITLE)) {
            $criteria->add(SiteTableMap::COL_SITE_TITLE, $this->site_title);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_DOMAIN)) {
            $criteria->add(SiteTableMap::COL_SITE_DOMAIN, $this->site_domain);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_VERSION)) {
            $criteria->add(SiteTableMap::COL_SITE_VERSION, $this->site_version);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_TAG)) {
            $criteria->add(SiteTableMap::COL_SITE_TAG, $this->site_tag);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_FLAG)) {
            $criteria->add(SiteTableMap::COL_SITE_FLAG, $this->site_flag);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_CONTACT)) {
            $criteria->add(SiteTableMap::COL_SITE_CONTACT, $this->site_contact);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_ADDRESS)) {
            $criteria->add(SiteTableMap::COL_SITE_ADDRESS, $this->site_address);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_TVA)) {
            $criteria->add(SiteTableMap::COL_SITE_TVA, $this->site_tva);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_HTML_RENDERER)) {
            $criteria->add(SiteTableMap::COL_SITE_HTML_RENDERER, $this->site_html_renderer);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_AXYS)) {
            $criteria->add(SiteTableMap::COL_SITE_AXYS, $this->site_axys);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_NOOSFERE)) {
            $criteria->add(SiteTableMap::COL_SITE_NOOSFERE, $this->site_noosfere);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_AMAZON)) {
            $criteria->add(SiteTableMap::COL_SITE_AMAZON, $this->site_amazon);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_EVENT_ID)) {
            $criteria->add(SiteTableMap::COL_SITE_EVENT_ID, $this->site_event_id);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_EVENT_DATE)) {
            $criteria->add(SiteTableMap::COL_SITE_EVENT_DATE, $this->site_event_date);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_SHOP)) {
            $criteria->add(SiteTableMap::COL_SITE_SHOP, $this->site_shop);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_VPC)) {
            $criteria->add(SiteTableMap::COL_SITE_VPC, $this->site_vpc);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_SHIPPING_FEE)) {
            $criteria->add(SiteTableMap::COL_SITE_SHIPPING_FEE, $this->site_shipping_fee);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_WISHLIST)) {
            $criteria->add(SiteTableMap::COL_SITE_WISHLIST, $this->site_wishlist);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PAYMENT_CHEQUE)) {
            $criteria->add(SiteTableMap::COL_SITE_PAYMENT_CHEQUE, $this->site_payment_cheque);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PAYMENT_PAYPAL)) {
            $criteria->add(SiteTableMap::COL_SITE_PAYMENT_PAYPAL, $this->site_payment_paypal);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PAYMENT_PAYPLUG)) {
            $criteria->add(SiteTableMap::COL_SITE_PAYMENT_PAYPLUG, $this->site_payment_payplug);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PAYMENT_TRANSFER)) {
            $criteria->add(SiteTableMap::COL_SITE_PAYMENT_TRANSFER, $this->site_payment_transfer);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_BOOKSHOP)) {
            $criteria->add(SiteTableMap::COL_SITE_BOOKSHOP, $this->site_bookshop);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_BOOKSHOP_ID)) {
            $criteria->add(SiteTableMap::COL_SITE_BOOKSHOP_ID, $this->site_bookshop_id);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PUBLISHER)) {
            $criteria->add(SiteTableMap::COL_SITE_PUBLISHER, $this->site_publisher);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PUBLISHER_STOCK)) {
            $criteria->add(SiteTableMap::COL_SITE_PUBLISHER_STOCK, $this->site_publisher_stock);
        }
        if ($this->isColumnModified(SiteTableMap::COL_PUBLISHER_ID)) {
            $criteria->add(SiteTableMap::COL_PUBLISHER_ID, $this->publisher_id);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_EBOOK_BUNDLE)) {
            $criteria->add(SiteTableMap::COL_SITE_EBOOK_BUNDLE, $this->site_ebook_bundle);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_FB_PAGE_ID)) {
            $criteria->add(SiteTableMap::COL_SITE_FB_PAGE_ID, $this->site_fb_page_id);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_FB_PAGE_TOKEN)) {
            $criteria->add(SiteTableMap::COL_SITE_FB_PAGE_TOKEN, $this->site_fb_page_token);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_ANALYTICS_ID)) {
            $criteria->add(SiteTableMap::COL_SITE_ANALYTICS_ID, $this->site_analytics_id);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_PIWIK_ID)) {
            $criteria->add(SiteTableMap::COL_SITE_PIWIK_ID, $this->site_piwik_id);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_SITEMAP_UPDATED)) {
            $criteria->add(SiteTableMap::COL_SITE_SITEMAP_UPDATED, $this->site_sitemap_updated);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_MONITORING)) {
            $criteria->add(SiteTableMap::COL_SITE_MONITORING, $this->site_monitoring);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_CREATED)) {
            $criteria->add(SiteTableMap::COL_SITE_CREATED, $this->site_created);
        }
        if ($this->isColumnModified(SiteTableMap::COL_SITE_UPDATED)) {
            $criteria->add(SiteTableMap::COL_SITE_UPDATED, $this->site_updated);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria(): Criteria
    {
        $criteria = ChildSiteQuery::create();
        $criteria->add(SiteTableMap::COL_SITE_ID, $this->site_id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int|string Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (site_id column).
     *
     * @param int|null $key Primary key.
     * @return void
     */
    public function setPrimaryKey(?int $key = null): void
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return bool
     */
    public function isPrimaryKeyNull(): bool
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of \Model\Site (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setName($this->getName());
        $copyObj->setPass($this->getPass());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDomain($this->getDomain());
        $copyObj->setVersion($this->getVersion());
        $copyObj->setTag($this->getTag());
        $copyObj->setFlag($this->getFlag());
        $copyObj->setContact($this->getContact());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setTva($this->getTva());
        $copyObj->setHtmlRenderer($this->getHtmlRenderer());
        $copyObj->setAxys($this->getAxys());
        $copyObj->setNoosfere($this->getNoosfere());
        $copyObj->setAmazon($this->getAmazon());
        $copyObj->setEventId($this->getEventId());
        $copyObj->setEventDate($this->getEventDate());
        $copyObj->setShop($this->getShop());
        $copyObj->setVpc($this->getVpc());
        $copyObj->setShippingFee($this->getShippingFee());
        $copyObj->setWishlist($this->getWishlist());
        $copyObj->setPaymentCheque($this->getPaymentCheque());
        $copyObj->setPaymentPaypal($this->getPaymentPaypal());
        $copyObj->setPaymentPayplug($this->getPaymentPayplug());
        $copyObj->setPaymentTransfer($this->getPaymentTransfer());
        $copyObj->setBookshop($this->getBookshop());
        $copyObj->setBookshopId($this->getBookshopId());
        $copyObj->setPublisher($this->getPublisher());
        $copyObj->setPublisherStock($this->getPublisherStock());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setEbookBundle($this->getEbookBundle());
        $copyObj->setFbPageId($this->getFbPageId());
        $copyObj->setFbPageToken($this->getFbPageToken());
        $copyObj->setAnalyticsId($this->getAnalyticsId());
        $copyObj->setPiwikId($this->getPiwikId());
        $copyObj->setSitemapUpdated($this->getSitemapUpdated());
        $copyObj->setMonitoring($this->getMonitoring());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCarts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCart($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCrowdfundingCampaigns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCrowdfundingCampaign($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCrowfundingRewards() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCrowfundingReward($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInvitations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvitation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOptions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOption($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrders() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrder($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPayments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPayment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getArticleCategories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArticleCategory($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRights() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRight($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSessions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSession($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStocks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStock($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Model\Site Clone of current object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function copy(bool $deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName): void
    {
        if ('Cart' === $relationName) {
            $this->initCarts();
            return;
        }
        if ('CrowdfundingCampaign' === $relationName) {
            $this->initCrowdfundingCampaigns();
            return;
        }
        if ('CrowfundingReward' === $relationName) {
            $this->initCrowfundingRewards();
            return;
        }
        if ('Invitation' === $relationName) {
            $this->initInvitations();
            return;
        }
        if ('Option' === $relationName) {
            $this->initOptions();
            return;
        }
        if ('Order' === $relationName) {
            $this->initOrders();
            return;
        }
        if ('Page' === $relationName) {
            $this->initPages();
            return;
        }
        if ('Payment' === $relationName) {
            $this->initPayments();
            return;
        }
        if ('ArticleCategory' === $relationName) {
            $this->initArticleCategories();
            return;
        }
        if ('Right' === $relationName) {
            $this->initRights();
            return;
        }
        if ('Session' === $relationName) {
            $this->initSessions();
            return;
        }
        if ('Stock' === $relationName) {
            $this->initStocks();
            return;
        }
    }

    /**
     * Clears out the collCarts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addCarts()
     */
    public function clearCarts()
    {
        $this->collCarts = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collCarts collection loaded partially.
     *
     * @return void
     */
    public function resetPartialCarts($v = true): void
    {
        $this->collCartsPartial = $v;
    }

    /**
     * Initializes the collCarts collection.
     *
     * By default this just sets the collCarts collection to an empty array (like clearcollCarts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCarts(bool $overrideExisting = true): void
    {
        if (null !== $this->collCarts && !$overrideExisting) {
            return;
        }

        $collectionClassName = CartTableMap::getTableMap()->getCollectionClassName();

        $this->collCarts = new $collectionClassName;
        $this->collCarts->setModel('\Model\Cart');
    }

    /**
     * Gets an array of ChildCart objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCart[] List of ChildCart objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCart> List of ChildCart objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCarts(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collCartsPartial && !$this->isNew();
        if (null === $this->collCarts || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCarts) {
                    $this->initCarts();
                } else {
                    $collectionClassName = CartTableMap::getTableMap()->getCollectionClassName();

                    $collCarts = new $collectionClassName;
                    $collCarts->setModel('\Model\Cart');

                    return $collCarts;
                }
            } else {
                $collCarts = ChildCartQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCartsPartial && count($collCarts)) {
                        $this->initCarts(false);

                        foreach ($collCarts as $obj) {
                            if (false == $this->collCarts->contains($obj)) {
                                $this->collCarts->append($obj);
                            }
                        }

                        $this->collCartsPartial = true;
                    }

                    return $collCarts;
                }

                if ($partial && $this->collCarts) {
                    foreach ($this->collCarts as $obj) {
                        if ($obj->isNew()) {
                            $collCarts[] = $obj;
                        }
                    }
                }

                $this->collCarts = $collCarts;
                $this->collCartsPartial = false;
            }
        }

        return $this->collCarts;
    }

    /**
     * Sets a collection of ChildCart objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $carts A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setCarts(Collection $carts, ?ConnectionInterface $con = null)
    {
        /** @var ChildCart[] $cartsToDelete */
        $cartsToDelete = $this->getCarts(new Criteria(), $con)->diff($carts);


        $this->cartsScheduledForDeletion = $cartsToDelete;

        foreach ($cartsToDelete as $cartRemoved) {
            $cartRemoved->setSite(null);
        }

        $this->collCarts = null;
        foreach ($carts as $cart) {
            $this->addCart($cart);
        }

        $this->collCarts = $carts;
        $this->collCartsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Cart objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Cart objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countCarts(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collCartsPartial && !$this->isNew();
        if (null === $this->collCarts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCarts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCarts());
            }

            $query = ChildCartQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collCarts);
    }

    /**
     * Method called to associate a ChildCart object to this object
     * through the ChildCart foreign key attribute.
     *
     * @param ChildCart $l ChildCart
     * @return $this The current object (for fluent API support)
     */
    public function addCart(ChildCart $l)
    {
        if ($this->collCarts === null) {
            $this->initCarts();
            $this->collCartsPartial = true;
        }

        if (!$this->collCarts->contains($l)) {
            $this->doAddCart($l);

            if ($this->cartsScheduledForDeletion and $this->cartsScheduledForDeletion->contains($l)) {
                $this->cartsScheduledForDeletion->remove($this->cartsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCart $cart The ChildCart object to add.
     */
    protected function doAddCart(ChildCart $cart): void
    {
        $this->collCarts[]= $cart;
        $cart->setSite($this);
    }

    /**
     * @param ChildCart $cart The ChildCart object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeCart(ChildCart $cart)
    {
        if ($this->getCarts()->contains($cart)) {
            $pos = $this->collCarts->search($cart);
            $this->collCarts->remove($pos);
            if (null === $this->cartsScheduledForDeletion) {
                $this->cartsScheduledForDeletion = clone $this->collCarts;
                $this->cartsScheduledForDeletion->clear();
            }
            $this->cartsScheduledForDeletion[]= $cart;
            $cart->setSite(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Site is new, it will return
     * an empty collection; or if this Site has previously
     * been saved, it will retrieve related Carts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Site.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCart[] List of ChildCart objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCart}> List of ChildCart objects
     */
    public function getCartsJoinAxysAccount(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCartQuery::create(null, $criteria);
        $query->joinWith('AxysAccount', $joinBehavior);

        return $this->getCarts($query, $con);
    }

    /**
     * Clears out the collCrowdfundingCampaigns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addCrowdfundingCampaigns()
     */
    public function clearCrowdfundingCampaigns()
    {
        $this->collCrowdfundingCampaigns = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collCrowdfundingCampaigns collection loaded partially.
     *
     * @return void
     */
    public function resetPartialCrowdfundingCampaigns($v = true): void
    {
        $this->collCrowdfundingCampaignsPartial = $v;
    }

    /**
     * Initializes the collCrowdfundingCampaigns collection.
     *
     * By default this just sets the collCrowdfundingCampaigns collection to an empty array (like clearcollCrowdfundingCampaigns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCrowdfundingCampaigns(bool $overrideExisting = true): void
    {
        if (null !== $this->collCrowdfundingCampaigns && !$overrideExisting) {
            return;
        }

        $collectionClassName = CrowdfundingCampaignTableMap::getTableMap()->getCollectionClassName();

        $this->collCrowdfundingCampaigns = new $collectionClassName;
        $this->collCrowdfundingCampaigns->setModel('\Model\CrowdfundingCampaign');
    }

    /**
     * Gets an array of ChildCrowdfundingCampaign objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCrowdfundingCampaign[] List of ChildCrowdfundingCampaign objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCrowdfundingCampaign> List of ChildCrowdfundingCampaign objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCrowdfundingCampaigns(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collCrowdfundingCampaignsPartial && !$this->isNew();
        if (null === $this->collCrowdfundingCampaigns || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCrowdfundingCampaigns) {
                    $this->initCrowdfundingCampaigns();
                } else {
                    $collectionClassName = CrowdfundingCampaignTableMap::getTableMap()->getCollectionClassName();

                    $collCrowdfundingCampaigns = new $collectionClassName;
                    $collCrowdfundingCampaigns->setModel('\Model\CrowdfundingCampaign');

                    return $collCrowdfundingCampaigns;
                }
            } else {
                $collCrowdfundingCampaigns = ChildCrowdfundingCampaignQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCrowdfundingCampaignsPartial && count($collCrowdfundingCampaigns)) {
                        $this->initCrowdfundingCampaigns(false);

                        foreach ($collCrowdfundingCampaigns as $obj) {
                            if (false == $this->collCrowdfundingCampaigns->contains($obj)) {
                                $this->collCrowdfundingCampaigns->append($obj);
                            }
                        }

                        $this->collCrowdfundingCampaignsPartial = true;
                    }

                    return $collCrowdfundingCampaigns;
                }

                if ($partial && $this->collCrowdfundingCampaigns) {
                    foreach ($this->collCrowdfundingCampaigns as $obj) {
                        if ($obj->isNew()) {
                            $collCrowdfundingCampaigns[] = $obj;
                        }
                    }
                }

                $this->collCrowdfundingCampaigns = $collCrowdfundingCampaigns;
                $this->collCrowdfundingCampaignsPartial = false;
            }
        }

        return $this->collCrowdfundingCampaigns;
    }

    /**
     * Sets a collection of ChildCrowdfundingCampaign objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $crowdfundingCampaigns A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setCrowdfundingCampaigns(Collection $crowdfundingCampaigns, ?ConnectionInterface $con = null)
    {
        /** @var ChildCrowdfundingCampaign[] $crowdfundingCampaignsToDelete */
        $crowdfundingCampaignsToDelete = $this->getCrowdfundingCampaigns(new Criteria(), $con)->diff($crowdfundingCampaigns);


        $this->crowdfundingCampaignsScheduledForDeletion = $crowdfundingCampaignsToDelete;

        foreach ($crowdfundingCampaignsToDelete as $crowdfundingCampaignRemoved) {
            $crowdfundingCampaignRemoved->setSite(null);
        }

        $this->collCrowdfundingCampaigns = null;
        foreach ($crowdfundingCampaigns as $crowdfundingCampaign) {
            $this->addCrowdfundingCampaign($crowdfundingCampaign);
        }

        $this->collCrowdfundingCampaigns = $crowdfundingCampaigns;
        $this->collCrowdfundingCampaignsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CrowdfundingCampaign objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related CrowdfundingCampaign objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countCrowdfundingCampaigns(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collCrowdfundingCampaignsPartial && !$this->isNew();
        if (null === $this->collCrowdfundingCampaigns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCrowdfundingCampaigns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCrowdfundingCampaigns());
            }

            $query = ChildCrowdfundingCampaignQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collCrowdfundingCampaigns);
    }

    /**
     * Method called to associate a ChildCrowdfundingCampaign object to this object
     * through the ChildCrowdfundingCampaign foreign key attribute.
     *
     * @param ChildCrowdfundingCampaign $l ChildCrowdfundingCampaign
     * @return $this The current object (for fluent API support)
     */
    public function addCrowdfundingCampaign(ChildCrowdfundingCampaign $l)
    {
        if ($this->collCrowdfundingCampaigns === null) {
            $this->initCrowdfundingCampaigns();
            $this->collCrowdfundingCampaignsPartial = true;
        }

        if (!$this->collCrowdfundingCampaigns->contains($l)) {
            $this->doAddCrowdfundingCampaign($l);

            if ($this->crowdfundingCampaignsScheduledForDeletion and $this->crowdfundingCampaignsScheduledForDeletion->contains($l)) {
                $this->crowdfundingCampaignsScheduledForDeletion->remove($this->crowdfundingCampaignsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCrowdfundingCampaign $crowdfundingCampaign The ChildCrowdfundingCampaign object to add.
     */
    protected function doAddCrowdfundingCampaign(ChildCrowdfundingCampaign $crowdfundingCampaign): void
    {
        $this->collCrowdfundingCampaigns[]= $crowdfundingCampaign;
        $crowdfundingCampaign->setSite($this);
    }

    /**
     * @param ChildCrowdfundingCampaign $crowdfundingCampaign The ChildCrowdfundingCampaign object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeCrowdfundingCampaign(ChildCrowdfundingCampaign $crowdfundingCampaign)
    {
        if ($this->getCrowdfundingCampaigns()->contains($crowdfundingCampaign)) {
            $pos = $this->collCrowdfundingCampaigns->search($crowdfundingCampaign);
            $this->collCrowdfundingCampaigns->remove($pos);
            if (null === $this->crowdfundingCampaignsScheduledForDeletion) {
                $this->crowdfundingCampaignsScheduledForDeletion = clone $this->collCrowdfundingCampaigns;
                $this->crowdfundingCampaignsScheduledForDeletion->clear();
            }
            $this->crowdfundingCampaignsScheduledForDeletion[]= $crowdfundingCampaign;
            $crowdfundingCampaign->setSite(null);
        }

        return $this;
    }

    /**
     * Clears out the collCrowfundingRewards collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addCrowfundingRewards()
     */
    public function clearCrowfundingRewards()
    {
        $this->collCrowfundingRewards = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collCrowfundingRewards collection loaded partially.
     *
     * @return void
     */
    public function resetPartialCrowfundingRewards($v = true): void
    {
        $this->collCrowfundingRewardsPartial = $v;
    }

    /**
     * Initializes the collCrowfundingRewards collection.
     *
     * By default this just sets the collCrowfundingRewards collection to an empty array (like clearcollCrowfundingRewards());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCrowfundingRewards(bool $overrideExisting = true): void
    {
        if (null !== $this->collCrowfundingRewards && !$overrideExisting) {
            return;
        }

        $collectionClassName = CrowfundingRewardTableMap::getTableMap()->getCollectionClassName();

        $this->collCrowfundingRewards = new $collectionClassName;
        $this->collCrowfundingRewards->setModel('\Model\CrowfundingReward');
    }

    /**
     * Gets an array of ChildCrowfundingReward objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCrowfundingReward[] List of ChildCrowfundingReward objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCrowfundingReward> List of ChildCrowfundingReward objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCrowfundingRewards(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collCrowfundingRewardsPartial && !$this->isNew();
        if (null === $this->collCrowfundingRewards || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCrowfundingRewards) {
                    $this->initCrowfundingRewards();
                } else {
                    $collectionClassName = CrowfundingRewardTableMap::getTableMap()->getCollectionClassName();

                    $collCrowfundingRewards = new $collectionClassName;
                    $collCrowfundingRewards->setModel('\Model\CrowfundingReward');

                    return $collCrowfundingRewards;
                }
            } else {
                $collCrowfundingRewards = ChildCrowfundingRewardQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCrowfundingRewardsPartial && count($collCrowfundingRewards)) {
                        $this->initCrowfundingRewards(false);

                        foreach ($collCrowfundingRewards as $obj) {
                            if (false == $this->collCrowfundingRewards->contains($obj)) {
                                $this->collCrowfundingRewards->append($obj);
                            }
                        }

                        $this->collCrowfundingRewardsPartial = true;
                    }

                    return $collCrowfundingRewards;
                }

                if ($partial && $this->collCrowfundingRewards) {
                    foreach ($this->collCrowfundingRewards as $obj) {
                        if ($obj->isNew()) {
                            $collCrowfundingRewards[] = $obj;
                        }
                    }
                }

                $this->collCrowfundingRewards = $collCrowfundingRewards;
                $this->collCrowfundingRewardsPartial = false;
            }
        }

        return $this->collCrowfundingRewards;
    }

    /**
     * Sets a collection of ChildCrowfundingReward objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $crowfundingRewards A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setCrowfundingRewards(Collection $crowfundingRewards, ?ConnectionInterface $con = null)
    {
        /** @var ChildCrowfundingReward[] $crowfundingRewardsToDelete */
        $crowfundingRewardsToDelete = $this->getCrowfundingRewards(new Criteria(), $con)->diff($crowfundingRewards);


        $this->crowfundingRewardsScheduledForDeletion = $crowfundingRewardsToDelete;

        foreach ($crowfundingRewardsToDelete as $crowfundingRewardRemoved) {
            $crowfundingRewardRemoved->setSite(null);
        }

        $this->collCrowfundingRewards = null;
        foreach ($crowfundingRewards as $crowfundingReward) {
            $this->addCrowfundingReward($crowfundingReward);
        }

        $this->collCrowfundingRewards = $crowfundingRewards;
        $this->collCrowfundingRewardsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CrowfundingReward objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related CrowfundingReward objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countCrowfundingRewards(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collCrowfundingRewardsPartial && !$this->isNew();
        if (null === $this->collCrowfundingRewards || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCrowfundingRewards) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCrowfundingRewards());
            }

            $query = ChildCrowfundingRewardQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collCrowfundingRewards);
    }

    /**
     * Method called to associate a ChildCrowfundingReward object to this object
     * through the ChildCrowfundingReward foreign key attribute.
     *
     * @param ChildCrowfundingReward $l ChildCrowfundingReward
     * @return $this The current object (for fluent API support)
     */
    public function addCrowfundingReward(ChildCrowfundingReward $l)
    {
        if ($this->collCrowfundingRewards === null) {
            $this->initCrowfundingRewards();
            $this->collCrowfundingRewardsPartial = true;
        }

        if (!$this->collCrowfundingRewards->contains($l)) {
            $this->doAddCrowfundingReward($l);

            if ($this->crowfundingRewardsScheduledForDeletion and $this->crowfundingRewardsScheduledForDeletion->contains($l)) {
                $this->crowfundingRewardsScheduledForDeletion->remove($this->crowfundingRewardsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCrowfundingReward $crowfundingReward The ChildCrowfundingReward object to add.
     */
    protected function doAddCrowfundingReward(ChildCrowfundingReward $crowfundingReward): void
    {
        $this->collCrowfundingRewards[]= $crowfundingReward;
        $crowfundingReward->setSite($this);
    }

    /**
     * @param ChildCrowfundingReward $crowfundingReward The ChildCrowfundingReward object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeCrowfundingReward(ChildCrowfundingReward $crowfundingReward)
    {
        if ($this->getCrowfundingRewards()->contains($crowfundingReward)) {
            $pos = $this->collCrowfundingRewards->search($crowfundingReward);
            $this->collCrowfundingRewards->remove($pos);
            if (null === $this->crowfundingRewardsScheduledForDeletion) {
                $this->crowfundingRewardsScheduledForDeletion = clone $this->collCrowfundingRewards;
                $this->crowfundingRewardsScheduledForDeletion->clear();
            }
            $this->crowfundingRewardsScheduledForDeletion[]= $crowfundingReward;
            $crowfundingReward->setSite(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Site is new, it will return
     * an empty collection; or if this Site has previously
     * been saved, it will retrieve related CrowfundingRewards from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Site.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCrowfundingReward[] List of ChildCrowfundingReward objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCrowfundingReward}> List of ChildCrowfundingReward objects
     */
    public function getCrowfundingRewardsJoinCrowdfundingCampaign(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCrowfundingRewardQuery::create(null, $criteria);
        $query->joinWith('CrowdfundingCampaign', $joinBehavior);

        return $this->getCrowfundingRewards($query, $con);
    }

    /**
     * Clears out the collInvitations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addInvitations()
     */
    public function clearInvitations()
    {
        $this->collInvitations = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collInvitations collection loaded partially.
     *
     * @return void
     */
    public function resetPartialInvitations($v = true): void
    {
        $this->collInvitationsPartial = $v;
    }

    /**
     * Initializes the collInvitations collection.
     *
     * By default this just sets the collInvitations collection to an empty array (like clearcollInvitations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvitations(bool $overrideExisting = true): void
    {
        if (null !== $this->collInvitations && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvitationTableMap::getTableMap()->getCollectionClassName();

        $this->collInvitations = new $collectionClassName;
        $this->collInvitations->setModel('\Model\Invitation');
    }

    /**
     * Gets an array of ChildInvitation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildInvitation[] List of ChildInvitation objects
     * @phpstan-return ObjectCollection&\Traversable<ChildInvitation> List of ChildInvitation objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getInvitations(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collInvitationsPartial && !$this->isNew();
        if (null === $this->collInvitations || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collInvitations) {
                    $this->initInvitations();
                } else {
                    $collectionClassName = InvitationTableMap::getTableMap()->getCollectionClassName();

                    $collInvitations = new $collectionClassName;
                    $collInvitations->setModel('\Model\Invitation');

                    return $collInvitations;
                }
            } else {
                $collInvitations = ChildInvitationQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvitationsPartial && count($collInvitations)) {
                        $this->initInvitations(false);

                        foreach ($collInvitations as $obj) {
                            if (false == $this->collInvitations->contains($obj)) {
                                $this->collInvitations->append($obj);
                            }
                        }

                        $this->collInvitationsPartial = true;
                    }

                    return $collInvitations;
                }

                if ($partial && $this->collInvitations) {
                    foreach ($this->collInvitations as $obj) {
                        if ($obj->isNew()) {
                            $collInvitations[] = $obj;
                        }
                    }
                }

                $this->collInvitations = $collInvitations;
                $this->collInvitationsPartial = false;
            }
        }

        return $this->collInvitations;
    }

    /**
     * Sets a collection of ChildInvitation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $invitations A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setInvitations(Collection $invitations, ?ConnectionInterface $con = null)
    {
        /** @var ChildInvitation[] $invitationsToDelete */
        $invitationsToDelete = $this->getInvitations(new Criteria(), $con)->diff($invitations);


        $this->invitationsScheduledForDeletion = $invitationsToDelete;

        foreach ($invitationsToDelete as $invitationRemoved) {
            $invitationRemoved->setSite(null);
        }

        $this->collInvitations = null;
        foreach ($invitations as $invitation) {
            $this->addInvitation($invitation);
        }

        $this->collInvitations = $invitations;
        $this->collInvitationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Invitation objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Invitation objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countInvitations(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collInvitationsPartial && !$this->isNew();
        if (null === $this->collInvitations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvitations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvitations());
            }

            $query = ChildInvitationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collInvitations);
    }

    /**
     * Method called to associate a ChildInvitation object to this object
     * through the ChildInvitation foreign key attribute.
     *
     * @param ChildInvitation $l ChildInvitation
     * @return $this The current object (for fluent API support)
     */
    public function addInvitation(ChildInvitation $l)
    {
        if ($this->collInvitations === null) {
            $this->initInvitations();
            $this->collInvitationsPartial = true;
        }

        if (!$this->collInvitations->contains($l)) {
            $this->doAddInvitation($l);

            if ($this->invitationsScheduledForDeletion and $this->invitationsScheduledForDeletion->contains($l)) {
                $this->invitationsScheduledForDeletion->remove($this->invitationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildInvitation $invitation The ChildInvitation object to add.
     */
    protected function doAddInvitation(ChildInvitation $invitation): void
    {
        $this->collInvitations[]= $invitation;
        $invitation->setSite($this);
    }

    /**
     * @param ChildInvitation $invitation The ChildInvitation object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeInvitation(ChildInvitation $invitation)
    {
        if ($this->getInvitations()->contains($invitation)) {
            $pos = $this->collInvitations->search($invitation);
            $this->collInvitations->remove($pos);
            if (null === $this->invitationsScheduledForDeletion) {
                $this->invitationsScheduledForDeletion = clone $this->collInvitations;
                $this->invitationsScheduledForDeletion->clear();
            }
            $this->invitationsScheduledForDeletion[]= clone $invitation;
            $invitation->setSite(null);
        }

        return $this;
    }

    /**
     * Clears out the collOptions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addOptions()
     */
    public function clearOptions()
    {
        $this->collOptions = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collOptions collection loaded partially.
     *
     * @return void
     */
    public function resetPartialOptions($v = true): void
    {
        $this->collOptionsPartial = $v;
    }

    /**
     * Initializes the collOptions collection.
     *
     * By default this just sets the collOptions collection to an empty array (like clearcollOptions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOptions(bool $overrideExisting = true): void
    {
        if (null !== $this->collOptions && !$overrideExisting) {
            return;
        }

        $collectionClassName = OptionTableMap::getTableMap()->getCollectionClassName();

        $this->collOptions = new $collectionClassName;
        $this->collOptions->setModel('\Model\Option');
    }

    /**
     * Gets an array of ChildOption objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOption[] List of ChildOption objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOption> List of ChildOption objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getOptions(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collOptionsPartial && !$this->isNew();
        if (null === $this->collOptions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collOptions) {
                    $this->initOptions();
                } else {
                    $collectionClassName = OptionTableMap::getTableMap()->getCollectionClassName();

                    $collOptions = new $collectionClassName;
                    $collOptions->setModel('\Model\Option');

                    return $collOptions;
                }
            } else {
                $collOptions = ChildOptionQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOptionsPartial && count($collOptions)) {
                        $this->initOptions(false);

                        foreach ($collOptions as $obj) {
                            if (false == $this->collOptions->contains($obj)) {
                                $this->collOptions->append($obj);
                            }
                        }

                        $this->collOptionsPartial = true;
                    }

                    return $collOptions;
                }

                if ($partial && $this->collOptions) {
                    foreach ($this->collOptions as $obj) {
                        if ($obj->isNew()) {
                            $collOptions[] = $obj;
                        }
                    }
                }

                $this->collOptions = $collOptions;
                $this->collOptionsPartial = false;
            }
        }

        return $this->collOptions;
    }

    /**
     * Sets a collection of ChildOption objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $options A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setOptions(Collection $options, ?ConnectionInterface $con = null)
    {
        /** @var ChildOption[] $optionsToDelete */
        $optionsToDelete = $this->getOptions(new Criteria(), $con)->diff($options);


        $this->optionsScheduledForDeletion = $optionsToDelete;

        foreach ($optionsToDelete as $optionRemoved) {
            $optionRemoved->setSite(null);
        }

        $this->collOptions = null;
        foreach ($options as $option) {
            $this->addOption($option);
        }

        $this->collOptions = $options;
        $this->collOptionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Option objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Option objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countOptions(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collOptionsPartial && !$this->isNew();
        if (null === $this->collOptions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOptions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOptions());
            }

            $query = ChildOptionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collOptions);
    }

    /**
     * Method called to associate a ChildOption object to this object
     * through the ChildOption foreign key attribute.
     *
     * @param ChildOption $l ChildOption
     * @return $this The current object (for fluent API support)
     */
    public function addOption(ChildOption $l)
    {
        if ($this->collOptions === null) {
            $this->initOptions();
            $this->collOptionsPartial = true;
        }

        if (!$this->collOptions->contains($l)) {
            $this->doAddOption($l);

            if ($this->optionsScheduledForDeletion and $this->optionsScheduledForDeletion->contains($l)) {
                $this->optionsScheduledForDeletion->remove($this->optionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOption $option The ChildOption object to add.
     */
    protected function doAddOption(ChildOption $option): void
    {
        $this->collOptions[]= $option;
        $option->setSite($this);
    }

    /**
     * @param ChildOption $option The ChildOption object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeOption(ChildOption $option)
    {
        if ($this->getOptions()->contains($option)) {
            $pos = $this->collOptions->search($option);
            $this->collOptions->remove($pos);
            if (null === $this->optionsScheduledForDeletion) {
                $this->optionsScheduledForDeletion = clone $this->collOptions;
                $this->optionsScheduledForDeletion->clear();
            }
            $this->optionsScheduledForDeletion[]= $option;
            $option->setSite(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Site is new, it will return
     * an empty collection; or if this Site has previously
     * been saved, it will retrieve related Options from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Site.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOption[] List of ChildOption objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOption}> List of ChildOption objects
     */
    public function getOptionsJoinAxysAccount(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOptionQuery::create(null, $criteria);
        $query->joinWith('AxysAccount', $joinBehavior);

        return $this->getOptions($query, $con);
    }

    /**
     * Clears out the collOrders collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addOrders()
     */
    public function clearOrders()
    {
        $this->collOrders = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collOrders collection loaded partially.
     *
     * @return void
     */
    public function resetPartialOrders($v = true): void
    {
        $this->collOrdersPartial = $v;
    }

    /**
     * Initializes the collOrders collection.
     *
     * By default this just sets the collOrders collection to an empty array (like clearcollOrders());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrders(bool $overrideExisting = true): void
    {
        if (null !== $this->collOrders && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrderTableMap::getTableMap()->getCollectionClassName();

        $this->collOrders = new $collectionClassName;
        $this->collOrders->setModel('\Model\Order');
    }

    /**
     * Gets an array of ChildOrder objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder> List of ChildOrder objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getOrders(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersPartial && !$this->isNew();
        if (null === $this->collOrders || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collOrders) {
                    $this->initOrders();
                } else {
                    $collectionClassName = OrderTableMap::getTableMap()->getCollectionClassName();

                    $collOrders = new $collectionClassName;
                    $collOrders->setModel('\Model\Order');

                    return $collOrders;
                }
            } else {
                $collOrders = ChildOrderQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersPartial && count($collOrders)) {
                        $this->initOrders(false);

                        foreach ($collOrders as $obj) {
                            if (false == $this->collOrders->contains($obj)) {
                                $this->collOrders->append($obj);
                            }
                        }

                        $this->collOrdersPartial = true;
                    }

                    return $collOrders;
                }

                if ($partial && $this->collOrders) {
                    foreach ($this->collOrders as $obj) {
                        if ($obj->isNew()) {
                            $collOrders[] = $obj;
                        }
                    }
                }

                $this->collOrders = $collOrders;
                $this->collOrdersPartial = false;
            }
        }

        return $this->collOrders;
    }

    /**
     * Sets a collection of ChildOrder objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $orders A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setOrders(Collection $orders, ?ConnectionInterface $con = null)
    {
        /** @var ChildOrder[] $ordersToDelete */
        $ordersToDelete = $this->getOrders(new Criteria(), $con)->diff($orders);


        $this->ordersScheduledForDeletion = $ordersToDelete;

        foreach ($ordersToDelete as $orderRemoved) {
            $orderRemoved->setSite(null);
        }

        $this->collOrders = null;
        foreach ($orders as $order) {
            $this->addOrder($order);
        }

        $this->collOrders = $orders;
        $this->collOrdersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Order objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Order objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countOrders(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collOrdersPartial && !$this->isNew();
        if (null === $this->collOrders || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrders) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrders());
            }

            $query = ChildOrderQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collOrders);
    }

    /**
     * Method called to associate a ChildOrder object to this object
     * through the ChildOrder foreign key attribute.
     *
     * @param ChildOrder $l ChildOrder
     * @return $this The current object (for fluent API support)
     */
    public function addOrder(ChildOrder $l)
    {
        if ($this->collOrders === null) {
            $this->initOrders();
            $this->collOrdersPartial = true;
        }

        if (!$this->collOrders->contains($l)) {
            $this->doAddOrder($l);

            if ($this->ordersScheduledForDeletion and $this->ordersScheduledForDeletion->contains($l)) {
                $this->ordersScheduledForDeletion->remove($this->ordersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOrder $order The ChildOrder object to add.
     */
    protected function doAddOrder(ChildOrder $order): void
    {
        $this->collOrders[]= $order;
        $order->setSite($this);
    }

    /**
     * @param ChildOrder $order The ChildOrder object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeOrder(ChildOrder $order)
    {
        if ($this->getOrders()->contains($order)) {
            $pos = $this->collOrders->search($order);
            $this->collOrders->remove($pos);
            if (null === $this->ordersScheduledForDeletion) {
                $this->ordersScheduledForDeletion = clone $this->collOrders;
                $this->ordersScheduledForDeletion->clear();
            }
            $this->ordersScheduledForDeletion[]= $order;
            $order->setSite(null);
        }

        return $this;
    }

    /**
     * Clears out the collPages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addPages()
     */
    public function clearPages()
    {
        $this->collPages = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collPages collection loaded partially.
     *
     * @return void
     */
    public function resetPartialPages($v = true): void
    {
        $this->collPagesPartial = $v;
    }

    /**
     * Initializes the collPages collection.
     *
     * By default this just sets the collPages collection to an empty array (like clearcollPages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPages(bool $overrideExisting = true): void
    {
        if (null !== $this->collPages && !$overrideExisting) {
            return;
        }

        $collectionClassName = PageTableMap::getTableMap()->getCollectionClassName();

        $this->collPages = new $collectionClassName;
        $this->collPages->setModel('\Model\Page');
    }

    /**
     * Gets an array of ChildPage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPage[] List of ChildPage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPage> List of ChildPage objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getPages(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collPagesPartial && !$this->isNew();
        if (null === $this->collPages || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPages) {
                    $this->initPages();
                } else {
                    $collectionClassName = PageTableMap::getTableMap()->getCollectionClassName();

                    $collPages = new $collectionClassName;
                    $collPages->setModel('\Model\Page');

                    return $collPages;
                }
            } else {
                $collPages = ChildPageQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPagesPartial && count($collPages)) {
                        $this->initPages(false);

                        foreach ($collPages as $obj) {
                            if (false == $this->collPages->contains($obj)) {
                                $this->collPages->append($obj);
                            }
                        }

                        $this->collPagesPartial = true;
                    }

                    return $collPages;
                }

                if ($partial && $this->collPages) {
                    foreach ($this->collPages as $obj) {
                        if ($obj->isNew()) {
                            $collPages[] = $obj;
                        }
                    }
                }

                $this->collPages = $collPages;
                $this->collPagesPartial = false;
            }
        }

        return $this->collPages;
    }

    /**
     * Sets a collection of ChildPage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $pages A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setPages(Collection $pages, ?ConnectionInterface $con = null)
    {
        /** @var ChildPage[] $pagesToDelete */
        $pagesToDelete = $this->getPages(new Criteria(), $con)->diff($pages);


        $this->pagesScheduledForDeletion = $pagesToDelete;

        foreach ($pagesToDelete as $pageRemoved) {
            $pageRemoved->setSite(null);
        }

        $this->collPages = null;
        foreach ($pages as $page) {
            $this->addPage($page);
        }

        $this->collPages = $pages;
        $this->collPagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Page objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Page objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countPages(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collPagesPartial && !$this->isNew();
        if (null === $this->collPages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPages());
            }

            $query = ChildPageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collPages);
    }

    /**
     * Method called to associate a ChildPage object to this object
     * through the ChildPage foreign key attribute.
     *
     * @param ChildPage $l ChildPage
     * @return $this The current object (for fluent API support)
     */
    public function addPage(ChildPage $l)
    {
        if ($this->collPages === null) {
            $this->initPages();
            $this->collPagesPartial = true;
        }

        if (!$this->collPages->contains($l)) {
            $this->doAddPage($l);

            if ($this->pagesScheduledForDeletion and $this->pagesScheduledForDeletion->contains($l)) {
                $this->pagesScheduledForDeletion->remove($this->pagesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPage $page The ChildPage object to add.
     */
    protected function doAddPage(ChildPage $page): void
    {
        $this->collPages[]= $page;
        $page->setSite($this);
    }

    /**
     * @param ChildPage $page The ChildPage object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removePage(ChildPage $page)
    {
        if ($this->getPages()->contains($page)) {
            $pos = $this->collPages->search($page);
            $this->collPages->remove($pos);
            if (null === $this->pagesScheduledForDeletion) {
                $this->pagesScheduledForDeletion = clone $this->collPages;
                $this->pagesScheduledForDeletion->clear();
            }
            $this->pagesScheduledForDeletion[]= $page;
            $page->setSite(null);
        }

        return $this;
    }

    /**
     * Clears out the collPayments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addPayments()
     */
    public function clearPayments()
    {
        $this->collPayments = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collPayments collection loaded partially.
     *
     * @return void
     */
    public function resetPartialPayments($v = true): void
    {
        $this->collPaymentsPartial = $v;
    }

    /**
     * Initializes the collPayments collection.
     *
     * By default this just sets the collPayments collection to an empty array (like clearcollPayments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPayments(bool $overrideExisting = true): void
    {
        if (null !== $this->collPayments && !$overrideExisting) {
            return;
        }

        $collectionClassName = PaymentTableMap::getTableMap()->getCollectionClassName();

        $this->collPayments = new $collectionClassName;
        $this->collPayments->setModel('\Model\Payment');
    }

    /**
     * Gets an array of ChildPayment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPayment[] List of ChildPayment objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPayment> List of ChildPayment objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getPayments(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collPaymentsPartial && !$this->isNew();
        if (null === $this->collPayments || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPayments) {
                    $this->initPayments();
                } else {
                    $collectionClassName = PaymentTableMap::getTableMap()->getCollectionClassName();

                    $collPayments = new $collectionClassName;
                    $collPayments->setModel('\Model\Payment');

                    return $collPayments;
                }
            } else {
                $collPayments = ChildPaymentQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPaymentsPartial && count($collPayments)) {
                        $this->initPayments(false);

                        foreach ($collPayments as $obj) {
                            if (false == $this->collPayments->contains($obj)) {
                                $this->collPayments->append($obj);
                            }
                        }

                        $this->collPaymentsPartial = true;
                    }

                    return $collPayments;
                }

                if ($partial && $this->collPayments) {
                    foreach ($this->collPayments as $obj) {
                        if ($obj->isNew()) {
                            $collPayments[] = $obj;
                        }
                    }
                }

                $this->collPayments = $collPayments;
                $this->collPaymentsPartial = false;
            }
        }

        return $this->collPayments;
    }

    /**
     * Sets a collection of ChildPayment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $payments A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setPayments(Collection $payments, ?ConnectionInterface $con = null)
    {
        /** @var ChildPayment[] $paymentsToDelete */
        $paymentsToDelete = $this->getPayments(new Criteria(), $con)->diff($payments);


        $this->paymentsScheduledForDeletion = $paymentsToDelete;

        foreach ($paymentsToDelete as $paymentRemoved) {
            $paymentRemoved->setSite(null);
        }

        $this->collPayments = null;
        foreach ($payments as $payment) {
            $this->addPayment($payment);
        }

        $this->collPayments = $payments;
        $this->collPaymentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Payment objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Payment objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countPayments(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collPaymentsPartial && !$this->isNew();
        if (null === $this->collPayments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPayments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPayments());
            }

            $query = ChildPaymentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collPayments);
    }

    /**
     * Method called to associate a ChildPayment object to this object
     * through the ChildPayment foreign key attribute.
     *
     * @param ChildPayment $l ChildPayment
     * @return $this The current object (for fluent API support)
     */
    public function addPayment(ChildPayment $l)
    {
        if ($this->collPayments === null) {
            $this->initPayments();
            $this->collPaymentsPartial = true;
        }

        if (!$this->collPayments->contains($l)) {
            $this->doAddPayment($l);

            if ($this->paymentsScheduledForDeletion and $this->paymentsScheduledForDeletion->contains($l)) {
                $this->paymentsScheduledForDeletion->remove($this->paymentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPayment $payment The ChildPayment object to add.
     */
    protected function doAddPayment(ChildPayment $payment): void
    {
        $this->collPayments[]= $payment;
        $payment->setSite($this);
    }

    /**
     * @param ChildPayment $payment The ChildPayment object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removePayment(ChildPayment $payment)
    {
        if ($this->getPayments()->contains($payment)) {
            $pos = $this->collPayments->search($payment);
            $this->collPayments->remove($pos);
            if (null === $this->paymentsScheduledForDeletion) {
                $this->paymentsScheduledForDeletion = clone $this->collPayments;
                $this->paymentsScheduledForDeletion->clear();
            }
            $this->paymentsScheduledForDeletion[]= $payment;
            $payment->setSite(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Site is new, it will return
     * an empty collection; or if this Site has previously
     * been saved, it will retrieve related Payments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Site.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPayment[] List of ChildPayment objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPayment}> List of ChildPayment objects
     */
    public function getPaymentsJoinOrder(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPaymentQuery::create(null, $criteria);
        $query->joinWith('Order', $joinBehavior);

        return $this->getPayments($query, $con);
    }

    /**
     * Clears out the collArticleCategories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addArticleCategories()
     */
    public function clearArticleCategories()
    {
        $this->collArticleCategories = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collArticleCategories collection loaded partially.
     *
     * @return void
     */
    public function resetPartialArticleCategories($v = true): void
    {
        $this->collArticleCategoriesPartial = $v;
    }

    /**
     * Initializes the collArticleCategories collection.
     *
     * By default this just sets the collArticleCategories collection to an empty array (like clearcollArticleCategories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initArticleCategories(bool $overrideExisting = true): void
    {
        if (null !== $this->collArticleCategories && !$overrideExisting) {
            return;
        }

        $collectionClassName = ArticleCategoryTableMap::getTableMap()->getCollectionClassName();

        $this->collArticleCategories = new $collectionClassName;
        $this->collArticleCategories->setModel('\Model\ArticleCategory');
    }

    /**
     * Gets an array of ChildArticleCategory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildArticleCategory[] List of ChildArticleCategory objects
     * @phpstan-return ObjectCollection&\Traversable<ChildArticleCategory> List of ChildArticleCategory objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getArticleCategories(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collArticleCategoriesPartial && !$this->isNew();
        if (null === $this->collArticleCategories || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collArticleCategories) {
                    $this->initArticleCategories();
                } else {
                    $collectionClassName = ArticleCategoryTableMap::getTableMap()->getCollectionClassName();

                    $collArticleCategories = new $collectionClassName;
                    $collArticleCategories->setModel('\Model\ArticleCategory');

                    return $collArticleCategories;
                }
            } else {
                $collArticleCategories = ChildArticleCategoryQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collArticleCategoriesPartial && count($collArticleCategories)) {
                        $this->initArticleCategories(false);

                        foreach ($collArticleCategories as $obj) {
                            if (false == $this->collArticleCategories->contains($obj)) {
                                $this->collArticleCategories->append($obj);
                            }
                        }

                        $this->collArticleCategoriesPartial = true;
                    }

                    return $collArticleCategories;
                }

                if ($partial && $this->collArticleCategories) {
                    foreach ($this->collArticleCategories as $obj) {
                        if ($obj->isNew()) {
                            $collArticleCategories[] = $obj;
                        }
                    }
                }

                $this->collArticleCategories = $collArticleCategories;
                $this->collArticleCategoriesPartial = false;
            }
        }

        return $this->collArticleCategories;
    }

    /**
     * Sets a collection of ChildArticleCategory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $articleCategories A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setArticleCategories(Collection $articleCategories, ?ConnectionInterface $con = null)
    {
        /** @var ChildArticleCategory[] $articleCategoriesToDelete */
        $articleCategoriesToDelete = $this->getArticleCategories(new Criteria(), $con)->diff($articleCategories);


        $this->articleCategoriesScheduledForDeletion = $articleCategoriesToDelete;

        foreach ($articleCategoriesToDelete as $articleCategoryRemoved) {
            $articleCategoryRemoved->setSite(null);
        }

        $this->collArticleCategories = null;
        foreach ($articleCategories as $articleCategory) {
            $this->addArticleCategory($articleCategory);
        }

        $this->collArticleCategories = $articleCategories;
        $this->collArticleCategoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ArticleCategory objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related ArticleCategory objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countArticleCategories(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collArticleCategoriesPartial && !$this->isNew();
        if (null === $this->collArticleCategories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArticleCategories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getArticleCategories());
            }

            $query = ChildArticleCategoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collArticleCategories);
    }

    /**
     * Method called to associate a ChildArticleCategory object to this object
     * through the ChildArticleCategory foreign key attribute.
     *
     * @param ChildArticleCategory $l ChildArticleCategory
     * @return $this The current object (for fluent API support)
     */
    public function addArticleCategory(ChildArticleCategory $l)
    {
        if ($this->collArticleCategories === null) {
            $this->initArticleCategories();
            $this->collArticleCategoriesPartial = true;
        }

        if (!$this->collArticleCategories->contains($l)) {
            $this->doAddArticleCategory($l);

            if ($this->articleCategoriesScheduledForDeletion and $this->articleCategoriesScheduledForDeletion->contains($l)) {
                $this->articleCategoriesScheduledForDeletion->remove($this->articleCategoriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildArticleCategory $articleCategory The ChildArticleCategory object to add.
     */
    protected function doAddArticleCategory(ChildArticleCategory $articleCategory): void
    {
        $this->collArticleCategories[]= $articleCategory;
        $articleCategory->setSite($this);
    }

    /**
     * @param ChildArticleCategory $articleCategory The ChildArticleCategory object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeArticleCategory(ChildArticleCategory $articleCategory)
    {
        if ($this->getArticleCategories()->contains($articleCategory)) {
            $pos = $this->collArticleCategories->search($articleCategory);
            $this->collArticleCategories->remove($pos);
            if (null === $this->articleCategoriesScheduledForDeletion) {
                $this->articleCategoriesScheduledForDeletion = clone $this->collArticleCategories;
                $this->articleCategoriesScheduledForDeletion->clear();
            }
            $this->articleCategoriesScheduledForDeletion[]= $articleCategory;
            $articleCategory->setSite(null);
        }

        return $this;
    }

    /**
     * Clears out the collRights collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addRights()
     */
    public function clearRights()
    {
        $this->collRights = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collRights collection loaded partially.
     *
     * @return void
     */
    public function resetPartialRights($v = true): void
    {
        $this->collRightsPartial = $v;
    }

    /**
     * Initializes the collRights collection.
     *
     * By default this just sets the collRights collection to an empty array (like clearcollRights());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRights(bool $overrideExisting = true): void
    {
        if (null !== $this->collRights && !$overrideExisting) {
            return;
        }

        $collectionClassName = RightTableMap::getTableMap()->getCollectionClassName();

        $this->collRights = new $collectionClassName;
        $this->collRights->setModel('\Model\Right');
    }

    /**
     * Gets an array of ChildRight objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight> List of ChildRight objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getRights(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collRightsPartial && !$this->isNew();
        if (null === $this->collRights || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collRights) {
                    $this->initRights();
                } else {
                    $collectionClassName = RightTableMap::getTableMap()->getCollectionClassName();

                    $collRights = new $collectionClassName;
                    $collRights->setModel('\Model\Right');

                    return $collRights;
                }
            } else {
                $collRights = ChildRightQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collRightsPartial && count($collRights)) {
                        $this->initRights(false);

                        foreach ($collRights as $obj) {
                            if (false == $this->collRights->contains($obj)) {
                                $this->collRights->append($obj);
                            }
                        }

                        $this->collRightsPartial = true;
                    }

                    return $collRights;
                }

                if ($partial && $this->collRights) {
                    foreach ($this->collRights as $obj) {
                        if ($obj->isNew()) {
                            $collRights[] = $obj;
                        }
                    }
                }

                $this->collRights = $collRights;
                $this->collRightsPartial = false;
            }
        }

        return $this->collRights;
    }

    /**
     * Sets a collection of ChildRight objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $rights A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setRights(Collection $rights, ?ConnectionInterface $con = null)
    {
        /** @var ChildRight[] $rightsToDelete */
        $rightsToDelete = $this->getRights(new Criteria(), $con)->diff($rights);


        $this->rightsScheduledForDeletion = $rightsToDelete;

        foreach ($rightsToDelete as $rightRemoved) {
            $rightRemoved->setSite(null);
        }

        $this->collRights = null;
        foreach ($rights as $right) {
            $this->addRight($right);
        }

        $this->collRights = $rights;
        $this->collRightsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Right objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Right objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countRights(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collRightsPartial && !$this->isNew();
        if (null === $this->collRights || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRights) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRights());
            }

            $query = ChildRightQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collRights);
    }

    /**
     * Method called to associate a ChildRight object to this object
     * through the ChildRight foreign key attribute.
     *
     * @param ChildRight $l ChildRight
     * @return $this The current object (for fluent API support)
     */
    public function addRight(ChildRight $l)
    {
        if ($this->collRights === null) {
            $this->initRights();
            $this->collRightsPartial = true;
        }

        if (!$this->collRights->contains($l)) {
            $this->doAddRight($l);

            if ($this->rightsScheduledForDeletion and $this->rightsScheduledForDeletion->contains($l)) {
                $this->rightsScheduledForDeletion->remove($this->rightsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildRight $right The ChildRight object to add.
     */
    protected function doAddRight(ChildRight $right): void
    {
        $this->collRights[]= $right;
        $right->setSite($this);
    }

    /**
     * @param ChildRight $right The ChildRight object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeRight(ChildRight $right)
    {
        if ($this->getRights()->contains($right)) {
            $pos = $this->collRights->search($right);
            $this->collRights->remove($pos);
            if (null === $this->rightsScheduledForDeletion) {
                $this->rightsScheduledForDeletion = clone $this->collRights;
                $this->rightsScheduledForDeletion->clear();
            }
            $this->rightsScheduledForDeletion[]= $right;
            $right->setSite(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Site is new, it will return
     * an empty collection; or if this Site has previously
     * been saved, it will retrieve related Rights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Site.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight}> List of ChildRight objects
     */
    public function getRightsJoinAxysAccount(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRightQuery::create(null, $criteria);
        $query->joinWith('AxysAccount', $joinBehavior);

        return $this->getRights($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Site is new, it will return
     * an empty collection; or if this Site has previously
     * been saved, it will retrieve related Rights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Site.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight}> List of ChildRight objects
     */
    public function getRightsJoinPublisher(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRightQuery::create(null, $criteria);
        $query->joinWith('Publisher', $joinBehavior);

        return $this->getRights($query, $con);
    }

    /**
     * Clears out the collSessions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addSessions()
     */
    public function clearSessions()
    {
        $this->collSessions = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collSessions collection loaded partially.
     *
     * @return void
     */
    public function resetPartialSessions($v = true): void
    {
        $this->collSessionsPartial = $v;
    }

    /**
     * Initializes the collSessions collection.
     *
     * By default this just sets the collSessions collection to an empty array (like clearcollSessions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSessions(bool $overrideExisting = true): void
    {
        if (null !== $this->collSessions && !$overrideExisting) {
            return;
        }

        $collectionClassName = SessionTableMap::getTableMap()->getCollectionClassName();

        $this->collSessions = new $collectionClassName;
        $this->collSessions->setModel('\Model\Session');
    }

    /**
     * Gets an array of ChildSession objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSession[] List of ChildSession objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSession> List of ChildSession objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSessions(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collSessionsPartial && !$this->isNew();
        if (null === $this->collSessions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSessions) {
                    $this->initSessions();
                } else {
                    $collectionClassName = SessionTableMap::getTableMap()->getCollectionClassName();

                    $collSessions = new $collectionClassName;
                    $collSessions->setModel('\Model\Session');

                    return $collSessions;
                }
            } else {
                $collSessions = ChildSessionQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSessionsPartial && count($collSessions)) {
                        $this->initSessions(false);

                        foreach ($collSessions as $obj) {
                            if (false == $this->collSessions->contains($obj)) {
                                $this->collSessions->append($obj);
                            }
                        }

                        $this->collSessionsPartial = true;
                    }

                    return $collSessions;
                }

                if ($partial && $this->collSessions) {
                    foreach ($this->collSessions as $obj) {
                        if ($obj->isNew()) {
                            $collSessions[] = $obj;
                        }
                    }
                }

                $this->collSessions = $collSessions;
                $this->collSessionsPartial = false;
            }
        }

        return $this->collSessions;
    }

    /**
     * Sets a collection of ChildSession objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $sessions A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setSessions(Collection $sessions, ?ConnectionInterface $con = null)
    {
        /** @var ChildSession[] $sessionsToDelete */
        $sessionsToDelete = $this->getSessions(new Criteria(), $con)->diff($sessions);


        $this->sessionsScheduledForDeletion = $sessionsToDelete;

        foreach ($sessionsToDelete as $sessionRemoved) {
            $sessionRemoved->setSite(null);
        }

        $this->collSessions = null;
        foreach ($sessions as $session) {
            $this->addSession($session);
        }

        $this->collSessions = $sessions;
        $this->collSessionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Session objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Session objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countSessions(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collSessionsPartial && !$this->isNew();
        if (null === $this->collSessions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSessions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSessions());
            }

            $query = ChildSessionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collSessions);
    }

    /**
     * Method called to associate a ChildSession object to this object
     * through the ChildSession foreign key attribute.
     *
     * @param ChildSession $l ChildSession
     * @return $this The current object (for fluent API support)
     */
    public function addSession(ChildSession $l)
    {
        if ($this->collSessions === null) {
            $this->initSessions();
            $this->collSessionsPartial = true;
        }

        if (!$this->collSessions->contains($l)) {
            $this->doAddSession($l);

            if ($this->sessionsScheduledForDeletion and $this->sessionsScheduledForDeletion->contains($l)) {
                $this->sessionsScheduledForDeletion->remove($this->sessionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSession $session The ChildSession object to add.
     */
    protected function doAddSession(ChildSession $session): void
    {
        $this->collSessions[]= $session;
        $session->setSite($this);
    }

    /**
     * @param ChildSession $session The ChildSession object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeSession(ChildSession $session)
    {
        if ($this->getSessions()->contains($session)) {
            $pos = $this->collSessions->search($session);
            $this->collSessions->remove($pos);
            if (null === $this->sessionsScheduledForDeletion) {
                $this->sessionsScheduledForDeletion = clone $this->collSessions;
                $this->sessionsScheduledForDeletion->clear();
            }
            $this->sessionsScheduledForDeletion[]= $session;
            $session->setSite(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Site is new, it will return
     * an empty collection; or if this Site has previously
     * been saved, it will retrieve related Sessions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Site.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSession[] List of ChildSession objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSession}> List of ChildSession objects
     */
    public function getSessionsJoinAxysAccount(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSessionQuery::create(null, $criteria);
        $query->joinWith('AxysAccount', $joinBehavior);

        return $this->getSessions($query, $con);
    }

    /**
     * Clears out the collStocks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addStocks()
     */
    public function clearStocks()
    {
        $this->collStocks = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collStocks collection loaded partially.
     *
     * @return void
     */
    public function resetPartialStocks($v = true): void
    {
        $this->collStocksPartial = $v;
    }

    /**
     * Initializes the collStocks collection.
     *
     * By default this just sets the collStocks collection to an empty array (like clearcollStocks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStocks(bool $overrideExisting = true): void
    {
        if (null !== $this->collStocks && !$overrideExisting) {
            return;
        }

        $collectionClassName = StockTableMap::getTableMap()->getCollectionClassName();

        $this->collStocks = new $collectionClassName;
        $this->collStocks->setModel('\Model\Stock');
    }

    /**
     * Gets an array of ChildStock objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock> List of ChildStock objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getStocks(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collStocksPartial && !$this->isNew();
        if (null === $this->collStocks || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collStocks) {
                    $this->initStocks();
                } else {
                    $collectionClassName = StockTableMap::getTableMap()->getCollectionClassName();

                    $collStocks = new $collectionClassName;
                    $collStocks->setModel('\Model\Stock');

                    return $collStocks;
                }
            } else {
                $collStocks = ChildStockQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collStocksPartial && count($collStocks)) {
                        $this->initStocks(false);

                        foreach ($collStocks as $obj) {
                            if (false == $this->collStocks->contains($obj)) {
                                $this->collStocks->append($obj);
                            }
                        }

                        $this->collStocksPartial = true;
                    }

                    return $collStocks;
                }

                if ($partial && $this->collStocks) {
                    foreach ($this->collStocks as $obj) {
                        if ($obj->isNew()) {
                            $collStocks[] = $obj;
                        }
                    }
                }

                $this->collStocks = $collStocks;
                $this->collStocksPartial = false;
            }
        }

        return $this->collStocks;
    }

    /**
     * Sets a collection of ChildStock objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $stocks A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setStocks(Collection $stocks, ?ConnectionInterface $con = null)
    {
        /** @var ChildStock[] $stocksToDelete */
        $stocksToDelete = $this->getStocks(new Criteria(), $con)->diff($stocks);


        $this->stocksScheduledForDeletion = $stocksToDelete;

        foreach ($stocksToDelete as $stockRemoved) {
            $stockRemoved->setSite(null);
        }

        $this->collStocks = null;
        foreach ($stocks as $stock) {
            $this->addStock($stock);
        }

        $this->collStocks = $stocks;
        $this->collStocksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Stock objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Stock objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countStocks(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collStocksPartial && !$this->isNew();
        if (null === $this->collStocks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStocks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStocks());
            }

            $query = ChildStockQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collStocks);
    }

    /**
     * Method called to associate a ChildStock object to this object
     * through the ChildStock foreign key attribute.
     *
     * @param ChildStock $l ChildStock
     * @return $this The current object (for fluent API support)
     */
    public function addStock(ChildStock $l)
    {
        if ($this->collStocks === null) {
            $this->initStocks();
            $this->collStocksPartial = true;
        }

        if (!$this->collStocks->contains($l)) {
            $this->doAddStock($l);

            if ($this->stocksScheduledForDeletion and $this->stocksScheduledForDeletion->contains($l)) {
                $this->stocksScheduledForDeletion->remove($this->stocksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildStock $stock The ChildStock object to add.
     */
    protected function doAddStock(ChildStock $stock): void
    {
        $this->collStocks[]= $stock;
        $stock->setSite($this);
    }

    /**
     * @param ChildStock $stock The ChildStock object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeStock(ChildStock $stock)
    {
        if ($this->getStocks()->contains($stock)) {
            $pos = $this->collStocks->search($stock);
            $this->collStocks->remove($pos);
            if (null === $this->stocksScheduledForDeletion) {
                $this->stocksScheduledForDeletion = clone $this->collStocks;
                $this->stocksScheduledForDeletion->clear();
            }
            $this->stocksScheduledForDeletion[]= $stock;
            $stock->setSite(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Site is new, it will return
     * an empty collection; or if this Site has previously
     * been saved, it will retrieve related Stocks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Site.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStocksJoinCart(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('Cart', $joinBehavior);

        return $this->getStocks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Site is new, it will return
     * an empty collection; or if this Site has previously
     * been saved, it will retrieve related Stocks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Site.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStocksJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getStocks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Site is new, it will return
     * an empty collection; or if this Site has previously
     * been saved, it will retrieve related Stocks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Site.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStocksJoinAxysAccount(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('AxysAccount', $joinBehavior);

        return $this->getStocks($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     *
     * @return $this
     */
    public function clear()
    {
        $this->site_id = null;
        $this->site_name = null;
        $this->site_pass = null;
        $this->site_title = null;
        $this->site_domain = null;
        $this->site_version = null;
        $this->site_tag = null;
        $this->site_flag = null;
        $this->site_contact = null;
        $this->site_address = null;
        $this->site_tva = null;
        $this->site_html_renderer = null;
        $this->site_axys = null;
        $this->site_noosfere = null;
        $this->site_amazon = null;
        $this->site_event_id = null;
        $this->site_event_date = null;
        $this->site_shop = null;
        $this->site_vpc = null;
        $this->site_shipping_fee = null;
        $this->site_wishlist = null;
        $this->site_payment_cheque = null;
        $this->site_payment_paypal = null;
        $this->site_payment_payplug = null;
        $this->site_payment_transfer = null;
        $this->site_bookshop = null;
        $this->site_bookshop_id = null;
        $this->site_publisher = null;
        $this->site_publisher_stock = null;
        $this->publisher_id = null;
        $this->site_ebook_bundle = null;
        $this->site_fb_page_id = null;
        $this->site_fb_page_token = null;
        $this->site_analytics_id = null;
        $this->site_piwik_id = null;
        $this->site_sitemap_updated = null;
        $this->site_monitoring = null;
        $this->site_created = null;
        $this->site_updated = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);

        return $this;
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param bool $deep Whether to also clear the references on all referrer objects.
     * @return $this
     */
    public function clearAllReferences(bool $deep = false)
    {
        if ($deep) {
            if ($this->collCarts) {
                foreach ($this->collCarts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCrowdfundingCampaigns) {
                foreach ($this->collCrowdfundingCampaigns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCrowfundingRewards) {
                foreach ($this->collCrowfundingRewards as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInvitations) {
                foreach ($this->collInvitations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOptions) {
                foreach ($this->collOptions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrders) {
                foreach ($this->collOrders as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPages) {
                foreach ($this->collPages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPayments) {
                foreach ($this->collPayments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collArticleCategories) {
                foreach ($this->collArticleCategories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRights) {
                foreach ($this->collRights as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSessions) {
                foreach ($this->collSessions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStocks) {
                foreach ($this->collStocks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCarts = null;
        $this->collCrowdfundingCampaigns = null;
        $this->collCrowfundingRewards = null;
        $this->collInvitations = null;
        $this->collOptions = null;
        $this->collOrders = null;
        $this->collPages = null;
        $this->collPayments = null;
        $this->collArticleCategories = null;
        $this->collRights = null;
        $this->collSessions = null;
        $this->collStocks = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SiteTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[SiteTableMap::COL_SITE_UPDATED] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postSave(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before inserting to database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preInsert(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postInsert(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preUpdate(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postUpdate(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preDelete(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postDelete(?ConnectionInterface $con = null): void
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);
            $inputData = $params[0];
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->importFrom($format, $inputData, $keyType);
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = $params[0] ?? true;
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->exportTo($format, $includeLazyLoadColumns, $keyType);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
