<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Order as ChildOrder;
use Model\OrderQuery as ChildOrderQuery;
use Model\ShippingOption as ChildShippingOption;
use Model\ShippingOptionQuery as ChildShippingOptionQuery;
use Model\ShippingZone as ChildShippingZone;
use Model\ShippingZoneQuery as ChildShippingZoneQuery;
use Model\Map\OrderTableMap;
use Model\Map\ShippingOptionTableMap;
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
 * Base class that represents a row from the 'shipping' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class ShippingOption implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\ShippingOptionTableMap';


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
     * The value for the shipping_id field.
     *
     * @var        int
     */
    protected $shipping_id;

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
     * The value for the shipping_mode field.
     *
     * @var        string|null
     */
    protected $shipping_mode;

    /**
     * The value for the shipping_type field.
     *
     * @var        string|null
     */
    protected $shipping_type;

    /**
     * The value for the shipping_zone field.
     *
     * @var        string|null
     */
    protected $shipping_zone;

    /**
     * The value for the shipping_zone_id field.
     *
     * @var        int|null
     */
    protected $shipping_zone_id;

    /**
     * The value for the shipping_min_weight field.
     *
     * @var        int|null
     */
    protected $shipping_min_weight;

    /**
     * The value for the shipping_max_weight field.
     *
     * @var        int|null
     */
    protected $shipping_max_weight;

    /**
     * The value for the shipping_max_articles field.
     *
     * @var        int|null
     */
    protected $shipping_max_articles;

    /**
     * The value for the shipping_min_amount field.
     *
     * @var        int|null
     */
    protected $shipping_min_amount;

    /**
     * The value for the shipping_max_amount field.
     *
     * @var        int|null
     */
    protected $shipping_max_amount;

    /**
     * The value for the shipping_fee field.
     *
     * @var        int|null
     */
    protected $shipping_fee;

    /**
     * The value for the shipping_info field.
     *
     * @var        string|null
     */
    protected $shipping_info;

    /**
     * The value for the shipping_created field.
     *
     * @var        DateTime|null
     */
    protected $shipping_created;

    /**
     * The value for the shipping_updated field.
     *
     * @var        DateTime|null
     */
    protected $shipping_updated;

    /**
     * The value for the shipping_archived_at field.
     *
     * @var        DateTime|null
     */
    protected $shipping_archived_at;

    /**
     * @var        ChildShippingZone
     */
    protected $aShippingZone;

    /**
     * @var        ObjectCollection|ChildOrder[] Collection to store aggregation of ChildOrder objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildOrder> Collection to store aggregation of ChildOrder objects.
     */
    protected $collOrders;
    protected $collOrdersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOrder[]
     * @phpstan-var ObjectCollection&\Traversable<ChildOrder>
     */
    protected $ordersScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Base\ShippingOption object.
     */
    public function __construct()
    {
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
     * Compares this with another <code>ShippingOption</code> instance.  If
     * <code>obj</code> is an instance of <code>ShippingOption</code>, delegates to
     * <code>equals(ShippingOption)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [shipping_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->shipping_id;
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
     * Get the [shipping_mode] column value.
     *
     * @return string|null
     */
    public function getMode()
    {
        return $this->shipping_mode;
    }

    /**
     * Get the [shipping_type] column value.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->shipping_type;
    }

    /**
     * Get the [shipping_zone] column value.
     *
     * @return string|null
     */
    public function getZoneCode()
    {
        return $this->shipping_zone;
    }

    /**
     * Get the [shipping_zone_id] column value.
     *
     * @return int|null
     */
    public function getShippingZoneId()
    {
        return $this->shipping_zone_id;
    }

    /**
     * Get the [shipping_min_weight] column value.
     *
     * @return int|null
     */
    public function getMinWeight()
    {
        return $this->shipping_min_weight;
    }

    /**
     * Get the [shipping_max_weight] column value.
     *
     * @return int|null
     */
    public function getMaxWeight()
    {
        return $this->shipping_max_weight;
    }

    /**
     * Get the [shipping_max_articles] column value.
     *
     * @return int|null
     */
    public function getMaxArticles()
    {
        return $this->shipping_max_articles;
    }

    /**
     * Get the [shipping_min_amount] column value.
     *
     * @return int|null
     */
    public function getMinAmount()
    {
        return $this->shipping_min_amount;
    }

    /**
     * Get the [shipping_max_amount] column value.
     *
     * @return int|null
     */
    public function getMaxAmount()
    {
        return $this->shipping_max_amount;
    }

    /**
     * Get the [shipping_fee] column value.
     *
     * @return int|null
     */
    public function getFee()
    {
        return $this->shipping_fee;
    }

    /**
     * Get the [shipping_info] column value.
     *
     * @return string|null
     */
    public function getInfo()
    {
        return $this->shipping_info;
    }

    /**
     * Get the [optionally formatted] temporal [shipping_created] column value.
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
            return $this->shipping_created;
        } else {
            return $this->shipping_created instanceof \DateTimeInterface ? $this->shipping_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [shipping_updated] column value.
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
            return $this->shipping_updated;
        } else {
            return $this->shipping_updated instanceof \DateTimeInterface ? $this->shipping_updated->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [shipping_archived_at] column value.
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
    public function getArchivedAt($format = null)
    {
        if ($format === null) {
            return $this->shipping_archived_at;
        } else {
            return $this->shipping_archived_at instanceof \DateTimeInterface ? $this->shipping_archived_at->format($format) : null;
        }
    }

    /**
     * Set the value of [shipping_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_id !== $v) {
            $this->shipping_id = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_ID] = true;
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
            $this->modifiedColumns[ShippingOptionTableMap::COL_SITE_ID] = true;
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
            $this->modifiedColumns[ShippingOptionTableMap::COL_ARTICLE_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_mode] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->shipping_mode !== $v) {
            $this->shipping_mode = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_MODE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_type] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->shipping_type !== $v) {
            $this->shipping_type = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_TYPE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_zone] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setZoneCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->shipping_zone !== $v) {
            $this->shipping_zone = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_ZONE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_zone_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setShippingZoneId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_zone_id !== $v) {
            $this->shipping_zone_id = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_ZONE_ID] = true;
        }

        if ($this->aShippingZone !== null && $this->aShippingZone->getId() !== $v) {
            $this->aShippingZone = null;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_min_weight] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMinWeight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_min_weight !== $v) {
            $this->shipping_min_weight = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_max_weight] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMaxWeight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_max_weight !== $v) {
            $this->shipping_max_weight = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_max_articles] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMaxArticles($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_max_articles !== $v) {
            $this->shipping_max_articles = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_min_amount] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMinAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_min_amount !== $v) {
            $this->shipping_min_amount = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_max_amount] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMaxAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_max_amount !== $v) {
            $this->shipping_max_amount = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_fee] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFee($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_fee !== $v) {
            $this->shipping_fee = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_FEE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_info] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setInfo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->shipping_info !== $v) {
            $this->shipping_info = $v;
            $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_INFO] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [shipping_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->shipping_created !== null || $dt !== null) {
            if ($this->shipping_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->shipping_created->format("Y-m-d H:i:s.u")) {
                $this->shipping_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [shipping_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->shipping_updated !== null || $dt !== null) {
            if ($this->shipping_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->shipping_updated->format("Y-m-d H:i:s.u")) {
                $this->shipping_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_UPDATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [shipping_archived_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setArchivedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->shipping_archived_at !== null || $dt !== null) {
            if ($this->shipping_archived_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->shipping_archived_at->format("Y-m-d H:i:s.u")) {
                $this->shipping_archived_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ShippingOptionTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ShippingOptionTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ShippingOptionTableMap::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ShippingOptionTableMap::translateFieldName('Mode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_mode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ShippingOptionTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ShippingOptionTableMap::translateFieldName('ZoneCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_zone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ShippingOptionTableMap::translateFieldName('ShippingZoneId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_zone_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : ShippingOptionTableMap::translateFieldName('MinWeight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_min_weight = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : ShippingOptionTableMap::translateFieldName('MaxWeight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_max_weight = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : ShippingOptionTableMap::translateFieldName('MaxArticles', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_max_articles = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : ShippingOptionTableMap::translateFieldName('MinAmount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_min_amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : ShippingOptionTableMap::translateFieldName('MaxAmount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_max_amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : ShippingOptionTableMap::translateFieldName('Fee', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_fee = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : ShippingOptionTableMap::translateFieldName('Info', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_info = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : ShippingOptionTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->shipping_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : ShippingOptionTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->shipping_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : ShippingOptionTableMap::translateFieldName('ArchivedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->shipping_archived_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 17; // 17 = ShippingOptionTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\ShippingOption'), 0, $e);
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
        if ($this->aShippingZone !== null && $this->shipping_zone_id !== $this->aShippingZone->getId()) {
            $this->aShippingZone = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(ShippingOptionTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildShippingOptionQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aShippingZone = null;
            $this->collOrders = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see ShippingOption::setDeleted()
     * @see ShippingOption::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingOptionTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildShippingOptionQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingOptionTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_UPDATED)) {
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
                ShippingOptionTableMap::addInstanceToPool($this);
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

            if ($this->aShippingZone !== null) {
                if ($this->aShippingZone->isModified() || $this->aShippingZone->isNew()) {
                    $affectedRows += $this->aShippingZone->save($con);
                }
                $this->setShippingZone($this->aShippingZone);
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

        $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_ID] = true;
        if (null !== $this->shipping_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ShippingOptionTableMap::COL_SHIPPING_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_ID)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_id';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_ARTICLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'article_id';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MODE)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_mode';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_type';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_ZONE)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_zone';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_ZONE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_zone_id';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_min_weight';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_max_weight';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_max_articles';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_min_amount';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_max_amount';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_FEE)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_fee';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_INFO)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_info';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_created';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_updated';
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_archived_at';
        }

        $sql = sprintf(
            'INSERT INTO shipping (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'shipping_id':
                        $stmt->bindValue($identifier, $this->shipping_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'article_id':
                        $stmt->bindValue($identifier, $this->article_id, PDO::PARAM_INT);

                        break;
                    case 'shipping_mode':
                        $stmt->bindValue($identifier, $this->shipping_mode, PDO::PARAM_STR);

                        break;
                    case 'shipping_type':
                        $stmt->bindValue($identifier, $this->shipping_type, PDO::PARAM_STR);

                        break;
                    case 'shipping_zone':
                        $stmt->bindValue($identifier, $this->shipping_zone, PDO::PARAM_STR);

                        break;
                    case 'shipping_zone_id':
                        $stmt->bindValue($identifier, $this->shipping_zone_id, PDO::PARAM_INT);

                        break;
                    case 'shipping_min_weight':
                        $stmt->bindValue($identifier, $this->shipping_min_weight, PDO::PARAM_INT);

                        break;
                    case 'shipping_max_weight':
                        $stmt->bindValue($identifier, $this->shipping_max_weight, PDO::PARAM_INT);

                        break;
                    case 'shipping_max_articles':
                        $stmt->bindValue($identifier, $this->shipping_max_articles, PDO::PARAM_INT);

                        break;
                    case 'shipping_min_amount':
                        $stmt->bindValue($identifier, $this->shipping_min_amount, PDO::PARAM_INT);

                        break;
                    case 'shipping_max_amount':
                        $stmt->bindValue($identifier, $this->shipping_max_amount, PDO::PARAM_INT);

                        break;
                    case 'shipping_fee':
                        $stmt->bindValue($identifier, $this->shipping_fee, PDO::PARAM_INT);

                        break;
                    case 'shipping_info':
                        $stmt->bindValue($identifier, $this->shipping_info, PDO::PARAM_STR);

                        break;
                    case 'shipping_created':
                        $stmt->bindValue($identifier, $this->shipping_created ? $this->shipping_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'shipping_updated':
                        $stmt->bindValue($identifier, $this->shipping_updated ? $this->shipping_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'shipping_archived_at':
                        $stmt->bindValue($identifier, $this->shipping_archived_at ? $this->shipping_archived_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = ShippingOptionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getMode();

            case 4:
                return $this->getType();

            case 5:
                return $this->getZoneCode();

            case 6:
                return $this->getShippingZoneId();

            case 7:
                return $this->getMinWeight();

            case 8:
                return $this->getMaxWeight();

            case 9:
                return $this->getMaxArticles();

            case 10:
                return $this->getMinAmount();

            case 11:
                return $this->getMaxAmount();

            case 12:
                return $this->getFee();

            case 13:
                return $this->getInfo();

            case 14:
                return $this->getCreatedAt();

            case 15:
                return $this->getUpdatedAt();

            case 16:
                return $this->getArchivedAt();

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
        if (isset($alreadyDumpedObjects['ShippingOption'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['ShippingOption'][$this->hashCode()] = true;
        $keys = ShippingOptionTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getArticleId(),
            $keys[3] => $this->getMode(),
            $keys[4] => $this->getType(),
            $keys[5] => $this->getZoneCode(),
            $keys[6] => $this->getShippingZoneId(),
            $keys[7] => $this->getMinWeight(),
            $keys[8] => $this->getMaxWeight(),
            $keys[9] => $this->getMaxArticles(),
            $keys[10] => $this->getMinAmount(),
            $keys[11] => $this->getMaxAmount(),
            $keys[12] => $this->getFee(),
            $keys[13] => $this->getInfo(),
            $keys[14] => $this->getCreatedAt(),
            $keys[15] => $this->getUpdatedAt(),
            $keys[16] => $this->getArchivedAt(),
        ];
        if ($result[$keys[14]] instanceof \DateTimeInterface) {
            $result[$keys[14]] = $result[$keys[14]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[15]] instanceof \DateTimeInterface) {
            $result[$keys[15]] = $result[$keys[15]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[16]] instanceof \DateTimeInterface) {
            $result[$keys[16]] = $result[$keys[16]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aShippingZone) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'shippingZone';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'shipping_zones';
                        break;
                    default:
                        $key = 'ShippingZone';
                }

                $result[$key] = $this->aShippingZone->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = ShippingOptionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setMode($value);
                break;
            case 4:
                $this->setType($value);
                break;
            case 5:
                $this->setZoneCode($value);
                break;
            case 6:
                $this->setShippingZoneId($value);
                break;
            case 7:
                $this->setMinWeight($value);
                break;
            case 8:
                $this->setMaxWeight($value);
                break;
            case 9:
                $this->setMaxArticles($value);
                break;
            case 10:
                $this->setMinAmount($value);
                break;
            case 11:
                $this->setMaxAmount($value);
                break;
            case 12:
                $this->setFee($value);
                break;
            case 13:
                $this->setInfo($value);
                break;
            case 14:
                $this->setCreatedAt($value);
                break;
            case 15:
                $this->setUpdatedAt($value);
                break;
            case 16:
                $this->setArchivedAt($value);
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
        $keys = ShippingOptionTableMap::getFieldNames($keyType);

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
            $this->setMode($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setType($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setZoneCode($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setShippingZoneId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setMinWeight($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setMaxWeight($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setMaxArticles($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setMinAmount($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setMaxAmount($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setFee($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setInfo($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setCreatedAt($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setUpdatedAt($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setArchivedAt($arr[$keys[16]]);
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
        $criteria = new Criteria(ShippingOptionTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_ID)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_ID, $this->shipping_id);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SITE_ID)) {
            $criteria->add(ShippingOptionTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_ARTICLE_ID)) {
            $criteria->add(ShippingOptionTableMap::COL_ARTICLE_ID, $this->article_id);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MODE)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_MODE, $this->shipping_mode);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_TYPE)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_TYPE, $this->shipping_type);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_ZONE)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_ZONE, $this->shipping_zone);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_ZONE_ID)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_ZONE_ID, $this->shipping_zone_id);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT, $this->shipping_min_weight);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT, $this->shipping_max_weight);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES, $this->shipping_max_articles);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT, $this->shipping_min_amount);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT, $this->shipping_max_amount);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_FEE)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_FEE, $this->shipping_fee);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_INFO)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_INFO, $this->shipping_info);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_CREATED)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_CREATED, $this->shipping_created);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_UPDATED)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_UPDATED, $this->shipping_updated);
        }
        if ($this->isColumnModified(ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT)) {
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT, $this->shipping_archived_at);
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
        $criteria = ChildShippingOptionQuery::create();
        $criteria->add(ShippingOptionTableMap::COL_SHIPPING_ID, $this->shipping_id);

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
     * Generic method to set the primary key (shipping_id column).
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
     * @param object $copyObj An object of \Model\ShippingOption (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setArticleId($this->getArticleId());
        $copyObj->setMode($this->getMode());
        $copyObj->setType($this->getType());
        $copyObj->setZoneCode($this->getZoneCode());
        $copyObj->setShippingZoneId($this->getShippingZoneId());
        $copyObj->setMinWeight($this->getMinWeight());
        $copyObj->setMaxWeight($this->getMaxWeight());
        $copyObj->setMaxArticles($this->getMaxArticles());
        $copyObj->setMinAmount($this->getMinAmount());
        $copyObj->setMaxAmount($this->getMaxAmount());
        $copyObj->setFee($this->getFee());
        $copyObj->setInfo($this->getInfo());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setArchivedAt($this->getArchivedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getOrders() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrder($relObj->copy($deepCopy));
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
     * @return \Model\ShippingOption Clone of current object.
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
     * Declares an association between this object and a ChildShippingZone object.
     *
     * @param ChildShippingZone|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setShippingZone(ChildShippingZone $v = null)
    {
        if ($v === null) {
            $this->setShippingZoneId(NULL);
        } else {
            $this->setShippingZoneId($v->getId());
        }

        $this->aShippingZone = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildShippingZone object, it will not be re-added.
        if ($v !== null) {
            $v->addShippingOption($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildShippingZone object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildShippingZone|null The associated ChildShippingZone object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getShippingZone(?ConnectionInterface $con = null)
    {
        if ($this->aShippingZone === null && ($this->shipping_zone_id != 0)) {
            $this->aShippingZone = ChildShippingZoneQuery::create()->findPk($this->shipping_zone_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aShippingZone->addShippingOptions($this);
             */
        }

        return $this->aShippingZone;
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
        if ('Order' === $relationName) {
            $this->initOrders();
            return;
        }
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
     * If this ChildShippingOption is new, it will return
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
                    ->filterByShippingOption($this)
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
            $orderRemoved->setShippingOption(null);
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
                ->filterByShippingOption($this)
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
        $order->setShippingOption($this);
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
            $order->setShippingOption(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ShippingOption is new, it will return
     * an empty collection; or if this ShippingOption has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ShippingOption.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinUser(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ShippingOption is new, it will return
     * an empty collection; or if this ShippingOption has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ShippingOption.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinCustomer(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('Customer', $joinBehavior);

        return $this->getOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ShippingOption is new, it will return
     * an empty collection; or if this ShippingOption has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ShippingOption.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinCountry(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('Country', $joinBehavior);

        return $this->getOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ShippingOption is new, it will return
     * an empty collection; or if this ShippingOption has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ShippingOption.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getOrders($query, $con);
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
        if (null !== $this->aShippingZone) {
            $this->aShippingZone->removeShippingOption($this);
        }
        $this->shipping_id = null;
        $this->site_id = null;
        $this->article_id = null;
        $this->shipping_mode = null;
        $this->shipping_type = null;
        $this->shipping_zone = null;
        $this->shipping_zone_id = null;
        $this->shipping_min_weight = null;
        $this->shipping_max_weight = null;
        $this->shipping_max_articles = null;
        $this->shipping_min_amount = null;
        $this->shipping_max_amount = null;
        $this->shipping_fee = null;
        $this->shipping_info = null;
        $this->shipping_created = null;
        $this->shipping_updated = null;
        $this->shipping_archived_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
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
            if ($this->collOrders) {
                foreach ($this->collOrders as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collOrders = null;
        $this->aShippingZone = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ShippingOptionTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[ShippingOptionTableMap::COL_SHIPPING_UPDATED] = true;

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
