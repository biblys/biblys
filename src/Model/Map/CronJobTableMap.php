<?php

namespace Model\Map;

use Model\CronJob;
use Model\CronJobQuery;
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
 * This class defines the structure of the 'cron_jobs' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class CronJobTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.CronJobTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'cron_jobs';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\CronJob';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.CronJob';

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
     * the column name for the cron_job_id field
     */
    const COL_CRON_JOB_ID = 'cron_jobs.cron_job_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'cron_jobs.site_id';

    /**
     * the column name for the cron_job_task field
     */
    const COL_CRON_JOB_TASK = 'cron_jobs.cron_job_task';

    /**
     * the column name for the cron_job_result field
     */
    const COL_CRON_JOB_RESULT = 'cron_jobs.cron_job_result';

    /**
     * the column name for the cron_job_message field
     */
    const COL_CRON_JOB_MESSAGE = 'cron_jobs.cron_job_message';

    /**
     * the column name for the cron_job_created field
     */
    const COL_CRON_JOB_CREATED = 'cron_jobs.cron_job_created';

    /**
     * the column name for the cron_job_updated field
     */
    const COL_CRON_JOB_UPDATED = 'cron_jobs.cron_job_updated';

    /**
     * the column name for the cron_job_deleted field
     */
    const COL_CRON_JOB_DELETED = 'cron_jobs.cron_job_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'Task', 'Result', 'Message', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'task', 'result', 'message', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(CronJobTableMap::COL_CRON_JOB_ID, CronJobTableMap::COL_SITE_ID, CronJobTableMap::COL_CRON_JOB_TASK, CronJobTableMap::COL_CRON_JOB_RESULT, CronJobTableMap::COL_CRON_JOB_MESSAGE, CronJobTableMap::COL_CRON_JOB_CREATED, CronJobTableMap::COL_CRON_JOB_UPDATED, CronJobTableMap::COL_CRON_JOB_DELETED, ),
        self::TYPE_FIELDNAME     => array('cron_job_id', 'site_id', 'cron_job_task', 'cron_job_result', 'cron_job_message', 'cron_job_created', 'cron_job_updated', 'cron_job_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'Task' => 2, 'Result' => 3, 'Message' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, 'DeletedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'task' => 2, 'result' => 3, 'message' => 4, 'createdAt' => 5, 'updatedAt' => 6, 'deletedAt' => 7, ),
        self::TYPE_COLNAME       => array(CronJobTableMap::COL_CRON_JOB_ID => 0, CronJobTableMap::COL_SITE_ID => 1, CronJobTableMap::COL_CRON_JOB_TASK => 2, CronJobTableMap::COL_CRON_JOB_RESULT => 3, CronJobTableMap::COL_CRON_JOB_MESSAGE => 4, CronJobTableMap::COL_CRON_JOB_CREATED => 5, CronJobTableMap::COL_CRON_JOB_UPDATED => 6, CronJobTableMap::COL_CRON_JOB_DELETED => 7, ),
        self::TYPE_FIELDNAME     => array('cron_job_id' => 0, 'site_id' => 1, 'cron_job_task' => 2, 'cron_job_result' => 3, 'cron_job_message' => 4, 'cron_job_created' => 5, 'cron_job_updated' => 6, 'cron_job_deleted' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'CRON_JOB_ID',
        'CronJob.Id' => 'CRON_JOB_ID',
        'id' => 'CRON_JOB_ID',
        'cronJob.id' => 'CRON_JOB_ID',
        'CronJobTableMap::COL_CRON_JOB_ID' => 'CRON_JOB_ID',
        'COL_CRON_JOB_ID' => 'CRON_JOB_ID',
        'cron_job_id' => 'CRON_JOB_ID',
        'cron_jobs.cron_job_id' => 'CRON_JOB_ID',
        'SiteId' => 'SITE_ID',
        'CronJob.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'cronJob.siteId' => 'SITE_ID',
        'CronJobTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'cron_jobs.site_id' => 'SITE_ID',
        'Task' => 'CRON_JOB_TASK',
        'CronJob.Task' => 'CRON_JOB_TASK',
        'task' => 'CRON_JOB_TASK',
        'cronJob.task' => 'CRON_JOB_TASK',
        'CronJobTableMap::COL_CRON_JOB_TASK' => 'CRON_JOB_TASK',
        'COL_CRON_JOB_TASK' => 'CRON_JOB_TASK',
        'cron_job_task' => 'CRON_JOB_TASK',
        'cron_jobs.cron_job_task' => 'CRON_JOB_TASK',
        'Result' => 'CRON_JOB_RESULT',
        'CronJob.Result' => 'CRON_JOB_RESULT',
        'result' => 'CRON_JOB_RESULT',
        'cronJob.result' => 'CRON_JOB_RESULT',
        'CronJobTableMap::COL_CRON_JOB_RESULT' => 'CRON_JOB_RESULT',
        'COL_CRON_JOB_RESULT' => 'CRON_JOB_RESULT',
        'cron_job_result' => 'CRON_JOB_RESULT',
        'cron_jobs.cron_job_result' => 'CRON_JOB_RESULT',
        'Message' => 'CRON_JOB_MESSAGE',
        'CronJob.Message' => 'CRON_JOB_MESSAGE',
        'message' => 'CRON_JOB_MESSAGE',
        'cronJob.message' => 'CRON_JOB_MESSAGE',
        'CronJobTableMap::COL_CRON_JOB_MESSAGE' => 'CRON_JOB_MESSAGE',
        'COL_CRON_JOB_MESSAGE' => 'CRON_JOB_MESSAGE',
        'cron_job_message' => 'CRON_JOB_MESSAGE',
        'cron_jobs.cron_job_message' => 'CRON_JOB_MESSAGE',
        'CreatedAt' => 'CRON_JOB_CREATED',
        'CronJob.CreatedAt' => 'CRON_JOB_CREATED',
        'createdAt' => 'CRON_JOB_CREATED',
        'cronJob.createdAt' => 'CRON_JOB_CREATED',
        'CronJobTableMap::COL_CRON_JOB_CREATED' => 'CRON_JOB_CREATED',
        'COL_CRON_JOB_CREATED' => 'CRON_JOB_CREATED',
        'cron_job_created' => 'CRON_JOB_CREATED',
        'cron_jobs.cron_job_created' => 'CRON_JOB_CREATED',
        'UpdatedAt' => 'CRON_JOB_UPDATED',
        'CronJob.UpdatedAt' => 'CRON_JOB_UPDATED',
        'updatedAt' => 'CRON_JOB_UPDATED',
        'cronJob.updatedAt' => 'CRON_JOB_UPDATED',
        'CronJobTableMap::COL_CRON_JOB_UPDATED' => 'CRON_JOB_UPDATED',
        'COL_CRON_JOB_UPDATED' => 'CRON_JOB_UPDATED',
        'cron_job_updated' => 'CRON_JOB_UPDATED',
        'cron_jobs.cron_job_updated' => 'CRON_JOB_UPDATED',
        'DeletedAt' => 'CRON_JOB_DELETED',
        'CronJob.DeletedAt' => 'CRON_JOB_DELETED',
        'deletedAt' => 'CRON_JOB_DELETED',
        'cronJob.deletedAt' => 'CRON_JOB_DELETED',
        'CronJobTableMap::COL_CRON_JOB_DELETED' => 'CRON_JOB_DELETED',
        'COL_CRON_JOB_DELETED' => 'CRON_JOB_DELETED',
        'cron_job_deleted' => 'CRON_JOB_DELETED',
        'cron_jobs.cron_job_deleted' => 'CRON_JOB_DELETED',
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
        $this->setName('cron_jobs');
        $this->setPhpName('CronJob');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\CronJob');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('cron_job_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('cron_job_task', 'Task', 'VARCHAR', false, 128, null);
        $this->addColumn('cron_job_result', 'Result', 'VARCHAR', false, 16, null);
        $this->addColumn('cron_job_message', 'Message', 'VARCHAR', false, 256, null);
        $this->addColumn('cron_job_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('cron_job_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('cron_job_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

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
        return $withPrefix ? CronJobTableMap::CLASS_DEFAULT : CronJobTableMap::OM_CLASS;
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
     * @return array           (CronJob object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CronJobTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CronJobTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CronJobTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CronJobTableMap::OM_CLASS;
            /** @var CronJob $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CronJobTableMap::addInstanceToPool($obj, $key);
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
            $key = CronJobTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CronJobTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var CronJob $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CronJobTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CronJobTableMap::COL_CRON_JOB_ID);
            $criteria->addSelectColumn(CronJobTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(CronJobTableMap::COL_CRON_JOB_TASK);
            $criteria->addSelectColumn(CronJobTableMap::COL_CRON_JOB_RESULT);
            $criteria->addSelectColumn(CronJobTableMap::COL_CRON_JOB_MESSAGE);
            $criteria->addSelectColumn(CronJobTableMap::COL_CRON_JOB_CREATED);
            $criteria->addSelectColumn(CronJobTableMap::COL_CRON_JOB_UPDATED);
            $criteria->addSelectColumn(CronJobTableMap::COL_CRON_JOB_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.cron_job_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.cron_job_task');
            $criteria->addSelectColumn($alias . '.cron_job_result');
            $criteria->addSelectColumn($alias . '.cron_job_message');
            $criteria->addSelectColumn($alias . '.cron_job_created');
            $criteria->addSelectColumn($alias . '.cron_job_updated');
            $criteria->addSelectColumn($alias . '.cron_job_deleted');
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
            $criteria->removeSelectColumn(CronJobTableMap::COL_CRON_JOB_ID);
            $criteria->removeSelectColumn(CronJobTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(CronJobTableMap::COL_CRON_JOB_TASK);
            $criteria->removeSelectColumn(CronJobTableMap::COL_CRON_JOB_RESULT);
            $criteria->removeSelectColumn(CronJobTableMap::COL_CRON_JOB_MESSAGE);
            $criteria->removeSelectColumn(CronJobTableMap::COL_CRON_JOB_CREATED);
            $criteria->removeSelectColumn(CronJobTableMap::COL_CRON_JOB_UPDATED);
            $criteria->removeSelectColumn(CronJobTableMap::COL_CRON_JOB_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.cron_job_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.cron_job_task');
            $criteria->removeSelectColumn($alias . '.cron_job_result');
            $criteria->removeSelectColumn($alias . '.cron_job_message');
            $criteria->removeSelectColumn($alias . '.cron_job_created');
            $criteria->removeSelectColumn($alias . '.cron_job_updated');
            $criteria->removeSelectColumn($alias . '.cron_job_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(CronJobTableMap::DATABASE_NAME)->getTable(CronJobTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CronJobTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CronJobTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CronJobTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a CronJob or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or CronJob object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CronJobTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\CronJob) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CronJobTableMap::DATABASE_NAME);
            $criteria->add(CronJobTableMap::COL_CRON_JOB_ID, (array) $values, Criteria::IN);
        }

        $query = CronJobQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CronJobTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CronJobTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the cron_jobs table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CronJobQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a CronJob or Criteria object.
     *
     * @param mixed               $criteria Criteria or CronJob object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CronJobTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from CronJob object
        }

        if ($criteria->containsKey(CronJobTableMap::COL_CRON_JOB_ID) && $criteria->keyContainsValue(CronJobTableMap::COL_CRON_JOB_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CronJobTableMap::COL_CRON_JOB_ID.')');
        }


        // Set the correct dbName
        $query = CronJobQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CronJobTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CronJobTableMap::buildTableMap();
