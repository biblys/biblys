<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Article as ChildArticle;
use Model\ArticleQuery as ChildArticleQuery;
use Model\BookCollection as ChildBookCollection;
use Model\BookCollectionQuery as ChildBookCollectionQuery;
use Model\Publisher as ChildPublisher;
use Model\PublisherQuery as ChildPublisherQuery;
use Model\SpecialOffer as ChildSpecialOffer;
use Model\SpecialOfferQuery as ChildSpecialOfferQuery;
use Model\Map\ArticleTableMap;
use Model\Map\BookCollectionTableMap;
use Model\Map\SpecialOfferTableMap;
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
 * Base class that represents a row from the 'collections' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class BookCollection implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\BookCollectionTableMap';


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
     * The value for the collection_id field.
     *
     * @var        int
     */
    protected $collection_id;

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
     * The value for the pricegrid_id field.
     *
     * @var        int|null
     */
    protected $pricegrid_id;

    /**
     * The value for the collection_name field.
     *
     * @var        string|null
     */
    protected $collection_name;

    /**
     * The value for the collection_url field.
     *
     * @var        string|null
     */
    protected $collection_url;

    /**
     * The value for the collection_publisher field.
     *
     * @var        string|null
     */
    protected $collection_publisher;

    /**
     * The value for the collection_desc field.
     *
     * @var        string|null
     */
    protected $collection_desc;

    /**
     * The value for the collection_ignorenum field.
     *
     * @var        boolean|null
     */
    protected $collection_ignorenum;

    /**
     * The value for the collection_orderby field.
     *
     * @var        string|null
     */
    protected $collection_orderby;

    /**
     * The value for the collection_incorrect_weights field.
     *
     * @var        boolean|null
     */
    protected $collection_incorrect_weights;

    /**
     * The value for the collection_noosfere_id field.
     *
     * @var        int|null
     */
    protected $collection_noosfere_id;

    /**
     * The value for the collection_insert field.
     *
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        DateTime|null
     */
    protected $collection_insert;

    /**
     * The value for the collection_update field.
     *
     * @var        DateTime|null
     */
    protected $collection_update;

    /**
     * The value for the collection_hits field.
     *
     * @var        int|null
     */
    protected $collection_hits;

    /**
     * The value for the collection_duplicate field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $collection_duplicate;

    /**
     * The value for the collection_created field.
     *
     * @var        DateTime|null
     */
    protected $collection_created;

    /**
     * The value for the collection_updated field.
     *
     * @var        DateTime|null
     */
    protected $collection_updated;

    /**
     * @var        ChildPublisher
     */
    protected $aPublisher;

    /**
     * @var        ObjectCollection|ChildArticle[] Collection to store aggregation of ChildArticle objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildArticle> Collection to store aggregation of ChildArticle objects.
     */
    protected $collArticles;
    protected $collArticlesPartial;

    /**
     * @var        ObjectCollection|ChildSpecialOffer[] Collection to store aggregation of ChildSpecialOffer objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildSpecialOffer> Collection to store aggregation of ChildSpecialOffer objects.
     */
    protected $collSpecialOffers;
    protected $collSpecialOffersPartial;

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
     * @var ObjectCollection|ChildSpecialOffer[]
     * @phpstan-var ObjectCollection&\Traversable<ChildSpecialOffer>
     */
    protected $specialOffersScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
        $this->collection_duplicate = false;
    }

    /**
     * Initializes internal state of Model\Base\BookCollection object.
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
     * Compares this with another <code>BookCollection</code> instance.  If
     * <code>obj</code> is an instance of <code>BookCollection</code>, delegates to
     * <code>equals(BookCollection)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [collection_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->collection_id;
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
     * Get the [pricegrid_id] column value.
     *
     * @return int|null
     */
    public function getPricegridId()
    {
        return $this->pricegrid_id;
    }

    /**
     * Get the [collection_name] column value.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->collection_name;
    }

    /**
     * Get the [collection_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->collection_url;
    }

    /**
     * Get the [collection_publisher] column value.
     *
     * @return string|null
     */
    public function getPublisherName()
    {
        return $this->collection_publisher;
    }

    /**
     * Get the [collection_desc] column value.
     *
     * @return string|null
     */
    public function getDesc()
    {
        return $this->collection_desc;
    }

    /**
     * Get the [collection_ignorenum] column value.
     *
     * @return boolean|null
     */
    public function getIgnorenum()
    {
        return $this->collection_ignorenum;
    }

    /**
     * Get the [collection_ignorenum] column value.
     *
     * @return boolean|null
     */
    public function isIgnorenum()
    {
        return $this->getIgnorenum();
    }

    /**
     * Get the [collection_orderby] column value.
     *
     * @return string|null
     */
    public function getOrderby()
    {
        return $this->collection_orderby;
    }

    /**
     * Get the [collection_incorrect_weights] column value.
     *
     * @return boolean|null
     */
    public function getIncorrectWeights()
    {
        return $this->collection_incorrect_weights;
    }

    /**
     * Get the [collection_incorrect_weights] column value.
     *
     * @return boolean|null
     */
    public function isIncorrectWeights()
    {
        return $this->getIncorrectWeights();
    }

    /**
     * Get the [collection_noosfere_id] column value.
     *
     * @return int|null
     */
    public function getNoosfereId()
    {
        return $this->collection_noosfere_id;
    }

    /**
     * Get the [optionally formatted] temporal [collection_insert] column value.
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
            return $this->collection_insert;
        } else {
            return $this->collection_insert instanceof \DateTimeInterface ? $this->collection_insert->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [collection_update] column value.
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
            return $this->collection_update;
        } else {
            return $this->collection_update instanceof \DateTimeInterface ? $this->collection_update->format($format) : null;
        }
    }

    /**
     * Get the [collection_hits] column value.
     *
     * @return int|null
     */
    public function getHits()
    {
        return $this->collection_hits;
    }

    /**
     * Get the [collection_duplicate] column value.
     *
     * @return boolean|null
     */
    public function getDuplicate()
    {
        return $this->collection_duplicate;
    }

    /**
     * Get the [collection_duplicate] column value.
     *
     * @return boolean|null
     */
    public function isDuplicate()
    {
        return $this->getDuplicate();
    }

    /**
     * Get the [optionally formatted] temporal [collection_created] column value.
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
            return $this->collection_created;
        } else {
            return $this->collection_created instanceof \DateTimeInterface ? $this->collection_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [collection_updated] column value.
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
            return $this->collection_updated;
        } else {
            return $this->collection_updated instanceof \DateTimeInterface ? $this->collection_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [collection_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->collection_id !== $v) {
            $this->collection_id = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_ID] = true;
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
            $this->modifiedColumns[BookCollectionTableMap::COL_SITE_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPublisherId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_id !== $v) {
            $this->publisher_id = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_PUBLISHER_ID] = true;
        }

        if ($this->aPublisher !== null && $this->aPublisher->getId() !== $v) {
            $this->aPublisher = null;
        }

        return $this;
    }

    /**
     * Set the value of [pricegrid_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPricegridId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->pricegrid_id !== $v) {
            $this->pricegrid_id = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_PRICEGRID_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [collection_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->collection_name !== $v) {
            $this->collection_name = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [collection_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->collection_url !== $v) {
            $this->collection_url = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [collection_publisher] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPublisherName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->collection_publisher !== $v) {
            $this->collection_publisher = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_PUBLISHER] = true;
        }

        return $this;
    }

    /**
     * Set the value of [collection_desc] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->collection_desc !== $v) {
            $this->collection_desc = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_DESC] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [collection_ignorenum] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setIgnorenum($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->collection_ignorenum !== $v) {
            $this->collection_ignorenum = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_IGNORENUM] = true;
        }

        return $this;
    }

    /**
     * Set the value of [collection_orderby] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setOrderby($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->collection_orderby !== $v) {
            $this->collection_orderby = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_ORDERBY] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [collection_incorrect_weights] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setIncorrectWeights($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->collection_incorrect_weights !== $v) {
            $this->collection_incorrect_weights = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [collection_noosfere_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNoosfereId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->collection_noosfere_id !== $v) {
            $this->collection_noosfere_id = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [collection_insert] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->collection_insert !== null || $dt !== null) {
            if ($this->collection_insert === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->collection_insert->format("Y-m-d H:i:s.u")) {
                $this->collection_insert = $dt === null ? null : clone $dt;
                $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_INSERT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [collection_update] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->collection_update !== null || $dt !== null) {
            if ($this->collection_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->collection_update->format("Y-m-d H:i:s.u")) {
                $this->collection_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Set the value of [collection_hits] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setHits($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->collection_hits !== $v) {
            $this->collection_hits = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_HITS] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [collection_duplicate] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setDuplicate($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->collection_duplicate !== $v) {
            $this->collection_duplicate = $v;
            $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_DUPLICATE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [collection_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->collection_created !== null || $dt !== null) {
            if ($this->collection_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->collection_created->format("Y-m-d H:i:s.u")) {
                $this->collection_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [collection_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->collection_updated !== null || $dt !== null) {
            if ($this->collection_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->collection_updated->format("Y-m-d H:i:s.u")) {
                $this->collection_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_UPDATED] = true;
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
            if ($this->collection_duplicate !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : BookCollectionTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : BookCollectionTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : BookCollectionTableMap::translateFieldName('PublisherId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : BookCollectionTableMap::translateFieldName('PricegridId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pricegrid_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : BookCollectionTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : BookCollectionTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : BookCollectionTableMap::translateFieldName('PublisherName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_publisher = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : BookCollectionTableMap::translateFieldName('Desc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_desc = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : BookCollectionTableMap::translateFieldName('Ignorenum', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_ignorenum = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : BookCollectionTableMap::translateFieldName('Orderby', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_orderby = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : BookCollectionTableMap::translateFieldName('IncorrectWeights', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_incorrect_weights = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : BookCollectionTableMap::translateFieldName('NoosfereId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_noosfere_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : BookCollectionTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->collection_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : BookCollectionTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->collection_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : BookCollectionTableMap::translateFieldName('Hits', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_hits = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : BookCollectionTableMap::translateFieldName('Duplicate', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_duplicate = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : BookCollectionTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->collection_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : BookCollectionTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->collection_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 18; // 18 = BookCollectionTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\BookCollection'), 0, $e);
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
        if ($this->aPublisher !== null && $this->publisher_id !== $this->aPublisher->getId()) {
            $this->aPublisher = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(BookCollectionTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildBookCollectionQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPublisher = null;
            $this->collArticles = null;

            $this->collSpecialOffers = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see BookCollection::setDeleted()
     * @see BookCollection::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookCollectionTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildBookCollectionQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(BookCollectionTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_UPDATED)) {
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
                BookCollectionTableMap::addInstanceToPool($this);
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

            if ($this->aPublisher !== null) {
                if ($this->aPublisher->isModified() || $this->aPublisher->isNew()) {
                    $affectedRows += $this->aPublisher->save($con);
                }
                $this->setPublisher($this->aPublisher);
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

            if ($this->articlesScheduledForDeletion !== null) {
                if (!$this->articlesScheduledForDeletion->isEmpty()) {
                    foreach ($this->articlesScheduledForDeletion as $article) {
                        // need to save related object because we set the relation to null
                        $article->save($con);
                    }
                    $this->articlesScheduledForDeletion = null;
                }
            }

            if ($this->collArticles !== null) {
                foreach ($this->collArticles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->specialOffersScheduledForDeletion !== null) {
                if (!$this->specialOffersScheduledForDeletion->isEmpty()) {
                    \Model\SpecialOfferQuery::create()
                        ->filterByPrimaryKeys($this->specialOffersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->specialOffersScheduledForDeletion = null;
                }
            }

            if ($this->collSpecialOffers !== null) {
                foreach ($this->collSpecialOffers as $referrerFK) {
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

        $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_ID] = true;
        if (null !== $this->collection_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . BookCollectionTableMap::COL_COLLECTION_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_ID)) {
            $modifiedColumns[':p' . $index++]  = 'collection_id';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_id';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_PRICEGRID_ID)) {
            $modifiedColumns[':p' . $index++]  = 'pricegrid_id';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'collection_name';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_URL)) {
            $modifiedColumns[':p' . $index++]  = 'collection_url';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_PUBLISHER)) {
            $modifiedColumns[':p' . $index++]  = 'collection_publisher';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_DESC)) {
            $modifiedColumns[':p' . $index++]  = 'collection_desc';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_IGNORENUM)) {
            $modifiedColumns[':p' . $index++]  = 'collection_ignorenum';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_ORDERBY)) {
            $modifiedColumns[':p' . $index++]  = 'collection_orderby';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS)) {
            $modifiedColumns[':p' . $index++]  = 'collection_incorrect_weights';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'collection_noosfere_id';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_INSERT)) {
            $modifiedColumns[':p' . $index++]  = 'collection_insert';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'collection_update';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_HITS)) {
            $modifiedColumns[':p' . $index++]  = 'collection_hits';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_DUPLICATE)) {
            $modifiedColumns[':p' . $index++]  = 'collection_duplicate';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'collection_created';
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'collection_updated';
        }

        $sql = sprintf(
            'INSERT INTO collections (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'collection_id':
                        $stmt->bindValue($identifier, $this->collection_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'publisher_id':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);

                        break;
                    case 'pricegrid_id':
                        $stmt->bindValue($identifier, $this->pricegrid_id, PDO::PARAM_INT);

                        break;
                    case 'collection_name':
                        $stmt->bindValue($identifier, $this->collection_name, PDO::PARAM_STR);

                        break;
                    case 'collection_url':
                        $stmt->bindValue($identifier, $this->collection_url, PDO::PARAM_STR);

                        break;
                    case 'collection_publisher':
                        $stmt->bindValue($identifier, $this->collection_publisher, PDO::PARAM_STR);

                        break;
                    case 'collection_desc':
                        $stmt->bindValue($identifier, $this->collection_desc, PDO::PARAM_STR);

                        break;
                    case 'collection_ignorenum':
                        $stmt->bindValue($identifier, (int) $this->collection_ignorenum, PDO::PARAM_INT);

                        break;
                    case 'collection_orderby':
                        $stmt->bindValue($identifier, $this->collection_orderby, PDO::PARAM_STR);

                        break;
                    case 'collection_incorrect_weights':
                        $stmt->bindValue($identifier, (int) $this->collection_incorrect_weights, PDO::PARAM_INT);

                        break;
                    case 'collection_noosfere_id':
                        $stmt->bindValue($identifier, $this->collection_noosfere_id, PDO::PARAM_INT);

                        break;
                    case 'collection_insert':
                        $stmt->bindValue($identifier, $this->collection_insert ? $this->collection_insert->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'collection_update':
                        $stmt->bindValue($identifier, $this->collection_update ? $this->collection_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'collection_hits':
                        $stmt->bindValue($identifier, $this->collection_hits, PDO::PARAM_INT);

                        break;
                    case 'collection_duplicate':
                        $stmt->bindValue($identifier, (int) $this->collection_duplicate, PDO::PARAM_INT);

                        break;
                    case 'collection_created':
                        $stmt->bindValue($identifier, $this->collection_created ? $this->collection_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'collection_updated':
                        $stmt->bindValue($identifier, $this->collection_updated ? $this->collection_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = BookCollectionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getPublisherId();

            case 3:
                return $this->getPricegridId();

            case 4:
                return $this->getName();

            case 5:
                return $this->getUrl();

            case 6:
                return $this->getPublisherName();

            case 7:
                return $this->getDesc();

            case 8:
                return $this->getIgnorenum();

            case 9:
                return $this->getOrderby();

            case 10:
                return $this->getIncorrectWeights();

            case 11:
                return $this->getNoosfereId();

            case 12:
                return $this->getInsert();

            case 13:
                return $this->getUpdate();

            case 14:
                return $this->getHits();

            case 15:
                return $this->getDuplicate();

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
        if (isset($alreadyDumpedObjects['BookCollection'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['BookCollection'][$this->hashCode()] = true;
        $keys = BookCollectionTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getPublisherId(),
            $keys[3] => $this->getPricegridId(),
            $keys[4] => $this->getName(),
            $keys[5] => $this->getUrl(),
            $keys[6] => $this->getPublisherName(),
            $keys[7] => $this->getDesc(),
            $keys[8] => $this->getIgnorenum(),
            $keys[9] => $this->getOrderby(),
            $keys[10] => $this->getIncorrectWeights(),
            $keys[11] => $this->getNoosfereId(),
            $keys[12] => $this->getInsert(),
            $keys[13] => $this->getUpdate(),
            $keys[14] => $this->getHits(),
            $keys[15] => $this->getDuplicate(),
            $keys[16] => $this->getCreatedAt(),
            $keys[17] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[12]] instanceof \DateTimeInterface) {
            $result[$keys[12]] = $result[$keys[12]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[13]] instanceof \DateTimeInterface) {
            $result[$keys[13]] = $result[$keys[13]]->format('Y-m-d H:i:s.u');
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
            if (null !== $this->aPublisher) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'publisher';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'publishers';
                        break;
                    default:
                        $key = 'Publisher';
                }

                $result[$key] = $this->aPublisher->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collArticles) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'articles';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'articless';
                        break;
                    default:
                        $key = 'Articles';
                }

                $result[$key] = $this->collArticles->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSpecialOffers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'specialOffers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'special_offerss';
                        break;
                    default:
                        $key = 'SpecialOffers';
                }

                $result[$key] = $this->collSpecialOffers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = BookCollectionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setPublisherId($value);
                break;
            case 3:
                $this->setPricegridId($value);
                break;
            case 4:
                $this->setName($value);
                break;
            case 5:
                $this->setUrl($value);
                break;
            case 6:
                $this->setPublisherName($value);
                break;
            case 7:
                $this->setDesc($value);
                break;
            case 8:
                $this->setIgnorenum($value);
                break;
            case 9:
                $this->setOrderby($value);
                break;
            case 10:
                $this->setIncorrectWeights($value);
                break;
            case 11:
                $this->setNoosfereId($value);
                break;
            case 12:
                $this->setInsert($value);
                break;
            case 13:
                $this->setUpdate($value);
                break;
            case 14:
                $this->setHits($value);
                break;
            case 15:
                $this->setDuplicate($value);
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
        $keys = BookCollectionTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPublisherId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPricegridId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setName($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setUrl($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setPublisherName($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setDesc($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setIgnorenum($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setOrderby($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setIncorrectWeights($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setNoosfereId($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setInsert($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setUpdate($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setHits($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setDuplicate($arr[$keys[15]]);
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
        $criteria = new Criteria(BookCollectionTableMap::DATABASE_NAME);

        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_ID)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_ID, $this->collection_id);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_SITE_ID)) {
            $criteria->add(BookCollectionTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_PUBLISHER_ID)) {
            $criteria->add(BookCollectionTableMap::COL_PUBLISHER_ID, $this->publisher_id);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_PRICEGRID_ID)) {
            $criteria->add(BookCollectionTableMap::COL_PRICEGRID_ID, $this->pricegrid_id);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_NAME)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_NAME, $this->collection_name);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_URL)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_URL, $this->collection_url);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_PUBLISHER)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_PUBLISHER, $this->collection_publisher);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_DESC)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_DESC, $this->collection_desc);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_IGNORENUM)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_IGNORENUM, $this->collection_ignorenum);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_ORDERBY)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_ORDERBY, $this->collection_orderby);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS, $this->collection_incorrect_weights);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID, $this->collection_noosfere_id);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_INSERT)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_INSERT, $this->collection_insert);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_UPDATE)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_UPDATE, $this->collection_update);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_HITS)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_HITS, $this->collection_hits);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_DUPLICATE)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_DUPLICATE, $this->collection_duplicate);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_CREATED)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_CREATED, $this->collection_created);
        }
        if ($this->isColumnModified(BookCollectionTableMap::COL_COLLECTION_UPDATED)) {
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_UPDATED, $this->collection_updated);
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
        $criteria = ChildBookCollectionQuery::create();
        $criteria->add(BookCollectionTableMap::COL_COLLECTION_ID, $this->collection_id);

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
     * Generic method to set the primary key (collection_id column).
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
     * @param object $copyObj An object of \Model\BookCollection (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setPricegridId($this->getPricegridId());
        $copyObj->setName($this->getName());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setPublisherName($this->getPublisherName());
        $copyObj->setDesc($this->getDesc());
        $copyObj->setIgnorenum($this->getIgnorenum());
        $copyObj->setOrderby($this->getOrderby());
        $copyObj->setIncorrectWeights($this->getIncorrectWeights());
        $copyObj->setNoosfereId($this->getNoosfereId());
        $copyObj->setInsert($this->getInsert());
        $copyObj->setUpdate($this->getUpdate());
        $copyObj->setHits($this->getHits());
        $copyObj->setDuplicate($this->getDuplicate());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getArticles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArticle($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSpecialOffers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSpecialOffer($relObj->copy($deepCopy));
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
     * @return \Model\BookCollection Clone of current object.
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
     * Declares an association between this object and a ChildPublisher object.
     *
     * @param ChildPublisher|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setPublisher(ChildPublisher $v = null)
    {
        if ($v === null) {
            $this->setPublisherId(NULL);
        } else {
            $this->setPublisherId($v->getId());
        }

        $this->aPublisher = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPublisher object, it will not be re-added.
        if ($v !== null) {
            $v->addBookCollection($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPublisher object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildPublisher|null The associated ChildPublisher object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getPublisher(?ConnectionInterface $con = null)
    {
        if ($this->aPublisher === null && ($this->publisher_id != 0)) {
            $this->aPublisher = ChildPublisherQuery::create()->findPk($this->publisher_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPublisher->addBookCollections($this);
             */
        }

        return $this->aPublisher;
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
        if ('Article' === $relationName) {
            $this->initArticles();
            return;
        }
        if ('SpecialOffer' === $relationName) {
            $this->initSpecialOffers();
            return;
        }
    }

    /**
     * Clears out the collArticles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addArticles()
     */
    public function clearArticles()
    {
        $this->collArticles = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collArticles collection loaded partially.
     *
     * @return void
     */
    public function resetPartialArticles($v = true): void
    {
        $this->collArticlesPartial = $v;
    }

    /**
     * Initializes the collArticles collection.
     *
     * By default this just sets the collArticles collection to an empty array (like clearcollArticles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initArticles(bool $overrideExisting = true): void
    {
        if (null !== $this->collArticles && !$overrideExisting) {
            return;
        }

        $collectionClassName = ArticleTableMap::getTableMap()->getCollectionClassName();

        $this->collArticles = new $collectionClassName;
        $this->collArticles->setModel('\Model\Article');
    }

    /**
     * Gets an array of ChildArticle objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildBookCollection is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildArticle[] List of ChildArticle objects
     * @phpstan-return ObjectCollection&\Traversable<ChildArticle> List of ChildArticle objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getArticles(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collArticlesPartial && !$this->isNew();
        if (null === $this->collArticles || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collArticles) {
                    $this->initArticles();
                } else {
                    $collectionClassName = ArticleTableMap::getTableMap()->getCollectionClassName();

                    $collArticles = new $collectionClassName;
                    $collArticles->setModel('\Model\Article');

                    return $collArticles;
                }
            } else {
                $collArticles = ChildArticleQuery::create(null, $criteria)
                    ->filterByBookCollection($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collArticlesPartial && count($collArticles)) {
                        $this->initArticles(false);

                        foreach ($collArticles as $obj) {
                            if (false == $this->collArticles->contains($obj)) {
                                $this->collArticles->append($obj);
                            }
                        }

                        $this->collArticlesPartial = true;
                    }

                    return $collArticles;
                }

                if ($partial && $this->collArticles) {
                    foreach ($this->collArticles as $obj) {
                        if ($obj->isNew()) {
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
     * Sets a collection of ChildArticle objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $articles A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setArticles(Collection $articles, ?ConnectionInterface $con = null)
    {
        /** @var ChildArticle[] $articlesToDelete */
        $articlesToDelete = $this->getArticles(new Criteria(), $con)->diff($articles);


        $this->articlesScheduledForDeletion = $articlesToDelete;

        foreach ($articlesToDelete as $articleRemoved) {
            $articleRemoved->setBookCollection(null);
        }

        $this->collArticles = null;
        foreach ($articles as $article) {
            $this->addArticle($article);
        }

        $this->collArticles = $articles;
        $this->collArticlesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Article objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Article objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countArticles(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collArticlesPartial && !$this->isNew();
        if (null === $this->collArticles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArticles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getArticles());
            }

            $query = ChildArticleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByBookCollection($this)
                ->count($con);
        }

        return count($this->collArticles);
    }

    /**
     * Method called to associate a ChildArticle object to this object
     * through the ChildArticle foreign key attribute.
     *
     * @param ChildArticle $l ChildArticle
     * @return $this The current object (for fluent API support)
     */
    public function addArticle(ChildArticle $l)
    {
        if ($this->collArticles === null) {
            $this->initArticles();
            $this->collArticlesPartial = true;
        }

        if (!$this->collArticles->contains($l)) {
            $this->doAddArticle($l);

            if ($this->articlesScheduledForDeletion and $this->articlesScheduledForDeletion->contains($l)) {
                $this->articlesScheduledForDeletion->remove($this->articlesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildArticle $article The ChildArticle object to add.
     */
    protected function doAddArticle(ChildArticle $article): void
    {
        $this->collArticles[]= $article;
        $article->setBookCollection($this);
    }

    /**
     * @param ChildArticle $article The ChildArticle object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeArticle(ChildArticle $article)
    {
        if ($this->getArticles()->contains($article)) {
            $pos = $this->collArticles->search($article);
            $this->collArticles->remove($pos);
            if (null === $this->articlesScheduledForDeletion) {
                $this->articlesScheduledForDeletion = clone $this->collArticles;
                $this->articlesScheduledForDeletion->clear();
            }
            $this->articlesScheduledForDeletion[]= $article;
            $article->setBookCollection(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BookCollection is new, it will return
     * an empty collection; or if this BookCollection has previously
     * been saved, it will retrieve related Articles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BookCollection.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildArticle[] List of ChildArticle objects
     * @phpstan-return ObjectCollection&\Traversable<ChildArticle}> List of ChildArticle objects
     */
    public function getArticlesJoinPublisher(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildArticleQuery::create(null, $criteria);
        $query->joinWith('Publisher', $joinBehavior);

        return $this->getArticles($query, $con);
    }

    /**
     * Clears out the collSpecialOffers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addSpecialOffers()
     */
    public function clearSpecialOffers()
    {
        $this->collSpecialOffers = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collSpecialOffers collection loaded partially.
     *
     * @return void
     */
    public function resetPartialSpecialOffers($v = true): void
    {
        $this->collSpecialOffersPartial = $v;
    }

    /**
     * Initializes the collSpecialOffers collection.
     *
     * By default this just sets the collSpecialOffers collection to an empty array (like clearcollSpecialOffers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSpecialOffers(bool $overrideExisting = true): void
    {
        if (null !== $this->collSpecialOffers && !$overrideExisting) {
            return;
        }

        $collectionClassName = SpecialOfferTableMap::getTableMap()->getCollectionClassName();

        $this->collSpecialOffers = new $collectionClassName;
        $this->collSpecialOffers->setModel('\Model\SpecialOffer');
    }

    /**
     * Gets an array of ChildSpecialOffer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildBookCollection is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSpecialOffer[] List of ChildSpecialOffer objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSpecialOffer> List of ChildSpecialOffer objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSpecialOffers(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collSpecialOffersPartial && !$this->isNew();
        if (null === $this->collSpecialOffers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSpecialOffers) {
                    $this->initSpecialOffers();
                } else {
                    $collectionClassName = SpecialOfferTableMap::getTableMap()->getCollectionClassName();

                    $collSpecialOffers = new $collectionClassName;
                    $collSpecialOffers->setModel('\Model\SpecialOffer');

                    return $collSpecialOffers;
                }
            } else {
                $collSpecialOffers = ChildSpecialOfferQuery::create(null, $criteria)
                    ->filterByTargetCollection($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSpecialOffersPartial && count($collSpecialOffers)) {
                        $this->initSpecialOffers(false);

                        foreach ($collSpecialOffers as $obj) {
                            if (false == $this->collSpecialOffers->contains($obj)) {
                                $this->collSpecialOffers->append($obj);
                            }
                        }

                        $this->collSpecialOffersPartial = true;
                    }

                    return $collSpecialOffers;
                }

                if ($partial && $this->collSpecialOffers) {
                    foreach ($this->collSpecialOffers as $obj) {
                        if ($obj->isNew()) {
                            $collSpecialOffers[] = $obj;
                        }
                    }
                }

                $this->collSpecialOffers = $collSpecialOffers;
                $this->collSpecialOffersPartial = false;
            }
        }

        return $this->collSpecialOffers;
    }

    /**
     * Sets a collection of ChildSpecialOffer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $specialOffers A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setSpecialOffers(Collection $specialOffers, ?ConnectionInterface $con = null)
    {
        /** @var ChildSpecialOffer[] $specialOffersToDelete */
        $specialOffersToDelete = $this->getSpecialOffers(new Criteria(), $con)->diff($specialOffers);


        $this->specialOffersScheduledForDeletion = $specialOffersToDelete;

        foreach ($specialOffersToDelete as $specialOfferRemoved) {
            $specialOfferRemoved->setTargetCollection(null);
        }

        $this->collSpecialOffers = null;
        foreach ($specialOffers as $specialOffer) {
            $this->addSpecialOffer($specialOffer);
        }

        $this->collSpecialOffers = $specialOffers;
        $this->collSpecialOffersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SpecialOffer objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related SpecialOffer objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countSpecialOffers(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collSpecialOffersPartial && !$this->isNew();
        if (null === $this->collSpecialOffers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSpecialOffers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSpecialOffers());
            }

            $query = ChildSpecialOfferQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTargetCollection($this)
                ->count($con);
        }

        return count($this->collSpecialOffers);
    }

    /**
     * Method called to associate a ChildSpecialOffer object to this object
     * through the ChildSpecialOffer foreign key attribute.
     *
     * @param ChildSpecialOffer $l ChildSpecialOffer
     * @return $this The current object (for fluent API support)
     */
    public function addSpecialOffer(ChildSpecialOffer $l)
    {
        if ($this->collSpecialOffers === null) {
            $this->initSpecialOffers();
            $this->collSpecialOffersPartial = true;
        }

        if (!$this->collSpecialOffers->contains($l)) {
            $this->doAddSpecialOffer($l);

            if ($this->specialOffersScheduledForDeletion and $this->specialOffersScheduledForDeletion->contains($l)) {
                $this->specialOffersScheduledForDeletion->remove($this->specialOffersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSpecialOffer $specialOffer The ChildSpecialOffer object to add.
     */
    protected function doAddSpecialOffer(ChildSpecialOffer $specialOffer): void
    {
        $this->collSpecialOffers[]= $specialOffer;
        $specialOffer->setTargetCollection($this);
    }

    /**
     * @param ChildSpecialOffer $specialOffer The ChildSpecialOffer object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeSpecialOffer(ChildSpecialOffer $specialOffer)
    {
        if ($this->getSpecialOffers()->contains($specialOffer)) {
            $pos = $this->collSpecialOffers->search($specialOffer);
            $this->collSpecialOffers->remove($pos);
            if (null === $this->specialOffersScheduledForDeletion) {
                $this->specialOffersScheduledForDeletion = clone $this->collSpecialOffers;
                $this->specialOffersScheduledForDeletion->clear();
            }
            $this->specialOffersScheduledForDeletion[]= clone $specialOffer;
            $specialOffer->setTargetCollection(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BookCollection is new, it will return
     * an empty collection; or if this BookCollection has previously
     * been saved, it will retrieve related SpecialOffers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BookCollection.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSpecialOffer[] List of ChildSpecialOffer objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSpecialOffer}> List of ChildSpecialOffer objects
     */
    public function getSpecialOffersJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSpecialOfferQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getSpecialOffers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this BookCollection is new, it will return
     * an empty collection; or if this BookCollection has previously
     * been saved, it will retrieve related SpecialOffers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in BookCollection.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSpecialOffer[] List of ChildSpecialOffer objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSpecialOffer}> List of ChildSpecialOffer objects
     */
    public function getSpecialOffersJoinFreeArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSpecialOfferQuery::create(null, $criteria);
        $query->joinWith('FreeArticle', $joinBehavior);

        return $this->getSpecialOffers($query, $con);
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
        if (null !== $this->aPublisher) {
            $this->aPublisher->removeBookCollection($this);
        }
        $this->collection_id = null;
        $this->site_id = null;
        $this->publisher_id = null;
        $this->pricegrid_id = null;
        $this->collection_name = null;
        $this->collection_url = null;
        $this->collection_publisher = null;
        $this->collection_desc = null;
        $this->collection_ignorenum = null;
        $this->collection_orderby = null;
        $this->collection_incorrect_weights = null;
        $this->collection_noosfere_id = null;
        $this->collection_insert = null;
        $this->collection_update = null;
        $this->collection_hits = null;
        $this->collection_duplicate = null;
        $this->collection_created = null;
        $this->collection_updated = null;
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
            if ($this->collArticles) {
                foreach ($this->collArticles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSpecialOffers) {
                foreach ($this->collSpecialOffers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collArticles = null;
        $this->collSpecialOffers = null;
        $this->aPublisher = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(BookCollectionTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[BookCollectionTableMap::COL_COLLECTION_UPDATED] = true;

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
