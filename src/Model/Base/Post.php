<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\PostQuery as ChildPostQuery;
use Model\Map\PostTableMap;
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
 * Base class that represents a row from the 'posts' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Post implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\PostTableMap';


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
     * The value for the post_id field.
     *
     * @var        int
     */
    protected $post_id;

    /**
     * The value for the axys_user_id field.
     *
     * @var        int|null
     */
    protected $axys_user_id;

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
     * The value for the category_id field.
     *
     * @var        int|null
     */
    protected $category_id;

    /**
     * The value for the post_url field.
     *
     * @var        string|null
     */
    protected $post_url;

    /**
     * The value for the post_title field.
     *
     * @var        string|null
     */
    protected $post_title;

    /**
     * The value for the post_content field.
     *
     * @var        string|null
     */
    protected $post_content;

    /**
     * The value for the post_illustration_version field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $post_illustration_version;

    /**
     * The value for the post_illustration_legend field.
     *
     * @var        string|null
     */
    protected $post_illustration_legend;

    /**
     * The value for the post_selected field.
     *
     * @var        boolean|null
     */
    protected $post_selected;

    /**
     * The value for the post_link field.
     *
     * @var        string|null
     */
    protected $post_link;

    /**
     * The value for the post_status field.
     *
     * @var        boolean|null
     */
    protected $post_status;

    /**
     * The value for the post_keywords field.
     *
     * @var        string|null
     */
    protected $post_keywords;

    /**
     * The value for the post_links field.
     *
     * @var        string|null
     */
    protected $post_links;

    /**
     * The value for the post_keywords_generated field.
     *
     * @var        DateTime|null
     */
    protected $post_keywords_generated;

    /**
     * The value for the post_fb_id field.
     *
     * @var        string|null
     */
    protected $post_fb_id;

    /**
     * The value for the post_date field.
     *
     * @var        DateTime|null
     */
    protected $post_date;

    /**
     * The value for the post_hits field.
     *
     * @var        int|null
     */
    protected $post_hits;

    /**
     * The value for the post_insert field.
     *
     * @var        DateTime|null
     */
    protected $post_insert;

    /**
     * The value for the post_update field.
     *
     * @var        DateTime|null
     */
    protected $post_update;

    /**
     * The value for the post_created field.
     *
     * @var        DateTime|null
     */
    protected $post_created;

    /**
     * The value for the post_updated field.
     *
     * @var        DateTime|null
     */
    protected $post_updated;

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
        $this->post_illustration_version = 0;
    }

    /**
     * Initializes internal state of Model\Base\Post object.
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
     * Compares this with another <code>Post</code> instance.  If
     * <code>obj</code> is an instance of <code>Post</code>, delegates to
     * <code>equals(Post)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [post_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->post_id;
    }

    /**
     * Get the [axys_user_id] column value.
     *
     * @return int|null
     */
    public function getAxysUserId()
    {
        return $this->axys_user_id;
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
     * Get the [category_id] column value.
     *
     * @return int|null
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Get the [post_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->post_url;
    }

    /**
     * Get the [post_title] column value.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->post_title;
    }

    /**
     * Get the [post_content] column value.
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->post_content;
    }

    /**
     * Get the [post_illustration_version] column value.
     *
     * @return int|null
     */
    public function getIllustrationVersion()
    {
        return $this->post_illustration_version;
    }

    /**
     * Get the [post_illustration_legend] column value.
     *
     * @return string|null
     */
    public function getIllustrationLegend()
    {
        return $this->post_illustration_legend;
    }

    /**
     * Get the [post_selected] column value.
     *
     * @return boolean|null
     */
    public function getSelected()
    {
        return $this->post_selected;
    }

    /**
     * Get the [post_selected] column value.
     *
     * @return boolean|null
     */
    public function isSelected()
    {
        return $this->getSelected();
    }

    /**
     * Get the [post_link] column value.
     *
     * @return string|null
     */
    public function getLink()
    {
        return $this->post_link;
    }

    /**
     * Get the [post_status] column value.
     *
     * @return boolean|null
     */
    public function getStatus()
    {
        return $this->post_status;
    }

    /**
     * Get the [post_status] column value.
     *
     * @return boolean|null
     */
    public function isStatus()
    {
        return $this->getStatus();
    }

    /**
     * Get the [post_keywords] column value.
     *
     * @return string|null
     */
    public function getKeywords()
    {
        return $this->post_keywords;
    }

    /**
     * Get the [post_links] column value.
     *
     * @return string|null
     */
    public function getLinks()
    {
        return $this->post_links;
    }

    /**
     * Get the [optionally formatted] temporal [post_keywords_generated] column value.
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
    public function getKeywordsGenerated($format = null)
    {
        if ($format === null) {
            return $this->post_keywords_generated;
        } else {
            return $this->post_keywords_generated instanceof \DateTimeInterface ? $this->post_keywords_generated->format($format) : null;
        }
    }

    /**
     * Get the [post_fb_id] column value.
     *
     * @return string|null
     */
    public function getFbId()
    {
        return $this->post_fb_id;
    }

    /**
     * Get the [optionally formatted] temporal [post_date] column value.
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
            return $this->post_date;
        } else {
            return $this->post_date instanceof \DateTimeInterface ? $this->post_date->format($format) : null;
        }
    }

    /**
     * Get the [post_hits] column value.
     *
     * @return int|null
     */
    public function getHits()
    {
        return $this->post_hits;
    }

    /**
     * Get the [optionally formatted] temporal [post_insert] column value.
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
            return $this->post_insert;
        } else {
            return $this->post_insert instanceof \DateTimeInterface ? $this->post_insert->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [post_update] column value.
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
            return $this->post_update;
        } else {
            return $this->post_update instanceof \DateTimeInterface ? $this->post_update->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [post_created] column value.
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
            return $this->post_created;
        } else {
            return $this->post_created instanceof \DateTimeInterface ? $this->post_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [post_updated] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getUpdatedAt($format = null)
    {
        if ($format === null) {
            return $this->post_updated;
        } else {
            return $this->post_updated instanceof \DateTimeInterface ? $this->post_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [post_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->post_id !== $v) {
            $this->post_id = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [axys_user_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAxysUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->axys_user_id !== $v) {
            $this->axys_user_id = $v;
            $this->modifiedColumns[PostTableMap::COL_AXYS_USER_ID] = true;
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
            $this->modifiedColumns[PostTableMap::COL_SITE_ID] = true;
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
            $this->modifiedColumns[PostTableMap::COL_PUBLISHER_ID] = true;
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
            $this->modifiedColumns[PostTableMap::COL_CATEGORY_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [post_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->post_url !== $v) {
            $this->post_url = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [post_title] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->post_title !== $v) {
            $this->post_title = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_TITLE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [post_content] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setContent($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->post_content !== $v) {
            $this->post_content = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_CONTENT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [post_illustration_version] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setIllustrationVersion($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->post_illustration_version !== $v) {
            $this->post_illustration_version = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_ILLUSTRATION_VERSION] = true;
        }

        return $this;
    }

    /**
     * Set the value of [post_illustration_legend] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setIllustrationLegend($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->post_illustration_legend !== $v) {
            $this->post_illustration_legend = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_ILLUSTRATION_LEGEND] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [post_selected] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setSelected($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->post_selected !== $v) {
            $this->post_selected = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_SELECTED] = true;
        }

        return $this;
    }

    /**
     * Set the value of [post_link] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLink($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->post_link !== $v) {
            $this->post_link = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_LINK] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [post_status] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->post_status !== $v) {
            $this->post_status = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_STATUS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [post_keywords] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setKeywords($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->post_keywords !== $v) {
            $this->post_keywords = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_KEYWORDS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [post_links] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLinks($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->post_links !== $v) {
            $this->post_links = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_LINKS] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [post_keywords_generated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setKeywordsGenerated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->post_keywords_generated !== null || $dt !== null) {
            if ($this->post_keywords_generated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->post_keywords_generated->format("Y-m-d H:i:s.u")) {
                $this->post_keywords_generated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PostTableMap::COL_POST_KEYWORDS_GENERATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Set the value of [post_fb_id] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFbId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->post_fb_id !== $v) {
            $this->post_fb_id = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_FB_ID] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [post_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->post_date !== null || $dt !== null) {
            if ($this->post_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->post_date->format("Y-m-d H:i:s.u")) {
                $this->post_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PostTableMap::COL_POST_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Set the value of [post_hits] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setHits($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->post_hits !== $v) {
            $this->post_hits = $v;
            $this->modifiedColumns[PostTableMap::COL_POST_HITS] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [post_insert] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->post_insert !== null || $dt !== null) {
            if ($this->post_insert === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->post_insert->format("Y-m-d H:i:s.u")) {
                $this->post_insert = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PostTableMap::COL_POST_INSERT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [post_update] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->post_update !== null || $dt !== null) {
            if ($this->post_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->post_update->format("Y-m-d H:i:s.u")) {
                $this->post_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PostTableMap::COL_POST_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [post_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->post_created !== null || $dt !== null) {
            if ($this->post_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->post_created->format("Y-m-d H:i:s.u")) {
                $this->post_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PostTableMap::COL_POST_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [post_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->post_updated !== null || $dt !== null) {
            if ($this->post_updated === null || $dt === null || $dt->format("Y-m-d") !== $this->post_updated->format("Y-m-d")) {
                $this->post_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PostTableMap::COL_POST_UPDATED] = true;
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
            if ($this->post_illustration_version !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PostTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PostTableMap::translateFieldName('AxysUserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PostTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PostTableMap::translateFieldName('PublisherId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PostTableMap::translateFieldName('CategoryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->category_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PostTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PostTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PostTableMap::translateFieldName('Content', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_content = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : PostTableMap::translateFieldName('IllustrationVersion', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_illustration_version = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : PostTableMap::translateFieldName('IllustrationLegend', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_illustration_legend = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : PostTableMap::translateFieldName('Selected', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_selected = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : PostTableMap::translateFieldName('Link', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_link = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : PostTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_status = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : PostTableMap::translateFieldName('Keywords', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_keywords = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : PostTableMap::translateFieldName('Links', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_links = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : PostTableMap::translateFieldName('KeywordsGenerated', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->post_keywords_generated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : PostTableMap::translateFieldName('FbId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_fb_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : PostTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->post_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : PostTableMap::translateFieldName('Hits', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_hits = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : PostTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->post_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : PostTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->post_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : PostTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->post_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : PostTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->post_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 23; // 23 = PostTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Post'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(PostTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPostQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
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
     * @see Post::setDeleted()
     * @see Post::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PostTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPostQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PostTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(PostTableMap::COL_POST_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(PostTableMap::COL_POST_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PostTableMap::COL_POST_UPDATED)) {
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
                PostTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[PostTableMap::COL_POST_ID] = true;
        if (null !== $this->post_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PostTableMap::COL_POST_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PostTableMap::COL_POST_ID)) {
            $modifiedColumns[':p' . $index++]  = 'post_id';
        }
        if ($this->isColumnModified(PostTableMap::COL_AXYS_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'axys_user_id';
        }
        if ($this->isColumnModified(PostTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(PostTableMap::COL_PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_id';
        }
        if ($this->isColumnModified(PostTableMap::COL_CATEGORY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'category_id';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_URL)) {
            $modifiedColumns[':p' . $index++]  = 'post_url';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'post_title';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_CONTENT)) {
            $modifiedColumns[':p' . $index++]  = 'post_content';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_ILLUSTRATION_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'post_illustration_version';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_ILLUSTRATION_LEGEND)) {
            $modifiedColumns[':p' . $index++]  = 'post_illustration_legend';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_SELECTED)) {
            $modifiedColumns[':p' . $index++]  = 'post_selected';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_LINK)) {
            $modifiedColumns[':p' . $index++]  = 'post_link';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'post_status';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_KEYWORDS)) {
            $modifiedColumns[':p' . $index++]  = 'post_keywords';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_LINKS)) {
            $modifiedColumns[':p' . $index++]  = 'post_links';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_KEYWORDS_GENERATED)) {
            $modifiedColumns[':p' . $index++]  = 'post_keywords_generated';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_FB_ID)) {
            $modifiedColumns[':p' . $index++]  = 'post_fb_id';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'post_date';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_HITS)) {
            $modifiedColumns[':p' . $index++]  = 'post_hits';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_INSERT)) {
            $modifiedColumns[':p' . $index++]  = 'post_insert';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'post_update';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'post_created';
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'post_updated';
        }

        $sql = sprintf(
            'INSERT INTO posts (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'post_id':
                        $stmt->bindValue($identifier, $this->post_id, PDO::PARAM_INT);

                        break;
                    case 'axys_user_id':
                        $stmt->bindValue($identifier, $this->axys_user_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'publisher_id':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);

                        break;
                    case 'category_id':
                        $stmt->bindValue($identifier, $this->category_id, PDO::PARAM_INT);

                        break;
                    case 'post_url':
                        $stmt->bindValue($identifier, $this->post_url, PDO::PARAM_STR);

                        break;
                    case 'post_title':
                        $stmt->bindValue($identifier, $this->post_title, PDO::PARAM_STR);

                        break;
                    case 'post_content':
                        $stmt->bindValue($identifier, $this->post_content, PDO::PARAM_STR);

                        break;
                    case 'post_illustration_version':
                        $stmt->bindValue($identifier, $this->post_illustration_version, PDO::PARAM_INT);

                        break;
                    case 'post_illustration_legend':
                        $stmt->bindValue($identifier, $this->post_illustration_legend, PDO::PARAM_STR);

                        break;
                    case 'post_selected':
                        $stmt->bindValue($identifier, (int) $this->post_selected, PDO::PARAM_INT);

                        break;
                    case 'post_link':
                        $stmt->bindValue($identifier, $this->post_link, PDO::PARAM_STR);

                        break;
                    case 'post_status':
                        $stmt->bindValue($identifier, (int) $this->post_status, PDO::PARAM_INT);

                        break;
                    case 'post_keywords':
                        $stmt->bindValue($identifier, $this->post_keywords, PDO::PARAM_STR);

                        break;
                    case 'post_links':
                        $stmt->bindValue($identifier, $this->post_links, PDO::PARAM_STR);

                        break;
                    case 'post_keywords_generated':
                        $stmt->bindValue($identifier, $this->post_keywords_generated ? $this->post_keywords_generated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'post_fb_id':
                        $stmt->bindValue($identifier, $this->post_fb_id, PDO::PARAM_INT);

                        break;
                    case 'post_date':
                        $stmt->bindValue($identifier, $this->post_date ? $this->post_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'post_hits':
                        $stmt->bindValue($identifier, $this->post_hits, PDO::PARAM_INT);

                        break;
                    case 'post_insert':
                        $stmt->bindValue($identifier, $this->post_insert ? $this->post_insert->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'post_update':
                        $stmt->bindValue($identifier, $this->post_update ? $this->post_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'post_created':
                        $stmt->bindValue($identifier, $this->post_created ? $this->post_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'post_updated':
                        $stmt->bindValue($identifier, $this->post_updated ? $this->post_updated->format("Y-m-d") : null, PDO::PARAM_STR);

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
        $pos = PostTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAxysUserId();

            case 2:
                return $this->getSiteId();

            case 3:
                return $this->getPublisherId();

            case 4:
                return $this->getCategoryId();

            case 5:
                return $this->getUrl();

            case 6:
                return $this->getTitle();

            case 7:
                return $this->getContent();

            case 8:
                return $this->getIllustrationVersion();

            case 9:
                return $this->getIllustrationLegend();

            case 10:
                return $this->getSelected();

            case 11:
                return $this->getLink();

            case 12:
                return $this->getStatus();

            case 13:
                return $this->getKeywords();

            case 14:
                return $this->getLinks();

            case 15:
                return $this->getKeywordsGenerated();

            case 16:
                return $this->getFbId();

            case 17:
                return $this->getDate();

            case 18:
                return $this->getHits();

            case 19:
                return $this->getInsert();

            case 20:
                return $this->getUpdate();

            case 21:
                return $this->getCreatedAt();

            case 22:
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
        if (isset($alreadyDumpedObjects['Post'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Post'][$this->hashCode()] = true;
        $keys = PostTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAxysUserId(),
            $keys[2] => $this->getSiteId(),
            $keys[3] => $this->getPublisherId(),
            $keys[4] => $this->getCategoryId(),
            $keys[5] => $this->getUrl(),
            $keys[6] => $this->getTitle(),
            $keys[7] => $this->getContent(),
            $keys[8] => $this->getIllustrationVersion(),
            $keys[9] => $this->getIllustrationLegend(),
            $keys[10] => $this->getSelected(),
            $keys[11] => $this->getLink(),
            $keys[12] => $this->getStatus(),
            $keys[13] => $this->getKeywords(),
            $keys[14] => $this->getLinks(),
            $keys[15] => $this->getKeywordsGenerated(),
            $keys[16] => $this->getFbId(),
            $keys[17] => $this->getDate(),
            $keys[18] => $this->getHits(),
            $keys[19] => $this->getInsert(),
            $keys[20] => $this->getUpdate(),
            $keys[21] => $this->getCreatedAt(),
            $keys[22] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[15]] instanceof \DateTimeInterface) {
            $result[$keys[15]] = $result[$keys[15]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[17]] instanceof \DateTimeInterface) {
            $result[$keys[17]] = $result[$keys[17]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[19]] instanceof \DateTimeInterface) {
            $result[$keys[19]] = $result[$keys[19]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[20]] instanceof \DateTimeInterface) {
            $result[$keys[20]] = $result[$keys[20]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[21]] instanceof \DateTimeInterface) {
            $result[$keys[21]] = $result[$keys[21]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[22]] instanceof \DateTimeInterface) {
            $result[$keys[22]] = $result[$keys[22]]->format('Y-m-d');
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
        $pos = PostTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setAxysUserId($value);
                break;
            case 2:
                $this->setSiteId($value);
                break;
            case 3:
                $this->setPublisherId($value);
                break;
            case 4:
                $this->setCategoryId($value);
                break;
            case 5:
                $this->setUrl($value);
                break;
            case 6:
                $this->setTitle($value);
                break;
            case 7:
                $this->setContent($value);
                break;
            case 8:
                $this->setIllustrationVersion($value);
                break;
            case 9:
                $this->setIllustrationLegend($value);
                break;
            case 10:
                $this->setSelected($value);
                break;
            case 11:
                $this->setLink($value);
                break;
            case 12:
                $this->setStatus($value);
                break;
            case 13:
                $this->setKeywords($value);
                break;
            case 14:
                $this->setLinks($value);
                break;
            case 15:
                $this->setKeywordsGenerated($value);
                break;
            case 16:
                $this->setFbId($value);
                break;
            case 17:
                $this->setDate($value);
                break;
            case 18:
                $this->setHits($value);
                break;
            case 19:
                $this->setInsert($value);
                break;
            case 20:
                $this->setUpdate($value);
                break;
            case 21:
                $this->setCreatedAt($value);
                break;
            case 22:
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
        $keys = PostTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setAxysUserId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setSiteId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPublisherId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCategoryId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setUrl($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setTitle($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setContent($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setIllustrationVersion($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setIllustrationLegend($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setSelected($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setLink($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setStatus($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setKeywords($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setLinks($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setKeywordsGenerated($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setFbId($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setDate($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setHits($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setInsert($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setUpdate($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setCreatedAt($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setUpdatedAt($arr[$keys[22]]);
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
        $criteria = new Criteria(PostTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PostTableMap::COL_POST_ID)) {
            $criteria->add(PostTableMap::COL_POST_ID, $this->post_id);
        }
        if ($this->isColumnModified(PostTableMap::COL_AXYS_USER_ID)) {
            $criteria->add(PostTableMap::COL_AXYS_USER_ID, $this->axys_user_id);
        }
        if ($this->isColumnModified(PostTableMap::COL_SITE_ID)) {
            $criteria->add(PostTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(PostTableMap::COL_PUBLISHER_ID)) {
            $criteria->add(PostTableMap::COL_PUBLISHER_ID, $this->publisher_id);
        }
        if ($this->isColumnModified(PostTableMap::COL_CATEGORY_ID)) {
            $criteria->add(PostTableMap::COL_CATEGORY_ID, $this->category_id);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_URL)) {
            $criteria->add(PostTableMap::COL_POST_URL, $this->post_url);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_TITLE)) {
            $criteria->add(PostTableMap::COL_POST_TITLE, $this->post_title);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_CONTENT)) {
            $criteria->add(PostTableMap::COL_POST_CONTENT, $this->post_content);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_ILLUSTRATION_VERSION)) {
            $criteria->add(PostTableMap::COL_POST_ILLUSTRATION_VERSION, $this->post_illustration_version);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_ILLUSTRATION_LEGEND)) {
            $criteria->add(PostTableMap::COL_POST_ILLUSTRATION_LEGEND, $this->post_illustration_legend);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_SELECTED)) {
            $criteria->add(PostTableMap::COL_POST_SELECTED, $this->post_selected);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_LINK)) {
            $criteria->add(PostTableMap::COL_POST_LINK, $this->post_link);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_STATUS)) {
            $criteria->add(PostTableMap::COL_POST_STATUS, $this->post_status);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_KEYWORDS)) {
            $criteria->add(PostTableMap::COL_POST_KEYWORDS, $this->post_keywords);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_LINKS)) {
            $criteria->add(PostTableMap::COL_POST_LINKS, $this->post_links);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_KEYWORDS_GENERATED)) {
            $criteria->add(PostTableMap::COL_POST_KEYWORDS_GENERATED, $this->post_keywords_generated);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_FB_ID)) {
            $criteria->add(PostTableMap::COL_POST_FB_ID, $this->post_fb_id);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_DATE)) {
            $criteria->add(PostTableMap::COL_POST_DATE, $this->post_date);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_HITS)) {
            $criteria->add(PostTableMap::COL_POST_HITS, $this->post_hits);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_INSERT)) {
            $criteria->add(PostTableMap::COL_POST_INSERT, $this->post_insert);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_UPDATE)) {
            $criteria->add(PostTableMap::COL_POST_UPDATE, $this->post_update);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_CREATED)) {
            $criteria->add(PostTableMap::COL_POST_CREATED, $this->post_created);
        }
        if ($this->isColumnModified(PostTableMap::COL_POST_UPDATED)) {
            $criteria->add(PostTableMap::COL_POST_UPDATED, $this->post_updated);
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
        $criteria = ChildPostQuery::create();
        $criteria->add(PostTableMap::COL_POST_ID, $this->post_id);

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
     * Generic method to set the primary key (post_id column).
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
     * @param object $copyObj An object of \Model\Post (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setAxysUserId($this->getAxysUserId());
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setCategoryId($this->getCategoryId());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setContent($this->getContent());
        $copyObj->setIllustrationVersion($this->getIllustrationVersion());
        $copyObj->setIllustrationLegend($this->getIllustrationLegend());
        $copyObj->setSelected($this->getSelected());
        $copyObj->setLink($this->getLink());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setKeywords($this->getKeywords());
        $copyObj->setLinks($this->getLinks());
        $copyObj->setKeywordsGenerated($this->getKeywordsGenerated());
        $copyObj->setFbId($this->getFbId());
        $copyObj->setDate($this->getDate());
        $copyObj->setHits($this->getHits());
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
     * @return \Model\Post Clone of current object.
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
        $this->post_id = null;
        $this->axys_user_id = null;
        $this->site_id = null;
        $this->publisher_id = null;
        $this->category_id = null;
        $this->post_url = null;
        $this->post_title = null;
        $this->post_content = null;
        $this->post_illustration_version = null;
        $this->post_illustration_legend = null;
        $this->post_selected = null;
        $this->post_link = null;
        $this->post_status = null;
        $this->post_keywords = null;
        $this->post_links = null;
        $this->post_keywords_generated = null;
        $this->post_fb_id = null;
        $this->post_date = null;
        $this->post_hits = null;
        $this->post_insert = null;
        $this->post_update = null;
        $this->post_created = null;
        $this->post_updated = null;
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

        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PostTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PostTableMap::COL_POST_UPDATED] = true;

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
