<?php

namespace Model\Map;

use Model\Wish;
use Model\WishQuery;
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
 * This class defines the structure of the 'wishes' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class WishTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.WishTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'wishes';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Wish';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Wish';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the wish_id field
     */
    const COL_WISH_ID = 'wishes.wish_id';

    /**
     * the column name for the wishlist_id field
     */
    const COL_WISHLIST_ID = 'wishes.wishlist_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'wishes.user_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'wishes.site_id';

    /**
     * the column name for the article_id field
     */
    const COL_ARTICLE_ID = 'wishes.article_id';

    /**
     * the column name for the wish_created field
     */
    const COL_WISH_CREATED = 'wishes.wish_created';

    /**
     * the column name for the wish_updated field
     */
    const COL_WISH_UPDATED = 'wishes.wish_updated';

    /**
     * the column name for the wish_bought field
     */
    const COL_WISH_BOUGHT = 'wishes.wish_bought';

    /**
     * the column name for the wish_deleted field
     */
    const COL_WISH_DELETED = 'wishes.wish_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'WishlistId', 'UserId', 'SiteId', 'ArticleId', 'CreatedAt', 'UpdatedAt', 'Bought', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'wishlistId', 'userId', 'siteId', 'articleId', 'createdAt', 'updatedAt', 'bought', 'deletedAt', ),
        self::TYPE_COLNAME       => array(WishTableMap::COL_WISH_ID, WishTableMap::COL_WISHLIST_ID, WishTableMap::COL_USER_ID, WishTableMap::COL_SITE_ID, WishTableMap::COL_ARTICLE_ID, WishTableMap::COL_WISH_CREATED, WishTableMap::COL_WISH_UPDATED, WishTableMap::COL_WISH_BOUGHT, WishTableMap::COL_WISH_DELETED, ),
        self::TYPE_FIELDNAME     => array('wish_id', 'wishlist_id', 'user_id', 'site_id', 'article_id', 'wish_created', 'wish_updated', 'wish_bought', 'wish_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'WishlistId' => 1, 'UserId' => 2, 'SiteId' => 3, 'ArticleId' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, 'Bought' => 7, 'DeletedAt' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'wishlistId' => 1, 'userId' => 2, 'siteId' => 3, 'articleId' => 4, 'createdAt' => 5, 'updatedAt' => 6, 'bought' => 7, 'deletedAt' => 8, ),
        self::TYPE_COLNAME       => array(WishTableMap::COL_WISH_ID => 0, WishTableMap::COL_WISHLIST_ID => 1, WishTableMap::COL_USER_ID => 2, WishTableMap::COL_SITE_ID => 3, WishTableMap::COL_ARTICLE_ID => 4, WishTableMap::COL_WISH_CREATED => 5, WishTableMap::COL_WISH_UPDATED => 6, WishTableMap::COL_WISH_BOUGHT => 7, WishTableMap::COL_WISH_DELETED => 8, ),
        self::TYPE_FIELDNAME     => array('wish_id' => 0, 'wishlist_id' => 1, 'user_id' => 2, 'site_id' => 3, 'article_id' => 4, 'wish_created' => 5, 'wish_updated' => 6, 'wish_bought' => 7, 'wish_deleted' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'WISH_ID',
        'Wish.Id' => 'WISH_ID',
        'id' => 'WISH_ID',
        'wish.id' => 'WISH_ID',
        'WishTableMap::COL_WISH_ID' => 'WISH_ID',
        'COL_WISH_ID' => 'WISH_ID',
        'wish_id' => 'WISH_ID',
        'wishes.wish_id' => 'WISH_ID',
        'WishlistId' => 'WISHLIST_ID',
        'Wish.WishlistId' => 'WISHLIST_ID',
        'wishlistId' => 'WISHLIST_ID',
        'wish.wishlistId' => 'WISHLIST_ID',
        'WishTableMap::COL_WISHLIST_ID' => 'WISHLIST_ID',
        'COL_WISHLIST_ID' => 'WISHLIST_ID',
        'wishlist_id' => 'WISHLIST_ID',
        'wishes.wishlist_id' => 'WISHLIST_ID',
        'UserId' => 'USER_ID',
        'Wish.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'wish.userId' => 'USER_ID',
        'WishTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'wishes.user_id' => 'USER_ID',
        'SiteId' => 'SITE_ID',
        'Wish.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'wish.siteId' => 'SITE_ID',
        'WishTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'wishes.site_id' => 'SITE_ID',
        'ArticleId' => 'ARTICLE_ID',
        'Wish.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'wish.articleId' => 'ARTICLE_ID',
        'WishTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'wishes.article_id' => 'ARTICLE_ID',
        'CreatedAt' => 'WISH_CREATED',
        'Wish.CreatedAt' => 'WISH_CREATED',
        'createdAt' => 'WISH_CREATED',
        'wish.createdAt' => 'WISH_CREATED',
        'WishTableMap::COL_WISH_CREATED' => 'WISH_CREATED',
        'COL_WISH_CREATED' => 'WISH_CREATED',
        'wish_created' => 'WISH_CREATED',
        'wishes.wish_created' => 'WISH_CREATED',
        'UpdatedAt' => 'WISH_UPDATED',
        'Wish.UpdatedAt' => 'WISH_UPDATED',
        'updatedAt' => 'WISH_UPDATED',
        'wish.updatedAt' => 'WISH_UPDATED',
        'WishTableMap::COL_WISH_UPDATED' => 'WISH_UPDATED',
        'COL_WISH_UPDATED' => 'WISH_UPDATED',
        'wish_updated' => 'WISH_UPDATED',
        'wishes.wish_updated' => 'WISH_UPDATED',
        'Bought' => 'WISH_BOUGHT',
        'Wish.Bought' => 'WISH_BOUGHT',
        'bought' => 'WISH_BOUGHT',
        'wish.bought' => 'WISH_BOUGHT',
        'WishTableMap::COL_WISH_BOUGHT' => 'WISH_BOUGHT',
        'COL_WISH_BOUGHT' => 'WISH_BOUGHT',
        'wish_bought' => 'WISH_BOUGHT',
        'wishes.wish_bought' => 'WISH_BOUGHT',
        'DeletedAt' => 'WISH_DELETED',
        'Wish.DeletedAt' => 'WISH_DELETED',
        'deletedAt' => 'WISH_DELETED',
        'wish.deletedAt' => 'WISH_DELETED',
        'WishTableMap::COL_WISH_DELETED' => 'WISH_DELETED',
        'COL_WISH_DELETED' => 'WISH_DELETED',
        'wish_deleted' => 'WISH_DELETED',
        'wishes.wish_deleted' => 'WISH_DELETED',
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
        $this->setName('wishes');
        $this->setPhpName('Wish');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Wish');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('wish_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('wishlist_id', 'WishlistId', 'INTEGER', false, null, null);
        $this->addColumn('user_id', 'UserId', 'INTEGER', false, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('article_id', 'ArticleId', 'INTEGER', false, 10, null);
        $this->addColumn('wish_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('wish_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('wish_bought', 'Bought', 'TIMESTAMP', false, null, null);
        $this->addColumn('wish_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'wish_created', 'update_column' => 'wish_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? WishTableMap::CLASS_DEFAULT : WishTableMap::OM_CLASS;
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
     * @return array           (Wish object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = WishTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = WishTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + WishTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = WishTableMap::OM_CLASS;
            /** @var Wish $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            WishTableMap::addInstanceToPool($obj, $key);
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
            $key = WishTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = WishTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Wish $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                WishTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(WishTableMap::COL_WISH_ID);
            $criteria->addSelectColumn(WishTableMap::COL_WISHLIST_ID);
            $criteria->addSelectColumn(WishTableMap::COL_USER_ID);
            $criteria->addSelectColumn(WishTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(WishTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(WishTableMap::COL_WISH_CREATED);
            $criteria->addSelectColumn(WishTableMap::COL_WISH_UPDATED);
            $criteria->addSelectColumn(WishTableMap::COL_WISH_BOUGHT);
            $criteria->addSelectColumn(WishTableMap::COL_WISH_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.wish_id');
            $criteria->addSelectColumn($alias . '.wishlist_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.wish_created');
            $criteria->addSelectColumn($alias . '.wish_updated');
            $criteria->addSelectColumn($alias . '.wish_bought');
            $criteria->addSelectColumn($alias . '.wish_deleted');
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
            $criteria->removeSelectColumn(WishTableMap::COL_WISH_ID);
            $criteria->removeSelectColumn(WishTableMap::COL_WISHLIST_ID);
            $criteria->removeSelectColumn(WishTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(WishTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(WishTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(WishTableMap::COL_WISH_CREATED);
            $criteria->removeSelectColumn(WishTableMap::COL_WISH_UPDATED);
            $criteria->removeSelectColumn(WishTableMap::COL_WISH_BOUGHT);
            $criteria->removeSelectColumn(WishTableMap::COL_WISH_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.wish_id');
            $criteria->removeSelectColumn($alias . '.wishlist_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.wish_created');
            $criteria->removeSelectColumn($alias . '.wish_updated');
            $criteria->removeSelectColumn($alias . '.wish_bought');
            $criteria->removeSelectColumn($alias . '.wish_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(WishTableMap::DATABASE_NAME)->getTable(WishTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Wish or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Wish object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(WishTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Wish) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(WishTableMap::DATABASE_NAME);
            $criteria->add(WishTableMap::COL_WISH_ID, (array) $values, Criteria::IN);
        }

        $query = WishQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            WishTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                WishTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the wishes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return WishQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Wish or Criteria object.
     *
     * @param mixed               $criteria Criteria or Wish object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WishTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Wish object
        }

        if ($criteria->containsKey(WishTableMap::COL_WISH_ID) && $criteria->keyContainsValue(WishTableMap::COL_WISH_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.WishTableMap::COL_WISH_ID.')');
        }


        // Set the correct dbName
        $query = WishQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // WishTableMap
