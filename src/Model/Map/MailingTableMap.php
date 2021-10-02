<?php

namespace Model\Map;

use Model\Mailing;
use Model\MailingQuery;
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
 * This class defines the structure of the 'mailing' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class MailingTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.MailingTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'mailing';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Mailing';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Mailing';

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
     * the column name for the mailing_id field
     */
    const COL_MAILING_ID = 'mailing.mailing_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'mailing.site_id';

    /**
     * the column name for the mailing_email field
     */
    const COL_MAILING_EMAIL = 'mailing.mailing_email';

    /**
     * the column name for the mailing_block field
     */
    const COL_MAILING_BLOCK = 'mailing.mailing_block';

    /**
     * the column name for the mailing_checked field
     */
    const COL_MAILING_CHECKED = 'mailing.mailing_checked';

    /**
     * the column name for the mailing_date field
     */
    const COL_MAILING_DATE = 'mailing.mailing_date';

    /**
     * the column name for the mailing_created field
     */
    const COL_MAILING_CREATED = 'mailing.mailing_created';

    /**
     * the column name for the mailing_updated field
     */
    const COL_MAILING_UPDATED = 'mailing.mailing_updated';

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
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'Email', 'Block', 'Checked', 'Date', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'email', 'block', 'checked', 'date', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(MailingTableMap::COL_MAILING_ID, MailingTableMap::COL_SITE_ID, MailingTableMap::COL_MAILING_EMAIL, MailingTableMap::COL_MAILING_BLOCK, MailingTableMap::COL_MAILING_CHECKED, MailingTableMap::COL_MAILING_DATE, MailingTableMap::COL_MAILING_CREATED, MailingTableMap::COL_MAILING_UPDATED, ),
        self::TYPE_FIELDNAME     => array('mailing_id', 'site_id', 'mailing_email', 'mailing_block', 'mailing_checked', 'mailing_date', 'mailing_created', 'mailing_updated', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'Email' => 2, 'Block' => 3, 'Checked' => 4, 'Date' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'email' => 2, 'block' => 3, 'checked' => 4, 'date' => 5, 'createdAt' => 6, 'updatedAt' => 7, ),
        self::TYPE_COLNAME       => array(MailingTableMap::COL_MAILING_ID => 0, MailingTableMap::COL_SITE_ID => 1, MailingTableMap::COL_MAILING_EMAIL => 2, MailingTableMap::COL_MAILING_BLOCK => 3, MailingTableMap::COL_MAILING_CHECKED => 4, MailingTableMap::COL_MAILING_DATE => 5, MailingTableMap::COL_MAILING_CREATED => 6, MailingTableMap::COL_MAILING_UPDATED => 7, ),
        self::TYPE_FIELDNAME     => array('mailing_id' => 0, 'site_id' => 1, 'mailing_email' => 2, 'mailing_block' => 3, 'mailing_checked' => 4, 'mailing_date' => 5, 'mailing_created' => 6, 'mailing_updated' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'MAILING_ID',
        'Mailing.Id' => 'MAILING_ID',
        'id' => 'MAILING_ID',
        'mailing.id' => 'MAILING_ID',
        'MailingTableMap::COL_MAILING_ID' => 'MAILING_ID',
        'COL_MAILING_ID' => 'MAILING_ID',
        'mailing_id' => 'MAILING_ID',
        'mailing.mailing_id' => 'MAILING_ID',
        'SiteId' => 'SITE_ID',
        'Mailing.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'mailing.siteId' => 'SITE_ID',
        'MailingTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'mailing.site_id' => 'SITE_ID',
        'Email' => 'MAILING_EMAIL',
        'Mailing.Email' => 'MAILING_EMAIL',
        'email' => 'MAILING_EMAIL',
        'mailing.email' => 'MAILING_EMAIL',
        'MailingTableMap::COL_MAILING_EMAIL' => 'MAILING_EMAIL',
        'COL_MAILING_EMAIL' => 'MAILING_EMAIL',
        'mailing_email' => 'MAILING_EMAIL',
        'mailing.mailing_email' => 'MAILING_EMAIL',
        'Block' => 'MAILING_BLOCK',
        'Mailing.Block' => 'MAILING_BLOCK',
        'block' => 'MAILING_BLOCK',
        'mailing.block' => 'MAILING_BLOCK',
        'MailingTableMap::COL_MAILING_BLOCK' => 'MAILING_BLOCK',
        'COL_MAILING_BLOCK' => 'MAILING_BLOCK',
        'mailing_block' => 'MAILING_BLOCK',
        'mailing.mailing_block' => 'MAILING_BLOCK',
        'Checked' => 'MAILING_CHECKED',
        'Mailing.Checked' => 'MAILING_CHECKED',
        'checked' => 'MAILING_CHECKED',
        'mailing.checked' => 'MAILING_CHECKED',
        'MailingTableMap::COL_MAILING_CHECKED' => 'MAILING_CHECKED',
        'COL_MAILING_CHECKED' => 'MAILING_CHECKED',
        'mailing_checked' => 'MAILING_CHECKED',
        'mailing.mailing_checked' => 'MAILING_CHECKED',
        'Date' => 'MAILING_DATE',
        'Mailing.Date' => 'MAILING_DATE',
        'date' => 'MAILING_DATE',
        'mailing.date' => 'MAILING_DATE',
        'MailingTableMap::COL_MAILING_DATE' => 'MAILING_DATE',
        'COL_MAILING_DATE' => 'MAILING_DATE',
        'mailing_date' => 'MAILING_DATE',
        'mailing.mailing_date' => 'MAILING_DATE',
        'CreatedAt' => 'MAILING_CREATED',
        'Mailing.CreatedAt' => 'MAILING_CREATED',
        'createdAt' => 'MAILING_CREATED',
        'mailing.createdAt' => 'MAILING_CREATED',
        'MailingTableMap::COL_MAILING_CREATED' => 'MAILING_CREATED',
        'COL_MAILING_CREATED' => 'MAILING_CREATED',
        'mailing_created' => 'MAILING_CREATED',
        'mailing.mailing_created' => 'MAILING_CREATED',
        'UpdatedAt' => 'MAILING_UPDATED',
        'Mailing.UpdatedAt' => 'MAILING_UPDATED',
        'updatedAt' => 'MAILING_UPDATED',
        'mailing.updatedAt' => 'MAILING_UPDATED',
        'MailingTableMap::COL_MAILING_UPDATED' => 'MAILING_UPDATED',
        'COL_MAILING_UPDATED' => 'MAILING_UPDATED',
        'mailing_updated' => 'MAILING_UPDATED',
        'mailing.mailing_updated' => 'MAILING_UPDATED',
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
        $this->setName('mailing');
        $this->setPhpName('Mailing');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Mailing');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('mailing_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'TINYINT', false, 3, null);
        $this->addColumn('mailing_email', 'Email', 'VARCHAR', false, 256, '');
        $this->addColumn('mailing_block', 'Block', 'BOOLEAN', false, 1, null);
        $this->addColumn('mailing_checked', 'Checked', 'BOOLEAN', false, 1, null);
        $this->addColumn('mailing_date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('mailing_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('mailing_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'mailing_created', 'update_column' => 'mailing_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? MailingTableMap::CLASS_DEFAULT : MailingTableMap::OM_CLASS;
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
     * @return array           (Mailing object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MailingTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MailingTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MailingTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MailingTableMap::OM_CLASS;
            /** @var Mailing $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MailingTableMap::addInstanceToPool($obj, $key);
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
            $key = MailingTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MailingTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Mailing $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MailingTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MailingTableMap::COL_MAILING_ID);
            $criteria->addSelectColumn(MailingTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(MailingTableMap::COL_MAILING_EMAIL);
            $criteria->addSelectColumn(MailingTableMap::COL_MAILING_BLOCK);
            $criteria->addSelectColumn(MailingTableMap::COL_MAILING_CHECKED);
            $criteria->addSelectColumn(MailingTableMap::COL_MAILING_DATE);
            $criteria->addSelectColumn(MailingTableMap::COL_MAILING_CREATED);
            $criteria->addSelectColumn(MailingTableMap::COL_MAILING_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.mailing_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.mailing_email');
            $criteria->addSelectColumn($alias . '.mailing_block');
            $criteria->addSelectColumn($alias . '.mailing_checked');
            $criteria->addSelectColumn($alias . '.mailing_date');
            $criteria->addSelectColumn($alias . '.mailing_created');
            $criteria->addSelectColumn($alias . '.mailing_updated');
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
            $criteria->removeSelectColumn(MailingTableMap::COL_MAILING_ID);
            $criteria->removeSelectColumn(MailingTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(MailingTableMap::COL_MAILING_EMAIL);
            $criteria->removeSelectColumn(MailingTableMap::COL_MAILING_BLOCK);
            $criteria->removeSelectColumn(MailingTableMap::COL_MAILING_CHECKED);
            $criteria->removeSelectColumn(MailingTableMap::COL_MAILING_DATE);
            $criteria->removeSelectColumn(MailingTableMap::COL_MAILING_CREATED);
            $criteria->removeSelectColumn(MailingTableMap::COL_MAILING_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.mailing_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.mailing_email');
            $criteria->removeSelectColumn($alias . '.mailing_block');
            $criteria->removeSelectColumn($alias . '.mailing_checked');
            $criteria->removeSelectColumn($alias . '.mailing_date');
            $criteria->removeSelectColumn($alias . '.mailing_created');
            $criteria->removeSelectColumn($alias . '.mailing_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(MailingTableMap::DATABASE_NAME)->getTable(MailingTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Mailing or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Mailing object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MailingTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Mailing) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MailingTableMap::DATABASE_NAME);
            $criteria->add(MailingTableMap::COL_MAILING_ID, (array) $values, Criteria::IN);
        }

        $query = MailingQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MailingTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MailingTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the mailing table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MailingQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Mailing or Criteria object.
     *
     * @param mixed               $criteria Criteria or Mailing object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailingTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Mailing object
        }

        if ($criteria->containsKey(MailingTableMap::COL_MAILING_ID) && $criteria->keyContainsValue(MailingTableMap::COL_MAILING_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MailingTableMap::COL_MAILING_ID.')');
        }


        // Set the correct dbName
        $query = MailingQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MailingTableMap
