<?php

namespace Model\Map;

use Model\Price;
use Model\PriceQuery;
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
 * This class defines the structure of the 'prices' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class PriceTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.PriceTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'prices';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Price';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Price';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Price';

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
     * the column name for the price_id field
     */
    public const COL_PRICE_ID = 'prices.price_id';

    /**
     * the column name for the pricegrid_id field
     */
    public const COL_PRICEGRID_ID = 'prices.pricegrid_id';

    /**
     * the column name for the price_cat field
     */
    public const COL_PRICE_CAT = 'prices.price_cat';

    /**
     * the column name for the price_amount field
     */
    public const COL_PRICE_AMOUNT = 'prices.price_amount';

    /**
     * the column name for the price_created field
     */
    public const COL_PRICE_CREATED = 'prices.price_created';

    /**
     * the column name for the price_updated field
     */
    public const COL_PRICE_UPDATED = 'prices.price_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'gridId', 'Cat', 'Amount', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'gridId', 'cat', 'amount', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [PriceTableMap::COL_PRICE_ID, PriceTableMap::COL_PRICEGRID_ID, PriceTableMap::COL_PRICE_CAT, PriceTableMap::COL_PRICE_AMOUNT, PriceTableMap::COL_PRICE_CREATED, PriceTableMap::COL_PRICE_UPDATED, ],
        self::TYPE_FIELDNAME     => ['price_id', 'pricegrid_id', 'price_cat', 'price_amount', 'price_created', 'price_updated', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'gridId' => 1, 'Cat' => 2, 'Amount' => 3, 'CreatedAt' => 4, 'UpdatedAt' => 5, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'gridId' => 1, 'cat' => 2, 'amount' => 3, 'createdAt' => 4, 'updatedAt' => 5, ],
        self::TYPE_COLNAME       => [PriceTableMap::COL_PRICE_ID => 0, PriceTableMap::COL_PRICEGRID_ID => 1, PriceTableMap::COL_PRICE_CAT => 2, PriceTableMap::COL_PRICE_AMOUNT => 3, PriceTableMap::COL_PRICE_CREATED => 4, PriceTableMap::COL_PRICE_UPDATED => 5, ],
        self::TYPE_FIELDNAME     => ['price_id' => 0, 'pricegrid_id' => 1, 'price_cat' => 2, 'price_amount' => 3, 'price_created' => 4, 'price_updated' => 5, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'PRICE_ID',
        'Price.Id' => 'PRICE_ID',
        'id' => 'PRICE_ID',
        'price.id' => 'PRICE_ID',
        'PriceTableMap::COL_PRICE_ID' => 'PRICE_ID',
        'COL_PRICE_ID' => 'PRICE_ID',
        'price_id' => 'PRICE_ID',
        'prices.price_id' => 'PRICE_ID',
        'gridId' => 'PRICEGRID_ID',
        'Price.gridId' => 'PRICEGRID_ID',
        'price.gridId' => 'PRICEGRID_ID',
        'PriceTableMap::COL_PRICEGRID_ID' => 'PRICEGRID_ID',
        'COL_PRICEGRID_ID' => 'PRICEGRID_ID',
        'pricegrid_id' => 'PRICEGRID_ID',
        'prices.pricegrid_id' => 'PRICEGRID_ID',
        'Cat' => 'PRICE_CAT',
        'Price.Cat' => 'PRICE_CAT',
        'cat' => 'PRICE_CAT',
        'price.cat' => 'PRICE_CAT',
        'PriceTableMap::COL_PRICE_CAT' => 'PRICE_CAT',
        'COL_PRICE_CAT' => 'PRICE_CAT',
        'price_cat' => 'PRICE_CAT',
        'prices.price_cat' => 'PRICE_CAT',
        'Amount' => 'PRICE_AMOUNT',
        'Price.Amount' => 'PRICE_AMOUNT',
        'amount' => 'PRICE_AMOUNT',
        'price.amount' => 'PRICE_AMOUNT',
        'PriceTableMap::COL_PRICE_AMOUNT' => 'PRICE_AMOUNT',
        'COL_PRICE_AMOUNT' => 'PRICE_AMOUNT',
        'price_amount' => 'PRICE_AMOUNT',
        'prices.price_amount' => 'PRICE_AMOUNT',
        'CreatedAt' => 'PRICE_CREATED',
        'Price.CreatedAt' => 'PRICE_CREATED',
        'createdAt' => 'PRICE_CREATED',
        'price.createdAt' => 'PRICE_CREATED',
        'PriceTableMap::COL_PRICE_CREATED' => 'PRICE_CREATED',
        'COL_PRICE_CREATED' => 'PRICE_CREATED',
        'price_created' => 'PRICE_CREATED',
        'prices.price_created' => 'PRICE_CREATED',
        'UpdatedAt' => 'PRICE_UPDATED',
        'Price.UpdatedAt' => 'PRICE_UPDATED',
        'updatedAt' => 'PRICE_UPDATED',
        'price.updatedAt' => 'PRICE_UPDATED',
        'PriceTableMap::COL_PRICE_UPDATED' => 'PRICE_UPDATED',
        'COL_PRICE_UPDATED' => 'PRICE_UPDATED',
        'price_updated' => 'PRICE_UPDATED',
        'prices.price_updated' => 'PRICE_UPDATED',
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
        $this->setName('prices');
        $this->setPhpName('Price');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Price');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('price_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('pricegrid_id', 'gridId', 'INTEGER', false, null, null);
        $this->addColumn('price_cat', 'Cat', 'LONGVARCHAR', false, null, null);
        $this->addColumn('price_amount', 'Amount', 'INTEGER', false, null, null);
        $this->addColumn('price_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('price_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'price_created', 'update_column' => 'price_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? PriceTableMap::CLASS_DEFAULT : PriceTableMap::OM_CLASS;
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
     * @return array (Price object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = PriceTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PriceTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PriceTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PriceTableMap::OM_CLASS;
            /** @var Price $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PriceTableMap::addInstanceToPool($obj, $key);
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
            $key = PriceTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PriceTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Price $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PriceTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PriceTableMap::COL_PRICE_ID);
            $criteria->addSelectColumn(PriceTableMap::COL_PRICEGRID_ID);
            $criteria->addSelectColumn(PriceTableMap::COL_PRICE_CAT);
            $criteria->addSelectColumn(PriceTableMap::COL_PRICE_AMOUNT);
            $criteria->addSelectColumn(PriceTableMap::COL_PRICE_CREATED);
            $criteria->addSelectColumn(PriceTableMap::COL_PRICE_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.price_id');
            $criteria->addSelectColumn($alias . '.pricegrid_id');
            $criteria->addSelectColumn($alias . '.price_cat');
            $criteria->addSelectColumn($alias . '.price_amount');
            $criteria->addSelectColumn($alias . '.price_created');
            $criteria->addSelectColumn($alias . '.price_updated');
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
            $criteria->removeSelectColumn(PriceTableMap::COL_PRICE_ID);
            $criteria->removeSelectColumn(PriceTableMap::COL_PRICEGRID_ID);
            $criteria->removeSelectColumn(PriceTableMap::COL_PRICE_CAT);
            $criteria->removeSelectColumn(PriceTableMap::COL_PRICE_AMOUNT);
            $criteria->removeSelectColumn(PriceTableMap::COL_PRICE_CREATED);
            $criteria->removeSelectColumn(PriceTableMap::COL_PRICE_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.price_id');
            $criteria->removeSelectColumn($alias . '.pricegrid_id');
            $criteria->removeSelectColumn($alias . '.price_cat');
            $criteria->removeSelectColumn($alias . '.price_amount');
            $criteria->removeSelectColumn($alias . '.price_created');
            $criteria->removeSelectColumn($alias . '.price_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(PriceTableMap::DATABASE_NAME)->getTable(PriceTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Price or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Price object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PriceTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Price) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PriceTableMap::DATABASE_NAME);
            $criteria->add(PriceTableMap::COL_PRICE_ID, (array) $values, Criteria::IN);
        }

        $query = PriceQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PriceTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PriceTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the prices table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return PriceQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Price or Criteria object.
     *
     * @param mixed $criteria Criteria or Price object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PriceTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Price object
        }

        if ($criteria->containsKey(PriceTableMap::COL_PRICE_ID) && $criteria->keyContainsValue(PriceTableMap::COL_PRICE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PriceTableMap::COL_PRICE_ID.')');
        }


        // Set the correct dbName
        $query = PriceQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
