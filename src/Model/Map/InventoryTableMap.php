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


namespace Model\Map;

use Model\Inventory;
use Model\InventoryQuery;
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
 * This class defines the structure of the 'inventory' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class InventoryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.InventoryTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'inventory';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Inventory';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Inventory';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Inventory';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the inventory_id field
     */
    public const COL_INVENTORY_ID = 'inventory.inventory_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'inventory.site_id';

    /**
     * the column name for the inventory_title field
     */
    public const COL_INVENTORY_TITLE = 'inventory.inventory_title';

    /**
     * the column name for the inventory_created field
     */
    public const COL_INVENTORY_CREATED = 'inventory.inventory_created';

    /**
     * the column name for the inventory_updated field
     */
    public const COL_INVENTORY_UPDATED = 'inventory.inventory_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'Title', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'title', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [InventoryTableMap::COL_INVENTORY_ID, InventoryTableMap::COL_SITE_ID, InventoryTableMap::COL_INVENTORY_TITLE, InventoryTableMap::COL_INVENTORY_CREATED, InventoryTableMap::COL_INVENTORY_UPDATED, ],
        self::TYPE_FIELDNAME     => ['inventory_id', 'site_id', 'inventory_title', 'inventory_created', 'inventory_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'Title' => 2, 'CreatedAt' => 3, 'UpdatedAt' => 4, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'title' => 2, 'createdAt' => 3, 'updatedAt' => 4, ],
        self::TYPE_COLNAME       => [InventoryTableMap::COL_INVENTORY_ID => 0, InventoryTableMap::COL_SITE_ID => 1, InventoryTableMap::COL_INVENTORY_TITLE => 2, InventoryTableMap::COL_INVENTORY_CREATED => 3, InventoryTableMap::COL_INVENTORY_UPDATED => 4, ],
        self::TYPE_FIELDNAME     => ['inventory_id' => 0, 'site_id' => 1, 'inventory_title' => 2, 'inventory_created' => 3, 'inventory_updated' => 4, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'INVENTORY_ID',
        'Inventory.Id' => 'INVENTORY_ID',
        'id' => 'INVENTORY_ID',
        'inventory.id' => 'INVENTORY_ID',
        'InventoryTableMap::COL_INVENTORY_ID' => 'INVENTORY_ID',
        'COL_INVENTORY_ID' => 'INVENTORY_ID',
        'inventory_id' => 'INVENTORY_ID',
        'inventory.inventory_id' => 'INVENTORY_ID',
        'SiteId' => 'SITE_ID',
        'Inventory.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'inventory.siteId' => 'SITE_ID',
        'InventoryTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'inventory.site_id' => 'SITE_ID',
        'Title' => 'INVENTORY_TITLE',
        'Inventory.Title' => 'INVENTORY_TITLE',
        'title' => 'INVENTORY_TITLE',
        'inventory.title' => 'INVENTORY_TITLE',
        'InventoryTableMap::COL_INVENTORY_TITLE' => 'INVENTORY_TITLE',
        'COL_INVENTORY_TITLE' => 'INVENTORY_TITLE',
        'inventory_title' => 'INVENTORY_TITLE',
        'inventory.inventory_title' => 'INVENTORY_TITLE',
        'CreatedAt' => 'INVENTORY_CREATED',
        'Inventory.CreatedAt' => 'INVENTORY_CREATED',
        'createdAt' => 'INVENTORY_CREATED',
        'inventory.createdAt' => 'INVENTORY_CREATED',
        'InventoryTableMap::COL_INVENTORY_CREATED' => 'INVENTORY_CREATED',
        'COL_INVENTORY_CREATED' => 'INVENTORY_CREATED',
        'inventory_created' => 'INVENTORY_CREATED',
        'inventory.inventory_created' => 'INVENTORY_CREATED',
        'UpdatedAt' => 'INVENTORY_UPDATED',
        'Inventory.UpdatedAt' => 'INVENTORY_UPDATED',
        'updatedAt' => 'INVENTORY_UPDATED',
        'inventory.updatedAt' => 'INVENTORY_UPDATED',
        'InventoryTableMap::COL_INVENTORY_UPDATED' => 'INVENTORY_UPDATED',
        'COL_INVENTORY_UPDATED' => 'INVENTORY_UPDATED',
        'inventory_updated' => 'INVENTORY_UPDATED',
        'inventory.inventory_updated' => 'INVENTORY_UPDATED',
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
        $this->setName('inventory');
        $this->setPhpName('Inventory');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Inventory');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('inventory_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('inventory_title', 'Title', 'VARCHAR', false, 32, null);
        $this->addColumn('inventory_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('inventory_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'inventory_created', 'update_column' => 'inventory_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? InventoryTableMap::CLASS_DEFAULT : InventoryTableMap::OM_CLASS;
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
     * @return array (Inventory object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = InventoryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = InventoryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + InventoryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = InventoryTableMap::OM_CLASS;
            /** @var Inventory $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            InventoryTableMap::addInstanceToPool($obj, $key);
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
            $key = InventoryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = InventoryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Inventory $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                InventoryTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(InventoryTableMap::COL_INVENTORY_ID);
            $criteria->addSelectColumn(InventoryTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(InventoryTableMap::COL_INVENTORY_TITLE);
            $criteria->addSelectColumn(InventoryTableMap::COL_INVENTORY_CREATED);
            $criteria->addSelectColumn(InventoryTableMap::COL_INVENTORY_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.inventory_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.inventory_title');
            $criteria->addSelectColumn($alias . '.inventory_created');
            $criteria->addSelectColumn($alias . '.inventory_updated');
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
            $criteria->removeSelectColumn(InventoryTableMap::COL_INVENTORY_ID);
            $criteria->removeSelectColumn(InventoryTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(InventoryTableMap::COL_INVENTORY_TITLE);
            $criteria->removeSelectColumn(InventoryTableMap::COL_INVENTORY_CREATED);
            $criteria->removeSelectColumn(InventoryTableMap::COL_INVENTORY_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.inventory_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.inventory_title');
            $criteria->removeSelectColumn($alias . '.inventory_created');
            $criteria->removeSelectColumn($alias . '.inventory_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(InventoryTableMap::DATABASE_NAME)->getTable(InventoryTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Inventory or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Inventory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(InventoryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Inventory) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(InventoryTableMap::DATABASE_NAME);
            $criteria->add(InventoryTableMap::COL_INVENTORY_ID, (array) $values, Criteria::IN);
        }

        $query = InventoryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            InventoryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                InventoryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the inventory table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return InventoryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Inventory or Criteria object.
     *
     * @param mixed $criteria Criteria or Inventory object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InventoryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Inventory object
        }

        if ($criteria->containsKey(InventoryTableMap::COL_INVENTORY_ID) && $criteria->keyContainsValue(InventoryTableMap::COL_INVENTORY_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.InventoryTableMap::COL_INVENTORY_ID.')');
        }


        // Set the correct dbName
        $query = InventoryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
