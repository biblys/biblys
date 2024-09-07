<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\MediaFileQuery as ChildMediaFileQuery;
use Model\Map\MediaFileTableMap;
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
 * Base class that represents a row from the 'medias' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class MediaFile implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\MediaFileTableMap';


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
     * The value for the media_id field.
     *
     * @var        int
     */
    protected $media_id;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the category_id field.
     *
     * @var        int|null
     */
    protected $category_id;

    /**
     * The value for the media_dir field.
     *
     * @var        string|null
     */
    protected $media_dir;

    /**
     * The value for the media_file field.
     *
     * @var        string|null
     */
    protected $media_file;

    /**
     * The value for the media_ext field.
     *
     * @var        string|null
     */
    protected $media_ext;

    /**
     * The value for the media_title field.
     *
     * @var        string|null
     */
    protected $media_title;

    /**
     * The value for the media_desc field.
     *
     * @var        string|null
     */
    protected $media_desc;

    /**
     * The value for the media_link field.
     *
     * @var        string|null
     */
    protected $media_link;

    /**
     * The value for the media_headline field.
     *
     * @var        string|null
     */
    protected $media_headline;

    /**
     * The value for the media_insert field.
     *
     * @var        DateTime|null
     */
    protected $media_insert;

    /**
     * The value for the media_update field.
     *
     * @var        DateTime|null
     */
    protected $media_update;

    /**
     * The value for the media_created field.
     *
     * @var        DateTime|null
     */
    protected $media_created;

    /**
     * The value for the media_updated field.
     *
     * @var        DateTime|null
     */
    protected $media_updated;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of Model\Base\MediaFile object.
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
     * Compares this with another <code>MediaFile</code> instance.  If
     * <code>obj</code> is an instance of <code>MediaFile</code>, delegates to
     * <code>equals(MediaFile)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [media_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->media_id;
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
     * Get the [category_id] column value.
     *
     * @return int|null
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Get the [media_dir] column value.
     *
     * @return string|null
     */
    public function getDir()
    {
        return $this->media_dir;
    }

    /**
     * Get the [media_file] column value.
     *
     * @return string|null
     */
    public function getFile()
    {
        return $this->media_file;
    }

    /**
     * Get the [media_ext] column value.
     *
     * @return string|null
     */
    public function getExt()
    {
        return $this->media_ext;
    }

    /**
     * Get the [media_title] column value.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->media_title;
    }

    /**
     * Get the [media_desc] column value.
     *
     * @return string|null
     */
    public function getDesc()
    {
        return $this->media_desc;
    }

    /**
     * Get the [media_link] column value.
     *
     * @return string|null
     */
    public function getLink()
    {
        return $this->media_link;
    }

    /**
     * Get the [media_headline] column value.
     *
     * @return string|null
     */
    public function getHeadline()
    {
        return $this->media_headline;
    }

    /**
     * Get the [optionally formatted] temporal [media_insert] column value.
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
            return $this->media_insert;
        } else {
            return $this->media_insert instanceof \DateTimeInterface ? $this->media_insert->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [media_update] column value.
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
            return $this->media_update;
        } else {
            return $this->media_update instanceof \DateTimeInterface ? $this->media_update->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [media_created] column value.
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
            return $this->media_created;
        } else {
            return $this->media_created instanceof \DateTimeInterface ? $this->media_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [media_updated] column value.
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
            return $this->media_updated;
        } else {
            return $this->media_updated instanceof \DateTimeInterface ? $this->media_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [media_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->media_id !== $v) {
            $this->media_id = $v;
            $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_ID] = true;
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
            $this->modifiedColumns[MediaFileTableMap::COL_SITE_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [category_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCategoryId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->category_id !== $v) {
            $this->category_id = $v;
            $this->modifiedColumns[MediaFileTableMap::COL_CATEGORY_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [media_dir] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDir($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->media_dir !== $v) {
            $this->media_dir = $v;
            $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_DIR] = true;
        }

        return $this;
    }

    /**
     * Set the value of [media_file] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFile($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->media_file !== $v) {
            $this->media_file = $v;
            $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_FILE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [media_ext] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setExt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->media_ext !== $v) {
            $this->media_ext = $v;
            $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_EXT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [media_title] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->media_title !== $v) {
            $this->media_title = $v;
            $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_TITLE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [media_desc] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->media_desc !== $v) {
            $this->media_desc = $v;
            $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_DESC] = true;
        }

        return $this;
    }

    /**
     * Set the value of [media_link] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLink($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->media_link !== $v) {
            $this->media_link = $v;
            $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_LINK] = true;
        }

        return $this;
    }

    /**
     * Set the value of [media_headline] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setHeadline($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->media_headline !== $v) {
            $this->media_headline = $v;
            $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_HEADLINE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [media_insert] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->media_insert !== null || $dt !== null) {
            if ($this->media_insert === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->media_insert->format("Y-m-d H:i:s.u")) {
                $this->media_insert = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_INSERT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [media_update] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->media_update !== null || $dt !== null) {
            if ($this->media_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->media_update->format("Y-m-d H:i:s.u")) {
                $this->media_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [media_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->media_created !== null || $dt !== null) {
            if ($this->media_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->media_created->format("Y-m-d H:i:s.u")) {
                $this->media_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [media_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->media_updated !== null || $dt !== null) {
            if ($this->media_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->media_updated->format("Y-m-d H:i:s.u")) {
                $this->media_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MediaFileTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->media_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MediaFileTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MediaFileTableMap::translateFieldName('CategoryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->category_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MediaFileTableMap::translateFieldName('Dir', TableMap::TYPE_PHPNAME, $indexType)];
            $this->media_dir = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MediaFileTableMap::translateFieldName('File', TableMap::TYPE_PHPNAME, $indexType)];
            $this->media_file = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : MediaFileTableMap::translateFieldName('Ext', TableMap::TYPE_PHPNAME, $indexType)];
            $this->media_ext = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : MediaFileTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->media_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : MediaFileTableMap::translateFieldName('Desc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->media_desc = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : MediaFileTableMap::translateFieldName('Link', TableMap::TYPE_PHPNAME, $indexType)];
            $this->media_link = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : MediaFileTableMap::translateFieldName('Headline', TableMap::TYPE_PHPNAME, $indexType)];
            $this->media_headline = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : MediaFileTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->media_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : MediaFileTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->media_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : MediaFileTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->media_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : MediaFileTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->media_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 14; // 14 = MediaFileTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\MediaFile'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(MediaFileTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMediaFileQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
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
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see MediaFile::setDeleted()
     * @see MediaFile::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MediaFileTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMediaFileQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MediaFileTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(MediaFileTableMap::COL_MEDIA_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(MediaFileTableMap::COL_MEDIA_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(MediaFileTableMap::COL_MEDIA_UPDATED)) {
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
                MediaFileTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_ID] = true;
        if (null !== $this->media_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MediaFileTableMap::COL_MEDIA_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_ID)) {
            $modifiedColumns[':p' . $index++]  = 'media_id';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_CATEGORY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'category_id';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_DIR)) {
            $modifiedColumns[':p' . $index++]  = 'media_dir';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_FILE)) {
            $modifiedColumns[':p' . $index++]  = 'media_file';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_EXT)) {
            $modifiedColumns[':p' . $index++]  = 'media_ext';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'media_title';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_DESC)) {
            $modifiedColumns[':p' . $index++]  = 'media_desc';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_LINK)) {
            $modifiedColumns[':p' . $index++]  = 'media_link';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_HEADLINE)) {
            $modifiedColumns[':p' . $index++]  = 'media_headline';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_INSERT)) {
            $modifiedColumns[':p' . $index++]  = 'media_insert';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'media_update';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'media_created';
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'media_updated';
        }

        $sql = sprintf(
            'INSERT INTO medias (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'media_id':
                        $stmt->bindValue($identifier, $this->media_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'category_id':
                        $stmt->bindValue($identifier, $this->category_id, PDO::PARAM_INT);

                        break;
                    case 'media_dir':
                        $stmt->bindValue($identifier, $this->media_dir, PDO::PARAM_STR);

                        break;
                    case 'media_file':
                        $stmt->bindValue($identifier, $this->media_file, PDO::PARAM_STR);

                        break;
                    case 'media_ext':
                        $stmt->bindValue($identifier, $this->media_ext, PDO::PARAM_STR);

                        break;
                    case 'media_title':
                        $stmt->bindValue($identifier, $this->media_title, PDO::PARAM_STR);

                        break;
                    case 'media_desc':
                        $stmt->bindValue($identifier, $this->media_desc, PDO::PARAM_STR);

                        break;
                    case 'media_link':
                        $stmt->bindValue($identifier, $this->media_link, PDO::PARAM_STR);

                        break;
                    case 'media_headline':
                        $stmt->bindValue($identifier, $this->media_headline, PDO::PARAM_STR);

                        break;
                    case 'media_insert':
                        $stmt->bindValue($identifier, $this->media_insert ? $this->media_insert->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'media_update':
                        $stmt->bindValue($identifier, $this->media_update ? $this->media_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'media_created':
                        $stmt->bindValue($identifier, $this->media_created ? $this->media_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'media_updated':
                        $stmt->bindValue($identifier, $this->media_updated ? $this->media_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = MediaFileTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getCategoryId();

            case 3:
                return $this->getDir();

            case 4:
                return $this->getFile();

            case 5:
                return $this->getExt();

            case 6:
                return $this->getTitle();

            case 7:
                return $this->getDesc();

            case 8:
                return $this->getLink();

            case 9:
                return $this->getHeadline();

            case 10:
                return $this->getInsert();

            case 11:
                return $this->getUpdate();

            case 12:
                return $this->getCreatedAt();

            case 13:
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
     *
     * @return array An associative array containing the field names (as keys) and field values
     */
    public function toArray(string $keyType = TableMap::TYPE_PHPNAME, bool $includeLazyLoadColumns = true, array $alreadyDumpedObjects = []): array
    {
        if (isset($alreadyDumpedObjects['MediaFile'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['MediaFile'][$this->hashCode()] = true;
        $keys = MediaFileTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getCategoryId(),
            $keys[3] => $this->getDir(),
            $keys[4] => $this->getFile(),
            $keys[5] => $this->getExt(),
            $keys[6] => $this->getTitle(),
            $keys[7] => $this->getDesc(),
            $keys[8] => $this->getLink(),
            $keys[9] => $this->getHeadline(),
            $keys[10] => $this->getInsert(),
            $keys[11] => $this->getUpdate(),
            $keys[12] => $this->getCreatedAt(),
            $keys[13] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[10]] instanceof \DateTimeInterface) {
            $result[$keys[10]] = $result[$keys[10]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[11]] instanceof \DateTimeInterface) {
            $result[$keys[11]] = $result[$keys[11]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[12]] instanceof \DateTimeInterface) {
            $result[$keys[12]] = $result[$keys[12]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[13]] instanceof \DateTimeInterface) {
            $result[$keys[13]] = $result[$keys[13]]->format('Y-m-d H:i:s.u');
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
        $pos = MediaFileTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setCategoryId($value);
                break;
            case 3:
                $this->setDir($value);
                break;
            case 4:
                $this->setFile($value);
                break;
            case 5:
                $this->setExt($value);
                break;
            case 6:
                $this->setTitle($value);
                break;
            case 7:
                $this->setDesc($value);
                break;
            case 8:
                $this->setLink($value);
                break;
            case 9:
                $this->setHeadline($value);
                break;
            case 10:
                $this->setInsert($value);
                break;
            case 11:
                $this->setUpdate($value);
                break;
            case 12:
                $this->setCreatedAt($value);
                break;
            case 13:
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
        $keys = MediaFileTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCategoryId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setDir($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setFile($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setExt($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setTitle($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setDesc($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setLink($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setHeadline($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setInsert($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setUpdate($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setCreatedAt($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setUpdatedAt($arr[$keys[13]]);
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
        $criteria = new Criteria(MediaFileTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_ID)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_ID, $this->media_id);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_SITE_ID)) {
            $criteria->add(MediaFileTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_CATEGORY_ID)) {
            $criteria->add(MediaFileTableMap::COL_CATEGORY_ID, $this->category_id);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_DIR)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_DIR, $this->media_dir);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_FILE)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_FILE, $this->media_file);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_EXT)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_EXT, $this->media_ext);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_TITLE)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_TITLE, $this->media_title);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_DESC)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_DESC, $this->media_desc);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_LINK)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_LINK, $this->media_link);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_HEADLINE)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_HEADLINE, $this->media_headline);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_INSERT)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_INSERT, $this->media_insert);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_UPDATE)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_UPDATE, $this->media_update);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_CREATED)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_CREATED, $this->media_created);
        }
        if ($this->isColumnModified(MediaFileTableMap::COL_MEDIA_UPDATED)) {
            $criteria->add(MediaFileTableMap::COL_MEDIA_UPDATED, $this->media_updated);
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
        $criteria = ChildMediaFileQuery::create();
        $criteria->add(MediaFileTableMap::COL_MEDIA_ID, $this->media_id);

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
     * Generic method to set the primary key (media_id column).
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
     * @param object $copyObj An object of \Model\MediaFile (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setCategoryId($this->getCategoryId());
        $copyObj->setDir($this->getDir());
        $copyObj->setFile($this->getFile());
        $copyObj->setExt($this->getExt());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDesc($this->getDesc());
        $copyObj->setLink($this->getLink());
        $copyObj->setHeadline($this->getHeadline());
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
     * @return \Model\MediaFile Clone of current object.
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     *
     * @return $this
     */
    public function clear()
    {
        $this->media_id = null;
        $this->site_id = null;
        $this->category_id = null;
        $this->media_dir = null;
        $this->media_file = null;
        $this->media_ext = null;
        $this->media_title = null;
        $this->media_desc = null;
        $this->media_link = null;
        $this->media_headline = null;
        $this->media_insert = null;
        $this->media_update = null;
        $this->media_created = null;
        $this->media_updated = null;
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

        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MediaFileTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[MediaFileTableMap::COL_MEDIA_UPDATED] = true;

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
