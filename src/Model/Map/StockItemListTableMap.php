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

use Model\StockItemList;
use Model\StockItemListQuery;
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
 * This class defines the structure of the 'lists' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class StockItemListTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.StockItemListTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'lists';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'StockItemList';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\StockItemList';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.StockItemList';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the list_id field
     */
    public const COL_LIST_ID = 'lists.list_id';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'lists.axys_account_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'lists.user_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'lists.site_id';

    /**
     * the column name for the list_title field
     */
    public const COL_LIST_TITLE = 'lists.list_title';

    /**
     * the column name for the list_url field
     */
    public const COL_LIST_URL = 'lists.list_url';

    /**
     * the column name for the list_created field
     */
    public const COL_LIST_CREATED = 'lists.list_created';

    /**
     * the column name for the list_updated field
     */
    public const COL_LIST_UPDATED = 'lists.list_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'AxysAccountId', 'UserId', 'SiteId', 'Title', 'Url', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'axysAccountId', 'userId', 'siteId', 'title', 'url', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [StockItemListTableMap::COL_LIST_ID, StockItemListTableMap::COL_AXYS_ACCOUNT_ID, StockItemListTableMap::COL_USER_ID, StockItemListTableMap::COL_SITE_ID, StockItemListTableMap::COL_LIST_TITLE, StockItemListTableMap::COL_LIST_URL, StockItemListTableMap::COL_LIST_CREATED, StockItemListTableMap::COL_LIST_UPDATED, ],
        self::TYPE_FIELDNAME     => ['list_id', 'axys_account_id', 'user_id', 'site_id', 'list_title', 'list_url', 'list_created', 'list_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'AxysAccountId' => 1, 'UserId' => 2, 'SiteId' => 3, 'Title' => 4, 'Url' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'axysAccountId' => 1, 'userId' => 2, 'siteId' => 3, 'title' => 4, 'url' => 5, 'createdAt' => 6, 'updatedAt' => 7, ],
        self::TYPE_COLNAME       => [StockItemListTableMap::COL_LIST_ID => 0, StockItemListTableMap::COL_AXYS_ACCOUNT_ID => 1, StockItemListTableMap::COL_USER_ID => 2, StockItemListTableMap::COL_SITE_ID => 3, StockItemListTableMap::COL_LIST_TITLE => 4, StockItemListTableMap::COL_LIST_URL => 5, StockItemListTableMap::COL_LIST_CREATED => 6, StockItemListTableMap::COL_LIST_UPDATED => 7, ],
        self::TYPE_FIELDNAME     => ['list_id' => 0, 'axys_account_id' => 1, 'user_id' => 2, 'site_id' => 3, 'list_title' => 4, 'list_url' => 5, 'list_created' => 6, 'list_updated' => 7, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'LIST_ID',
        'StockItemList.Id' => 'LIST_ID',
        'id' => 'LIST_ID',
        'stockItemList.id' => 'LIST_ID',
        'StockItemListTableMap::COL_LIST_ID' => 'LIST_ID',
        'COL_LIST_ID' => 'LIST_ID',
        'list_id' => 'LIST_ID',
        'lists.list_id' => 'LIST_ID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'StockItemList.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'stockItemList.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'StockItemListTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'lists.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'UserId' => 'USER_ID',
        'StockItemList.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'stockItemList.userId' => 'USER_ID',
        'StockItemListTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'lists.user_id' => 'USER_ID',
        'SiteId' => 'SITE_ID',
        'StockItemList.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'stockItemList.siteId' => 'SITE_ID',
        'StockItemListTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'lists.site_id' => 'SITE_ID',
        'Title' => 'LIST_TITLE',
        'StockItemList.Title' => 'LIST_TITLE',
        'title' => 'LIST_TITLE',
        'stockItemList.title' => 'LIST_TITLE',
        'StockItemListTableMap::COL_LIST_TITLE' => 'LIST_TITLE',
        'COL_LIST_TITLE' => 'LIST_TITLE',
        'list_title' => 'LIST_TITLE',
        'lists.list_title' => 'LIST_TITLE',
        'Url' => 'LIST_URL',
        'StockItemList.Url' => 'LIST_URL',
        'url' => 'LIST_URL',
        'stockItemList.url' => 'LIST_URL',
        'StockItemListTableMap::COL_LIST_URL' => 'LIST_URL',
        'COL_LIST_URL' => 'LIST_URL',
        'list_url' => 'LIST_URL',
        'lists.list_url' => 'LIST_URL',
        'CreatedAt' => 'LIST_CREATED',
        'StockItemList.CreatedAt' => 'LIST_CREATED',
        'createdAt' => 'LIST_CREATED',
        'stockItemList.createdAt' => 'LIST_CREATED',
        'StockItemListTableMap::COL_LIST_CREATED' => 'LIST_CREATED',
        'COL_LIST_CREATED' => 'LIST_CREATED',
        'list_created' => 'LIST_CREATED',
        'lists.list_created' => 'LIST_CREATED',
        'UpdatedAt' => 'LIST_UPDATED',
        'StockItemList.UpdatedAt' => 'LIST_UPDATED',
        'updatedAt' => 'LIST_UPDATED',
        'stockItemList.updatedAt' => 'LIST_UPDATED',
        'StockItemListTableMap::COL_LIST_UPDATED' => 'LIST_UPDATED',
        'COL_LIST_UPDATED' => 'LIST_UPDATED',
        'list_updated' => 'LIST_UPDATED',
        'lists.list_updated' => 'LIST_UPDATED',
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
        $this->setName('lists');
        $this->setPhpName('StockItemList');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\StockItemList');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('list_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('axys_account_id', 'AxysAccountId', 'INTEGER', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addColumn('list_title', 'Title', 'VARCHAR', false, 256, null);
        $this->addColumn('list_url', 'Url', 'VARCHAR', false, 256, null);
        $this->addColumn('list_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('list_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('User', '\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Site', '\\Model\\Site', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, null, false);
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
            'timestampable' => ['create_column' => 'list_created', 'update_column' => 'list_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? StockItemListTableMap::CLASS_DEFAULT : StockItemListTableMap::OM_CLASS;
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
     * @return array (StockItemList object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = StockItemListTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = StockItemListTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + StockItemListTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StockItemListTableMap::OM_CLASS;
            /** @var StockItemList $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            StockItemListTableMap::addInstanceToPool($obj, $key);
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
            $key = StockItemListTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = StockItemListTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var StockItemList $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StockItemListTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(StockItemListTableMap::COL_LIST_ID);
            $criteria->addSelectColumn(StockItemListTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(StockItemListTableMap::COL_USER_ID);
            $criteria->addSelectColumn(StockItemListTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(StockItemListTableMap::COL_LIST_TITLE);
            $criteria->addSelectColumn(StockItemListTableMap::COL_LIST_URL);
            $criteria->addSelectColumn(StockItemListTableMap::COL_LIST_CREATED);
            $criteria->addSelectColumn(StockItemListTableMap::COL_LIST_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.list_id');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.list_title');
            $criteria->addSelectColumn($alias . '.list_url');
            $criteria->addSelectColumn($alias . '.list_created');
            $criteria->addSelectColumn($alias . '.list_updated');
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
            $criteria->removeSelectColumn(StockItemListTableMap::COL_LIST_ID);
            $criteria->removeSelectColumn(StockItemListTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(StockItemListTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(StockItemListTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(StockItemListTableMap::COL_LIST_TITLE);
            $criteria->removeSelectColumn(StockItemListTableMap::COL_LIST_URL);
            $criteria->removeSelectColumn(StockItemListTableMap::COL_LIST_CREATED);
            $criteria->removeSelectColumn(StockItemListTableMap::COL_LIST_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.list_id');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.list_title');
            $criteria->removeSelectColumn($alias . '.list_url');
            $criteria->removeSelectColumn($alias . '.list_created');
            $criteria->removeSelectColumn($alias . '.list_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(StockItemListTableMap::DATABASE_NAME)->getTable(StockItemListTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a StockItemList or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or StockItemList object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(StockItemListTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\StockItemList) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StockItemListTableMap::DATABASE_NAME);
            $criteria->add(StockItemListTableMap::COL_LIST_ID, (array) $values, Criteria::IN);
        }

        $query = StockItemListQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            StockItemListTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                StockItemListTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the lists table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return StockItemListQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a StockItemList or Criteria object.
     *
     * @param mixed $criteria Criteria or StockItemList object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StockItemListTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from StockItemList object
        }

        if ($criteria->containsKey(StockItemListTableMap::COL_LIST_ID) && $criteria->keyContainsValue(StockItemListTableMap::COL_LIST_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.StockItemListTableMap::COL_LIST_ID.')');
        }


        // Set the correct dbName
        $query = StockItemListQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
