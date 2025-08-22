<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Article as ChildArticle;
use Model\ArticleCategory as ChildArticleCategory;
use Model\ArticleCategoryQuery as ChildArticleCategoryQuery;
use Model\ArticleQuery as ChildArticleQuery;
use Model\LinkQuery as ChildLinkQuery;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\LinkTableMap;
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
 * Base class that represents a row from the 'links' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Link implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\LinkTableMap';


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
     * The value for the link_id field.
     *
     * @var        int
     */
    protected $link_id;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the axys_account_id field.
     *
     * @var        int|null
     */
    protected $axys_account_id;

    /**
     * The value for the user_id field.
     *
     * @var        int|null
     */
    protected $user_id;

    /**
     * The value for the article_id field.
     *
     * @var        int|null
     */
    protected $article_id;

    /**
     * The value for the stock_id field.
     *
     * @var        int|null
     */
    protected $stock_id;

    /**
     * The value for the list_id field.
     *
     * @var        int|null
     */
    protected $list_id;

    /**
     * The value for the book_id field.
     *
     * @var        int|null
     */
    protected $book_id;

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
     * The value for the rayon_id field.
     *
     * @var        int|null
     */
    protected $rayon_id;

    /**
     * The value for the event_id field.
     *
     * @var        int|null
     */
    protected $event_id;

    /**
     * The value for the post_id field.
     *
     * @var        int|null
     */
    protected $post_id;

    /**
     * The value for the collection_id field.
     *
     * @var        int|null
     */
    protected $collection_id;

    /**
     * The value for the publisher_id field.
     *
     * @var        int|null
     */
    protected $publisher_id;

    /**
     * The value for the supplier_id field.
     *
     * @var        int|null
     */
    protected $supplier_id;

    /**
     * The value for the media_id field.
     *
     * @var        int|null
     */
    protected $media_id;

    /**
     * The value for the bundle_id field.
     *
     * @var        int|null
     */
    protected $bundle_id;

    /**
     * The value for the link_hide field.
     *
     * @var        boolean|null
     */
    protected $link_hide;

    /**
     * The value for the link_do_not_reorder field.
     *
     * @var        boolean|null
     */
    protected $link_do_not_reorder;

    /**
     * The value for the link_sponsor_axys_account_id field.
     *
     * @var        int|null
     */
    protected $link_sponsor_axys_account_id;

    /**
     * The value for the link_date field.
     *
     * @var        DateTime|null
     */
    protected $link_date;

    /**
     * The value for the link_created field.
     *
     * @var        DateTime|null
     */
    protected $link_created;

    /**
     * The value for the link_updated field.
     *
     * @var        DateTime|null
     */
    protected $link_updated;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ChildArticle
     */
    protected $aArticle;

    /**
     * @var        ChildArticleCategory
     */
    protected $aArticleCategory;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of Model\Base\Link object.
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
     * Compares this with another <code>Link</code> instance.  If
     * <code>obj</code> is an instance of <code>Link</code>, delegates to
     * <code>equals(Link)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [link_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->link_id;
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
     * Get the [axys_account_id] column value.
     *
     * @return int|null
     */
    public function getAxysAccountId()
    {
        return $this->axys_account_id;
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
     * Get the [article_id] column value.
     *
     * @return int|null
     */
    public function getArticleId()
    {
        return $this->article_id;
    }

    /**
     * Get the [stock_id] column value.
     *
     * @return int|null
     */
    public function getStockId()
    {
        return $this->stock_id;
    }

    /**
     * Get the [list_id] column value.
     *
     * @return int|null
     */
    public function getListId()
    {
        return $this->list_id;
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
     * Get the [rayon_id] column value.
     *
     * @return int|null
     */
    public function getRayonId()
    {
        return $this->rayon_id;
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
     * Get the [post_id] column value.
     *
     * @return int|null
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Get the [collection_id] column value.
     *
     * @return int|null
     */
    public function getCollectionId()
    {
        return $this->collection_id;
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
     * Get the [supplier_id] column value.
     *
     * @return int|null
     */
    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    /**
     * Get the [media_id] column value.
     *
     * @return int|null
     */
    public function getMediaId()
    {
        return $this->media_id;
    }

    /**
     * Get the [bundle_id] column value.
     *
     * @return int|null
     */
    public function getBundleId()
    {
        return $this->bundle_id;
    }

    /**
     * Get the [link_hide] column value.
     *
     * @return boolean|null
     */
    public function getHide()
    {
        return $this->link_hide;
    }

    /**
     * Get the [link_hide] column value.
     *
     * @return boolean|null
     */
    public function isHide()
    {
        return $this->getHide();
    }

    /**
     * Get the [link_do_not_reorder] column value.
     *
     * @return boolean|null
     */
    public function getDoNotReorder()
    {
        return $this->link_do_not_reorder;
    }

    /**
     * Get the [link_do_not_reorder] column value.
     *
     * @return boolean|null
     */
    public function isDoNotReorder()
    {
        return $this->getDoNotReorder();
    }

    /**
     * Get the [link_sponsor_axys_account_id] column value.
     *
     * @return int|null
     */
    public function getSponsorAxysAccountId()
    {
        return $this->link_sponsor_axys_account_id;
    }

    /**
     * Get the [optionally formatted] temporal [link_date] column value.
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
            return $this->link_date;
        } else {
            return $this->link_date instanceof \DateTimeInterface ? $this->link_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [link_created] column value.
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
            return $this->link_created;
        } else {
            return $this->link_created instanceof \DateTimeInterface ? $this->link_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [link_updated] column value.
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
            return $this->link_updated;
        } else {
            return $this->link_updated instanceof \DateTimeInterface ? $this->link_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [link_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->link_id !== $v) {
            $this->link_id = $v;
            $this->modifiedColumns[LinkTableMap::COL_LINK_ID] = true;
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
            $this->modifiedColumns[LinkTableMap::COL_SITE_ID] = true;
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
            $this->modifiedColumns[LinkTableMap::COL_AXYS_ACCOUNT_ID] = true;
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
            $this->modifiedColumns[LinkTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
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
            $this->modifiedColumns[LinkTableMap::COL_ARTICLE_ID] = true;
        }

        if ($this->aArticle !== null && $this->aArticle->getId() !== $v) {
            $this->aArticle = null;
        }

        return $this;
    }

    /**
     * Set the value of [stock_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setStockId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_id !== $v) {
            $this->stock_id = $v;
            $this->modifiedColumns[LinkTableMap::COL_STOCK_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [list_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setListId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->list_id !== $v) {
            $this->list_id = $v;
            $this->modifiedColumns[LinkTableMap::COL_LIST_ID] = true;
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
            $this->modifiedColumns[LinkTableMap::COL_BOOK_ID] = true;
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
            $this->modifiedColumns[LinkTableMap::COL_PEOPLE_ID] = true;
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
            $this->modifiedColumns[LinkTableMap::COL_JOB_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [rayon_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setRayonId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->rayon_id !== $v) {
            $this->rayon_id = $v;
            $this->modifiedColumns[LinkTableMap::COL_RAYON_ID] = true;
        }

        if ($this->aArticleCategory !== null && $this->aArticleCategory->getId() !== $v) {
            $this->aArticleCategory = null;
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
            $this->modifiedColumns[LinkTableMap::COL_EVENT_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [post_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPostId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->post_id !== $v) {
            $this->post_id = $v;
            $this->modifiedColumns[LinkTableMap::COL_POST_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [collection_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCollectionId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->collection_id !== $v) {
            $this->collection_id = $v;
            $this->modifiedColumns[LinkTableMap::COL_COLLECTION_ID] = true;
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
            $this->modifiedColumns[LinkTableMap::COL_PUBLISHER_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [supplier_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSupplierId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->supplier_id !== $v) {
            $this->supplier_id = $v;
            $this->modifiedColumns[LinkTableMap::COL_SUPPLIER_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [media_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMediaId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->media_id !== $v) {
            $this->media_id = $v;
            $this->modifiedColumns[LinkTableMap::COL_MEDIA_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bundle_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setBundleId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->bundle_id !== $v) {
            $this->bundle_id = $v;
            $this->modifiedColumns[LinkTableMap::COL_BUNDLE_ID] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [link_hide] column.
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

        if ($this->link_hide !== $v) {
            $this->link_hide = $v;
            $this->modifiedColumns[LinkTableMap::COL_LINK_HIDE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [link_do_not_reorder] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setDoNotReorder($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->link_do_not_reorder !== $v) {
            $this->link_do_not_reorder = $v;
            $this->modifiedColumns[LinkTableMap::COL_LINK_DO_NOT_REORDER] = true;
        }

        return $this;
    }

    /**
     * Set the value of [link_sponsor_axys_account_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSponsorAxysAccountId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->link_sponsor_axys_account_id !== $v) {
            $this->link_sponsor_axys_account_id = $v;
            $this->modifiedColumns[LinkTableMap::COL_LINK_SPONSOR_AXYS_ACCOUNT_ID] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [link_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->link_date !== null || $dt !== null) {
            if ($this->link_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->link_date->format("Y-m-d H:i:s.u")) {
                $this->link_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[LinkTableMap::COL_LINK_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [link_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->link_created !== null || $dt !== null) {
            if ($this->link_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->link_created->format("Y-m-d H:i:s.u")) {
                $this->link_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[LinkTableMap::COL_LINK_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [link_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->link_updated !== null || $dt !== null) {
            if ($this->link_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->link_updated->format("Y-m-d H:i:s.u")) {
                $this->link_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[LinkTableMap::COL_LINK_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LinkTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->link_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LinkTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LinkTableMap::translateFieldName('AxysAccountId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LinkTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LinkTableMap::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LinkTableMap::translateFieldName('StockId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : LinkTableMap::translateFieldName('ListId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->list_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : LinkTableMap::translateFieldName('BookId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->book_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : LinkTableMap::translateFieldName('PeopleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : LinkTableMap::translateFieldName('JobId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->job_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : LinkTableMap::translateFieldName('RayonId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rayon_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : LinkTableMap::translateFieldName('EventId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : LinkTableMap::translateFieldName('PostId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : LinkTableMap::translateFieldName('CollectionId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : LinkTableMap::translateFieldName('PublisherId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : LinkTableMap::translateFieldName('SupplierId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->supplier_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : LinkTableMap::translateFieldName('MediaId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->media_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : LinkTableMap::translateFieldName('BundleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bundle_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : LinkTableMap::translateFieldName('Hide', TableMap::TYPE_PHPNAME, $indexType)];
            $this->link_hide = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : LinkTableMap::translateFieldName('DoNotReorder', TableMap::TYPE_PHPNAME, $indexType)];
            $this->link_do_not_reorder = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : LinkTableMap::translateFieldName('SponsorAxysAccountId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->link_sponsor_axys_account_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : LinkTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->link_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : LinkTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->link_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : LinkTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->link_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 24; // 24 = LinkTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Link'), 0, $e);
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
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aArticle !== null && $this->article_id !== $this->aArticle->getId()) {
            $this->aArticle = null;
        }
        if ($this->aArticleCategory !== null && $this->rayon_id !== $this->aArticleCategory->getId()) {
            $this->aArticleCategory = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(LinkTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildLinkQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aArticle = null;
            $this->aArticleCategory = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Link::setDeleted()
     * @see Link::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LinkTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildLinkQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(LinkTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(LinkTableMap::COL_LINK_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(LinkTableMap::COL_LINK_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(LinkTableMap::COL_LINK_UPDATED)) {
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
                LinkTableMap::addInstanceToPool($this);
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

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->aArticle !== null) {
                if ($this->aArticle->isModified() || $this->aArticle->isNew()) {
                    $affectedRows += $this->aArticle->save($con);
                }
                $this->setArticle($this->aArticle);
            }

            if ($this->aArticleCategory !== null) {
                if ($this->aArticleCategory->isModified() || $this->aArticleCategory->isNew()) {
                    $affectedRows += $this->aArticleCategory->save($con);
                }
                $this->setArticleCategory($this->aArticleCategory);
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

        $this->modifiedColumns[LinkTableMap::COL_LINK_ID] = true;
        if (null !== $this->link_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LinkTableMap::COL_LINK_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LinkTableMap::COL_LINK_ID)) {
            $modifiedColumns[':p' . $index++]  = 'link_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_AXYS_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_ARTICLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'article_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_STOCK_ID)) {
            $modifiedColumns[':p' . $index++]  = 'stock_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_LIST_ID)) {
            $modifiedColumns[':p' . $index++]  = 'list_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_BOOK_ID)) {
            $modifiedColumns[':p' . $index++]  = 'book_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_PEOPLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'people_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_JOB_ID)) {
            $modifiedColumns[':p' . $index++]  = 'job_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_RAYON_ID)) {
            $modifiedColumns[':p' . $index++]  = 'rayon_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_EVENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'event_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_POST_ID)) {
            $modifiedColumns[':p' . $index++]  = 'post_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_COLLECTION_ID)) {
            $modifiedColumns[':p' . $index++]  = 'collection_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_SUPPLIER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'supplier_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_MEDIA_ID)) {
            $modifiedColumns[':p' . $index++]  = 'media_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_BUNDLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'bundle_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_HIDE)) {
            $modifiedColumns[':p' . $index++]  = 'link_hide';
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_DO_NOT_REORDER)) {
            $modifiedColumns[':p' . $index++]  = 'link_do_not_reorder';
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_SPONSOR_AXYS_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'link_sponsor_axys_account_id';
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'link_date';
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'link_created';
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'link_updated';
        }

        $sql = sprintf(
            'INSERT INTO links (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'link_id':
                        $stmt->bindValue($identifier, $this->link_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'axys_account_id':
                        $stmt->bindValue($identifier, $this->axys_account_id, PDO::PARAM_INT);

                        break;
                    case 'user_id':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);

                        break;
                    case 'article_id':
                        $stmt->bindValue($identifier, $this->article_id, PDO::PARAM_INT);

                        break;
                    case 'stock_id':
                        $stmt->bindValue($identifier, $this->stock_id, PDO::PARAM_INT);

                        break;
                    case 'list_id':
                        $stmt->bindValue($identifier, $this->list_id, PDO::PARAM_INT);

                        break;
                    case 'book_id':
                        $stmt->bindValue($identifier, $this->book_id, PDO::PARAM_INT);

                        break;
                    case 'people_id':
                        $stmt->bindValue($identifier, $this->people_id, PDO::PARAM_INT);

                        break;
                    case 'job_id':
                        $stmt->bindValue($identifier, $this->job_id, PDO::PARAM_INT);

                        break;
                    case 'rayon_id':
                        $stmt->bindValue($identifier, $this->rayon_id, PDO::PARAM_INT);

                        break;
                    case 'event_id':
                        $stmt->bindValue($identifier, $this->event_id, PDO::PARAM_INT);

                        break;
                    case 'post_id':
                        $stmt->bindValue($identifier, $this->post_id, PDO::PARAM_INT);

                        break;
                    case 'collection_id':
                        $stmt->bindValue($identifier, $this->collection_id, PDO::PARAM_INT);

                        break;
                    case 'publisher_id':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);

                        break;
                    case 'supplier_id':
                        $stmt->bindValue($identifier, $this->supplier_id, PDO::PARAM_INT);

                        break;
                    case 'media_id':
                        $stmt->bindValue($identifier, $this->media_id, PDO::PARAM_INT);

                        break;
                    case 'bundle_id':
                        $stmt->bindValue($identifier, $this->bundle_id, PDO::PARAM_INT);

                        break;
                    case 'link_hide':
                        $stmt->bindValue($identifier, (int) $this->link_hide, PDO::PARAM_INT);

                        break;
                    case 'link_do_not_reorder':
                        $stmt->bindValue($identifier, (int) $this->link_do_not_reorder, PDO::PARAM_INT);

                        break;
                    case 'link_sponsor_axys_account_id':
                        $stmt->bindValue($identifier, $this->link_sponsor_axys_account_id, PDO::PARAM_INT);

                        break;
                    case 'link_date':
                        $stmt->bindValue($identifier, $this->link_date ? $this->link_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'link_created':
                        $stmt->bindValue($identifier, $this->link_created ? $this->link_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'link_updated':
                        $stmt->bindValue($identifier, $this->link_updated ? $this->link_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = LinkTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAxysAccountId();

            case 3:
                return $this->getUserId();

            case 4:
                return $this->getArticleId();

            case 5:
                return $this->getStockId();

            case 6:
                return $this->getListId();

            case 7:
                return $this->getBookId();

            case 8:
                return $this->getPeopleId();

            case 9:
                return $this->getJobId();

            case 10:
                return $this->getRayonId();

            case 11:
                return $this->getEventId();

            case 12:
                return $this->getPostId();

            case 13:
                return $this->getCollectionId();

            case 14:
                return $this->getPublisherId();

            case 15:
                return $this->getSupplierId();

            case 16:
                return $this->getMediaId();

            case 17:
                return $this->getBundleId();

            case 18:
                return $this->getHide();

            case 19:
                return $this->getDoNotReorder();

            case 20:
                return $this->getSponsorAxysAccountId();

            case 21:
                return $this->getDate();

            case 22:
                return $this->getCreatedAt();

            case 23:
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
        if (isset($alreadyDumpedObjects['Link'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Link'][$this->hashCode()] = true;
        $keys = LinkTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getAxysAccountId(),
            $keys[3] => $this->getUserId(),
            $keys[4] => $this->getArticleId(),
            $keys[5] => $this->getStockId(),
            $keys[6] => $this->getListId(),
            $keys[7] => $this->getBookId(),
            $keys[8] => $this->getPeopleId(),
            $keys[9] => $this->getJobId(),
            $keys[10] => $this->getRayonId(),
            $keys[11] => $this->getEventId(),
            $keys[12] => $this->getPostId(),
            $keys[13] => $this->getCollectionId(),
            $keys[14] => $this->getPublisherId(),
            $keys[15] => $this->getSupplierId(),
            $keys[16] => $this->getMediaId(),
            $keys[17] => $this->getBundleId(),
            $keys[18] => $this->getHide(),
            $keys[19] => $this->getDoNotReorder(),
            $keys[20] => $this->getSponsorAxysAccountId(),
            $keys[21] => $this->getDate(),
            $keys[22] => $this->getCreatedAt(),
            $keys[23] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[21]] instanceof \DateTimeInterface) {
            $result[$keys[21]] = $result[$keys[21]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[22]] instanceof \DateTimeInterface) {
            $result[$keys[22]] = $result[$keys[22]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[23]] instanceof \DateTimeInterface) {
            $result[$keys[23]] = $result[$keys[23]]->format('Y-m-d H:i:s.u');
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
            if (null !== $this->aArticleCategory) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'articleCategory';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'rayons';
                        break;
                    default:
                        $key = 'ArticleCategory';
                }

                $result[$key] = $this->aArticleCategory->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = LinkTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setAxysAccountId($value);
                break;
            case 3:
                $this->setUserId($value);
                break;
            case 4:
                $this->setArticleId($value);
                break;
            case 5:
                $this->setStockId($value);
                break;
            case 6:
                $this->setListId($value);
                break;
            case 7:
                $this->setBookId($value);
                break;
            case 8:
                $this->setPeopleId($value);
                break;
            case 9:
                $this->setJobId($value);
                break;
            case 10:
                $this->setRayonId($value);
                break;
            case 11:
                $this->setEventId($value);
                break;
            case 12:
                $this->setPostId($value);
                break;
            case 13:
                $this->setCollectionId($value);
                break;
            case 14:
                $this->setPublisherId($value);
                break;
            case 15:
                $this->setSupplierId($value);
                break;
            case 16:
                $this->setMediaId($value);
                break;
            case 17:
                $this->setBundleId($value);
                break;
            case 18:
                $this->setHide($value);
                break;
            case 19:
                $this->setDoNotReorder($value);
                break;
            case 20:
                $this->setSponsorAxysAccountId($value);
                break;
            case 21:
                $this->setDate($value);
                break;
            case 22:
                $this->setCreatedAt($value);
                break;
            case 23:
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
        $keys = LinkTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAxysAccountId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUserId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setArticleId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setStockId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setListId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setBookId($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setPeopleId($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setJobId($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setRayonId($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setEventId($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setPostId($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setCollectionId($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setPublisherId($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setSupplierId($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setMediaId($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setBundleId($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setHide($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setDoNotReorder($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setSponsorAxysAccountId($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setDate($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setCreatedAt($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setUpdatedAt($arr[$keys[23]]);
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
        $criteria = new Criteria(LinkTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LinkTableMap::COL_LINK_ID)) {
            $criteria->add(LinkTableMap::COL_LINK_ID, $this->link_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_SITE_ID)) {
            $criteria->add(LinkTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_AXYS_ACCOUNT_ID)) {
            $criteria->add(LinkTableMap::COL_AXYS_ACCOUNT_ID, $this->axys_account_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_USER_ID)) {
            $criteria->add(LinkTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_ARTICLE_ID)) {
            $criteria->add(LinkTableMap::COL_ARTICLE_ID, $this->article_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_STOCK_ID)) {
            $criteria->add(LinkTableMap::COL_STOCK_ID, $this->stock_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_LIST_ID)) {
            $criteria->add(LinkTableMap::COL_LIST_ID, $this->list_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_BOOK_ID)) {
            $criteria->add(LinkTableMap::COL_BOOK_ID, $this->book_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_PEOPLE_ID)) {
            $criteria->add(LinkTableMap::COL_PEOPLE_ID, $this->people_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_JOB_ID)) {
            $criteria->add(LinkTableMap::COL_JOB_ID, $this->job_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_RAYON_ID)) {
            $criteria->add(LinkTableMap::COL_RAYON_ID, $this->rayon_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_EVENT_ID)) {
            $criteria->add(LinkTableMap::COL_EVENT_ID, $this->event_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_POST_ID)) {
            $criteria->add(LinkTableMap::COL_POST_ID, $this->post_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_COLLECTION_ID)) {
            $criteria->add(LinkTableMap::COL_COLLECTION_ID, $this->collection_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_PUBLISHER_ID)) {
            $criteria->add(LinkTableMap::COL_PUBLISHER_ID, $this->publisher_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_SUPPLIER_ID)) {
            $criteria->add(LinkTableMap::COL_SUPPLIER_ID, $this->supplier_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_MEDIA_ID)) {
            $criteria->add(LinkTableMap::COL_MEDIA_ID, $this->media_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_BUNDLE_ID)) {
            $criteria->add(LinkTableMap::COL_BUNDLE_ID, $this->bundle_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_HIDE)) {
            $criteria->add(LinkTableMap::COL_LINK_HIDE, $this->link_hide);
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_DO_NOT_REORDER)) {
            $criteria->add(LinkTableMap::COL_LINK_DO_NOT_REORDER, $this->link_do_not_reorder);
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_SPONSOR_AXYS_ACCOUNT_ID)) {
            $criteria->add(LinkTableMap::COL_LINK_SPONSOR_AXYS_ACCOUNT_ID, $this->link_sponsor_axys_account_id);
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_DATE)) {
            $criteria->add(LinkTableMap::COL_LINK_DATE, $this->link_date);
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_CREATED)) {
            $criteria->add(LinkTableMap::COL_LINK_CREATED, $this->link_created);
        }
        if ($this->isColumnModified(LinkTableMap::COL_LINK_UPDATED)) {
            $criteria->add(LinkTableMap::COL_LINK_UPDATED, $this->link_updated);
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
        $criteria = ChildLinkQuery::create();
        $criteria->add(LinkTableMap::COL_LINK_ID, $this->link_id);

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
     * Generic method to set the primary key (link_id column).
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
     * @param object $copyObj An object of \Model\Link (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setAxysAccountId($this->getAxysAccountId());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setArticleId($this->getArticleId());
        $copyObj->setStockId($this->getStockId());
        $copyObj->setListId($this->getListId());
        $copyObj->setBookId($this->getBookId());
        $copyObj->setPeopleId($this->getPeopleId());
        $copyObj->setJobId($this->getJobId());
        $copyObj->setRayonId($this->getRayonId());
        $copyObj->setEventId($this->getEventId());
        $copyObj->setPostId($this->getPostId());
        $copyObj->setCollectionId($this->getCollectionId());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setSupplierId($this->getSupplierId());
        $copyObj->setMediaId($this->getMediaId());
        $copyObj->setBundleId($this->getBundleId());
        $copyObj->setHide($this->getHide());
        $copyObj->setDoNotReorder($this->getDoNotReorder());
        $copyObj->setSponsorAxysAccountId($this->getSponsorAxysAccountId());
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
     * @return \Model\Link Clone of current object.
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
            $v->addLink($this);
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
                $this->aUser->addLinks($this);
             */
        }

        return $this->aUser;
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
            $v->addLink($this);
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
                $this->aArticle->addLinks($this);
             */
        }

        return $this->aArticle;
    }

    /**
     * Declares an association between this object and a ChildArticleCategory object.
     *
     * @param ChildArticleCategory|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setArticleCategory(ChildArticleCategory $v = null)
    {
        if ($v === null) {
            $this->setRayonId(NULL);
        } else {
            $this->setRayonId($v->getId());
        }

        $this->aArticleCategory = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildArticleCategory object, it will not be re-added.
        if ($v !== null) {
            $v->addLink($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildArticleCategory object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildArticleCategory|null The associated ChildArticleCategory object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getArticleCategory(?ConnectionInterface $con = null)
    {
        if ($this->aArticleCategory === null && ($this->rayon_id != 0)) {
            $this->aArticleCategory = ChildArticleCategoryQuery::create()->findPk($this->rayon_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aArticleCategory->addLinks($this);
             */
        }

        return $this->aArticleCategory;
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
        if (null !== $this->aUser) {
            $this->aUser->removeLink($this);
        }
        if (null !== $this->aArticle) {
            $this->aArticle->removeLink($this);
        }
        if (null !== $this->aArticleCategory) {
            $this->aArticleCategory->removeLink($this);
        }
        $this->link_id = null;
        $this->site_id = null;
        $this->axys_account_id = null;
        $this->user_id = null;
        $this->article_id = null;
        $this->stock_id = null;
        $this->list_id = null;
        $this->book_id = null;
        $this->people_id = null;
        $this->job_id = null;
        $this->rayon_id = null;
        $this->event_id = null;
        $this->post_id = null;
        $this->collection_id = null;
        $this->publisher_id = null;
        $this->supplier_id = null;
        $this->media_id = null;
        $this->bundle_id = null;
        $this->link_hide = null;
        $this->link_do_not_reorder = null;
        $this->link_sponsor_axys_account_id = null;
        $this->link_date = null;
        $this->link_created = null;
        $this->link_updated = null;
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

        $this->aUser = null;
        $this->aArticle = null;
        $this->aArticleCategory = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LinkTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[LinkTableMap::COL_LINK_UPDATED] = true;

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
