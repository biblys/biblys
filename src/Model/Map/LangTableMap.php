<?php

namespace Model\Map;

use Model\Lang;
use Model\LangQuery;
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
 * This class defines the structure of the 'langs' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class LangTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.LangTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'langs';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Lang';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Lang';

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
     * the column name for the lang_id field
     */
    const COL_LANG_ID = 'langs.lang_id';

    /**
     * the column name for the lang_iso_639-1 field
     */
    const COL_LANG_ISO_639-1 = 'langs.lang_iso_639-1';

    /**
     * the column name for the lang_iso_639-2 field
     */
    const COL_LANG_ISO_639-2 = 'langs.lang_iso_639-2';

    /**
     * the column name for the lang_iso_639-3 field
     */
    const COL_LANG_ISO_639-3 = 'langs.lang_iso_639-3';

    /**
     * the column name for the lang_name field
     */
    const COL_LANG_NAME = 'langs.lang_name';

    /**
     * the column name for the lang_name_original field
     */
    const COL_LANG_NAME_ORIGINAL = 'langs.lang_name_original';

    /**
     * the column name for the lang_created field
     */
    const COL_LANG_CREATED = 'langs.lang_created';

    /**
     * the column name for the lang_updated field
     */
    const COL_LANG_UPDATED = 'langs.lang_updated';

    /**
     * the column name for the lang_deleted field
     */
    const COL_LANG_DELETED = 'langs.lang_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'Iso639-1', 'Iso639-2', 'Iso639-3', 'Name', 'NameOriginal', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'iso639-1', 'iso639-2', 'iso639-3', 'name', 'nameOriginal', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(LangTableMap::COL_LANG_ID, LangTableMap::COL_LANG_ISO_639-1, LangTableMap::COL_LANG_ISO_639-2, LangTableMap::COL_LANG_ISO_639-3, LangTableMap::COL_LANG_NAME, LangTableMap::COL_LANG_NAME_ORIGINAL, LangTableMap::COL_LANG_CREATED, LangTableMap::COL_LANG_UPDATED, LangTableMap::COL_LANG_DELETED, ),
        self::TYPE_FIELDNAME     => array('lang_id', 'lang_iso_639-1', 'lang_iso_639-2', 'lang_iso_639-3', 'lang_name', 'lang_name_original', 'lang_created', 'lang_updated', 'lang_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Iso639-1' => 1, 'Iso639-2' => 2, 'Iso639-3' => 3, 'Name' => 4, 'NameOriginal' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, 'DeletedAt' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'iso639-1' => 1, 'iso639-2' => 2, 'iso639-3' => 3, 'name' => 4, 'nameOriginal' => 5, 'createdAt' => 6, 'updatedAt' => 7, 'deletedAt' => 8, ),
        self::TYPE_COLNAME       => array(LangTableMap::COL_LANG_ID => 0, LangTableMap::COL_LANG_ISO_639-1 => 1, LangTableMap::COL_LANG_ISO_639-2 => 2, LangTableMap::COL_LANG_ISO_639-3 => 3, LangTableMap::COL_LANG_NAME => 4, LangTableMap::COL_LANG_NAME_ORIGINAL => 5, LangTableMap::COL_LANG_CREATED => 6, LangTableMap::COL_LANG_UPDATED => 7, LangTableMap::COL_LANG_DELETED => 8, ),
        self::TYPE_FIELDNAME     => array('lang_id' => 0, 'lang_iso_639-1' => 1, 'lang_iso_639-2' => 2, 'lang_iso_639-3' => 3, 'lang_name' => 4, 'lang_name_original' => 5, 'lang_created' => 6, 'lang_updated' => 7, 'lang_deleted' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'LANG_ID',
        'Lang.Id' => 'LANG_ID',
        'id' => 'LANG_ID',
        'lang.id' => 'LANG_ID',
        'LangTableMap::COL_LANG_ID' => 'LANG_ID',
        'COL_LANG_ID' => 'LANG_ID',
        'lang_id' => 'LANG_ID',
        'langs.lang_id' => 'LANG_ID',
        'Iso639-1' => 'LANG_ISO_639-1',
        'Lang.Iso639-1' => 'LANG_ISO_639-1',
        'iso639-1' => 'LANG_ISO_639-1',
        'lang.iso639-1' => 'LANG_ISO_639-1',
        'LangTableMap::COL_LANG_ISO_639-1' => 'LANG_ISO_639-1',
        'COL_LANG_ISO_639-1' => 'LANG_ISO_639-1',
        'lang_iso_639-1' => 'LANG_ISO_639-1',
        'langs.lang_iso_639-1' => 'LANG_ISO_639-1',
        'Iso639-2' => 'LANG_ISO_639-2',
        'Lang.Iso639-2' => 'LANG_ISO_639-2',
        'iso639-2' => 'LANG_ISO_639-2',
        'lang.iso639-2' => 'LANG_ISO_639-2',
        'LangTableMap::COL_LANG_ISO_639-2' => 'LANG_ISO_639-2',
        'COL_LANG_ISO_639-2' => 'LANG_ISO_639-2',
        'lang_iso_639-2' => 'LANG_ISO_639-2',
        'langs.lang_iso_639-2' => 'LANG_ISO_639-2',
        'Iso639-3' => 'LANG_ISO_639-3',
        'Lang.Iso639-3' => 'LANG_ISO_639-3',
        'iso639-3' => 'LANG_ISO_639-3',
        'lang.iso639-3' => 'LANG_ISO_639-3',
        'LangTableMap::COL_LANG_ISO_639-3' => 'LANG_ISO_639-3',
        'COL_LANG_ISO_639-3' => 'LANG_ISO_639-3',
        'lang_iso_639-3' => 'LANG_ISO_639-3',
        'langs.lang_iso_639-3' => 'LANG_ISO_639-3',
        'Name' => 'LANG_NAME',
        'Lang.Name' => 'LANG_NAME',
        'name' => 'LANG_NAME',
        'lang.name' => 'LANG_NAME',
        'LangTableMap::COL_LANG_NAME' => 'LANG_NAME',
        'COL_LANG_NAME' => 'LANG_NAME',
        'lang_name' => 'LANG_NAME',
        'langs.lang_name' => 'LANG_NAME',
        'NameOriginal' => 'LANG_NAME_ORIGINAL',
        'Lang.NameOriginal' => 'LANG_NAME_ORIGINAL',
        'nameOriginal' => 'LANG_NAME_ORIGINAL',
        'lang.nameOriginal' => 'LANG_NAME_ORIGINAL',
        'LangTableMap::COL_LANG_NAME_ORIGINAL' => 'LANG_NAME_ORIGINAL',
        'COL_LANG_NAME_ORIGINAL' => 'LANG_NAME_ORIGINAL',
        'lang_name_original' => 'LANG_NAME_ORIGINAL',
        'langs.lang_name_original' => 'LANG_NAME_ORIGINAL',
        'CreatedAt' => 'LANG_CREATED',
        'Lang.CreatedAt' => 'LANG_CREATED',
        'createdAt' => 'LANG_CREATED',
        'lang.createdAt' => 'LANG_CREATED',
        'LangTableMap::COL_LANG_CREATED' => 'LANG_CREATED',
        'COL_LANG_CREATED' => 'LANG_CREATED',
        'lang_created' => 'LANG_CREATED',
        'langs.lang_created' => 'LANG_CREATED',
        'UpdatedAt' => 'LANG_UPDATED',
        'Lang.UpdatedAt' => 'LANG_UPDATED',
        'updatedAt' => 'LANG_UPDATED',
        'lang.updatedAt' => 'LANG_UPDATED',
        'LangTableMap::COL_LANG_UPDATED' => 'LANG_UPDATED',
        'COL_LANG_UPDATED' => 'LANG_UPDATED',
        'lang_updated' => 'LANG_UPDATED',
        'langs.lang_updated' => 'LANG_UPDATED',
        'DeletedAt' => 'LANG_DELETED',
        'Lang.DeletedAt' => 'LANG_DELETED',
        'deletedAt' => 'LANG_DELETED',
        'lang.deletedAt' => 'LANG_DELETED',
        'LangTableMap::COL_LANG_DELETED' => 'LANG_DELETED',
        'COL_LANG_DELETED' => 'LANG_DELETED',
        'lang_deleted' => 'LANG_DELETED',
        'langs.lang_deleted' => 'LANG_DELETED',
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
        $this->setName('langs');
        $this->setPhpName('Lang');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Lang');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('lang_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('lang_iso_639-1', 'Iso639-1', 'VARCHAR', false, 2, null);
        $this->addColumn('lang_iso_639-2', 'Iso639-2', 'VARCHAR', false, 7, null);
        $this->addColumn('lang_iso_639-3', 'Iso639-3', 'VARCHAR', false, 7, null);
        $this->addColumn('lang_name', 'Name', 'VARCHAR', false, 27, null);
        $this->addColumn('lang_name_original', 'NameOriginal', 'VARCHAR', false, 35, null);
        $this->addColumn('lang_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('lang_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('lang_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => array('create_column' => 'lang_created', 'update_column' => 'lang_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
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
        return $withPrefix ? LangTableMap::CLASS_DEFAULT : LangTableMap::OM_CLASS;
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
     * @return array           (Lang object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LangTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LangTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LangTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LangTableMap::OM_CLASS;
            /** @var Lang $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LangTableMap::addInstanceToPool($obj, $key);
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
            $key = LangTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LangTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Lang $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LangTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(LangTableMap::COL_LANG_ID);
            $criteria->addSelectColumn(LangTableMap::COL_LANG_ISO_639-1);
            $criteria->addSelectColumn(LangTableMap::COL_LANG_ISO_639-2);
            $criteria->addSelectColumn(LangTableMap::COL_LANG_ISO_639-3);
            $criteria->addSelectColumn(LangTableMap::COL_LANG_NAME);
            $criteria->addSelectColumn(LangTableMap::COL_LANG_NAME_ORIGINAL);
            $criteria->addSelectColumn(LangTableMap::COL_LANG_CREATED);
            $criteria->addSelectColumn(LangTableMap::COL_LANG_UPDATED);
            $criteria->addSelectColumn(LangTableMap::COL_LANG_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.lang_id');
            $criteria->addSelectColumn($alias . '.lang_iso_639-1');
            $criteria->addSelectColumn($alias . '.lang_iso_639-2');
            $criteria->addSelectColumn($alias . '.lang_iso_639-3');
            $criteria->addSelectColumn($alias . '.lang_name');
            $criteria->addSelectColumn($alias . '.lang_name_original');
            $criteria->addSelectColumn($alias . '.lang_created');
            $criteria->addSelectColumn($alias . '.lang_updated');
            $criteria->addSelectColumn($alias . '.lang_deleted');
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
            $criteria->removeSelectColumn(LangTableMap::COL_LANG_ID);
            $criteria->removeSelectColumn(LangTableMap::COL_LANG_ISO_639-1);
            $criteria->removeSelectColumn(LangTableMap::COL_LANG_ISO_639-2);
            $criteria->removeSelectColumn(LangTableMap::COL_LANG_ISO_639-3);
            $criteria->removeSelectColumn(LangTableMap::COL_LANG_NAME);
            $criteria->removeSelectColumn(LangTableMap::COL_LANG_NAME_ORIGINAL);
            $criteria->removeSelectColumn(LangTableMap::COL_LANG_CREATED);
            $criteria->removeSelectColumn(LangTableMap::COL_LANG_UPDATED);
            $criteria->removeSelectColumn(LangTableMap::COL_LANG_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.lang_id');
            $criteria->removeSelectColumn($alias . '.lang_iso_639-1');
            $criteria->removeSelectColumn($alias . '.lang_iso_639-2');
            $criteria->removeSelectColumn($alias . '.lang_iso_639-3');
            $criteria->removeSelectColumn($alias . '.lang_name');
            $criteria->removeSelectColumn($alias . '.lang_name_original');
            $criteria->removeSelectColumn($alias . '.lang_created');
            $criteria->removeSelectColumn($alias . '.lang_updated');
            $criteria->removeSelectColumn($alias . '.lang_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(LangTableMap::DATABASE_NAME)->getTable(LangTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LangTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(LangTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new LangTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Lang or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Lang object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LangTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Lang) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LangTableMap::DATABASE_NAME);
            $criteria->add(LangTableMap::COL_LANG_ID, (array) $values, Criteria::IN);
        }

        $query = LangQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LangTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                LangTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the langs table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LangQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Lang or Criteria object.
     *
     * @param mixed               $criteria Criteria or Lang object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LangTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Lang object
        }

        if ($criteria->containsKey(LangTableMap::COL_LANG_ID) && $criteria->keyContainsValue(LangTableMap::COL_LANG_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LangTableMap::COL_LANG_ID.')');
        }


        // Set the correct dbName
        $query = LangQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LangTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LangTableMap::buildTableMap();
