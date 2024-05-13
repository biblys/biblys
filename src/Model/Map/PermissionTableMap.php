<?php

namespace Model\Map;

use Model\Permission;
use Model\PermissionQuery;
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
 * This class defines the structure of the 'permissions' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class PermissionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.PermissionTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'permissions';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Permission';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Permission';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Permission';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the permission_id field
     */
    public const COL_PERMISSION_ID = 'permissions.permission_id';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'permissions.axys_account_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'permissions.user_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'permissions.site_id';

    /**
     * the column name for the permission_rank field
     */
    public const COL_PERMISSION_RANK = 'permissions.permission_rank';

    /**
     * the column name for the permission_last field
     */
    public const COL_PERMISSION_LAST = 'permissions.permission_last';

    /**
     * the column name for the permission_date field
     */
    public const COL_PERMISSION_DATE = 'permissions.permission_date';

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
        self::TYPE_PHPNAME       => ['Id', 'AxysAccountId', 'UserId', 'SiteId', 'Rank', 'Last', 'Date', ],
        self::TYPE_CAMELNAME     => ['id', 'axysAccountId', 'userId', 'siteId', 'rank', 'last', 'date', ],
        self::TYPE_COLNAME       => [PermissionTableMap::COL_PERMISSION_ID, PermissionTableMap::COL_AXYS_ACCOUNT_ID, PermissionTableMap::COL_USER_ID, PermissionTableMap::COL_SITE_ID, PermissionTableMap::COL_PERMISSION_RANK, PermissionTableMap::COL_PERMISSION_LAST, PermissionTableMap::COL_PERMISSION_DATE, ],
        self::TYPE_FIELDNAME     => ['permission_id', 'axys_account_id', 'user_id', 'site_id', 'permission_rank', 'permission_last', 'permission_date', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'AxysAccountId' => 1, 'UserId' => 2, 'SiteId' => 3, 'Rank' => 4, 'Last' => 5, 'Date' => 6, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'axysAccountId' => 1, 'userId' => 2, 'siteId' => 3, 'rank' => 4, 'last' => 5, 'date' => 6, ],
        self::TYPE_COLNAME       => [PermissionTableMap::COL_PERMISSION_ID => 0, PermissionTableMap::COL_AXYS_ACCOUNT_ID => 1, PermissionTableMap::COL_USER_ID => 2, PermissionTableMap::COL_SITE_ID => 3, PermissionTableMap::COL_PERMISSION_RANK => 4, PermissionTableMap::COL_PERMISSION_LAST => 5, PermissionTableMap::COL_PERMISSION_DATE => 6, ],
        self::TYPE_FIELDNAME     => ['permission_id' => 0, 'axys_account_id' => 1, 'user_id' => 2, 'site_id' => 3, 'permission_rank' => 4, 'permission_last' => 5, 'permission_date' => 6, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'PERMISSION_ID',
        'Permission.Id' => 'PERMISSION_ID',
        'id' => 'PERMISSION_ID',
        'permission.id' => 'PERMISSION_ID',
        'PermissionTableMap::COL_PERMISSION_ID' => 'PERMISSION_ID',
        'COL_PERMISSION_ID' => 'PERMISSION_ID',
        'permission_id' => 'PERMISSION_ID',
        'permissions.permission_id' => 'PERMISSION_ID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'Permission.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'permission.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'PermissionTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'permissions.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'UserId' => 'USER_ID',
        'Permission.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'permission.userId' => 'USER_ID',
        'PermissionTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'permissions.user_id' => 'USER_ID',
        'SiteId' => 'SITE_ID',
        'Permission.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'permission.siteId' => 'SITE_ID',
        'PermissionTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'permissions.site_id' => 'SITE_ID',
        'Rank' => 'PERMISSION_RANK',
        'Permission.Rank' => 'PERMISSION_RANK',
        'rank' => 'PERMISSION_RANK',
        'permission.rank' => 'PERMISSION_RANK',
        'PermissionTableMap::COL_PERMISSION_RANK' => 'PERMISSION_RANK',
        'COL_PERMISSION_RANK' => 'PERMISSION_RANK',
        'permission_rank' => 'PERMISSION_RANK',
        'permissions.permission_rank' => 'PERMISSION_RANK',
        'Last' => 'PERMISSION_LAST',
        'Permission.Last' => 'PERMISSION_LAST',
        'last' => 'PERMISSION_LAST',
        'permission.last' => 'PERMISSION_LAST',
        'PermissionTableMap::COL_PERMISSION_LAST' => 'PERMISSION_LAST',
        'COL_PERMISSION_LAST' => 'PERMISSION_LAST',
        'permission_last' => 'PERMISSION_LAST',
        'permissions.permission_last' => 'PERMISSION_LAST',
        'Date' => 'PERMISSION_DATE',
        'Permission.Date' => 'PERMISSION_DATE',
        'date' => 'PERMISSION_DATE',
        'permission.date' => 'PERMISSION_DATE',
        'PermissionTableMap::COL_PERMISSION_DATE' => 'PERMISSION_DATE',
        'COL_PERMISSION_DATE' => 'PERMISSION_DATE',
        'permission_date' => 'PERMISSION_DATE',
        'permissions.permission_date' => 'PERMISSION_DATE',
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
        $this->setName('permissions');
        $this->setPhpName('Permission');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Permission');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('permission_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('axys_account_id', 'AxysAccountId', 'INTEGER', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('permission_rank', 'Rank', 'VARCHAR', false, 8, null);
        $this->addColumn('permission_last', 'Last', 'TIMESTAMP', false, null, null);
        $this->addColumn('permission_date', 'Date', 'TIMESTAMP', false, null, null);
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
        return $withPrefix ? PermissionTableMap::CLASS_DEFAULT : PermissionTableMap::OM_CLASS;
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
     * @return array (Permission object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = PermissionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PermissionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PermissionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PermissionTableMap::OM_CLASS;
            /** @var Permission $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PermissionTableMap::addInstanceToPool($obj, $key);
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
            $key = PermissionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PermissionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Permission $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PermissionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PermissionTableMap::COL_PERMISSION_ID);
            $criteria->addSelectColumn(PermissionTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(PermissionTableMap::COL_USER_ID);
            $criteria->addSelectColumn(PermissionTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(PermissionTableMap::COL_PERMISSION_RANK);
            $criteria->addSelectColumn(PermissionTableMap::COL_PERMISSION_LAST);
            $criteria->addSelectColumn(PermissionTableMap::COL_PERMISSION_DATE);
        } else {
            $criteria->addSelectColumn($alias . '.permission_id');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.permission_rank');
            $criteria->addSelectColumn($alias . '.permission_last');
            $criteria->addSelectColumn($alias . '.permission_date');
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
            $criteria->removeSelectColumn(PermissionTableMap::COL_PERMISSION_ID);
            $criteria->removeSelectColumn(PermissionTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(PermissionTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(PermissionTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(PermissionTableMap::COL_PERMISSION_RANK);
            $criteria->removeSelectColumn(PermissionTableMap::COL_PERMISSION_LAST);
            $criteria->removeSelectColumn(PermissionTableMap::COL_PERMISSION_DATE);
        } else {
            $criteria->removeSelectColumn($alias . '.permission_id');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.permission_rank');
            $criteria->removeSelectColumn($alias . '.permission_last');
            $criteria->removeSelectColumn($alias . '.permission_date');
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
        return Propel::getServiceContainer()->getDatabaseMap(PermissionTableMap::DATABASE_NAME)->getTable(PermissionTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Permission or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Permission object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Permission) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PermissionTableMap::DATABASE_NAME);
            $criteria->add(PermissionTableMap::COL_PERMISSION_ID, (array) $values, Criteria::IN);
        }

        $query = PermissionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PermissionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PermissionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the permissions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return PermissionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Permission or Criteria object.
     *
     * @param mixed $criteria Criteria or Permission object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Permission object
        }

        if ($criteria->containsKey(PermissionTableMap::COL_PERMISSION_ID) && $criteria->keyContainsValue(PermissionTableMap::COL_PERMISSION_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PermissionTableMap::COL_PERMISSION_ID.')');
        }


        // Set the correct dbName
        $query = PermissionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
