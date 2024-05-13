<?php

namespace Model\Map;

use Model\Job;
use Model\JobQuery;
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
 * This class defines the structure of the 'jobs' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class JobTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.JobTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'jobs';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Job';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Job';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Job';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the job_id field
     */
    public const COL_JOB_ID = 'jobs.job_id';

    /**
     * the column name for the job_name field
     */
    public const COL_JOB_NAME = 'jobs.job_name';

    /**
     * the column name for the job_name_f field
     */
    public const COL_JOB_NAME_F = 'jobs.job_name_f';

    /**
     * the column name for the job_other_names field
     */
    public const COL_JOB_OTHER_NAMES = 'jobs.job_other_names';

    /**
     * the column name for the job_event field
     */
    public const COL_JOB_EVENT = 'jobs.job_event';

    /**
     * the column name for the job_order field
     */
    public const COL_JOB_ORDER = 'jobs.job_order';

    /**
     * the column name for the job_onix field
     */
    public const COL_JOB_ONIX = 'jobs.job_onix';

    /**
     * the column name for the job_date field
     */
    public const COL_JOB_DATE = 'jobs.job_date';

    /**
     * the column name for the job_created field
     */
    public const COL_JOB_CREATED = 'jobs.job_created';

    /**
     * the column name for the job_updated field
     */
    public const COL_JOB_UPDATED = 'jobs.job_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'Name', 'NameF', 'OtherNames', 'Event', 'Order', 'Onix', 'Date', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'name', 'nameF', 'otherNames', 'event', 'order', 'onix', 'date', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [JobTableMap::COL_JOB_ID, JobTableMap::COL_JOB_NAME, JobTableMap::COL_JOB_NAME_F, JobTableMap::COL_JOB_OTHER_NAMES, JobTableMap::COL_JOB_EVENT, JobTableMap::COL_JOB_ORDER, JobTableMap::COL_JOB_ONIX, JobTableMap::COL_JOB_DATE, JobTableMap::COL_JOB_CREATED, JobTableMap::COL_JOB_UPDATED, ],
        self::TYPE_FIELDNAME     => ['job_id', 'job_name', 'job_name_f', 'job_other_names', 'job_event', 'job_order', 'job_onix', 'job_date', 'job_created', 'job_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Name' => 1, 'NameF' => 2, 'OtherNames' => 3, 'Event' => 4, 'Order' => 5, 'Onix' => 6, 'Date' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'name' => 1, 'nameF' => 2, 'otherNames' => 3, 'event' => 4, 'order' => 5, 'onix' => 6, 'date' => 7, 'createdAt' => 8, 'updatedAt' => 9, ],
        self::TYPE_COLNAME       => [JobTableMap::COL_JOB_ID => 0, JobTableMap::COL_JOB_NAME => 1, JobTableMap::COL_JOB_NAME_F => 2, JobTableMap::COL_JOB_OTHER_NAMES => 3, JobTableMap::COL_JOB_EVENT => 4, JobTableMap::COL_JOB_ORDER => 5, JobTableMap::COL_JOB_ONIX => 6, JobTableMap::COL_JOB_DATE => 7, JobTableMap::COL_JOB_CREATED => 8, JobTableMap::COL_JOB_UPDATED => 9, ],
        self::TYPE_FIELDNAME     => ['job_id' => 0, 'job_name' => 1, 'job_name_f' => 2, 'job_other_names' => 3, 'job_event' => 4, 'job_order' => 5, 'job_onix' => 6, 'job_date' => 7, 'job_created' => 8, 'job_updated' => 9, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'JOB_ID',
        'Job.Id' => 'JOB_ID',
        'id' => 'JOB_ID',
        'job.id' => 'JOB_ID',
        'JobTableMap::COL_JOB_ID' => 'JOB_ID',
        'COL_JOB_ID' => 'JOB_ID',
        'job_id' => 'JOB_ID',
        'jobs.job_id' => 'JOB_ID',
        'Name' => 'JOB_NAME',
        'Job.Name' => 'JOB_NAME',
        'name' => 'JOB_NAME',
        'job.name' => 'JOB_NAME',
        'JobTableMap::COL_JOB_NAME' => 'JOB_NAME',
        'COL_JOB_NAME' => 'JOB_NAME',
        'job_name' => 'JOB_NAME',
        'jobs.job_name' => 'JOB_NAME',
        'NameF' => 'JOB_NAME_F',
        'Job.NameF' => 'JOB_NAME_F',
        'nameF' => 'JOB_NAME_F',
        'job.nameF' => 'JOB_NAME_F',
        'JobTableMap::COL_JOB_NAME_F' => 'JOB_NAME_F',
        'COL_JOB_NAME_F' => 'JOB_NAME_F',
        'job_name_f' => 'JOB_NAME_F',
        'jobs.job_name_f' => 'JOB_NAME_F',
        'OtherNames' => 'JOB_OTHER_NAMES',
        'Job.OtherNames' => 'JOB_OTHER_NAMES',
        'otherNames' => 'JOB_OTHER_NAMES',
        'job.otherNames' => 'JOB_OTHER_NAMES',
        'JobTableMap::COL_JOB_OTHER_NAMES' => 'JOB_OTHER_NAMES',
        'COL_JOB_OTHER_NAMES' => 'JOB_OTHER_NAMES',
        'job_other_names' => 'JOB_OTHER_NAMES',
        'jobs.job_other_names' => 'JOB_OTHER_NAMES',
        'Event' => 'JOB_EVENT',
        'Job.Event' => 'JOB_EVENT',
        'event' => 'JOB_EVENT',
        'job.event' => 'JOB_EVENT',
        'JobTableMap::COL_JOB_EVENT' => 'JOB_EVENT',
        'COL_JOB_EVENT' => 'JOB_EVENT',
        'job_event' => 'JOB_EVENT',
        'jobs.job_event' => 'JOB_EVENT',
        'Order' => 'JOB_ORDER',
        'Job.Order' => 'JOB_ORDER',
        'order' => 'JOB_ORDER',
        'job.order' => 'JOB_ORDER',
        'JobTableMap::COL_JOB_ORDER' => 'JOB_ORDER',
        'COL_JOB_ORDER' => 'JOB_ORDER',
        'job_order' => 'JOB_ORDER',
        'jobs.job_order' => 'JOB_ORDER',
        'Onix' => 'JOB_ONIX',
        'Job.Onix' => 'JOB_ONIX',
        'onix' => 'JOB_ONIX',
        'job.onix' => 'JOB_ONIX',
        'JobTableMap::COL_JOB_ONIX' => 'JOB_ONIX',
        'COL_JOB_ONIX' => 'JOB_ONIX',
        'job_onix' => 'JOB_ONIX',
        'jobs.job_onix' => 'JOB_ONIX',
        'Date' => 'JOB_DATE',
        'Job.Date' => 'JOB_DATE',
        'date' => 'JOB_DATE',
        'job.date' => 'JOB_DATE',
        'JobTableMap::COL_JOB_DATE' => 'JOB_DATE',
        'COL_JOB_DATE' => 'JOB_DATE',
        'job_date' => 'JOB_DATE',
        'jobs.job_date' => 'JOB_DATE',
        'CreatedAt' => 'JOB_CREATED',
        'Job.CreatedAt' => 'JOB_CREATED',
        'createdAt' => 'JOB_CREATED',
        'job.createdAt' => 'JOB_CREATED',
        'JobTableMap::COL_JOB_CREATED' => 'JOB_CREATED',
        'COL_JOB_CREATED' => 'JOB_CREATED',
        'job_created' => 'JOB_CREATED',
        'jobs.job_created' => 'JOB_CREATED',
        'UpdatedAt' => 'JOB_UPDATED',
        'Job.UpdatedAt' => 'JOB_UPDATED',
        'updatedAt' => 'JOB_UPDATED',
        'job.updatedAt' => 'JOB_UPDATED',
        'JobTableMap::COL_JOB_UPDATED' => 'JOB_UPDATED',
        'COL_JOB_UPDATED' => 'JOB_UPDATED',
        'job_updated' => 'JOB_UPDATED',
        'jobs.job_updated' => 'JOB_UPDATED',
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
        $this->setName('jobs');
        $this->setPhpName('Job');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Job');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('job_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('job_name', 'Name', 'VARCHAR', false, 64, null);
        $this->addColumn('job_name_f', 'NameF', 'VARCHAR', false, 64, null);
        $this->addColumn('job_other_names', 'OtherNames', 'VARCHAR', false, 256, null);
        $this->addColumn('job_event', 'Event', 'BOOLEAN', false, 1, null);
        $this->addColumn('job_order', 'Order', 'TINYINT', true, 3, 0);
        $this->addColumn('job_onix', 'Onix', 'VARCHAR', false, 3, null);
        $this->addColumn('job_date', 'Date', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('job_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('job_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
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
            'timestampable' => ['create_column' => 'job_created', 'update_column' => 'job_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? JobTableMap::CLASS_DEFAULT : JobTableMap::OM_CLASS;
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
     * @return array (Job object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = JobTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = JobTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + JobTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = JobTableMap::OM_CLASS;
            /** @var Job $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            JobTableMap::addInstanceToPool($obj, $key);
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
            $key = JobTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = JobTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Job $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                JobTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(JobTableMap::COL_JOB_ID);
            $criteria->addSelectColumn(JobTableMap::COL_JOB_NAME);
            $criteria->addSelectColumn(JobTableMap::COL_JOB_NAME_F);
            $criteria->addSelectColumn(JobTableMap::COL_JOB_OTHER_NAMES);
            $criteria->addSelectColumn(JobTableMap::COL_JOB_EVENT);
            $criteria->addSelectColumn(JobTableMap::COL_JOB_ORDER);
            $criteria->addSelectColumn(JobTableMap::COL_JOB_ONIX);
            $criteria->addSelectColumn(JobTableMap::COL_JOB_DATE);
            $criteria->addSelectColumn(JobTableMap::COL_JOB_CREATED);
            $criteria->addSelectColumn(JobTableMap::COL_JOB_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.job_id');
            $criteria->addSelectColumn($alias . '.job_name');
            $criteria->addSelectColumn($alias . '.job_name_f');
            $criteria->addSelectColumn($alias . '.job_other_names');
            $criteria->addSelectColumn($alias . '.job_event');
            $criteria->addSelectColumn($alias . '.job_order');
            $criteria->addSelectColumn($alias . '.job_onix');
            $criteria->addSelectColumn($alias . '.job_date');
            $criteria->addSelectColumn($alias . '.job_created');
            $criteria->addSelectColumn($alias . '.job_updated');
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
            $criteria->removeSelectColumn(JobTableMap::COL_JOB_ID);
            $criteria->removeSelectColumn(JobTableMap::COL_JOB_NAME);
            $criteria->removeSelectColumn(JobTableMap::COL_JOB_NAME_F);
            $criteria->removeSelectColumn(JobTableMap::COL_JOB_OTHER_NAMES);
            $criteria->removeSelectColumn(JobTableMap::COL_JOB_EVENT);
            $criteria->removeSelectColumn(JobTableMap::COL_JOB_ORDER);
            $criteria->removeSelectColumn(JobTableMap::COL_JOB_ONIX);
            $criteria->removeSelectColumn(JobTableMap::COL_JOB_DATE);
            $criteria->removeSelectColumn(JobTableMap::COL_JOB_CREATED);
            $criteria->removeSelectColumn(JobTableMap::COL_JOB_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.job_id');
            $criteria->removeSelectColumn($alias . '.job_name');
            $criteria->removeSelectColumn($alias . '.job_name_f');
            $criteria->removeSelectColumn($alias . '.job_other_names');
            $criteria->removeSelectColumn($alias . '.job_event');
            $criteria->removeSelectColumn($alias . '.job_order');
            $criteria->removeSelectColumn($alias . '.job_onix');
            $criteria->removeSelectColumn($alias . '.job_date');
            $criteria->removeSelectColumn($alias . '.job_created');
            $criteria->removeSelectColumn($alias . '.job_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(JobTableMap::DATABASE_NAME)->getTable(JobTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Job or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Job object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(JobTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Job) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(JobTableMap::DATABASE_NAME);
            $criteria->add(JobTableMap::COL_JOB_ID, (array) $values, Criteria::IN);
        }

        $query = JobQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            JobTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                JobTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the jobs table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return JobQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Job or Criteria object.
     *
     * @param mixed $criteria Criteria or Job object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(JobTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Job object
        }

        if ($criteria->containsKey(JobTableMap::COL_JOB_ID) && $criteria->keyContainsValue(JobTableMap::COL_JOB_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.JobTableMap::COL_JOB_ID.')');
        }


        // Set the correct dbName
        $query = JobQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
