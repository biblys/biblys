<?php

namespace Model\Map;

use Model\Ticket;
use Model\TicketQuery;
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
 * This class defines the structure of the 'ticket' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class TicketTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.TicketTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'ticket';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Ticket';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Ticket';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Ticket';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the ticket_id field
     */
    public const COL_TICKET_ID = 'ticket.ticket_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'ticket.user_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'ticket.site_id';

    /**
     * the column name for the ticket_type field
     */
    public const COL_TICKET_TYPE = 'ticket.ticket_type';

    /**
     * the column name for the ticket_title field
     */
    public const COL_TICKET_TITLE = 'ticket.ticket_title';

    /**
     * the column name for the ticket_content field
     */
    public const COL_TICKET_CONTENT = 'ticket.ticket_content';

    /**
     * the column name for the ticket_priority field
     */
    public const COL_TICKET_PRIORITY = 'ticket.ticket_priority';

    /**
     * the column name for the ticket_created field
     */
    public const COL_TICKET_CREATED = 'ticket.ticket_created';

    /**
     * the column name for the ticket_updated field
     */
    public const COL_TICKET_UPDATED = 'ticket.ticket_updated';

    /**
     * the column name for the ticket_resolved field
     */
    public const COL_TICKET_RESOLVED = 'ticket.ticket_resolved';

    /**
     * the column name for the ticket_closed field
     */
    public const COL_TICKET_CLOSED = 'ticket.ticket_closed';

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
        self::TYPE_PHPNAME       => ['Id', 'UserId', 'SiteId', 'Type', 'Title', 'Content', 'Priority', 'CreatedAt', 'UpdatedAt', 'Resolved', 'Closed', ],
        self::TYPE_CAMELNAME     => ['id', 'userId', 'siteId', 'type', 'title', 'content', 'priority', 'createdAt', 'updatedAt', 'resolved', 'closed', ],
        self::TYPE_COLNAME       => [TicketTableMap::COL_TICKET_ID, TicketTableMap::COL_USER_ID, TicketTableMap::COL_SITE_ID, TicketTableMap::COL_TICKET_TYPE, TicketTableMap::COL_TICKET_TITLE, TicketTableMap::COL_TICKET_CONTENT, TicketTableMap::COL_TICKET_PRIORITY, TicketTableMap::COL_TICKET_CREATED, TicketTableMap::COL_TICKET_UPDATED, TicketTableMap::COL_TICKET_RESOLVED, TicketTableMap::COL_TICKET_CLOSED, ],
        self::TYPE_FIELDNAME     => ['ticket_id', 'user_id', 'site_id', 'ticket_type', 'ticket_title', 'ticket_content', 'ticket_priority', 'ticket_created', 'ticket_updated', 'ticket_resolved', 'ticket_closed', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'UserId' => 1, 'SiteId' => 2, 'Type' => 3, 'Title' => 4, 'Content' => 5, 'Priority' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, 'Resolved' => 9, 'Closed' => 10, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'userId' => 1, 'siteId' => 2, 'type' => 3, 'title' => 4, 'content' => 5, 'priority' => 6, 'createdAt' => 7, 'updatedAt' => 8, 'resolved' => 9, 'closed' => 10, ],
        self::TYPE_COLNAME       => [TicketTableMap::COL_TICKET_ID => 0, TicketTableMap::COL_USER_ID => 1, TicketTableMap::COL_SITE_ID => 2, TicketTableMap::COL_TICKET_TYPE => 3, TicketTableMap::COL_TICKET_TITLE => 4, TicketTableMap::COL_TICKET_CONTENT => 5, TicketTableMap::COL_TICKET_PRIORITY => 6, TicketTableMap::COL_TICKET_CREATED => 7, TicketTableMap::COL_TICKET_UPDATED => 8, TicketTableMap::COL_TICKET_RESOLVED => 9, TicketTableMap::COL_TICKET_CLOSED => 10, ],
        self::TYPE_FIELDNAME     => ['ticket_id' => 0, 'user_id' => 1, 'site_id' => 2, 'ticket_type' => 3, 'ticket_title' => 4, 'ticket_content' => 5, 'ticket_priority' => 6, 'ticket_created' => 7, 'ticket_updated' => 8, 'ticket_resolved' => 9, 'ticket_closed' => 10, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'TICKET_ID',
        'Ticket.Id' => 'TICKET_ID',
        'id' => 'TICKET_ID',
        'ticket.id' => 'TICKET_ID',
        'TicketTableMap::COL_TICKET_ID' => 'TICKET_ID',
        'COL_TICKET_ID' => 'TICKET_ID',
        'ticket_id' => 'TICKET_ID',
        'ticket.ticket_id' => 'TICKET_ID',
        'UserId' => 'USER_ID',
        'Ticket.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'ticket.userId' => 'USER_ID',
        'TicketTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'ticket.user_id' => 'USER_ID',
        'SiteId' => 'SITE_ID',
        'Ticket.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'ticket.siteId' => 'SITE_ID',
        'TicketTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'ticket.site_id' => 'SITE_ID',
        'Type' => 'TICKET_TYPE',
        'Ticket.Type' => 'TICKET_TYPE',
        'type' => 'TICKET_TYPE',
        'ticket.type' => 'TICKET_TYPE',
        'TicketTableMap::COL_TICKET_TYPE' => 'TICKET_TYPE',
        'COL_TICKET_TYPE' => 'TICKET_TYPE',
        'ticket_type' => 'TICKET_TYPE',
        'ticket.ticket_type' => 'TICKET_TYPE',
        'Title' => 'TICKET_TITLE',
        'Ticket.Title' => 'TICKET_TITLE',
        'title' => 'TICKET_TITLE',
        'ticket.title' => 'TICKET_TITLE',
        'TicketTableMap::COL_TICKET_TITLE' => 'TICKET_TITLE',
        'COL_TICKET_TITLE' => 'TICKET_TITLE',
        'ticket_title' => 'TICKET_TITLE',
        'ticket.ticket_title' => 'TICKET_TITLE',
        'Content' => 'TICKET_CONTENT',
        'Ticket.Content' => 'TICKET_CONTENT',
        'content' => 'TICKET_CONTENT',
        'ticket.content' => 'TICKET_CONTENT',
        'TicketTableMap::COL_TICKET_CONTENT' => 'TICKET_CONTENT',
        'COL_TICKET_CONTENT' => 'TICKET_CONTENT',
        'ticket_content' => 'TICKET_CONTENT',
        'ticket.ticket_content' => 'TICKET_CONTENT',
        'Priority' => 'TICKET_PRIORITY',
        'Ticket.Priority' => 'TICKET_PRIORITY',
        'priority' => 'TICKET_PRIORITY',
        'ticket.priority' => 'TICKET_PRIORITY',
        'TicketTableMap::COL_TICKET_PRIORITY' => 'TICKET_PRIORITY',
        'COL_TICKET_PRIORITY' => 'TICKET_PRIORITY',
        'ticket_priority' => 'TICKET_PRIORITY',
        'ticket.ticket_priority' => 'TICKET_PRIORITY',
        'CreatedAt' => 'TICKET_CREATED',
        'Ticket.CreatedAt' => 'TICKET_CREATED',
        'createdAt' => 'TICKET_CREATED',
        'ticket.createdAt' => 'TICKET_CREATED',
        'TicketTableMap::COL_TICKET_CREATED' => 'TICKET_CREATED',
        'COL_TICKET_CREATED' => 'TICKET_CREATED',
        'ticket_created' => 'TICKET_CREATED',
        'ticket.ticket_created' => 'TICKET_CREATED',
        'UpdatedAt' => 'TICKET_UPDATED',
        'Ticket.UpdatedAt' => 'TICKET_UPDATED',
        'updatedAt' => 'TICKET_UPDATED',
        'ticket.updatedAt' => 'TICKET_UPDATED',
        'TicketTableMap::COL_TICKET_UPDATED' => 'TICKET_UPDATED',
        'COL_TICKET_UPDATED' => 'TICKET_UPDATED',
        'ticket_updated' => 'TICKET_UPDATED',
        'ticket.ticket_updated' => 'TICKET_UPDATED',
        'Resolved' => 'TICKET_RESOLVED',
        'Ticket.Resolved' => 'TICKET_RESOLVED',
        'resolved' => 'TICKET_RESOLVED',
        'ticket.resolved' => 'TICKET_RESOLVED',
        'TicketTableMap::COL_TICKET_RESOLVED' => 'TICKET_RESOLVED',
        'COL_TICKET_RESOLVED' => 'TICKET_RESOLVED',
        'ticket_resolved' => 'TICKET_RESOLVED',
        'ticket.ticket_resolved' => 'TICKET_RESOLVED',
        'Closed' => 'TICKET_CLOSED',
        'Ticket.Closed' => 'TICKET_CLOSED',
        'closed' => 'TICKET_CLOSED',
        'ticket.closed' => 'TICKET_CLOSED',
        'TicketTableMap::COL_TICKET_CLOSED' => 'TICKET_CLOSED',
        'COL_TICKET_CLOSED' => 'TICKET_CLOSED',
        'ticket_closed' => 'TICKET_CLOSED',
        'ticket.ticket_closed' => 'TICKET_CLOSED',
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
        $this->setName('ticket');
        $this->setPhpName('Ticket');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Ticket');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ticket_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('user_id', 'UserId', 'INTEGER', false, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('ticket_type', 'Type', 'VARCHAR', false, 16, '');
        $this->addColumn('ticket_title', 'Title', 'VARCHAR', false, 255, null);
        $this->addColumn('ticket_content', 'Content', 'LONGVARCHAR', false, null, null);
        $this->addColumn('ticket_priority', 'Priority', 'INTEGER', false, null, 0);
        $this->addColumn('ticket_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('ticket_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('ticket_resolved', 'Resolved', 'TIMESTAMP', false, null, null);
        $this->addColumn('ticket_closed', 'Closed', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'ticket_created', 'update_column' => 'ticket_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? TicketTableMap::CLASS_DEFAULT : TicketTableMap::OM_CLASS;
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
     * @return array (Ticket object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = TicketTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = TicketTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + TicketTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = TicketTableMap::OM_CLASS;
            /** @var Ticket $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            TicketTableMap::addInstanceToPool($obj, $key);
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
            $key = TicketTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = TicketTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Ticket $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                TicketTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(TicketTableMap::COL_TICKET_ID);
            $criteria->addSelectColumn(TicketTableMap::COL_USER_ID);
            $criteria->addSelectColumn(TicketTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(TicketTableMap::COL_TICKET_TYPE);
            $criteria->addSelectColumn(TicketTableMap::COL_TICKET_TITLE);
            $criteria->addSelectColumn(TicketTableMap::COL_TICKET_CONTENT);
            $criteria->addSelectColumn(TicketTableMap::COL_TICKET_PRIORITY);
            $criteria->addSelectColumn(TicketTableMap::COL_TICKET_CREATED);
            $criteria->addSelectColumn(TicketTableMap::COL_TICKET_UPDATED);
            $criteria->addSelectColumn(TicketTableMap::COL_TICKET_RESOLVED);
            $criteria->addSelectColumn(TicketTableMap::COL_TICKET_CLOSED);
        } else {
            $criteria->addSelectColumn($alias . '.ticket_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.ticket_type');
            $criteria->addSelectColumn($alias . '.ticket_title');
            $criteria->addSelectColumn($alias . '.ticket_content');
            $criteria->addSelectColumn($alias . '.ticket_priority');
            $criteria->addSelectColumn($alias . '.ticket_created');
            $criteria->addSelectColumn($alias . '.ticket_updated');
            $criteria->addSelectColumn($alias . '.ticket_resolved');
            $criteria->addSelectColumn($alias . '.ticket_closed');
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
            $criteria->removeSelectColumn(TicketTableMap::COL_TICKET_ID);
            $criteria->removeSelectColumn(TicketTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(TicketTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(TicketTableMap::COL_TICKET_TYPE);
            $criteria->removeSelectColumn(TicketTableMap::COL_TICKET_TITLE);
            $criteria->removeSelectColumn(TicketTableMap::COL_TICKET_CONTENT);
            $criteria->removeSelectColumn(TicketTableMap::COL_TICKET_PRIORITY);
            $criteria->removeSelectColumn(TicketTableMap::COL_TICKET_CREATED);
            $criteria->removeSelectColumn(TicketTableMap::COL_TICKET_UPDATED);
            $criteria->removeSelectColumn(TicketTableMap::COL_TICKET_RESOLVED);
            $criteria->removeSelectColumn(TicketTableMap::COL_TICKET_CLOSED);
        } else {
            $criteria->removeSelectColumn($alias . '.ticket_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.ticket_type');
            $criteria->removeSelectColumn($alias . '.ticket_title');
            $criteria->removeSelectColumn($alias . '.ticket_content');
            $criteria->removeSelectColumn($alias . '.ticket_priority');
            $criteria->removeSelectColumn($alias . '.ticket_created');
            $criteria->removeSelectColumn($alias . '.ticket_updated');
            $criteria->removeSelectColumn($alias . '.ticket_resolved');
            $criteria->removeSelectColumn($alias . '.ticket_closed');
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
        return Propel::getServiceContainer()->getDatabaseMap(TicketTableMap::DATABASE_NAME)->getTable(TicketTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Ticket or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Ticket object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(TicketTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Ticket) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(TicketTableMap::DATABASE_NAME);
            $criteria->add(TicketTableMap::COL_TICKET_ID, (array) $values, Criteria::IN);
        }

        $query = TicketQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            TicketTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                TicketTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the ticket table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return TicketQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Ticket or Criteria object.
     *
     * @param mixed $criteria Criteria or Ticket object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TicketTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Ticket object
        }

        if ($criteria->containsKey(TicketTableMap::COL_TICKET_ID) && $criteria->keyContainsValue(TicketTableMap::COL_TICKET_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.TicketTableMap::COL_TICKET_ID.')');
        }


        // Set the correct dbName
        $query = TicketQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
