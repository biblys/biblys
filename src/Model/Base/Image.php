<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Article as ChildArticle;
use Model\ArticleQuery as ChildArticleQuery;
use Model\Event as ChildEvent;
use Model\EventQuery as ChildEventQuery;
use Model\ImageQuery as ChildImageQuery;
use Model\People as ChildPeople;
use Model\PeopleQuery as ChildPeopleQuery;
use Model\Post as ChildPost;
use Model\PostQuery as ChildPostQuery;
use Model\Publisher as ChildPublisher;
use Model\PublisherQuery as ChildPublisherQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\Stock as ChildStock;
use Model\StockQuery as ChildStockQuery;
use Model\Map\ImageTableMap;
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
 * Base class that represents a row from the 'images' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Image implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\ImageTableMap';


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
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the type field.
     *
     * @var        string|null
     */
    protected $type;

    /**
     * The value for the filepath field.
     *
     * @var        string|null
     */
    protected $filepath;

    /**
     * The value for the filename field.
     *
     * @var        string|null
     */
    protected $filename;

    /**
     * The value for the version field.
     *
     * @var        int|null
     */
    protected $version;

    /**
     * The value for the mediatype field.
     *
     * @var        string|null
     */
    protected $mediatype;

    /**
     * The value for the filesize field.
     *
     * @var        string|null
     */
    protected $filesize;

    /**
     * The value for the height field.
     *
     * @var        int|null
     */
    protected $height;

    /**
     * The value for the width field.
     *
     * @var        int|null
     */
    protected $width;

    /**
     * The value for the article_id field.
     *
     * @var        int|null
     */
    protected $article_id;

    /**
     * The value for the stock_item_id field.
     *
     * @var        int|null
     */
    protected $stock_item_id;

    /**
     * The value for the contributor_id field.
     *
     * @var        int|null
     */
    protected $contributor_id;

    /**
     * The value for the post_id field.
     *
     * @var        int|null
     */
    protected $post_id;

    /**
     * The value for the event_id field.
     *
     * @var        int|null
     */
    protected $event_id;

    /**
     * The value for the publisher_id field.
     *
     * @var        int|null
     */
    protected $publisher_id;

    /**
     * The value for the uploaded_at field.
     *
     * @var        DateTime|null
     */
    protected $uploaded_at;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime|null
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime|null
     */
    protected $updated_at;

    /**
     * @var        ChildSite
     */
    protected $aSite;

    /**
     * @var        ChildArticle
     */
    protected $aArticle;

    /**
     * @var        ChildStock
     */
    protected $aStockItem;

    /**
     * @var        ChildPeople
     */
    protected $aContributor;

    /**
     * @var        ChildPost
     */
    protected $aPost;

    /**
     * @var        ChildEvent
     */
    protected $aEvent;

    /**
     * @var        ChildPublisher
     */
    protected $aPublisher;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of Model\Base\Image object.
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
     * Compares this with another <code>Image</code> instance.  If
     * <code>obj</code> is an instance of <code>Image</code>, delegates to
     * <code>equals(Image)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [site_id] column value.
     *
     * @return int|null
     */
    public function getSiteId()
    {
        return $this->site_id;
    }

    /**
     * Get the [type] column value.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the [filepath] column value.
     *
     * @return string|null
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Get the [filename] column value.
     *
     * @return string|null
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Get the [version] column value.
     *
     * @return int|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get the [mediatype] column value.
     *
     * @return string|null
     */
    public function getMediatype()
    {
        return $this->mediatype;
    }

    /**
     * Get the [filesize] column value.
     *
     * @return string|null
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Get the [height] column value.
     *
     * @return int|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Get the [width] column value.
     *
     * @return int|null
     */
    public function getWidth()
    {
        return $this->width;
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
     * Get the [stock_item_id] column value.
     *
     * @return int|null
     */
    public function getStockItemId()
    {
        return $this->stock_item_id;
    }

    /**
     * Get the [contributor_id] column value.
     *
     * @return int|null
     */
    public function getContributorId()
    {
        return $this->contributor_id;
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
     * Get the [event_id] column value.
     *
     * @return int|null
     */
    public function getEventId()
    {
        return $this->event_id;
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
     * Get the [optionally formatted] temporal [uploaded_at] column value.
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
    public function getuploaded_at($format = null)
    {
        if ($format === null) {
            return $this->uploaded_at;
        } else {
            return $this->uploaded_at instanceof \DateTimeInterface ? $this->uploaded_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
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
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
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
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
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
            $this->modifiedColumns[ImageTableMap::COL_ID] = true;
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
            $this->modifiedColumns[ImageTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    }

    /**
     * Set the value of [type] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[ImageTableMap::COL_TYPE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [filepath] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFilepath($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->filepath !== $v) {
            $this->filepath = $v;
            $this->modifiedColumns[ImageTableMap::COL_FILEPATH] = true;
        }

        return $this;
    }

    /**
     * Set the value of [filename] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFilename($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->filename !== $v) {
            $this->filename = $v;
            $this->modifiedColumns[ImageTableMap::COL_FILENAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [version] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->version !== $v) {
            $this->version = $v;
            $this->modifiedColumns[ImageTableMap::COL_VERSION] = true;
        }

        return $this;
    }

    /**
     * Set the value of [mediatype] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMediatype($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mediatype !== $v) {
            $this->mediatype = $v;
            $this->modifiedColumns[ImageTableMap::COL_MEDIATYPE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [filesize] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFilesize($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->filesize !== $v) {
            $this->filesize = $v;
            $this->modifiedColumns[ImageTableMap::COL_FILESIZE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [height] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setHeight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->height !== $v) {
            $this->height = $v;
            $this->modifiedColumns[ImageTableMap::COL_HEIGHT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [width] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setWidth($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->width !== $v) {
            $this->width = $v;
            $this->modifiedColumns[ImageTableMap::COL_WIDTH] = true;
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
            $this->modifiedColumns[ImageTableMap::COL_ARTICLE_ID] = true;
        }

        if ($this->aArticle !== null && $this->aArticle->getId() !== $v) {
            $this->aArticle = null;
        }

        return $this;
    }

    /**
     * Set the value of [stock_item_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setStockItemId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->stock_item_id !== $v) {
            $this->stock_item_id = $v;
            $this->modifiedColumns[ImageTableMap::COL_STOCK_ITEM_ID] = true;
        }

        if ($this->aStockItem !== null && $this->aStockItem->getId() !== $v) {
            $this->aStockItem = null;
        }

        return $this;
    }

    /**
     * Set the value of [contributor_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setContributorId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->contributor_id !== $v) {
            $this->contributor_id = $v;
            $this->modifiedColumns[ImageTableMap::COL_CONTRIBUTOR_ID] = true;
        }

        if ($this->aContributor !== null && $this->aContributor->getId() !== $v) {
            $this->aContributor = null;
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
            $this->modifiedColumns[ImageTableMap::COL_POST_ID] = true;
        }

        if ($this->aPost !== null && $this->aPost->getId() !== $v) {
            $this->aPost = null;
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
            $this->modifiedColumns[ImageTableMap::COL_EVENT_ID] = true;
        }

        if ($this->aEvent !== null && $this->aEvent->getId() !== $v) {
            $this->aEvent = null;
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
            $this->modifiedColumns[ImageTableMap::COL_PUBLISHER_ID] = true;
        }

        if ($this->aPublisher !== null && $this->aPublisher->getId() !== $v) {
            $this->aPublisher = null;
        }

        return $this;
    }

    /**
     * Sets the value of [uploaded_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setuploaded_at($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->uploaded_at !== null || $dt !== null) {
            if ($this->uploaded_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->uploaded_at->format("Y-m-d H:i:s.u")) {
                $this->uploaded_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ImageTableMap::COL_UPLOADED_AT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ImageTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ImageTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ImageTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ImageTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ImageTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ImageTableMap::translateFieldName('Filepath', TableMap::TYPE_PHPNAME, $indexType)];
            $this->filepath = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ImageTableMap::translateFieldName('Filename', TableMap::TYPE_PHPNAME, $indexType)];
            $this->filename = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ImageTableMap::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ImageTableMap::translateFieldName('Mediatype', TableMap::TYPE_PHPNAME, $indexType)];
            $this->mediatype = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : ImageTableMap::translateFieldName('Filesize', TableMap::TYPE_PHPNAME, $indexType)];
            $this->filesize = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : ImageTableMap::translateFieldName('Height', TableMap::TYPE_PHPNAME, $indexType)];
            $this->height = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : ImageTableMap::translateFieldName('Width', TableMap::TYPE_PHPNAME, $indexType)];
            $this->width = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : ImageTableMap::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : ImageTableMap::translateFieldName('StockItemId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stock_item_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : ImageTableMap::translateFieldName('ContributorId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->contributor_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : ImageTableMap::translateFieldName('PostId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : ImageTableMap::translateFieldName('EventId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : ImageTableMap::translateFieldName('PublisherId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : ImageTableMap::translateFieldName('uploaded_at', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->uploaded_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : ImageTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : ImageTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 19; // 19 = ImageTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Image'), 0, $e);
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
        if ($this->aArticle !== null && $this->article_id !== $this->aArticle->getId()) {
            $this->aArticle = null;
        }
        if ($this->aStockItem !== null && $this->stock_item_id !== $this->aStockItem->getId()) {
            $this->aStockItem = null;
        }
        if ($this->aContributor !== null && $this->contributor_id !== $this->aContributor->getId()) {
            $this->aContributor = null;
        }
        if ($this->aPost !== null && $this->post_id !== $this->aPost->getId()) {
            $this->aPost = null;
        }
        if ($this->aEvent !== null && $this->event_id !== $this->aEvent->getId()) {
            $this->aEvent = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(ImageTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildImageQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSite = null;
            $this->aArticle = null;
            $this->aStockItem = null;
            $this->aContributor = null;
            $this->aPost = null;
            $this->aEvent = null;
            $this->aPublisher = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Image::setDeleted()
     * @see Image::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImageTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildImageQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ImageTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(ImageTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(ImageTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(ImageTableMap::COL_UPDATED_AT)) {
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
                ImageTableMap::addInstanceToPool($this);
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

            if ($this->aArticle !== null) {
                if ($this->aArticle->isModified() || $this->aArticle->isNew()) {
                    $affectedRows += $this->aArticle->save($con);
                }
                $this->setArticle($this->aArticle);
            }

            if ($this->aStockItem !== null) {
                if ($this->aStockItem->isModified() || $this->aStockItem->isNew()) {
                    $affectedRows += $this->aStockItem->save($con);
                }
                $this->setStockItem($this->aStockItem);
            }

            if ($this->aContributor !== null) {
                if ($this->aContributor->isModified() || $this->aContributor->isNew()) {
                    $affectedRows += $this->aContributor->save($con);
                }
                $this->setContributor($this->aContributor);
            }

            if ($this->aPost !== null) {
                if ($this->aPost->isModified() || $this->aPost->isNew()) {
                    $affectedRows += $this->aPost->save($con);
                }
                $this->setPost($this->aPost);
            }

            if ($this->aEvent !== null) {
                if ($this->aEvent->isModified() || $this->aEvent->isNew()) {
                    $affectedRows += $this->aEvent->save($con);
                }
                $this->setEvent($this->aEvent);
            }

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

        $this->modifiedColumns[ImageTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ImageTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ImageTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ImageTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(ImageTableMap::COL_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'type';
        }
        if ($this->isColumnModified(ImageTableMap::COL_FILEPATH)) {
            $modifiedColumns[':p' . $index++]  = 'filePath';
        }
        if ($this->isColumnModified(ImageTableMap::COL_FILENAME)) {
            $modifiedColumns[':p' . $index++]  = 'fileName';
        }
        if ($this->isColumnModified(ImageTableMap::COL_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'version';
        }
        if ($this->isColumnModified(ImageTableMap::COL_MEDIATYPE)) {
            $modifiedColumns[':p' . $index++]  = 'mediaType';
        }
        if ($this->isColumnModified(ImageTableMap::COL_FILESIZE)) {
            $modifiedColumns[':p' . $index++]  = 'fileSize';
        }
        if ($this->isColumnModified(ImageTableMap::COL_HEIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'height';
        }
        if ($this->isColumnModified(ImageTableMap::COL_WIDTH)) {
            $modifiedColumns[':p' . $index++]  = 'width';
        }
        if ($this->isColumnModified(ImageTableMap::COL_ARTICLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'article_id';
        }
        if ($this->isColumnModified(ImageTableMap::COL_STOCK_ITEM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'stock_item_id';
        }
        if ($this->isColumnModified(ImageTableMap::COL_CONTRIBUTOR_ID)) {
            $modifiedColumns[':p' . $index++]  = 'contributor_id';
        }
        if ($this->isColumnModified(ImageTableMap::COL_POST_ID)) {
            $modifiedColumns[':p' . $index++]  = 'post_id';
        }
        if ($this->isColumnModified(ImageTableMap::COL_EVENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'event_id';
        }
        if ($this->isColumnModified(ImageTableMap::COL_PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_id';
        }
        if ($this->isColumnModified(ImageTableMap::COL_UPLOADED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'uploaded_at';
        }
        if ($this->isColumnModified(ImageTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(ImageTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO images (%s) VALUES (%s)',
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
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'type':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_STR);

                        break;
                    case 'filePath':
                        $stmt->bindValue($identifier, $this->filepath, PDO::PARAM_STR);

                        break;
                    case 'fileName':
                        $stmt->bindValue($identifier, $this->filename, PDO::PARAM_STR);

                        break;
                    case 'version':
                        $stmt->bindValue($identifier, $this->version, PDO::PARAM_INT);

                        break;
                    case 'mediaType':
                        $stmt->bindValue($identifier, $this->mediatype, PDO::PARAM_STR);

                        break;
                    case 'fileSize':
                        $stmt->bindValue($identifier, $this->filesize, PDO::PARAM_INT);

                        break;
                    case 'height':
                        $stmt->bindValue($identifier, $this->height, PDO::PARAM_INT);

                        break;
                    case 'width':
                        $stmt->bindValue($identifier, $this->width, PDO::PARAM_INT);

                        break;
                    case 'article_id':
                        $stmt->bindValue($identifier, $this->article_id, PDO::PARAM_INT);

                        break;
                    case 'stock_item_id':
                        $stmt->bindValue($identifier, $this->stock_item_id, PDO::PARAM_INT);

                        break;
                    case 'contributor_id':
                        $stmt->bindValue($identifier, $this->contributor_id, PDO::PARAM_INT);

                        break;
                    case 'post_id':
                        $stmt->bindValue($identifier, $this->post_id, PDO::PARAM_INT);

                        break;
                    case 'event_id':
                        $stmt->bindValue($identifier, $this->event_id, PDO::PARAM_INT);

                        break;
                    case 'publisher_id':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);

                        break;
                    case 'uploaded_at':
                        $stmt->bindValue($identifier, $this->uploaded_at ? $this->uploaded_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = ImageTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getType();

            case 3:
                return $this->getFilepath();

            case 4:
                return $this->getFilename();

            case 5:
                return $this->getVersion();

            case 6:
                return $this->getMediatype();

            case 7:
                return $this->getFilesize();

            case 8:
                return $this->getHeight();

            case 9:
                return $this->getWidth();

            case 10:
                return $this->getArticleId();

            case 11:
                return $this->getStockItemId();

            case 12:
                return $this->getContributorId();

            case 13:
                return $this->getPostId();

            case 14:
                return $this->getEventId();

            case 15:
                return $this->getPublisherId();

            case 16:
                return $this->getuploaded_at();

            case 17:
                return $this->getCreatedAt();

            case 18:
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
        if (isset($alreadyDumpedObjects['Image'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Image'][$this->hashCode()] = true;
        $keys = ImageTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getType(),
            $keys[3] => $this->getFilepath(),
            $keys[4] => $this->getFilename(),
            $keys[5] => $this->getVersion(),
            $keys[6] => $this->getMediatype(),
            $keys[7] => $this->getFilesize(),
            $keys[8] => $this->getHeight(),
            $keys[9] => $this->getWidth(),
            $keys[10] => $this->getArticleId(),
            $keys[11] => $this->getStockItemId(),
            $keys[12] => $this->getContributorId(),
            $keys[13] => $this->getPostId(),
            $keys[14] => $this->getEventId(),
            $keys[15] => $this->getPublisherId(),
            $keys[16] => $this->getuploaded_at(),
            $keys[17] => $this->getCreatedAt(),
            $keys[18] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[16]] instanceof \DateTimeInterface) {
            $result[$keys[16]] = $result[$keys[16]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[17]] instanceof \DateTimeInterface) {
            $result[$keys[17]] = $result[$keys[17]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[18]] instanceof \DateTimeInterface) {
            $result[$keys[18]] = $result[$keys[18]]->format('Y-m-d H:i:s.u');
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
            if (null !== $this->aStockItem) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'stock';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'stock';
                        break;
                    default:
                        $key = 'StockItem';
                }

                $result[$key] = $this->aStockItem->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aContributor) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'people';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'people';
                        break;
                    default:
                        $key = 'Contributor';
                }

                $result[$key] = $this->aContributor->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPost) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'post';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'posts';
                        break;
                    default:
                        $key = 'Post';
                }

                $result[$key] = $this->aPost->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEvent) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'event';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'events';
                        break;
                    default:
                        $key = 'Event';
                }

                $result[$key] = $this->aEvent->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
        $pos = ImageTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setType($value);
                break;
            case 3:
                $this->setFilepath($value);
                break;
            case 4:
                $this->setFilename($value);
                break;
            case 5:
                $this->setVersion($value);
                break;
            case 6:
                $this->setMediatype($value);
                break;
            case 7:
                $this->setFilesize($value);
                break;
            case 8:
                $this->setHeight($value);
                break;
            case 9:
                $this->setWidth($value);
                break;
            case 10:
                $this->setArticleId($value);
                break;
            case 11:
                $this->setStockItemId($value);
                break;
            case 12:
                $this->setContributorId($value);
                break;
            case 13:
                $this->setPostId($value);
                break;
            case 14:
                $this->setEventId($value);
                break;
            case 15:
                $this->setPublisherId($value);
                break;
            case 16:
                $this->setuploaded_at($value);
                break;
            case 17:
                $this->setCreatedAt($value);
                break;
            case 18:
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
        $keys = ImageTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setType($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setFilepath($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setFilename($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setVersion($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setMediatype($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setFilesize($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setHeight($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setWidth($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setArticleId($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setStockItemId($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setContributorId($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setPostId($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setEventId($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setPublisherId($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setuploaded_at($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setCreatedAt($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setUpdatedAt($arr[$keys[18]]);
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
        $criteria = new Criteria(ImageTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ImageTableMap::COL_ID)) {
            $criteria->add(ImageTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ImageTableMap::COL_SITE_ID)) {
            $criteria->add(ImageTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(ImageTableMap::COL_TYPE)) {
            $criteria->add(ImageTableMap::COL_TYPE, $this->type);
        }
        if ($this->isColumnModified(ImageTableMap::COL_FILEPATH)) {
            $criteria->add(ImageTableMap::COL_FILEPATH, $this->filepath);
        }
        if ($this->isColumnModified(ImageTableMap::COL_FILENAME)) {
            $criteria->add(ImageTableMap::COL_FILENAME, $this->filename);
        }
        if ($this->isColumnModified(ImageTableMap::COL_VERSION)) {
            $criteria->add(ImageTableMap::COL_VERSION, $this->version);
        }
        if ($this->isColumnModified(ImageTableMap::COL_MEDIATYPE)) {
            $criteria->add(ImageTableMap::COL_MEDIATYPE, $this->mediatype);
        }
        if ($this->isColumnModified(ImageTableMap::COL_FILESIZE)) {
            $criteria->add(ImageTableMap::COL_FILESIZE, $this->filesize);
        }
        if ($this->isColumnModified(ImageTableMap::COL_HEIGHT)) {
            $criteria->add(ImageTableMap::COL_HEIGHT, $this->height);
        }
        if ($this->isColumnModified(ImageTableMap::COL_WIDTH)) {
            $criteria->add(ImageTableMap::COL_WIDTH, $this->width);
        }
        if ($this->isColumnModified(ImageTableMap::COL_ARTICLE_ID)) {
            $criteria->add(ImageTableMap::COL_ARTICLE_ID, $this->article_id);
        }
        if ($this->isColumnModified(ImageTableMap::COL_STOCK_ITEM_ID)) {
            $criteria->add(ImageTableMap::COL_STOCK_ITEM_ID, $this->stock_item_id);
        }
        if ($this->isColumnModified(ImageTableMap::COL_CONTRIBUTOR_ID)) {
            $criteria->add(ImageTableMap::COL_CONTRIBUTOR_ID, $this->contributor_id);
        }
        if ($this->isColumnModified(ImageTableMap::COL_POST_ID)) {
            $criteria->add(ImageTableMap::COL_POST_ID, $this->post_id);
        }
        if ($this->isColumnModified(ImageTableMap::COL_EVENT_ID)) {
            $criteria->add(ImageTableMap::COL_EVENT_ID, $this->event_id);
        }
        if ($this->isColumnModified(ImageTableMap::COL_PUBLISHER_ID)) {
            $criteria->add(ImageTableMap::COL_PUBLISHER_ID, $this->publisher_id);
        }
        if ($this->isColumnModified(ImageTableMap::COL_UPLOADED_AT)) {
            $criteria->add(ImageTableMap::COL_UPLOADED_AT, $this->uploaded_at);
        }
        if ($this->isColumnModified(ImageTableMap::COL_CREATED_AT)) {
            $criteria->add(ImageTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(ImageTableMap::COL_UPDATED_AT)) {
            $criteria->add(ImageTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildImageQuery::create();
        $criteria->add(ImageTableMap::COL_ID, $this->id);

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
     * @param object $copyObj An object of \Model\Image (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setType($this->getType());
        $copyObj->setFilepath($this->getFilepath());
        $copyObj->setFilename($this->getFilename());
        $copyObj->setVersion($this->getVersion());
        $copyObj->setMediatype($this->getMediatype());
        $copyObj->setFilesize($this->getFilesize());
        $copyObj->setHeight($this->getHeight());
        $copyObj->setWidth($this->getWidth());
        $copyObj->setArticleId($this->getArticleId());
        $copyObj->setStockItemId($this->getStockItemId());
        $copyObj->setContributorId($this->getContributorId());
        $copyObj->setPostId($this->getPostId());
        $copyObj->setEventId($this->getEventId());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setuploaded_at($this->getuploaded_at());
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
     * @return \Model\Image Clone of current object.
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
            $v->addImage($this);
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
                $this->aSite->addImages($this);
             */
        }

        return $this->aSite;
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
            $v->addImage($this);
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
                $this->aArticle->addImages($this);
             */
        }

        return $this->aArticle;
    }

    /**
     * Declares an association between this object and a ChildStock object.
     *
     * @param ChildStock|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setStockItem(ChildStock $v = null)
    {
        if ($v === null) {
            $this->setStockItemId(NULL);
        } else {
            $this->setStockItemId($v->getId());
        }

        $this->aStockItem = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildStock object, it will not be re-added.
        if ($v !== null) {
            $v->addImage($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildStock object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildStock|null The associated ChildStock object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getStockItem(?ConnectionInterface $con = null)
    {
        if ($this->aStockItem === null && ($this->stock_item_id != 0)) {
            $this->aStockItem = ChildStockQuery::create()->findPk($this->stock_item_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aStockItem->addImages($this);
             */
        }

        return $this->aStockItem;
    }

    /**
     * Declares an association between this object and a ChildPeople object.
     *
     * @param ChildPeople|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setContributor(ChildPeople $v = null)
    {
        if ($v === null) {
            $this->setContributorId(NULL);
        } else {
            $this->setContributorId($v->getId());
        }

        $this->aContributor = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPeople object, it will not be re-added.
        if ($v !== null) {
            $v->addImage($this);
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
    public function getContributor(?ConnectionInterface $con = null)
    {
        if ($this->aContributor === null && ($this->contributor_id != 0)) {
            $this->aContributor = ChildPeopleQuery::create()->findPk($this->contributor_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aContributor->addImages($this);
             */
        }

        return $this->aContributor;
    }

    /**
     * Declares an association between this object and a ChildPost object.
     *
     * @param ChildPost|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setPost(ChildPost $v = null)
    {
        if ($v === null) {
            $this->setPostId(NULL);
        } else {
            $this->setPostId($v->getId());
        }

        $this->aPost = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPost object, it will not be re-added.
        if ($v !== null) {
            $v->addImage($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPost object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildPost|null The associated ChildPost object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getPost(?ConnectionInterface $con = null)
    {
        if ($this->aPost === null && ($this->post_id != 0)) {
            $this->aPost = ChildPostQuery::create()->findPk($this->post_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPost->addImages($this);
             */
        }

        return $this->aPost;
    }

    /**
     * Declares an association between this object and a ChildEvent object.
     *
     * @param ChildEvent|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setEvent(ChildEvent $v = null)
    {
        if ($v === null) {
            $this->setEventId(NULL);
        } else {
            $this->setEventId($v->getId());
        }

        $this->aEvent = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEvent object, it will not be re-added.
        if ($v !== null) {
            $v->addImage($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEvent object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildEvent|null The associated ChildEvent object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getEvent(?ConnectionInterface $con = null)
    {
        if ($this->aEvent === null && ($this->event_id != 0)) {
            $this->aEvent = ChildEventQuery::create()->findPk($this->event_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEvent->addImages($this);
             */
        }

        return $this->aEvent;
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
            $v->addImage($this);
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
                $this->aPublisher->addImages($this);
             */
        }

        return $this->aPublisher;
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
            $this->aSite->removeImage($this);
        }
        if (null !== $this->aArticle) {
            $this->aArticle->removeImage($this);
        }
        if (null !== $this->aStockItem) {
            $this->aStockItem->removeImage($this);
        }
        if (null !== $this->aContributor) {
            $this->aContributor->removeImage($this);
        }
        if (null !== $this->aPost) {
            $this->aPost->removeImage($this);
        }
        if (null !== $this->aEvent) {
            $this->aEvent->removeImage($this);
        }
        if (null !== $this->aPublisher) {
            $this->aPublisher->removeImage($this);
        }
        $this->id = null;
        $this->site_id = null;
        $this->type = null;
        $this->filepath = null;
        $this->filename = null;
        $this->version = null;
        $this->mediatype = null;
        $this->filesize = null;
        $this->height = null;
        $this->width = null;
        $this->article_id = null;
        $this->stock_item_id = null;
        $this->contributor_id = null;
        $this->post_id = null;
        $this->event_id = null;
        $this->publisher_id = null;
        $this->uploaded_at = null;
        $this->created_at = null;
        $this->updated_at = null;
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

        $this->aSite = null;
        $this->aArticle = null;
        $this->aStockItem = null;
        $this->aContributor = null;
        $this->aPost = null;
        $this->aEvent = null;
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
        return (string) $this->exportTo(ImageTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[ImageTableMap::COL_UPDATED_AT] = true;

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
