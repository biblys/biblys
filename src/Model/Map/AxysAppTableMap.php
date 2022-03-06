<?php

namespace Model\Map;

use Model\AxysApp;
use Model\AxysAppQuery;
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
 * This class defines the structure of the 'axys_apps' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class AxysAppTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.AxysAppTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'axys_apps';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\AxysApp';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.AxysApp';

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
     * the column name for the id field
     */
    const COL_ID = 'axys_apps.id';

    /**
     * the column name for the client_id field
     */
    const COL_CLIENT_ID = 'axys_apps.client_id';

    /**
     * the column name for the client_secret field
     */
    const COL_CLIENT_SECRET = 'axys_apps.client_secret';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'axys_apps.name';

    /**
     * the column name for the redirect_uri field
     */
    const COL_REDIRECT_URI = 'axys_apps.redirect_uri';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'axys_apps.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'axys_apps.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'ClientId', 'ClientSecret', 'Name', 'RedirectUri', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'clientId', 'clientSecret', 'name', 'redirectUri', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(AxysAppTableMap::COL_ID, AxysAppTableMap::COL_CLIENT_ID, AxysAppTableMap::COL_CLIENT_SECRET, AxysAppTableMap::COL_NAME, AxysAppTableMap::COL_REDIRECT_URI, AxysAppTableMap::COL_CREATED_AT, AxysAppTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'client_id', 'client_secret', 'name', 'redirect_uri', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ClientId' => 1, 'ClientSecret' => 2, 'Name' => 3, 'RedirectUri' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'clientId' => 1, 'clientSecret' => 2, 'name' => 3, 'redirectUri' => 4, 'createdAt' => 5, 'updatedAt' => 6, ),
        self::TYPE_COLNAME       => array(AxysAppTableMap::COL_ID => 0, AxysAppTableMap::COL_CLIENT_ID => 1, AxysAppTableMap::COL_CLIENT_SECRET => 2, AxysAppTableMap::COL_NAME => 3, AxysAppTableMap::COL_REDIRECT_URI => 4, AxysAppTableMap::COL_CREATED_AT => 5, AxysAppTableMap::COL_UPDATED_AT => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'client_id' => 1, 'client_secret' => 2, 'name' => 3, 'redirect_uri' => 4, 'created_at' => 5, 'updated_at' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'AxysApp.Id' => 'ID',
        'id' => 'ID',
        'axysApp.id' => 'ID',
        'AxysAppTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'axys_apps.id' => 'ID',
        'ClientId' => 'CLIENT_ID',
        'AxysApp.ClientId' => 'CLIENT_ID',
        'clientId' => 'CLIENT_ID',
        'axysApp.clientId' => 'CLIENT_ID',
        'AxysAppTableMap::COL_CLIENT_ID' => 'CLIENT_ID',
        'COL_CLIENT_ID' => 'CLIENT_ID',
        'client_id' => 'CLIENT_ID',
        'axys_apps.client_id' => 'CLIENT_ID',
        'ClientSecret' => 'CLIENT_SECRET',
        'AxysApp.ClientSecret' => 'CLIENT_SECRET',
        'clientSecret' => 'CLIENT_SECRET',
        'axysApp.clientSecret' => 'CLIENT_SECRET',
        'AxysAppTableMap::COL_CLIENT_SECRET' => 'CLIENT_SECRET',
        'COL_CLIENT_SECRET' => 'CLIENT_SECRET',
        'client_secret' => 'CLIENT_SECRET',
        'axys_apps.client_secret' => 'CLIENT_SECRET',
        'Name' => 'NAME',
        'AxysApp.Name' => 'NAME',
        'name' => 'NAME',
        'axysApp.name' => 'NAME',
        'AxysAppTableMap::COL_NAME' => 'NAME',
        'COL_NAME' => 'NAME',
        'axys_apps.name' => 'NAME',
        'RedirectUri' => 'REDIRECT_URI',
        'AxysApp.RedirectUri' => 'REDIRECT_URI',
        'redirectUri' => 'REDIRECT_URI',
        'axysApp.redirectUri' => 'REDIRECT_URI',
        'AxysAppTableMap::COL_REDIRECT_URI' => 'REDIRECT_URI',
        'COL_REDIRECT_URI' => 'REDIRECT_URI',
        'redirect_uri' => 'REDIRECT_URI',
        'axys_apps.redirect_uri' => 'REDIRECT_URI',
        'CreatedAt' => 'CREATED_AT',
        'AxysApp.CreatedAt' => 'CREATED_AT',
        'createdAt' => 'CREATED_AT',
        'axysApp.createdAt' => 'CREATED_AT',
        'AxysAppTableMap::COL_CREATED_AT' => 'CREATED_AT',
        'COL_CREATED_AT' => 'CREATED_AT',
        'created_at' => 'CREATED_AT',
        'axys_apps.created_at' => 'CREATED_AT',
        'UpdatedAt' => 'UPDATED_AT',
        'AxysApp.UpdatedAt' => 'UPDATED_AT',
        'updatedAt' => 'UPDATED_AT',
        'axysApp.updatedAt' => 'UPDATED_AT',
        'AxysAppTableMap::COL_UPDATED_AT' => 'UPDATED_AT',
        'COL_UPDATED_AT' => 'UPDATED_AT',
        'updated_at' => 'UPDATED_AT',
        'axys_apps.updated_at' => 'UPDATED_AT',
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
        $this->setName('axys_apps');
        $this->setPhpName('AxysApp');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\AxysApp');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('client_id', 'ClientId', 'VARCHAR', true, 32, null);
        $this->addColumn('client_secret', 'ClientSecret', 'VARCHAR', true, 64, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 64, null);
        $this->addColumn('redirect_uri', 'RedirectUri', 'VARCHAR', true, 256, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? AxysAppTableMap::CLASS_DEFAULT : AxysAppTableMap::OM_CLASS;
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
     * @return array           (AxysApp object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AxysAppTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AxysAppTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AxysAppTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AxysAppTableMap::OM_CLASS;
            /** @var AxysApp $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AxysAppTableMap::addInstanceToPool($obj, $key);
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
            $key = AxysAppTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AxysAppTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AxysApp $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AxysAppTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AxysAppTableMap::COL_ID);
            $criteria->addSelectColumn(AxysAppTableMap::COL_CLIENT_ID);
            $criteria->addSelectColumn(AxysAppTableMap::COL_CLIENT_SECRET);
            $criteria->addSelectColumn(AxysAppTableMap::COL_NAME);
            $criteria->addSelectColumn(AxysAppTableMap::COL_REDIRECT_URI);
            $criteria->addSelectColumn(AxysAppTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(AxysAppTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.client_id');
            $criteria->addSelectColumn($alias . '.client_secret');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.redirect_uri');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
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
            $criteria->removeSelectColumn(AxysAppTableMap::COL_ID);
            $criteria->removeSelectColumn(AxysAppTableMap::COL_CLIENT_ID);
            $criteria->removeSelectColumn(AxysAppTableMap::COL_CLIENT_SECRET);
            $criteria->removeSelectColumn(AxysAppTableMap::COL_NAME);
            $criteria->removeSelectColumn(AxysAppTableMap::COL_REDIRECT_URI);
            $criteria->removeSelectColumn(AxysAppTableMap::COL_CREATED_AT);
            $criteria->removeSelectColumn(AxysAppTableMap::COL_UPDATED_AT);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.client_id');
            $criteria->removeSelectColumn($alias . '.client_secret');
            $criteria->removeSelectColumn($alias . '.name');
            $criteria->removeSelectColumn($alias . '.redirect_uri');
            $criteria->removeSelectColumn($alias . '.created_at');
            $criteria->removeSelectColumn($alias . '.updated_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(AxysAppTableMap::DATABASE_NAME)->getTable(AxysAppTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a AxysApp or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or AxysApp object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AxysAppTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\AxysApp) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AxysAppTableMap::DATABASE_NAME);
            $criteria->add(AxysAppTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AxysAppQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AxysAppTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AxysAppTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the axys_apps table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AxysAppQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AxysApp or Criteria object.
     *
     * @param mixed               $criteria Criteria or AxysApp object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AxysAppTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AxysApp object
        }

        if ($criteria->containsKey(AxysAppTableMap::COL_ID) && $criteria->keyContainsValue(AxysAppTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AxysAppTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AxysAppQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AxysAppTableMap
