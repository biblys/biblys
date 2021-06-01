<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Right as ChildRight;
use Model\RightQuery as ChildRightQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\RightTableMap;
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
 * Base class that represents a row from the 'rights' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Right implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Map\\RightTableMap';


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
     * The value for the right_id field.
     *
     * @var        int
     */
    protected $right_id;

    /**
     * The value for the right_uid field.
     *
     * @var        string|null
     */
    protected $right_uid;

    /**
     * The value for the user_id field.
     *
     * @var        int|null
     */
    protected $user_id;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the publisher_id field.
     *
     * @var        int|null
     */
    protected $publisher_id;

    /**
     * The value for the bookshop_id field.
     *
     * @var        int|null
     */
    protected $bookshop_id;

    /**
     * The value for the library_id field.
     *
     * @var        int|null
     */
    protected $library_id;

    /**
     * The value for the right_current field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $right_current;

    /**
     * The value for the right_created field.
     *
     * @var        DateTime|null
     */
    protected $right_created;

    /**
     * The value for the right_updated field.
     *
     * @var        DateTime|null
     */
    protected $right_updated;

    /**
     * The value for the right_deleted field.
     *
     * @var        DateTime|null
     */
    protected $right_deleted;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ChildSite
     */
    protected $aSite;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->right_current = false;
    }

    /**
     * Initializes internal state of Model\Base\Right object.
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
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Right</code> instance.  If
     * <code>obj</code> is an instance of <code>Right</code>, delegates to
     * <code>equals(Right)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [right_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->right_id;
    }

    /**
     * Get the [right_uid] column value.
     *
     * @return string|null
     */
    public function getUid()
    {
        return $this->right_uid;
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
     * Get the [site_id] column value.
     *
     * @return int|null
     */
    public function getSiteId()
    {
        return $this->site_id;
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
     * Get the [bookshop_id] column value.
     *
     * @return int|null
     */
    public function getBookshopId()
    {
        return $this->bookshop_id;
    }

    /**
     * Get the [library_id] column value.
     *
     * @return int|null
     */
    public function getLibraryId()
    {
        return $this->library_id;
    }

    /**
     * Get the [right_current] column value.
     *
     * @return boolean|null
     */
    public function getCurrent()
    {
        return $this->right_current;
    }

    /**
     * Get the [right_current] column value.
     *
     * @return boolean|null
     */
    public function isCurrent()
    {
        return $this->getCurrent();
    }

    /**
     * Get the [optionally formatted] temporal [right_created] column value.
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
            return $this->right_created;
        } else {
            return $this->right_created instanceof \DateTimeInterface ? $this->right_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [right_updated] column value.
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
            return $this->right_updated;
        } else {
            return $this->right_updated instanceof \DateTimeInterface ? $this->right_updated->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [right_deleted] column value.
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
            return $this->right_deleted;
        } else {
            return $this->right_deleted instanceof \DateTimeInterface ? $this->right_deleted->format($format) : null;
        }
    }

    /**
     * Set the value of [right_id] column.
     *
     * @param int $v New value
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->right_id !== $v) {
            $this->right_id = $v;
            $this->modifiedColumns[RightTableMap::COL_RIGHT_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [right_uid] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setUid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->right_uid !== $v) {
            $this->right_uid = $v;
            $this->modifiedColumns[RightTableMap::COL_RIGHT_UID] = true;
        }

        return $this;
    } // setUid()

    /**
     * Set the value of [user_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[RightTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setUserId()

    /**
     * Set the value of [site_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setSiteId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_id !== $v) {
            $this->site_id = $v;
            $this->modifiedColumns[RightTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    } // setSiteId()

    /**
     * Set the value of [publisher_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setPublisherId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_id !== $v) {
            $this->publisher_id = $v;
            $this->modifiedColumns[RightTableMap::COL_PUBLISHER_ID] = true;
        }

        return $this;
    } // setPublisherId()

    /**
     * Set the value of [bookshop_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setBookshopId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->bookshop_id !== $v) {
            $this->bookshop_id = $v;
            $this->modifiedColumns[RightTableMap::COL_BOOKSHOP_ID] = true;
        }

        return $this;
    } // setBookshopId()

    /**
     * Set the value of [library_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setLibraryId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->library_id !== $v) {
            $this->library_id = $v;
            $this->modifiedColumns[RightTableMap::COL_LIBRARY_ID] = true;
        }

        return $this;
    } // setLibraryId()

    /**
     * Sets the value of the [right_current] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setCurrent($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->right_current !== $v) {
            $this->right_current = $v;
            $this->modifiedColumns[RightTableMap::COL_RIGHT_CURRENT] = true;
        }

        return $this;
    } // setCurrent()

    /**
     * Sets the value of [right_created] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->right_created !== null || $dt !== null) {
            if ($this->right_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->right_created->format("Y-m-d H:i:s.u")) {
                $this->right_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[RightTableMap::COL_RIGHT_CREATED] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [right_updated] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->right_updated !== null || $dt !== null) {
            if ($this->right_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->right_updated->format("Y-m-d H:i:s.u")) {
                $this->right_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[RightTableMap::COL_RIGHT_UPDATED] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Sets the value of [right_deleted] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Right The current object (for fluent API support)
     */
    public function setDeletedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->right_deleted !== null || $dt !== null) {
            if ($this->right_deleted === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->right_deleted->format("Y-m-d H:i:s.u")) {
                $this->right_deleted = $dt === null ? null : clone $dt;
                $this->modifiedColumns[RightTableMap::COL_RIGHT_DELETED] = true;
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
            if ($this->right_current !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : RightTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->right_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : RightTableMap::translateFieldName('Uid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->right_uid = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : RightTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : RightTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : RightTableMap::translateFieldName('PublisherId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : RightTableMap::translateFieldName('BookshopId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : RightTableMap::translateFieldName('LibraryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : RightTableMap::translateFieldName('Current', TableMap::TYPE_PHPNAME, $indexType)];
            $this->right_current = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : RightTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->right_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : RightTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->right_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : RightTableMap::translateFieldName('DeletedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->right_deleted = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 11; // 11 = RightTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Right'), 0, $e);
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
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aSite !== null && $this->site_id !== $this->aSite->getId()) {
            $this->aSite = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(RightTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildRightQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aSite = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Right::setDeleted()
     * @see Right::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(RightTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildRightQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(RightTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(RightTableMap::COL_RIGHT_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(RightTableMap::COL_RIGHT_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(RightTableMap::COL_RIGHT_UPDATED)) {
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
                RightTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->aSite !== null) {
                if ($this->aSite->isModified() || $this->aSite->isNew()) {
                    $affectedRows += $this->aSite->save($con);
                }
                $this->setSite($this->aSite);
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

        $this->modifiedColumns[RightTableMap::COL_RIGHT_ID] = true;
        if (null !== $this->right_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . RightTableMap::COL_RIGHT_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'right_id';
        }
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_UID)) {
            $modifiedColumns[':p' . $index++]  = 'right_uid';
        }
        if ($this->isColumnModified(RightTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_id';
        }
        if ($this->isColumnModified(RightTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(RightTableMap::COL_PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_id';
        }
        if ($this->isColumnModified(RightTableMap::COL_BOOKSHOP_ID)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_id';
        }
        if ($this->isColumnModified(RightTableMap::COL_LIBRARY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'library_id';
        }
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_CURRENT)) {
            $modifiedColumns[':p' . $index++]  = 'right_current';
        }
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'right_created';
        }
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'right_updated';
        }
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_DELETED)) {
            $modifiedColumns[':p' . $index++]  = 'right_deleted';
        }

        $sql = sprintf(
            'INSERT INTO rights (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'right_id':
                        $stmt->bindValue($identifier, $this->right_id, PDO::PARAM_INT);
                        break;
                    case 'right_uid':
                        $stmt->bindValue($identifier, $this->right_uid, PDO::PARAM_STR);
                        break;
                    case 'user_id':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);
                        break;
                    case 'publisher_id':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);
                        break;
                    case 'bookshop_id':
                        $stmt->bindValue($identifier, $this->bookshop_id, PDO::PARAM_INT);
                        break;
                    case 'library_id':
                        $stmt->bindValue($identifier, $this->library_id, PDO::PARAM_INT);
                        break;
                    case 'right_current':
                        $stmt->bindValue($identifier, (int) $this->right_current, PDO::PARAM_INT);
                        break;
                    case 'right_created':
                        $stmt->bindValue($identifier, $this->right_created ? $this->right_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'right_updated':
                        $stmt->bindValue($identifier, $this->right_updated ? $this->right_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'right_deleted':
                        $stmt->bindValue($identifier, $this->right_deleted ? $this->right_deleted->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $pos = RightTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getUid();
                break;
            case 2:
                return $this->getUserId();
                break;
            case 3:
                return $this->getSiteId();
                break;
            case 4:
                return $this->getPublisherId();
                break;
            case 5:
                return $this->getBookshopId();
                break;
            case 6:
                return $this->getLibraryId();
                break;
            case 7:
                return $this->getCurrent();
                break;
            case 8:
                return $this->getCreatedAt();
                break;
            case 9:
                return $this->getUpdatedAt();
                break;
            case 10:
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
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Right'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Right'][$this->hashCode()] = true;
        $keys = RightTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUid(),
            $keys[2] => $this->getUserId(),
            $keys[3] => $this->getSiteId(),
            $keys[4] => $this->getPublisherId(),
            $keys[5] => $this->getBookshopId(),
            $keys[6] => $this->getLibraryId(),
            $keys[7] => $this->getCurrent(),
            $keys[8] => $this->getCreatedAt(),
            $keys[9] => $this->getUpdatedAt(),
            $keys[10] => $this->getDeletedAt(),
        );
        if ($result[$keys[8]] instanceof \DateTimeInterface) {
            $result[$keys[8]] = $result[$keys[8]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[9]] instanceof \DateTimeInterface) {
            $result[$keys[9]] = $result[$keys[9]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[10]] instanceof \DateTimeInterface) {
            $result[$keys[10]] = $result[$keys[10]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
     * @return $this|\Model\Right
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = RightTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Right
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUid($value);
                break;
            case 2:
                $this->setUserId($value);
                break;
            case 3:
                $this->setSiteId($value);
                break;
            case 4:
                $this->setPublisherId($value);
                break;
            case 5:
                $this->setBookshopId($value);
                break;
            case 6:
                $this->setLibraryId($value);
                break;
            case 7:
                $this->setCurrent($value);
                break;
            case 8:
                $this->setCreatedAt($value);
                break;
            case 9:
                $this->setUpdatedAt($value);
                break;
            case 10:
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
        $keys = RightTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUserId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSiteId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPublisherId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setBookshopId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setLibraryId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCurrent($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setCreatedAt($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setUpdatedAt($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setDeletedAt($arr[$keys[10]]);
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
     * @return $this|\Model\Right The current object, for fluid interface
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
        $criteria = new Criteria(RightTableMap::DATABASE_NAME);

        if ($this->isColumnModified(RightTableMap::COL_RIGHT_ID)) {
            $criteria->add(RightTableMap::COL_RIGHT_ID, $this->right_id);
        }
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_UID)) {
            $criteria->add(RightTableMap::COL_RIGHT_UID, $this->right_uid);
        }
        if ($this->isColumnModified(RightTableMap::COL_USER_ID)) {
            $criteria->add(RightTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(RightTableMap::COL_SITE_ID)) {
            $criteria->add(RightTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(RightTableMap::COL_PUBLISHER_ID)) {
            $criteria->add(RightTableMap::COL_PUBLISHER_ID, $this->publisher_id);
        }
        if ($this->isColumnModified(RightTableMap::COL_BOOKSHOP_ID)) {
            $criteria->add(RightTableMap::COL_BOOKSHOP_ID, $this->bookshop_id);
        }
        if ($this->isColumnModified(RightTableMap::COL_LIBRARY_ID)) {
            $criteria->add(RightTableMap::COL_LIBRARY_ID, $this->library_id);
        }
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_CURRENT)) {
            $criteria->add(RightTableMap::COL_RIGHT_CURRENT, $this->right_current);
        }
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_CREATED)) {
            $criteria->add(RightTableMap::COL_RIGHT_CREATED, $this->right_created);
        }
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_UPDATED)) {
            $criteria->add(RightTableMap::COL_RIGHT_UPDATED, $this->right_updated);
        }
        if ($this->isColumnModified(RightTableMap::COL_RIGHT_DELETED)) {
            $criteria->add(RightTableMap::COL_RIGHT_DELETED, $this->right_deleted);
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
        $criteria = ChildRightQuery::create();
        $criteria->add(RightTableMap::COL_RIGHT_ID, $this->right_id);

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
     * Generic method to set the primary key (right_id column).
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
     * @param      object $copyObj An object of \Model\Right (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUid($this->getUid());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setBookshopId($this->getBookshopId());
        $copyObj->setLibraryId($this->getLibraryId());
        $copyObj->setCurrent($this->getCurrent());
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
     * @return \Model\Right Clone of current object.
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
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser|null $v
     * @return $this|\Model\Right The current object (for fluent API support)
     * @throws PropelException
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
            $v->addRight($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser|null The associated ChildUser object.
     * @throws PropelException
     */
    public function getUser(ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->user_id != 0)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addRights($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a ChildSite object.
     *
     * @param  ChildSite|null $v
     * @return $this|\Model\Right The current object (for fluent API support)
     * @throws PropelException
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
            $v->addRight($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSite object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildSite|null The associated ChildSite object.
     * @throws PropelException
     */
    public function getSite(ConnectionInterface $con = null)
    {
        if ($this->aSite === null && ($this->site_id != 0)) {
            $this->aSite = ChildSiteQuery::create()->findPk($this->site_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSite->addRights($this);
             */
        }

        return $this->aSite;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUser) {
            $this->aUser->removeRight($this);
        }
        if (null !== $this->aSite) {
            $this->aSite->removeRight($this);
        }
        $this->right_id = null;
        $this->right_uid = null;
        $this->user_id = null;
        $this->site_id = null;
        $this->publisher_id = null;
        $this->bookshop_id = null;
        $this->library_id = null;
        $this->right_current = null;
        $this->right_created = null;
        $this->right_updated = null;
        $this->right_deleted = null;
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
        } // if ($deep)

        $this->aUser = null;
        $this->aSite = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(RightTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildRight The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[RightTableMap::COL_RIGHT_UPDATED] = true;

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
