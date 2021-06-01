<?php

namespace Model\Map;

use Model\TicketComment;
use Model\TicketCommentQuery;
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
 * This class defines the structure of the 'ticket_comment' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class TicketCommentTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.TicketCommentTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'ticket_comment';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\TicketComment';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.TicketComment';

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
     * the column name for the ticket_comment_id field
     */
    const COL_TICKET_COMMENT_ID = 'ticket_comment.ticket_comment_id';

    /**
     * the column name for the ticket_id field
     */
    const COL_TICKET_ID = 'ticket_comment.ticket_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'ticket_comment.user_id';

    /**
     * the column name for the ticket_comment_content field
     */
    const COL_TICKET_COMMENT_CONTENT = 'ticket_comment.ticket_comment_content';

    /**
     * the column name for the ticket_comment_created field
     */
    const COL_TICKET_COMMENT_CREATED = 'ticket_comment.ticket_comment_created';

    /**
     * the column name for the ticket_comment_update field
     */
    const COL_TICKET_COMMENT_UPDATE = 'ticket_comment.ticket_comment_update';

    /**
     * the column name for the ticket_comment_deleted field
     */
    const COL_TICKET_COMMENT_DELETED = 'ticket_comment.ticket_comment_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'TicketId', 'UserId', 'Content', 'CreatedAt', 'Update', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'ticketId', 'userId', 'content', 'createdAt', 'update', 'deletedAt', ),
        self::TYPE_COLNAME       => array(TicketCommentTableMap::COL_TICKET_COMMENT_ID, TicketCommentTableMap::COL_TICKET_ID, TicketCommentTableMap::COL_USER_ID, TicketCommentTableMap::COL_TICKET_COMMENT_CONTENT, TicketCommentTableMap::COL_TICKET_COMMENT_CREATED, TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE, TicketCommentTableMap::COL_TICKET_COMMENT_DELETED, ),
        self::TYPE_FIELDNAME     => array('ticket_comment_id', 'ticket_id', 'user_id', 'ticket_comment_content', 'ticket_comment_created', 'ticket_comment_update', 'ticket_comment_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'TicketId' => 1, 'UserId' => 2, 'Content' => 3, 'CreatedAt' => 4, 'Update' => 5, 'DeletedAt' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'ticketId' => 1, 'userId' => 2, 'content' => 3, 'createdAt' => 4, 'update' => 5, 'deletedAt' => 6, ),
        self::TYPE_COLNAME       => array(TicketCommentTableMap::COL_TICKET_COMMENT_ID => 0, TicketCommentTableMap::COL_TICKET_ID => 1, TicketCommentTableMap::COL_USER_ID => 2, TicketCommentTableMap::COL_TICKET_COMMENT_CONTENT => 3, TicketCommentTableMap::COL_TICKET_COMMENT_CREATED => 4, TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE => 5, TicketCommentTableMap::COL_TICKET_COMMENT_DELETED => 6, ),
        self::TYPE_FIELDNAME     => array('ticket_comment_id' => 0, 'ticket_id' => 1, 'user_id' => 2, 'ticket_comment_content' => 3, 'ticket_comment_created' => 4, 'ticket_comment_update' => 5, 'ticket_comment_deleted' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'TICKET_COMMENT_ID',
        'TicketComment.Id' => 'TICKET_COMMENT_ID',
        'id' => 'TICKET_COMMENT_ID',
        'ticketComment.id' => 'TICKET_COMMENT_ID',
        'TicketCommentTableMap::COL_TICKET_COMMENT_ID' => 'TICKET_COMMENT_ID',
        'COL_TICKET_COMMENT_ID' => 'TICKET_COMMENT_ID',
        'ticket_comment_id' => 'TICKET_COMMENT_ID',
        'ticket_comment.ticket_comment_id' => 'TICKET_COMMENT_ID',
        'TicketId' => 'TICKET_ID',
        'TicketComment.TicketId' => 'TICKET_ID',
        'ticketId' => 'TICKET_ID',
        'ticketComment.ticketId' => 'TICKET_ID',
        'TicketCommentTableMap::COL_TICKET_ID' => 'TICKET_ID',
        'COL_TICKET_ID' => 'TICKET_ID',
        'ticket_id' => 'TICKET_ID',
        'ticket_comment.ticket_id' => 'TICKET_ID',
        'UserId' => 'USER_ID',
        'TicketComment.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'ticketComment.userId' => 'USER_ID',
        'TicketCommentTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'ticket_comment.user_id' => 'USER_ID',
        'Content' => 'TICKET_COMMENT_CONTENT',
        'TicketComment.Content' => 'TICKET_COMMENT_CONTENT',
        'content' => 'TICKET_COMMENT_CONTENT',
        'ticketComment.content' => 'TICKET_COMMENT_CONTENT',
        'TicketCommentTableMap::COL_TICKET_COMMENT_CONTENT' => 'TICKET_COMMENT_CONTENT',
        'COL_TICKET_COMMENT_CONTENT' => 'TICKET_COMMENT_CONTENT',
        'ticket_comment_content' => 'TICKET_COMMENT_CONTENT',
        'ticket_comment.ticket_comment_content' => 'TICKET_COMMENT_CONTENT',
        'CreatedAt' => 'TICKET_COMMENT_CREATED',
        'TicketComment.CreatedAt' => 'TICKET_COMMENT_CREATED',
        'createdAt' => 'TICKET_COMMENT_CREATED',
        'ticketComment.createdAt' => 'TICKET_COMMENT_CREATED',
        'TicketCommentTableMap::COL_TICKET_COMMENT_CREATED' => 'TICKET_COMMENT_CREATED',
        'COL_TICKET_COMMENT_CREATED' => 'TICKET_COMMENT_CREATED',
        'ticket_comment_created' => 'TICKET_COMMENT_CREATED',
        'ticket_comment.ticket_comment_created' => 'TICKET_COMMENT_CREATED',
        'Update' => 'TICKET_COMMENT_UPDATE',
        'TicketComment.Update' => 'TICKET_COMMENT_UPDATE',
        'update' => 'TICKET_COMMENT_UPDATE',
        'ticketComment.update' => 'TICKET_COMMENT_UPDATE',
        'TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE' => 'TICKET_COMMENT_UPDATE',
        'COL_TICKET_COMMENT_UPDATE' => 'TICKET_COMMENT_UPDATE',
        'ticket_comment_update' => 'TICKET_COMMENT_UPDATE',
        'ticket_comment.ticket_comment_update' => 'TICKET_COMMENT_UPDATE',
        'DeletedAt' => 'TICKET_COMMENT_DELETED',
        'TicketComment.DeletedAt' => 'TICKET_COMMENT_DELETED',
        'deletedAt' => 'TICKET_COMMENT_DELETED',
        'ticketComment.deletedAt' => 'TICKET_COMMENT_DELETED',
        'TicketCommentTableMap::COL_TICKET_COMMENT_DELETED' => 'TICKET_COMMENT_DELETED',
        'COL_TICKET_COMMENT_DELETED' => 'TICKET_COMMENT_DELETED',
        'ticket_comment_deleted' => 'TICKET_COMMENT_DELETED',
        'ticket_comment.ticket_comment_deleted' => 'TICKET_COMMENT_DELETED',
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
        $this->setName('ticket_comment');
        $this->setPhpName('TicketComment');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\TicketComment');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ticket_comment_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('ticket_id', 'TicketId', 'INTEGER', false, null, null);
        $this->addColumn('user_id', 'UserId', 'INTEGER', false, null, null);
        $this->addColumn('ticket_comment_content', 'Content', 'LONGVARCHAR', false, null, null);
        $this->addColumn('ticket_comment_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('ticket_comment_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('ticket_comment_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => array('create_column' => 'ticket_comment_created', 'update_column' => 'ticket_comment_update', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
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
        return $withPrefix ? TicketCommentTableMap::CLASS_DEFAULT : TicketCommentTableMap::OM_CLASS;
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
     * @return array           (TicketComment object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = TicketCommentTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = TicketCommentTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + TicketCommentTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = TicketCommentTableMap::OM_CLASS;
            /** @var TicketComment $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            TicketCommentTableMap::addInstanceToPool($obj, $key);
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
            $key = TicketCommentTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = TicketCommentTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var TicketComment $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                TicketCommentTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(TicketCommentTableMap::COL_TICKET_COMMENT_ID);
            $criteria->addSelectColumn(TicketCommentTableMap::COL_TICKET_ID);
            $criteria->addSelectColumn(TicketCommentTableMap::COL_USER_ID);
            $criteria->addSelectColumn(TicketCommentTableMap::COL_TICKET_COMMENT_CONTENT);
            $criteria->addSelectColumn(TicketCommentTableMap::COL_TICKET_COMMENT_CREATED);
            $criteria->addSelectColumn(TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE);
            $criteria->addSelectColumn(TicketCommentTableMap::COL_TICKET_COMMENT_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.ticket_comment_id');
            $criteria->addSelectColumn($alias . '.ticket_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.ticket_comment_content');
            $criteria->addSelectColumn($alias . '.ticket_comment_created');
            $criteria->addSelectColumn($alias . '.ticket_comment_update');
            $criteria->addSelectColumn($alias . '.ticket_comment_deleted');
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
            $criteria->removeSelectColumn(TicketCommentTableMap::COL_TICKET_COMMENT_ID);
            $criteria->removeSelectColumn(TicketCommentTableMap::COL_TICKET_ID);
            $criteria->removeSelectColumn(TicketCommentTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(TicketCommentTableMap::COL_TICKET_COMMENT_CONTENT);
            $criteria->removeSelectColumn(TicketCommentTableMap::COL_TICKET_COMMENT_CREATED);
            $criteria->removeSelectColumn(TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE);
            $criteria->removeSelectColumn(TicketCommentTableMap::COL_TICKET_COMMENT_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.ticket_comment_id');
            $criteria->removeSelectColumn($alias . '.ticket_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.ticket_comment_content');
            $criteria->removeSelectColumn($alias . '.ticket_comment_created');
            $criteria->removeSelectColumn($alias . '.ticket_comment_update');
            $criteria->removeSelectColumn($alias . '.ticket_comment_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(TicketCommentTableMap::DATABASE_NAME)->getTable(TicketCommentTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(TicketCommentTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(TicketCommentTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new TicketCommentTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a TicketComment or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or TicketComment object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(TicketCommentTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\TicketComment) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(TicketCommentTableMap::DATABASE_NAME);
            $criteria->add(TicketCommentTableMap::COL_TICKET_COMMENT_ID, (array) $values, Criteria::IN);
        }

        $query = TicketCommentQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            TicketCommentTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                TicketCommentTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the ticket_comment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return TicketCommentQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a TicketComment or Criteria object.
     *
     * @param mixed               $criteria Criteria or TicketComment object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TicketCommentTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from TicketComment object
        }

        if ($criteria->containsKey(TicketCommentTableMap::COL_TICKET_COMMENT_ID) && $criteria->keyContainsValue(TicketCommentTableMap::COL_TICKET_COMMENT_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.TicketCommentTableMap::COL_TICKET_COMMENT_ID.')');
        }


        // Set the correct dbName
        $query = TicketCommentQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // TicketCommentTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
TicketCommentTableMap::buildTableMap();
