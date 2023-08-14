<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Article as ChildArticle;
use Model\ArticleQuery as ChildArticleQuery;
use Model\AxysAccount as ChildAxysAccount;
use Model\AxysAccountQuery as ChildAxysAccountQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\StockQuery as ChildStockQuery;
use Model\Map\StockTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'stock' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Stock implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\StockTableMap';


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
     * The value for the stock_id field.
     *
     * @var        int
     */
    protected $stock_id;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the article_id field.
     *
     * @var        int|null
     */
    protected $article_id;

    /**
     * The value for the campaign_id field.
     *
     * @var        int|null
     */
    protected $campaign_id;

    /**
     * The value for the reward_id field.
     *
     * @var        int|null
     */
    protected $reward_id;

    /**
     * The value for the axys_user_id field.
     *
     * @var        int|null
     */
    protected $axys_user_id;

    /**
     * The value for the customer_id field.
     *
     * @var        int|null
     */
    protected $customer_id;

    /**
     * The value for the wish_id field.
     *
     * @var        int|null
     */
    protected $wish_id;

    /**
     * The value for the cart_id field.
     *
     * @var        int|null
     */
    protected $cart_id;

    /**
     * The value for the order_id field.
     *
     * @var        int|null
     */
    protected $order_id;

    /**
     * The value for the coupon_id field.
     *
     * @var        int|null
     */
    protected $coupon_id;

    /**
     * The value for the stock_shop field.
     *
     * @var        int|null
     */
    protected $stock_shop;

    /**
     * The value for the stock_invoice field.
     *
     * @var        string|null
     */
    protected $stock_invoice;

    /**
     * The value for the stock_depot field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $stock_depot;

    /**
     * The value for the stock_stockage field.
     *
     * @var        string|null
     */
    protected $stock_stockage;

    /**
     * The value for the stock_condition field.
     *
     * @var        string|null
     */
    protected $stock_condition;

    /**
     * The value for the stock_condition_details field.
     *
     * @var        string|null
     */
    protected $stock_condition_details;

    /**
     * The value for the stock_purchase_price field.
     *
     * @var        int|null
     */
    protected $stock_purchase_price;

    /**
     * The value for the stock_selling_price field.
     *
     * @var        int|null
     */
    protected $stock_selling_price;

    /**
     * The value for the stock_selling_price2 field.
     *
     * @var        int|null
     */
    protected $stock_selling_price2;

    /**
     * The value for the stock_selling_price_saved field.
     *
     * @var        int|null
     */
    protected $stock_selling_price_saved;

    /**
     * The value for the stock_selling_price_ht field.
     *
     * @var        int|null
     */
    protected $stock_selling_price_ht;

    /**
     * The value for the stock_selling_price_tva field.
     *
     * @var        int|null
     */
    protected $stock_selling_price_tva;

    /**
     * The value for the stock_tva_rate field.
     *
     * @var        double|null
     */
    protected $stock_tva_rate;

    /**
     * The value for the stock_weight field.
     *
     * @var        int|null
     */
    protected $stock_weight;

    /**
     * The value for the stock_pub_year field.
     *
     * @var        int|null
     */
    protected $stock_pub_year;

    /**
     * The value for the stock_allow_predownload field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $stock_allow_predownload;

    /**
     * The value for the stock_photo_version field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $stock_photo_version;

    /**
     * The value for the stock_purchase_date field.
     *
     * @var        DateTime|null
     */
    protected $stock_purchase_date;

    /**
     * The value for the stock_onsale_date field.
     *
     * @var        DateTime|null
     */
    protected $stock_onsale_date;

    /**
     * The value for the stock_cart_date field.
     *
     * @var        DateTime|null
     */
    protected $stock_cart_date;

    /**
     * The value for the stock_selling_date field.
     *
     * @var        DateTime|null
     */
    protected $stock_selling_date;

    /**
     * The value for the stock_return_date field.
     *
     * @var        DateTime|null
     */
    protected $stock_return_date;

    /**
     * The value for the stock_lost_date field.
     *
     * @var        DateTime|null
     */
    protected $stock_lost_date;

    /**
     * The value for the stock_media_ok field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $stock_media_ok;

    /**
     * The value for the stock_file_updated field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $stock_file_updated;

    /**
     * The value for the stock_insert field.
     *
     * @var        DateTime|null
     */
    protected $stock_insert;

    /**
     * The value for the stock_update field.
     *
     * @var        DateTime|null
     */
    protected $stock_update;

    /**
     * The value for the stock_dl field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $stock_dl;

    /**
     * The value for the stock_created field.
     *
     * @var        DateTime|null
     */
    protected $stock_created;

    /**
     * The value for the stock_updated field.
     *
     * @var        DateTime|null
     */
    protected $stock_updated;

    /**
     * @var        ChildSite
     */
    protected $aSite;

    /**
     * @var        ChildArticle
     */
    protected $aArticle;

    /**
     * @var        ChildAxysAccount
     */
    protected $aAxysAccount;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
        $this->stock_depot = false;
        $this->stock_allow_predownload = false;
        $this->stock_photo_version = 0;
        $this->stock_media_ok = false;
        $this->stock_file_updated = false;
        $this->stock_dl = false;
    }

    /**
     * Initializes internal state of Model\Base\Stock object.
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
     * Compares this with another <code>Stock</code> instance.  If
     * <code>obj</code> is an instance of <code>Stock</code>, delegates to
     * <code>equals(Stock)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [stock_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->stock_id;
    }

    /**
     * Get the [site_id] column value.
     *
     * @return int|null
     */
    public function getSiteId()
    {
        return $this->site_id;
    }

    /**
     * Get the [article_id] column value.
     *
     * @return int|null
     */
    public function getArticleId()
    {
        return $this->article_id;
    }

    /**
     * Get the [campaign_id] column value.
     *
     * @return int|null
     */
    public function getCampaignId()
    {
        return $this->campaign_id;
    }

    /**
     * Get the [reward_id] column value.
     *
     * @return int|null
     */
    public function getRewardId()
    {
        return $this->reward_id;
    }

    /**
     * Get the [axys_user_id] column value.
     *
     * @return int|null
     */
    public function getAxysUserId()
    {
        return $this->axys_user_id;
    }

    /**
     * Get the [customer_id] column value.
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Get the [wish_id] column value.
     *
     * @return int|null
     */
    public function getWishId()
    {
        return $this->wish_id;
    }

    /**
     * Get the [cart_id] column value.
     *
     * @return int|null
     */
    public function getCartId()
    {
        return $this->cart_id;
    }

    /**
     * Get the [order_id] column value.
     *
     * @return int|null
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * Get the [coupon_id] column value.
     *
     * @return int|null
     */
    public function getCouponId()
    {
        return $this->coupon_id;
    }

    /**
     * Get the [stock_shop] column value.
     *
     * @return int|null
     */
    public function getShop()
    {
        return $this->stock_shop;
    }

    /**
     * Get the [stock_invoice] column value.
     *
     * @return string|null
     */
    public function getInvoice()
    {
        return $this->stock_invoice;
    }

    /**
     * Get the [stock_depot] column value.
     *
     * @return boolean|null
     */
    public function getDepot()
    {
        return $this->stock_depot;
    }

    /**
     * Get the [stock_depot] column value.
     *
     * @return boolean|null
     */
    public function isDepot()
    {
        return $this->getDepot();
    }

    /**
     * Get the [stock_stockage] column value.
     *
     * @return string|null
     */
    public function getStockage()
    {
        return $this->stock_stockage;
    }

    /**
     * Get the [stock_condition] column value.
     *
     * @return string|null
     */
    public function getCondition()
    {
        return $this->stock_condition;
    }

    /**
     * Get the [stock_condition_details] column value.
     *
     * @return string|null
     */
    public function getConditionDetails()
    {
        return $this->stock_condition_details;
    }

    /**
     * Get the [stock_purchase_price] column value.
     *
     * @return int|null
     */
    public function getPurchasePrice()
    {
        return $this->stock_purchase_price;
    }

    /**
     * Get the [stock_selling_price] column value.
     *
     * @return int|null
     */
    public function getSellingPrice()
    {
        return $this->stock_selling_price;
    }

    /**
     * Get the [stock_selling_price2] column value.
     *
     * @return int|null
     */
    public function getSellingPrice2()
    {
        return $this->stock_selling_price2;
    }

    /**
     * Get the [stock_selling_price_saved] column value.
     *
     * @return int|null
     */
    public function getSellingPriceSaved()
    {
        return $this->stock_selling_price_saved;
    }

    /**
     * Get the [stock_selling_price_ht] column value.
     *
     * @return int|null
     */
    public function getSellingPriceHt()
    {
        return $this->stock_selling_price_ht;
    }

    /**
     * Get the [stock_selling_price_tva] column value.
     *
     * @return int|null
     */
    public function getSellingPriceTva()
    {
        return $this->stock_selling_price_tva;
    }

    /**
     * Get the [stock_tva_rate] column value.
     *
     * @return double|null
     */
    public function getTvaRate()
    {
        return $this->stock_tva_rate;
    }

    /**
     * Get the [stock_weight] column value.
     *
     * @return int|null
     */
    public function getWeight()
    {
        return $this->stock_weight;
    }

    /**
     * Get the [stock_pub_year] column value.
     *
     * @return int|null
     */
    public function getPubYear()
    {
        return $this->stock_pub_year;
    }

    /**
     * Get the [stock_allow_predownload] column value.
     *
     * @return boolean|null
     */
    public function getAllowPredownload()
    {
        return $this->stock_allow_predownload;
    }

    /**
     * Get the [stock_allow_predownload] column value.
     *
     * @return boolean|null
     */
    public function isAllowPredownload()
    {
        return $this->getAllowPredownload();
    }

    /**
     * Get the [stock_photo_version] column value.
     *
     * @return int|null
     */
    public function getPhotoVersion()
    {
        return $this->stock_photo_version;
    }

    /**
     * Get the [optionally formatted] temporal [stock_purchase_date] column value.
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
    public function getPurchaseDate($format = null)
    {
        if ($format === null) {
            return $this->stock_purchase_date;
        } else {
            return $this->stock_purchase_date instanceof \DateTimeInterface ? $this->stock_purchase_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [stock_onsale_date] column value.
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
    public function getOnsaleDate($format = null)
    {
        if ($format === null) {
            return $this->stock_onsale_date;
        } else {
            return $this->stock_onsale_date instanceof \DateTimeInterface ? $this->stock_onsale_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [stock_cart_date] column value.
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
    public function getCartDate($format = null)
    {
        if ($format === null) {
            return $this->stock_cart_date;
        } else {
            return $this->stock_cart_date instanceof \DateTimeInterface ? $this->stock_cart_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [stock_selling_date] column value.
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
    public function getSellingDate($format = null)
    {
        if ($format === null) {
            return $this->stock_selling_date;
        } else {
            return $this->stock_selling_date instanceof \DateTimeInterface ? $this->stock_selling_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [stock_return_date] column value.
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
    public function getReturnDate($format = null)
    {
        if ($format === null) {
            return $this->stock_return_date;
        } else {
            return $this->stock_return_date instanceof \DateTimeInterface ? $this->stock_return_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [stock_lost_date] column value.
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
    public function getLostDate($format = null)
    {
        if ($format === null) {
            return $this->stock_lost_date;
        } else {
            return $this->stock_lost_date instanceof \DateTimeInterface ? $this->stock_lost_date->format($format) : null;
        }
    }

    /**
     * Get the [stock_media_ok] column value.
     *
     * @return boolean|null
     */
    public function getMediaOk()
    {
        return $this->stock_media_ok;
    }

    /**
     * Get the [stock_media_ok] column value.
     *
     * @return boolean|null
     */
    public function isMediaOk()
    {
        return $this->getMediaOk();
    }

    /**
     * Get the [stock_file_updated] column value.
     *
     * @return boolean|null
     */
    public function getFileUpdated()
    {
        return $this->stock_file_updated;
    }

    /**
     * Get the [stock_file_updated] column value.
     *
     * @return boolean|null
     */
    public function isFileUpdated()
    {
        return $this->getFileUpdated();
    }

    /**
     * Get the [optionally formatted] temporal [stock_insert] column value.
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
    public function getInsert($format = null)
    {
        if ($format === null) {
            return $this->stock_insert;
        } else {
            return $this->stock_insert instanceof \DateTimeInterface ? $this->stock_insert->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [stock_update] column value.
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
    public function getUpdate($format = null)
    {
        if ($format === null) {
            return $this->stock_update;
        } else {
            return $this->stock_update instanceof \DateTimeInterface ? $this->stock_update->format($format) : null;
        }
    }

    /**
     * Get the [stock_dl] column value.
     *
     * @return boolean|null
     */
    public function getDl()
    {
        return $this->stock_dl;
    }

    /**
     * Get the [stock_dl] column value.
     *
     * @return boolean|null
     */
    public function isDl()
    {
        return $this->getDl();
    }

    /**
     * Get the [optionally formatted] temporal [stock_created] column value.
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
            return $this->stock_created;
        } else {
            return $this->stock_created instanceof \DateTimeInterface ? $this->stock_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [stock_updated] column value.
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
            return $this->stock_updated;
        } else {
            return $this->stock_updated instanceof \DateTimeInterface ? $this->stock_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [stock_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_id !== $v) {
            $this->stock_id = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSiteId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_id !== $v) {
            $this->site_id = $v;
            $this->modifiedColumns[StockTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    }

    /**
     * Set the value of [article_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setArticleId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_id !== $v) {
            $this->article_id = $v;
            $this->modifiedColumns[StockTableMap::COL_ARTICLE_ID] = true;
        }

        if ($this->aArticle !== null && $this->aArticle->getId() !== $v) {
            $this->aArticle = null;
        }

        return $this;
    }

    /**
     * Set the value of [campaign_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCampaignId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->campaign_id !== $v) {
            $this->campaign_id = $v;
            $this->modifiedColumns[StockTableMap::COL_CAMPAIGN_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [reward_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setRewardId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reward_id !== $v) {
            $this->reward_id = $v;
            $this->modifiedColumns[StockTableMap::COL_REWARD_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [axys_user_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAxysUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->axys_user_id !== $v) {
            $this->axys_user_id = $v;
            $this->modifiedColumns[StockTableMap::COL_AXYS_USER_ID] = true;
        }

        if ($this->aAxysAccount !== null && $this->aAxysAccount->getId() !== $v) {
            $this->aAxysAccount = null;
        }

        return $this;
    }

    /**
     * Set the value of [customer_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCustomerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->customer_id !== $v) {
            $this->customer_id = $v;
            $this->modifiedColumns[StockTableMap::COL_CUSTOMER_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [wish_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setWishId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->wish_id !== $v) {
            $this->wish_id = $v;
            $this->modifiedColumns[StockTableMap::COL_WISH_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [cart_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCartId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cart_id !== $v) {
            $this->cart_id = $v;
            $this->modifiedColumns[StockTableMap::COL_CART_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setOrderId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_id !== $v) {
            $this->order_id = $v;
            $this->modifiedColumns[StockTableMap::COL_ORDER_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [coupon_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCouponId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->coupon_id !== $v) {
            $this->coupon_id = $v;
            $this->modifiedColumns[StockTableMap::COL_COUPON_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_shop] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setShop($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_shop !== $v) {
            $this->stock_shop = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_SHOP] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_invoice] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setInvoice($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->stock_invoice !== $v) {
            $this->stock_invoice = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_INVOICE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [stock_depot] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setDepot($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->stock_depot !== $v) {
            $this->stock_depot = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_DEPOT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_stockage] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setStockage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->stock_stockage !== $v) {
            $this->stock_stockage = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_STOCKAGE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_condition] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCondition($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->stock_condition !== $v) {
            $this->stock_condition = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_CONDITION] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_condition_details] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setConditionDetails($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->stock_condition_details !== $v) {
            $this->stock_condition_details = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_CONDITION_DETAILS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_purchase_price] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPurchasePrice($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_purchase_price !== $v) {
            $this->stock_purchase_price = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_PURCHASE_PRICE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_selling_price] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSellingPrice($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_selling_price !== $v) {
            $this->stock_selling_price = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_SELLING_PRICE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_selling_price2] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSellingPrice2($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_selling_price2 !== $v) {
            $this->stock_selling_price2 = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_SELLING_PRICE2] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_selling_price_saved] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSellingPriceSaved($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_selling_price_saved !== $v) {
            $this->stock_selling_price_saved = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_SELLING_PRICE_SAVED] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_selling_price_ht] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSellingPriceHt($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_selling_price_ht !== $v) {
            $this->stock_selling_price_ht = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_SELLING_PRICE_HT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_selling_price_tva] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSellingPriceTva($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_selling_price_tva !== $v) {
            $this->stock_selling_price_tva = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_SELLING_PRICE_TVA] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_tva_rate] column.
     *
     * @param double|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTvaRate($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->stock_tva_rate !== $v) {
            $this->stock_tva_rate = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_TVA_RATE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_weight] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setWeight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_weight !== $v) {
            $this->stock_weight = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_WEIGHT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_pub_year] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPubYear($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_pub_year !== $v) {
            $this->stock_pub_year = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_PUB_YEAR] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [stock_allow_predownload] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setAllowPredownload($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->stock_allow_predownload !== $v) {
            $this->stock_allow_predownload = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_ALLOW_PREDOWNLOAD] = true;
        }

        return $this;
    }

    /**
     * Set the value of [stock_photo_version] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPhotoVersion($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_photo_version !== $v) {
            $this->stock_photo_version = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_PHOTO_VERSION] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [stock_purchase_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setPurchaseDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stock_purchase_date !== null || $dt !== null) {
            if ($this->stock_purchase_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->stock_purchase_date->format("Y-m-d H:i:s.u")) {
                $this->stock_purchase_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[StockTableMap::COL_STOCK_PURCHASE_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [stock_onsale_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setOnsaleDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stock_onsale_date !== null || $dt !== null) {
            if ($this->stock_onsale_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->stock_onsale_date->format("Y-m-d H:i:s.u")) {
                $this->stock_onsale_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[StockTableMap::COL_STOCK_ONSALE_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [stock_cart_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCartDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stock_cart_date !== null || $dt !== null) {
            if ($this->stock_cart_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->stock_cart_date->format("Y-m-d H:i:s.u")) {
                $this->stock_cart_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[StockTableMap::COL_STOCK_CART_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [stock_selling_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setSellingDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stock_selling_date !== null || $dt !== null) {
            if ($this->stock_selling_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->stock_selling_date->format("Y-m-d H:i:s.u")) {
                $this->stock_selling_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[StockTableMap::COL_STOCK_SELLING_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [stock_return_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setReturnDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stock_return_date !== null || $dt !== null) {
            if ($this->stock_return_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->stock_return_date->format("Y-m-d H:i:s.u")) {
                $this->stock_return_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[StockTableMap::COL_STOCK_RETURN_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [stock_lost_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setLostDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stock_lost_date !== null || $dt !== null) {
            if ($this->stock_lost_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->stock_lost_date->format("Y-m-d H:i:s.u")) {
                $this->stock_lost_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[StockTableMap::COL_STOCK_LOST_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of the [stock_media_ok] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setMediaOk($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->stock_media_ok !== $v) {
            $this->stock_media_ok = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_MEDIA_OK] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [stock_file_updated] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setFileUpdated($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->stock_file_updated !== $v) {
            $this->stock_file_updated = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_FILE_UPDATED] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [stock_insert] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stock_insert !== null || $dt !== null) {
            if ($this->stock_insert === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->stock_insert->format("Y-m-d H:i:s.u")) {
                $this->stock_insert = $dt === null ? null : clone $dt;
                $this->modifiedColumns[StockTableMap::COL_STOCK_INSERT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [stock_update] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stock_update !== null || $dt !== null) {
            if ($this->stock_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->stock_update->format("Y-m-d H:i:s.u")) {
                $this->stock_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[StockTableMap::COL_STOCK_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of the [stock_dl] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setDl($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->stock_dl !== $v) {
            $this->stock_dl = $v;
            $this->modifiedColumns[StockTableMap::COL_STOCK_DL] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [stock_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stock_created !== null || $dt !== null) {
            if ($this->stock_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->stock_created->format("Y-m-d H:i:s.u")) {
                $this->stock_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[StockTableMap::COL_STOCK_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [stock_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stock_updated !== null || $dt !== null) {
            if ($this->stock_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->stock_updated->format("Y-m-d H:i:s.u")) {
                $this->stock_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[StockTableMap::COL_STOCK_UPDATED] = true;
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
            if ($this->stock_depot !== false) {
                return false;
            }

            if ($this->stock_allow_predownload !== false) {
                return false;
            }

            if ($this->stock_photo_version !== 0) {
                return false;
            }

            if ($this->stock_media_ok !== false) {
                return false;
            }

            if ($this->stock_file_updated !== false) {
                return false;
            }

            if ($this->stock_dl !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : StockTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : StockTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : StockTableMap::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : StockTableMap::translateFieldName('CampaignId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->campaign_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : StockTableMap::translateFieldName('RewardId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reward_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : StockTableMap::translateFieldName('AxysUserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : StockTableMap::translateFieldName('CustomerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->customer_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : StockTableMap::translateFieldName('WishId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->wish_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : StockTableMap::translateFieldName('CartId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : StockTableMap::translateFieldName('OrderId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : StockTableMap::translateFieldName('CouponId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->coupon_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : StockTableMap::translateFieldName('Shop', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_shop = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : StockTableMap::translateFieldName('Invoice', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_invoice = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : StockTableMap::translateFieldName('Depot', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_depot = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : StockTableMap::translateFieldName('Stockage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_stockage = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : StockTableMap::translateFieldName('Condition', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_condition = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : StockTableMap::translateFieldName('ConditionDetails', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_condition_details = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : StockTableMap::translateFieldName('PurchasePrice', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_purchase_price = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : StockTableMap::translateFieldName('SellingPrice', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_selling_price = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : StockTableMap::translateFieldName('SellingPrice2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_selling_price2 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : StockTableMap::translateFieldName('SellingPriceSaved', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_selling_price_saved = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : StockTableMap::translateFieldName('SellingPriceHt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_selling_price_ht = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : StockTableMap::translateFieldName('SellingPriceTva', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_selling_price_tva = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : StockTableMap::translateFieldName('TvaRate', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_tva_rate = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : StockTableMap::translateFieldName('Weight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_weight = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : StockTableMap::translateFieldName('PubYear', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_pub_year = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 26 + $startcol : StockTableMap::translateFieldName('AllowPredownload', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_allow_predownload = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 27 + $startcol : StockTableMap::translateFieldName('PhotoVersion', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_photo_version = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 28 + $startcol : StockTableMap::translateFieldName('PurchaseDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->stock_purchase_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 29 + $startcol : StockTableMap::translateFieldName('OnsaleDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->stock_onsale_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 30 + $startcol : StockTableMap::translateFieldName('CartDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->stock_cart_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 31 + $startcol : StockTableMap::translateFieldName('SellingDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->stock_selling_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 32 + $startcol : StockTableMap::translateFieldName('ReturnDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->stock_return_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 33 + $startcol : StockTableMap::translateFieldName('LostDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->stock_lost_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 34 + $startcol : StockTableMap::translateFieldName('MediaOk', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_media_ok = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 35 + $startcol : StockTableMap::translateFieldName('FileUpdated', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_file_updated = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 36 + $startcol : StockTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->stock_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 37 + $startcol : StockTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->stock_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 38 + $startcol : StockTableMap::translateFieldName('Dl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_dl = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 39 + $startcol : StockTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->stock_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 40 + $startcol : StockTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->stock_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 41; // 41 = StockTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Stock'), 0, $e);
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
        if ($this->aSite !== null && $this->site_id !== $this->aSite->getId()) {
            $this->aSite = null;
        }
        if ($this->aArticle !== null && $this->article_id !== $this->aArticle->getId()) {
            $this->aArticle = null;
        }
        if ($this->aAxysAccount !== null && $this->axys_user_id !== $this->aAxysAccount->getId()) {
            $this->aAxysAccount = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(StockTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildStockQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSite = null;
            $this->aArticle = null;
            $this->aAxysAccount = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Stock::setDeleted()
     * @see Stock::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(StockTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildStockQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(StockTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(StockTableMap::COL_STOCK_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(StockTableMap::COL_STOCK_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(StockTableMap::COL_STOCK_UPDATED)) {
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
                StockTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aSite !== null) {
                if ($this->aSite->isModified() || $this->aSite->isNew()) {
                    $affectedRows += $this->aSite->save($con);
                }
                $this->setSite($this->aSite);
            }

            if ($this->aArticle !== null) {
                if ($this->aArticle->isModified() || $this->aArticle->isNew()) {
                    $affectedRows += $this->aArticle->save($con);
                }
                $this->setArticle($this->aArticle);
            }

            if ($this->aAxysAccount !== null) {
                if ($this->aAxysAccount->isModified() || $this->aAxysAccount->isNew()) {
                    $affectedRows += $this->aAxysAccount->save($con);
                }
                $this->setAxysAccount($this->aAxysAccount);
            }

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

        $this->modifiedColumns[StockTableMap::COL_STOCK_ID] = true;
        if (null !== $this->stock_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . StockTableMap::COL_STOCK_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(StockTableMap::COL_STOCK_ID)) {
            $modifiedColumns[':p' . $index++]  = 'stock_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_ARTICLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'article_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_CAMPAIGN_ID)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_REWARD_ID)) {
            $modifiedColumns[':p' . $index++]  = 'reward_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_AXYS_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'axys_user_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_CUSTOMER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'customer_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_WISH_ID)) {
            $modifiedColumns[':p' . $index++]  = 'wish_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_CART_ID)) {
            $modifiedColumns[':p' . $index++]  = 'cart_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_ORDER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'order_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_COUPON_ID)) {
            $modifiedColumns[':p' . $index++]  = 'coupon_id';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SHOP)) {
            $modifiedColumns[':p' . $index++]  = 'stock_shop';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_INVOICE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_invoice';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_DEPOT)) {
            $modifiedColumns[':p' . $index++]  = 'stock_depot';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_STOCKAGE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_stockage';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_CONDITION)) {
            $modifiedColumns[':p' . $index++]  = 'stock_condition';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_CONDITION_DETAILS)) {
            $modifiedColumns[':p' . $index++]  = 'stock_condition_details';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_PURCHASE_PRICE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_purchase_price';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_PRICE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_selling_price';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_PRICE2)) {
            $modifiedColumns[':p' . $index++]  = 'stock_selling_price2';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_PRICE_SAVED)) {
            $modifiedColumns[':p' . $index++]  = 'stock_selling_price_saved';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_PRICE_HT)) {
            $modifiedColumns[':p' . $index++]  = 'stock_selling_price_ht';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_PRICE_TVA)) {
            $modifiedColumns[':p' . $index++]  = 'stock_selling_price_tva';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_TVA_RATE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_tva_rate';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_WEIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'stock_weight';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_PUB_YEAR)) {
            $modifiedColumns[':p' . $index++]  = 'stock_pub_year';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_ALLOW_PREDOWNLOAD)) {
            $modifiedColumns[':p' . $index++]  = 'stock_allow_predownload';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_PHOTO_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'stock_photo_version';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_PURCHASE_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_purchase_date';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_ONSALE_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_onsale_date';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_CART_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_cart_date';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_selling_date';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_RETURN_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_return_date';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_LOST_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_lost_date';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_MEDIA_OK)) {
            $modifiedColumns[':p' . $index++]  = 'stock_media_ok';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_FILE_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'stock_file_updated';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_INSERT)) {
            $modifiedColumns[':p' . $index++]  = 'stock_insert';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'stock_update';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_DL)) {
            $modifiedColumns[':p' . $index++]  = 'stock_dl';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'stock_created';
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'stock_updated';
        }

        $sql = sprintf(
            'INSERT INTO stock (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'stock_id':
                        $stmt->bindValue($identifier, $this->stock_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'article_id':
                        $stmt->bindValue($identifier, $this->article_id, PDO::PARAM_INT);

                        break;
                    case 'campaign_id':
                        $stmt->bindValue($identifier, $this->campaign_id, PDO::PARAM_INT);

                        break;
                    case 'reward_id':
                        $stmt->bindValue($identifier, $this->reward_id, PDO::PARAM_INT);

                        break;
                    case 'axys_user_id':
                        $stmt->bindValue($identifier, $this->axys_user_id, PDO::PARAM_INT);

                        break;
                    case 'customer_id':
                        $stmt->bindValue($identifier, $this->customer_id, PDO::PARAM_INT);

                        break;
                    case 'wish_id':
                        $stmt->bindValue($identifier, $this->wish_id, PDO::PARAM_INT);

                        break;
                    case 'cart_id':
                        $stmt->bindValue($identifier, $this->cart_id, PDO::PARAM_INT);

                        break;
                    case 'order_id':
                        $stmt->bindValue($identifier, $this->order_id, PDO::PARAM_INT);

                        break;
                    case 'coupon_id':
                        $stmt->bindValue($identifier, $this->coupon_id, PDO::PARAM_INT);

                        break;
                    case 'stock_shop':
                        $stmt->bindValue($identifier, $this->stock_shop, PDO::PARAM_INT);

                        break;
                    case 'stock_invoice':
                        $stmt->bindValue($identifier, $this->stock_invoice, PDO::PARAM_STR);

                        break;
                    case 'stock_depot':
                        $stmt->bindValue($identifier, (int) $this->stock_depot, PDO::PARAM_INT);

                        break;
                    case 'stock_stockage':
                        $stmt->bindValue($identifier, $this->stock_stockage, PDO::PARAM_STR);

                        break;
                    case 'stock_condition':
                        $stmt->bindValue($identifier, $this->stock_condition, PDO::PARAM_STR);

                        break;
                    case 'stock_condition_details':
                        $stmt->bindValue($identifier, $this->stock_condition_details, PDO::PARAM_STR);

                        break;
                    case 'stock_purchase_price':
                        $stmt->bindValue($identifier, $this->stock_purchase_price, PDO::PARAM_INT);

                        break;
                    case 'stock_selling_price':
                        $stmt->bindValue($identifier, $this->stock_selling_price, PDO::PARAM_INT);

                        break;
                    case 'stock_selling_price2':
                        $stmt->bindValue($identifier, $this->stock_selling_price2, PDO::PARAM_INT);

                        break;
                    case 'stock_selling_price_saved':
                        $stmt->bindValue($identifier, $this->stock_selling_price_saved, PDO::PARAM_INT);

                        break;
                    case 'stock_selling_price_ht':
                        $stmt->bindValue($identifier, $this->stock_selling_price_ht, PDO::PARAM_INT);

                        break;
                    case 'stock_selling_price_tva':
                        $stmt->bindValue($identifier, $this->stock_selling_price_tva, PDO::PARAM_INT);

                        break;
                    case 'stock_tva_rate':
                        $stmt->bindValue($identifier, $this->stock_tva_rate, PDO::PARAM_STR);

                        break;
                    case 'stock_weight':
                        $stmt->bindValue($identifier, $this->stock_weight, PDO::PARAM_INT);

                        break;
                    case 'stock_pub_year':
                        $stmt->bindValue($identifier, $this->stock_pub_year, PDO::PARAM_INT);

                        break;
                    case 'stock_allow_predownload':
                        $stmt->bindValue($identifier, (int) $this->stock_allow_predownload, PDO::PARAM_INT);

                        break;
                    case 'stock_photo_version':
                        $stmt->bindValue($identifier, $this->stock_photo_version, PDO::PARAM_INT);

                        break;
                    case 'stock_purchase_date':
                        $stmt->bindValue($identifier, $this->stock_purchase_date ? $this->stock_purchase_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'stock_onsale_date':
                        $stmt->bindValue($identifier, $this->stock_onsale_date ? $this->stock_onsale_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'stock_cart_date':
                        $stmt->bindValue($identifier, $this->stock_cart_date ? $this->stock_cart_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'stock_selling_date':
                        $stmt->bindValue($identifier, $this->stock_selling_date ? $this->stock_selling_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'stock_return_date':
                        $stmt->bindValue($identifier, $this->stock_return_date ? $this->stock_return_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'stock_lost_date':
                        $stmt->bindValue($identifier, $this->stock_lost_date ? $this->stock_lost_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'stock_media_ok':
                        $stmt->bindValue($identifier, (int) $this->stock_media_ok, PDO::PARAM_INT);

                        break;
                    case 'stock_file_updated':
                        $stmt->bindValue($identifier, (int) $this->stock_file_updated, PDO::PARAM_INT);

                        break;
                    case 'stock_insert':
                        $stmt->bindValue($identifier, $this->stock_insert ? $this->stock_insert->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'stock_update':
                        $stmt->bindValue($identifier, $this->stock_update ? $this->stock_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'stock_dl':
                        $stmt->bindValue($identifier, (int) $this->stock_dl, PDO::PARAM_INT);

                        break;
                    case 'stock_created':
                        $stmt->bindValue($identifier, $this->stock_created ? $this->stock_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'stock_updated':
                        $stmt->bindValue($identifier, $this->stock_updated ? $this->stock_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = StockTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getSiteId();

            case 2:
                return $this->getArticleId();

            case 3:
                return $this->getCampaignId();

            case 4:
                return $this->getRewardId();

            case 5:
                return $this->getAxysUserId();

            case 6:
                return $this->getCustomerId();

            case 7:
                return $this->getWishId();

            case 8:
                return $this->getCartId();

            case 9:
                return $this->getOrderId();

            case 10:
                return $this->getCouponId();

            case 11:
                return $this->getShop();

            case 12:
                return $this->getInvoice();

            case 13:
                return $this->getDepot();

            case 14:
                return $this->getStockage();

            case 15:
                return $this->getCondition();

            case 16:
                return $this->getConditionDetails();

            case 17:
                return $this->getPurchasePrice();

            case 18:
                return $this->getSellingPrice();

            case 19:
                return $this->getSellingPrice2();

            case 20:
                return $this->getSellingPriceSaved();

            case 21:
                return $this->getSellingPriceHt();

            case 22:
                return $this->getSellingPriceTva();

            case 23:
                return $this->getTvaRate();

            case 24:
                return $this->getWeight();

            case 25:
                return $this->getPubYear();

            case 26:
                return $this->getAllowPredownload();

            case 27:
                return $this->getPhotoVersion();

            case 28:
                return $this->getPurchaseDate();

            case 29:
                return $this->getOnsaleDate();

            case 30:
                return $this->getCartDate();

            case 31:
                return $this->getSellingDate();

            case 32:
                return $this->getReturnDate();

            case 33:
                return $this->getLostDate();

            case 34:
                return $this->getMediaOk();

            case 35:
                return $this->getFileUpdated();

            case 36:
                return $this->getInsert();

            case 37:
                return $this->getUpdate();

            case 38:
                return $this->getDl();

            case 39:
                return $this->getCreatedAt();

            case 40:
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
        if (isset($alreadyDumpedObjects['Stock'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Stock'][$this->hashCode()] = true;
        $keys = StockTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getArticleId(),
            $keys[3] => $this->getCampaignId(),
            $keys[4] => $this->getRewardId(),
            $keys[5] => $this->getAxysUserId(),
            $keys[6] => $this->getCustomerId(),
            $keys[7] => $this->getWishId(),
            $keys[8] => $this->getCartId(),
            $keys[9] => $this->getOrderId(),
            $keys[10] => $this->getCouponId(),
            $keys[11] => $this->getShop(),
            $keys[12] => $this->getInvoice(),
            $keys[13] => $this->getDepot(),
            $keys[14] => $this->getStockage(),
            $keys[15] => $this->getCondition(),
            $keys[16] => $this->getConditionDetails(),
            $keys[17] => $this->getPurchasePrice(),
            $keys[18] => $this->getSellingPrice(),
            $keys[19] => $this->getSellingPrice2(),
            $keys[20] => $this->getSellingPriceSaved(),
            $keys[21] => $this->getSellingPriceHt(),
            $keys[22] => $this->getSellingPriceTva(),
            $keys[23] => $this->getTvaRate(),
            $keys[24] => $this->getWeight(),
            $keys[25] => $this->getPubYear(),
            $keys[26] => $this->getAllowPredownload(),
            $keys[27] => $this->getPhotoVersion(),
            $keys[28] => $this->getPurchaseDate(),
            $keys[29] => $this->getOnsaleDate(),
            $keys[30] => $this->getCartDate(),
            $keys[31] => $this->getSellingDate(),
            $keys[32] => $this->getReturnDate(),
            $keys[33] => $this->getLostDate(),
            $keys[34] => $this->getMediaOk(),
            $keys[35] => $this->getFileUpdated(),
            $keys[36] => $this->getInsert(),
            $keys[37] => $this->getUpdate(),
            $keys[38] => $this->getDl(),
            $keys[39] => $this->getCreatedAt(),
            $keys[40] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[28]] instanceof \DateTimeInterface) {
            $result[$keys[28]] = $result[$keys[28]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[29]] instanceof \DateTimeInterface) {
            $result[$keys[29]] = $result[$keys[29]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[30]] instanceof \DateTimeInterface) {
            $result[$keys[30]] = $result[$keys[30]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[31]] instanceof \DateTimeInterface) {
            $result[$keys[31]] = $result[$keys[31]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[32]] instanceof \DateTimeInterface) {
            $result[$keys[32]] = $result[$keys[32]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[33]] instanceof \DateTimeInterface) {
            $result[$keys[33]] = $result[$keys[33]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[36]] instanceof \DateTimeInterface) {
            $result[$keys[36]] = $result[$keys[36]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[37]] instanceof \DateTimeInterface) {
            $result[$keys[37]] = $result[$keys[37]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[39]] instanceof \DateTimeInterface) {
            $result[$keys[39]] = $result[$keys[39]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[40]] instanceof \DateTimeInterface) {
            $result[$keys[40]] = $result[$keys[40]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aSite) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'site';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'sites';
                        break;
                    default:
                        $key = 'Site';
                }

                $result[$key] = $this->aSite->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aArticle) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'article';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'articles';
                        break;
                    default:
                        $key = 'Article';
                }

                $result[$key] = $this->aArticle->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aAxysAccount) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'axysAccount';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'axys_accounts';
                        break;
                    default:
                        $key = 'AxysAccount';
                }

                $result[$key] = $this->aAxysAccount->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = StockTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setSiteId($value);
                break;
            case 2:
                $this->setArticleId($value);
                break;
            case 3:
                $this->setCampaignId($value);
                break;
            case 4:
                $this->setRewardId($value);
                break;
            case 5:
                $this->setAxysUserId($value);
                break;
            case 6:
                $this->setCustomerId($value);
                break;
            case 7:
                $this->setWishId($value);
                break;
            case 8:
                $this->setCartId($value);
                break;
            case 9:
                $this->setOrderId($value);
                break;
            case 10:
                $this->setCouponId($value);
                break;
            case 11:
                $this->setShop($value);
                break;
            case 12:
                $this->setInvoice($value);
                break;
            case 13:
                $this->setDepot($value);
                break;
            case 14:
                $this->setStockage($value);
                break;
            case 15:
                $this->setCondition($value);
                break;
            case 16:
                $this->setConditionDetails($value);
                break;
            case 17:
                $this->setPurchasePrice($value);
                break;
            case 18:
                $this->setSellingPrice($value);
                break;
            case 19:
                $this->setSellingPrice2($value);
                break;
            case 20:
                $this->setSellingPriceSaved($value);
                break;
            case 21:
                $this->setSellingPriceHt($value);
                break;
            case 22:
                $this->setSellingPriceTva($value);
                break;
            case 23:
                $this->setTvaRate($value);
                break;
            case 24:
                $this->setWeight($value);
                break;
            case 25:
                $this->setPubYear($value);
                break;
            case 26:
                $this->setAllowPredownload($value);
                break;
            case 27:
                $this->setPhotoVersion($value);
                break;
            case 28:
                $this->setPurchaseDate($value);
                break;
            case 29:
                $this->setOnsaleDate($value);
                break;
            case 30:
                $this->setCartDate($value);
                break;
            case 31:
                $this->setSellingDate($value);
                break;
            case 32:
                $this->setReturnDate($value);
                break;
            case 33:
                $this->setLostDate($value);
                break;
            case 34:
                $this->setMediaOk($value);
                break;
            case 35:
                $this->setFileUpdated($value);
                break;
            case 36:
                $this->setInsert($value);
                break;
            case 37:
                $this->setUpdate($value);
                break;
            case 38:
                $this->setDl($value);
                break;
            case 39:
                $this->setCreatedAt($value);
                break;
            case 40:
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
        $keys = StockTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setArticleId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCampaignId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setRewardId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAxysUserId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCustomerId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setWishId($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setCartId($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setOrderId($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setCouponId($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setShop($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setInvoice($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setDepot($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setStockage($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setCondition($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setConditionDetails($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setPurchasePrice($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setSellingPrice($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setSellingPrice2($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setSellingPriceSaved($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setSellingPriceHt($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setSellingPriceTva($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setTvaRate($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setWeight($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setPubYear($arr[$keys[25]]);
        }
        if (array_key_exists($keys[26], $arr)) {
            $this->setAllowPredownload($arr[$keys[26]]);
        }
        if (array_key_exists($keys[27], $arr)) {
            $this->setPhotoVersion($arr[$keys[27]]);
        }
        if (array_key_exists($keys[28], $arr)) {
            $this->setPurchaseDate($arr[$keys[28]]);
        }
        if (array_key_exists($keys[29], $arr)) {
            $this->setOnsaleDate($arr[$keys[29]]);
        }
        if (array_key_exists($keys[30], $arr)) {
            $this->setCartDate($arr[$keys[30]]);
        }
        if (array_key_exists($keys[31], $arr)) {
            $this->setSellingDate($arr[$keys[31]]);
        }
        if (array_key_exists($keys[32], $arr)) {
            $this->setReturnDate($arr[$keys[32]]);
        }
        if (array_key_exists($keys[33], $arr)) {
            $this->setLostDate($arr[$keys[33]]);
        }
        if (array_key_exists($keys[34], $arr)) {
            $this->setMediaOk($arr[$keys[34]]);
        }
        if (array_key_exists($keys[35], $arr)) {
            $this->setFileUpdated($arr[$keys[35]]);
        }
        if (array_key_exists($keys[36], $arr)) {
            $this->setInsert($arr[$keys[36]]);
        }
        if (array_key_exists($keys[37], $arr)) {
            $this->setUpdate($arr[$keys[37]]);
        }
        if (array_key_exists($keys[38], $arr)) {
            $this->setDl($arr[$keys[38]]);
        }
        if (array_key_exists($keys[39], $arr)) {
            $this->setCreatedAt($arr[$keys[39]]);
        }
        if (array_key_exists($keys[40], $arr)) {
            $this->setUpdatedAt($arr[$keys[40]]);
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
        $criteria = new Criteria(StockTableMap::DATABASE_NAME);

        if ($this->isColumnModified(StockTableMap::COL_STOCK_ID)) {
            $criteria->add(StockTableMap::COL_STOCK_ID, $this->stock_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_SITE_ID)) {
            $criteria->add(StockTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_ARTICLE_ID)) {
            $criteria->add(StockTableMap::COL_ARTICLE_ID, $this->article_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_CAMPAIGN_ID)) {
            $criteria->add(StockTableMap::COL_CAMPAIGN_ID, $this->campaign_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_REWARD_ID)) {
            $criteria->add(StockTableMap::COL_REWARD_ID, $this->reward_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_AXYS_USER_ID)) {
            $criteria->add(StockTableMap::COL_AXYS_USER_ID, $this->axys_user_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_CUSTOMER_ID)) {
            $criteria->add(StockTableMap::COL_CUSTOMER_ID, $this->customer_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_WISH_ID)) {
            $criteria->add(StockTableMap::COL_WISH_ID, $this->wish_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_CART_ID)) {
            $criteria->add(StockTableMap::COL_CART_ID, $this->cart_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_ORDER_ID)) {
            $criteria->add(StockTableMap::COL_ORDER_ID, $this->order_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_COUPON_ID)) {
            $criteria->add(StockTableMap::COL_COUPON_ID, $this->coupon_id);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SHOP)) {
            $criteria->add(StockTableMap::COL_STOCK_SHOP, $this->stock_shop);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_INVOICE)) {
            $criteria->add(StockTableMap::COL_STOCK_INVOICE, $this->stock_invoice);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_DEPOT)) {
            $criteria->add(StockTableMap::COL_STOCK_DEPOT, $this->stock_depot);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_STOCKAGE)) {
            $criteria->add(StockTableMap::COL_STOCK_STOCKAGE, $this->stock_stockage);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_CONDITION)) {
            $criteria->add(StockTableMap::COL_STOCK_CONDITION, $this->stock_condition);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_CONDITION_DETAILS)) {
            $criteria->add(StockTableMap::COL_STOCK_CONDITION_DETAILS, $this->stock_condition_details);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_PURCHASE_PRICE)) {
            $criteria->add(StockTableMap::COL_STOCK_PURCHASE_PRICE, $this->stock_purchase_price);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_PRICE)) {
            $criteria->add(StockTableMap::COL_STOCK_SELLING_PRICE, $this->stock_selling_price);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_PRICE2)) {
            $criteria->add(StockTableMap::COL_STOCK_SELLING_PRICE2, $this->stock_selling_price2);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_PRICE_SAVED)) {
            $criteria->add(StockTableMap::COL_STOCK_SELLING_PRICE_SAVED, $this->stock_selling_price_saved);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_PRICE_HT)) {
            $criteria->add(StockTableMap::COL_STOCK_SELLING_PRICE_HT, $this->stock_selling_price_ht);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_PRICE_TVA)) {
            $criteria->add(StockTableMap::COL_STOCK_SELLING_PRICE_TVA, $this->stock_selling_price_tva);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_TVA_RATE)) {
            $criteria->add(StockTableMap::COL_STOCK_TVA_RATE, $this->stock_tva_rate);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_WEIGHT)) {
            $criteria->add(StockTableMap::COL_STOCK_WEIGHT, $this->stock_weight);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_PUB_YEAR)) {
            $criteria->add(StockTableMap::COL_STOCK_PUB_YEAR, $this->stock_pub_year);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_ALLOW_PREDOWNLOAD)) {
            $criteria->add(StockTableMap::COL_STOCK_ALLOW_PREDOWNLOAD, $this->stock_allow_predownload);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_PHOTO_VERSION)) {
            $criteria->add(StockTableMap::COL_STOCK_PHOTO_VERSION, $this->stock_photo_version);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_PURCHASE_DATE)) {
            $criteria->add(StockTableMap::COL_STOCK_PURCHASE_DATE, $this->stock_purchase_date);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_ONSALE_DATE)) {
            $criteria->add(StockTableMap::COL_STOCK_ONSALE_DATE, $this->stock_onsale_date);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_CART_DATE)) {
            $criteria->add(StockTableMap::COL_STOCK_CART_DATE, $this->stock_cart_date);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_SELLING_DATE)) {
            $criteria->add(StockTableMap::COL_STOCK_SELLING_DATE, $this->stock_selling_date);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_RETURN_DATE)) {
            $criteria->add(StockTableMap::COL_STOCK_RETURN_DATE, $this->stock_return_date);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_LOST_DATE)) {
            $criteria->add(StockTableMap::COL_STOCK_LOST_DATE, $this->stock_lost_date);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_MEDIA_OK)) {
            $criteria->add(StockTableMap::COL_STOCK_MEDIA_OK, $this->stock_media_ok);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_FILE_UPDATED)) {
            $criteria->add(StockTableMap::COL_STOCK_FILE_UPDATED, $this->stock_file_updated);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_INSERT)) {
            $criteria->add(StockTableMap::COL_STOCK_INSERT, $this->stock_insert);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_UPDATE)) {
            $criteria->add(StockTableMap::COL_STOCK_UPDATE, $this->stock_update);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_DL)) {
            $criteria->add(StockTableMap::COL_STOCK_DL, $this->stock_dl);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_CREATED)) {
            $criteria->add(StockTableMap::COL_STOCK_CREATED, $this->stock_created);
        }
        if ($this->isColumnModified(StockTableMap::COL_STOCK_UPDATED)) {
            $criteria->add(StockTableMap::COL_STOCK_UPDATED, $this->stock_updated);
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
        $criteria = ChildStockQuery::create();
        $criteria->add(StockTableMap::COL_STOCK_ID, $this->stock_id);

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
     * Generic method to set the primary key (stock_id column).
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
     * @param object $copyObj An object of \Model\Stock (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setArticleId($this->getArticleId());
        $copyObj->setCampaignId($this->getCampaignId());
        $copyObj->setRewardId($this->getRewardId());
        $copyObj->setAxysUserId($this->getAxysUserId());
        $copyObj->setCustomerId($this->getCustomerId());
        $copyObj->setWishId($this->getWishId());
        $copyObj->setCartId($this->getCartId());
        $copyObj->setOrderId($this->getOrderId());
        $copyObj->setCouponId($this->getCouponId());
        $copyObj->setShop($this->getShop());
        $copyObj->setInvoice($this->getInvoice());
        $copyObj->setDepot($this->getDepot());
        $copyObj->setStockage($this->getStockage());
        $copyObj->setCondition($this->getCondition());
        $copyObj->setConditionDetails($this->getConditionDetails());
        $copyObj->setPurchasePrice($this->getPurchasePrice());
        $copyObj->setSellingPrice($this->getSellingPrice());
        $copyObj->setSellingPrice2($this->getSellingPrice2());
        $copyObj->setSellingPriceSaved($this->getSellingPriceSaved());
        $copyObj->setSellingPriceHt($this->getSellingPriceHt());
        $copyObj->setSellingPriceTva($this->getSellingPriceTva());
        $copyObj->setTvaRate($this->getTvaRate());
        $copyObj->setWeight($this->getWeight());
        $copyObj->setPubYear($this->getPubYear());
        $copyObj->setAllowPredownload($this->getAllowPredownload());
        $copyObj->setPhotoVersion($this->getPhotoVersion());
        $copyObj->setPurchaseDate($this->getPurchaseDate());
        $copyObj->setOnsaleDate($this->getOnsaleDate());
        $copyObj->setCartDate($this->getCartDate());
        $copyObj->setSellingDate($this->getSellingDate());
        $copyObj->setReturnDate($this->getReturnDate());
        $copyObj->setLostDate($this->getLostDate());
        $copyObj->setMediaOk($this->getMediaOk());
        $copyObj->setFileUpdated($this->getFileUpdated());
        $copyObj->setInsert($this->getInsert());
        $copyObj->setUpdate($this->getUpdate());
        $copyObj->setDl($this->getDl());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
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
     * @return \Model\Stock Clone of current object.
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
     * Declares an association between this object and a ChildSite object.
     *
     * @param ChildSite|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setSite(ChildSite $v = null)
    {
        if ($v === null) {
            $this->setSiteId(NULL);
        } else {
            $this->setSiteId($v->getId());
        }

        $this->aSite = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildSite object, it will not be re-added.
        if ($v !== null) {
            $v->addStock($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSite object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildSite|null The associated ChildSite object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSite(?ConnectionInterface $con = null)
    {
        if ($this->aSite === null && ($this->site_id != 0)) {
            $this->aSite = ChildSiteQuery::create()->findPk($this->site_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSite->addStocks($this);
             */
        }

        return $this->aSite;
    }

    /**
     * Declares an association between this object and a ChildArticle object.
     *
     * @param ChildArticle|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setArticle(ChildArticle $v = null)
    {
        if ($v === null) {
            $this->setArticleId(NULL);
        } else {
            $this->setArticleId($v->getId());
        }

        $this->aArticle = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildArticle object, it will not be re-added.
        if ($v !== null) {
            $v->addStock($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildArticle object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildArticle|null The associated ChildArticle object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getArticle(?ConnectionInterface $con = null)
    {
        if ($this->aArticle === null && ($this->article_id != 0)) {
            $this->aArticle = ChildArticleQuery::create()->findPk($this->article_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aArticle->addStocks($this);
             */
        }

        return $this->aArticle;
    }

    /**
     * Declares an association between this object and a ChildAxysAccount object.
     *
     * @param ChildAxysAccount|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setAxysAccount(ChildAxysAccount $v = null)
    {
        if ($v === null) {
            $this->setAxysUserId(NULL);
        } else {
            $this->setAxysUserId($v->getId());
        }

        $this->aAxysAccount = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAxysAccount object, it will not be re-added.
        if ($v !== null) {
            $v->addStock($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAxysAccount object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildAxysAccount|null The associated ChildAxysAccount object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getAxysAccount(?ConnectionInterface $con = null)
    {
        if ($this->aAxysAccount === null && ($this->axys_user_id != 0)) {
            $this->aAxysAccount = ChildAxysAccountQuery::create()->findPk($this->axys_user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAxysAccount->addStocks($this);
             */
        }

        return $this->aAxysAccount;
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
        if (null !== $this->aSite) {
            $this->aSite->removeStock($this);
        }
        if (null !== $this->aArticle) {
            $this->aArticle->removeStock($this);
        }
        if (null !== $this->aAxysAccount) {
            $this->aAxysAccount->removeStock($this);
        }
        $this->stock_id = null;
        $this->site_id = null;
        $this->article_id = null;
        $this->campaign_id = null;
        $this->reward_id = null;
        $this->axys_user_id = null;
        $this->customer_id = null;
        $this->wish_id = null;
        $this->cart_id = null;
        $this->order_id = null;
        $this->coupon_id = null;
        $this->stock_shop = null;
        $this->stock_invoice = null;
        $this->stock_depot = null;
        $this->stock_stockage = null;
        $this->stock_condition = null;
        $this->stock_condition_details = null;
        $this->stock_purchase_price = null;
        $this->stock_selling_price = null;
        $this->stock_selling_price2 = null;
        $this->stock_selling_price_saved = null;
        $this->stock_selling_price_ht = null;
        $this->stock_selling_price_tva = null;
        $this->stock_tva_rate = null;
        $this->stock_weight = null;
        $this->stock_pub_year = null;
        $this->stock_allow_predownload = null;
        $this->stock_photo_version = null;
        $this->stock_purchase_date = null;
        $this->stock_onsale_date = null;
        $this->stock_cart_date = null;
        $this->stock_selling_date = null;
        $this->stock_return_date = null;
        $this->stock_lost_date = null;
        $this->stock_media_ok = null;
        $this->stock_file_updated = null;
        $this->stock_insert = null;
        $this->stock_update = null;
        $this->stock_dl = null;
        $this->stock_created = null;
        $this->stock_updated = null;
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
        } // if ($deep)

        $this->aSite = null;
        $this->aArticle = null;
        $this->aAxysAccount = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(StockTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[StockTableMap::COL_STOCK_UPDATED] = true;

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
