<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Option as ChildOption;
use Model\OptionQuery as ChildOptionQuery;
use Model\Payment as ChildPayment;
use Model\PaymentQuery as ChildPaymentQuery;
use Model\Right as ChildRight;
use Model\RightQuery as ChildRightQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\OptionTableMap;
use Model\Map\PaymentTableMap;
use Model\Map\RightTableMap;
use Model\Map\SiteTableMap;
use Model\Map\UserTableMap;
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
     */
    const TABLE_MAP = '\\Model\\Map\\SiteTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

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
     * @var        ObjectCollection|ChildOption[] Collection to store aggregation of ChildOption objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildOption> Collection to store aggregation of ChildOption objects.
     */
    protected $collOptions;
    protected $collOptionsPartial;

    /**
     * @var        ObjectCollection|ChildPayment[] Collection to store aggregation of ChildPayment objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildPayment> Collection to store aggregation of ChildPayment objects.
     */
    protected $collPayments;
    protected $collPaymentsPartial;

    /**
     * @var        ObjectCollection|ChildRight[] Collection to store aggregation of ChildRight objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildRight> Collection to store aggregation of ChildRight objects.
     */
    protected $collRights;
    protected $collRightsPartial;

    /**
     * @var        ObjectCollection|ChildUser[] Collection to store aggregation of ChildUser objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildUser> Collection to store aggregation of ChildUser objects.
     */
    protected $collUsers;
    protected $collUsersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOption[]
     * @phpstan-var ObjectCollection&\Traversable<ChildOption>
     */
    protected $optionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPayment[]
     * @phpstan-var ObjectCollection&\Traversable<ChildPayment>
     */
    protected $paymentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRight[]
     * @phpstan-var ObjectCollection&\Traversable<ChildRight>
     */
    protected $rightsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     * @phpstan-var ObjectCollection&\Traversable<ChildUser>
     */
    protected $usersScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
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
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            unset($this->modifiedColumns[$col]);
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Site</code> instance.  If
     * <code>obj</code> is an instance of <code>Site</code>, delegates to
     * <code>equals(Site)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
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
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
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
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @param  string  $keyType                (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME, TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to TableMap::TYPE_PHPNAME.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray($keyType, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setId()

    /**
     * Set the value of [site_name] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setName()

    /**
     * Set the value of [site_pass] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setPass()

    /**
     * Set the value of [site_title] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setTitle()

    /**
     * Set the value of [site_domain] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setDomain()

    /**
     * Set the value of [site_version] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setVersion()

    /**
     * Set the value of [site_tag] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setTag()

    /**
     * Set the value of [site_flag] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setFlag()

    /**
     * Set the value of [site_contact] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setContact()

    /**
     * Set the value of [site_address] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setAddress()

    /**
     * Set the value of [site_tva] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setTva()

    /**
     * Sets the value of the [site_html_renderer] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setHtmlRenderer()

    /**
     * Sets the value of the [site_axys] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setAxys()

    /**
     * Sets the value of the [site_noosfere] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setNoosfere()

    /**
     * Sets the value of the [site_amazon] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setAmazon()

    /**
     * Set the value of [site_event_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setEventId()

    /**
     * Set the value of [site_event_date] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setEventDate()

    /**
     * Sets the value of the [site_shop] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setShop()

    /**
     * Sets the value of the [site_vpc] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setVpc()

    /**
     * Set the value of [site_shipping_fee] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setShippingFee()

    /**
     * Sets the value of the [site_wishlist] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setWishlist()

    /**
     * Sets the value of the [site_payment_cheque] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setPaymentCheque()

    /**
     * Set the value of [site_payment_paypal] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setPaymentPaypal()

    /**
     * Sets the value of the [site_payment_payplug] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setPaymentPayplug()

    /**
     * Sets the value of the [site_payment_transfer] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setPaymentTransfer()

    /**
     * Sets the value of the [site_bookshop] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setBookshop()

    /**
     * Set the value of [site_bookshop_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setBookshopId()

    /**
     * Sets the value of the [site_publisher] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setPublisher()

    /**
     * Sets the value of the [site_publisher_stock] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setPublisherStock()

    /**
     * Set the value of [publisher_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setPublisherId()

    /**
     * Set the value of [site_ebook_bundle] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setEbookBundle()

    /**
     * Set the value of [site_fb_page_id] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setFbPageId()

    /**
     * Set the value of [site_fb_page_token] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setFbPageToken()

    /**
     * Set the value of [site_analytics_id] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setAnalyticsId()

    /**
     * Set the value of [site_piwik_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setPiwikId()

    /**
     * Sets the value of [site_sitemap_updated] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setSitemapUpdated()

    /**
     * Sets the value of the [site_monitoring] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setMonitoring()

    /**
     * Sets the value of [site_created] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setCreatedAt()

    /**
     * Sets the value of [site_updated] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Site The current object (for fluent API support)
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
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
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
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
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
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
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

            $this->collOptions = null;

            $this->collPayments = null;

            $this->collRights = null;

            $this->collUsers = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Site::setDeleted()
     * @see Site::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
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
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
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
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
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

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    foreach ($this->usersScheduledForDeletion as $user) {
                        // need to save related object because we set the relation to null
                        $user->save($con);
                    }
                    $this->usersScheduledForDeletion = null;
                }
            }

            if ($this->collUsers !== null) {
                foreach ($this->collUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
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
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SiteTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getPass();
                break;
            case 3:
                return $this->getTitle();
                break;
            case 4:
                return $this->getDomain();
                break;
            case 5:
                return $this->getVersion();
                break;
            case 6:
                return $this->getTag();
                break;
            case 7:
                return $this->getFlag();
                break;
            case 8:
                return $this->getContact();
                break;
            case 9:
                return $this->getAddress();
                break;
            case 10:
                return $this->getTva();
                break;
            case 11:
                return $this->getHtmlRenderer();
                break;
            case 12:
                return $this->getAxys();
                break;
            case 13:
                return $this->getNoosfere();
                break;
            case 14:
                return $this->getAmazon();
                break;
            case 15:
                return $this->getEventId();
                break;
            case 16:
                return $this->getEventDate();
                break;
            case 17:
                return $this->getShop();
                break;
            case 18:
                return $this->getVpc();
                break;
            case 19:
                return $this->getShippingFee();
                break;
            case 20:
                return $this->getWishlist();
                break;
            case 21:
                return $this->getPaymentCheque();
                break;
            case 22:
                return $this->getPaymentPaypal();
                break;
            case 23:
                return $this->getPaymentPayplug();
                break;
            case 24:
                return $this->getPaymentTransfer();
                break;
            case 25:
                return $this->getBookshop();
                break;
            case 26:
                return $this->getBookshopId();
                break;
            case 27:
                return $this->getPublisher();
                break;
            case 28:
                return $this->getPublisherStock();
                break;
            case 29:
                return $this->getPublisherId();
                break;
            case 30:
                return $this->getEbookBundle();
                break;
            case 31:
                return $this->getFbPageId();
                break;
            case 32:
                return $this->getFbPageToken();
                break;
            case 33:
                return $this->getAnalyticsId();
                break;
            case 34:
                return $this->getPiwikId();
                break;
            case 35:
                return $this->getSitemapUpdated();
                break;
            case 36:
                return $this->getMonitoring();
                break;
            case 37:
                return $this->getCreatedAt();
                break;
            case 38:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Site'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Site'][$this->hashCode()] = true;
        $keys = SiteTableMap::getFieldNames($keyType);
        $result = array(
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
        );
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
            if (null !== $this->collUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'users';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'userss';
                        break;
                    default:
                        $key = 'Users';
                }

                $result[$key] = $this->collUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Model\Site
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SiteTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Site
     */
    public function setByPosition($pos, $value)
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
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return     $this|\Model\Site
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
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
     * @return $this|\Model\Site The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
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
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
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
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildSiteQuery::create();
        $criteria->add(SiteTableMap::COL_SITE_ID, $this->site_id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
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
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Model\Site (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
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

            foreach ($this->getOptions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOption($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPayments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPayment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRights() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRight($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUser($relObj->copy($deepCopy));
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
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Model\Site Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
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
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Option' === $relationName) {
            $this->initOptions();
            return;
        }
        if ('Payment' === $relationName) {
            $this->initPayments();
            return;
        }
        if ('Right' === $relationName) {
            $this->initRights();
            return;
        }
        if ('User' === $relationName) {
            $this->initUsers();
            return;
        }
    }

    /**
     * Clears out the collOptions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOptions()
     */
    public function clearOptions()
    {
        $this->collOptions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOptions collection loaded partially.
     */
    public function resetPartialOptions($v = true)
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
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOptions($overrideExisting = true)
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
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOption[] List of ChildOption objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOption> List of ChildOption objects
     * @throws PropelException
     */
    public function getOptions(Criteria $criteria = null, ConnectionInterface $con = null)
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
     * @param      Collection $options A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSite The current object (for fluent API support)
     */
    public function setOptions(Collection $options, ConnectionInterface $con = null)
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
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Option objects.
     * @throws PropelException
     */
    public function countOptions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
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
     * @param  ChildOption $l ChildOption
     * @return $this|\Model\Site The current object (for fluent API support)
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
    protected function doAddOption(ChildOption $option)
    {
        $this->collOptions[]= $option;
        $option->setSite($this);
    }

    /**
     * @param  ChildOption $option The ChildOption object to remove.
     * @return $this|ChildSite The current object (for fluent API support)
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
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOption[] List of ChildOption objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOption}> List of ChildOption objects
     */
    public function getOptionsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOptionQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getOptions($query, $con);
    }

    /**
     * Clears out the collPayments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPayments()
     */
    public function clearPayments()
    {
        $this->collPayments = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPayments collection loaded partially.
     */
    public function resetPartialPayments($v = true)
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
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPayments($overrideExisting = true)
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
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPayment[] List of ChildPayment objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPayment> List of ChildPayment objects
     * @throws PropelException
     */
    public function getPayments(Criteria $criteria = null, ConnectionInterface $con = null)
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
     * @param      Collection $payments A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSite The current object (for fluent API support)
     */
    public function setPayments(Collection $payments, ConnectionInterface $con = null)
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
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Payment objects.
     * @throws PropelException
     */
    public function countPayments(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
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
     * @param  ChildPayment $l ChildPayment
     * @return $this|\Model\Site The current object (for fluent API support)
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
    protected function doAddPayment(ChildPayment $payment)
    {
        $this->collPayments[]= $payment;
        $payment->setSite($this);
    }

    /**
     * @param  ChildPayment $payment The ChildPayment object to remove.
     * @return $this|ChildSite The current object (for fluent API support)
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
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPayment[] List of ChildPayment objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPayment}> List of ChildPayment objects
     */
    public function getPaymentsJoinOrder(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPaymentQuery::create(null, $criteria);
        $query->joinWith('Order', $joinBehavior);

        return $this->getPayments($query, $con);
    }

    /**
     * Clears out the collRights collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRights()
     */
    public function clearRights()
    {
        $this->collRights = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collRights collection loaded partially.
     */
    public function resetPartialRights($v = true)
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
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRights($overrideExisting = true)
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
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight> List of ChildRight objects
     * @throws PropelException
     */
    public function getRights(Criteria $criteria = null, ConnectionInterface $con = null)
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
     * @param      Collection $rights A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSite The current object (for fluent API support)
     */
    public function setRights(Collection $rights, ConnectionInterface $con = null)
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
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Right objects.
     * @throws PropelException
     */
    public function countRights(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
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
     * @param  ChildRight $l ChildRight
     * @return $this|\Model\Site The current object (for fluent API support)
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
    protected function doAddRight(ChildRight $right)
    {
        $this->collRights[]= $right;
        $right->setSite($this);
    }

    /**
     * @param  ChildRight $right The ChildRight object to remove.
     * @return $this|ChildSite The current object (for fluent API support)
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
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight}> List of ChildRight objects
     */
    public function getRightsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRightQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

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
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight}> List of ChildRight objects
     */
    public function getRightsJoinPublisher(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRightQuery::create(null, $criteria);
        $query->joinWith('Publisher', $joinBehavior);

        return $this->getRights($query, $con);
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUsers collection loaded partially.
     */
    public function resetPartialUsers($v = true)
    {
        $this->collUsersPartial = $v;
    }

    /**
     * Initializes the collUsers collection.
     *
     * By default this just sets the collUsers collection to an empty array (like clearcollUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsers($overrideExisting = true)
    {
        if (null !== $this->collUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserTableMap::getTableMap()->getCollectionClassName();

        $this->collUsers = new $collectionClassName;
        $this->collUsers->setModel('\Model\User');
    }

    /**
     * Gets an array of ChildUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSite is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     * @phpstan-return ObjectCollection&\Traversable<ChildUser> List of ChildUser objects
     * @throws PropelException
     */
    public function getUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collUsers) {
                    $this->initUsers();
                } else {
                    $collectionClassName = UserTableMap::getTableMap()->getCollectionClassName();

                    $collUsers = new $collectionClassName;
                    $collUsers->setModel('\Model\User');

                    return $collUsers;
                }
            } else {
                $collUsers = ChildUserQuery::create(null, $criteria)
                    ->filterBySite($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUsersPartial && count($collUsers)) {
                        $this->initUsers(false);

                        foreach ($collUsers as $obj) {
                            if (false == $this->collUsers->contains($obj)) {
                                $this->collUsers->append($obj);
                            }
                        }

                        $this->collUsersPartial = true;
                    }

                    return $collUsers;
                }

                if ($partial && $this->collUsers) {
                    foreach ($this->collUsers as $obj) {
                        if ($obj->isNew()) {
                            $collUsers[] = $obj;
                        }
                    }
                }

                $this->collUsers = $collUsers;
                $this->collUsersPartial = false;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of ChildUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $users A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSite The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        /** @var ChildUser[] $usersToDelete */
        $usersToDelete = $this->getUsers(new Criteria(), $con)->diff($users);


        $this->usersScheduledForDeletion = $usersToDelete;

        foreach ($usersToDelete as $userRemoved) {
            $userRemoved->setSite(null);
        }

        $this->collUsers = null;
        foreach ($users as $user) {
            $this->addUser($user);
        }

        $this->collUsers = $users;
        $this->collUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related User objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related User objects.
     * @throws PropelException
     */
    public function countUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsers());
            }

            $query = ChildUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySite($this)
                ->count($con);
        }

        return count($this->collUsers);
    }

    /**
     * Method called to associate a ChildUser object to this object
     * through the ChildUser foreign key attribute.
     *
     * @param  ChildUser $l ChildUser
     * @return $this|\Model\Site The current object (for fluent API support)
     */
    public function addUser(ChildUser $l)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
            $this->collUsersPartial = true;
        }

        if (!$this->collUsers->contains($l)) {
            $this->doAddUser($l);

            if ($this->usersScheduledForDeletion and $this->usersScheduledForDeletion->contains($l)) {
                $this->usersScheduledForDeletion->remove($this->usersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUser $user The ChildUser object to add.
     */
    protected function doAddUser(ChildUser $user)
    {
        $this->collUsers[]= $user;
        $user->setSite($this);
    }

    /**
     * @param  ChildUser $user The ChildUser object to remove.
     * @return $this|ChildSite The current object (for fluent API support)
     */
    public function removeUser(ChildUser $user)
    {
        if ($this->getUsers()->contains($user)) {
            $pos = $this->collUsers->search($user);
            $this->collUsers->remove($pos);
            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }
            $this->usersScheduledForDeletion[]= $user;
            $user->setSite(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
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
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collOptions) {
                foreach ($this->collOptions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPayments) {
                foreach ($this->collPayments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRights) {
                foreach ($this->collRights as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collOptions = null;
        $this->collPayments = null;
        $this->collRights = null;
        $this->collUsers = null;
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
     * @return     $this|ChildSite The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[SiteTableMap::COL_SITE_UPDATED] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
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
