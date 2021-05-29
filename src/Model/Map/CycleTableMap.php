<?php

namespace Model\Map;

use Model\Cycle;
use Model\CycleQuery;
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
 * This class defines the structure of the 'cycles' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class CycleTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.CycleTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'cycles';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Cycle';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Cycle';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the cycle_id field
     */
    const COL_CYCLE_ID = 'cycles.cycle_id';

    /**
     * the column name for the cycle_name field
     */
    const COL_CYCLE_NAME = 'cycles.cycle_name';

    /**
     * the column name for the cycle_url field
     */
    const COL_CYCLE_URL = 'cycles.cycle_url';

    /**
     * the column name for the cycle_desc field
     */
    const COL_CYCLE_DESC = 'cycles.cycle_desc';

    /**
     * the column name for the cycle_hits field
     */
    const COL_CYCLE_HITS = 'cycles.cycle_hits';

    /**
     * the column name for the cycle_noosfere_id field
     */
    const COL_CYCLE_NOOSFERE_ID = 'cycles.cycle_noosfere_id';

    /**
     * the column name for the cycle_insert field
     */
    const COL_CYCLE_INSERT = 'cycles.cycle_insert';

    /**
     * the column name for the cycle_update field
     */
    const COL_CYCLE_UPDATE = 'cycles.cycle_update';

    /**
     * the column name for the cycle_created field
     */
    const COL_CYCLE_CREATED = 'cycles.cycle_created';

    /**
     * the column name for the cycle_updated field
     */
    const COL_CYCLE_UPDATED = 'cycles.cycle_updated';

    /**
     * the column name for the cycle_deleted field
     */
    const COL_CYCLE_DELETED = 'cycles.cycle_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Url', 'Desc', 'Hits', 'NoosfereId', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'url', 'desc', 'hits', 'noosfereId', 'insert', 'update', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(CycleTableMap::COL_CYCLE_ID, CycleTableMap::COL_CYCLE_NAME, CycleTableMap::COL_CYCLE_URL, CycleTableMap::COL_CYCLE_DESC, CycleTableMap::COL_CYCLE_HITS, CycleTableMap::COL_CYCLE_NOOSFERE_ID, CycleTableMap::COL_CYCLE_INSERT, CycleTableMap::COL_CYCLE_UPDATE, CycleTableMap::COL_CYCLE_CREATED, CycleTableMap::COL_CYCLE_UPDATED, CycleTableMap::COL_CYCLE_DELETED, ),
        self::TYPE_FIELDNAME     => array('cycle_id', 'cycle_name', 'cycle_url', 'cycle_desc', 'cycle_hits', 'cycle_noosfere_id', 'cycle_insert', 'cycle_update', 'cycle_created', 'cycle_updated', 'cycle_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Url' => 2, 'Desc' => 3, 'Hits' => 4, 'NoosfereId' => 5, 'Insert' => 6, 'Update' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, 'DeletedAt' => 10, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'url' => 2, 'desc' => 3, 'hits' => 4, 'noosfereId' => 5, 'insert' => 6, 'update' => 7, 'createdAt' => 8, 'updatedAt' => 9, 'deletedAt' => 10, ),
        self::TYPE_COLNAME       => array(CycleTableMap::COL_CYCLE_ID => 0, CycleTableMap::COL_CYCLE_NAME => 1, CycleTableMap::COL_CYCLE_URL => 2, CycleTableMap::COL_CYCLE_DESC => 3, CycleTableMap::COL_CYCLE_HITS => 4, CycleTableMap::COL_CYCLE_NOOSFERE_ID => 5, CycleTableMap::COL_CYCLE_INSERT => 6, CycleTableMap::COL_CYCLE_UPDATE => 7, CycleTableMap::COL_CYCLE_CREATED => 8, CycleTableMap::COL_CYCLE_UPDATED => 9, CycleTableMap::COL_CYCLE_DELETED => 10, ),
        self::TYPE_FIELDNAME     => array('cycle_id' => 0, 'cycle_name' => 1, 'cycle_url' => 2, 'cycle_desc' => 3, 'cycle_hits' => 4, 'cycle_noosfere_id' => 5, 'cycle_insert' => 6, 'cycle_update' => 7, 'cycle_created' => 8, 'cycle_updated' => 9, 'cycle_deleted' => 10, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'CYCLE_ID',
        'Cycle.Id' => 'CYCLE_ID',
        'id' => 'CYCLE_ID',
        'cycle.id' => 'CYCLE_ID',
        'CycleTableMap::COL_CYCLE_ID' => 'CYCLE_ID',
        'COL_CYCLE_ID' => 'CYCLE_ID',
        'cycle_id' => 'CYCLE_ID',
        'cycles.cycle_id' => 'CYCLE_ID',
        'Name' => 'CYCLE_NAME',
        'Cycle.Name' => 'CYCLE_NAME',
        'name' => 'CYCLE_NAME',
        'cycle.name' => 'CYCLE_NAME',
        'CycleTableMap::COL_CYCLE_NAME' => 'CYCLE_NAME',
        'COL_CYCLE_NAME' => 'CYCLE_NAME',
        'cycle_name' => 'CYCLE_NAME',
        'cycles.cycle_name' => 'CYCLE_NAME',
        'Url' => 'CYCLE_URL',
        'Cycle.Url' => 'CYCLE_URL',
        'url' => 'CYCLE_URL',
        'cycle.url' => 'CYCLE_URL',
        'CycleTableMap::COL_CYCLE_URL' => 'CYCLE_URL',
        'COL_CYCLE_URL' => 'CYCLE_URL',
        'cycle_url' => 'CYCLE_URL',
        'cycles.cycle_url' => 'CYCLE_URL',
        'Desc' => 'CYCLE_DESC',
        'Cycle.Desc' => 'CYCLE_DESC',
        'desc' => 'CYCLE_DESC',
        'cycle.desc' => 'CYCLE_DESC',
        'CycleTableMap::COL_CYCLE_DESC' => 'CYCLE_DESC',
        'COL_CYCLE_DESC' => 'CYCLE_DESC',
        'cycle_desc' => 'CYCLE_DESC',
        'cycles.cycle_desc' => 'CYCLE_DESC',
        'Hits' => 'CYCLE_HITS',
        'Cycle.Hits' => 'CYCLE_HITS',
        'hits' => 'CYCLE_HITS',
        'cycle.hits' => 'CYCLE_HITS',
        'CycleTableMap::COL_CYCLE_HITS' => 'CYCLE_HITS',
        'COL_CYCLE_HITS' => 'CYCLE_HITS',
        'cycle_hits' => 'CYCLE_HITS',
        'cycles.cycle_hits' => 'CYCLE_HITS',
        'NoosfereId' => 'CYCLE_NOOSFERE_ID',
        'Cycle.NoosfereId' => 'CYCLE_NOOSFERE_ID',
        'noosfereId' => 'CYCLE_NOOSFERE_ID',
        'cycle.noosfereId' => 'CYCLE_NOOSFERE_ID',
        'CycleTableMap::COL_CYCLE_NOOSFERE_ID' => 'CYCLE_NOOSFERE_ID',
        'COL_CYCLE_NOOSFERE_ID' => 'CYCLE_NOOSFERE_ID',
        'cycle_noosfere_id' => 'CYCLE_NOOSFERE_ID',
        'cycles.cycle_noosfere_id' => 'CYCLE_NOOSFERE_ID',
        'Insert' => 'CYCLE_INSERT',
        'Cycle.Insert' => 'CYCLE_INSERT',
        'insert' => 'CYCLE_INSERT',
        'cycle.insert' => 'CYCLE_INSERT',
        'CycleTableMap::COL_CYCLE_INSERT' => 'CYCLE_INSERT',
        'COL_CYCLE_INSERT' => 'CYCLE_INSERT',
        'cycle_insert' => 'CYCLE_INSERT',
        'cycles.cycle_insert' => 'CYCLE_INSERT',
        'Update' => 'CYCLE_UPDATE',
        'Cycle.Update' => 'CYCLE_UPDATE',
        'update' => 'CYCLE_UPDATE',
        'cycle.update' => 'CYCLE_UPDATE',
        'CycleTableMap::COL_CYCLE_UPDATE' => 'CYCLE_UPDATE',
        'COL_CYCLE_UPDATE' => 'CYCLE_UPDATE',
        'cycle_update' => 'CYCLE_UPDATE',
        'cycles.cycle_update' => 'CYCLE_UPDATE',
        'CreatedAt' => 'CYCLE_CREATED',
        'Cycle.CreatedAt' => 'CYCLE_CREATED',
        'createdAt' => 'CYCLE_CREATED',
        'cycle.createdAt' => 'CYCLE_CREATED',
        'CycleTableMap::COL_CYCLE_CREATED' => 'CYCLE_CREATED',
        'COL_CYCLE_CREATED' => 'CYCLE_CREATED',
        'cycle_created' => 'CYCLE_CREATED',
        'cycles.cycle_created' => 'CYCLE_CREATED',
        'UpdatedAt' => 'CYCLE_UPDATED',
        'Cycle.UpdatedAt' => 'CYCLE_UPDATED',
        'updatedAt' => 'CYCLE_UPDATED',
        'cycle.updatedAt' => 'CYCLE_UPDATED',
        'CycleTableMap::COL_CYCLE_UPDATED' => 'CYCLE_UPDATED',
        'COL_CYCLE_UPDATED' => 'CYCLE_UPDATED',
        'cycle_updated' => 'CYCLE_UPDATED',
        'cycles.cycle_updated' => 'CYCLE_UPDATED',
        'DeletedAt' => 'CYCLE_DELETED',
        'Cycle.DeletedAt' => 'CYCLE_DELETED',
        'deletedAt' => 'CYCLE_DELETED',
        'cycle.deletedAt' => 'CYCLE_DELETED',
        'CycleTableMap::COL_CYCLE_DELETED' => 'CYCLE_DELETED',
        'COL_CYCLE_DELETED' => 'CYCLE_DELETED',
        'cycle_deleted' => 'CYCLE_DELETED',
        'cycles.cycle_deleted' => 'CYCLE_DELETED',
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
        $this->setName('cycles');
        $this->setPhpName('Cycle');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Cycle');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('cycle_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('cycle_name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('cycle_url', 'Url', 'LONGVARCHAR', false, null, null);
        $this->addColumn('cycle_desc', 'Desc', 'LONGVARCHAR', false, null, null);
        $this->addColumn('cycle_hits', 'Hits', 'INTEGER', false, null, null);
        $this->addColumn('cycle_noosfere_id', 'NoosfereId', 'INTEGER', false, null, null);
        $this->addColumn('cycle_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('cycle_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('cycle_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('cycle_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('cycle_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
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
        return $withPrefix ? CycleTableMap::CLASS_DEFAULT : CycleTableMap::OM_CLASS;
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
     * @return array           (Cycle object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CycleTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CycleTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CycleTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CycleTableMap::OM_CLASS;
            /** @var Cycle $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CycleTableMap::addInstanceToPool($obj, $key);
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
            $key = CycleTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CycleTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Cycle $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CycleTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_ID);
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_NAME);
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_URL);
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_DESC);
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_HITS);
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_NOOSFERE_ID);
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_INSERT);
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_UPDATE);
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_CREATED);
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_UPDATED);
            $criteria->addSelectColumn(CycleTableMap::COL_CYCLE_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.cycle_id');
            $criteria->addSelectColumn($alias . '.cycle_name');
            $criteria->addSelectColumn($alias . '.cycle_url');
            $criteria->addSelectColumn($alias . '.cycle_desc');
            $criteria->addSelectColumn($alias . '.cycle_hits');
            $criteria->addSelectColumn($alias . '.cycle_noosfere_id');
            $criteria->addSelectColumn($alias . '.cycle_insert');
            $criteria->addSelectColumn($alias . '.cycle_update');
            $criteria->addSelectColumn($alias . '.cycle_created');
            $criteria->addSelectColumn($alias . '.cycle_updated');
            $criteria->addSelectColumn($alias . '.cycle_deleted');
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
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_ID);
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_NAME);
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_URL);
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_DESC);
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_HITS);
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_NOOSFERE_ID);
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_INSERT);
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_UPDATE);
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_CREATED);
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_UPDATED);
            $criteria->removeSelectColumn(CycleTableMap::COL_CYCLE_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.cycle_id');
            $criteria->removeSelectColumn($alias . '.cycle_name');
            $criteria->removeSelectColumn($alias . '.cycle_url');
            $criteria->removeSelectColumn($alias . '.cycle_desc');
            $criteria->removeSelectColumn($alias . '.cycle_hits');
            $criteria->removeSelectColumn($alias . '.cycle_noosfere_id');
            $criteria->removeSelectColumn($alias . '.cycle_insert');
            $criteria->removeSelectColumn($alias . '.cycle_update');
            $criteria->removeSelectColumn($alias . '.cycle_created');
            $criteria->removeSelectColumn($alias . '.cycle_updated');
            $criteria->removeSelectColumn($alias . '.cycle_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(CycleTableMap::DATABASE_NAME)->getTable(CycleTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CycleTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CycleTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CycleTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Cycle or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Cycle object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CycleTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Cycle) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CycleTableMap::DATABASE_NAME);
            $criteria->add(CycleTableMap::COL_CYCLE_ID, (array) $values, Criteria::IN);
        }

        $query = CycleQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CycleTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CycleTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the cycles table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CycleQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Cycle or Criteria object.
     *
     * @param mixed               $criteria Criteria or Cycle object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CycleTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Cycle object
        }

        if ($criteria->containsKey(CycleTableMap::COL_CYCLE_ID) && $criteria->keyContainsValue(CycleTableMap::COL_CYCLE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CycleTableMap::COL_CYCLE_ID.')');
        }


        // Set the correct dbName
        $query = CycleQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CycleTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CycleTableMap::buildTableMap();
