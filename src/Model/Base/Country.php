<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Country as ChildCountry;
use Model\CountryQuery as ChildCountryQuery;
use Model\Order as ChildOrder;
use Model\OrderQuery as ChildOrderQuery;
use Model\ShippingZone as ChildShippingZone;
use Model\ShippingZoneQuery as ChildShippingZoneQuery;
use Model\ShippingZonesCountries as ChildShippingZonesCountries;
use Model\ShippingZonesCountriesQuery as ChildShippingZonesCountriesQuery;
use Model\Map\CountryTableMap;
use Model\Map\OrderTableMap;
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
 * Base class that represents a row from the 'countries' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Country implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\CountryTableMap';


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
     * The value for the country_id field.
     *
     * @var        int
     */
    protected $country_id;

    /**
     * The value for the country_code field.
     *
     * @var        string|null
     */
    protected $country_code;

    /**
     * The value for the country_name field.
     *
     * @var        string|null
     */
    protected $country_name;

    /**
     * The value for the country_name_en field.
     *
     * @var        string|null
     */
    protected $country_name_en;

    /**
     * The value for the country_created field.
     *
     * @var        DateTime|null
     */
    protected $country_created;

    /**
     * The value for the country_updated field.
     *
     * @var        DateTime|null
     */
    protected $country_updated;

    /**
     * @var        ObjectCollection|ChildOrder[] Collection to store aggregation of ChildOrder objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildOrder> Collection to store aggregation of ChildOrder objects.
     */
    protected $collOrders;
    protected $collOrdersPartial;

    /**
     * @var        ObjectCollection|ChildShippingZonesCountries[] Collection to store aggregation of ChildShippingZonesCountries objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildShippingZonesCountries> Collection to store aggregation of ChildShippingZonesCountries objects.
     */
    protected $collShippingZonesCountriess;
    protected $collShippingZonesCountriessPartial;

    /**
     * @var        ObjectCollection|ChildShippingZone[] Cross Collection to store aggregation of ChildShippingZone objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildShippingZone> Cross Collection to store aggregation of ChildShippingZone objects.
     */
    protected $collShippingZones;

    /**
     * @var bool
     */
    protected $collShippingZonesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildShippingZone[]
     * @phpstan-var ObjectCollection&\Traversable<ChildShippingZone>
     */
    protected $shippingZonesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOrder[]
     * @phpstan-var ObjectCollection&\Traversable<ChildOrder>
     */
    protected $ordersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildShippingZonesCountries[]
     * @phpstan-var ObjectCollection&\Traversable<ChildShippingZonesCountries>
     */
    protected $shippingZonesCountriessScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Base\Country object.
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
     * Compares this with another <code>Country</code> instance.  If
     * <code>obj</code> is an instance of <code>Country</code>, delegates to
     * <code>equals(Country)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [country_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->country_id;
    }

    /**
     * Get the [country_code] column value.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->country_code;
    }

    /**
     * Get the [country_name] column value.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->country_name;
    }

    /**
     * Get the [country_name_en] column value.
     *
     * @return string|null
     */
    public function getNameEn()
    {
        return $this->country_name_en;
    }

    /**
     * Get the [optionally formatted] temporal [country_created] column value.
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
            return $this->country_created;
        } else {
            return $this->country_created instanceof \DateTimeInterface ? $this->country_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [country_updated] column value.
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
            return $this->country_updated;
        } else {
            return $this->country_updated instanceof \DateTimeInterface ? $this->country_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [country_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->country_id !== $v) {
            $this->country_id = $v;
            $this->modifiedColumns[CountryTableMap::COL_COUNTRY_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [country_code] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->country_code !== $v) {
            $this->country_code = $v;
            $this->modifiedColumns[CountryTableMap::COL_COUNTRY_CODE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [country_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->country_name !== $v) {
            $this->country_name = $v;
            $this->modifiedColumns[CountryTableMap::COL_COUNTRY_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [country_name_en] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNameEn($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->country_name_en !== $v) {
            $this->country_name_en = $v;
            $this->modifiedColumns[CountryTableMap::COL_COUNTRY_NAME_EN] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [country_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->country_created !== null || $dt !== null) {
            if ($this->country_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->country_created->format("Y-m-d H:i:s.u")) {
                $this->country_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CountryTableMap::COL_COUNTRY_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [country_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->country_updated !== null || $dt !== null) {
            if ($this->country_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->country_updated->format("Y-m-d H:i:s.u")) {
                $this->country_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CountryTableMap::COL_COUNTRY_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CountryTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CountryTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CountryTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CountryTableMap::translateFieldName('NameEn', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country_name_en = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CountryTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->country_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CountryTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->country_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = CountryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Country'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(CountryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCountryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collOrders = null;

            $this->collShippingZonesCountriess = null;

            $this->collShippingZones = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Country::setDeleted()
     * @see Country::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCountryQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(CountryTableMap::COL_COUNTRY_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(CountryTableMap::COL_COUNTRY_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CountryTableMap::COL_COUNTRY_UPDATED)) {
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
                CountryTableMap::addInstanceToPool($this);
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

            if ($this->shippingZonesScheduledForDeletion !== null) {
                if (!$this->shippingZonesScheduledForDeletion->isEmpty()) {
                    $pks = [];
                    foreach ($this->shippingZonesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Model\ShippingZonesCountriesQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->shippingZonesScheduledForDeletion = null;
                }

            }

            if ($this->collShippingZones) {
                foreach ($this->collShippingZones as $shippingZone) {
                    if (!$shippingZone->isDeleted() && ($shippingZone->isNew() || $shippingZone->isModified())) {
                        $shippingZone->save($con);
                    }
                }
            }


            if ($this->ordersScheduledForDeletion !== null) {
                if (!$this->ordersScheduledForDeletion->isEmpty()) {
                    foreach ($this->ordersScheduledForDeletion as $order) {
                        // need to save related object because we set the relation to null
                        $order->save($con);
                    }
                    $this->ordersScheduledForDeletion = null;
                }
            }

            if ($this->collOrders !== null) {
                foreach ($this->collOrders as $referrerFK) {
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

        $this->modifiedColumns[CountryTableMap::COL_COUNTRY_ID] = true;
        if (null !== $this->country_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CountryTableMap::COL_COUNTRY_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'country_id';
        }
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'country_code';
        }
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'country_name';
        }
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_NAME_EN)) {
            $modifiedColumns[':p' . $index++]  = 'country_name_en';
        }
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'country_created';
        }
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'country_updated';
        }

        $sql = sprintf(
            'INSERT INTO countries (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'country_id':
                        $stmt->bindValue($identifier, $this->country_id, PDO::PARAM_INT);

                        break;
                    case 'country_code':
                        $stmt->bindValue($identifier, $this->country_code, PDO::PARAM_STR);

                        break;
                    case 'country_name':
                        $stmt->bindValue($identifier, $this->country_name, PDO::PARAM_STR);

                        break;
                    case 'country_name_en':
                        $stmt->bindValue($identifier, $this->country_name_en, PDO::PARAM_STR);

                        break;
                    case 'country_created':
                        $stmt->bindValue($identifier, $this->country_created ? $this->country_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'country_updated':
                        $stmt->bindValue($identifier, $this->country_updated ? $this->country_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = CountryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getCode();

            case 2:
                return $this->getName();

            case 3:
                return $this->getNameEn();

            case 4:
                return $this->getCreatedAt();

            case 5:
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
        if (isset($alreadyDumpedObjects['Country'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Country'][$this->hashCode()] = true;
        $keys = CountryTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCode(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getNameEn(),
            $keys[4] => $this->getCreatedAt(),
            $keys[5] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[4]] instanceof \DateTimeInterface) {
            $result[$keys[4]] = $result[$keys[4]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[5]] instanceof \DateTimeInterface) {
            $result[$keys[5]] = $result[$keys[5]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collOrders) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orders';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orderss';
                        break;
                    default:
                        $key = 'Orders';
                }

                $result[$key] = $this->collOrders->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = CountryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setCode($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setNameEn($value);
                break;
            case 4:
                $this->setCreatedAt($value);
                break;
            case 5:
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
        $keys = CountryTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setCode($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setNameEn($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCreatedAt($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setUpdatedAt($arr[$keys[5]]);
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
        $criteria = new Criteria(CountryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_ID)) {
            $criteria->add(CountryTableMap::COL_COUNTRY_ID, $this->country_id);
        }
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_CODE)) {
            $criteria->add(CountryTableMap::COL_COUNTRY_CODE, $this->country_code);
        }
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_NAME)) {
            $criteria->add(CountryTableMap::COL_COUNTRY_NAME, $this->country_name);
        }
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_NAME_EN)) {
            $criteria->add(CountryTableMap::COL_COUNTRY_NAME_EN, $this->country_name_en);
        }
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_CREATED)) {
            $criteria->add(CountryTableMap::COL_COUNTRY_CREATED, $this->country_created);
        }
        if ($this->isColumnModified(CountryTableMap::COL_COUNTRY_UPDATED)) {
            $criteria->add(CountryTableMap::COL_COUNTRY_UPDATED, $this->country_updated);
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
        $criteria = ChildCountryQuery::create();
        $criteria->add(CountryTableMap::COL_COUNTRY_ID, $this->country_id);

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
     * Generic method to set the primary key (country_id column).
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
     * @param object $copyObj An object of \Model\Country (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setCode($this->getCode());
        $copyObj->setName($this->getName());
        $copyObj->setNameEn($this->getNameEn());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getOrders() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrder($relObj->copy($deepCopy));
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
     * @return \Model\Country Clone of current object.
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
        if ('Order' === $relationName) {
            $this->initOrders();
            return;
        }
        if ('ShippingZonesCountries' === $relationName) {
            $this->initShippingZonesCountriess();
            return;
        }
    }

    /**
     * Clears out the collOrders collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addOrders()
     */
    public function clearOrders()
    {
        $this->collOrders = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collOrders collection loaded partially.
     *
     * @return void
     */
    public function resetPartialOrders($v = true): void
    {
        $this->collOrdersPartial = $v;
    }

    /**
     * Initializes the collOrders collection.
     *
     * By default this just sets the collOrders collection to an empty array (like clearcollOrders());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrders(bool $overrideExisting = true): void
    {
        if (null !== $this->collOrders && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrderTableMap::getTableMap()->getCollectionClassName();

        $this->collOrders = new $collectionClassName;
        $this->collOrders->setModel('\Model\Order');
    }

    /**
     * Gets an array of ChildOrder objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCountry is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder> List of ChildOrder objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getOrders(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersPartial && !$this->isNew();
        if (null === $this->collOrders || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collOrders) {
                    $this->initOrders();
                } else {
                    $collectionClassName = OrderTableMap::getTableMap()->getCollectionClassName();

                    $collOrders = new $collectionClassName;
                    $collOrders->setModel('\Model\Order');

                    return $collOrders;
                }
            } else {
                $collOrders = ChildOrderQuery::create(null, $criteria)
                    ->filterByCountry($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersPartial && count($collOrders)) {
                        $this->initOrders(false);

                        foreach ($collOrders as $obj) {
                            if (false == $this->collOrders->contains($obj)) {
                                $this->collOrders->append($obj);
                            }
                        }

                        $this->collOrdersPartial = true;
                    }

                    return $collOrders;
                }

                if ($partial && $this->collOrders) {
                    foreach ($this->collOrders as $obj) {
                        if ($obj->isNew()) {
                            $collOrders[] = $obj;
                        }
                    }
                }

                $this->collOrders = $collOrders;
                $this->collOrdersPartial = false;
            }
        }

        return $this->collOrders;
    }

    /**
     * Sets a collection of ChildOrder objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $orders A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setOrders(Collection $orders, ?ConnectionInterface $con = null)
    {
        /** @var ChildOrder[] $ordersToDelete */
        $ordersToDelete = $this->getOrders(new Criteria(), $con)->diff($orders);


        $this->ordersScheduledForDeletion = $ordersToDelete;

        foreach ($ordersToDelete as $orderRemoved) {
            $orderRemoved->setCountry(null);
        }

        $this->collOrders = null;
        foreach ($orders as $order) {
            $this->addOrder($order);
        }

        $this->collOrders = $orders;
        $this->collOrdersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Order objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Order objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countOrders(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collOrdersPartial && !$this->isNew();
        if (null === $this->collOrders || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrders) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrders());
            }

            $query = ChildOrderQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCountry($this)
                ->count($con);
        }

        return count($this->collOrders);
    }

    /**
     * Method called to associate a ChildOrder object to this object
     * through the ChildOrder foreign key attribute.
     *
     * @param ChildOrder $l ChildOrder
     * @return $this The current object (for fluent API support)
     */
    public function addOrder(ChildOrder $l)
    {
        if ($this->collOrders === null) {
            $this->initOrders();
            $this->collOrdersPartial = true;
        }

        if (!$this->collOrders->contains($l)) {
            $this->doAddOrder($l);

            if ($this->ordersScheduledForDeletion and $this->ordersScheduledForDeletion->contains($l)) {
                $this->ordersScheduledForDeletion->remove($this->ordersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOrder $order The ChildOrder object to add.
     */
    protected function doAddOrder(ChildOrder $order): void
    {
        $this->collOrders[]= $order;
        $order->setCountry($this);
    }

    /**
     * @param ChildOrder $order The ChildOrder object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeOrder(ChildOrder $order)
    {
        if ($this->getOrders()->contains($order)) {
            $pos = $this->collOrders->search($order);
            $this->collOrders->remove($pos);
            if (null === $this->ordersScheduledForDeletion) {
                $this->ordersScheduledForDeletion = clone $this->collOrders;
                $this->ordersScheduledForDeletion->clear();
            }
            $this->ordersScheduledForDeletion[]= $order;
            $order->setCountry(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinUser(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinCustomer(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('Customer', $joinBehavior);

        return $this->getOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinShippingOption(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('ShippingOption', $joinBehavior);

        return $this->getOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getOrders($query, $con);
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
     * If this ChildCountry is new, it will return
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
                    ->filterByCountry($this)
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
            $shippingZonesCountriesRemoved->setCountry(null);
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
                ->filterByCountry($this)
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
        $shippingZonesCountries->setCountry($this);
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
            $shippingZonesCountries->setCountry(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Country is new, it will return
     * an empty collection; or if this Country has previously
     * been saved, it will retrieve related ShippingZonesCountriess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Country.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildShippingZonesCountries[] List of ChildShippingZonesCountries objects
     * @phpstan-return ObjectCollection&\Traversable<ChildShippingZonesCountries}> List of ChildShippingZonesCountries objects
     */
    public function getShippingZonesCountriessJoinShippingZone(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildShippingZonesCountriesQuery::create(null, $criteria);
        $query->joinWith('ShippingZone', $joinBehavior);

        return $this->getShippingZonesCountriess($query, $con);
    }

    /**
     * Clears out the collShippingZones collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addShippingZones()
     */
    public function clearShippingZones()
    {
        $this->collShippingZones = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collShippingZones crossRef collection.
     *
     * By default this just sets the collShippingZones collection to an empty collection (like clearShippingZones());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initShippingZones()
    {
        $collectionClassName = ShippingZonesCountriesTableMap::getTableMap()->getCollectionClassName();

        $this->collShippingZones = new $collectionClassName;
        $this->collShippingZonesPartial = true;
        $this->collShippingZones->setModel('\Model\ShippingZone');
    }

    /**
     * Checks if the collShippingZones collection is loaded.
     *
     * @return bool
     */
    public function isShippingZonesLoaded(): bool
    {
        return null !== $this->collShippingZones;
    }

    /**
     * Gets a collection of ChildShippingZone objects related by a many-to-many relationship
     * to the current object by way of the shipping_zones_countries cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCountry is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildShippingZone[] List of ChildShippingZone objects
     * @phpstan-return ObjectCollection&\Traversable<ChildShippingZone> List of ChildShippingZone objects
     */
    public function getShippingZones(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collShippingZonesPartial && !$this->isNew();
        if (null === $this->collShippingZones || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collShippingZones) {
                    $this->initShippingZones();
                }
            } else {

                $query = ChildShippingZoneQuery::create(null, $criteria)
                    ->filterByCountry($this);
                $collShippingZones = $query->find($con);
                if (null !== $criteria) {
                    return $collShippingZones;
                }

                if ($partial && $this->collShippingZones) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collShippingZones as $obj) {
                        if (!$collShippingZones->contains($obj)) {
                            $collShippingZones[] = $obj;
                        }
                    }
                }

                $this->collShippingZones = $collShippingZones;
                $this->collShippingZonesPartial = false;
            }
        }

        return $this->collShippingZones;
    }

    /**
     * Sets a collection of ShippingZone objects related by a many-to-many relationship
     * to the current object by way of the shipping_zones_countries cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $shippingZones A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setShippingZones(Collection $shippingZones, ?ConnectionInterface $con = null)
    {
        $this->clearShippingZones();
        $currentShippingZones = $this->getShippingZones();

        $shippingZonesScheduledForDeletion = $currentShippingZones->diff($shippingZones);

        foreach ($shippingZonesScheduledForDeletion as $toDelete) {
            $this->removeShippingZone($toDelete);
        }

        foreach ($shippingZones as $shippingZone) {
            if (!$currentShippingZones->contains($shippingZone)) {
                $this->doAddShippingZone($shippingZone);
            }
        }

        $this->collShippingZonesPartial = false;
        $this->collShippingZones = $shippingZones;

        return $this;
    }

    /**
     * Gets the number of ShippingZone objects related by a many-to-many relationship
     * to the current object by way of the shipping_zones_countries cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param bool $distinct Set to true to force count distinct
     * @param ConnectionInterface $con Optional connection object
     *
     * @return int The number of related ShippingZone objects
     */
    public function countShippingZones(?Criteria $criteria = null, $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collShippingZonesPartial && !$this->isNew();
        if (null === $this->collShippingZones || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collShippingZones) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getShippingZones());
                }

                $query = ChildShippingZoneQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCountry($this)
                    ->count($con);
            }
        } else {
            return count($this->collShippingZones);
        }
    }

    /**
     * Associate a ChildShippingZone to this object
     * through the shipping_zones_countries cross reference table.
     *
     * @param ChildShippingZone $shippingZone
     * @return ChildCountry The current object (for fluent API support)
     */
    public function addShippingZone(ChildShippingZone $shippingZone)
    {
        if ($this->collShippingZones === null) {
            $this->initShippingZones();
        }

        if (!$this->getShippingZones()->contains($shippingZone)) {
            // only add it if the **same** object is not already associated
            $this->collShippingZones->push($shippingZone);
            $this->doAddShippingZone($shippingZone);
        }

        return $this;
    }

    /**
     *
     * @param ChildShippingZone $shippingZone
     */
    protected function doAddShippingZone(ChildShippingZone $shippingZone)
    {
        $shippingZonesCountries = new ChildShippingZonesCountries();

        $shippingZonesCountries->setShippingZone($shippingZone);

        $shippingZonesCountries->setCountry($this);

        $this->addShippingZonesCountries($shippingZonesCountries);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$shippingZone->isCountriesLoaded()) {
            $shippingZone->initCountries();
            $shippingZone->getCountries()->push($this);
        } elseif (!$shippingZone->getCountries()->contains($this)) {
            $shippingZone->getCountries()->push($this);
        }

    }

    /**
     * Remove shippingZone of this object
     * through the shipping_zones_countries cross reference table.
     *
     * @param ChildShippingZone $shippingZone
     * @return ChildCountry The current object (for fluent API support)
     */
    public function removeShippingZone(ChildShippingZone $shippingZone)
    {
        if ($this->getShippingZones()->contains($shippingZone)) {
            $shippingZonesCountries = new ChildShippingZonesCountries();
            $shippingZonesCountries->setShippingZone($shippingZone);
            if ($shippingZone->isCountriesLoaded()) {
                //remove the back reference if available
                $shippingZone->getCountries()->removeObject($this);
            }

            $shippingZonesCountries->setCountry($this);
            $this->removeShippingZonesCountries(clone $shippingZonesCountries);
            $shippingZonesCountries->clear();

            $this->collShippingZones->remove($this->collShippingZones->search($shippingZone));

            if (null === $this->shippingZonesScheduledForDeletion) {
                $this->shippingZonesScheduledForDeletion = clone $this->collShippingZones;
                $this->shippingZonesScheduledForDeletion->clear();
            }

            $this->shippingZonesScheduledForDeletion->push($shippingZone);
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
        $this->country_id = null;
        $this->country_code = null;
        $this->country_name = null;
        $this->country_name_en = null;
        $this->country_created = null;
        $this->country_updated = null;
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
            if ($this->collOrders) {
                foreach ($this->collOrders as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collShippingZonesCountriess) {
                foreach ($this->collShippingZonesCountriess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collShippingZones) {
                foreach ($this->collShippingZones as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collOrders = null;
        $this->collShippingZonesCountriess = null;
        $this->collShippingZones = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CountryTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[CountryTableMap::COL_COUNTRY_UPDATED] = true;

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
