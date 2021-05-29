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
    const CLASS_NAME = 'Model.Map.CountryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'countries';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Country';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Country';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the country_id field
     */
    const COL_COUNTRY_ID = 'countries.country_id';

    /**
     * the column name for the country_code field
     */
    const COL_COUNTRY_CODE = 'countries.country_code';

    /**
     * the column name for the country_name field
     */
    const COL_COUNTRY_NAME = 'countries.country_name';

    /**
     * the column name for the country_name_en field
     */
    const COL_COUNTRY_NAME_EN = 'countries.country_name_en';

    /**
     * the column name for the shipping_zone field
     */
    const COL_SHIPPING_ZONE = 'countries.shipping_zone';

    /**
     * the column name for the country_created field
     */
    const COL_COUNTRY_CREATED = 'countries.country_created';

    /**
     * the column name for the country_updated field
     */
    const COL_COUNTRY_UPDATED = 'countries.country_updated';

    /**
     * the column name for the country_deleted field
     */
    const COL_COUNTRY_DELETED = 'countries.country_deleted';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Code', 'Name', 'NameEn', 'ShippingZone', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'code', 'name', 'nameEn', 'shippingZone', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(CountryTableMap::COL_COUNTRY_ID, CountryTableMap::COL_COUNTRY_CODE, CountryTableMap::COL_COUNTRY_NAME, CountryTableMap::COL_COUNTRY_NAME_EN, CountryTableMap::COL_SHIPPING_ZONE, CountryTableMap::COL_COUNTRY_CREATED, CountryTableMap::COL_COUNTRY_UPDATED, CountryTableMap::COL_COUNTRY_DELETED, ),
        self::TYPE_FIELDNAME     => array('country_id', 'country_code', 'country_name', 'country_name_en', 'shipping_zone', 'country_created', 'country_updated', 'country_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Code' => 1, 'Name' => 2, 'NameEn' => 3, 'ShippingZone' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, 'DeletedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'code' => 1, 'name' => 2, 'nameEn' => 3, 'shippingZone' => 4, 'createdAt' => 5, 'updatedAt' => 6, 'deletedAt' => 7, ),
        self::TYPE_COLNAME       => array(CountryTableMap::COL_COUNTRY_ID => 0, CountryTableMap::COL_COUNTRY_CODE => 1, CountryTableMap::COL_COUNTRY_NAME => 2, CountryTableMap::COL_COUNTRY_NAME_EN => 3, CountryTableMap::COL_SHIPPING_ZONE => 4, CountryTableMap::COL_COUNTRY_CREATED => 5, CountryTableMap::COL_COUNTRY_UPDATED => 6, CountryTableMap::COL_COUNTRY_DELETED => 7, ),
        self::TYPE_FIELDNAME     => array('country_id' => 0, 'country_code' => 1, 'country_name' => 2, 'country_name_en' => 3, 'shipping_zone' => 4, 'country_created' => 5, 'country_updated' => 6, 'country_deleted' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
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
        'ShippingZone' => 'SHIPPING_ZONE',
        'Country.ShippingZone' => 'SHIPPING_ZONE',
        'shippingZone' => 'SHIPPING_ZONE',
        'country.shippingZone' => 'SHIPPING_ZONE',
        'CountryTableMap::COL_SHIPPING_ZONE' => 'SHIPPING_ZONE',
        'COL_SHIPPING_ZONE' => 'SHIPPING_ZONE',
        'shipping_zone' => 'SHIPPING_ZONE',
        'countries.shipping_zone' => 'SHIPPING_ZONE',
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
        'DeletedAt' => 'COUNTRY_DELETED',
        'Country.DeletedAt' => 'COUNTRY_DELETED',
        'deletedAt' => 'COUNTRY_DELETED',
        'country.deletedAt' => 'COUNTRY_DELETED',
        'CountryTableMap::COL_COUNTRY_DELETED' => 'COUNTRY_DELETED',
        'COL_COUNTRY_DELETED' => 'COUNTRY_DELETED',
        'country_deleted' => 'COUNTRY_DELETED',
        'countries.country_deleted' => 'COUNTRY_DELETED',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
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
        $this->addColumn('shipping_zone', 'ShippingZone', 'VARCHAR', false, 8, null);
        $this->addColumn('country_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('country_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('country_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? CountryTableMap::CLASS_DEFAULT : CountryTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Country object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

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
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_ID);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_CODE);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_NAME);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_NAME_EN);
            $criteria->addSelectColumn(CountryTableMap::COL_SHIPPING_ZONE);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_CREATED);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_UPDATED);
            $criteria->addSelectColumn(CountryTableMap::COL_COUNTRY_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.country_id');
            $criteria->addSelectColumn($alias . '.country_code');
            $criteria->addSelectColumn($alias . '.country_name');
            $criteria->addSelectColumn($alias . '.country_name_en');
            $criteria->addSelectColumn($alias . '.shipping_zone');
            $criteria->addSelectColumn($alias . '.country_created');
            $criteria->addSelectColumn($alias . '.country_updated');
            $criteria->addSelectColumn($alias . '.country_deleted');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria object containing the columns to remove.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function removeSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_ID);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_CODE);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_NAME);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_NAME_EN);
            $criteria->removeSelectColumn(CountryTableMap::COL_SHIPPING_ZONE);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_CREATED);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_UPDATED);
            $criteria->removeSelectColumn(CountryTableMap::COL_COUNTRY_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.country_id');
            $criteria->removeSelectColumn($alias . '.country_code');
            $criteria->removeSelectColumn($alias . '.country_name');
            $criteria->removeSelectColumn($alias . '.country_name_en');
            $criteria->removeSelectColumn($alias . '.shipping_zone');
            $criteria->removeSelectColumn($alias . '.country_created');
            $criteria->removeSelectColumn($alias . '.country_updated');
            $criteria->removeSelectColumn($alias . '.country_deleted');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(CountryTableMap::DATABASE_NAME)->getTable(CountryTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CountryTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CountryTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CountryTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Country or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Country object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
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
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CountryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Country or Criteria object.
     *
     * @param mixed               $criteria Criteria or Country object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
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

} // CountryTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CountryTableMap::buildTableMap();
