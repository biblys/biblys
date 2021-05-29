<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\ShippingFeeQuery as ChildShippingFeeQuery;
use Model\Map\ShippingFeeTableMap;
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
 * Base class that represents a row from the 'shipping' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class ShippingFee implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Map\\ShippingFeeTableMap';


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
     * The value for the shipping_deleted field.
     *
     * @var        DateTime|null
     */
    protected $shipping_deleted;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of Model\Base\ShippingFee object.
     */
    public function __construct()
    {
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
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>ShippingFee</code> instance.  If
     * <code>obj</code> is an instance of <code>ShippingFee</code>, delegates to
     * <code>equals(ShippingFee)</code>.  Otherwise, returns <code>false</code>.
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
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
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
    public function getZone()
    {
        return $this->shipping_zone;
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * Get the [optionally formatted] temporal [shipping_deleted] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDeletedAt($format = null)
    {
        if ($format === null) {
            return $this->shipping_deleted;
        } else {
            return $this->shipping_deleted instanceof \DateTimeInterface ? $this->shipping_deleted->format($format) : null;
        }
    }

    /**
     * Set the value of [shipping_id] column.
     *
     * @param int $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_id !== $v) {
            $this->shipping_id = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [site_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setSiteId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_id !== $v) {
            $this->site_id = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SITE_ID] = true;
        }

        return $this;
    } // setSiteId()

    /**
     * Set the value of [article_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setArticleId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_id !== $v) {
            $this->article_id = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_ARTICLE_ID] = true;
        }

        return $this;
    } // setArticleId()

    /**
     * Set the value of [shipping_mode] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setMode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->shipping_mode !== $v) {
            $this->shipping_mode = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_MODE] = true;
        }

        return $this;
    } // setMode()

    /**
     * Set the value of [shipping_type] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->shipping_type !== $v) {
            $this->shipping_type = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_TYPE] = true;
        }

        return $this;
    } // setType()

    /**
     * Set the value of [shipping_zone] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setZone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->shipping_zone !== $v) {
            $this->shipping_zone = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_ZONE] = true;
        }

        return $this;
    } // setZone()

    /**
     * Set the value of [shipping_min_weight] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setMinWeight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_min_weight !== $v) {
            $this->shipping_min_weight = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT] = true;
        }

        return $this;
    } // setMinWeight()

    /**
     * Set the value of [shipping_max_weight] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setMaxWeight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_max_weight !== $v) {
            $this->shipping_max_weight = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT] = true;
        }

        return $this;
    } // setMaxWeight()

    /**
     * Set the value of [shipping_max_articles] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setMaxArticles($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_max_articles !== $v) {
            $this->shipping_max_articles = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES] = true;
        }

        return $this;
    } // setMaxArticles()

    /**
     * Set the value of [shipping_min_amount] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setMinAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_min_amount !== $v) {
            $this->shipping_min_amount = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT] = true;
        }

        return $this;
    } // setMinAmount()

    /**
     * Set the value of [shipping_max_amount] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setMaxAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_max_amount !== $v) {
            $this->shipping_max_amount = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT] = true;
        }

        return $this;
    } // setMaxAmount()

    /**
     * Set the value of [shipping_fee] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setFee($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_fee !== $v) {
            $this->shipping_fee = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_FEE] = true;
        }

        return $this;
    } // setFee()

    /**
     * Set the value of [shipping_info] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setInfo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->shipping_info !== $v) {
            $this->shipping_info = $v;
            $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_INFO] = true;
        }

        return $this;
    } // setInfo()

    /**
     * Sets the value of [shipping_created] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->shipping_created !== null || $dt !== null) {
            if ($this->shipping_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->shipping_created->format("Y-m-d H:i:s.u")) {
                $this->shipping_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_CREATED] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [shipping_updated] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->shipping_updated !== null || $dt !== null) {
            if ($this->shipping_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->shipping_updated->format("Y-m-d H:i:s.u")) {
                $this->shipping_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_UPDATED] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Sets the value of [shipping_deleted] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\ShippingFee The current object (for fluent API support)
     */
    public function setDeletedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->shipping_deleted !== null || $dt !== null) {
            if ($this->shipping_deleted === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->shipping_deleted->format("Y-m-d H:i:s.u")) {
                $this->shipping_deleted = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_DELETED] = true;
            }
        } // if either are not null

        return $this;
    } // setDeletedAt()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ShippingFeeTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ShippingFeeTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ShippingFeeTableMap::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ShippingFeeTableMap::translateFieldName('Mode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_mode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ShippingFeeTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ShippingFeeTableMap::translateFieldName('Zone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_zone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ShippingFeeTableMap::translateFieldName('MinWeight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_min_weight = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : ShippingFeeTableMap::translateFieldName('MaxWeight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_max_weight = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : ShippingFeeTableMap::translateFieldName('MaxArticles', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_max_articles = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : ShippingFeeTableMap::translateFieldName('MinAmount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_min_amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : ShippingFeeTableMap::translateFieldName('MaxAmount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_max_amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : ShippingFeeTableMap::translateFieldName('Fee', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_fee = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : ShippingFeeTableMap::translateFieldName('Info', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_info = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : ShippingFeeTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->shipping_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : ShippingFeeTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->shipping_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : ShippingFeeTableMap::translateFieldName('DeletedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->shipping_deleted = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 16; // 16 = ShippingFeeTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\ShippingFee'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(ShippingFeeTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildShippingFeeQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see ShippingFee::setDeleted()
     * @see ShippingFee::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingFeeTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildShippingFeeQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingFeeTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ShippingFeeTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[ShippingFeeTableMap::COL_SHIPPING_ID] = true;
        if (null !== $this->shipping_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ShippingFeeTableMap::COL_SHIPPING_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_ID)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_id';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_ARTICLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'article_id';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MODE)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_mode';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_type';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_ZONE)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_zone';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_min_weight';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_max_weight';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_max_articles';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_min_amount';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_max_amount';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_FEE)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_fee';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_INFO)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_info';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_created';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_updated';
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_DELETED)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_deleted';
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
                    case 'shipping_deleted':
                        $stmt->bindValue($identifier, $this->shipping_deleted ? $this->shipping_deleted->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $pos = ShippingFeeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getSiteId();
                break;
            case 2:
                return $this->getArticleId();
                break;
            case 3:
                return $this->getMode();
                break;
            case 4:
                return $this->getType();
                break;
            case 5:
                return $this->getZone();
                break;
            case 6:
                return $this->getMinWeight();
                break;
            case 7:
                return $this->getMaxWeight();
                break;
            case 8:
                return $this->getMaxArticles();
                break;
            case 9:
                return $this->getMinAmount();
                break;
            case 10:
                return $this->getMaxAmount();
                break;
            case 11:
                return $this->getFee();
                break;
            case 12:
                return $this->getInfo();
                break;
            case 13:
                return $this->getCreatedAt();
                break;
            case 14:
                return $this->getUpdatedAt();
                break;
            case 15:
                return $this->getDeletedAt();
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
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {

        if (isset($alreadyDumpedObjects['ShippingFee'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ShippingFee'][$this->hashCode()] = true;
        $keys = ShippingFeeTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getArticleId(),
            $keys[3] => $this->getMode(),
            $keys[4] => $this->getType(),
            $keys[5] => $this->getZone(),
            $keys[6] => $this->getMinWeight(),
            $keys[7] => $this->getMaxWeight(),
            $keys[8] => $this->getMaxArticles(),
            $keys[9] => $this->getMinAmount(),
            $keys[10] => $this->getMaxAmount(),
            $keys[11] => $this->getFee(),
            $keys[12] => $this->getInfo(),
            $keys[13] => $this->getCreatedAt(),
            $keys[14] => $this->getUpdatedAt(),
            $keys[15] => $this->getDeletedAt(),
        );
        if ($result[$keys[13]] instanceof \DateTimeInterface) {
            $result[$keys[13]] = $result[$keys[13]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[14]] instanceof \DateTimeInterface) {
            $result[$keys[14]] = $result[$keys[14]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[15]] instanceof \DateTimeInterface) {
            $result[$keys[15]] = $result[$keys[15]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
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
     * @return $this|\Model\ShippingFee
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ShippingFeeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\ShippingFee
     */
    public function setByPosition($pos, $value)
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
                $this->setZone($value);
                break;
            case 6:
                $this->setMinWeight($value);
                break;
            case 7:
                $this->setMaxWeight($value);
                break;
            case 8:
                $this->setMaxArticles($value);
                break;
            case 9:
                $this->setMinAmount($value);
                break;
            case 10:
                $this->setMaxAmount($value);
                break;
            case 11:
                $this->setFee($value);
                break;
            case 12:
                $this->setInfo($value);
                break;
            case 13:
                $this->setCreatedAt($value);
                break;
            case 14:
                $this->setUpdatedAt($value);
                break;
            case 15:
                $this->setDeletedAt($value);
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
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = ShippingFeeTableMap::getFieldNames($keyType);

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
            $this->setZone($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setMinWeight($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setMaxWeight($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setMaxArticles($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setMinAmount($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setMaxAmount($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setFee($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setInfo($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setCreatedAt($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setUpdatedAt($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setDeletedAt($arr[$keys[15]]);
        }
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
     * @return $this|\Model\ShippingFee The current object, for fluid interface
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
        $criteria = new Criteria(ShippingFeeTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_ID)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_ID, $this->shipping_id);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SITE_ID)) {
            $criteria->add(ShippingFeeTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_ARTICLE_ID)) {
            $criteria->add(ShippingFeeTableMap::COL_ARTICLE_ID, $this->article_id);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MODE)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_MODE, $this->shipping_mode);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_TYPE)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_TYPE, $this->shipping_type);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_ZONE)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_ZONE, $this->shipping_zone);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT, $this->shipping_min_weight);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT, $this->shipping_max_weight);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES, $this->shipping_max_articles);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT, $this->shipping_min_amount);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT, $this->shipping_max_amount);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_FEE)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_FEE, $this->shipping_fee);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_INFO)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_INFO, $this->shipping_info);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_CREATED)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_CREATED, $this->shipping_created);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_UPDATED)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_UPDATED, $this->shipping_updated);
        }
        if ($this->isColumnModified(ShippingFeeTableMap::COL_SHIPPING_DELETED)) {
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_DELETED, $this->shipping_deleted);
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
        $criteria = ChildShippingFeeQuery::create();
        $criteria->add(ShippingFeeTableMap::COL_SHIPPING_ID, $this->shipping_id);

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
     * Generic method to set the primary key (shipping_id column).
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
     * @param      object $copyObj An object of \Model\ShippingFee (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setArticleId($this->getArticleId());
        $copyObj->setMode($this->getMode());
        $copyObj->setType($this->getType());
        $copyObj->setZone($this->getZone());
        $copyObj->setMinWeight($this->getMinWeight());
        $copyObj->setMaxWeight($this->getMaxWeight());
        $copyObj->setMaxArticles($this->getMaxArticles());
        $copyObj->setMinAmount($this->getMinAmount());
        $copyObj->setMaxAmount($this->getMaxAmount());
        $copyObj->setFee($this->getFee());
        $copyObj->setInfo($this->getInfo());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setDeletedAt($this->getDeletedAt());
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
     * @return \Model\ShippingFee Clone of current object.
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->shipping_id = null;
        $this->site_id = null;
        $this->article_id = null;
        $this->shipping_mode = null;
        $this->shipping_type = null;
        $this->shipping_zone = null;
        $this->shipping_min_weight = null;
        $this->shipping_max_weight = null;
        $this->shipping_max_articles = null;
        $this->shipping_min_amount = null;
        $this->shipping_max_amount = null;
        $this->shipping_fee = null;
        $this->shipping_info = null;
        $this->shipping_created = null;
        $this->shipping_updated = null;
        $this->shipping_deleted = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
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
        } // if ($deep)

    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ShippingFeeTableMap::DEFAULT_STRING_FORMAT);
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

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
