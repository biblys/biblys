<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Country as ChildCountry;
use Model\CountryQuery as ChildCountryQuery;
use Model\ShippingOption as ChildShippingOption;
use Model\ShippingOptionQuery as ChildShippingOptionQuery;
use Model\ShippingZone as ChildShippingZone;
use Model\ShippingZoneQuery as ChildShippingZoneQuery;
use Model\ShippingZonesCountries as ChildShippingZonesCountries;
use Model\ShippingZonesCountriesQuery as ChildShippingZonesCountriesQuery;
use Model\Map\ShippingOptionTableMap;
use Model\Map\ShippingZoneTableMap;
use Model\Map\ShippingZonesCountriesTableMap;
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
 * Base class that represents a row from the 'shipping_zones' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class ShippingZone implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\ShippingZoneTableMap';


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
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the description field.
     *
     * @var        string|null
     */
    protected $description;

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
     * @var        ObjectCollection|ChildShippingOption[] Collection to store aggregation of ChildShippingOption objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildShippingOption> Collection to store aggregation of ChildShippingOption objects.
     */
    protected $collShippingOptions;
    protected $collShippingOptionsPartial;

    /**
     * @var        ObjectCollection|ChildShippingZonesCountries[] Collection to store aggregation of ChildShippingZonesCountries objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildShippingZonesCountries> Collection to store aggregation of ChildShippingZonesCountries objects.
     */
    protected $collShippingZonesCountriess;
    protected $collShippingZonesCountriessPartial;

    /**
     * @var        ObjectCollection|ChildCountry[] Cross Collection to store aggregation of ChildCountry objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildCountry> Cross Collection to store aggregation of ChildCountry objects.
     */
    protected $collCountries;

    /**
     * @var bool
     */
    protected $collCountriesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCountry[]
     * @phpstan-var ObjectCollection&\Traversable<ChildCountry>
     */
    protected $countriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildShippingOption[]
     * @phpstan-var ObjectCollection&\Traversable<ChildShippingOption>
     */
    protected $shippingOptionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildShippingZonesCountries[]
     * @phpstan-var ObjectCollection&\Traversable<ChildShippingZonesCountries>
     */
    protected $shippingZonesCountriessScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Base\ShippingZone object.
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
     * Compares this with another <code>ShippingZone</code> instance.  If
     * <code>obj</code> is an instance of <code>ShippingZone</code>, delegates to
     * <code>equals(ShippingZone)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [description] column value.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
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
            $this->modifiedColumns[ShippingZoneTableMap::COL_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [name] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[ShippingZoneTableMap::COL_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [description] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[ShippingZoneTableMap::COL_DESCRIPTION] = true;
        }

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
                $this->modifiedColumns[ShippingZoneTableMap::COL_CREATED_AT] = true;
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
                $this->modifiedColumns[ShippingZoneTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ShippingZoneTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ShippingZoneTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ShippingZoneTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ShippingZoneTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ShippingZoneTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = ShippingZoneTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\ShippingZone'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(ShippingZoneTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildShippingZoneQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collShippingOptions = null;

            $this->collShippingZonesCountriess = null;

            $this->collCountries = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see ShippingZone::setDeleted()
     * @see ShippingZone::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingZoneTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildShippingZoneQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingZoneTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(ShippingZoneTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(ShippingZoneTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(ShippingZoneTableMap::COL_UPDATED_AT)) {
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
                ShippingZoneTableMap::addInstanceToPool($this);
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

            if ($this->countriesScheduledForDeletion !== null) {
                if (!$this->countriesScheduledForDeletion->isEmpty()) {
                    $pks = [];
                    foreach ($this->countriesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Model\ShippingZonesCountriesQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->countriesScheduledForDeletion = null;
                }

            }

            if ($this->collCountries) {
                foreach ($this->collCountries as $country) {
                    if (!$country->isDeleted() && ($country->isNew() || $country->isModified())) {
                        $country->save($con);
                    }
                }
            }


            if ($this->shippingOptionsScheduledForDeletion !== null) {
                if (!$this->shippingOptionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->shippingOptionsScheduledForDeletion as $shippingOption) {
                        // need to save related object because we set the relation to null
                        $shippingOption->save($con);
                    }
                    $this->shippingOptionsScheduledForDeletion = null;
                }
            }

            if ($this->collShippingOptions !== null) {
                foreach ($this->collShippingOptions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->shippingZonesCountriessScheduledForDeletion !== null) {
                if (!$this->shippingZonesCountriessScheduledForDeletion->isEmpty()) {
                    \Model\ShippingZonesCountriesQuery::create()
                        ->filterByPrimaryKeys($this->shippingZonesCountriessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->shippingZonesCountriessScheduledForDeletion = null;
                }
            }

            if ($this->collShippingZonesCountriess !== null) {
                foreach ($this->collShippingZonesCountriess as $referrerFK) {
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

        $this->modifiedColumns[ShippingZoneTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ShippingZoneTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ShippingZoneTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ShippingZoneTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(ShippingZoneTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(ShippingZoneTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(ShippingZoneTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO shipping_zones (%s) VALUES (%s)',
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
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);

                        break;
                    case 'description':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);

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
        $pos = ShippingZoneTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getDescription();

            case 3:
                return $this->getCreatedAt();

            case 4:
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
        if (isset($alreadyDumpedObjects['ShippingZone'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['ShippingZone'][$this->hashCode()] = true;
        $keys = ShippingZoneTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getDescription(),
            $keys[3] => $this->getCreatedAt(),
            $keys[4] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[3]] instanceof \DateTimeInterface) {
            $result[$keys[3]] = $result[$keys[3]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[4]] instanceof \DateTimeInterface) {
            $result[$keys[4]] = $result[$keys[4]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collShippingOptions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'shippingOptions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'shippings';
                        break;
                    default:
                        $key = 'ShippingOptions';
                }

                $result[$key] = $this->collShippingOptions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collShippingZonesCountriess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'shippingZonesCountriess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'shipping_zones_countriess';
                        break;
                    default:
                        $key = 'ShippingZonesCountriess';
                }

                $result[$key] = $this->collShippingZonesCountriess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = ShippingZoneTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setDescription($value);
                break;
            case 3:
                $this->setCreatedAt($value);
                break;
            case 4:
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
        $keys = ShippingZoneTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDescription($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCreatedAt($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setUpdatedAt($arr[$keys[4]]);
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
        $criteria = new Criteria(ShippingZoneTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ShippingZoneTableMap::COL_ID)) {
            $criteria->add(ShippingZoneTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ShippingZoneTableMap::COL_NAME)) {
            $criteria->add(ShippingZoneTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(ShippingZoneTableMap::COL_DESCRIPTION)) {
            $criteria->add(ShippingZoneTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(ShippingZoneTableMap::COL_CREATED_AT)) {
            $criteria->add(ShippingZoneTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(ShippingZoneTableMap::COL_UPDATED_AT)) {
            $criteria->add(ShippingZoneTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildShippingZoneQuery::create();
        $criteria->add(ShippingZoneTableMap::COL_ID, $this->id);

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
     * @param object $copyObj An object of \Model\ShippingZone (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getShippingOptions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addShippingOption($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getShippingZonesCountriess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addShippingZonesCountries($relObj->copy($deepCopy));
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
     * @return \Model\ShippingZone Clone of current object.
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
        if ('ShippingOption' === $relationName) {
            $this->initShippingOptions();
            return;
        }
        if ('ShippingZonesCountries' === $relationName) {
            $this->initShippingZonesCountriess();
            return;
        }
    }

    /**
     * Clears out the collShippingOptions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addShippingOptions()
     */
    public function clearShippingOptions()
    {
        $this->collShippingOptions = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collShippingOptions collection loaded partially.
     *
     * @return void
     */
    public function resetPartialShippingOptions($v = true): void
    {
        $this->collShippingOptionsPartial = $v;
    }

    /**
     * Initializes the collShippingOptions collection.
     *
     * By default this just sets the collShippingOptions collection to an empty array (like clearcollShippingOptions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initShippingOptions(bool $overrideExisting = true): void
    {
        if (null !== $this->collShippingOptions && !$overrideExisting) {
            return;
        }

        $collectionClassName = ShippingOptionTableMap::getTableMap()->getCollectionClassName();

        $this->collShippingOptions = new $collectionClassName;
        $this->collShippingOptions->setModel('\Model\ShippingOption');
    }

    /**
     * Gets an array of ChildShippingOption objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildShippingZone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildShippingOption[] List of ChildShippingOption objects
     * @phpstan-return ObjectCollection&\Traversable<ChildShippingOption> List of ChildShippingOption objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getShippingOptions(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collShippingOptionsPartial && !$this->isNew();
        if (null === $this->collShippingOptions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collShippingOptions) {
                    $this->initShippingOptions();
                } else {
                    $collectionClassName = ShippingOptionTableMap::getTableMap()->getCollectionClassName();

                    $collShippingOptions = new $collectionClassName;
                    $collShippingOptions->setModel('\Model\ShippingOption');

                    return $collShippingOptions;
                }
            } else {
                $collShippingOptions = ChildShippingOptionQuery::create(null, $criteria)
                    ->filterByShippingZone($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collShippingOptionsPartial && count($collShippingOptions)) {
                        $this->initShippingOptions(false);

                        foreach ($collShippingOptions as $obj) {
                            if (false == $this->collShippingOptions->contains($obj)) {
                                $this->collShippingOptions->append($obj);
                            }
                        }

                        $this->collShippingOptionsPartial = true;
                    }

                    return $collShippingOptions;
                }

                if ($partial && $this->collShippingOptions) {
                    foreach ($this->collShippingOptions as $obj) {
                        if ($obj->isNew()) {
                            $collShippingOptions[] = $obj;
                        }
                    }
                }

                $this->collShippingOptions = $collShippingOptions;
                $this->collShippingOptionsPartial = false;
            }
        }

        return $this->collShippingOptions;
    }

    /**
     * Sets a collection of ChildShippingOption objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $shippingOptions A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setShippingOptions(Collection $shippingOptions, ?ConnectionInterface $con = null)
    {
        /** @var ChildShippingOption[] $shippingOptionsToDelete */
        $shippingOptionsToDelete = $this->getShippingOptions(new Criteria(), $con)->diff($shippingOptions);


        $this->shippingOptionsScheduledForDeletion = $shippingOptionsToDelete;

        foreach ($shippingOptionsToDelete as $shippingOptionRemoved) {
            $shippingOptionRemoved->setShippingZone(null);
        }

        $this->collShippingOptions = null;
        foreach ($shippingOptions as $shippingOption) {
            $this->addShippingOption($shippingOption);
        }

        $this->collShippingOptions = $shippingOptions;
        $this->collShippingOptionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ShippingOption objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related ShippingOption objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countShippingOptions(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collShippingOptionsPartial && !$this->isNew();
        if (null === $this->collShippingOptions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collShippingOptions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getShippingOptions());
            }

            $query = ChildShippingOptionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByShippingZone($this)
                ->count($con);
        }

        return count($this->collShippingOptions);
    }

    /**
     * Method called to associate a ChildShippingOption object to this object
     * through the ChildShippingOption foreign key attribute.
     *
     * @param ChildShippingOption $l ChildShippingOption
     * @return $this The current object (for fluent API support)
     */
    public function addShippingOption(ChildShippingOption $l)
    {
        if ($this->collShippingOptions === null) {
            $this->initShippingOptions();
            $this->collShippingOptionsPartial = true;
        }

        if (!$this->collShippingOptions->contains($l)) {
            $this->doAddShippingOption($l);

            if ($this->shippingOptionsScheduledForDeletion and $this->shippingOptionsScheduledForDeletion->contains($l)) {
                $this->shippingOptionsScheduledForDeletion->remove($this->shippingOptionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildShippingOption $shippingOption The ChildShippingOption object to add.
     */
    protected function doAddShippingOption(ChildShippingOption $shippingOption): void
    {
        $this->collShippingOptions[]= $shippingOption;
        $shippingOption->setShippingZone($this);
    }

    /**
     * @param ChildShippingOption $shippingOption The ChildShippingOption object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeShippingOption(ChildShippingOption $shippingOption)
    {
        if ($this->getShippingOptions()->contains($shippingOption)) {
            $pos = $this->collShippingOptions->search($shippingOption);
            $this->collShippingOptions->remove($pos);
            if (null === $this->shippingOptionsScheduledForDeletion) {
                $this->shippingOptionsScheduledForDeletion = clone $this->collShippingOptions;
                $this->shippingOptionsScheduledForDeletion->clear();
            }
            $this->shippingOptionsScheduledForDeletion[]= $shippingOption;
            $shippingOption->setShippingZone(null);
        }

        return $this;
    }

    /**
     * Clears out the collShippingZonesCountriess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addShippingZonesCountriess()
     */
    public function clearShippingZonesCountriess()
    {
        $this->collShippingZonesCountriess = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collShippingZonesCountriess collection loaded partially.
     *
     * @return void
     */
    public function resetPartialShippingZonesCountriess($v = true): void
    {
        $this->collShippingZonesCountriessPartial = $v;
    }

    /**
     * Initializes the collShippingZonesCountriess collection.
     *
     * By default this just sets the collShippingZonesCountriess collection to an empty array (like clearcollShippingZonesCountriess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initShippingZonesCountriess(bool $overrideExisting = true): void
    {
        if (null !== $this->collShippingZonesCountriess && !$overrideExisting) {
            return;
        }

        $collectionClassName = ShippingZonesCountriesTableMap::getTableMap()->getCollectionClassName();

        $this->collShippingZonesCountriess = new $collectionClassName;
        $this->collShippingZonesCountriess->setModel('\Model\ShippingZonesCountries');
    }

    /**
     * Gets an array of ChildShippingZonesCountries objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildShippingZone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildShippingZonesCountries[] List of ChildShippingZonesCountries objects
     * @phpstan-return ObjectCollection&\Traversable<ChildShippingZonesCountries> List of ChildShippingZonesCountries objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getShippingZonesCountriess(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collShippingZonesCountriessPartial && !$this->isNew();
        if (null === $this->collShippingZonesCountriess || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collShippingZonesCountriess) {
                    $this->initShippingZonesCountriess();
                } else {
                    $collectionClassName = ShippingZonesCountriesTableMap::getTableMap()->getCollectionClassName();

                    $collShippingZonesCountriess = new $collectionClassName;
                    $collShippingZonesCountriess->setModel('\Model\ShippingZonesCountries');

                    return $collShippingZonesCountriess;
                }
            } else {
                $collShippingZonesCountriess = ChildShippingZonesCountriesQuery::create(null, $criteria)
                    ->filterByShippingZone($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collShippingZonesCountriessPartial && count($collShippingZonesCountriess)) {
                        $this->initShippingZonesCountriess(false);

                        foreach ($collShippingZonesCountriess as $obj) {
                            if (false == $this->collShippingZonesCountriess->contains($obj)) {
                                $this->collShippingZonesCountriess->append($obj);
                            }
                        }

                        $this->collShippingZonesCountriessPartial = true;
                    }

                    return $collShippingZonesCountriess;
                }

                if ($partial && $this->collShippingZonesCountriess) {
                    foreach ($this->collShippingZonesCountriess as $obj) {
                        if ($obj->isNew()) {
                            $collShippingZonesCountriess[] = $obj;
                        }
                    }
                }

                $this->collShippingZonesCountriess = $collShippingZonesCountriess;
                $this->collShippingZonesCountriessPartial = false;
            }
        }

        return $this->collShippingZonesCountriess;
    }

    /**
     * Sets a collection of ChildShippingZonesCountries objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $shippingZonesCountriess A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setShippingZonesCountriess(Collection $shippingZonesCountriess, ?ConnectionInterface $con = null)
    {
        /** @var ChildShippingZonesCountries[] $shippingZonesCountriessToDelete */
        $shippingZonesCountriessToDelete = $this->getShippingZonesCountriess(new Criteria(), $con)->diff($shippingZonesCountriess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->shippingZonesCountriessScheduledForDeletion = clone $shippingZonesCountriessToDelete;

        foreach ($shippingZonesCountriessToDelete as $shippingZonesCountriesRemoved) {
            $shippingZonesCountriesRemoved->setShippingZone(null);
        }

        $this->collShippingZonesCountriess = null;
        foreach ($shippingZonesCountriess as $shippingZonesCountries) {
            $this->addShippingZonesCountries($shippingZonesCountries);
        }

        $this->collShippingZonesCountriess = $shippingZonesCountriess;
        $this->collShippingZonesCountriessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ShippingZonesCountries objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related ShippingZonesCountries objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countShippingZonesCountriess(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collShippingZonesCountriessPartial && !$this->isNew();
        if (null === $this->collShippingZonesCountriess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collShippingZonesCountriess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getShippingZonesCountriess());
            }

            $query = ChildShippingZonesCountriesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByShippingZone($this)
                ->count($con);
        }

        return count($this->collShippingZonesCountriess);
    }

    /**
     * Method called to associate a ChildShippingZonesCountries object to this object
     * through the ChildShippingZonesCountries foreign key attribute.
     *
     * @param ChildShippingZonesCountries $l ChildShippingZonesCountries
     * @return $this The current object (for fluent API support)
     */
    public function addShippingZonesCountries(ChildShippingZonesCountries $l)
    {
        if ($this->collShippingZonesCountriess === null) {
            $this->initShippingZonesCountriess();
            $this->collShippingZonesCountriessPartial = true;
        }

        if (!$this->collShippingZonesCountriess->contains($l)) {
            $this->doAddShippingZonesCountries($l);

            if ($this->shippingZonesCountriessScheduledForDeletion and $this->shippingZonesCountriessScheduledForDeletion->contains($l)) {
                $this->shippingZonesCountriessScheduledForDeletion->remove($this->shippingZonesCountriessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildShippingZonesCountries $shippingZonesCountries The ChildShippingZonesCountries object to add.
     */
    protected function doAddShippingZonesCountries(ChildShippingZonesCountries $shippingZonesCountries): void
    {
        $this->collShippingZonesCountriess[]= $shippingZonesCountries;
        $shippingZonesCountries->setShippingZone($this);
    }

    /**
     * @param ChildShippingZonesCountries $shippingZonesCountries The ChildShippingZonesCountries object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeShippingZonesCountries(ChildShippingZonesCountries $shippingZonesCountries)
    {
        if ($this->getShippingZonesCountriess()->contains($shippingZonesCountries)) {
            $pos = $this->collShippingZonesCountriess->search($shippingZonesCountries);
            $this->collShippingZonesCountriess->remove($pos);
            if (null === $this->shippingZonesCountriessScheduledForDeletion) {
                $this->shippingZonesCountriessScheduledForDeletion = clone $this->collShippingZonesCountriess;
                $this->shippingZonesCountriessScheduledForDeletion->clear();
            }
            $this->shippingZonesCountriessScheduledForDeletion[]= clone $shippingZonesCountries;
            $shippingZonesCountries->setShippingZone(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ShippingZone is new, it will return
     * an empty collection; or if this ShippingZone has previously
     * been saved, it will retrieve related ShippingZonesCountriess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ShippingZone.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildShippingZonesCountries[] List of ChildShippingZonesCountries objects
     * @phpstan-return ObjectCollection&\Traversable<ChildShippingZonesCountries}> List of ChildShippingZonesCountries objects
     */
    public function getShippingZonesCountriessJoinCountry(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildShippingZonesCountriesQuery::create(null, $criteria);
        $query->joinWith('Country', $joinBehavior);

        return $this->getShippingZonesCountriess($query, $con);
    }

    /**
     * Clears out the collCountries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCountries()
     */
    public function clearCountries()
    {
        $this->collCountries = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collCountries crossRef collection.
     *
     * By default this just sets the collCountries collection to an empty collection (like clearCountries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCountries()
    {
        $collectionClassName = ShippingZonesCountriesTableMap::getTableMap()->getCollectionClassName();

        $this->collCountries = new $collectionClassName;
        $this->collCountriesPartial = true;
        $this->collCountries->setModel('\Model\Country');
    }

    /**
     * Checks if the collCountries collection is loaded.
     *
     * @return bool
     */
    public function isCountriesLoaded(): bool
    {
        return null !== $this->collCountries;
    }

    /**
     * Gets a collection of ChildCountry objects related by a many-to-many relationship
     * to the current object by way of the shipping_zones_countries cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildShippingZone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildCountry[] List of ChildCountry objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCountry> List of ChildCountry objects
     */
    public function getCountries(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collCountriesPartial && !$this->isNew();
        if (null === $this->collCountries || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCountries) {
                    $this->initCountries();
                }
            } else {

                $query = ChildCountryQuery::create(null, $criteria)
                    ->filterByShippingZone($this);
                $collCountries = $query->find($con);
                if (null !== $criteria) {
                    return $collCountries;
                }

                if ($partial && $this->collCountries) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collCountries as $obj) {
                        if (!$collCountries->contains($obj)) {
                            $collCountries[] = $obj;
                        }
                    }
                }

                $this->collCountries = $collCountries;
                $this->collCountriesPartial = false;
            }
        }

        return $this->collCountries;
    }

    /**
     * Sets a collection of Country objects related by a many-to-many relationship
     * to the current object by way of the shipping_zones_countries cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $countries A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setCountries(Collection $countries, ?ConnectionInterface $con = null)
    {
        $this->clearCountries();
        $currentCountries = $this->getCountries();

        $countriesScheduledForDeletion = $currentCountries->diff($countries);

        foreach ($countriesScheduledForDeletion as $toDelete) {
            $this->removeCountry($toDelete);
        }

        foreach ($countries as $country) {
            if (!$currentCountries->contains($country)) {
                $this->doAddCountry($country);
            }
        }

        $this->collCountriesPartial = false;
        $this->collCountries = $countries;

        return $this;
    }

    /**
     * Gets the number of Country objects related by a many-to-many relationship
     * to the current object by way of the shipping_zones_countries cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param bool $distinct Set to true to force count distinct
     * @param ConnectionInterface $con Optional connection object
     *
     * @return int The number of related Country objects
     */
    public function countCountries(?Criteria $criteria = null, $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collCountriesPartial && !$this->isNew();
        if (null === $this->collCountries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCountries) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getCountries());
                }

                $query = ChildCountryQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByShippingZone($this)
                    ->count($con);
            }
        } else {
            return count($this->collCountries);
        }
    }

    /**
     * Associate a ChildCountry to this object
     * through the shipping_zones_countries cross reference table.
     *
     * @param ChildCountry $country
     * @return ChildShippingZone The current object (for fluent API support)
     */
    public function addCountry(ChildCountry $country)
    {
        if ($this->collCountries === null) {
            $this->initCountries();
        }

        if (!$this->getCountries()->contains($country)) {
            // only add it if the **same** object is not already associated
            $this->collCountries->push($country);
            $this->doAddCountry($country);
        }

        return $this;
    }

    /**
     *
     * @param ChildCountry $country
     */
    protected function doAddCountry(ChildCountry $country)
    {
        $shippingZonesCountries = new ChildShippingZonesCountries();

        $shippingZonesCountries->setCountry($country);

        $shippingZonesCountries->setShippingZone($this);

        $this->addShippingZonesCountries($shippingZonesCountries);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$country->isShippingZonesLoaded()) {
            $country->initShippingZones();
            $country->getShippingZones()->push($this);
        } elseif (!$country->getShippingZones()->contains($this)) {
            $country->getShippingZones()->push($this);
        }

    }

    /**
     * Remove country of this object
     * through the shipping_zones_countries cross reference table.
     *
     * @param ChildCountry $country
     * @return ChildShippingZone The current object (for fluent API support)
     */
    public function removeCountry(ChildCountry $country)
    {
        if ($this->getCountries()->contains($country)) {
            $shippingZonesCountries = new ChildShippingZonesCountries();
            $shippingZonesCountries->setCountry($country);
            if ($country->isShippingZonesLoaded()) {
                //remove the back reference if available
                $country->getShippingZones()->removeObject($this);
            }

            $shippingZonesCountries->setShippingZone($this);
            $this->removeShippingZonesCountries(clone $shippingZonesCountries);
            $shippingZonesCountries->clear();

            $this->collCountries->remove($this->collCountries->search($country));

            if (null === $this->countriesScheduledForDeletion) {
                $this->countriesScheduledForDeletion = clone $this->collCountries;
                $this->countriesScheduledForDeletion->clear();
            }

            $this->countriesScheduledForDeletion->push($country);
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
        $this->id = null;
        $this->name = null;
        $this->description = null;
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
            if ($this->collShippingOptions) {
                foreach ($this->collShippingOptions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collShippingZonesCountriess) {
                foreach ($this->collShippingZonesCountriess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCountries) {
                foreach ($this->collCountries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collShippingOptions = null;
        $this->collShippingZonesCountriess = null;
        $this->collCountries = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ShippingZoneTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[ShippingZoneTableMap::COL_UPDATED_AT] = true;

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
