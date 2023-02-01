<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\CartQuery as ChildCartQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\CartTableMap;
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
 * Base class that represents a row from the 'carts' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Cart implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\CartTableMap';


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
     * The value for the cart_id field.
     *
     * @var        int
     */
    protected $cart_id;

    /**
     * The value for the cart_uid field.
     *
     * @var        string|null
     */
    protected $cart_uid;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the user_id field.
     *
     * @var        int|null
     */
    protected $user_id;

    /**
     * The value for the cart_seller_id field.
     *
     * @var        int|null
     */
    protected $cart_seller_id;

    /**
     * The value for the customer_id field.
     *
     * @var        int|null
     */
    protected $customer_id;

    /**
     * The value for the cart_title field.
     *
     * @var        string|null
     */
    protected $cart_title;

    /**
     * The value for the cart_type field.
     *
     * Note: this column has a database default value of: ''
     * @var        string|null
     */
    protected $cart_type;

    /**
     * The value for the cart_ip field.
     *
     * @var        string|null
     */
    protected $cart_ip;

    /**
     * The value for the cart_count field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $cart_count;

    /**
     * The value for the cart_amount field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $cart_amount;

    /**
     * The value for the cart_as_a_gift field.
     *
     * @var        string|null
     */
    protected $cart_as_a_gift;

    /**
     * The value for the cart_gift_recipient field.
     *
     * @var        int|null
     */
    protected $cart_gift_recipient;

    /**
     * The value for the cart_date field.
     *
     * @var        DateTime|null
     */
    protected $cart_date;

    /**
     * The value for the cart_insert field.
     *
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        DateTime|null
     */
    protected $cart_insert;

    /**
     * The value for the cart_update field.
     *
     * @var        DateTime|null
     */
    protected $cart_update;

    /**
     * The value for the cart_created field.
     *
     * @var        DateTime|null
     */
    protected $cart_created;

    /**
     * The value for the cart_updated field.
     *
     * @var        DateTime|null
     */
    protected $cart_updated;

    /**
     * @var        ChildSite
     */
    protected $aSite;

    /**
     * @var        ChildUser
     */
    protected $aUser;

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
        $this->cart_type = '';
        $this->cart_count = 0;
        $this->cart_amount = 0;
    }

    /**
     * Initializes internal state of Model\Base\Cart object.
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
     * Compares this with another <code>Cart</code> instance.  If
     * <code>obj</code> is an instance of <code>Cart</code>, delegates to
     * <code>equals(Cart)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [cart_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->cart_id;
    }

    /**
     * Get the [cart_uid] column value.
     *
     * @return string|null
     */
    public function getUid()
    {
        return $this->cart_uid;
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
     * Get the [user_id] column value.
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [cart_seller_id] column value.
     *
     * @return int|null
     */
    public function getSellerId()
    {
        return $this->cart_seller_id;
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
     * Get the [cart_title] column value.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->cart_title;
    }

    /**
     * Get the [cart_type] column value.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->cart_type;
    }

    /**
     * Get the [cart_ip] column value.
     *
     * @return string|null
     */
    public function getIp()
    {
        return $this->cart_ip;
    }

    /**
     * Get the [cart_count] column value.
     *
     * @return int|null
     */
    public function getCount()
    {
        return $this->cart_count;
    }

    /**
     * Get the [cart_amount] column value.
     *
     * @return int|null
     */
    public function getAmount()
    {
        return $this->cart_amount;
    }

    /**
     * Get the [cart_as_a_gift] column value.
     *
     * @return string|null
     */
    public function getAsAGift()
    {
        return $this->cart_as_a_gift;
    }

    /**
     * Get the [cart_gift_recipient] column value.
     *
     * @return int|null
     */
    public function getGiftRecipient()
    {
        return $this->cart_gift_recipient;
    }

    /**
     * Get the [optionally formatted] temporal [cart_date] column value.
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
    public function getDate($format = null)
    {
        if ($format === null) {
            return $this->cart_date;
        } else {
            return $this->cart_date instanceof \DateTimeInterface ? $this->cart_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [cart_insert] column value.
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
            return $this->cart_insert;
        } else {
            return $this->cart_insert instanceof \DateTimeInterface ? $this->cart_insert->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [cart_update] column value.
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
            return $this->cart_update;
        } else {
            return $this->cart_update instanceof \DateTimeInterface ? $this->cart_update->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [cart_created] column value.
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
            return $this->cart_created;
        } else {
            return $this->cart_created instanceof \DateTimeInterface ? $this->cart_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [cart_updated] column value.
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
            return $this->cart_updated;
        } else {
            return $this->cart_updated instanceof \DateTimeInterface ? $this->cart_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [cart_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cart_id !== $v) {
            $this->cart_id = $v;
            $this->modifiedColumns[CartTableMap::COL_CART_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [cart_uid] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cart_uid !== $v) {
            $this->cart_uid = $v;
            $this->modifiedColumns[CartTableMap::COL_CART_UID] = true;
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
            $this->modifiedColumns[CartTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    }

    /**
     * Set the value of [user_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[CartTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    }

    /**
     * Set the value of [cart_seller_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSellerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cart_seller_id !== $v) {
            $this->cart_seller_id = $v;
            $this->modifiedColumns[CartTableMap::COL_CART_SELLER_ID] = true;
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
            $this->modifiedColumns[CartTableMap::COL_CUSTOMER_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [cart_title] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cart_title !== $v) {
            $this->cart_title = $v;
            $this->modifiedColumns[CartTableMap::COL_CART_TITLE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [cart_type] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cart_type !== $v) {
            $this->cart_type = $v;
            $this->modifiedColumns[CartTableMap::COL_CART_TYPE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [cart_ip] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setIp($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cart_ip !== $v) {
            $this->cart_ip = $v;
            $this->modifiedColumns[CartTableMap::COL_CART_IP] = true;
        }

        return $this;
    }

    /**
     * Set the value of [cart_count] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cart_count !== $v) {
            $this->cart_count = $v;
            $this->modifiedColumns[CartTableMap::COL_CART_COUNT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [cart_amount] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cart_amount !== $v) {
            $this->cart_amount = $v;
            $this->modifiedColumns[CartTableMap::COL_CART_AMOUNT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [cart_as_a_gift] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAsAGift($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cart_as_a_gift !== $v) {
            $this->cart_as_a_gift = $v;
            $this->modifiedColumns[CartTableMap::COL_AS_A_GIFT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [cart_gift_recipient] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setGiftRecipient($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cart_gift_recipient !== $v) {
            $this->cart_gift_recipient = $v;
            $this->modifiedColumns[CartTableMap::COL_GIFT_RECIPIENT] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [cart_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->cart_date !== null || $dt !== null) {
            if ($this->cart_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->cart_date->format("Y-m-d H:i:s.u")) {
                $this->cart_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CartTableMap::COL_CART_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [cart_insert] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->cart_insert !== null || $dt !== null) {
            if ($this->cart_insert === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->cart_insert->format("Y-m-d H:i:s.u")) {
                $this->cart_insert = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CartTableMap::COL_CART_INSERT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [cart_update] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->cart_update !== null || $dt !== null) {
            if ($this->cart_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->cart_update->format("Y-m-d H:i:s.u")) {
                $this->cart_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CartTableMap::COL_CART_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [cart_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->cart_created !== null || $dt !== null) {
            if ($this->cart_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->cart_created->format("Y-m-d H:i:s.u")) {
                $this->cart_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CartTableMap::COL_CART_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [cart_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->cart_updated !== null || $dt !== null) {
            if ($this->cart_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->cart_updated->format("Y-m-d H:i:s.u")) {
                $this->cart_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CartTableMap::COL_CART_UPDATED] = true;
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
            if ($this->cart_type !== '') {
                return false;
            }

            if ($this->cart_count !== 0) {
                return false;
            }

            if ($this->cart_amount !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CartTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CartTableMap::translateFieldName('Uid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_uid = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CartTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CartTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CartTableMap::translateFieldName('SellerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_seller_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CartTableMap::translateFieldName('CustomerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->customer_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CartTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : CartTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : CartTableMap::translateFieldName('Ip', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_ip = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : CartTableMap::translateFieldName('Count', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : CartTableMap::translateFieldName('Amount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : CartTableMap::translateFieldName('AsAGift', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_as_a_gift = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : CartTableMap::translateFieldName('GiftRecipient', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cart_gift_recipient = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : CartTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->cart_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : CartTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->cart_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : CartTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->cart_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : CartTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->cart_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : CartTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->cart_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 18; // 18 = CartTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Cart'), 0, $e);
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
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(CartTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCartQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSite = null;
            $this->aUser = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Cart::setDeleted()
     * @see Cart::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CartTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCartQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CartTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(CartTableMap::COL_CART_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(CartTableMap::COL_CART_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CartTableMap::COL_CART_UPDATED)) {
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
                CartTableMap::addInstanceToPool($this);
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

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
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

        $this->modifiedColumns[CartTableMap::COL_CART_ID] = true;
        if (null !== $this->cart_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CartTableMap::COL_CART_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CartTableMap::COL_CART_ID)) {
            $modifiedColumns[':p' . $index++]  = 'cart_id';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_UID)) {
            $modifiedColumns[':p' . $index++]  = 'cart_uid';
        }
        if ($this->isColumnModified(CartTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(CartTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_id';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_SELLER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'cart_seller_id';
        }
        if ($this->isColumnModified(CartTableMap::COL_CUSTOMER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'customer_id';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'cart_title';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'cart_type';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_IP)) {
            $modifiedColumns[':p' . $index++]  = 'cart_ip';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_COUNT)) {
            $modifiedColumns[':p' . $index++]  = 'cart_count';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'cart_amount';
        }
        if ($this->isColumnModified(CartTableMap::COL_AS_A_GIFT)) {
            $modifiedColumns[':p' . $index++]  = 'cart_as_a_gift';
        }
        if ($this->isColumnModified(CartTableMap::COL_GIFT_RECIPIENT)) {
            $modifiedColumns[':p' . $index++]  = 'cart_gift_recipient';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'cart_date';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_INSERT)) {
            $modifiedColumns[':p' . $index++]  = 'cart_insert';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'cart_update';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'cart_created';
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'cart_updated';
        }

        $sql = sprintf(
            'INSERT INTO carts (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'cart_id':
                        $stmt->bindValue($identifier, $this->cart_id, PDO::PARAM_INT);

                        break;
                    case 'cart_uid':
                        $stmt->bindValue($identifier, $this->cart_uid, PDO::PARAM_STR);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'user_id':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);

                        break;
                    case 'cart_seller_id':
                        $stmt->bindValue($identifier, $this->cart_seller_id, PDO::PARAM_INT);

                        break;
                    case 'customer_id':
                        $stmt->bindValue($identifier, $this->customer_id, PDO::PARAM_INT);

                        break;
                    case 'cart_title':
                        $stmt->bindValue($identifier, $this->cart_title, PDO::PARAM_STR);

                        break;
                    case 'cart_type':
                        $stmt->bindValue($identifier, $this->cart_type, PDO::PARAM_STR);

                        break;
                    case 'cart_ip':
                        $stmt->bindValue($identifier, $this->cart_ip, PDO::PARAM_STR);

                        break;
                    case 'cart_count':
                        $stmt->bindValue($identifier, $this->cart_count, PDO::PARAM_INT);

                        break;
                    case 'cart_amount':
                        $stmt->bindValue($identifier, $this->cart_amount, PDO::PARAM_INT);

                        break;
                    case 'cart_as_a_gift':
                        $stmt->bindValue($identifier, $this->cart_as_a_gift, PDO::PARAM_STR);

                        break;
                    case 'cart_gift_recipient':
                        $stmt->bindValue($identifier, $this->cart_gift_recipient, PDO::PARAM_INT);

                        break;
                    case 'cart_date':
                        $stmt->bindValue($identifier, $this->cart_date ? $this->cart_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'cart_insert':
                        $stmt->bindValue($identifier, $this->cart_insert ? $this->cart_insert->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'cart_update':
                        $stmt->bindValue($identifier, $this->cart_update ? $this->cart_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'cart_created':
                        $stmt->bindValue($identifier, $this->cart_created ? $this->cart_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'cart_updated':
                        $stmt->bindValue($identifier, $this->cart_updated ? $this->cart_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = CartTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getUid();

            case 2:
                return $this->getSiteId();

            case 3:
                return $this->getUserId();

            case 4:
                return $this->getSellerId();

            case 5:
                return $this->getCustomerId();

            case 6:
                return $this->getTitle();

            case 7:
                return $this->getType();

            case 8:
                return $this->getIp();

            case 9:
                return $this->getCount();

            case 10:
                return $this->getAmount();

            case 11:
                return $this->getAsAGift();

            case 12:
                return $this->getGiftRecipient();

            case 13:
                return $this->getDate();

            case 14:
                return $this->getInsert();

            case 15:
                return $this->getUpdate();

            case 16:
                return $this->getCreatedAt();

            case 17:
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
        if (isset($alreadyDumpedObjects['Cart'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Cart'][$this->hashCode()] = true;
        $keys = CartTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUid(),
            $keys[2] => $this->getSiteId(),
            $keys[3] => $this->getUserId(),
            $keys[4] => $this->getSellerId(),
            $keys[5] => $this->getCustomerId(),
            $keys[6] => $this->getTitle(),
            $keys[7] => $this->getType(),
            $keys[8] => $this->getIp(),
            $keys[9] => $this->getCount(),
            $keys[10] => $this->getAmount(),
            $keys[11] => $this->getAsAGift(),
            $keys[12] => $this->getGiftRecipient(),
            $keys[13] => $this->getDate(),
            $keys[14] => $this->getInsert(),
            $keys[15] => $this->getUpdate(),
            $keys[16] => $this->getCreatedAt(),
            $keys[17] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[13]] instanceof \DateTimeInterface) {
            $result[$keys[13]] = $result[$keys[13]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[14]] instanceof \DateTimeInterface) {
            $result[$keys[14]] = $result[$keys[14]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[15]] instanceof \DateTimeInterface) {
            $result[$keys[15]] = $result[$keys[15]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[16]] instanceof \DateTimeInterface) {
            $result[$keys[16]] = $result[$keys[16]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[17]] instanceof \DateTimeInterface) {
            $result[$keys[17]] = $result[$keys[17]]->format('Y-m-d H:i:s.u');
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
            if (null !== $this->aUser) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = CartTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setUid($value);
                break;
            case 2:
                $this->setSiteId($value);
                break;
            case 3:
                $this->setUserId($value);
                break;
            case 4:
                $this->setSellerId($value);
                break;
            case 5:
                $this->setCustomerId($value);
                break;
            case 6:
                $this->setTitle($value);
                break;
            case 7:
                $this->setType($value);
                break;
            case 8:
                $this->setIp($value);
                break;
            case 9:
                $this->setCount($value);
                break;
            case 10:
                $this->setAmount($value);
                break;
            case 11:
                $this->setAsAGift($value);
                break;
            case 12:
                $this->setGiftRecipient($value);
                break;
            case 13:
                $this->setDate($value);
                break;
            case 14:
                $this->setInsert($value);
                break;
            case 15:
                $this->setUpdate($value);
                break;
            case 16:
                $this->setCreatedAt($value);
                break;
            case 17:
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
        $keys = CartTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setSiteId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUserId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setSellerId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCustomerId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setTitle($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setType($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setIp($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setCount($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setAmount($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setAsAGift($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setGiftRecipient($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setDate($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setInsert($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setUpdate($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setCreatedAt($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setUpdatedAt($arr[$keys[17]]);
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
        $criteria = new Criteria(CartTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CartTableMap::COL_CART_ID)) {
            $criteria->add(CartTableMap::COL_CART_ID, $this->cart_id);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_UID)) {
            $criteria->add(CartTableMap::COL_CART_UID, $this->cart_uid);
        }
        if ($this->isColumnModified(CartTableMap::COL_SITE_ID)) {
            $criteria->add(CartTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(CartTableMap::COL_USER_ID)) {
            $criteria->add(CartTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_SELLER_ID)) {
            $criteria->add(CartTableMap::COL_CART_SELLER_ID, $this->cart_seller_id);
        }
        if ($this->isColumnModified(CartTableMap::COL_CUSTOMER_ID)) {
            $criteria->add(CartTableMap::COL_CUSTOMER_ID, $this->customer_id);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_TITLE)) {
            $criteria->add(CartTableMap::COL_CART_TITLE, $this->cart_title);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_TYPE)) {
            $criteria->add(CartTableMap::COL_CART_TYPE, $this->cart_type);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_IP)) {
            $criteria->add(CartTableMap::COL_CART_IP, $this->cart_ip);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_COUNT)) {
            $criteria->add(CartTableMap::COL_CART_COUNT, $this->cart_count);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_AMOUNT)) {
            $criteria->add(CartTableMap::COL_CART_AMOUNT, $this->cart_amount);
        }
        if ($this->isColumnModified(CartTableMap::COL_AS_A_GIFT)) {
            $criteria->add(CartTableMap::COL_AS_A_GIFT, $this->cart_as_a_gift);
        }
        if ($this->isColumnModified(CartTableMap::COL_GIFT_RECIPIENT)) {
            $criteria->add(CartTableMap::COL_GIFT_RECIPIENT, $this->cart_gift_recipient);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_DATE)) {
            $criteria->add(CartTableMap::COL_CART_DATE, $this->cart_date);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_INSERT)) {
            $criteria->add(CartTableMap::COL_CART_INSERT, $this->cart_insert);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_UPDATE)) {
            $criteria->add(CartTableMap::COL_CART_UPDATE, $this->cart_update);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_CREATED)) {
            $criteria->add(CartTableMap::COL_CART_CREATED, $this->cart_created);
        }
        if ($this->isColumnModified(CartTableMap::COL_CART_UPDATED)) {
            $criteria->add(CartTableMap::COL_CART_UPDATED, $this->cart_updated);
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
        $criteria = ChildCartQuery::create();
        $criteria->add(CartTableMap::COL_CART_ID, $this->cart_id);

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
     * Generic method to set the primary key (cart_id column).
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
     * @param object $copyObj An object of \Model\Cart (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setUid($this->getUid());
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setSellerId($this->getSellerId());
        $copyObj->setCustomerId($this->getCustomerId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setType($this->getType());
        $copyObj->setIp($this->getIp());
        $copyObj->setCount($this->getCount());
        $copyObj->setAmount($this->getAmount());
        $copyObj->setAsAGift($this->getAsAGift());
        $copyObj->setGiftRecipient($this->getGiftRecipient());
        $copyObj->setDate($this->getDate());
        $copyObj->setInsert($this->getInsert());
        $copyObj->setUpdate($this->getUpdate());
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
     * @return \Model\Cart Clone of current object.
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
            $v->addCart($this);
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
                $this->aSite->addCarts($this);
             */
        }

        return $this->aSite;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param ChildUser|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addCart($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildUser|null The associated ChildUser object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getUser(?ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->user_id != 0)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addCarts($this);
             */
        }

        return $this->aUser;
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
            $this->aSite->removeCart($this);
        }
        if (null !== $this->aUser) {
            $this->aUser->removeCart($this);
        }
        $this->cart_id = null;
        $this->cart_uid = null;
        $this->site_id = null;
        $this->user_id = null;
        $this->cart_seller_id = null;
        $this->customer_id = null;
        $this->cart_title = null;
        $this->cart_type = null;
        $this->cart_ip = null;
        $this->cart_count = null;
        $this->cart_amount = null;
        $this->cart_as_a_gift = null;
        $this->cart_gift_recipient = null;
        $this->cart_date = null;
        $this->cart_insert = null;
        $this->cart_update = null;
        $this->cart_created = null;
        $this->cart_updated = null;
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
        $this->aUser = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CartTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[CartTableMap::COL_CART_UPDATED] = true;

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
