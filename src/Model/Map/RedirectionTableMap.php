<?php

namespace Model\Map;

use Model\Redirection;
use Model\RedirectionQuery;
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
 * This class defines the structure of the 'redirections' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class RedirectionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.RedirectionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'redirections';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Redirection';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Redirection';

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
     * the column name for the redirection_id field
     */
    const COL_REDIRECTION_ID = 'redirections.redirection_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'redirections.site_id';

    /**
     * the column name for the redirection_old field
     */
    const COL_REDIRECTION_OLD = 'redirections.redirection_old';

    /**
     * the column name for the redirection_new field
     */
    const COL_REDIRECTION_NEW = 'redirections.redirection_new';

    /**
     * the column name for the redirection_hits field
     */
    const COL_REDIRECTION_HITS = 'redirections.redirection_hits';

    /**
     * the column name for the redirection_date field
     */
    const COL_REDIRECTION_DATE = 'redirections.redirection_date';

    /**
     * the column name for the redirection_created field
     */
    const COL_REDIRECTION_CREATED = 'redirections.redirection_created';

    /**
     * the column name for the redirection_updated field
     */
    const COL_REDIRECTION_UPDATED = 'redirections.redirection_updated';

    /**
     * the column name for the redirection_deleted field
     */
    const COL_REDIRECTION_DELETED = 'redirections.redirection_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'Old', 'New', 'Hits', 'Date', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'old', 'new', 'hits', 'date', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(RedirectionTableMap::COL_REDIRECTION_ID, RedirectionTableMap::COL_SITE_ID, RedirectionTableMap::COL_REDIRECTION_OLD, RedirectionTableMap::COL_REDIRECTION_NEW, RedirectionTableMap::COL_REDIRECTION_HITS, RedirectionTableMap::COL_REDIRECTION_DATE, RedirectionTableMap::COL_REDIRECTION_CREATED, RedirectionTableMap::COL_REDIRECTION_UPDATED, RedirectionTableMap::COL_REDIRECTION_DELETED, ),
        self::TYPE_FIELDNAME     => array('redirection_id', 'site_id', 'redirection_old', 'redirection_new', 'redirection_hits', 'redirection_date', 'redirection_created', 'redirection_updated', 'redirection_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'Old' => 2, 'New' => 3, 'Hits' => 4, 'Date' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, 'DeletedAt' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'old' => 2, 'new' => 3, 'hits' => 4, 'date' => 5, 'createdAt' => 6, 'updatedAt' => 7, 'deletedAt' => 8, ),
        self::TYPE_COLNAME       => array(RedirectionTableMap::COL_REDIRECTION_ID => 0, RedirectionTableMap::COL_SITE_ID => 1, RedirectionTableMap::COL_REDIRECTION_OLD => 2, RedirectionTableMap::COL_REDIRECTION_NEW => 3, RedirectionTableMap::COL_REDIRECTION_HITS => 4, RedirectionTableMap::COL_REDIRECTION_DATE => 5, RedirectionTableMap::COL_REDIRECTION_CREATED => 6, RedirectionTableMap::COL_REDIRECTION_UPDATED => 7, RedirectionTableMap::COL_REDIRECTION_DELETED => 8, ),
        self::TYPE_FIELDNAME     => array('redirection_id' => 0, 'site_id' => 1, 'redirection_old' => 2, 'redirection_new' => 3, 'redirection_hits' => 4, 'redirection_date' => 5, 'redirection_created' => 6, 'redirection_updated' => 7, 'redirection_deleted' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'REDIRECTION_ID',
        'Redirection.Id' => 'REDIRECTION_ID',
        'id' => 'REDIRECTION_ID',
        'redirection.id' => 'REDIRECTION_ID',
        'RedirectionTableMap::COL_REDIRECTION_ID' => 'REDIRECTION_ID',
        'COL_REDIRECTION_ID' => 'REDIRECTION_ID',
        'redirection_id' => 'REDIRECTION_ID',
        'redirections.redirection_id' => 'REDIRECTION_ID',
        'SiteId' => 'SITE_ID',
        'Redirection.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'redirection.siteId' => 'SITE_ID',
        'RedirectionTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'redirections.site_id' => 'SITE_ID',
        'Old' => 'REDIRECTION_OLD',
        'Redirection.Old' => 'REDIRECTION_OLD',
        'old' => 'REDIRECTION_OLD',
        'redirection.old' => 'REDIRECTION_OLD',
        'RedirectionTableMap::COL_REDIRECTION_OLD' => 'REDIRECTION_OLD',
        'COL_REDIRECTION_OLD' => 'REDIRECTION_OLD',
        'redirection_old' => 'REDIRECTION_OLD',
        'redirections.redirection_old' => 'REDIRECTION_OLD',
        'New' => 'REDIRECTION_NEW',
        'Redirection.New' => 'REDIRECTION_NEW',
        'new' => 'REDIRECTION_NEW',
        'redirection.new' => 'REDIRECTION_NEW',
        'RedirectionTableMap::COL_REDIRECTION_NEW' => 'REDIRECTION_NEW',
        'COL_REDIRECTION_NEW' => 'REDIRECTION_NEW',
        'redirection_new' => 'REDIRECTION_NEW',
        'redirections.redirection_new' => 'REDIRECTION_NEW',
        'Hits' => 'REDIRECTION_HITS',
        'Redirection.Hits' => 'REDIRECTION_HITS',
        'hits' => 'REDIRECTION_HITS',
        'redirection.hits' => 'REDIRECTION_HITS',
        'RedirectionTableMap::COL_REDIRECTION_HITS' => 'REDIRECTION_HITS',
        'COL_REDIRECTION_HITS' => 'REDIRECTION_HITS',
        'redirection_hits' => 'REDIRECTION_HITS',
        'redirections.redirection_hits' => 'REDIRECTION_HITS',
        'Date' => 'REDIRECTION_DATE',
        'Redirection.Date' => 'REDIRECTION_DATE',
        'date' => 'REDIRECTION_DATE',
        'redirection.date' => 'REDIRECTION_DATE',
        'RedirectionTableMap::COL_REDIRECTION_DATE' => 'REDIRECTION_DATE',
        'COL_REDIRECTION_DATE' => 'REDIRECTION_DATE',
        'redirection_date' => 'REDIRECTION_DATE',
        'redirections.redirection_date' => 'REDIRECTION_DATE',
        'CreatedAt' => 'REDIRECTION_CREATED',
        'Redirection.CreatedAt' => 'REDIRECTION_CREATED',
        'createdAt' => 'REDIRECTION_CREATED',
        'redirection.createdAt' => 'REDIRECTION_CREATED',
        'RedirectionTableMap::COL_REDIRECTION_CREATED' => 'REDIRECTION_CREATED',
        'COL_REDIRECTION_CREATED' => 'REDIRECTION_CREATED',
        'redirection_created' => 'REDIRECTION_CREATED',
        'redirections.redirection_created' => 'REDIRECTION_CREATED',
        'UpdatedAt' => 'REDIRECTION_UPDATED',
        'Redirection.UpdatedAt' => 'REDIRECTION_UPDATED',
        'updatedAt' => 'REDIRECTION_UPDATED',
        'redirection.updatedAt' => 'REDIRECTION_UPDATED',
        'RedirectionTableMap::COL_REDIRECTION_UPDATED' => 'REDIRECTION_UPDATED',
        'COL_REDIRECTION_UPDATED' => 'REDIRECTION_UPDATED',
        'redirection_updated' => 'REDIRECTION_UPDATED',
        'redirections.redirection_updated' => 'REDIRECTION_UPDATED',
        'DeletedAt' => 'REDIRECTION_DELETED',
        'Redirection.DeletedAt' => 'REDIRECTION_DELETED',
        'deletedAt' => 'REDIRECTION_DELETED',
        'redirection.deletedAt' => 'REDIRECTION_DELETED',
        'RedirectionTableMap::COL_REDIRECTION_DELETED' => 'REDIRECTION_DELETED',
        'COL_REDIRECTION_DELETED' => 'REDIRECTION_DELETED',
        'redirection_deleted' => 'REDIRECTION_DELETED',
        'redirections.redirection_deleted' => 'REDIRECTION_DELETED',
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
        $this->setName('redirections');
        $this->setPhpName('Redirection');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Redirection');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('redirection_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('redirection_old', 'Old', 'VARCHAR', false, 256, null);
        $this->addColumn('redirection_new', 'New', 'VARCHAR', false, 256, null);
        $this->addColumn('redirection_hits', 'Hits', 'INTEGER', false, null, 0);
        $this->addColumn('redirection_date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('redirection_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('redirection_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('redirection_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'redirection_created', 'update_column' => 'redirection_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? RedirectionTableMap::CLASS_DEFAULT : RedirectionTableMap::OM_CLASS;
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
     * @return array           (Redirection object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = RedirectionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = RedirectionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + RedirectionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = RedirectionTableMap::OM_CLASS;
            /** @var Redirection $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            RedirectionTableMap::addInstanceToPool($obj, $key);
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
            $key = RedirectionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = RedirectionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Redirection $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                RedirectionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(RedirectionTableMap::COL_REDIRECTION_ID);
            $criteria->addSelectColumn(RedirectionTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(RedirectionTableMap::COL_REDIRECTION_OLD);
            $criteria->addSelectColumn(RedirectionTableMap::COL_REDIRECTION_NEW);
            $criteria->addSelectColumn(RedirectionTableMap::COL_REDIRECTION_HITS);
            $criteria->addSelectColumn(RedirectionTableMap::COL_REDIRECTION_DATE);
            $criteria->addSelectColumn(RedirectionTableMap::COL_REDIRECTION_CREATED);
            $criteria->addSelectColumn(RedirectionTableMap::COL_REDIRECTION_UPDATED);
            $criteria->addSelectColumn(RedirectionTableMap::COL_REDIRECTION_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.redirection_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.redirection_old');
            $criteria->addSelectColumn($alias . '.redirection_new');
            $criteria->addSelectColumn($alias . '.redirection_hits');
            $criteria->addSelectColumn($alias . '.redirection_date');
            $criteria->addSelectColumn($alias . '.redirection_created');
            $criteria->addSelectColumn($alias . '.redirection_updated');
            $criteria->addSelectColumn($alias . '.redirection_deleted');
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
            $criteria->removeSelectColumn(RedirectionTableMap::COL_REDIRECTION_ID);
            $criteria->removeSelectColumn(RedirectionTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(RedirectionTableMap::COL_REDIRECTION_OLD);
            $criteria->removeSelectColumn(RedirectionTableMap::COL_REDIRECTION_NEW);
            $criteria->removeSelectColumn(RedirectionTableMap::COL_REDIRECTION_HITS);
            $criteria->removeSelectColumn(RedirectionTableMap::COL_REDIRECTION_DATE);
            $criteria->removeSelectColumn(RedirectionTableMap::COL_REDIRECTION_CREATED);
            $criteria->removeSelectColumn(RedirectionTableMap::COL_REDIRECTION_UPDATED);
            $criteria->removeSelectColumn(RedirectionTableMap::COL_REDIRECTION_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.redirection_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.redirection_old');
            $criteria->removeSelectColumn($alias . '.redirection_new');
            $criteria->removeSelectColumn($alias . '.redirection_hits');
            $criteria->removeSelectColumn($alias . '.redirection_date');
            $criteria->removeSelectColumn($alias . '.redirection_created');
            $criteria->removeSelectColumn($alias . '.redirection_updated');
            $criteria->removeSelectColumn($alias . '.redirection_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(RedirectionTableMap::DATABASE_NAME)->getTable(RedirectionTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Redirection or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Redirection object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(RedirectionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Redirection) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(RedirectionTableMap::DATABASE_NAME);
            $criteria->add(RedirectionTableMap::COL_REDIRECTION_ID, (array) $values, Criteria::IN);
        }

        $query = RedirectionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            RedirectionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                RedirectionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the redirections table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return RedirectionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Redirection or Criteria object.
     *
     * @param mixed               $criteria Criteria or Redirection object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RedirectionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Redirection object
        }

        if ($criteria->containsKey(RedirectionTableMap::COL_REDIRECTION_ID) && $criteria->keyContainsValue(RedirectionTableMap::COL_REDIRECTION_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.RedirectionTableMap::COL_REDIRECTION_ID.')');
        }


        // Set the correct dbName
        $query = RedirectionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // RedirectionTableMap
