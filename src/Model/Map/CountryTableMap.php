<?php

namespace Model\Map;

use Model\Country;
use Model\CountryQuery;
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
 * This class defines the structure of the 'countries' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class CountryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.CountryTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'countries';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Country';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Country';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Country';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the country_id field
     */
    public const COL_COUNTRY_ID = 'countries.country_id';

    /**
     * the column name for the country_code field
     */
    public const COL_COUNTRY_CODE = 'countries.country_code';

    /**
     * the column name for the country_name field
     */
    public const COL_COUNTRY_NAME = 'countries.country_name';

    /**
     * the column name for the country_name_en field
     */
    public const COL_COUNTRY_NAME_EN = 'countries.country_name_en';

    /**
     * the column name for the country_created field
     */
    public const COL_COUNTRY_CREATED = 'countries.country_created';

    /**
     * the column name for the country_updated field
     */
    public const COL_COUNTRY_UPDATED = 'countries.country_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'Code', 'Name', 'NameEn', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'code', 'name', 'nameEn', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [CountryTableMap::COL_COUNTRY_ID, CountryTableMap::COL_COUNTRY_CODE, CountryTableMap::COL_COUNTRY_NAME, CountryTableMap::COL_COUNTRY_NAME_EN, CountryTableMap::COL_COUNTRY_CREATED, CountryTableMap::COL_COUNTRY_UPDATED, ],
        self::TYPE_FIELDNAME     => ['country_id', 'country_code', 'country_name', 'country_name_en', 'country_created', 'country_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Code' => 1, 'Name' => 2, 'NameEn' => 3, 'CreatedAt' => 4, 'UpdatedAt' => 5, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'code' => 1, 'name' => 2, 'nameEn' => 3, 'createdAt' => 4, 'updatedAt' => 5, ],
        self::TYPE_COLNAME       => [CountryTableMap::COL_COUNTRY_ID => 0, CountryTableMap::COL_COUNTRY_CODE => 1, CountryTableMap::COL_COUNTRY_NAME => 2, CountryTableMap::COL_COUNTRY_NAME_EN => 3, CountryTableMap::COL_COUNTRY_CREATED => 4, CountryTableMap::COL_COUNTRY_UPDATED => 5, ],
        self::TYPE_FIELDNAME     => ['country_id' => 0, 'country_code' => 1, 'country_name' => 2, 'country_name_en' => 3, 'country_created' => 4, 'country_updated' => 5, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'COUNTRY_ID',
        'Country.Id' => 'COUNTRY_ID',
        'id' => 'COUNTRY_ID',
        'country.id' => 'COUNTRY_ID',
        'CountryTableMap::COL_COUNTRY_ID' => 'COUNTRY_ID',
        'COL_COUNTRY_ID' => 'COUNTRY_ID',
        'country_id' => 'COUNTRY_ID',
        'countries.country_id' => 'COUNTRY_ID',
        'Code' => 'COUNTRY_CODE',
        'Country.Code' => 'COUNTRY_CODE',
        'code' => 'COUNTRY_CODE',
        'country.code' => 'COUNTRY_CODE',
        'CountryTableMap::COL_COUNTRY_CODE' => 'COUNTRY_CODE',
        'COL_COUNTRY_CODE' => 'COUNTRY_CODE',
        'country_code' => 'COUNTRY_CODE',
        'countries.country_code' => 'COUNTRY_CODE',
        'Name' => 'COUNTRY_NAME',
        'Country.Name' => 'COUNTRY_NAME',
        'name' => 'COUNTRY_NAME',
        'country.name' => 'COUNTRY_NAME',
        'CountryTableMap::COL_COUNTRY_NAME' => 'COUNTRY_NAME',
        'COL_COUNTRY_NAME' => 'COUNTRY_NAME',
        'country_name' => 'COUNTRY_NAME',
        'countries.country_name' => 'COUNTRY_NAME',
        'NameEn' => 'COUNTRY_NAME_EN',
        'Country.NameEn' => 'COUNTRY_NAME_EN',
        'nameEn' => 'COUNTRY_NAME_EN',
        'country.nameEn' => 'COUNTRY_NAME_EN',
        'CountryTableMap::COL_COUNTRY_NAME_EN' => 'COUNTRY_NAME_EN',
        'COL_COUNTRY_NAME_EN' => 'COUNTRY_NAME_EN',
        'country_name_en' => 'COUNTRY_NAME_EN',
        'countries.country_name_en' => 'COUNTRY_NAME_EN',
        'CreatedAt' => 'COUNTRY_CREATED',
        'Country.CreatedAt' => 'COUNTRY_CREATED',
        'createdAt' => 'COUNTRY_CREATED',
        'country.createdAt' => 'COUNTRY_CREATED',
        'CountryTableMap::COL_COUNTRY_CREATED' => 'COUNTRY_CREATED',
        'COL_COUNTRY_CREATED' => 'COUNTRY_CREATED',
        'country_created' => 'COUNTRY_CREATED',
        'countries.country_created' => 'COUNTRY_CREATED',
        'UpdatedAt' => 'COUNTRY_UPDATED',
        'Country.UpdatedAt' => 'COUNTRY_UPDATED',
        'updatedAt' => 'COUNTRY_UPDATED',
        'country.updatedAt' => 'COUNTRY_UPDATED',
        'CountryTableMap::COL_COUNTRY_UPDATED' => 'COUNTRY_UPDATED',
        'COL_COUNTRY_UPDATED' => 'COUNTRY_UPDATED',
        'country_updated' => 'COUNTRY_UPDATED',
        'countries.country_updated' => 'COUNTRY_UPDATED',
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
        $this->setName('countries');
        $this->setPhpName('Country');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Country');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('country_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('country_code', 'Code', 'VARCHAR', false, 3, null);
        $this->addColumn('country_name', 'Name', 'VARCHAR', false, 200, null);
        $this->addColumn('country_name_en', 'NameEn', 'VARCHAR', false, 200, null);
        $this->addColumn('country_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('country_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Order', '\\Model\\Order', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':country_id',
    1 => ':country_id',
  ),
), null, null, 'Orders', false);
        $this->addRelation('ShippingZonesCountries', '\\Model\\ShippingZonesCountries', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':country_id',
    1 => ':country_id',
  ),
), null, null, 'ShippingZonesCountriess', false);
        $this->addRelation('ShippingZone', '\\Model\\ShippingZone', RelationMap::MANY_TO_MANY, array(), null, null, 'ShippingZones');
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
            'timestampable' => ['create_column' => 'country_created', 'update_column' => 'country_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? CountryTableMap::CLASS_DEFAULT : CountryTableMap::OM_CLASS;
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
     * @return array (Country object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = CountryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CountryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CountryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CountryTableMap::OM_CLASS;
            /** @var Country $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CountryTableMap::addInstanceToPool($obj, $key);
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
            $key = CountryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CountryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Country $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CountryTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_ID);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_CODE);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_NAME);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_NAME_EN);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_CREATED);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.country_id');
            $criteria->addSelectColumn($alias . '.country_code');
            $criteria->addSelectColumn($alias . '.country_name');
            $criteria->addSelectColumn($alias . '.country_name_en');
            $criteria->addSelectColumn($alias . '.country_created');
            $criteria->addSelectColumn($alias . '.country_updated');
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
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_ID);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_CODE);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_NAME);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_NAME_EN);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_CREATED);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.country_id');
            $criteria->removeSelectColumn($alias . '.country_code');
            $criteria->removeSelectColumn($alias . '.country_name');
            $criteria->removeSelectColumn($alias . '.country_name_en');
            $criteria->removeSelectColumn($alias . '.country_created');
            $criteria->removeSelectColumn($alias . '.country_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(CountryTableMap::DATABASE_NAME)->getTable(CountryTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Country or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Country object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Country) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CountryTableMap::DATABASE_NAME);
            $criteria->add(CountryTableMap::COL_COUNTRY_ID, (array) $values, Criteria::IN);
        }

        $query = CountryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CountryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CountryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the countries table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return CountryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Country or Criteria object.
     *
     * @param mixed $criteria Criteria or Country object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Country object
        }

        if ($criteria->containsKey(CountryTableMap::COL_COUNTRY_ID) && $criteria->keyContainsValue(CountryTableMap::COL_COUNTRY_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CountryTableMap::COL_COUNTRY_ID.')');
        }


        // Set the correct dbName
        $query = CountryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
