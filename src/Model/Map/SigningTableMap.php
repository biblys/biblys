<?php

namespace Model\Map;

use Model\Signing;
use Model\SigningQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'signings' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class SigningTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.SigningTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'signings';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Signing';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Signing';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Signing';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the signing_id field
     */
    public const COL_SIGNING_ID = 'signings.signing_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'signings.site_id';

    /**
     * the column name for the publisher_id field
     */
    public const COL_PUBLISHER_ID = 'signings.publisher_id';

    /**
     * the column name for the people_id field
     */
    public const COL_PEOPLE_ID = 'signings.people_id';

    /**
     * the column name for the signing_date field
     */
    public const COL_SIGNING_DATE = 'signings.signing_date';

    /**
     * the column name for the signing_starts field
     */
    public const COL_SIGNING_STARTS = 'signings.signing_starts';

    /**
     * the column name for the signing_ends field
     */
    public const COL_SIGNING_ENDS = 'signings.signing_ends';

    /**
     * the column name for the signing_location field
     */
    public const COL_SIGNING_LOCATION = 'signings.signing_location';

    /**
     * the column name for the signing_created field
     */
    public const COL_SIGNING_CREATED = 'signings.signing_created';

    /**
     * the column name for the signing_updated field
     */
    public const COL_SIGNING_UPDATED = 'signings.signing_updated';

    /**
     * The default string format for model objects of the related table
     */
    public const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     *
     * @var array<string, mixed>
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'PublisherId', 'PeopleId', 'Date', 'Starts', 'Ends', 'Location', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'publisherId', 'peopleId', 'date', 'starts', 'ends', 'location', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [SigningTableMap::COL_SIGNING_ID, SigningTableMap::COL_SITE_ID, SigningTableMap::COL_PUBLISHER_ID, SigningTableMap::COL_PEOPLE_ID, SigningTableMap::COL_SIGNING_DATE, SigningTableMap::COL_SIGNING_STARTS, SigningTableMap::COL_SIGNING_ENDS, SigningTableMap::COL_SIGNING_LOCATION, SigningTableMap::COL_SIGNING_CREATED, SigningTableMap::COL_SIGNING_UPDATED, ],
        self::TYPE_FIELDNAME     => ['signing_id', 'site_id', 'publisher_id', 'people_id', 'signing_date', 'signing_starts', 'signing_ends', 'signing_location', 'signing_created', 'signing_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     *
     * @var array<string, mixed>
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'PublisherId' => 2, 'PeopleId' => 3, 'Date' => 4, 'Starts' => 5, 'Ends' => 6, 'Location' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'publisherId' => 2, 'peopleId' => 3, 'date' => 4, 'starts' => 5, 'ends' => 6, 'location' => 7, 'createdAt' => 8, 'updatedAt' => 9, ],
        self::TYPE_COLNAME       => [SigningTableMap::COL_SIGNING_ID => 0, SigningTableMap::COL_SITE_ID => 1, SigningTableMap::COL_PUBLISHER_ID => 2, SigningTableMap::COL_PEOPLE_ID => 3, SigningTableMap::COL_SIGNING_DATE => 4, SigningTableMap::COL_SIGNING_STARTS => 5, SigningTableMap::COL_SIGNING_ENDS => 6, SigningTableMap::COL_SIGNING_LOCATION => 7, SigningTableMap::COL_SIGNING_CREATED => 8, SigningTableMap::COL_SIGNING_UPDATED => 9, ],
        self::TYPE_FIELDNAME     => ['signing_id' => 0, 'site_id' => 1, 'publisher_id' => 2, 'people_id' => 3, 'signing_date' => 4, 'signing_starts' => 5, 'signing_ends' => 6, 'signing_location' => 7, 'signing_created' => 8, 'signing_updated' => 9, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'SIGNING_ID',
        'Signing.Id' => 'SIGNING_ID',
        'id' => 'SIGNING_ID',
        'signing.id' => 'SIGNING_ID',
        'SigningTableMap::COL_SIGNING_ID' => 'SIGNING_ID',
        'COL_SIGNING_ID' => 'SIGNING_ID',
        'signing_id' => 'SIGNING_ID',
        'signings.signing_id' => 'SIGNING_ID',
        'SiteId' => 'SITE_ID',
        'Signing.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'signing.siteId' => 'SITE_ID',
        'SigningTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'signings.site_id' => 'SITE_ID',
        'PublisherId' => 'PUBLISHER_ID',
        'Signing.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'signing.publisherId' => 'PUBLISHER_ID',
        'SigningTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'signings.publisher_id' => 'PUBLISHER_ID',
        'PeopleId' => 'PEOPLE_ID',
        'Signing.PeopleId' => 'PEOPLE_ID',
        'peopleId' => 'PEOPLE_ID',
        'signing.peopleId' => 'PEOPLE_ID',
        'SigningTableMap::COL_PEOPLE_ID' => 'PEOPLE_ID',
        'COL_PEOPLE_ID' => 'PEOPLE_ID',
        'people_id' => 'PEOPLE_ID',
        'signings.people_id' => 'PEOPLE_ID',
        'Date' => 'SIGNING_DATE',
        'Signing.Date' => 'SIGNING_DATE',
        'date' => 'SIGNING_DATE',
        'signing.date' => 'SIGNING_DATE',
        'SigningTableMap::COL_SIGNING_DATE' => 'SIGNING_DATE',
        'COL_SIGNING_DATE' => 'SIGNING_DATE',
        'signing_date' => 'SIGNING_DATE',
        'signings.signing_date' => 'SIGNING_DATE',
        'Starts' => 'SIGNING_STARTS',
        'Signing.Starts' => 'SIGNING_STARTS',
        'starts' => 'SIGNING_STARTS',
        'signing.starts' => 'SIGNING_STARTS',
        'SigningTableMap::COL_SIGNING_STARTS' => 'SIGNING_STARTS',
        'COL_SIGNING_STARTS' => 'SIGNING_STARTS',
        'signing_starts' => 'SIGNING_STARTS',
        'signings.signing_starts' => 'SIGNING_STARTS',
        'Ends' => 'SIGNING_ENDS',
        'Signing.Ends' => 'SIGNING_ENDS',
        'ends' => 'SIGNING_ENDS',
        'signing.ends' => 'SIGNING_ENDS',
        'SigningTableMap::COL_SIGNING_ENDS' => 'SIGNING_ENDS',
        'COL_SIGNING_ENDS' => 'SIGNING_ENDS',
        'signing_ends' => 'SIGNING_ENDS',
        'signings.signing_ends' => 'SIGNING_ENDS',
        'Location' => 'SIGNING_LOCATION',
        'Signing.Location' => 'SIGNING_LOCATION',
        'location' => 'SIGNING_LOCATION',
        'signing.location' => 'SIGNING_LOCATION',
        'SigningTableMap::COL_SIGNING_LOCATION' => 'SIGNING_LOCATION',
        'COL_SIGNING_LOCATION' => 'SIGNING_LOCATION',
        'signing_location' => 'SIGNING_LOCATION',
        'signings.signing_location' => 'SIGNING_LOCATION',
        'CreatedAt' => 'SIGNING_CREATED',
        'Signing.CreatedAt' => 'SIGNING_CREATED',
        'createdAt' => 'SIGNING_CREATED',
        'signing.createdAt' => 'SIGNING_CREATED',
        'SigningTableMap::COL_SIGNING_CREATED' => 'SIGNING_CREATED',
        'COL_SIGNING_CREATED' => 'SIGNING_CREATED',
        'signing_created' => 'SIGNING_CREATED',
        'signings.signing_created' => 'SIGNING_CREATED',
        'UpdatedAt' => 'SIGNING_UPDATED',
        'Signing.UpdatedAt' => 'SIGNING_UPDATED',
        'updatedAt' => 'SIGNING_UPDATED',
        'signing.updatedAt' => 'SIGNING_UPDATED',
        'SigningTableMap::COL_SIGNING_UPDATED' => 'SIGNING_UPDATED',
        'COL_SIGNING_UPDATED' => 'SIGNING_UPDATED',
        'signing_updated' => 'SIGNING_UPDATED',
        'signings.signing_updated' => 'SIGNING_UPDATED',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function initialize(): void
    {
        // attributes
        $this->setName('signings');
        $this->setPhpName('Signing');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Signing');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('signing_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('publisher_id', 'PublisherId', 'INTEGER', false, null, null);
        $this->addColumn('people_id', 'PeopleId', 'INTEGER', false, null, null);
        $this->addColumn('signing_date', 'Date', 'DATE', false, null, null);
        $this->addColumn('signing_starts', 'Starts', 'TIME', false, null, null);
        $this->addColumn('signing_ends', 'Ends', 'TIME', false, null, null);
        $this->addColumn('signing_location', 'Location', 'VARCHAR', false, 255, null);
        $this->addColumn('signing_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('signing_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
    }

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array<string, array> Associative array (name => parameters) of behaviors
     */
    public function getBehaviors(): array
    {
        return [
            'timestampable' => ['create_column' => 'signing_created', 'update_column' => 'signing_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
        ];
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param bool $withPrefix Whether to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass(bool $withPrefix = true): string
    {
        return $withPrefix ? SigningTableMap::CLASS_DEFAULT : SigningTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array (Signing object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = SigningTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SigningTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SigningTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SigningTableMap::OM_CLASS;
            /** @var Signing $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SigningTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array<object>
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher): array
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = SigningTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SigningTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Signing $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SigningTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->addSelectColumn(SigningTableMap::COL_SIGNING_ID);
            $criteria->addSelectColumn(SigningTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(SigningTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(SigningTableMap::COL_PEOPLE_ID);
            $criteria->addSelectColumn(SigningTableMap::COL_SIGNING_DATE);
            $criteria->addSelectColumn(SigningTableMap::COL_SIGNING_STARTS);
            $criteria->addSelectColumn(SigningTableMap::COL_SIGNING_ENDS);
            $criteria->addSelectColumn(SigningTableMap::COL_SIGNING_LOCATION);
            $criteria->addSelectColumn(SigningTableMap::COL_SIGNING_CREATED);
            $criteria->addSelectColumn(SigningTableMap::COL_SIGNING_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.signing_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.people_id');
            $criteria->addSelectColumn($alias . '.signing_date');
            $criteria->addSelectColumn($alias . '.signing_starts');
            $criteria->addSelectColumn($alias . '.signing_ends');
            $criteria->addSelectColumn($alias . '.signing_location');
            $criteria->addSelectColumn($alias . '.signing_created');
            $criteria->addSelectColumn($alias . '.signing_updated');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria Object containing the columns to remove.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function removeSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(SigningTableMap::COL_SIGNING_ID);
            $criteria->removeSelectColumn(SigningTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(SigningTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(SigningTableMap::COL_PEOPLE_ID);
            $criteria->removeSelectColumn(SigningTableMap::COL_SIGNING_DATE);
            $criteria->removeSelectColumn(SigningTableMap::COL_SIGNING_STARTS);
            $criteria->removeSelectColumn(SigningTableMap::COL_SIGNING_ENDS);
            $criteria->removeSelectColumn(SigningTableMap::COL_SIGNING_LOCATION);
            $criteria->removeSelectColumn(SigningTableMap::COL_SIGNING_CREATED);
            $criteria->removeSelectColumn(SigningTableMap::COL_SIGNING_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.signing_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.people_id');
            $criteria->removeSelectColumn($alias . '.signing_date');
            $criteria->removeSelectColumn($alias . '.signing_starts');
            $criteria->removeSelectColumn($alias . '.signing_ends');
            $criteria->removeSelectColumn($alias . '.signing_location');
            $criteria->removeSelectColumn($alias . '.signing_created');
            $criteria->removeSelectColumn($alias . '.signing_updated');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap(): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(SigningTableMap::DATABASE_NAME)->getTable(SigningTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Signing or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Signing object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ?ConnectionInterface $con = null): int
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SigningTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Signing) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SigningTableMap::DATABASE_NAME);
            $criteria->add(SigningTableMap::COL_SIGNING_ID, (array) $values, Criteria::IN);
        }

        $query = SigningQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SigningTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SigningTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the signings table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return SigningQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Signing or Criteria object.
     *
     * @param mixed $criteria Criteria or Signing object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SigningTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Signing object
        }

        if ($criteria->containsKey(SigningTableMap::COL_SIGNING_ID) && $criteria->keyContainsValue(SigningTableMap::COL_SIGNING_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SigningTableMap::COL_SIGNING_ID.')');
        }


        // Set the correct dbName
        $query = SigningQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
