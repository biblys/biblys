<?php

namespace Model\Map;

use Model\Session;
use Model\SessionQuery;
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
 * This class defines the structure of the 'session' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class SessionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.SessionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'session';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Session';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Session';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the session_id field
     */
    const COL_SESSION_ID = 'session.session_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'session.site_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'session.user_id';

    /**
     * the column name for the session_token field
     */
    const COL_SESSION_TOKEN = 'session.session_token';

    /**
     * the column name for the session_created field
     */
    const COL_SESSION_CREATED = 'session.session_created';

    /**
     * the column name for the session_expires field
     */
    const COL_SESSION_EXPIRES = 'session.session_expires';

    /**
     * the column name for the session_updated field
     */
    const COL_SESSION_UPDATED = 'session.session_updated';

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
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'UserId', 'Token', 'CreatedAt', 'ExpiresAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'userId', 'token', 'createdAt', 'expiresAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(SessionTableMap::COL_SESSION_ID, SessionTableMap::COL_SITE_ID, SessionTableMap::COL_USER_ID, SessionTableMap::COL_SESSION_TOKEN, SessionTableMap::COL_SESSION_CREATED, SessionTableMap::COL_SESSION_EXPIRES, SessionTableMap::COL_SESSION_UPDATED, ),
        self::TYPE_FIELDNAME     => array('session_id', 'site_id', 'user_id', 'session_token', 'session_created', 'session_expires', 'session_updated', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'UserId' => 2, 'Token' => 3, 'CreatedAt' => 4, 'ExpiresAt' => 5, 'UpdatedAt' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'userId' => 2, 'token' => 3, 'createdAt' => 4, 'expiresAt' => 5, 'updatedAt' => 6, ),
        self::TYPE_COLNAME       => array(SessionTableMap::COL_SESSION_ID => 0, SessionTableMap::COL_SITE_ID => 1, SessionTableMap::COL_USER_ID => 2, SessionTableMap::COL_SESSION_TOKEN => 3, SessionTableMap::COL_SESSION_CREATED => 4, SessionTableMap::COL_SESSION_EXPIRES => 5, SessionTableMap::COL_SESSION_UPDATED => 6, ),
        self::TYPE_FIELDNAME     => array('session_id' => 0, 'site_id' => 1, 'user_id' => 2, 'session_token' => 3, 'session_created' => 4, 'session_expires' => 5, 'session_updated' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'SESSION_ID',
        'Session.Id' => 'SESSION_ID',
        'id' => 'SESSION_ID',
        'session.id' => 'SESSION_ID',
        'SessionTableMap::COL_SESSION_ID' => 'SESSION_ID',
        'COL_SESSION_ID' => 'SESSION_ID',
        'session_id' => 'SESSION_ID',
        'session.session_id' => 'SESSION_ID',
        'SiteId' => 'SITE_ID',
        'Session.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'session.siteId' => 'SITE_ID',
        'SessionTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'session.site_id' => 'SITE_ID',
        'UserId' => 'USER_ID',
        'Session.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'session.userId' => 'USER_ID',
        'SessionTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'session.user_id' => 'USER_ID',
        'Token' => 'SESSION_TOKEN',
        'Session.Token' => 'SESSION_TOKEN',
        'token' => 'SESSION_TOKEN',
        'session.token' => 'SESSION_TOKEN',
        'SessionTableMap::COL_SESSION_TOKEN' => 'SESSION_TOKEN',
        'COL_SESSION_TOKEN' => 'SESSION_TOKEN',
        'session_token' => 'SESSION_TOKEN',
        'session.session_token' => 'SESSION_TOKEN',
        'CreatedAt' => 'SESSION_CREATED',
        'Session.CreatedAt' => 'SESSION_CREATED',
        'createdAt' => 'SESSION_CREATED',
        'session.createdAt' => 'SESSION_CREATED',
        'SessionTableMap::COL_SESSION_CREATED' => 'SESSION_CREATED',
        'COL_SESSION_CREATED' => 'SESSION_CREATED',
        'session_created' => 'SESSION_CREATED',
        'session.session_created' => 'SESSION_CREATED',
        'ExpiresAt' => 'SESSION_EXPIRES',
        'Session.ExpiresAt' => 'SESSION_EXPIRES',
        'expiresAt' => 'SESSION_EXPIRES',
        'session.expiresAt' => 'SESSION_EXPIRES',
        'SessionTableMap::COL_SESSION_EXPIRES' => 'SESSION_EXPIRES',
        'COL_SESSION_EXPIRES' => 'SESSION_EXPIRES',
        'session_expires' => 'SESSION_EXPIRES',
        'session.session_expires' => 'SESSION_EXPIRES',
        'UpdatedAt' => 'SESSION_UPDATED',
        'Session.UpdatedAt' => 'SESSION_UPDATED',
        'updatedAt' => 'SESSION_UPDATED',
        'session.updatedAt' => 'SESSION_UPDATED',
        'SessionTableMap::COL_SESSION_UPDATED' => 'SESSION_UPDATED',
        'COL_SESSION_UPDATED' => 'SESSION_UPDATED',
        'session_updated' => 'SESSION_UPDATED',
        'session.session_updated' => 'SESSION_UPDATED',
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
        $this->setName('session');
        $this->setPhpName('Session');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Session');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('session_id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, null, null);
        $this->addColumn('session_token', 'Token', 'VARCHAR', false, 32, null);
        $this->addColumn('session_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('session_expires', 'ExpiresAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('session_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
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
            'timestampable' => ['create_column' => 'session_created', 'update_column' => 'session_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? SessionTableMap::CLASS_DEFAULT : SessionTableMap::OM_CLASS;
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
     * @return array           (Session object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SessionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SessionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SessionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SessionTableMap::OM_CLASS;
            /** @var Session $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SessionTableMap::addInstanceToPool($obj, $key);
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
            $key = SessionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SessionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Session $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SessionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SessionTableMap::COL_SESSION_ID);
            $criteria->addSelectColumn(SessionTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(SessionTableMap::COL_USER_ID);
            $criteria->addSelectColumn(SessionTableMap::COL_SESSION_TOKEN);
            $criteria->addSelectColumn(SessionTableMap::COL_SESSION_CREATED);
            $criteria->addSelectColumn(SessionTableMap::COL_SESSION_EXPIRES);
            $criteria->addSelectColumn(SessionTableMap::COL_SESSION_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.session_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.session_token');
            $criteria->addSelectColumn($alias . '.session_created');
            $criteria->addSelectColumn($alias . '.session_expires');
            $criteria->addSelectColumn($alias . '.session_updated');
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
            $criteria->removeSelectColumn(SessionTableMap::COL_SESSION_ID);
            $criteria->removeSelectColumn(SessionTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(SessionTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(SessionTableMap::COL_SESSION_TOKEN);
            $criteria->removeSelectColumn(SessionTableMap::COL_SESSION_CREATED);
            $criteria->removeSelectColumn(SessionTableMap::COL_SESSION_EXPIRES);
            $criteria->removeSelectColumn(SessionTableMap::COL_SESSION_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.session_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.session_token');
            $criteria->removeSelectColumn($alias . '.session_created');
            $criteria->removeSelectColumn($alias . '.session_expires');
            $criteria->removeSelectColumn($alias . '.session_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(SessionTableMap::DATABASE_NAME)->getTable(SessionTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Session or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Session object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SessionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Session) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SessionTableMap::DATABASE_NAME);
            $criteria->add(SessionTableMap::COL_SESSION_ID, (array) $values, Criteria::IN);
        }

        $query = SessionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SessionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SessionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the session table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SessionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Session or Criteria object.
     *
     * @param mixed               $criteria Criteria or Session object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SessionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Session object
        }

        if ($criteria->containsKey(SessionTableMap::COL_SESSION_ID) && $criteria->keyContainsValue(SessionTableMap::COL_SESSION_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SessionTableMap::COL_SESSION_ID.')');
        }


        // Set the correct dbName
        $query = SessionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SessionTableMap
