<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Article as ChildArticle;
use Model\ArticleQuery as ChildArticleQuery;
use Model\ArticleTag as ChildArticleTag;
use Model\ArticleTagQuery as ChildArticleTagQuery;
use Model\Tag as ChildTag;
use Model\TagQuery as ChildTagQuery;
use Model\Map\ArticleTagTableMap;
use Model\Map\TagTableMap;
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
 * Base class that represents a row from the 'tags' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Tag implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\TagTableMap';


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
     * The value for the tag_id field.
     *
     * @var        int
     */
    protected $tag_id;

    /**
     * The value for the tag_name field.
     *
     * @var        string|null
     */
    protected $tag_name;

    /**
     * The value for the tag_url field.
     *
     * @var        string|null
     */
    protected $tag_url;

    /**
     * The value for the tag_description field.
     *
     * @var        string|null
     */
    protected $tag_description;

    /**
     * The value for the tag_date field.
     *
     * @var        DateTime|null
     */
    protected $tag_date;

    /**
     * The value for the tag_num field.
     *
     * @var        int|null
     */
    protected $tag_num;

    /**
     * The value for the tag_insert field.
     *
     * @var        DateTime|null
     */
    protected $tag_insert;

    /**
     * The value for the tag_update field.
     *
     * @var        DateTime|null
     */
    protected $tag_update;

    /**
     * The value for the tag_created field.
     *
     * @var        DateTime|null
     */
    protected $tag_created;

    /**
     * The value for the tag_updated field.
     *
     * @var        DateTime|null
     */
    protected $tag_updated;

    /**
     * @var        ObjectCollection|ChildArticleTag[] Collection to store aggregation of ChildArticleTag objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildArticleTag> Collection to store aggregation of ChildArticleTag objects.
     */
    protected $collArticleTags;
    protected $collArticleTagsPartial;

    /**
     * @var        ObjectCollection|ChildArticle[] Cross Collection to store aggregation of ChildArticle objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildArticle> Cross Collection to store aggregation of ChildArticle objects.
     */
    protected $collArticles;

    /**
     * @var bool
     */
    protected $collArticlesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildArticle[]
     * @phpstan-var ObjectCollection&\Traversable<ChildArticle>
     */
    protected $articlesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildArticleTag[]
     * @phpstan-var ObjectCollection&\Traversable<ChildArticleTag>
     */
    protected $articleTagsScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Base\Tag object.
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
     * Compares this with another <code>Tag</code> instance.  If
     * <code>obj</code> is an instance of <code>Tag</code>, delegates to
     * <code>equals(Tag)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [tag_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->tag_id;
    }

    /**
     * Get the [tag_name] column value.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->tag_name;
    }

    /**
     * Get the [tag_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->tag_url;
    }

    /**
     * Get the [tag_description] column value.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->tag_description;
    }

    /**
     * Get the [optionally formatted] temporal [tag_date] column value.
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
            return $this->tag_date;
        } else {
            return $this->tag_date instanceof \DateTimeInterface ? $this->tag_date->format($format) : null;
        }
    }

    /**
     * Get the [tag_num] column value.
     *
     * @return int|null
     */
    public function getNum()
    {
        return $this->tag_num;
    }

    /**
     * Get the [optionally formatted] temporal [tag_insert] column value.
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
            return $this->tag_insert;
        } else {
            return $this->tag_insert instanceof \DateTimeInterface ? $this->tag_insert->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [tag_update] column value.
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
            return $this->tag_update;
        } else {
            return $this->tag_update instanceof \DateTimeInterface ? $this->tag_update->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [tag_created] column value.
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
            return $this->tag_created;
        } else {
            return $this->tag_created instanceof \DateTimeInterface ? $this->tag_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [tag_updated] column value.
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
            return $this->tag_updated;
        } else {
            return $this->tag_updated instanceof \DateTimeInterface ? $this->tag_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [tag_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->tag_id !== $v) {
            $this->tag_id = $v;
            $this->modifiedColumns[TagTableMap::COL_TAG_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [tag_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->tag_name !== $v) {
            $this->tag_name = $v;
            $this->modifiedColumns[TagTableMap::COL_TAG_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [tag_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->tag_url !== $v) {
            $this->tag_url = $v;
            $this->modifiedColumns[TagTableMap::COL_TAG_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [tag_description] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->tag_description !== $v) {
            $this->tag_description = $v;
            $this->modifiedColumns[TagTableMap::COL_TAG_DESCRIPTION] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [tag_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->tag_date !== null || $dt !== null) {
            if ($this->tag_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->tag_date->format("Y-m-d H:i:s.u")) {
                $this->tag_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[TagTableMap::COL_TAG_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Set the value of [tag_num] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNum($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->tag_num !== $v) {
            $this->tag_num = $v;
            $this->modifiedColumns[TagTableMap::COL_TAG_NUM] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [tag_insert] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->tag_insert !== null || $dt !== null) {
            if ($this->tag_insert === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->tag_insert->format("Y-m-d H:i:s.u")) {
                $this->tag_insert = $dt === null ? null : clone $dt;
                $this->modifiedColumns[TagTableMap::COL_TAG_INSERT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [tag_update] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->tag_update !== null || $dt !== null) {
            if ($this->tag_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->tag_update->format("Y-m-d H:i:s.u")) {
                $this->tag_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[TagTableMap::COL_TAG_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [tag_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->tag_created !== null || $dt !== null) {
            if ($this->tag_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->tag_created->format("Y-m-d H:i:s.u")) {
                $this->tag_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[TagTableMap::COL_TAG_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [tag_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->tag_updated !== null || $dt !== null) {
            if ($this->tag_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->tag_updated->format("Y-m-d H:i:s.u")) {
                $this->tag_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[TagTableMap::COL_TAG_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : TagTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tag_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : TagTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tag_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : TagTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tag_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : TagTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tag_description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : TagTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->tag_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : TagTableMap::translateFieldName('Num', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tag_num = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : TagTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->tag_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : TagTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->tag_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : TagTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->tag_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : TagTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->tag_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = TagTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Tag'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(TagTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildTagQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collArticleTags = null;

            $this->collArticles = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Tag::setDeleted()
     * @see Tag::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TagTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildTagQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(TagTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(TagTableMap::COL_TAG_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(TagTableMap::COL_TAG_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(TagTableMap::COL_TAG_UPDATED)) {
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
                TagTableMap::addInstanceToPool($this);
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

            if ($this->articlesScheduledForDeletion !== null) {
                if (!$this->articlesScheduledForDeletion->isEmpty()) {
                    $pks = [];
                    foreach ($this->articlesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Model\ArticleTagQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->articlesScheduledForDeletion = null;
                }

            }

            if ($this->collArticles) {
                foreach ($this->collArticles as $article) {
                    if (!$article->isDeleted() && ($article->isNew() || $article->isModified())) {
                        $article->save($con);
                    }
                }
            }


            if ($this->articleTagsScheduledForDeletion !== null) {
                if (!$this->articleTagsScheduledForDeletion->isEmpty()) {
                    \Model\ArticleTagQuery::create()
                        ->filterByPrimaryKeys($this->articleTagsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->articleTagsScheduledForDeletion = null;
                }
            }

            if ($this->collArticleTags !== null) {
                foreach ($this->collArticleTags as $referrerFK) {
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

        $this->modifiedColumns[TagTableMap::COL_TAG_ID] = true;
        if (null !== $this->tag_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TagTableMap::COL_TAG_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TagTableMap::COL_TAG_ID)) {
            $modifiedColumns[':p' . $index++]  = 'tag_id';
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'tag_name';
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_URL)) {
            $modifiedColumns[':p' . $index++]  = 'tag_url';
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'tag_description';
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'tag_date';
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_NUM)) {
            $modifiedColumns[':p' . $index++]  = 'tag_num';
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_INSERT)) {
            $modifiedColumns[':p' . $index++]  = 'tag_insert';
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'tag_update';
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'tag_created';
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'tag_updated';
        }

        $sql = sprintf(
            'INSERT INTO tags (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'tag_id':
                        $stmt->bindValue($identifier, $this->tag_id, PDO::PARAM_INT);

                        break;
                    case 'tag_name':
                        $stmt->bindValue($identifier, $this->tag_name, PDO::PARAM_STR);

                        break;
                    case 'tag_url':
                        $stmt->bindValue($identifier, $this->tag_url, PDO::PARAM_STR);

                        break;
                    case 'tag_description':
                        $stmt->bindValue($identifier, $this->tag_description, PDO::PARAM_STR);

                        break;
                    case 'tag_date':
                        $stmt->bindValue($identifier, $this->tag_date ? $this->tag_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'tag_num':
                        $stmt->bindValue($identifier, $this->tag_num, PDO::PARAM_INT);

                        break;
                    case 'tag_insert':
                        $stmt->bindValue($identifier, $this->tag_insert ? $this->tag_insert->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'tag_update':
                        $stmt->bindValue($identifier, $this->tag_update ? $this->tag_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'tag_created':
                        $stmt->bindValue($identifier, $this->tag_created ? $this->tag_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'tag_updated':
                        $stmt->bindValue($identifier, $this->tag_updated ? $this->tag_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = TagTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getUrl();

            case 3:
                return $this->getDescription();

            case 4:
                return $this->getDate();

            case 5:
                return $this->getNum();

            case 6:
                return $this->getInsert();

            case 7:
                return $this->getUpdate();

            case 8:
                return $this->getCreatedAt();

            case 9:
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
        if (isset($alreadyDumpedObjects['Tag'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Tag'][$this->hashCode()] = true;
        $keys = TagTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getUrl(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getDate(),
            $keys[5] => $this->getNum(),
            $keys[6] => $this->getInsert(),
            $keys[7] => $this->getUpdate(),
            $keys[8] => $this->getCreatedAt(),
            $keys[9] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[4]] instanceof \DateTimeInterface) {
            $result[$keys[4]] = $result[$keys[4]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[6]] instanceof \DateTimeInterface) {
            $result[$keys[6]] = $result[$keys[6]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[7]] instanceof \DateTimeInterface) {
            $result[$keys[7]] = $result[$keys[7]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[8]] instanceof \DateTimeInterface) {
            $result[$keys[8]] = $result[$keys[8]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[9]] instanceof \DateTimeInterface) {
            $result[$keys[9]] = $result[$keys[9]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collArticleTags) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'articleTags';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'tags_articless';
                        break;
                    default:
                        $key = 'ArticleTags';
                }

                $result[$key] = $this->collArticleTags->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = TagTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setUrl($value);
                break;
            case 3:
                $this->setDescription($value);
                break;
            case 4:
                $this->setDate($value);
                break;
            case 5:
                $this->setNum($value);
                break;
            case 6:
                $this->setInsert($value);
                break;
            case 7:
                $this->setUpdate($value);
                break;
            case 8:
                $this->setCreatedAt($value);
                break;
            case 9:
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
        $keys = TagTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUrl($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setDescription($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDate($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setNum($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setInsert($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setUpdate($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setCreatedAt($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setUpdatedAt($arr[$keys[9]]);
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
        $criteria = new Criteria(TagTableMap::DATABASE_NAME);

        if ($this->isColumnModified(TagTableMap::COL_TAG_ID)) {
            $criteria->add(TagTableMap::COL_TAG_ID, $this->tag_id);
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_NAME)) {
            $criteria->add(TagTableMap::COL_TAG_NAME, $this->tag_name);
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_URL)) {
            $criteria->add(TagTableMap::COL_TAG_URL, $this->tag_url);
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_DESCRIPTION)) {
            $criteria->add(TagTableMap::COL_TAG_DESCRIPTION, $this->tag_description);
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_DATE)) {
            $criteria->add(TagTableMap::COL_TAG_DATE, $this->tag_date);
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_NUM)) {
            $criteria->add(TagTableMap::COL_TAG_NUM, $this->tag_num);
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_INSERT)) {
            $criteria->add(TagTableMap::COL_TAG_INSERT, $this->tag_insert);
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_UPDATE)) {
            $criteria->add(TagTableMap::COL_TAG_UPDATE, $this->tag_update);
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_CREATED)) {
            $criteria->add(TagTableMap::COL_TAG_CREATED, $this->tag_created);
        }
        if ($this->isColumnModified(TagTableMap::COL_TAG_UPDATED)) {
            $criteria->add(TagTableMap::COL_TAG_UPDATED, $this->tag_updated);
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
        $criteria = ChildTagQuery::create();
        $criteria->add(TagTableMap::COL_TAG_ID, $this->tag_id);

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
     * Generic method to set the primary key (tag_id column).
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
     * @param object $copyObj An object of \Model\Tag (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setName($this->getName());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setDate($this->getDate());
        $copyObj->setNum($this->getNum());
        $copyObj->setInsert($this->getInsert());
        $copyObj->setUpdate($this->getUpdate());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getArticleTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArticleTag($relObj->copy($deepCopy));
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
     * @return \Model\Tag Clone of current object.
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
        if ('ArticleTag' === $relationName) {
            $this->initArticleTags();
            return;
        }
    }

    /**
     * Clears out the collArticleTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addArticleTags()
     */
    public function clearArticleTags()
    {
        $this->collArticleTags = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collArticleTags collection loaded partially.
     *
     * @return void
     */
    public function resetPartialArticleTags($v = true): void
    {
        $this->collArticleTagsPartial = $v;
    }

    /**
     * Initializes the collArticleTags collection.
     *
     * By default this just sets the collArticleTags collection to an empty array (like clearcollArticleTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initArticleTags(bool $overrideExisting = true): void
    {
        if (null !== $this->collArticleTags && !$overrideExisting) {
            return;
        }

        $collectionClassName = ArticleTagTableMap::getTableMap()->getCollectionClassName();

        $this->collArticleTags = new $collectionClassName;
        $this->collArticleTags->setModel('\Model\ArticleTag');
    }

    /**
     * Gets an array of ChildArticleTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildArticleTag[] List of ChildArticleTag objects
     * @phpstan-return ObjectCollection&\Traversable<ChildArticleTag> List of ChildArticleTag objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getArticleTags(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collArticleTagsPartial && !$this->isNew();
        if (null === $this->collArticleTags || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collArticleTags) {
                    $this->initArticleTags();
                } else {
                    $collectionClassName = ArticleTagTableMap::getTableMap()->getCollectionClassName();

                    $collArticleTags = new $collectionClassName;
                    $collArticleTags->setModel('\Model\ArticleTag');

                    return $collArticleTags;
                }
            } else {
                $collArticleTags = ChildArticleTagQuery::create(null, $criteria)
                    ->filterByTag($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collArticleTagsPartial && count($collArticleTags)) {
                        $this->initArticleTags(false);

                        foreach ($collArticleTags as $obj) {
                            if (false == $this->collArticleTags->contains($obj)) {
                                $this->collArticleTags->append($obj);
                            }
                        }

                        $this->collArticleTagsPartial = true;
                    }

                    return $collArticleTags;
                }

                if ($partial && $this->collArticleTags) {
                    foreach ($this->collArticleTags as $obj) {
                        if ($obj->isNew()) {
                            $collArticleTags[] = $obj;
                        }
                    }
                }

                $this->collArticleTags = $collArticleTags;
                $this->collArticleTagsPartial = false;
            }
        }

        return $this->collArticleTags;
    }

    /**
     * Sets a collection of ChildArticleTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $articleTags A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setArticleTags(Collection $articleTags, ?ConnectionInterface $con = null)
    {
        /** @var ChildArticleTag[] $articleTagsToDelete */
        $articleTagsToDelete = $this->getArticleTags(new Criteria(), $con)->diff($articleTags);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->articleTagsScheduledForDeletion = clone $articleTagsToDelete;

        foreach ($articleTagsToDelete as $articleTagRemoved) {
            $articleTagRemoved->setTag(null);
        }

        $this->collArticleTags = null;
        foreach ($articleTags as $articleTag) {
            $this->addArticleTag($articleTag);
        }

        $this->collArticleTags = $articleTags;
        $this->collArticleTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ArticleTag objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related ArticleTag objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countArticleTags(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collArticleTagsPartial && !$this->isNew();
        if (null === $this->collArticleTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArticleTags) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getArticleTags());
            }

            $query = ChildArticleTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTag($this)
                ->count($con);
        }

        return count($this->collArticleTags);
    }

    /**
     * Method called to associate a ChildArticleTag object to this object
     * through the ChildArticleTag foreign key attribute.
     *
     * @param ChildArticleTag $l ChildArticleTag
     * @return $this The current object (for fluent API support)
     */
    public function addArticleTag(ChildArticleTag $l)
    {
        if ($this->collArticleTags === null) {
            $this->initArticleTags();
            $this->collArticleTagsPartial = true;
        }

        if (!$this->collArticleTags->contains($l)) {
            $this->doAddArticleTag($l);

            if ($this->articleTagsScheduledForDeletion and $this->articleTagsScheduledForDeletion->contains($l)) {
                $this->articleTagsScheduledForDeletion->remove($this->articleTagsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildArticleTag $articleTag The ChildArticleTag object to add.
     */
    protected function doAddArticleTag(ChildArticleTag $articleTag): void
    {
        $this->collArticleTags[]= $articleTag;
        $articleTag->setTag($this);
    }

    /**
     * @param ChildArticleTag $articleTag The ChildArticleTag object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeArticleTag(ChildArticleTag $articleTag)
    {
        if ($this->getArticleTags()->contains($articleTag)) {
            $pos = $this->collArticleTags->search($articleTag);
            $this->collArticleTags->remove($pos);
            if (null === $this->articleTagsScheduledForDeletion) {
                $this->articleTagsScheduledForDeletion = clone $this->collArticleTags;
                $this->articleTagsScheduledForDeletion->clear();
            }
            $this->articleTagsScheduledForDeletion[]= clone $articleTag;
            $articleTag->setTag(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Tag is new, it will return
     * an empty collection; or if this Tag has previously
     * been saved, it will retrieve related ArticleTags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Tag.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildArticleTag[] List of ChildArticleTag objects
     * @phpstan-return ObjectCollection&\Traversable<ChildArticleTag}> List of ChildArticleTag objects
     */
    public function getArticleTagsJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildArticleTagQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getArticleTags($query, $con);
    }

    /**
     * Clears out the collArticles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addArticles()
     */
    public function clearArticles()
    {
        $this->collArticles = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collArticles crossRef collection.
     *
     * By default this just sets the collArticles collection to an empty collection (like clearArticles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initArticles()
    {
        $collectionClassName = ArticleTagTableMap::getTableMap()->getCollectionClassName();

        $this->collArticles = new $collectionClassName;
        $this->collArticlesPartial = true;
        $this->collArticles->setModel('\Model\Article');
    }

    /**
     * Checks if the collArticles collection is loaded.
     *
     * @return bool
     */
    public function isArticlesLoaded(): bool
    {
        return null !== $this->collArticles;
    }

    /**
     * Gets a collection of ChildArticle objects related by a many-to-many relationship
     * to the current object by way of the tags_articles cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildArticle[] List of ChildArticle objects
     * @phpstan-return ObjectCollection&\Traversable<ChildArticle> List of ChildArticle objects
     */
    public function getArticles(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collArticlesPartial && !$this->isNew();
        if (null === $this->collArticles || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collArticles) {
                    $this->initArticles();
                }
            } else {

                $query = ChildArticleQuery::create(null, $criteria)
                    ->filterByTag($this);
                $collArticles = $query->find($con);
                if (null !== $criteria) {
                    return $collArticles;
                }

                if ($partial && $this->collArticles) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collArticles as $obj) {
                        if (!$collArticles->contains($obj)) {
                            $collArticles[] = $obj;
                        }
                    }
                }

                $this->collArticles = $collArticles;
                $this->collArticlesPartial = false;
            }
        }

        return $this->collArticles;
    }

    /**
     * Sets a collection of Article objects related by a many-to-many relationship
     * to the current object by way of the tags_articles cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $articles A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setArticles(Collection $articles, ?ConnectionInterface $con = null)
    {
        $this->clearArticles();
        $currentArticles = $this->getArticles();

        $articlesScheduledForDeletion = $currentArticles->diff($articles);

        foreach ($articlesScheduledForDeletion as $toDelete) {
            $this->removeArticle($toDelete);
        }

        foreach ($articles as $article) {
            if (!$currentArticles->contains($article)) {
                $this->doAddArticle($article);
            }
        }

        $this->collArticlesPartial = false;
        $this->collArticles = $articles;

        return $this;
    }

    /**
     * Gets the number of Article objects related by a many-to-many relationship
     * to the current object by way of the tags_articles cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param bool $distinct Set to true to force count distinct
     * @param ConnectionInterface $con Optional connection object
     *
     * @return int The number of related Article objects
     */
    public function countArticles(?Criteria $criteria = null, $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collArticlesPartial && !$this->isNew();
        if (null === $this->collArticles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArticles) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getArticles());
                }

                $query = ChildArticleQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByTag($this)
                    ->count($con);
            }
        } else {
            return count($this->collArticles);
        }
    }

    /**
     * Associate a ChildArticle to this object
     * through the tags_articles cross reference table.
     *
     * @param ChildArticle $article
     * @return ChildTag The current object (for fluent API support)
     */
    public function addArticle(ChildArticle $article)
    {
        if ($this->collArticles === null) {
            $this->initArticles();
        }

        if (!$this->getArticles()->contains($article)) {
            // only add it if the **same** object is not already associated
            $this->collArticles->push($article);
            $this->doAddArticle($article);
        }

        return $this;
    }

    /**
     *
     * @param ChildArticle $article
     */
    protected function doAddArticle(ChildArticle $article)
    {
        $articleTag = new ChildArticleTag();

        $articleTag->setArticle($article);

        $articleTag->setTag($this);

        $this->addArticleTag($articleTag);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$article->isTagsLoaded()) {
            $article->initTags();
            $article->getTags()->push($this);
        } elseif (!$article->getTags()->contains($this)) {
            $article->getTags()->push($this);
        }

    }

    /**
     * Remove article of this object
     * through the tags_articles cross reference table.
     *
     * @param ChildArticle $article
     * @return ChildTag The current object (for fluent API support)
     */
    public function removeArticle(ChildArticle $article)
    {
        if ($this->getArticles()->contains($article)) {
            $articleTag = new ChildArticleTag();
            $articleTag->setArticle($article);
            if ($article->isTagsLoaded()) {
                //remove the back reference if available
                $article->getTags()->removeObject($this);
            }

            $articleTag->setTag($this);
            $this->removeArticleTag(clone $articleTag);
            $articleTag->clear();

            $this->collArticles->remove($this->collArticles->search($article));

            if (null === $this->articlesScheduledForDeletion) {
                $this->articlesScheduledForDeletion = clone $this->collArticles;
                $this->articlesScheduledForDeletion->clear();
            }

            $this->articlesScheduledForDeletion->push($article);
        }


        return $this;
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
        $this->tag_id = null;
        $this->tag_name = null;
        $this->tag_url = null;
        $this->tag_description = null;
        $this->tag_date = null;
        $this->tag_num = null;
        $this->tag_insert = null;
        $this->tag_update = null;
        $this->tag_created = null;
        $this->tag_updated = null;
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
            if ($this->collArticleTags) {
                foreach ($this->collArticleTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collArticles) {
                foreach ($this->collArticles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collArticleTags = null;
        $this->collArticles = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(TagTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[TagTableMap::COL_TAG_UPDATED] = true;

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
