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
use Model\CrowdfundingCampaign as ChildCrowdfundingCampaign;
use Model\CrowdfundingCampaignQuery as ChildCrowdfundingCampaignQuery;
use Model\CrowfundingRewardQuery as ChildCrowfundingRewardQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\Map\CrowfundingRewardTableMap;
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
 * Base class that represents a row from the 'cf_rewards' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class CrowfundingReward implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\CrowfundingRewardTableMap';


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
     * The value for the reward_id field.
     *
     * @var        int
     */
    protected $reward_id;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the campaign_id field.
     *
     * @var        int|null
     */
    protected $campaign_id;

    /**
     * The value for the reward_content field.
     *
     * @var        string|null
     */
    protected $reward_content;

    /**
     * The value for the reward_articles field.
     *
     * @var        string|null
     */
    protected $reward_articles;

    /**
     * The value for the reward_price field.
     *
     * @var        int|null
     */
    protected $reward_price;

    /**
     * The value for the reward_limited field.
     *
     * @var        boolean|null
     */
    protected $reward_limited;

    /**
     * The value for the reward_highlighted field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $reward_highlighted;

    /**
     * The value for the reward_image field.
     *
     * @var        string|null
     */
    protected $reward_image;

    /**
     * The value for the reward_quantity field.
     *
     * @var        int|null
     */
    protected $reward_quantity;

    /**
     * The value for the reward_backers field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $reward_backers;

    /**
     * The value for the reward_created field.
     *
     * @var        DateTime|null
     */
    protected $reward_created;

    /**
     * The value for the reward_updated field.
     *
     * @var        DateTime|null
     */
    protected $reward_updated;

    /**
     * @var        ChildSite
     */
    protected $aSite;

    /**
     * @var        ChildCrowdfundingCampaign
     */
    protected $aCrowdfundingCampaign;

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
        $this->reward_highlighted = false;
        $this->reward_backers = 0;
    }

    /**
     * Initializes internal state of Model\Base\CrowfundingReward object.
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
     * Compares this with another <code>CrowfundingReward</code> instance.  If
     * <code>obj</code> is an instance of <code>CrowfundingReward</code>, delegates to
     * <code>equals(CrowfundingReward)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [reward_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->reward_id;
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
     * Get the [campaign_id] column value.
     *
     * @return int|null
     */
    public function getCampaignId()
    {
        return $this->campaign_id;
    }

    /**
     * Get the [reward_content] column value.
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->reward_content;
    }

    /**
     * Get the [reward_articles] column value.
     *
     * @return string|null
     */
    public function getArticles()
    {
        return $this->reward_articles;
    }

    /**
     * Get the [reward_price] column value.
     *
     * @return int|null
     */
    public function getPrice()
    {
        return $this->reward_price;
    }

    /**
     * Get the [reward_limited] column value.
     *
     * @return boolean|null
     */
    public function getLimited()
    {
        return $this->reward_limited;
    }

    /**
     * Get the [reward_limited] column value.
     *
     * @return boolean|null
     */
    public function isLimited()
    {
        return $this->getLimited();
    }

    /**
     * Get the [reward_highlighted] column value.
     *
     * @return boolean|null
     */
    public function getHighlighted()
    {
        return $this->reward_highlighted;
    }

    /**
     * Get the [reward_highlighted] column value.
     *
     * @return boolean|null
     */
    public function isHighlighted()
    {
        return $this->getHighlighted();
    }

    /**
     * Get the [reward_image] column value.
     *
     * @return string|null
     */
    public function getImage()
    {
        return $this->reward_image;
    }

    /**
     * Get the [reward_quantity] column value.
     *
     * @return int|null
     */
    public function getQuantity()
    {
        return $this->reward_quantity;
    }

    /**
     * Get the [reward_backers] column value.
     *
     * @return int|null
     */
    public function getBackers()
    {
        return $this->reward_backers;
    }

    /**
     * Get the [optionally formatted] temporal [reward_created] column value.
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
            return $this->reward_created;
        } else {
            return $this->reward_created instanceof \DateTimeInterface ? $this->reward_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [reward_updated] column value.
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
            return $this->reward_updated;
        } else {
            return $this->reward_updated instanceof \DateTimeInterface ? $this->reward_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [reward_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reward_id !== $v) {
            $this->reward_id = $v;
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_ID] = true;
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
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    }

    /**
     * Set the value of [campaign_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCampaignId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->campaign_id !== $v) {
            $this->campaign_id = $v;
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_CAMPAIGN_ID] = true;
        }

        if ($this->aCrowdfundingCampaign !== null && $this->aCrowdfundingCampaign->getId() !== $v) {
            $this->aCrowdfundingCampaign = null;
        }

        return $this;
    }

    /**
     * Set the value of [reward_content] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setContent($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->reward_content !== $v) {
            $this->reward_content = $v;
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_CONTENT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [reward_articles] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setArticles($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->reward_articles !== $v) {
            $this->reward_articles = $v;
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_ARTICLES] = true;
        }

        return $this;
    }

    /**
     * Set the value of [reward_price] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPrice($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reward_price !== $v) {
            $this->reward_price = $v;
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_PRICE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [reward_limited] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setLimited($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->reward_limited !== $v) {
            $this->reward_limited = $v;
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_LIMITED] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [reward_highlighted] column.
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

        if ($this->reward_highlighted !== $v) {
            $this->reward_highlighted = $v;
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_HIGHLIGHTED] = true;
        }

        return $this;
    }

    /**
     * Set the value of [reward_image] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setImage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->reward_image !== $v) {
            $this->reward_image = $v;
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_IMAGE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [reward_quantity] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setQuantity($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reward_quantity !== $v) {
            $this->reward_quantity = $v;
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_QUANTITY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [reward_backers] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setBackers($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reward_backers !== $v) {
            $this->reward_backers = $v;
            $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_BACKERS] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [reward_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->reward_created !== null || $dt !== null) {
            if ($this->reward_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->reward_created->format("Y-m-d H:i:s.u")) {
                $this->reward_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [reward_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->reward_updated !== null || $dt !== null) {
            if ($this->reward_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->reward_updated->format("Y-m-d H:i:s.u")) {
                $this->reward_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_UPDATED] = true;
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
            if ($this->reward_highlighted !== false) {
                return false;
            }

            if ($this->reward_backers !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CrowfundingRewardTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reward_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CrowfundingRewardTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CrowfundingRewardTableMap::translateFieldName('CampaignId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->campaign_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CrowfundingRewardTableMap::translateFieldName('Content', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reward_content = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CrowfundingRewardTableMap::translateFieldName('Articles', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reward_articles = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CrowfundingRewardTableMap::translateFieldName('Price', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reward_price = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CrowfundingRewardTableMap::translateFieldName('Limited', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reward_limited = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : CrowfundingRewardTableMap::translateFieldName('Highlighted', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reward_highlighted = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : CrowfundingRewardTableMap::translateFieldName('Image', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reward_image = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : CrowfundingRewardTableMap::translateFieldName('Quantity', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reward_quantity = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : CrowfundingRewardTableMap::translateFieldName('Backers', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reward_backers = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : CrowfundingRewardTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->reward_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : CrowfundingRewardTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->reward_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = CrowfundingRewardTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\CrowfundingReward'), 0, $e);
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
        if ($this->aCrowdfundingCampaign !== null && $this->campaign_id !== $this->aCrowdfundingCampaign->getId()) {
            $this->aCrowdfundingCampaign = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(CrowfundingRewardTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCrowfundingRewardQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSite = null;
            $this->aCrowdfundingCampaign = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see CrowfundingReward::setDeleted()
     * @see CrowfundingReward::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CrowfundingRewardTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCrowfundingRewardQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CrowfundingRewardTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_UPDATED)) {
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
                CrowfundingRewardTableMap::addInstanceToPool($this);
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

            if ($this->aCrowdfundingCampaign !== null) {
                if ($this->aCrowdfundingCampaign->isModified() || $this->aCrowdfundingCampaign->isNew()) {
                    $affectedRows += $this->aCrowdfundingCampaign->save($con);
                }
                $this->setCrowdfundingCampaign($this->aCrowdfundingCampaign);
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

        $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_ID] = true;
        if (null !== $this->reward_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CrowfundingRewardTableMap::COL_REWARD_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_ID)) {
            $modifiedColumns[':p' . $index++]  = 'reward_id';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_CAMPAIGN_ID)) {
            $modifiedColumns[':p' . $index++]  = 'campaign_id';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_CONTENT)) {
            $modifiedColumns[':p' . $index++]  = 'reward_content';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_ARTICLES)) {
            $modifiedColumns[':p' . $index++]  = 'reward_articles';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_PRICE)) {
            $modifiedColumns[':p' . $index++]  = 'reward_price';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_LIMITED)) {
            $modifiedColumns[':p' . $index++]  = 'reward_limited';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_HIGHLIGHTED)) {
            $modifiedColumns[':p' . $index++]  = 'reward_highlighted';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_IMAGE)) {
            $modifiedColumns[':p' . $index++]  = 'reward_image';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_QUANTITY)) {
            $modifiedColumns[':p' . $index++]  = 'reward_quantity';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_BACKERS)) {
            $modifiedColumns[':p' . $index++]  = 'reward_backers';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'reward_created';
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'reward_updated';
        }

        $sql = sprintf(
            'INSERT INTO cf_rewards (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'reward_id':
                        $stmt->bindValue($identifier, $this->reward_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'campaign_id':
                        $stmt->bindValue($identifier, $this->campaign_id, PDO::PARAM_INT);

                        break;
                    case 'reward_content':
                        $stmt->bindValue($identifier, $this->reward_content, PDO::PARAM_STR);

                        break;
                    case 'reward_articles':
                        $stmt->bindValue($identifier, $this->reward_articles, PDO::PARAM_STR);

                        break;
                    case 'reward_price':
                        $stmt->bindValue($identifier, $this->reward_price, PDO::PARAM_INT);

                        break;
                    case 'reward_limited':
                        $stmt->bindValue($identifier, (int) $this->reward_limited, PDO::PARAM_INT);

                        break;
                    case 'reward_highlighted':
                        $stmt->bindValue($identifier, (int) $this->reward_highlighted, PDO::PARAM_INT);

                        break;
                    case 'reward_image':
                        $stmt->bindValue($identifier, $this->reward_image, PDO::PARAM_STR);

                        break;
                    case 'reward_quantity':
                        $stmt->bindValue($identifier, $this->reward_quantity, PDO::PARAM_INT);

                        break;
                    case 'reward_backers':
                        $stmt->bindValue($identifier, $this->reward_backers, PDO::PARAM_INT);

                        break;
                    case 'reward_created':
                        $stmt->bindValue($identifier, $this->reward_created ? $this->reward_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'reward_updated':
                        $stmt->bindValue($identifier, $this->reward_updated ? $this->reward_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = CrowfundingRewardTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getCampaignId();

            case 3:
                return $this->getContent();

            case 4:
                return $this->getArticles();

            case 5:
                return $this->getPrice();

            case 6:
                return $this->getLimited();

            case 7:
                return $this->getHighlighted();

            case 8:
                return $this->getImage();

            case 9:
                return $this->getQuantity();

            case 10:
                return $this->getBackers();

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
        if (isset($alreadyDumpedObjects['CrowfundingReward'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['CrowfundingReward'][$this->hashCode()] = true;
        $keys = CrowfundingRewardTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getCampaignId(),
            $keys[3] => $this->getContent(),
            $keys[4] => $this->getArticles(),
            $keys[5] => $this->getPrice(),
            $keys[6] => $this->getLimited(),
            $keys[7] => $this->getHighlighted(),
            $keys[8] => $this->getImage(),
            $keys[9] => $this->getQuantity(),
            $keys[10] => $this->getBackers(),
            $keys[11] => $this->getCreatedAt(),
            $keys[12] => $this->getUpdatedAt(),
        ];
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
            if (null !== $this->aCrowdfundingCampaign) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'crowdfundingCampaign';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cf_campaigns';
                        break;
                    default:
                        $key = 'CrowdfundingCampaign';
                }

                $result[$key] = $this->aCrowdfundingCampaign->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = CrowfundingRewardTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setCampaignId($value);
                break;
            case 3:
                $this->setContent($value);
                break;
            case 4:
                $this->setArticles($value);
                break;
            case 5:
                $this->setPrice($value);
                break;
            case 6:
                $this->setLimited($value);
                break;
            case 7:
                $this->setHighlighted($value);
                break;
            case 8:
                $this->setImage($value);
                break;
            case 9:
                $this->setQuantity($value);
                break;
            case 10:
                $this->setBackers($value);
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
        $keys = CrowfundingRewardTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCampaignId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setContent($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setArticles($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setPrice($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setLimited($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setHighlighted($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setImage($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setQuantity($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setBackers($arr[$keys[10]]);
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
        $criteria = new Criteria(CrowfundingRewardTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_ID)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_ID, $this->reward_id);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_SITE_ID)) {
            $criteria->add(CrowfundingRewardTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_CAMPAIGN_ID)) {
            $criteria->add(CrowfundingRewardTableMap::COL_CAMPAIGN_ID, $this->campaign_id);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_CONTENT)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_CONTENT, $this->reward_content);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_ARTICLES)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_ARTICLES, $this->reward_articles);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_PRICE)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_PRICE, $this->reward_price);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_LIMITED)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_LIMITED, $this->reward_limited);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_HIGHLIGHTED)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_HIGHLIGHTED, $this->reward_highlighted);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_IMAGE)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_IMAGE, $this->reward_image);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_QUANTITY)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_QUANTITY, $this->reward_quantity);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_BACKERS)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_BACKERS, $this->reward_backers);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_CREATED)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_CREATED, $this->reward_created);
        }
        if ($this->isColumnModified(CrowfundingRewardTableMap::COL_REWARD_UPDATED)) {
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_UPDATED, $this->reward_updated);
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
        $criteria = ChildCrowfundingRewardQuery::create();
        $criteria->add(CrowfundingRewardTableMap::COL_REWARD_ID, $this->reward_id);

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
     * Generic method to set the primary key (reward_id column).
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
     * @param object $copyObj An object of \Model\CrowfundingReward (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setCampaignId($this->getCampaignId());
        $copyObj->setContent($this->getContent());
        $copyObj->setArticles($this->getArticles());
        $copyObj->setPrice($this->getPrice());
        $copyObj->setLimited($this->getLimited());
        $copyObj->setHighlighted($this->getHighlighted());
        $copyObj->setImage($this->getImage());
        $copyObj->setQuantity($this->getQuantity());
        $copyObj->setBackers($this->getBackers());
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
     * @return \Model\CrowfundingReward Clone of current object.
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
            $v->addCrowfundingReward($this);
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
                $this->aSite->addCrowfundingRewards($this);
             */
        }

        return $this->aSite;
    }

    /**
     * Declares an association between this object and a ChildCrowdfundingCampaign object.
     *
     * @param ChildCrowdfundingCampaign|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setCrowdfundingCampaign(ChildCrowdfundingCampaign $v = null)
    {
        if ($v === null) {
            $this->setCampaignId(NULL);
        } else {
            $this->setCampaignId($v->getId());
        }

        $this->aCrowdfundingCampaign = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCrowdfundingCampaign object, it will not be re-added.
        if ($v !== null) {
            $v->addCrowfundingReward($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCrowdfundingCampaign object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildCrowdfundingCampaign|null The associated ChildCrowdfundingCampaign object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCrowdfundingCampaign(?ConnectionInterface $con = null)
    {
        if ($this->aCrowdfundingCampaign === null && ($this->campaign_id != 0)) {
            $this->aCrowdfundingCampaign = ChildCrowdfundingCampaignQuery::create()->findPk($this->campaign_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCrowdfundingCampaign->addCrowfundingRewards($this);
             */
        }

        return $this->aCrowdfundingCampaign;
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
            $this->aSite->removeCrowfundingReward($this);
        }
        if (null !== $this->aCrowdfundingCampaign) {
            $this->aCrowdfundingCampaign->removeCrowfundingReward($this);
        }
        $this->reward_id = null;
        $this->site_id = null;
        $this->campaign_id = null;
        $this->reward_content = null;
        $this->reward_articles = null;
        $this->reward_price = null;
        $this->reward_limited = null;
        $this->reward_highlighted = null;
        $this->reward_image = null;
        $this->reward_quantity = null;
        $this->reward_backers = null;
        $this->reward_created = null;
        $this->reward_updated = null;
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

        $this->aSite = null;
        $this->aCrowdfundingCampaign = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CrowfundingRewardTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[CrowfundingRewardTableMap::COL_REWARD_UPDATED] = true;

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
