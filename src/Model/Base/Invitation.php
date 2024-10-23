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
use Model\Invitation as ChildInvitation;
use Model\InvitationQuery as ChildInvitationQuery;
use Model\InvitationsArticles as ChildInvitationsArticles;
use Model\InvitationsArticlesQuery as ChildInvitationsArticlesQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\Map\InvitationTableMap;
use Model\Map\InvitationsArticlesTableMap;
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
 * Base class that represents a row from the 'invitations' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Invitation implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\InvitationTableMap';


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
     * @var        int
     */
    protected $site_id;

    /**
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the code field.
     *
     * @var        string
     */
    protected $code;

    /**
     * The value for the allows_pre_download field.
     *
     * @var        boolean|null
     */
    protected $allows_pre_download;

    /**
     * The value for the consumed_at field.
     *
     * @var        DateTime|null
     */
    protected $consumed_at;

    /**
     * The value for the expires_at field.
     *
     * @var        DateTime
     */
    protected $expires_at;

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
     * @var        ObjectCollection|ChildInvitationsArticles[] Collection to store aggregation of ChildInvitationsArticles objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildInvitationsArticles> Collection to store aggregation of ChildInvitationsArticles objects.
     */
    protected $collInvitationsArticless;
    protected $collInvitationsArticlessPartial;

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
     * @var ObjectCollection|ChildInvitationsArticles[]
     * @phpstan-var ObjectCollection&\Traversable<ChildInvitationsArticles>
     */
    protected $invitationsArticlessScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Base\Invitation object.
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
     * Compares this with another <code>Invitation</code> instance.  If
     * <code>obj</code> is an instance of <code>Invitation</code>, delegates to
     * <code>equals(Invitation)</code>.  Otherwise, returns <code>false</code>.
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
     * @return int
     */
    public function getSiteId()
    {
        return $this->site_id;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the [allows_pre_download] column value.
     *
     * @return boolean|null
     */
    public function getAllowsPreDownload()
    {
        return $this->allows_pre_download;
    }

    /**
     * Get the [allows_pre_download] column value.
     *
     * @return boolean|null
     */
    public function isAllowsPreDownload()
    {
        return $this->getAllowsPreDownload();
    }

    /**
     * Get the [optionally formatted] temporal [consumed_at] column value.
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
    public function getConsumedAt($format = null)
    {
        if ($format === null) {
            return $this->consumed_at;
        } else {
            return $this->consumed_at instanceof \DateTimeInterface ? $this->consumed_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [expires_at] column value.
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
    public function getExpiresAt($format = null)
    {
        if ($format === null) {
            return $this->expires_at;
        } else {
            return $this->expires_at instanceof \DateTimeInterface ? $this->expires_at->format($format) : null;
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
            $this->modifiedColumns[InvitationTableMap::COL_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSiteId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_id !== $v) {
            $this->site_id = $v;
            $this->modifiedColumns[InvitationTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    }

    /**
     * Set the value of [email] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[InvitationTableMap::COL_EMAIL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [code] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[InvitationTableMap::COL_CODE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [allows_pre_download] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setAllowsPreDownload($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->allows_pre_download !== $v) {
            $this->allows_pre_download = $v;
            $this->modifiedColumns[InvitationTableMap::COL_ALLOWS_PRE_DOWNLOAD] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [consumed_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setConsumedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->consumed_at !== null || $dt !== null) {
            if ($this->consumed_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->consumed_at->format("Y-m-d H:i:s.u")) {
                $this->consumed_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InvitationTableMap::COL_CONSUMED_AT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [expires_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setExpiresAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->expires_at !== null || $dt !== null) {
            if ($this->expires_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->expires_at->format("Y-m-d H:i:s.u")) {
                $this->expires_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InvitationTableMap::COL_EXPIRES_AT] = true;
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
                $this->modifiedColumns[InvitationTableMap::COL_CREATED_AT] = true;
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
                $this->modifiedColumns[InvitationTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : InvitationTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : InvitationTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : InvitationTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : InvitationTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : InvitationTableMap::translateFieldName('AllowsPreDownload', TableMap::TYPE_PHPNAME, $indexType)];
            $this->allows_pre_download = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : InvitationTableMap::translateFieldName('ConsumedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->consumed_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : InvitationTableMap::translateFieldName('ExpiresAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->expires_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : InvitationTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : InvitationTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = InvitationTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Invitation'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(InvitationTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildInvitationQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSite = null;
            $this->collInvitationsArticless = null;

            $this->collArticles = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Invitation::setDeleted()
     * @see Invitation::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvitationTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildInvitationQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(InvitationTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(InvitationTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(InvitationTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(InvitationTableMap::COL_UPDATED_AT)) {
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
                InvitationTableMap::addInstanceToPool($this);
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

            if ($this->articlesScheduledForDeletion !== null) {
                if (!$this->articlesScheduledForDeletion->isEmpty()) {
                    $pks = [];
                    foreach ($this->articlesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Model\InvitationsArticlesQuery::create()
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


            if ($this->invitationsArticlessScheduledForDeletion !== null) {
                if (!$this->invitationsArticlessScheduledForDeletion->isEmpty()) {
                    \Model\InvitationsArticlesQuery::create()
                        ->filterByPrimaryKeys($this->invitationsArticlessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->invitationsArticlessScheduledForDeletion = null;
                }
            }

            if ($this->collInvitationsArticless !== null) {
                foreach ($this->collInvitationsArticless as $referrerFK) {
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

        $this->modifiedColumns[InvitationTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . InvitationTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(InvitationTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(InvitationTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(InvitationTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(InvitationTableMap::COL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'code';
        }
        if ($this->isColumnModified(InvitationTableMap::COL_ALLOWS_PRE_DOWNLOAD)) {
            $modifiedColumns[':p' . $index++]  = 'allows_pre_download';
        }
        if ($this->isColumnModified(InvitationTableMap::COL_CONSUMED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'consumed_at';
        }
        if ($this->isColumnModified(InvitationTableMap::COL_EXPIRES_AT)) {
            $modifiedColumns[':p' . $index++]  = 'expires_at';
        }
        if ($this->isColumnModified(InvitationTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(InvitationTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO invitations (%s) VALUES (%s)',
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
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);

                        break;
                    case 'code':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);

                        break;
                    case 'allows_pre_download':
                        $stmt->bindValue($identifier, (int) $this->allows_pre_download, PDO::PARAM_INT);

                        break;
                    case 'consumed_at':
                        $stmt->bindValue($identifier, $this->consumed_at ? $this->consumed_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'expires_at':
                        $stmt->bindValue($identifier, $this->expires_at ? $this->expires_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = InvitationTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEmail();

            case 3:
                return $this->getCode();

            case 4:
                return $this->getAllowsPreDownload();

            case 5:
                return $this->getConsumedAt();

            case 6:
                return $this->getExpiresAt();

            case 7:
                return $this->getCreatedAt();

            case 8:
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
        if (isset($alreadyDumpedObjects['Invitation'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Invitation'][$this->hashCode()] = true;
        $keys = InvitationTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getEmail(),
            $keys[3] => $this->getCode(),
            $keys[4] => $this->getAllowsPreDownload(),
            $keys[5] => $this->getConsumedAt(),
            $keys[6] => $this->getExpiresAt(),
            $keys[7] => $this->getCreatedAt(),
            $keys[8] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[5]] instanceof \DateTimeInterface) {
            $result[$keys[5]] = $result[$keys[5]]->format('Y-m-d H:i:s.u');
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
            if (null !== $this->collInvitationsArticless) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invitationsArticless';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invitations_articless';
                        break;
                    default:
                        $key = 'InvitationsArticless';
                }

                $result[$key] = $this->collInvitationsArticless->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = InvitationTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setEmail($value);
                break;
            case 3:
                $this->setCode($value);
                break;
            case 4:
                $this->setAllowsPreDownload($value);
                break;
            case 5:
                $this->setConsumedAt($value);
                break;
            case 6:
                $this->setExpiresAt($value);
                break;
            case 7:
                $this->setCreatedAt($value);
                break;
            case 8:
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
        $keys = InvitationTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setEmail($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCode($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAllowsPreDownload($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setConsumedAt($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setExpiresAt($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCreatedAt($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setUpdatedAt($arr[$keys[8]]);
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
        $criteria = new Criteria(InvitationTableMap::DATABASE_NAME);

        if ($this->isColumnModified(InvitationTableMap::COL_ID)) {
            $criteria->add(InvitationTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(InvitationTableMap::COL_SITE_ID)) {
            $criteria->add(InvitationTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(InvitationTableMap::COL_EMAIL)) {
            $criteria->add(InvitationTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(InvitationTableMap::COL_CODE)) {
            $criteria->add(InvitationTableMap::COL_CODE, $this->code);
        }
        if ($this->isColumnModified(InvitationTableMap::COL_ALLOWS_PRE_DOWNLOAD)) {
            $criteria->add(InvitationTableMap::COL_ALLOWS_PRE_DOWNLOAD, $this->allows_pre_download);
        }
        if ($this->isColumnModified(InvitationTableMap::COL_CONSUMED_AT)) {
            $criteria->add(InvitationTableMap::COL_CONSUMED_AT, $this->consumed_at);
        }
        if ($this->isColumnModified(InvitationTableMap::COL_EXPIRES_AT)) {
            $criteria->add(InvitationTableMap::COL_EXPIRES_AT, $this->expires_at);
        }
        if ($this->isColumnModified(InvitationTableMap::COL_CREATED_AT)) {
            $criteria->add(InvitationTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(InvitationTableMap::COL_UPDATED_AT)) {
            $criteria->add(InvitationTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildInvitationQuery::create();
        $criteria->add(InvitationTableMap::COL_ID, $this->id);

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
     * @param object $copyObj An object of \Model\Invitation (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setCode($this->getCode());
        $copyObj->setAllowsPreDownload($this->getAllowsPreDownload());
        $copyObj->setConsumedAt($this->getConsumedAt());
        $copyObj->setExpiresAt($this->getExpiresAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getInvitationsArticless() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvitationsArticles($relObj->copy($deepCopy));
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
     * @return \Model\Invitation Clone of current object.
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
     * @param ChildSite $v
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
            $v->addInvitation($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSite object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildSite The associated ChildSite object.
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
                $this->aSite->addInvitations($this);
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
        if ('InvitationsArticles' === $relationName) {
            $this->initInvitationsArticless();
            return;
        }
    }

    /**
     * Clears out the collInvitationsArticless collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addInvitationsArticless()
     */
    public function clearInvitationsArticless()
    {
        $this->collInvitationsArticless = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collInvitationsArticless collection loaded partially.
     *
     * @return void
     */
    public function resetPartialInvitationsArticless($v = true): void
    {
        $this->collInvitationsArticlessPartial = $v;
    }

    /**
     * Initializes the collInvitationsArticless collection.
     *
     * By default this just sets the collInvitationsArticless collection to an empty array (like clearcollInvitationsArticless());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvitationsArticless(bool $overrideExisting = true): void
    {
        if (null !== $this->collInvitationsArticless && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvitationsArticlesTableMap::getTableMap()->getCollectionClassName();

        $this->collInvitationsArticless = new $collectionClassName;
        $this->collInvitationsArticless->setModel('\Model\InvitationsArticles');
    }

    /**
     * Gets an array of ChildInvitationsArticles objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInvitation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildInvitationsArticles[] List of ChildInvitationsArticles objects
     * @phpstan-return ObjectCollection&\Traversable<ChildInvitationsArticles> List of ChildInvitationsArticles objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getInvitationsArticless(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collInvitationsArticlessPartial && !$this->isNew();
        if (null === $this->collInvitationsArticless || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collInvitationsArticless) {
                    $this->initInvitationsArticless();
                } else {
                    $collectionClassName = InvitationsArticlesTableMap::getTableMap()->getCollectionClassName();

                    $collInvitationsArticless = new $collectionClassName;
                    $collInvitationsArticless->setModel('\Model\InvitationsArticles');

                    return $collInvitationsArticless;
                }
            } else {
                $collInvitationsArticless = ChildInvitationsArticlesQuery::create(null, $criteria)
                    ->filterByInvitation($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvitationsArticlessPartial && count($collInvitationsArticless)) {
                        $this->initInvitationsArticless(false);

                        foreach ($collInvitationsArticless as $obj) {
                            if (false == $this->collInvitationsArticless->contains($obj)) {
                                $this->collInvitationsArticless->append($obj);
                            }
                        }

                        $this->collInvitationsArticlessPartial = true;
                    }

                    return $collInvitationsArticless;
                }

                if ($partial && $this->collInvitationsArticless) {
                    foreach ($this->collInvitationsArticless as $obj) {
                        if ($obj->isNew()) {
                            $collInvitationsArticless[] = $obj;
                        }
                    }
                }

                $this->collInvitationsArticless = $collInvitationsArticless;
                $this->collInvitationsArticlessPartial = false;
            }
        }

        return $this->collInvitationsArticless;
    }

    /**
     * Sets a collection of ChildInvitationsArticles objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $invitationsArticless A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setInvitationsArticless(Collection $invitationsArticless, ?ConnectionInterface $con = null)
    {
        /** @var ChildInvitationsArticles[] $invitationsArticlessToDelete */
        $invitationsArticlessToDelete = $this->getInvitationsArticless(new Criteria(), $con)->diff($invitationsArticless);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->invitationsArticlessScheduledForDeletion = clone $invitationsArticlessToDelete;

        foreach ($invitationsArticlessToDelete as $invitationsArticlesRemoved) {
            $invitationsArticlesRemoved->setInvitation(null);
        }

        $this->collInvitationsArticless = null;
        foreach ($invitationsArticless as $invitationsArticles) {
            $this->addInvitationsArticles($invitationsArticles);
        }

        $this->collInvitationsArticless = $invitationsArticless;
        $this->collInvitationsArticlessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related InvitationsArticles objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related InvitationsArticles objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countInvitationsArticless(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collInvitationsArticlessPartial && !$this->isNew();
        if (null === $this->collInvitationsArticless || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvitationsArticless) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvitationsArticless());
            }

            $query = ChildInvitationsArticlesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInvitation($this)
                ->count($con);
        }

        return count($this->collInvitationsArticless);
    }

    /**
     * Method called to associate a ChildInvitationsArticles object to this object
     * through the ChildInvitationsArticles foreign key attribute.
     *
     * @param ChildInvitationsArticles $l ChildInvitationsArticles
     * @return $this The current object (for fluent API support)
     */
    public function addInvitationsArticles(ChildInvitationsArticles $l)
    {
        if ($this->collInvitationsArticless === null) {
            $this->initInvitationsArticless();
            $this->collInvitationsArticlessPartial = true;
        }

        if (!$this->collInvitationsArticless->contains($l)) {
            $this->doAddInvitationsArticles($l);

            if ($this->invitationsArticlessScheduledForDeletion and $this->invitationsArticlessScheduledForDeletion->contains($l)) {
                $this->invitationsArticlessScheduledForDeletion->remove($this->invitationsArticlessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildInvitationsArticles $invitationsArticles The ChildInvitationsArticles object to add.
     */
    protected function doAddInvitationsArticles(ChildInvitationsArticles $invitationsArticles): void
    {
        $this->collInvitationsArticless[]= $invitationsArticles;
        $invitationsArticles->setInvitation($this);
    }

    /**
     * @param ChildInvitationsArticles $invitationsArticles The ChildInvitationsArticles object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeInvitationsArticles(ChildInvitationsArticles $invitationsArticles)
    {
        if ($this->getInvitationsArticless()->contains($invitationsArticles)) {
            $pos = $this->collInvitationsArticless->search($invitationsArticles);
            $this->collInvitationsArticless->remove($pos);
            if (null === $this->invitationsArticlessScheduledForDeletion) {
                $this->invitationsArticlessScheduledForDeletion = clone $this->collInvitationsArticless;
                $this->invitationsArticlessScheduledForDeletion->clear();
            }
            $this->invitationsArticlessScheduledForDeletion[]= clone $invitationsArticles;
            $invitationsArticles->setInvitation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invitation is new, it will return
     * an empty collection; or if this Invitation has previously
     * been saved, it will retrieve related InvitationsArticless from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invitation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInvitationsArticles[] List of ChildInvitationsArticles objects
     * @phpstan-return ObjectCollection&\Traversable<ChildInvitationsArticles}> List of ChildInvitationsArticles objects
     */
    public function getInvitationsArticlessJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInvitationsArticlesQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getInvitationsArticless($query, $con);
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
        $collectionClassName = InvitationsArticlesTableMap::getTableMap()->getCollectionClassName();

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
     * to the current object by way of the invitations_articles cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInvitation is new, it will return
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
                    ->filterByInvitation($this);
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
     * to the current object by way of the invitations_articles cross-reference table.
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
     * to the current object by way of the invitations_articles cross-reference table.
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
                    ->filterByInvitation($this)
                    ->count($con);
            }
        } else {
            return count($this->collArticles);
        }
    }

    /**
     * Associate a ChildArticle to this object
     * through the invitations_articles cross reference table.
     *
     * @param ChildArticle $article
     * @return ChildInvitation The current object (for fluent API support)
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
        $invitationsArticles = new ChildInvitationsArticles();

        $invitationsArticles->setArticle($article);

        $invitationsArticles->setInvitation($this);

        $this->addInvitationsArticles($invitationsArticles);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$article->isInvitationsLoaded()) {
            $article->initInvitations();
            $article->getInvitations()->push($this);
        } elseif (!$article->getInvitations()->contains($this)) {
            $article->getInvitations()->push($this);
        }

    }

    /**
     * Remove article of this object
     * through the invitations_articles cross reference table.
     *
     * @param ChildArticle $article
     * @return ChildInvitation The current object (for fluent API support)
     */
    public function removeArticle(ChildArticle $article)
    {
        if ($this->getArticles()->contains($article)) {
            $invitationsArticles = new ChildInvitationsArticles();
            $invitationsArticles->setArticle($article);
            if ($article->isInvitationsLoaded()) {
                //remove the back reference if available
                $article->getInvitations()->removeObject($this);
            }

            $invitationsArticles->setInvitation($this);
            $this->removeInvitationsArticles(clone $invitationsArticles);
            $invitationsArticles->clear();

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
        if (null !== $this->aSite) {
            $this->aSite->removeInvitation($this);
        }
        $this->id = null;
        $this->site_id = null;
        $this->email = null;
        $this->code = null;
        $this->allows_pre_download = null;
        $this->consumed_at = null;
        $this->expires_at = null;
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
            if ($this->collInvitationsArticless) {
                foreach ($this->collInvitationsArticless as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collArticles) {
                foreach ($this->collArticles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collInvitationsArticless = null;
        $this->collArticles = null;
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
        return (string) $this->exportTo(InvitationTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[InvitationTableMap::COL_UPDATED_AT] = true;

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
