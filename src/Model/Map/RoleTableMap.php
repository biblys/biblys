<?php

namespace Model\Map;

use Model\Role;
use Model\RoleQuery;
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
 * This class defines the structure of the 'roles' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class RoleTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.RoleTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'roles';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Role';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Role';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 13;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 13;

    /**
     * the column name for the id field
     */
    const COL_ID = 'roles.id';

    /**
     * the column name for the article_id field
     */
    const COL_ARTICLE_ID = 'roles.article_id';

    /**
     * the column name for the book_id field
     */
    const COL_BOOK_ID = 'roles.book_id';

    /**
     * the column name for the event_id field
     */
    const COL_EVENT_ID = 'roles.event_id';

    /**
     * the column name for the people_id field
     */
    const COL_PEOPLE_ID = 'roles.people_id';

    /**
     * the column name for the job_id field
     */
    const COL_JOB_ID = 'roles.job_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'roles.user_id';

    /**
     * the column name for the role_hide field
     */
    const COL_ROLE_HIDE = 'roles.role_hide';

    /**
     * the column name for the role_presence field
     */
    const COL_ROLE_PRESENCE = 'roles.role_presence';

    /**
     * the column name for the role_date field
     */
    const COL_ROLE_DATE = 'roles.role_date';

    /**
     * the column name for the role_created field
     */
    const COL_ROLE_CREATED = 'roles.role_created';

    /**
     * the column name for the role_updated field
     */
    const COL_ROLE_UPDATED = 'roles.role_updated';

    /**
     * the column name for the role_deleted field
     */
    const COL_ROLE_DELETED = 'roles.role_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'ArticleId', 'BookId', 'EventId', 'PeopleId', 'JobId', 'UserId', 'Hide', 'Presence', 'Date', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'articleId', 'bookId', 'eventId', 'peopleId', 'jobId', 'userId', 'hide', 'presence', 'date', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(RoleTableMap::COL_ID, RoleTableMap::COL_ARTICLE_ID, RoleTableMap::COL_BOOK_ID, RoleTableMap::COL_EVENT_ID, RoleTableMap::COL_PEOPLE_ID, RoleTableMap::COL_JOB_ID, RoleTableMap::COL_USER_ID, RoleTableMap::COL_ROLE_HIDE, RoleTableMap::COL_ROLE_PRESENCE, RoleTableMap::COL_ROLE_DATE, RoleTableMap::COL_ROLE_CREATED, RoleTableMap::COL_ROLE_UPDATED, RoleTableMap::COL_ROLE_DELETED, ),
        self::TYPE_FIELDNAME     => array('id', 'article_id', 'book_id', 'event_id', 'people_id', 'job_id', 'user_id', 'role_hide', 'role_presence', 'role_date', 'role_created', 'role_updated', 'role_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ArticleId' => 1, 'BookId' => 2, 'EventId' => 3, 'PeopleId' => 4, 'JobId' => 5, 'UserId' => 6, 'Hide' => 7, 'Presence' => 8, 'Date' => 9, 'CreatedAt' => 10, 'UpdatedAt' => 11, 'DeletedAt' => 12, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'articleId' => 1, 'bookId' => 2, 'eventId' => 3, 'peopleId' => 4, 'jobId' => 5, 'userId' => 6, 'hide' => 7, 'presence' => 8, 'date' => 9, 'createdAt' => 10, 'updatedAt' => 11, 'deletedAt' => 12, ),
        self::TYPE_COLNAME       => array(RoleTableMap::COL_ID => 0, RoleTableMap::COL_ARTICLE_ID => 1, RoleTableMap::COL_BOOK_ID => 2, RoleTableMap::COL_EVENT_ID => 3, RoleTableMap::COL_PEOPLE_ID => 4, RoleTableMap::COL_JOB_ID => 5, RoleTableMap::COL_USER_ID => 6, RoleTableMap::COL_ROLE_HIDE => 7, RoleTableMap::COL_ROLE_PRESENCE => 8, RoleTableMap::COL_ROLE_DATE => 9, RoleTableMap::COL_ROLE_CREATED => 10, RoleTableMap::COL_ROLE_UPDATED => 11, RoleTableMap::COL_ROLE_DELETED => 12, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'article_id' => 1, 'book_id' => 2, 'event_id' => 3, 'people_id' => 4, 'job_id' => 5, 'user_id' => 6, 'role_hide' => 7, 'role_presence' => 8, 'role_date' => 9, 'role_created' => 10, 'role_updated' => 11, 'role_deleted' => 12, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'ID',
        'Role.Id' => 'ID',
        'id' => 'ID',
        'role.id' => 'ID',
        'RoleTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'id' => 'ID',
        'roles.id' => 'ID',
        'ArticleId' => 'ARTICLE_ID',
        'Role.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'role.articleId' => 'ARTICLE_ID',
        'RoleTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'roles.article_id' => 'ARTICLE_ID',
        'BookId' => 'BOOK_ID',
        'Role.BookId' => 'BOOK_ID',
        'bookId' => 'BOOK_ID',
        'role.bookId' => 'BOOK_ID',
        'RoleTableMap::COL_BOOK_ID' => 'BOOK_ID',
        'COL_BOOK_ID' => 'BOOK_ID',
        'book_id' => 'BOOK_ID',
        'roles.book_id' => 'BOOK_ID',
        'EventId' => 'EVENT_ID',
        'Role.EventId' => 'EVENT_ID',
        'eventId' => 'EVENT_ID',
        'role.eventId' => 'EVENT_ID',
        'RoleTableMap::COL_EVENT_ID' => 'EVENT_ID',
        'COL_EVENT_ID' => 'EVENT_ID',
        'event_id' => 'EVENT_ID',
        'roles.event_id' => 'EVENT_ID',
        'PeopleId' => 'PEOPLE_ID',
        'Role.PeopleId' => 'PEOPLE_ID',
        'peopleId' => 'PEOPLE_ID',
        'role.peopleId' => 'PEOPLE_ID',
        'RoleTableMap::COL_PEOPLE_ID' => 'PEOPLE_ID',
        'COL_PEOPLE_ID' => 'PEOPLE_ID',
        'people_id' => 'PEOPLE_ID',
        'roles.people_id' => 'PEOPLE_ID',
        'JobId' => 'JOB_ID',
        'Role.JobId' => 'JOB_ID',
        'jobId' => 'JOB_ID',
        'role.jobId' => 'JOB_ID',
        'RoleTableMap::COL_JOB_ID' => 'JOB_ID',
        'COL_JOB_ID' => 'JOB_ID',
        'job_id' => 'JOB_ID',
        'roles.job_id' => 'JOB_ID',
        'UserId' => 'USER_ID',
        'Role.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'role.userId' => 'USER_ID',
        'RoleTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'roles.user_id' => 'USER_ID',
        'Hide' => 'ROLE_HIDE',
        'Role.Hide' => 'ROLE_HIDE',
        'hide' => 'ROLE_HIDE',
        'role.hide' => 'ROLE_HIDE',
        'RoleTableMap::COL_ROLE_HIDE' => 'ROLE_HIDE',
        'COL_ROLE_HIDE' => 'ROLE_HIDE',
        'role_hide' => 'ROLE_HIDE',
        'roles.role_hide' => 'ROLE_HIDE',
        'Presence' => 'ROLE_PRESENCE',
        'Role.Presence' => 'ROLE_PRESENCE',
        'presence' => 'ROLE_PRESENCE',
        'role.presence' => 'ROLE_PRESENCE',
        'RoleTableMap::COL_ROLE_PRESENCE' => 'ROLE_PRESENCE',
        'COL_ROLE_PRESENCE' => 'ROLE_PRESENCE',
        'role_presence' => 'ROLE_PRESENCE',
        'roles.role_presence' => 'ROLE_PRESENCE',
        'Date' => 'ROLE_DATE',
        'Role.Date' => 'ROLE_DATE',
        'date' => 'ROLE_DATE',
        'role.date' => 'ROLE_DATE',
        'RoleTableMap::COL_ROLE_DATE' => 'ROLE_DATE',
        'COL_ROLE_DATE' => 'ROLE_DATE',
        'role_date' => 'ROLE_DATE',
        'roles.role_date' => 'ROLE_DATE',
        'CreatedAt' => 'ROLE_CREATED',
        'Role.CreatedAt' => 'ROLE_CREATED',
        'createdAt' => 'ROLE_CREATED',
        'role.createdAt' => 'ROLE_CREATED',
        'RoleTableMap::COL_ROLE_CREATED' => 'ROLE_CREATED',
        'COL_ROLE_CREATED' => 'ROLE_CREATED',
        'role_created' => 'ROLE_CREATED',
        'roles.role_created' => 'ROLE_CREATED',
        'UpdatedAt' => 'ROLE_UPDATED',
        'Role.UpdatedAt' => 'ROLE_UPDATED',
        'updatedAt' => 'ROLE_UPDATED',
        'role.updatedAt' => 'ROLE_UPDATED',
        'RoleTableMap::COL_ROLE_UPDATED' => 'ROLE_UPDATED',
        'COL_ROLE_UPDATED' => 'ROLE_UPDATED',
        'role_updated' => 'ROLE_UPDATED',
        'roles.role_updated' => 'ROLE_UPDATED',
        'DeletedAt' => 'ROLE_DELETED',
        'Role.DeletedAt' => 'ROLE_DELETED',
        'deletedAt' => 'ROLE_DELETED',
        'role.deletedAt' => 'ROLE_DELETED',
        'RoleTableMap::COL_ROLE_DELETED' => 'ROLE_DELETED',
        'COL_ROLE_DELETED' => 'ROLE_DELETED',
        'role_deleted' => 'ROLE_DELETED',
        'roles.role_deleted' => 'ROLE_DELETED',
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
        $this->setName('roles');
        $this->setPhpName('Role');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Role');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addForeignKey('article_id', 'ArticleId', 'INTEGER', 'articles', 'article_id', false, 10, null);
        $this->addColumn('book_id', 'BookId', 'INTEGER', false, 10, null);
        $this->addColumn('event_id', 'EventId', 'INTEGER', false, 10, null);
        $this->addColumn('people_id', 'PeopleId', 'INTEGER', false, 10, null);
        $this->addColumn('job_id', 'JobId', 'INTEGER', false, 10, null);
        $this->addColumn('user_id', 'UserId', 'INTEGER', false, 10, null);
        $this->addColumn('role_hide', 'Hide', 'BOOLEAN', false, 1, null);
        $this->addColumn('role_presence', 'Presence', 'VARCHAR', false, 256, null);
        $this->addColumn('role_date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('role_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('role_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('role_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Article', '\\Model\\Article', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':article_id',
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
            'timestampable' => array('create_column' => 'role_created', 'update_column' => 'role_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
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
        return $withPrefix ? RoleTableMap::CLASS_DEFAULT : RoleTableMap::OM_CLASS;
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
     * @return array           (Role object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = RoleTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = RoleTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + RoleTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = RoleTableMap::OM_CLASS;
            /** @var Role $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            RoleTableMap::addInstanceToPool($obj, $key);
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
            $key = RoleTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = RoleTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Role $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                RoleTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(RoleTableMap::COL_ID);
            $criteria->addSelectColumn(RoleTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(RoleTableMap::COL_BOOK_ID);
            $criteria->addSelectColumn(RoleTableMap::COL_EVENT_ID);
            $criteria->addSelectColumn(RoleTableMap::COL_PEOPLE_ID);
            $criteria->addSelectColumn(RoleTableMap::COL_JOB_ID);
            $criteria->addSelectColumn(RoleTableMap::COL_USER_ID);
            $criteria->addSelectColumn(RoleTableMap::COL_ROLE_HIDE);
            $criteria->addSelectColumn(RoleTableMap::COL_ROLE_PRESENCE);
            $criteria->addSelectColumn(RoleTableMap::COL_ROLE_DATE);
            $criteria->addSelectColumn(RoleTableMap::COL_ROLE_CREATED);
            $criteria->addSelectColumn(RoleTableMap::COL_ROLE_UPDATED);
            $criteria->addSelectColumn(RoleTableMap::COL_ROLE_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.book_id');
            $criteria->addSelectColumn($alias . '.event_id');
            $criteria->addSelectColumn($alias . '.people_id');
            $criteria->addSelectColumn($alias . '.job_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.role_hide');
            $criteria->addSelectColumn($alias . '.role_presence');
            $criteria->addSelectColumn($alias . '.role_date');
            $criteria->addSelectColumn($alias . '.role_created');
            $criteria->addSelectColumn($alias . '.role_updated');
            $criteria->addSelectColumn($alias . '.role_deleted');
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
            $criteria->removeSelectColumn(RoleTableMap::COL_ID);
            $criteria->removeSelectColumn(RoleTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(RoleTableMap::COL_BOOK_ID);
            $criteria->removeSelectColumn(RoleTableMap::COL_EVENT_ID);
            $criteria->removeSelectColumn(RoleTableMap::COL_PEOPLE_ID);
            $criteria->removeSelectColumn(RoleTableMap::COL_JOB_ID);
            $criteria->removeSelectColumn(RoleTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(RoleTableMap::COL_ROLE_HIDE);
            $criteria->removeSelectColumn(RoleTableMap::COL_ROLE_PRESENCE);
            $criteria->removeSelectColumn(RoleTableMap::COL_ROLE_DATE);
            $criteria->removeSelectColumn(RoleTableMap::COL_ROLE_CREATED);
            $criteria->removeSelectColumn(RoleTableMap::COL_ROLE_UPDATED);
            $criteria->removeSelectColumn(RoleTableMap::COL_ROLE_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.book_id');
            $criteria->removeSelectColumn($alias . '.event_id');
            $criteria->removeSelectColumn($alias . '.people_id');
            $criteria->removeSelectColumn($alias . '.job_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.role_hide');
            $criteria->removeSelectColumn($alias . '.role_presence');
            $criteria->removeSelectColumn($alias . '.role_date');
            $criteria->removeSelectColumn($alias . '.role_created');
            $criteria->removeSelectColumn($alias . '.role_updated');
            $criteria->removeSelectColumn($alias . '.role_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(RoleTableMap::DATABASE_NAME)->getTable(RoleTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(RoleTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(RoleTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new RoleTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Role or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Role object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Role) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(RoleTableMap::DATABASE_NAME);
            $criteria->add(RoleTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = RoleQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            RoleTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                RoleTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the roles table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return RoleQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Role or Criteria object.
     *
     * @param mixed               $criteria Criteria or Role object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Role object
        }

        if ($criteria->containsKey(RoleTableMap::COL_ID) && $criteria->keyContainsValue(RoleTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.RoleTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = RoleQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // RoleTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
RoleTableMap::buildTableMap();
