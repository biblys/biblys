<?php

namespace Model\Map;

use Model\Wishlist;
use Model\WishlistQuery;
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
 * This class defines the structure of the 'wishlist' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class WishlistTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.WishlistTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'wishlist';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Wishlist';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Wishlist';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Wishlist';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the wishlist_id field
     */
    public const COL_WISHLIST_ID = 'wishlist.wishlist_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'wishlist.site_id';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'wishlist.axys_account_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'wishlist.user_id';

    /**
     * the column name for the wishlist_name field
     */
    public const COL_WISHLIST_NAME = 'wishlist.wishlist_name';

    /**
     * the column name for the wishlist_current field
     */
    public const COL_WISHLIST_CURRENT = 'wishlist.wishlist_current';

    /**
     * the column name for the wishlist_public field
     */
    public const COL_WISHLIST_PUBLIC = 'wishlist.wishlist_public';

    /**
     * the column name for the wishlist_created field
     */
    public const COL_WISHLIST_CREATED = 'wishlist.wishlist_created';

    /**
     * the column name for the wishlist_updated field
     */
    public const COL_WISHLIST_UPDATED = 'wishlist.wishlist_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'AxysAccountId', 'UserId', 'Name', 'Current', 'Public', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'axysAccountId', 'userId', 'name', 'current', 'public', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [WishlistTableMap::COL_WISHLIST_ID, WishlistTableMap::COL_SITE_ID, WishlistTableMap::COL_AXYS_ACCOUNT_ID, WishlistTableMap::COL_USER_ID, WishlistTableMap::COL_WISHLIST_NAME, WishlistTableMap::COL_WISHLIST_CURRENT, WishlistTableMap::COL_WISHLIST_PUBLIC, WishlistTableMap::COL_WISHLIST_CREATED, WishlistTableMap::COL_WISHLIST_UPDATED, ],
        self::TYPE_FIELDNAME     => ['wishlist_id', 'site_id', 'axys_account_id', 'user_id', 'wishlist_name', 'wishlist_current', 'wishlist_public', 'wishlist_created', 'wishlist_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'AxysAccountId' => 2, 'UserId' => 3, 'Name' => 4, 'Current' => 5, 'Public' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'axysAccountId' => 2, 'userId' => 3, 'name' => 4, 'current' => 5, 'public' => 6, 'createdAt' => 7, 'updatedAt' => 8, ],
        self::TYPE_COLNAME       => [WishlistTableMap::COL_WISHLIST_ID => 0, WishlistTableMap::COL_SITE_ID => 1, WishlistTableMap::COL_AXYS_ACCOUNT_ID => 2, WishlistTableMap::COL_USER_ID => 3, WishlistTableMap::COL_WISHLIST_NAME => 4, WishlistTableMap::COL_WISHLIST_CURRENT => 5, WishlistTableMap::COL_WISHLIST_PUBLIC => 6, WishlistTableMap::COL_WISHLIST_CREATED => 7, WishlistTableMap::COL_WISHLIST_UPDATED => 8, ],
        self::TYPE_FIELDNAME     => ['wishlist_id' => 0, 'site_id' => 1, 'axys_account_id' => 2, 'user_id' => 3, 'wishlist_name' => 4, 'wishlist_current' => 5, 'wishlist_public' => 6, 'wishlist_created' => 7, 'wishlist_updated' => 8, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'WISHLIST_ID',
        'Wishlist.Id' => 'WISHLIST_ID',
        'id' => 'WISHLIST_ID',
        'wishlist.id' => 'WISHLIST_ID',
        'WishlistTableMap::COL_WISHLIST_ID' => 'WISHLIST_ID',
        'COL_WISHLIST_ID' => 'WISHLIST_ID',
        'wishlist_id' => 'WISHLIST_ID',
        'wishlist.wishlist_id' => 'WISHLIST_ID',
        'SiteId' => 'SITE_ID',
        'Wishlist.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'wishlist.siteId' => 'SITE_ID',
        'WishlistTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'wishlist.site_id' => 'SITE_ID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'Wishlist.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'wishlist.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'WishlistTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'wishlist.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'UserId' => 'USER_ID',
        'Wishlist.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'wishlist.userId' => 'USER_ID',
        'WishlistTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'wishlist.user_id' => 'USER_ID',
        'Name' => 'WISHLIST_NAME',
        'Wishlist.Name' => 'WISHLIST_NAME',
        'name' => 'WISHLIST_NAME',
        'wishlist.name' => 'WISHLIST_NAME',
        'WishlistTableMap::COL_WISHLIST_NAME' => 'WISHLIST_NAME',
        'COL_WISHLIST_NAME' => 'WISHLIST_NAME',
        'wishlist_name' => 'WISHLIST_NAME',
        'wishlist.wishlist_name' => 'WISHLIST_NAME',
        'Current' => 'WISHLIST_CURRENT',
        'Wishlist.Current' => 'WISHLIST_CURRENT',
        'current' => 'WISHLIST_CURRENT',
        'wishlist.current' => 'WISHLIST_CURRENT',
        'WishlistTableMap::COL_WISHLIST_CURRENT' => 'WISHLIST_CURRENT',
        'COL_WISHLIST_CURRENT' => 'WISHLIST_CURRENT',
        'wishlist_current' => 'WISHLIST_CURRENT',
        'wishlist.wishlist_current' => 'WISHLIST_CURRENT',
        'Public' => 'WISHLIST_PUBLIC',
        'Wishlist.Public' => 'WISHLIST_PUBLIC',
        'public' => 'WISHLIST_PUBLIC',
        'wishlist.public' => 'WISHLIST_PUBLIC',
        'WishlistTableMap::COL_WISHLIST_PUBLIC' => 'WISHLIST_PUBLIC',
        'COL_WISHLIST_PUBLIC' => 'WISHLIST_PUBLIC',
        'wishlist_public' => 'WISHLIST_PUBLIC',
        'wishlist.wishlist_public' => 'WISHLIST_PUBLIC',
        'CreatedAt' => 'WISHLIST_CREATED',
        'Wishlist.CreatedAt' => 'WISHLIST_CREATED',
        'createdAt' => 'WISHLIST_CREATED',
        'wishlist.createdAt' => 'WISHLIST_CREATED',
        'WishlistTableMap::COL_WISHLIST_CREATED' => 'WISHLIST_CREATED',
        'COL_WISHLIST_CREATED' => 'WISHLIST_CREATED',
        'wishlist_created' => 'WISHLIST_CREATED',
        'wishlist.wishlist_created' => 'WISHLIST_CREATED',
        'UpdatedAt' => 'WISHLIST_UPDATED',
        'Wishlist.UpdatedAt' => 'WISHLIST_UPDATED',
        'updatedAt' => 'WISHLIST_UPDATED',
        'wishlist.updatedAt' => 'WISHLIST_UPDATED',
        'WishlistTableMap::COL_WISHLIST_UPDATED' => 'WISHLIST_UPDATED',
        'COL_WISHLIST_UPDATED' => 'WISHLIST_UPDATED',
        'wishlist_updated' => 'WISHLIST_UPDATED',
        'wishlist.wishlist_updated' => 'WISHLIST_UPDATED',
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
        $this->setName('wishlist');
        $this->setPhpName('Wishlist');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Wishlist');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('wishlist_id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, 10, null);
        $this->addForeignKey('axys_account_id', 'AxysAccountId', 'INTEGER', 'axys_accounts', 'axys_account_id', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, 10, null);
        $this->addColumn('wishlist_name', 'Name', 'VARCHAR', false, 128, null);
        $this->addColumn('wishlist_current', 'Current', 'BOOLEAN', false, 1, null);
        $this->addColumn('wishlist_public', 'Public', 'BOOLEAN', false, 1, null);
        $this->addColumn('wishlist_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('wishlist_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Site', '\\Model\\Site', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, null, false);
        $this->addRelation('User', '\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('AxysAccount', '\\Model\\AxysAccount', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':axys_account_id',
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
            'timestampable' => ['create_column' => 'wishlist_created', 'update_column' => 'wishlist_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? WishlistTableMap::CLASS_DEFAULT : WishlistTableMap::OM_CLASS;
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
     * @return array (Wishlist object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = WishlistTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = WishlistTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + WishlistTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = WishlistTableMap::OM_CLASS;
            /** @var Wishlist $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            WishlistTableMap::addInstanceToPool($obj, $key);
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
            $key = WishlistTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = WishlistTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Wishlist $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                WishlistTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_ID);
            $criteria->addSelectColumn(WishlistTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(WishlistTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(WishlistTableMap::COL_USER_ID);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_NAME);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_CURRENT);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_PUBLIC);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_CREATED);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.wishlist_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.wishlist_name');
            $criteria->addSelectColumn($alias . '.wishlist_current');
            $criteria->addSelectColumn($alias . '.wishlist_public');
            $criteria->addSelectColumn($alias . '.wishlist_created');
            $criteria->addSelectColumn($alias . '.wishlist_updated');
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
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_ID);
            $criteria->removeSelectColumn(WishlistTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(WishlistTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(WishlistTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_NAME);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_CURRENT);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_PUBLIC);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_CREATED);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.wishlist_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.wishlist_name');
            $criteria->removeSelectColumn($alias . '.wishlist_current');
            $criteria->removeSelectColumn($alias . '.wishlist_public');
            $criteria->removeSelectColumn($alias . '.wishlist_created');
            $criteria->removeSelectColumn($alias . '.wishlist_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(WishlistTableMap::DATABASE_NAME)->getTable(WishlistTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Wishlist or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Wishlist object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(WishlistTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Wishlist) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(WishlistTableMap::DATABASE_NAME);
            $criteria->add(WishlistTableMap::COL_WISHLIST_ID, (array) $values, Criteria::IN);
        }

        $query = WishlistQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            WishlistTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                WishlistTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the wishlist table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return WishlistQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Wishlist or Criteria object.
     *
     * @param mixed $criteria Criteria or Wishlist object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WishlistTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Wishlist object
        }

        if ($criteria->containsKey(WishlistTableMap::COL_WISHLIST_ID) && $criteria->keyContainsValue(WishlistTableMap::COL_WISHLIST_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.WishlistTableMap::COL_WISHLIST_ID.')');
        }


        // Set the correct dbName
        $query = WishlistQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
