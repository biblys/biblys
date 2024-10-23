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
use Model\Event as ChildEvent;
use Model\EventQuery as ChildEventQuery;
use Model\Image as ChildImage;
use Model\ImageQuery as ChildImageQuery;
use Model\Map\EventTableMap;
use Model\Map\ImageTableMap;
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
 * Base class that represents a row from the 'events' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Event implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\EventTableMap';


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
     * The value for the event_id field.
     *
     * @var        int
     */
    protected $event_id;

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
     * The value for the event_url field.
     *
     * @var        string|null
     */
    protected $event_url;

    /**
     * The value for the event_title field.
     *
     * @var        string|null
     */
    protected $event_title;

    /**
     * The value for the event_subtitle field.
     *
     * @var        string|null
     */
    protected $event_subtitle;

    /**
     * The value for the event_desc field.
     *
     * @var        string|null
     */
    protected $event_desc;

    /**
     * The value for the event_location field.
     *
     * @var        string|null
     */
    protected $event_location;

    /**
     * The value for the event_illustration_legend field.
     *
     * @var        string|null
     */
    protected $event_illustration_legend;

    /**
     * The value for the event_highlighted field.
     *
     * @var        boolean|null
     */
    protected $event_highlighted;

    /**
     * The value for the event_start field.
     *
     * @var        DateTime|null
     */
    protected $event_start;

    /**
     * The value for the event_end field.
     *
     * @var        DateTime|null
     */
    protected $event_end;

    /**
     * The value for the event_date field.
     *
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        DateTime
     */
    protected $event_date;

    /**
     * The value for the event_status field.
     *
     * @var        boolean|null
     */
    protected $event_status;

    /**
     * The value for the event_insert_ field.
     *
     * @var        DateTime|null
     */
    protected $event_insert_;

    /**
     * The value for the event_update_ field.
     *
     * @var        DateTime|null
     */
    protected $event_update_;

    /**
     * The value for the event_created field.
     *
     * @var        DateTime|null
     */
    protected $event_created;

    /**
     * The value for the event_updated field.
     *
     * @var        DateTime|null
     */
    protected $event_updated;

    /**
     * @var        ObjectCollection|ChildImage[] Collection to store aggregation of ChildImage objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildImage> Collection to store aggregation of ChildImage objects.
     */
    protected $collImages;
    protected $collImagesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildImage[]
     * @phpstan-var ObjectCollection&\Traversable<ChildImage>
     */
    protected $imagesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
    }

    /**
     * Initializes internal state of Model\Base\Event object.
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
     * Compares this with another <code>Event</code> instance.  If
     * <code>obj</code> is an instance of <code>Event</code>, delegates to
     * <code>equals(Event)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [event_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->event_id;
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
     * Get the [event_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->event_url;
    }

    /**
     * Get the [event_title] column value.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->event_title;
    }

    /**
     * Get the [event_subtitle] column value.
     *
     * @return string|null
     */
    public function getSubtitle()
    {
        return $this->event_subtitle;
    }

    /**
     * Get the [event_desc] column value.
     *
     * @return string|null
     */
    public function getDesc()
    {
        return $this->event_desc;
    }

    /**
     * Get the [event_location] column value.
     *
     * @return string|null
     */
    public function getLocation()
    {
        return $this->event_location;
    }

    /**
     * Get the [event_illustration_legend] column value.
     *
     * @return string|null
     */
    public function getIllustrationLegend()
    {
        return $this->event_illustration_legend;
    }

    /**
     * Get the [event_highlighted] column value.
     *
     * @return boolean|null
     */
    public function getHighlighted()
    {
        return $this->event_highlighted;
    }

    /**
     * Get the [event_highlighted] column value.
     *
     * @return boolean|null
     */
    public function isHighlighted()
    {
        return $this->getHighlighted();
    }

    /**
     * Get the [optionally formatted] temporal [event_start] column value.
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
    public function getStart($format = null)
    {
        if ($format === null) {
            return $this->event_start;
        } else {
            return $this->event_start instanceof \DateTimeInterface ? $this->event_start->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [event_end] column value.
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
    public function getEnd($format = null)
    {
        if ($format === null) {
            return $this->event_end;
        } else {
            return $this->event_end instanceof \DateTimeInterface ? $this->event_end->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [event_date] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), and 0 if column value is 0000-00-00 00:00:00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime : string)
     */
    public function getDate($format = null)
    {
        if ($format === null) {
            return $this->event_date;
        } else {
            return $this->event_date instanceof \DateTimeInterface ? $this->event_date->format($format) : null;
        }
    }

    /**
     * Get the [event_status] column value.
     *
     * @return boolean|null
     */
    public function getStatus()
    {
        return $this->event_status;
    }

    /**
     * Get the [event_status] column value.
     *
     * @return boolean|null
     */
    public function isStatus()
    {
        return $this->getStatus();
    }

    /**
     * Get the [optionally formatted] temporal [event_insert_] column value.
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
            return $this->event_insert_;
        } else {
            return $this->event_insert_ instanceof \DateTimeInterface ? $this->event_insert_->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [event_update_] column value.
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
            return $this->event_update_;
        } else {
            return $this->event_update_ instanceof \DateTimeInterface ? $this->event_update_->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [event_created] column value.
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
            return $this->event_created;
        } else {
            return $this->event_created instanceof \DateTimeInterface ? $this->event_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [event_updated] column value.
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
            return $this->event_updated;
        } else {
            return $this->event_updated instanceof \DateTimeInterface ? $this->event_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [event_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_id !== $v) {
            $this->event_id = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENT_ID] = true;
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
            $this->modifiedColumns[EventTableMap::COL_SITE_ID] = true;
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
            $this->modifiedColumns[EventTableMap::COL_PUBLISHER_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setBookshopId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->bookshop_id !== $v) {
            $this->bookshop_id = $v;
            $this->modifiedColumns[EventTableMap::COL_BOOKSHOP_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLibraryId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->library_id !== $v) {
            $this->library_id = $v;
            $this->modifiedColumns[EventTableMap::COL_LIBRARY_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [event_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_url !== $v) {
            $this->event_url = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENT_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [event_title] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_title !== $v) {
            $this->event_title = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENT_TITLE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [event_subtitle] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSubtitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_subtitle !== $v) {
            $this->event_subtitle = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENT_SUBTITLE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [event_desc] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_desc !== $v) {
            $this->event_desc = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENT_DESC] = true;
        }

        return $this;
    }

    /**
     * Set the value of [event_location] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLocation($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_location !== $v) {
            $this->event_location = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENT_LOCATION] = true;
        }

        return $this;
    }

    /**
     * Set the value of [event_illustration_legend] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setIllustrationLegend($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_illustration_legend !== $v) {
            $this->event_illustration_legend = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENT_ILLUSTRATION_LEGEND] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [event_highlighted] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setHighlighted($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->event_highlighted !== $v) {
            $this->event_highlighted = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENT_HIGHLIGHTED] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [event_start] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setStart($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_start !== null || $dt !== null) {
            if ($this->event_start === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->event_start->format("Y-m-d H:i:s.u")) {
                $this->event_start = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_EVENT_START] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [event_end] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setEnd($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_end !== null || $dt !== null) {
            if ($this->event_end === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->event_end->format("Y-m-d H:i:s.u")) {
                $this->event_end = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_EVENT_END] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [event_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_date !== null || $dt !== null) {
            if ($this->event_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->event_date->format("Y-m-d H:i:s.u")) {
                $this->event_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_EVENT_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of the [event_status] column.
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

        if ($this->event_status !== $v) {
            $this->event_status = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENT_STATUS] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [event_insert_] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_insert_ !== null || $dt !== null) {
            if ($this->event_insert_ === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->event_insert_->format("Y-m-d H:i:s.u")) {
                $this->event_insert_ = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_EVENT_INSERT_] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [event_update_] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_update_ !== null || $dt !== null) {
            if ($this->event_update_ === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->event_update_->format("Y-m-d H:i:s.u")) {
                $this->event_update_ = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_EVENT_UPDATE_] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [event_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_created !== null || $dt !== null) {
            if ($this->event_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->event_created->format("Y-m-d H:i:s.u")) {
                $this->event_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_EVENT_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [event_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_updated !== null || $dt !== null) {
            if ($this->event_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->event_updated->format("Y-m-d H:i:s.u")) {
                $this->event_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_EVENT_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventTableMap::translateFieldName('PublisherId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventTableMap::translateFieldName('BookshopId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventTableMap::translateFieldName('LibraryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EventTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EventTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : EventTableMap::translateFieldName('Subtitle', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_subtitle = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : EventTableMap::translateFieldName('Desc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_desc = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : EventTableMap::translateFieldName('Location', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_location = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : EventTableMap::translateFieldName('IllustrationLegend', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_illustration_legend = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : EventTableMap::translateFieldName('Highlighted', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_highlighted = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : EventTableMap::translateFieldName('Start', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->event_start = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : EventTableMap::translateFieldName('End', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->event_end = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : EventTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->event_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : EventTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_status = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : EventTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->event_insert_ = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : EventTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->event_update_ = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : EventTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->event_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : EventTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->event_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 20; // 20 = EventTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Event'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collImages = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Event::setDeleted()
     * @see Event::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(EventTableMap::COL_EVENT_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(EventTableMap::COL_EVENT_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(EventTableMap::COL_EVENT_UPDATED)) {
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
                EventTableMap::addInstanceToPool($this);
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

            if ($this->imagesScheduledForDeletion !== null) {
                if (!$this->imagesScheduledForDeletion->isEmpty()) {
                    foreach ($this->imagesScheduledForDeletion as $image) {
                        // need to save related object because we set the relation to null
                        $image->save($con);
                    }
                    $this->imagesScheduledForDeletion = null;
                }
            }

            if ($this->collImages !== null) {
                foreach ($this->collImages as $referrerFK) {
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

        $this->modifiedColumns[EventTableMap::COL_EVENT_ID] = true;
        if (null !== $this->event_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventTableMap::COL_EVENT_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventTableMap::COL_EVENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'event_id';
        }
        if ($this->isColumnModified(EventTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(EventTableMap::COL_PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_id';
        }
        if ($this->isColumnModified(EventTableMap::COL_BOOKSHOP_ID)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_id';
        }
        if ($this->isColumnModified(EventTableMap::COL_LIBRARY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'library_id';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_URL)) {
            $modifiedColumns[':p' . $index++]  = 'event_url';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'event_title';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_SUBTITLE)) {
            $modifiedColumns[':p' . $index++]  = 'event_subtitle';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_DESC)) {
            $modifiedColumns[':p' . $index++]  = 'event_desc';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_LOCATION)) {
            $modifiedColumns[':p' . $index++]  = 'event_location';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_ILLUSTRATION_LEGEND)) {
            $modifiedColumns[':p' . $index++]  = 'event_illustration_legend';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_HIGHLIGHTED)) {
            $modifiedColumns[':p' . $index++]  = 'event_highlighted';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_START)) {
            $modifiedColumns[':p' . $index++]  = 'event_start';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_END)) {
            $modifiedColumns[':p' . $index++]  = 'event_end';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'event_date';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'event_status';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_INSERT_)) {
            $modifiedColumns[':p' . $index++]  = 'event_insert_';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_UPDATE_)) {
            $modifiedColumns[':p' . $index++]  = 'event_update_';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'event_created';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'event_updated';
        }

        $sql = sprintf(
            'INSERT INTO events (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'event_id':
                        $stmt->bindValue($identifier, $this->event_id, PDO::PARAM_INT);

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
                    case 'event_url':
                        $stmt->bindValue($identifier, $this->event_url, PDO::PARAM_STR);

                        break;
                    case 'event_title':
                        $stmt->bindValue($identifier, $this->event_title, PDO::PARAM_STR);

                        break;
                    case 'event_subtitle':
                        $stmt->bindValue($identifier, $this->event_subtitle, PDO::PARAM_STR);

                        break;
                    case 'event_desc':
                        $stmt->bindValue($identifier, $this->event_desc, PDO::PARAM_STR);

                        break;
                    case 'event_location':
                        $stmt->bindValue($identifier, $this->event_location, PDO::PARAM_STR);

                        break;
                    case 'event_illustration_legend':
                        $stmt->bindValue($identifier, $this->event_illustration_legend, PDO::PARAM_STR);

                        break;
                    case 'event_highlighted':
                        $stmt->bindValue($identifier, (int) $this->event_highlighted, PDO::PARAM_INT);

                        break;
                    case 'event_start':
                        $stmt->bindValue($identifier, $this->event_start ? $this->event_start->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'event_end':
                        $stmt->bindValue($identifier, $this->event_end ? $this->event_end->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'event_date':
                        $stmt->bindValue($identifier, $this->event_date ? $this->event_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'event_status':
                        $stmt->bindValue($identifier, (int) $this->event_status, PDO::PARAM_INT);

                        break;
                    case 'event_insert_':
                        $stmt->bindValue($identifier, $this->event_insert_ ? $this->event_insert_->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'event_update_':
                        $stmt->bindValue($identifier, $this->event_update_ ? $this->event_update_->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'event_created':
                        $stmt->bindValue($identifier, $this->event_created ? $this->event_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'event_updated':
                        $stmt->bindValue($identifier, $this->event_updated ? $this->event_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getBookshopId();

            case 4:
                return $this->getLibraryId();

            case 5:
                return $this->getUrl();

            case 6:
                return $this->getTitle();

            case 7:
                return $this->getSubtitle();

            case 8:
                return $this->getDesc();

            case 9:
                return $this->getLocation();

            case 10:
                return $this->getIllustrationLegend();

            case 11:
                return $this->getHighlighted();

            case 12:
                return $this->getStart();

            case 13:
                return $this->getEnd();

            case 14:
                return $this->getDate();

            case 15:
                return $this->getStatus();

            case 16:
                return $this->getInsert();

            case 17:
                return $this->getUpdate();

            case 18:
                return $this->getCreatedAt();

            case 19:
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
        if (isset($alreadyDumpedObjects['Event'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Event'][$this->hashCode()] = true;
        $keys = EventTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getPublisherId(),
            $keys[3] => $this->getBookshopId(),
            $keys[4] => $this->getLibraryId(),
            $keys[5] => $this->getUrl(),
            $keys[6] => $this->getTitle(),
            $keys[7] => $this->getSubtitle(),
            $keys[8] => $this->getDesc(),
            $keys[9] => $this->getLocation(),
            $keys[10] => $this->getIllustrationLegend(),
            $keys[11] => $this->getHighlighted(),
            $keys[12] => $this->getStart(),
            $keys[13] => $this->getEnd(),
            $keys[14] => $this->getDate(),
            $keys[15] => $this->getStatus(),
            $keys[16] => $this->getInsert(),
            $keys[17] => $this->getUpdate(),
            $keys[18] => $this->getCreatedAt(),
            $keys[19] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[12]] instanceof \DateTimeInterface) {
            $result[$keys[12]] = $result[$keys[12]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[13]] instanceof \DateTimeInterface) {
            $result[$keys[13]] = $result[$keys[13]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[14]] instanceof \DateTimeInterface) {
            $result[$keys[14]] = $result[$keys[14]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[16]] instanceof \DateTimeInterface) {
            $result[$keys[16]] = $result[$keys[16]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[17]] instanceof \DateTimeInterface) {
            $result[$keys[17]] = $result[$keys[17]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[18]] instanceof \DateTimeInterface) {
            $result[$keys[18]] = $result[$keys[18]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[19]] instanceof \DateTimeInterface) {
            $result[$keys[19]] = $result[$keys[19]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collImages) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'images';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'imagess';
                        break;
                    default:
                        $key = 'Images';
                }

                $result[$key] = $this->collImages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setBookshopId($value);
                break;
            case 4:
                $this->setLibraryId($value);
                break;
            case 5:
                $this->setUrl($value);
                break;
            case 6:
                $this->setTitle($value);
                break;
            case 7:
                $this->setSubtitle($value);
                break;
            case 8:
                $this->setDesc($value);
                break;
            case 9:
                $this->setLocation($value);
                break;
            case 10:
                $this->setIllustrationLegend($value);
                break;
            case 11:
                $this->setHighlighted($value);
                break;
            case 12:
                $this->setStart($value);
                break;
            case 13:
                $this->setEnd($value);
                break;
            case 14:
                $this->setDate($value);
                break;
            case 15:
                $this->setStatus($value);
                break;
            case 16:
                $this->setInsert($value);
                break;
            case 17:
                $this->setUpdate($value);
                break;
            case 18:
                $this->setCreatedAt($value);
                break;
            case 19:
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
        $keys = EventTableMap::getFieldNames($keyType);

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
            $this->setBookshopId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setLibraryId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setUrl($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setTitle($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSubtitle($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setDesc($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setLocation($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setIllustrationLegend($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setHighlighted($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setStart($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setEnd($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setDate($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setStatus($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setInsert($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setUpdate($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setCreatedAt($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setUpdatedAt($arr[$keys[19]]);
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
        $criteria = new Criteria(EventTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventTableMap::COL_EVENT_ID)) {
            $criteria->add(EventTableMap::COL_EVENT_ID, $this->event_id);
        }
        if ($this->isColumnModified(EventTableMap::COL_SITE_ID)) {
            $criteria->add(EventTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(EventTableMap::COL_PUBLISHER_ID)) {
            $criteria->add(EventTableMap::COL_PUBLISHER_ID, $this->publisher_id);
        }
        if ($this->isColumnModified(EventTableMap::COL_BOOKSHOP_ID)) {
            $criteria->add(EventTableMap::COL_BOOKSHOP_ID, $this->bookshop_id);
        }
        if ($this->isColumnModified(EventTableMap::COL_LIBRARY_ID)) {
            $criteria->add(EventTableMap::COL_LIBRARY_ID, $this->library_id);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_URL)) {
            $criteria->add(EventTableMap::COL_EVENT_URL, $this->event_url);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_TITLE)) {
            $criteria->add(EventTableMap::COL_EVENT_TITLE, $this->event_title);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_SUBTITLE)) {
            $criteria->add(EventTableMap::COL_EVENT_SUBTITLE, $this->event_subtitle);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_DESC)) {
            $criteria->add(EventTableMap::COL_EVENT_DESC, $this->event_desc);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_LOCATION)) {
            $criteria->add(EventTableMap::COL_EVENT_LOCATION, $this->event_location);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_ILLUSTRATION_LEGEND)) {
            $criteria->add(EventTableMap::COL_EVENT_ILLUSTRATION_LEGEND, $this->event_illustration_legend);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_HIGHLIGHTED)) {
            $criteria->add(EventTableMap::COL_EVENT_HIGHLIGHTED, $this->event_highlighted);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_START)) {
            $criteria->add(EventTableMap::COL_EVENT_START, $this->event_start);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_END)) {
            $criteria->add(EventTableMap::COL_EVENT_END, $this->event_end);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_DATE)) {
            $criteria->add(EventTableMap::COL_EVENT_DATE, $this->event_date);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_STATUS)) {
            $criteria->add(EventTableMap::COL_EVENT_STATUS, $this->event_status);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_INSERT_)) {
            $criteria->add(EventTableMap::COL_EVENT_INSERT_, $this->event_insert_);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_UPDATE_)) {
            $criteria->add(EventTableMap::COL_EVENT_UPDATE_, $this->event_update_);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_CREATED)) {
            $criteria->add(EventTableMap::COL_EVENT_CREATED, $this->event_created);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENT_UPDATED)) {
            $criteria->add(EventTableMap::COL_EVENT_UPDATED, $this->event_updated);
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
        $criteria = ChildEventQuery::create();
        $criteria->add(EventTableMap::COL_EVENT_ID, $this->event_id);

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
     * Generic method to set the primary key (event_id column).
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
     * @param object $copyObj An object of \Model\Event (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setBookshopId($this->getBookshopId());
        $copyObj->setLibraryId($this->getLibraryId());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setSubtitle($this->getSubtitle());
        $copyObj->setDesc($this->getDesc());
        $copyObj->setLocation($this->getLocation());
        $copyObj->setIllustrationLegend($this->getIllustrationLegend());
        $copyObj->setHighlighted($this->getHighlighted());
        $copyObj->setStart($this->getStart());
        $copyObj->setEnd($this->getEnd());
        $copyObj->setDate($this->getDate());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setInsert($this->getInsert());
        $copyObj->setUpdate($this->getUpdate());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getImages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addImage($relObj->copy($deepCopy));
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
     * @return \Model\Event Clone of current object.
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
        if ('Image' === $relationName) {
            $this->initImages();
            return;
        }
    }

    /**
     * Clears out the collImages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addImages()
     */
    public function clearImages()
    {
        $this->collImages = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collImages collection loaded partially.
     *
     * @return void
     */
    public function resetPartialImages($v = true): void
    {
        $this->collImagesPartial = $v;
    }

    /**
     * Initializes the collImages collection.
     *
     * By default this just sets the collImages collection to an empty array (like clearcollImages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initImages(bool $overrideExisting = true): void
    {
        if (null !== $this->collImages && !$overrideExisting) {
            return;
        }

        $collectionClassName = ImageTableMap::getTableMap()->getCollectionClassName();

        $this->collImages = new $collectionClassName;
        $this->collImages->setModel('\Model\Image');
    }

    /**
     * Gets an array of ChildImage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage> List of ChildImage objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getImages(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collImagesPartial && !$this->isNew();
        if (null === $this->collImages || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collImages) {
                    $this->initImages();
                } else {
                    $collectionClassName = ImageTableMap::getTableMap()->getCollectionClassName();

                    $collImages = new $collectionClassName;
                    $collImages->setModel('\Model\Image');

                    return $collImages;
                }
            } else {
                $collImages = ChildImageQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collImagesPartial && count($collImages)) {
                        $this->initImages(false);

                        foreach ($collImages as $obj) {
                            if (false == $this->collImages->contains($obj)) {
                                $this->collImages->append($obj);
                            }
                        }

                        $this->collImagesPartial = true;
                    }

                    return $collImages;
                }

                if ($partial && $this->collImages) {
                    foreach ($this->collImages as $obj) {
                        if ($obj->isNew()) {
                            $collImages[] = $obj;
                        }
                    }
                }

                $this->collImages = $collImages;
                $this->collImagesPartial = false;
            }
        }

        return $this->collImages;
    }

    /**
     * Sets a collection of ChildImage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $images A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setImages(Collection $images, ?ConnectionInterface $con = null)
    {
        /** @var ChildImage[] $imagesToDelete */
        $imagesToDelete = $this->getImages(new Criteria(), $con)->diff($images);


        $this->imagesScheduledForDeletion = $imagesToDelete;

        foreach ($imagesToDelete as $imageRemoved) {
            $imageRemoved->setEvent(null);
        }

        $this->collImages = null;
        foreach ($images as $image) {
            $this->addImage($image);
        }

        $this->collImages = $images;
        $this->collImagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Image objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Image objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countImages(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collImagesPartial && !$this->isNew();
        if (null === $this->collImages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collImages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getImages());
            }

            $query = ChildImageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collImages);
    }

    /**
     * Method called to associate a ChildImage object to this object
     * through the ChildImage foreign key attribute.
     *
     * @param ChildImage $l ChildImage
     * @return $this The current object (for fluent API support)
     */
    public function addImage(ChildImage $l)
    {
        if ($this->collImages === null) {
            $this->initImages();
            $this->collImagesPartial = true;
        }

        if (!$this->collImages->contains($l)) {
            $this->doAddImage($l);

            if ($this->imagesScheduledForDeletion and $this->imagesScheduledForDeletion->contains($l)) {
                $this->imagesScheduledForDeletion->remove($this->imagesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildImage $image The ChildImage object to add.
     */
    protected function doAddImage(ChildImage $image): void
    {
        $this->collImages[]= $image;
        $image->setEvent($this);
    }

    /**
     * @param ChildImage $image The ChildImage object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeImage(ChildImage $image)
    {
        if ($this->getImages()->contains($image)) {
            $pos = $this->collImages->search($image);
            $this->collImages->remove($pos);
            if (null === $this->imagesScheduledForDeletion) {
                $this->imagesScheduledForDeletion = clone $this->collImages;
                $this->imagesScheduledForDeletion->clear();
            }
            $this->imagesScheduledForDeletion[]= $image;
            $image->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinStockItem(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('StockItem', $joinBehavior);

        return $this->getImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinContributor(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Contributor', $joinBehavior);

        return $this->getImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinPost(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Post', $joinBehavior);

        return $this->getImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinPublisher(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Publisher', $joinBehavior);

        return $this->getImages($query, $con);
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
        $this->event_id = null;
        $this->site_id = null;
        $this->publisher_id = null;
        $this->bookshop_id = null;
        $this->library_id = null;
        $this->event_url = null;
        $this->event_title = null;
        $this->event_subtitle = null;
        $this->event_desc = null;
        $this->event_location = null;
        $this->event_illustration_legend = null;
        $this->event_highlighted = null;
        $this->event_start = null;
        $this->event_end = null;
        $this->event_date = null;
        $this->event_status = null;
        $this->event_insert_ = null;
        $this->event_update_ = null;
        $this->event_created = null;
        $this->event_updated = null;
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
            if ($this->collImages) {
                foreach ($this->collImages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collImages = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[EventTableMap::COL_EVENT_UPDATED] = true;

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
