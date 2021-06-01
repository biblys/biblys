<?php

namespace Model\Map;

use Model\List;
use Model\ListQuery;
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
class ListTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.ListTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'lists';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\List';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.List';

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
     * the column name for the list_id field
     */
    const COL_LIST_ID = 'lists.list_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'lists.user_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'lists.site_id';

    /**
     * the column name for the list_title field
     */
    const COL_LIST_TITLE = 'lists.list_title';

    /**
     * the column name for the list_url field
     */
    const COL_LIST_URL = 'lists.list_url';

    /**
     * the column name for the list_created field
     */
    const COL_LIST_CREATED = 'lists.list_created';

    /**
     * the column name for the list_updated field
     */
    const COL_LIST_UPDATED = 'lists.list_updated';

    /**
     * the column name for the list_deleted field
     */
    const COL_LIST_DELETED = 'lists.list_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'UserId', 'SiteId', 'Title', 'Url', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'userId', 'siteId', 'title', 'url', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(ListTableMap::COL_LIST_ID, ListTableMap::COL_USER_ID, ListTableMap::COL_SITE_ID, ListTableMap::COL_LIST_TITLE, ListTableMap::COL_LIST_URL, ListTableMap::COL_LIST_CREATED, ListTableMap::COL_LIST_UPDATED, ListTableMap::COL_LIST_DELETED, ),
        self::TYPE_FIELDNAME     => array('list_id', 'user_id', 'site_id', 'list_title', 'list_url', 'list_created', 'list_updated', 'list_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'UserId' => 1, 'SiteId' => 2, 'Title' => 3, 'Url' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, 'DeletedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'userId' => 1, 'siteId' => 2, 'title' => 3, 'url' => 4, 'createdAt' => 5, 'updatedAt' => 6, 'deletedAt' => 7, ),
        self::TYPE_COLNAME       => array(ListTableMap::COL_LIST_ID => 0, ListTableMap::COL_USER_ID => 1, ListTableMap::COL_SITE_ID => 2, ListTableMap::COL_LIST_TITLE => 3, ListTableMap::COL_LIST_URL => 4, ListTableMap::COL_LIST_CREATED => 5, ListTableMap::COL_LIST_UPDATED => 6, ListTableMap::COL_LIST_DELETED => 7, ),
        self::TYPE_FIELDNAME     => array('list_id' => 0, 'user_id' => 1, 'site_id' => 2, 'list_title' => 3, 'list_url' => 4, 'list_created' => 5, 'list_updated' => 6, 'list_deleted' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'LIST_ID',
        'List.Id' => 'LIST_ID',
        'id' => 'LIST_ID',
        'list.id' => 'LIST_ID',
        'ListTableMap::COL_LIST_ID' => 'LIST_ID',
        'COL_LIST_ID' => 'LIST_ID',
        'list_id' => 'LIST_ID',
        'lists.list_id' => 'LIST_ID',
        'UserId' => 'USER_ID',
        'List.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'list.userId' => 'USER_ID',
        'ListTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'lists.user_id' => 'USER_ID',
        'SiteId' => 'SITE_ID',
        'List.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'list.siteId' => 'SITE_ID',
        'ListTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'lists.site_id' => 'SITE_ID',
        'Title' => 'LIST_TITLE',
        'List.Title' => 'LIST_TITLE',
        'title' => 'LIST_TITLE',
        'list.title' => 'LIST_TITLE',
        'ListTableMap::COL_LIST_TITLE' => 'LIST_TITLE',
        'COL_LIST_TITLE' => 'LIST_TITLE',
        'list_title' => 'LIST_TITLE',
        'lists.list_title' => 'LIST_TITLE',
        'Url' => 'LIST_URL',
        'List.Url' => 'LIST_URL',
        'url' => 'LIST_URL',
        'list.url' => 'LIST_URL',
        'ListTableMap::COL_LIST_URL' => 'LIST_URL',
        'COL_LIST_URL' => 'LIST_URL',
        'list_url' => 'LIST_URL',
        'lists.list_url' => 'LIST_URL',
        'CreatedAt' => 'LIST_CREATED',
        'List.CreatedAt' => 'LIST_CREATED',
        'createdAt' => 'LIST_CREATED',
        'list.createdAt' => 'LIST_CREATED',
        'ListTableMap::COL_LIST_CREATED' => 'LIST_CREATED',
        'COL_LIST_CREATED' => 'LIST_CREATED',
        'list_created' => 'LIST_CREATED',
        'lists.list_created' => 'LIST_CREATED',
        'UpdatedAt' => 'LIST_UPDATED',
        'List.UpdatedAt' => 'LIST_UPDATED',
        'updatedAt' => 'LIST_UPDATED',
        'list.updatedAt' => 'LIST_UPDATED',
        'ListTableMap::COL_LIST_UPDATED' => 'LIST_UPDATED',
        'COL_LIST_UPDATED' => 'LIST_UPDATED',
        'list_updated' => 'LIST_UPDATED',
        'lists.list_updated' => 'LIST_UPDATED',
        'DeletedAt' => 'LIST_DELETED',
        'List.DeletedAt' => 'LIST_DELETED',
        'deletedAt' => 'LIST_DELETED',
        'list.deletedAt' => 'LIST_DELETED',
        'ListTableMap::COL_LIST_DELETED' => 'LIST_DELETED',
        'COL_LIST_DELETED' => 'LIST_DELETED',
        'list_deleted' => 'LIST_DELETED',
        'lists.list_deleted' => 'LIST_DELETED',
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
        $this->setName('lists');
        $this->setPhpName('List');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\List');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('list_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('user_id', 'UserId', 'INTEGER', false, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('list_title', 'Title', 'VARCHAR', false, 256, null);
        $this->addColumn('list_url', 'Url', 'VARCHAR', false, 256, null);
        $this->addColumn('list_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('list_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('list_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => array('create_column' => 'list_created', 'update_column' => 'list_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
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
        return $withPrefix ? ListTableMap::CLASS_DEFAULT : ListTableMap::OM_CLASS;
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
     * @return array           (List object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ListTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ListTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ListTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ListTableMap::OM_CLASS;
            /** @var List $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ListTableMap::addInstanceToPool($obj, $key);
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
            $key = ListTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ListTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var List $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ListTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ListTableMap::COL_LIST_ID);
            $criteria->addSelectColumn(ListTableMap::COL_USER_ID);
            $criteria->addSelectColumn(ListTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(ListTableMap::COL_LIST_TITLE);
            $criteria->addSelectColumn(ListTableMap::COL_LIST_URL);
            $criteria->addSelectColumn(ListTableMap::COL_LIST_CREATED);
            $criteria->addSelectColumn(ListTableMap::COL_LIST_UPDATED);
            $criteria->addSelectColumn(ListTableMap::COL_LIST_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.list_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.list_title');
            $criteria->addSelectColumn($alias . '.list_url');
            $criteria->addSelectColumn($alias . '.list_created');
            $criteria->addSelectColumn($alias . '.list_updated');
            $criteria->addSelectColumn($alias . '.list_deleted');
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
            $criteria->removeSelectColumn(ListTableMap::COL_LIST_ID);
            $criteria->removeSelectColumn(ListTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(ListTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(ListTableMap::COL_LIST_TITLE);
            $criteria->removeSelectColumn(ListTableMap::COL_LIST_URL);
            $criteria->removeSelectColumn(ListTableMap::COL_LIST_CREATED);
            $criteria->removeSelectColumn(ListTableMap::COL_LIST_UPDATED);
            $criteria->removeSelectColumn(ListTableMap::COL_LIST_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.list_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.list_title');
            $criteria->removeSelectColumn($alias . '.list_url');
            $criteria->removeSelectColumn($alias . '.list_created');
            $criteria->removeSelectColumn($alias . '.list_updated');
            $criteria->removeSelectColumn($alias . '.list_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(ListTableMap::DATABASE_NAME)->getTable(ListTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ListTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ListTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ListTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a List or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or List object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ListTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\List) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ListTableMap::DATABASE_NAME);
            $criteria->add(ListTableMap::COL_LIST_ID, (array) $values, Criteria::IN);
        }

        $query = ListQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ListTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ListTableMap::removeInstanceFromPool($singleval);
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
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ListQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a List or Criteria object.
     *
     * @param mixed               $criteria Criteria or List object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ListTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from List object
        }

        if ($criteria->containsKey(ListTableMap::COL_LIST_ID) && $criteria->keyContainsValue(ListTableMap::COL_LIST_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ListTableMap::COL_LIST_ID.')');
        }


        // Set the correct dbName
        $query = ListQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ListTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ListTableMap::buildTableMap();
