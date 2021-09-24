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
    const CLASS_NAME = 'Model.Map.WishlistTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'wishlist';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Wishlist';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Wishlist';

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
     * the column name for the wishlist_id field
     */
    const COL_WISHLIST_ID = 'wishlist.wishlist_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'wishlist.user_id';

    /**
     * the column name for the wishlist_name field
     */
    const COL_WISHLIST_NAME = 'wishlist.wishlist_name';

    /**
     * the column name for the wishlist_current field
     */
    const COL_WISHLIST_CURRENT = 'wishlist.wishlist_current';

    /**
     * the column name for the wishlist_public field
     */
    const COL_WISHLIST_PUBLIC = 'wishlist.wishlist_public';

    /**
     * the column name for the wishlist_created field
     */
    const COL_WISHLIST_CREATED = 'wishlist.wishlist_created';

    /**
     * the column name for the wishlist_updated field
     */
    const COL_WISHLIST_UPDATED = 'wishlist.wishlist_updated';

    /**
     * the column name for the wishlist_deleted field
     */
    const COL_WISHLIST_DELETED = 'wishlist.wishlist_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'UserId', 'Name', 'Current', 'Public', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'userId', 'name', 'current', 'public', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(WishlistTableMap::COL_WISHLIST_ID, WishlistTableMap::COL_USER_ID, WishlistTableMap::COL_WISHLIST_NAME, WishlistTableMap::COL_WISHLIST_CURRENT, WishlistTableMap::COL_WISHLIST_PUBLIC, WishlistTableMap::COL_WISHLIST_CREATED, WishlistTableMap::COL_WISHLIST_UPDATED, WishlistTableMap::COL_WISHLIST_DELETED, ),
        self::TYPE_FIELDNAME     => array('wishlist_id', 'user_id', 'wishlist_name', 'wishlist_current', 'wishlist_public', 'wishlist_created', 'wishlist_updated', 'wishlist_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'UserId' => 1, 'Name' => 2, 'Current' => 3, 'Public' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, 'DeletedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'userId' => 1, 'name' => 2, 'current' => 3, 'public' => 4, 'createdAt' => 5, 'updatedAt' => 6, 'deletedAt' => 7, ),
        self::TYPE_COLNAME       => array(WishlistTableMap::COL_WISHLIST_ID => 0, WishlistTableMap::COL_USER_ID => 1, WishlistTableMap::COL_WISHLIST_NAME => 2, WishlistTableMap::COL_WISHLIST_CURRENT => 3, WishlistTableMap::COL_WISHLIST_PUBLIC => 4, WishlistTableMap::COL_WISHLIST_CREATED => 5, WishlistTableMap::COL_WISHLIST_UPDATED => 6, WishlistTableMap::COL_WISHLIST_DELETED => 7, ),
        self::TYPE_FIELDNAME     => array('wishlist_id' => 0, 'user_id' => 1, 'wishlist_name' => 2, 'wishlist_current' => 3, 'wishlist_public' => 4, 'wishlist_created' => 5, 'wishlist_updated' => 6, 'wishlist_deleted' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
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
        'DeletedAt' => 'WISHLIST_DELETED',
        'Wishlist.DeletedAt' => 'WISHLIST_DELETED',
        'deletedAt' => 'WISHLIST_DELETED',
        'wishlist.deletedAt' => 'WISHLIST_DELETED',
        'WishlistTableMap::COL_WISHLIST_DELETED' => 'WISHLIST_DELETED',
        'COL_WISHLIST_DELETED' => 'WISHLIST_DELETED',
        'wishlist_deleted' => 'WISHLIST_DELETED',
        'wishlist.wishlist_deleted' => 'WISHLIST_DELETED',
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
        $this->setName('wishlist');
        $this->setPhpName('Wishlist');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Wishlist');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('wishlist_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('user_id', 'UserId', 'INTEGER', false, null, null);
        $this->addColumn('wishlist_name', 'Name', 'VARCHAR', false, 128, null);
        $this->addColumn('wishlist_current', 'Current', 'BOOLEAN', false, 1, null);
        $this->addColumn('wishlist_public', 'Public', 'BOOLEAN', false, 1, null);
        $this->addColumn('wishlist_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('wishlist_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('wishlist_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => ['create_column' => 'wishlist_created', 'update_column' => 'wishlist_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
        );
    } // getBehaviors()

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
        return $withPrefix ? WishlistTableMap::CLASS_DEFAULT : WishlistTableMap::OM_CLASS;
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
     * @return array           (Wishlist object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_ID);
            $criteria->addSelectColumn(WishlistTableMap::COL_USER_ID);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_NAME);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_CURRENT);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_PUBLIC);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_CREATED);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_UPDATED);
            $criteria->addSelectColumn(WishlistTableMap::COL_WISHLIST_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.wishlist_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.wishlist_name');
            $criteria->addSelectColumn($alias . '.wishlist_current');
            $criteria->addSelectColumn($alias . '.wishlist_public');
            $criteria->addSelectColumn($alias . '.wishlist_created');
            $criteria->addSelectColumn($alias . '.wishlist_updated');
            $criteria->addSelectColumn($alias . '.wishlist_deleted');
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
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_ID);
            $criteria->removeSelectColumn(WishlistTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_NAME);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_CURRENT);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_PUBLIC);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_CREATED);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_UPDATED);
            $criteria->removeSelectColumn(WishlistTableMap::COL_WISHLIST_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.wishlist_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.wishlist_name');
            $criteria->removeSelectColumn($alias . '.wishlist_current');
            $criteria->removeSelectColumn($alias . '.wishlist_public');
            $criteria->removeSelectColumn($alias . '.wishlist_created');
            $criteria->removeSelectColumn($alias . '.wishlist_updated');
            $criteria->removeSelectColumn($alias . '.wishlist_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(WishlistTableMap::DATABASE_NAME)->getTable(WishlistTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Wishlist or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Wishlist object or primary key or array of primary keys
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
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return WishlistQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Wishlist or Criteria object.
     *
     * @param mixed               $criteria Criteria or Wishlist object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
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

} // WishlistTableMap
