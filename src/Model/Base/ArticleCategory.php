<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\ArticleCategory as ChildArticleCategory;
use Model\ArticleCategoryQuery as ChildArticleCategoryQuery;
use Model\Link as ChildLink;
use Model\LinkQuery as ChildLinkQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\Map\ArticleCategoryTableMap;
use Model\Map\LinkTableMap;
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
 * Base class that represents a row from the 'rayons' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class ArticleCategory implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\ArticleCategoryTableMap';


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
     * The value for the rayon_id field.
     *
     * @var        string
     */
    protected $rayon_id;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the rayon_name field.
     *
     * @var        string|null
     */
    protected $rayon_name;

    /**
     * The value for the rayon_url field.
     *
     * @var        string|null
     */
    protected $rayon_url;

    /**
     * The value for the rayon_desc field.
     *
     * @var        string|null
     */
    protected $rayon_desc;

    /**
     * The value for the rayon_order field.
     *
     * @var        int|null
     */
    protected $rayon_order;

    /**
     * The value for the rayon_sort_by field.
     *
     * Note: this column has a database default value of: 'id'
     * @var        string|null
     */
    protected $rayon_sort_by;

    /**
     * The value for the rayon_sort_order field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $rayon_sort_order;

    /**
     * The value for the rayon_show_upcoming field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $rayon_show_upcoming;

    /**
     * The value for the rayon_created field.
     *
     * @var        DateTime|null
     */
    protected $rayon_created;

    /**
     * The value for the rayon_updated field.
     *
     * @var        DateTime|null
     */
    protected $rayon_updated;

    /**
     * @var        ChildSite
     */
    protected $aSite;

    /**
     * @var        ObjectCollection|ChildLink[] Collection to store aggregation of ChildLink objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildLink> Collection to store aggregation of ChildLink objects.
     */
    protected $collLinks;
    protected $collLinksPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLink[]
     * @phpstan-var ObjectCollection&\Traversable<ChildLink>
     */
    protected $linksScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
        $this->rayon_sort_by = 'id';
        $this->rayon_sort_order = false;
        $this->rayon_show_upcoming = false;
    }

    /**
     * Initializes internal state of Model\Base\ArticleCategory object.
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
     * Compares this with another <code>ArticleCategory</code> instance.  If
     * <code>obj</code> is an instance of <code>ArticleCategory</code>, delegates to
     * <code>equals(ArticleCategory)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [rayon_id] column value.
     *
     * @return string
     */
    public function getId()
    {
        return $this->rayon_id;
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
     * Get the [rayon_name] column value.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->rayon_name;
    }

    /**
     * Get the [rayon_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->rayon_url;
    }

    /**
     * Get the [rayon_desc] column value.
     *
     * @return string|null
     */
    public function getDesc()
    {
        return $this->rayon_desc;
    }

    /**
     * Get the [rayon_order] column value.
     *
     * @return int|null
     */
    public function getOrder()
    {
        return $this->rayon_order;
    }

    /**
     * Get the [rayon_sort_by] column value.
     *
     * @return string|null
     */
    public function getSortBy()
    {
        return $this->rayon_sort_by;
    }

    /**
     * Get the [rayon_sort_order] column value.
     *
     * @return boolean|null
     */
    public function getSortOrder()
    {
        return $this->rayon_sort_order;
    }

    /**
     * Get the [rayon_sort_order] column value.
     *
     * @return boolean|null
     */
    public function isSortOrder()
    {
        return $this->getSortOrder();
    }

    /**
     * Get the [rayon_show_upcoming] column value.
     *
     * @return boolean|null
     */
    public function getShowUpcoming()
    {
        return $this->rayon_show_upcoming;
    }

    /**
     * Get the [rayon_show_upcoming] column value.
     *
     * @return boolean|null
     */
    public function isShowUpcoming()
    {
        return $this->getShowUpcoming();
    }

    /**
     * Get the [optionally formatted] temporal [rayon_created] column value.
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
            return $this->rayon_created;
        } else {
            return $this->rayon_created instanceof \DateTimeInterface ? $this->rayon_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [rayon_updated] column value.
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
            return $this->rayon_updated;
        } else {
            return $this->rayon_updated instanceof \DateTimeInterface ? $this->rayon_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [rayon_id] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->rayon_id !== $v) {
            $this->rayon_id = $v;
            $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_ID] = true;
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
            $this->modifiedColumns[ArticleCategoryTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    }

    /**
     * Set the value of [rayon_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->rayon_name !== $v) {
            $this->rayon_name = $v;
            $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [rayon_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->rayon_url !== $v) {
            $this->rayon_url = $v;
            $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [rayon_desc] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->rayon_desc !== $v) {
            $this->rayon_desc = $v;
            $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_DESC] = true;
        }

        return $this;
    }

    /**
     * Set the value of [rayon_order] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setOrder($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->rayon_order !== $v) {
            $this->rayon_order = $v;
            $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_ORDER] = true;
        }

        return $this;
    }

    /**
     * Set the value of [rayon_sort_by] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSortBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->rayon_sort_by !== $v) {
            $this->rayon_sort_by = $v;
            $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_SORT_BY] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [rayon_sort_order] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setSortOrder($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->rayon_sort_order !== $v) {
            $this->rayon_sort_order = $v;
            $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_SORT_ORDER] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [rayon_show_upcoming] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setShowUpcoming($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->rayon_show_upcoming !== $v) {
            $this->rayon_show_upcoming = $v;
            $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_SHOW_UPCOMING] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [rayon_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->rayon_created !== null || $dt !== null) {
            if ($this->rayon_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->rayon_created->format("Y-m-d H:i:s.u")) {
                $this->rayon_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [rayon_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->rayon_updated !== null || $dt !== null) {
            if ($this->rayon_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->rayon_updated->format("Y-m-d H:i:s.u")) {
                $this->rayon_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_UPDATED] = true;
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
            if ($this->rayon_sort_by !== 'id') {
                return false;
            }

            if ($this->rayon_sort_order !== false) {
                return false;
            }

            if ($this->rayon_show_upcoming !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ArticleCategoryTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rayon_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ArticleCategoryTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ArticleCategoryTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rayon_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ArticleCategoryTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rayon_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ArticleCategoryTableMap::translateFieldName('Desc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rayon_desc = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ArticleCategoryTableMap::translateFieldName('Order', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rayon_order = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ArticleCategoryTableMap::translateFieldName('SortBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rayon_sort_by = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : ArticleCategoryTableMap::translateFieldName('SortOrder', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rayon_sort_order = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : ArticleCategoryTableMap::translateFieldName('ShowUpcoming', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rayon_show_upcoming = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : ArticleCategoryTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->rayon_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : ArticleCategoryTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->rayon_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 11; // 11 = ArticleCategoryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\ArticleCategory'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(ArticleCategoryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildArticleCategoryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSite = null;
            $this->collLinks = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see ArticleCategory::setDeleted()
     * @see ArticleCategory::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleCategoryTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildArticleCategoryQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleCategoryTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            // sluggable behavior

            if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_URL) && $this->getUrl()) {
                $this->setUrl($this->makeSlugUnique($this->getUrl()));
            } else {
                $this->setUrl($this->createSlug());
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_UPDATED)) {
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
                ArticleCategoryTableMap::addInstanceToPool($this);
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

            if ($this->linksScheduledForDeletion !== null) {
                if (!$this->linksScheduledForDeletion->isEmpty()) {
                    foreach ($this->linksScheduledForDeletion as $link) {
                        // need to save related object because we set the relation to null
                        $link->save($con);
                    }
                    $this->linksScheduledForDeletion = null;
                }
            }

            if ($this->collLinks !== null) {
                foreach ($this->collLinks as $referrerFK) {
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

        $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_ID] = true;
        if (null !== $this->rayon_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ArticleCategoryTableMap::COL_RAYON_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_ID)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_id';
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_name';
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_URL)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_url';
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_DESC)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_desc';
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_ORDER)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_order';
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_SORT_BY)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_sort_by';
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_SORT_ORDER)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_sort_order';
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_SHOW_UPCOMING)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_show_upcoming';
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_created';
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_updated';
        }

        $sql = sprintf(
            'INSERT INTO rayons (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'rayon_id':
                        $stmt->bindValue($identifier, $this->rayon_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'rayon_name':
                        $stmt->bindValue($identifier, $this->rayon_name, PDO::PARAM_STR);

                        break;
                    case 'rayon_url':
                        $stmt->bindValue($identifier, $this->rayon_url, PDO::PARAM_STR);

                        break;
                    case 'rayon_desc':
                        $stmt->bindValue($identifier, $this->rayon_desc, PDO::PARAM_STR);

                        break;
                    case 'rayon_order':
                        $stmt->bindValue($identifier, $this->rayon_order, PDO::PARAM_INT);

                        break;
                    case 'rayon_sort_by':
                        $stmt->bindValue($identifier, $this->rayon_sort_by, PDO::PARAM_STR);

                        break;
                    case 'rayon_sort_order':
                        $stmt->bindValue($identifier, (int) $this->rayon_sort_order, PDO::PARAM_INT);

                        break;
                    case 'rayon_show_upcoming':
                        $stmt->bindValue($identifier, (int) $this->rayon_show_upcoming, PDO::PARAM_INT);

                        break;
                    case 'rayon_created':
                        $stmt->bindValue($identifier, $this->rayon_created ? $this->rayon_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'rayon_updated':
                        $stmt->bindValue($identifier, $this->rayon_updated ? $this->rayon_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = ArticleCategoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();

            case 3:
                return $this->getUrl();

            case 4:
                return $this->getDesc();

            case 5:
                return $this->getOrder();

            case 6:
                return $this->getSortBy();

            case 7:
                return $this->getSortOrder();

            case 8:
                return $this->getShowUpcoming();

            case 9:
                return $this->getCreatedAt();

            case 10:
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
        if (isset($alreadyDumpedObjects['ArticleCategory'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['ArticleCategory'][$this->hashCode()] = true;
        $keys = ArticleCategoryTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getUrl(),
            $keys[4] => $this->getDesc(),
            $keys[5] => $this->getOrder(),
            $keys[6] => $this->getSortBy(),
            $keys[7] => $this->getSortOrder(),
            $keys[8] => $this->getShowUpcoming(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
        ];
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
            if (null !== $this->collLinks) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'links';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'linkss';
                        break;
                    default:
                        $key = 'Links';
                }

                $result[$key] = $this->collLinks->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = ArticleCategoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setName($value);
                break;
            case 3:
                $this->setUrl($value);
                break;
            case 4:
                $this->setDesc($value);
                break;
            case 5:
                $this->setOrder($value);
                break;
            case 6:
                $this->setSortBy($value);
                break;
            case 7:
                $this->setSortOrder($value);
                break;
            case 8:
                $this->setShowUpcoming($value);
                break;
            case 9:
                $this->setCreatedAt($value);
                break;
            case 10:
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
        $keys = ArticleCategoryTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUrl($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDesc($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setOrder($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSortBy($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSortOrder($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setShowUpcoming($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setCreatedAt($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setUpdatedAt($arr[$keys[10]]);
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
        $criteria = new Criteria(ArticleCategoryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_ID)) {
            $criteria->add(ArticleCategoryTableMap::COL_RAYON_ID, $this->rayon_id);
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_SITE_ID)) {
            $criteria->add(ArticleCategoryTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_NAME)) {
            $criteria->add(ArticleCategoryTableMap::COL_RAYON_NAME, $this->rayon_name);
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_URL)) {
            $criteria->add(ArticleCategoryTableMap::COL_RAYON_URL, $this->rayon_url);
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_DESC)) {
            $criteria->add(ArticleCategoryTableMap::COL_RAYON_DESC, $this->rayon_desc);
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_ORDER)) {
            $criteria->add(ArticleCategoryTableMap::COL_RAYON_ORDER, $this->rayon_order);
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_SORT_BY)) {
            $criteria->add(ArticleCategoryTableMap::COL_RAYON_SORT_BY, $this->rayon_sort_by);
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_SORT_ORDER)) {
            $criteria->add(ArticleCategoryTableMap::COL_RAYON_SORT_ORDER, $this->rayon_sort_order);
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_SHOW_UPCOMING)) {
            $criteria->add(ArticleCategoryTableMap::COL_RAYON_SHOW_UPCOMING, $this->rayon_show_upcoming);
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_CREATED)) {
            $criteria->add(ArticleCategoryTableMap::COL_RAYON_CREATED, $this->rayon_created);
        }
        if ($this->isColumnModified(ArticleCategoryTableMap::COL_RAYON_UPDATED)) {
            $criteria->add(ArticleCategoryTableMap::COL_RAYON_UPDATED, $this->rayon_updated);
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
        $criteria = ChildArticleCategoryQuery::create();
        $criteria->add(ArticleCategoryTableMap::COL_RAYON_ID, $this->rayon_id);

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
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (rayon_id column).
     *
     * @param string|null $key Primary key.
     * @return void
     */
    public function setPrimaryKey(?string $key = null): void
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
     * @param object $copyObj An object of \Model\ArticleCategory (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setName($this->getName());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setDesc($this->getDesc());
        $copyObj->setOrder($this->getOrder());
        $copyObj->setSortBy($this->getSortBy());
        $copyObj->setSortOrder($this->getSortOrder());
        $copyObj->setShowUpcoming($this->getShowUpcoming());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getLinks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLink($relObj->copy($deepCopy));
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
     * @return \Model\ArticleCategory Clone of current object.
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
            $v->addArticleCategory($this);
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
                $this->aSite->addArticleCategories($this);
             */
        }

        return $this->aSite;
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
        if ('Link' === $relationName) {
            $this->initLinks();
            return;
        }
    }

    /**
     * Clears out the collLinks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addLinks()
     */
    public function clearLinks()
    {
        $this->collLinks = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collLinks collection loaded partially.
     *
     * @return void
     */
    public function resetPartialLinks($v = true): void
    {
        $this->collLinksPartial = $v;
    }

    /**
     * Initializes the collLinks collection.
     *
     * By default this just sets the collLinks collection to an empty array (like clearcollLinks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLinks(bool $overrideExisting = true): void
    {
        if (null !== $this->collLinks && !$overrideExisting) {
            return;
        }

        $collectionClassName = LinkTableMap::getTableMap()->getCollectionClassName();

        $this->collLinks = new $collectionClassName;
        $this->collLinks->setModel('\Model\Link');
    }

    /**
     * Gets an array of ChildLink objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildArticleCategory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLink[] List of ChildLink objects
     * @phpstan-return ObjectCollection&\Traversable<ChildLink> List of ChildLink objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getLinks(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collLinksPartial && !$this->isNew();
        if (null === $this->collLinks || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collLinks) {
                    $this->initLinks();
                } else {
                    $collectionClassName = LinkTableMap::getTableMap()->getCollectionClassName();

                    $collLinks = new $collectionClassName;
                    $collLinks->setModel('\Model\Link');

                    return $collLinks;
                }
            } else {
                $collLinks = ChildLinkQuery::create(null, $criteria)
                    ->filterByArticleCategory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLinksPartial && count($collLinks)) {
                        $this->initLinks(false);

                        foreach ($collLinks as $obj) {
                            if (false == $this->collLinks->contains($obj)) {
                                $this->collLinks->append($obj);
                            }
                        }

                        $this->collLinksPartial = true;
                    }

                    return $collLinks;
                }

                if ($partial && $this->collLinks) {
                    foreach ($this->collLinks as $obj) {
                        if ($obj->isNew()) {
                            $collLinks[] = $obj;
                        }
                    }
                }

                $this->collLinks = $collLinks;
                $this->collLinksPartial = false;
            }
        }

        return $this->collLinks;
    }

    /**
     * Sets a collection of ChildLink objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $links A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setLinks(Collection $links, ?ConnectionInterface $con = null)
    {
        /** @var ChildLink[] $linksToDelete */
        $linksToDelete = $this->getLinks(new Criteria(), $con)->diff($links);


        $this->linksScheduledForDeletion = $linksToDelete;

        foreach ($linksToDelete as $linkRemoved) {
            $linkRemoved->setArticleCategory(null);
        }

        $this->collLinks = null;
        foreach ($links as $link) {
            $this->addLink($link);
        }

        $this->collLinks = $links;
        $this->collLinksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Link objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Link objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countLinks(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collLinksPartial && !$this->isNew();
        if (null === $this->collLinks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLinks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLinks());
            }

            $query = ChildLinkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByArticleCategory($this)
                ->count($con);
        }

        return count($this->collLinks);
    }

    /**
     * Method called to associate a ChildLink object to this object
     * through the ChildLink foreign key attribute.
     *
     * @param ChildLink $l ChildLink
     * @return $this The current object (for fluent API support)
     */
    public function addLink(ChildLink $l)
    {
        if ($this->collLinks === null) {
            $this->initLinks();
            $this->collLinksPartial = true;
        }

        if (!$this->collLinks->contains($l)) {
            $this->doAddLink($l);

            if ($this->linksScheduledForDeletion and $this->linksScheduledForDeletion->contains($l)) {
                $this->linksScheduledForDeletion->remove($this->linksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLink $link The ChildLink object to add.
     */
    protected function doAddLink(ChildLink $link): void
    {
        $this->collLinks[]= $link;
        $link->setArticleCategory($this);
    }

    /**
     * @param ChildLink $link The ChildLink object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeLink(ChildLink $link)
    {
        if ($this->getLinks()->contains($link)) {
            $pos = $this->collLinks->search($link);
            $this->collLinks->remove($pos);
            if (null === $this->linksScheduledForDeletion) {
                $this->linksScheduledForDeletion = clone $this->collLinks;
                $this->linksScheduledForDeletion->clear();
            }
            $this->linksScheduledForDeletion[]= $link;
            $link->setArticleCategory(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ArticleCategory is new, it will return
     * an empty collection; or if this ArticleCategory has previously
     * been saved, it will retrieve related Links from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ArticleCategory.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLink[] List of ChildLink objects
     * @phpstan-return ObjectCollection&\Traversable<ChildLink}> List of ChildLink objects
     */
    public function getLinksJoinUser(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLinkQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getLinks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ArticleCategory is new, it will return
     * an empty collection; or if this ArticleCategory has previously
     * been saved, it will retrieve related Links from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ArticleCategory.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLink[] List of ChildLink objects
     * @phpstan-return ObjectCollection&\Traversable<ChildLink}> List of ChildLink objects
     */
    public function getLinksJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLinkQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getLinks($query, $con);
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
            $this->aSite->removeArticleCategory($this);
        }
        $this->rayon_id = null;
        $this->site_id = null;
        $this->rayon_name = null;
        $this->rayon_url = null;
        $this->rayon_desc = null;
        $this->rayon_order = null;
        $this->rayon_sort_by = null;
        $this->rayon_sort_order = null;
        $this->rayon_show_upcoming = null;
        $this->rayon_created = null;
        $this->rayon_updated = null;
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
            if ($this->collLinks) {
                foreach ($this->collLinks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collLinks = null;
        $this->aSite = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ArticleCategoryTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[ArticleCategoryTableMap::COL_RAYON_UPDATED] = true;

        return $this;
    }

    // sluggable behavior

    /**
     * Wrap the setter for slug value
     *
     * @param string
     * @return $this
     */
    public function setSlug($v)
    {
        $this->setUrl($v);

        return $this;
    }

    /**
     * Wrap the getter for slug value
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->getUrl();
    }

    /**
     * Create a unique slug based on the object
     *
     * @return string The object slug
     */
    protected function createSlug(): string
    {
        $slug = $this->createRawSlug();
        $slug = $this->limitSlugSize($slug);
        $slug = $this->makeSlugUnique($slug);

        return $slug;
    }

    /**
     * Create the slug from the appropriate columns
     *
     * @return string
     */
    protected function createRawSlug(): string
    {
        return '' . $this->cleanupSlugPart((string)$this->getName()) . '';
    }

    /**
     * Cleanup a string to make a slug of it
     * Removes special characters, replaces blanks with a separator, and trim it
     *
     * @param string $slug        the text to slugify
     * @param string $replacement the separator used by slug
     * @return string the slugified text
     */
    protected static function cleanupSlugPart(string $slug, string $replacement = '-'): string
    {
        // set locale explicitly
        $localeOrigin = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C.UTF-8');

        // transliterate
        if (function_exists('iconv')) {
            $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        }

        // lowercase
        if (function_exists('mb_strtolower')) {
            $slug = mb_strtolower($slug);
        } else {
            $slug = strtolower($slug);
        }

        // remove accents resulting from OSX's iconv
        $slug = str_replace(array('\'', '`', '^'), '', $slug);

        // replace non letter or digits with separator
        $slug = preg_replace('/\W+/', $replacement, $slug);

        // trim
        $slug = trim($slug, $replacement);

        setlocale(LC_CTYPE, $localeOrigin);

        if (empty($slug)) {
            return 'n-a';
        }

        return $slug;
    }


    /**
     * Make sure the slug is short enough to accommodate the column size
     *
     * @param string $slug The slug to check
     * @param int $incrementReservedSpace Space to reserve
     *
     * @return string The truncated slug
     */
    protected static function limitSlugSize(string $slug, int $incrementReservedSpace = 3): string
    {
        // check length, as suffix could put it over maximum
        if (strlen($slug) > (256 - $incrementReservedSpace)) {
            $slug = substr($slug, 0, 256 - $incrementReservedSpace);
        }

        return $slug;
    }


    /**
     * Get the slug, ensuring its uniqueness
     *
     * @param string $slug            the slug to check
     * @param string $separator       the separator used by slug
     * @param bool $alreadyExists   false for the first try, true for the second, and take the high count + 1
     * @return string the unique slug
     */
    protected function makeSlugUnique(string $slug, string $separator = '-', bool $alreadyExists = false)
    {
        if (!$alreadyExists) {
            $slug2 = $slug;
        } else {
            $slug2 = $slug . $separator;
        }

        $adapter = \Propel\Runtime\Propel::getServiceContainer()->getAdapter('default');
        $col = 'q.Url';
        $compare = $alreadyExists ? $adapter->compareRegex($col, '?') : sprintf('%s = ?', $col);

        $query = \Model\ArticleCategoryQuery::create('q')
            ->where($compare, $alreadyExists ? '^' . $slug2 . '[0-9]+$' : $slug2)
            ->prune($this)
        ;

        if (!$alreadyExists) {
            $count = $query->count();
            if ($count > 0) {
                return $this->makeSlugUnique($slug, $separator, true);
            }

            return $slug2;
        }

        $adapter = \Propel\Runtime\Propel::getServiceContainer()->getAdapter('default');
        // Already exists
        $object = $query
            ->addDescendingOrderByColumn($adapter->strLength('rayon_url'))
            ->addDescendingOrderByColumn('rayon_url')
        ->findOne();

        // First duplicate slug
        if ($object === null) {
            return $slug2 . '1';
        }

        $slugNum = substr($object->getUrl(), strlen($slug) + 1);
        if ($slugNum[0] == 0) {
            $slugNum[0] = 1;
        }

        return $slug2 . ($slugNum + 1);
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
