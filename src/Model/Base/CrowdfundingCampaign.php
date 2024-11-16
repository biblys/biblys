<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\CrowdfundingCampaign as ChildCrowdfundingCampaign;
use Model\CrowdfundingCampaignQuery as ChildCrowdfundingCampaignQuery;
use Model\CrowfundingReward as ChildCrowfundingReward;
use Model\CrowfundingRewardQuery as ChildCrowfundingRewardQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\Map\CrowdfundingCampaignTableMap;
use Model\Map\CrowfundingRewardTableMap;
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
 * Base class that represents a row from the 'cf_campaigns' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class CrowdfundingCampaign implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\CrowdfundingCampaignTableMap';


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
     * The value for the campaign_id field.
     *
     * @var        int
     */
    protected $campaign_id;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the campaign_title field.
     *
     * @var        string|null
     */
    protected $campaign_title;

    /**
     * The value for the campaign_url field.
     *
     * @var        string|null
     */
    protected $campaign_url;

    /**
     * The value for the campaign_description field.
     *
     * @var        string|null
     */
    protected $campaign_description;

    /**
     * The value for the campaign_image field.
     *
     * @var        string|null
     */
    protected $campaign_image;

    /**
     * The value for the campaign_goal field.
     *
     * @var        int|null
     */
    protected $campaign_goal;

    /**
     * The value for the campaign_pledged field.
     *
     * @var        int|null
     */
    protected $campaign_pledged;

    /**
     * The value for the campaign_backers field.
     *
     * @var        int|null
     */
    protected $campaign_backers;

    /**
     * The value for the campaign_starts field.
     *
     * @var        DateTime|null
     */
    protected $campaign_starts;

    /**
     * The value for the campaign_ends field.
     *
     * @var        DateTime|null
     */
    protected $campaign_ends;

    /**
     * The value for the campaign_created field.
     *
     * @var        DateTime|null
     */
    protected $campaign_created;

    /**
     * The value for the campaign_updated field.
     *
     * @var        DateTime|null
     */
    protected $campaign_updated;

    /**
     * @var        ChildSite
     */
    protected $aSite;

    /**
     * @var        ObjectCollection|ChildCrowfundingReward[] Collection to store aggregation of ChildCrowfundingReward objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildCrowfundingReward> Collection to store aggregation of ChildCrowfundingReward objects.
     */
    protected $collCrowfundingRewards;
    protected $collCrowfundingRewardsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCrowfundingReward[]
     * @phpstan-var ObjectCollection&\Traversable<ChildCrowfundingReward>
     */
    protected $crowfundingRewardsScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Base\CrowdfundingCampaign object.
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
     * Compares this with another <code>CrowdfundingCampaign</code> instance.  If
     * <code>obj</code> is an instance of <code>CrowdfundingCampaign</code>, delegates to
     * <code>equals(CrowdfundingCampaign)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [campaign_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->campaign_id;
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
     * Get the [campaign_title] column value.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->campaign_title;
    }

    /**
     * Get the [campaign_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->campaign_url;
    }

    /**
     * Get the [campaign_description] column value.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->campaign_description;
    }

    /**
     * Get the [campaign_image] column value.
     *
     * @return string|null
     */
    public function getImage()
    {
        return $this->campaign_image;
    }

    /**
     * Get the [campaign_goal] column value.
     *
     * @return int|null
     */
    public function getGoal()
    {
        return $this->campaign_goal;
    }

    /**
     * Get the [campaign_pledged] column value.
     *
     * @return int|null
     */
    public function getPledged()
    {
        return $this->campaign_pledged;
    }

    /**
     * Get the [campaign_backers] column value.
     *
     * @return int|null
     */
    public function getBackers()
    {
        return $this->campaign_backers;
    }

    /**
     * Get the [optionally formatted] temporal [campaign_starts] column value.
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
    public function getStarts($format = null)
    {
        if ($format === null) {
            return $this->campaign_starts;
        } else {
            return $this->campaign_starts instanceof \DateTimeInterface ? $this->campaign_starts->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [campaign_ends] column value.
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
    public function getEnds($format = null)
    {
        if ($format === null) {
            return $this->campaign_ends;
        } else {
            return $this->campaign_ends instanceof \DateTimeInterface ? $this->campaign_ends->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [campaign_created] column value.
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
            return $this->campaign_created;
        } else {
            return $this->campaign_created instanceof \DateTimeInterface ? $this->campaign_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [campaign_updated] column value.
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
            return $this->campaign_updated;
        } else {
            return $this->campaign_updated instanceof \DateTimeInterface ? $this->campaign_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [campaign_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->campaign_id !== $v) {
            $this->campaign_id = $v;
            $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID] = true;
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
            $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    }

    /**
     * Set the value of [campaign_title] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->campaign_title !== $v) {
            $this->campaign_title = $v;
            $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_TITLE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [campaign_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->campaign_url !== $v) {
            $this->campaign_url = $v;
            $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [campaign_description] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->campaign_description !== $v) {
            $this->campaign_description = $v;
            $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_DESCRIPTION] = true;
        }

        return $this;
    }

    /**
     * Set the value of [campaign_image] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setImage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->campaign_image !== $v) {
            $this->campaign_image = $v;
            $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_IMAGE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [campaign_goal] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setGoal($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->campaign_goal !== $v) {
            $this->campaign_goal = $v;
            $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [campaign_pledged] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPledged($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->campaign_pledged !== $v) {
            $this->campaign_pledged = $v;
            $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED] = true;
        }

        return $this;
    }

    /**
     * Set the value of [campaign_backers] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setBackers($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->campaign_backers !== $v) {
            $this->campaign_backers = $v;
            $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [campaign_starts] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setStarts($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->campaign_starts !== null || $dt !== null) {
            if ($this->campaign_starts === null || $dt === null || $dt->format("Y-m-d") !== $this->campaign_starts->format("Y-m-d")) {
                $this->campaign_starts = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [campaign_ends] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setEnds($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->campaign_ends !== null || $dt !== null) {
            if ($this->campaign_ends === null || $dt === null || $dt->format("Y-m-d") !== $this->campaign_ends->format("Y-m-d")) {
                $this->campaign_ends = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [campaign_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->campaign_created !== null || $dt !== null) {
            if ($this->campaign_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->campaign_created->format("Y-m-d H:i:s.u")) {
                $this->campaign_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [campaign_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->campaign_updated !== null || $dt !== null) {
            if ($this->campaign_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->campaign_updated->format("Y-m-d H:i:s.u")) {
                $this->campaign_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->campaign_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->campaign_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->campaign_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->campaign_description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('Image', TableMap::TYPE_PHPNAME, $indexType)];
            $this->campaign_image = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('Goal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->campaign_goal = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('Pledged', TableMap::TYPE_PHPNAME, $indexType)];
            $this->campaign_pledged = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('Backers', TableMap::TYPE_PHPNAME, $indexType)];
            $this->campaign_backers = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('Starts', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->campaign_starts = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('Ends', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->campaign_ends = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->campaign_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : CrowdfundingCampaignTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->campaign_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = CrowdfundingCampaignTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\CrowdfundingCampaign'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(CrowdfundingCampaignTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCrowdfundingCampaignQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSite = null;
            $this->collCrowfundingRewards = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see CrowdfundingCampaign::setDeleted()
     * @see CrowdfundingCampaign::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CrowdfundingCampaignTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCrowdfundingCampaignQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CrowdfundingCampaignTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED)) {
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
                CrowdfundingCampaignTableMap::addInstanceToPool($this);
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

            if ($this->crowfundingRewardsScheduledForDeletion !== null) {
                if (!$this->crowfundingRewardsScheduledForDeletion->isEmpty()) {
                    foreach ($this->crowfundingRewardsScheduledForDeletion as $crowfundingReward) {
                        // need to save related object because we set the relation to null
                        $crowfundingReward->save($con);
                    }
                    $this->crowfundingRewardsScheduledForDeletion = null;
                }
            }

            if ($this->collCrowfundingRewards !== null) {
                foreach ($this->collCrowfundingRewards as $referrerFK) {
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

        $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID] = true;
        if (null !== $this->campaign_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_id';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_title';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_URL)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_url';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_description';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_IMAGE)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_image';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_goal';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_pledged';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_backers';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_starts';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_ends';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_created';
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_updated';
        }

        $sql = sprintf(
            'INSERT INTO cf_campaigns (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'campaign_id':
                        $stmt->bindValue($identifier, $this->campaign_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'campaign_title':
                        $stmt->bindValue($identifier, $this->campaign_title, PDO::PARAM_STR);

                        break;
                    case 'campaign_url':
                        $stmt->bindValue($identifier, $this->campaign_url, PDO::PARAM_STR);

                        break;
                    case 'campaign_description':
                        $stmt->bindValue($identifier, $this->campaign_description, PDO::PARAM_STR);

                        break;
                    case 'campaign_image':
                        $stmt->bindValue($identifier, $this->campaign_image, PDO::PARAM_STR);

                        break;
                    case 'campaign_goal':
                        $stmt->bindValue($identifier, $this->campaign_goal, PDO::PARAM_INT);

                        break;
                    case 'campaign_pledged':
                        $stmt->bindValue($identifier, $this->campaign_pledged, PDO::PARAM_INT);

                        break;
                    case 'campaign_backers':
                        $stmt->bindValue($identifier, $this->campaign_backers, PDO::PARAM_INT);

                        break;
                    case 'campaign_starts':
                        $stmt->bindValue($identifier, $this->campaign_starts ? $this->campaign_starts->format("Y-m-d") : null, PDO::PARAM_STR);

                        break;
                    case 'campaign_ends':
                        $stmt->bindValue($identifier, $this->campaign_ends ? $this->campaign_ends->format("Y-m-d") : null, PDO::PARAM_STR);

                        break;
                    case 'campaign_created':
                        $stmt->bindValue($identifier, $this->campaign_created ? $this->campaign_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'campaign_updated':
                        $stmt->bindValue($identifier, $this->campaign_updated ? $this->campaign_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = CrowdfundingCampaignTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getTitle();

            case 3:
                return $this->getUrl();

            case 4:
                return $this->getDescription();

            case 5:
                return $this->getImage();

            case 6:
                return $this->getGoal();

            case 7:
                return $this->getPledged();

            case 8:
                return $this->getBackers();

            case 9:
                return $this->getStarts();

            case 10:
                return $this->getEnds();

            case 11:
                return $this->getCreatedAt();

            case 12:
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
        if (isset($alreadyDumpedObjects['CrowdfundingCampaign'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['CrowdfundingCampaign'][$this->hashCode()] = true;
        $keys = CrowdfundingCampaignTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getUrl(),
            $keys[4] => $this->getDescription(),
            $keys[5] => $this->getImage(),
            $keys[6] => $this->getGoal(),
            $keys[7] => $this->getPledged(),
            $keys[8] => $this->getBackers(),
            $keys[9] => $this->getStarts(),
            $keys[10] => $this->getEnds(),
            $keys[11] => $this->getCreatedAt(),
            $keys[12] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[9]] instanceof \DateTimeInterface) {
            $result[$keys[9]] = $result[$keys[9]]->format('Y-m-d');
        }

        if ($result[$keys[10]] instanceof \DateTimeInterface) {
            $result[$keys[10]] = $result[$keys[10]]->format('Y-m-d');
        }

        if ($result[$keys[11]] instanceof \DateTimeInterface) {
            $result[$keys[11]] = $result[$keys[11]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[12]] instanceof \DateTimeInterface) {
            $result[$keys[12]] = $result[$keys[12]]->format('Y-m-d H:i:s.u');
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
            if (null !== $this->collCrowfundingRewards) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'crowfundingRewards';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cf_rewardss';
                        break;
                    default:
                        $key = 'CrowfundingRewards';
                }

                $result[$key] = $this->collCrowfundingRewards->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = CrowdfundingCampaignTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setTitle($value);
                break;
            case 3:
                $this->setUrl($value);
                break;
            case 4:
                $this->setDescription($value);
                break;
            case 5:
                $this->setImage($value);
                break;
            case 6:
                $this->setGoal($value);
                break;
            case 7:
                $this->setPledged($value);
                break;
            case 8:
                $this->setBackers($value);
                break;
            case 9:
                $this->setStarts($value);
                break;
            case 10:
                $this->setEnds($value);
                break;
            case 11:
                $this->setCreatedAt($value);
                break;
            case 12:
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
        $keys = CrowdfundingCampaignTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTitle($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUrl($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDescription($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setImage($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setGoal($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setPledged($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setBackers($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setStarts($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setEnds($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setCreatedAt($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setUpdatedAt($arr[$keys[12]]);
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
        $criteria = new Criteria(CrowdfundingCampaignTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID, $this->campaign_id);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_SITE_ID)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_TITLE)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_TITLE, $this->campaign_title);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_URL)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_URL, $this->campaign_url);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_DESCRIPTION)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_DESCRIPTION, $this->campaign_description);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_IMAGE)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_IMAGE, $this->campaign_image);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL, $this->campaign_goal);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED, $this->campaign_pledged);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS, $this->campaign_backers);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS, $this->campaign_starts);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS, $this->campaign_ends);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED, $this->campaign_created);
        }
        if ($this->isColumnModified(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED)) {
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED, $this->campaign_updated);
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
        $criteria = ChildCrowdfundingCampaignQuery::create();
        $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID, $this->campaign_id);

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
     * Generic method to set the primary key (campaign_id column).
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
     * @param object $copyObj An object of \Model\CrowdfundingCampaign (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setImage($this->getImage());
        $copyObj->setGoal($this->getGoal());
        $copyObj->setPledged($this->getPledged());
        $copyObj->setBackers($this->getBackers());
        $copyObj->setStarts($this->getStarts());
        $copyObj->setEnds($this->getEnds());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCrowfundingRewards() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCrowfundingReward($relObj->copy($deepCopy));
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
     * @return \Model\CrowdfundingCampaign Clone of current object.
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
            $v->addCrowdfundingCampaign($this);
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
                $this->aSite->addCrowdfundingCampaigns($this);
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
        if ('CrowfundingReward' === $relationName) {
            $this->initCrowfundingRewards();
            return;
        }
    }

    /**
     * Clears out the collCrowfundingRewards collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addCrowfundingRewards()
     */
    public function clearCrowfundingRewards()
    {
        $this->collCrowfundingRewards = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collCrowfundingRewards collection loaded partially.
     *
     * @return void
     */
    public function resetPartialCrowfundingRewards($v = true): void
    {
        $this->collCrowfundingRewardsPartial = $v;
    }

    /**
     * Initializes the collCrowfundingRewards collection.
     *
     * By default this just sets the collCrowfundingRewards collection to an empty array (like clearcollCrowfundingRewards());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCrowfundingRewards(bool $overrideExisting = true): void
    {
        if (null !== $this->collCrowfundingRewards && !$overrideExisting) {
            return;
        }

        $collectionClassName = CrowfundingRewardTableMap::getTableMap()->getCollectionClassName();

        $this->collCrowfundingRewards = new $collectionClassName;
        $this->collCrowfundingRewards->setModel('\Model\CrowfundingReward');
    }

    /**
     * Gets an array of ChildCrowfundingReward objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCrowdfundingCampaign is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCrowfundingReward[] List of ChildCrowfundingReward objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCrowfundingReward> List of ChildCrowfundingReward objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCrowfundingRewards(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collCrowfundingRewardsPartial && !$this->isNew();
        if (null === $this->collCrowfundingRewards || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCrowfundingRewards) {
                    $this->initCrowfundingRewards();
                } else {
                    $collectionClassName = CrowfundingRewardTableMap::getTableMap()->getCollectionClassName();

                    $collCrowfundingRewards = new $collectionClassName;
                    $collCrowfundingRewards->setModel('\Model\CrowfundingReward');

                    return $collCrowfundingRewards;
                }
            } else {
                $collCrowfundingRewards = ChildCrowfundingRewardQuery::create(null, $criteria)
                    ->filterByCrowdfundingCampaign($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCrowfundingRewardsPartial && count($collCrowfundingRewards)) {
                        $this->initCrowfundingRewards(false);

                        foreach ($collCrowfundingRewards as $obj) {
                            if (false == $this->collCrowfundingRewards->contains($obj)) {
                                $this->collCrowfundingRewards->append($obj);
                            }
                        }

                        $this->collCrowfundingRewardsPartial = true;
                    }

                    return $collCrowfundingRewards;
                }

                if ($partial && $this->collCrowfundingRewards) {
                    foreach ($this->collCrowfundingRewards as $obj) {
                        if ($obj->isNew()) {
                            $collCrowfundingRewards[] = $obj;
                        }
                    }
                }

                $this->collCrowfundingRewards = $collCrowfundingRewards;
                $this->collCrowfundingRewardsPartial = false;
            }
        }

        return $this->collCrowfundingRewards;
    }

    /**
     * Sets a collection of ChildCrowfundingReward objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $crowfundingRewards A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setCrowfundingRewards(Collection $crowfundingRewards, ?ConnectionInterface $con = null)
    {
        /** @var ChildCrowfundingReward[] $crowfundingRewardsToDelete */
        $crowfundingRewardsToDelete = $this->getCrowfundingRewards(new Criteria(), $con)->diff($crowfundingRewards);


        $this->crowfundingRewardsScheduledForDeletion = $crowfundingRewardsToDelete;

        foreach ($crowfundingRewardsToDelete as $crowfundingRewardRemoved) {
            $crowfundingRewardRemoved->setCrowdfundingCampaign(null);
        }

        $this->collCrowfundingRewards = null;
        foreach ($crowfundingRewards as $crowfundingReward) {
            $this->addCrowfundingReward($crowfundingReward);
        }

        $this->collCrowfundingRewards = $crowfundingRewards;
        $this->collCrowfundingRewardsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CrowfundingReward objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related CrowfundingReward objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countCrowfundingRewards(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collCrowfundingRewardsPartial && !$this->isNew();
        if (null === $this->collCrowfundingRewards || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCrowfundingRewards) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCrowfundingRewards());
            }

            $query = ChildCrowfundingRewardQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCrowdfundingCampaign($this)
                ->count($con);
        }

        return count($this->collCrowfundingRewards);
    }

    /**
     * Method called to associate a ChildCrowfundingReward object to this object
     * through the ChildCrowfundingReward foreign key attribute.
     *
     * @param ChildCrowfundingReward $l ChildCrowfundingReward
     * @return $this The current object (for fluent API support)
     */
    public function addCrowfundingReward(ChildCrowfundingReward $l)
    {
        if ($this->collCrowfundingRewards === null) {
            $this->initCrowfundingRewards();
            $this->collCrowfundingRewardsPartial = true;
        }

        if (!$this->collCrowfundingRewards->contains($l)) {
            $this->doAddCrowfundingReward($l);

            if ($this->crowfundingRewardsScheduledForDeletion and $this->crowfundingRewardsScheduledForDeletion->contains($l)) {
                $this->crowfundingRewardsScheduledForDeletion->remove($this->crowfundingRewardsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCrowfundingReward $crowfundingReward The ChildCrowfundingReward object to add.
     */
    protected function doAddCrowfundingReward(ChildCrowfundingReward $crowfundingReward): void
    {
        $this->collCrowfundingRewards[]= $crowfundingReward;
        $crowfundingReward->setCrowdfundingCampaign($this);
    }

    /**
     * @param ChildCrowfundingReward $crowfundingReward The ChildCrowfundingReward object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeCrowfundingReward(ChildCrowfundingReward $crowfundingReward)
    {
        if ($this->getCrowfundingRewards()->contains($crowfundingReward)) {
            $pos = $this->collCrowfundingRewards->search($crowfundingReward);
            $this->collCrowfundingRewards->remove($pos);
            if (null === $this->crowfundingRewardsScheduledForDeletion) {
                $this->crowfundingRewardsScheduledForDeletion = clone $this->collCrowfundingRewards;
                $this->crowfundingRewardsScheduledForDeletion->clear();
            }
            $this->crowfundingRewardsScheduledForDeletion[]= $crowfundingReward;
            $crowfundingReward->setCrowdfundingCampaign(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this CrowdfundingCampaign is new, it will return
     * an empty collection; or if this CrowdfundingCampaign has previously
     * been saved, it will retrieve related CrowfundingRewards from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in CrowdfundingCampaign.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCrowfundingReward[] List of ChildCrowfundingReward objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCrowfundingReward}> List of ChildCrowfundingReward objects
     */
    public function getCrowfundingRewardsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCrowfundingRewardQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getCrowfundingRewards($query, $con);
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
            $this->aSite->removeCrowdfundingCampaign($this);
        }
        $this->campaign_id = null;
        $this->site_id = null;
        $this->campaign_title = null;
        $this->campaign_url = null;
        $this->campaign_description = null;
        $this->campaign_image = null;
        $this->campaign_goal = null;
        $this->campaign_pledged = null;
        $this->campaign_backers = null;
        $this->campaign_starts = null;
        $this->campaign_ends = null;
        $this->campaign_created = null;
        $this->campaign_updated = null;
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
            if ($this->collCrowfundingRewards) {
                foreach ($this->collCrowfundingRewards as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCrowfundingRewards = null;
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
        return (string) $this->exportTo(CrowdfundingCampaignTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED] = true;

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
