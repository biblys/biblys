<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Article as ChildArticle;
use Model\ArticleQuery as ChildArticleQuery;
use Model\People as ChildPeople;
use Model\PeopleQuery as ChildPeopleQuery;
use Model\RoleQuery as ChildRoleQuery;
use Model\Map\RoleTableMap;
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
 * Base class that represents a row from the 'roles' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Role implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\RoleTableMap';


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
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the article_id field.
     *
     * @var        int|null
     */
    protected $article_id;

    /**
     * The value for the book_id field.
     *
     * @var        int|null
     */
    protected $book_id;

    /**
     * The value for the event_id field.
     *
     * @var        int|null
     */
    protected $event_id;

    /**
     * The value for the people_id field.
     *
     * @var        int|null
     */
    protected $people_id;

    /**
     * The value for the job_id field.
     *
     * @var        int|null
     */
    protected $job_id;

    /**
     * The value for the axys_account_id field.
     *
     * @var        int|null
     */
    protected $axys_account_id;

    /**
     * The value for the role_hide field.
     *
     * @var        boolean|null
     */
    protected $role_hide;

    /**
     * The value for the role_presence field.
     *
     * @var        string|null
     */
    protected $role_presence;

    /**
     * The value for the role_date field.
     *
     * @var        DateTime|null
     */
    protected $role_date;

    /**
     * The value for the role_created field.
     *
     * @var        DateTime|null
     */
    protected $role_created;

    /**
     * The value for the role_updated field.
     *
     * @var        DateTime|null
     */
    protected $role_updated;

    /**
     * @var        ChildArticle
     */
    protected $aArticle;

    /**
     * @var        ChildPeople
     */
    protected $aPeople;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of Model\Base\Role object.
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
     * Compares this with another <code>Role</code> instance.  If
     * <code>obj</code> is an instance of <code>Role</code>, delegates to
     * <code>equals(Role)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * Get the [book_id] column value.
     *
     * @return int|null
     */
    public function getBookId()
    {
        return $this->book_id;
    }

    /**
     * Get the [event_id] column value.
     *
     * @return int|null
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Get the [people_id] column value.
     *
     * @return int|null
     */
    public function getPeopleId()
    {
        return $this->people_id;
    }

    /**
     * Get the [job_id] column value.
     *
     * @return int|null
     */
    public function getJobId()
    {
        return $this->job_id;
    }

    /**
     * Get the [axys_account_id] column value.
     *
     * @return int|null
     */
    public function getAxysAccountId()
    {
        return $this->axys_account_id;
    }

    /**
     * Get the [role_hide] column value.
     *
     * @return boolean|null
     */
    public function getHide()
    {
        return $this->role_hide;
    }

    /**
     * Get the [role_hide] column value.
     *
     * @return boolean|null
     */
    public function isHide()
    {
        return $this->getHide();
    }

    /**
     * Get the [role_presence] column value.
     *
     * @return string|null
     */
    public function getPresence()
    {
        return $this->role_presence;
    }

    /**
     * Get the [optionally formatted] temporal [role_date] column value.
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
            return $this->role_date;
        } else {
            return $this->role_date instanceof \DateTimeInterface ? $this->role_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [role_created] column value.
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
            return $this->role_created;
        } else {
            return $this->role_created instanceof \DateTimeInterface ? $this->role_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [role_updated] column value.
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
            return $this->role_updated;
        } else {
            return $this->role_updated instanceof \DateTimeInterface ? $this->role_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[RoleTableMap::COL_ID] = true;
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
            $this->modifiedColumns[RoleTableMap::COL_ARTICLE_ID] = true;
        }

        if ($this->aArticle !== null && $this->aArticle->getId() !== $v) {
            $this->aArticle = null;
        }

        return $this;
    }

    /**
     * Set the value of [book_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setBookId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->book_id !== $v) {
            $this->book_id = $v;
            $this->modifiedColumns[RoleTableMap::COL_BOOK_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [event_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEventId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_id !== $v) {
            $this->event_id = $v;
            $this->modifiedColumns[RoleTableMap::COL_EVENT_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPeopleId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->people_id !== $v) {
            $this->people_id = $v;
            $this->modifiedColumns[RoleTableMap::COL_PEOPLE_ID] = true;
        }

        if ($this->aPeople !== null && $this->aPeople->getId() !== $v) {
            $this->aPeople = null;
        }

        return $this;
    }

    /**
     * Set the value of [job_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setJobId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->job_id !== $v) {
            $this->job_id = $v;
            $this->modifiedColumns[RoleTableMap::COL_JOB_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [axys_account_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAxysAccountId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->axys_account_id !== $v) {
            $this->axys_account_id = $v;
            $this->modifiedColumns[RoleTableMap::COL_AXYS_ACCOUNT_ID] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [role_hide] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setHide($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->role_hide !== $v) {
            $this->role_hide = $v;
            $this->modifiedColumns[RoleTableMap::COL_ROLE_HIDE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [role_presence] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPresence($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->role_presence !== $v) {
            $this->role_presence = $v;
            $this->modifiedColumns[RoleTableMap::COL_ROLE_PRESENCE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [role_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->role_date !== null || $dt !== null) {
            if ($this->role_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->role_date->format("Y-m-d H:i:s.u")) {
                $this->role_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[RoleTableMap::COL_ROLE_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [role_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->role_created !== null || $dt !== null) {
            if ($this->role_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->role_created->format("Y-m-d H:i:s.u")) {
                $this->role_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[RoleTableMap::COL_ROLE_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [role_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->role_updated !== null || $dt !== null) {
            if ($this->role_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->role_updated->format("Y-m-d H:i:s.u")) {
                $this->role_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[RoleTableMap::COL_ROLE_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : RoleTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : RoleTableMap::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : RoleTableMap::translateFieldName('BookId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->book_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : RoleTableMap::translateFieldName('EventId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : RoleTableMap::translateFieldName('PeopleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : RoleTableMap::translateFieldName('JobId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->job_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : RoleTableMap::translateFieldName('AxysAccountId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : RoleTableMap::translateFieldName('Hide', TableMap::TYPE_PHPNAME, $indexType)];
            $this->role_hide = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : RoleTableMap::translateFieldName('Presence', TableMap::TYPE_PHPNAME, $indexType)];
            $this->role_presence = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : RoleTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->role_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : RoleTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->role_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : RoleTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->role_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = RoleTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Role'), 0, $e);
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
        if ($this->aArticle !== null && $this->article_id !== $this->aArticle->getId()) {
            $this->aArticle = null;
        }
        if ($this->aPeople !== null && $this->people_id !== $this->aPeople->getId()) {
            $this->aPeople = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(RoleTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildRoleQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aArticle = null;
            $this->aPeople = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Role::setDeleted()
     * @see Role::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildRoleQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(RoleTableMap::COL_ROLE_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(RoleTableMap::COL_ROLE_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(RoleTableMap::COL_ROLE_UPDATED)) {
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
                RoleTableMap::addInstanceToPool($this);
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

            if ($this->aArticle !== null) {
                if ($this->aArticle->isModified() || $this->aArticle->isNew()) {
                    $affectedRows += $this->aArticle->save($con);
                }
                $this->setArticle($this->aArticle);
            }

            if ($this->aPeople !== null) {
                if ($this->aPeople->isModified() || $this->aPeople->isNew()) {
                    $affectedRows += $this->aPeople->save($con);
                }
                $this->setPeople($this->aPeople);
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

        $this->modifiedColumns[RoleTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . RoleTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(RoleTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(RoleTableMap::COL_ARTICLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'article_id';
        }
        if ($this->isColumnModified(RoleTableMap::COL_BOOK_ID)) {
            $modifiedColumns[':p' . $index++]  = 'book_id';
        }
        if ($this->isColumnModified(RoleTableMap::COL_EVENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'event_id';
        }
        if ($this->isColumnModified(RoleTableMap::COL_PEOPLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'people_id';
        }
        if ($this->isColumnModified(RoleTableMap::COL_JOB_ID)) {
            $modifiedColumns[':p' . $index++]  = 'job_id';
        }
        if ($this->isColumnModified(RoleTableMap::COL_AXYS_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_id';
        }
        if ($this->isColumnModified(RoleTableMap::COL_ROLE_HIDE)) {
            $modifiedColumns[':p' . $index++]  = 'role_hide';
        }
        if ($this->isColumnModified(RoleTableMap::COL_ROLE_PRESENCE)) {
            $modifiedColumns[':p' . $index++]  = 'role_presence';
        }
        if ($this->isColumnModified(RoleTableMap::COL_ROLE_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'role_date';
        }
        if ($this->isColumnModified(RoleTableMap::COL_ROLE_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'role_created';
        }
        if ($this->isColumnModified(RoleTableMap::COL_ROLE_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'role_updated';
        }

        $sql = sprintf(
            'INSERT INTO roles (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);

                        break;
                    case 'article_id':
                        $stmt->bindValue($identifier, $this->article_id, PDO::PARAM_INT);

                        break;
                    case 'book_id':
                        $stmt->bindValue($identifier, $this->book_id, PDO::PARAM_INT);

                        break;
                    case 'event_id':
                        $stmt->bindValue($identifier, $this->event_id, PDO::PARAM_INT);

                        break;
                    case 'people_id':
                        $stmt->bindValue($identifier, $this->people_id, PDO::PARAM_INT);

                        break;
                    case 'job_id':
                        $stmt->bindValue($identifier, $this->job_id, PDO::PARAM_INT);

                        break;
                    case 'axys_account_id':
                        $stmt->bindValue($identifier, $this->axys_account_id, PDO::PARAM_INT);

                        break;
                    case 'role_hide':
                        $stmt->bindValue($identifier, (int) $this->role_hide, PDO::PARAM_INT);

                        break;
                    case 'role_presence':
                        $stmt->bindValue($identifier, $this->role_presence, PDO::PARAM_STR);

                        break;
                    case 'role_date':
                        $stmt->bindValue($identifier, $this->role_date ? $this->role_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'role_created':
                        $stmt->bindValue($identifier, $this->role_created ? $this->role_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'role_updated':
                        $stmt->bindValue($identifier, $this->role_updated ? $this->role_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = RoleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getArticleId();

            case 2:
                return $this->getBookId();

            case 3:
                return $this->getEventId();

            case 4:
                return $this->getPeopleId();

            case 5:
                return $this->getJobId();

            case 6:
                return $this->getAxysAccountId();

            case 7:
                return $this->getHide();

            case 8:
                return $this->getPresence();

            case 9:
                return $this->getDate();

            case 10:
                return $this->getCreatedAt();

            case 11:
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
        if (isset($alreadyDumpedObjects['Role'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Role'][$this->hashCode()] = true;
        $keys = RoleTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getArticleId(),
            $keys[2] => $this->getBookId(),
            $keys[3] => $this->getEventId(),
            $keys[4] => $this->getPeopleId(),
            $keys[5] => $this->getJobId(),
            $keys[6] => $this->getAxysAccountId(),
            $keys[7] => $this->getHide(),
            $keys[8] => $this->getPresence(),
            $keys[9] => $this->getDate(),
            $keys[10] => $this->getCreatedAt(),
            $keys[11] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[9]] instanceof \DateTimeInterface) {
            $result[$keys[9]] = $result[$keys[9]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[10]] instanceof \DateTimeInterface) {
            $result[$keys[10]] = $result[$keys[10]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[11]] instanceof \DateTimeInterface) {
            $result[$keys[11]] = $result[$keys[11]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
            if (null !== $this->aPeople) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'people';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'people';
                        break;
                    default:
                        $key = 'People';
                }

                $result[$key] = $this->aPeople->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = RoleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setArticleId($value);
                break;
            case 2:
                $this->setBookId($value);
                break;
            case 3:
                $this->setEventId($value);
                break;
            case 4:
                $this->setPeopleId($value);
                break;
            case 5:
                $this->setJobId($value);
                break;
            case 6:
                $this->setAxysAccountId($value);
                break;
            case 7:
                $this->setHide($value);
                break;
            case 8:
                $this->setPresence($value);
                break;
            case 9:
                $this->setDate($value);
                break;
            case 10:
                $this->setCreatedAt($value);
                break;
            case 11:
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
        $keys = RoleTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setArticleId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setBookId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setEventId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPeopleId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setJobId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setAxysAccountId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setHide($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setPresence($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setDate($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setCreatedAt($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setUpdatedAt($arr[$keys[11]]);
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
        $criteria = new Criteria(RoleTableMap::DATABASE_NAME);

        if ($this->isColumnModified(RoleTableMap::COL_ID)) {
            $criteria->add(RoleTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(RoleTableMap::COL_ARTICLE_ID)) {
            $criteria->add(RoleTableMap::COL_ARTICLE_ID, $this->article_id);
        }
        if ($this->isColumnModified(RoleTableMap::COL_BOOK_ID)) {
            $criteria->add(RoleTableMap::COL_BOOK_ID, $this->book_id);
        }
        if ($this->isColumnModified(RoleTableMap::COL_EVENT_ID)) {
            $criteria->add(RoleTableMap::COL_EVENT_ID, $this->event_id);
        }
        if ($this->isColumnModified(RoleTableMap::COL_PEOPLE_ID)) {
            $criteria->add(RoleTableMap::COL_PEOPLE_ID, $this->people_id);
        }
        if ($this->isColumnModified(RoleTableMap::COL_JOB_ID)) {
            $criteria->add(RoleTableMap::COL_JOB_ID, $this->job_id);
        }
        if ($this->isColumnModified(RoleTableMap::COL_AXYS_ACCOUNT_ID)) {
            $criteria->add(RoleTableMap::COL_AXYS_ACCOUNT_ID, $this->axys_account_id);
        }
        if ($this->isColumnModified(RoleTableMap::COL_ROLE_HIDE)) {
            $criteria->add(RoleTableMap::COL_ROLE_HIDE, $this->role_hide);
        }
        if ($this->isColumnModified(RoleTableMap::COL_ROLE_PRESENCE)) {
            $criteria->add(RoleTableMap::COL_ROLE_PRESENCE, $this->role_presence);
        }
        if ($this->isColumnModified(RoleTableMap::COL_ROLE_DATE)) {
            $criteria->add(RoleTableMap::COL_ROLE_DATE, $this->role_date);
        }
        if ($this->isColumnModified(RoleTableMap::COL_ROLE_CREATED)) {
            $criteria->add(RoleTableMap::COL_ROLE_CREATED, $this->role_created);
        }
        if ($this->isColumnModified(RoleTableMap::COL_ROLE_UPDATED)) {
            $criteria->add(RoleTableMap::COL_ROLE_UPDATED, $this->role_updated);
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
        $criteria = ChildRoleQuery::create();
        $criteria->add(RoleTableMap::COL_ID, $this->id);

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
     * Generic method to set the primary key (id column).
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
     * @param object $copyObj An object of \Model\Role (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setArticleId($this->getArticleId());
        $copyObj->setBookId($this->getBookId());
        $copyObj->setEventId($this->getEventId());
        $copyObj->setPeopleId($this->getPeopleId());
        $copyObj->setJobId($this->getJobId());
        $copyObj->setAxysAccountId($this->getAxysAccountId());
        $copyObj->setHide($this->getHide());
        $copyObj->setPresence($this->getPresence());
        $copyObj->setDate($this->getDate());
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
     * @return \Model\Role Clone of current object.
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
            $v->addRole($this);
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
                $this->aArticle->addRoles($this);
             */
        }

        return $this->aArticle;
    }

    /**
     * Declares an association between this object and a ChildPeople object.
     *
     * @param ChildPeople|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setPeople(ChildPeople $v = null)
    {
        if ($v === null) {
            $this->setPeopleId(NULL);
        } else {
            $this->setPeopleId($v->getId());
        }

        $this->aPeople = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPeople object, it will not be re-added.
        if ($v !== null) {
            $v->addRole($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPeople object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildPeople|null The associated ChildPeople object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getPeople(?ConnectionInterface $con = null)
    {
        if ($this->aPeople === null && ($this->people_id != 0)) {
            $this->aPeople = ChildPeopleQuery::create()->findPk($this->people_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPeople->addRoles($this);
             */
        }

        return $this->aPeople;
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
        if (null !== $this->aArticle) {
            $this->aArticle->removeRole($this);
        }
        if (null !== $this->aPeople) {
            $this->aPeople->removeRole($this);
        }
        $this->id = null;
        $this->article_id = null;
        $this->book_id = null;
        $this->event_id = null;
        $this->people_id = null;
        $this->job_id = null;
        $this->axys_account_id = null;
        $this->role_hide = null;
        $this->role_presence = null;
        $this->role_date = null;
        $this->role_created = null;
        $this->role_updated = null;
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
        } // if ($deep)

        $this->aArticle = null;
        $this->aPeople = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(RoleTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[RoleTableMap::COL_ROLE_UPDATED] = true;

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
