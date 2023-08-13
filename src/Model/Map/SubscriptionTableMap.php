<?php

namespace Model\Map;

use Model\Subscription;
use Model\SubscriptionQuery;
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
 * This class defines the structure of the 'subscriptions' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class SubscriptionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.SubscriptionTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'subscriptions';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Subscription';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Subscription';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Subscription';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 14;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 14;

    /**
     * the column name for the subscription_id field
     */
    public const COL_SUBSCRIPTION_ID = 'subscriptions.subscription_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'subscriptions.site_id';

    /**
     * the column name for the axys_user_id field
     */
    public const COL_AXYS_USER_ID = 'subscriptions.axys_user_id';

    /**
     * the column name for the publisher_id field
     */
    public const COL_PUBLISHER_ID = 'subscriptions.publisher_id';

    /**
     * the column name for the bookshop_id field
     */
    public const COL_BOOKSHOP_ID = 'subscriptions.bookshop_id';

    /**
     * the column name for the library_id field
     */
    public const COL_LIBRARY_ID = 'subscriptions.library_id';

    /**
     * the column name for the subscription_type field
     */
    public const COL_SUBSCRIPTION_TYPE = 'subscriptions.subscription_type';

    /**
     * the column name for the subscription_email field
     */
    public const COL_SUBSCRIPTION_EMAIL = 'subscriptions.subscription_email';

    /**
     * the column name for the subscription_ends field
     */
    public const COL_SUBSCRIPTION_ENDS = 'subscriptions.subscription_ends';

    /**
     * the column name for the subscription_option field
     */
    public const COL_SUBSCRIPTION_OPTION = 'subscriptions.subscription_option';

    /**
     * the column name for the subscription_insert field
     */
    public const COL_SUBSCRIPTION_INSERT = 'subscriptions.subscription_insert';

    /**
     * the column name for the subscription_update field
     */
    public const COL_SUBSCRIPTION_UPDATE = 'subscriptions.subscription_update';

    /**
     * the column name for the subscription_created field
     */
    public const COL_SUBSCRIPTION_CREATED = 'subscriptions.subscription_created';

    /**
     * the column name for the subscription_updated field
     */
    public const COL_SUBSCRIPTION_UPDATED = 'subscriptions.subscription_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'AxysUserId', 'PublisherId', 'BookshopId', 'LibraryId', 'Type', 'Email', 'Ends', 'Option', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'axysUserId', 'publisherId', 'bookshopId', 'libraryId', 'type', 'email', 'ends', 'option', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [SubscriptionTableMap::COL_SUBSCRIPTION_ID, SubscriptionTableMap::COL_SITE_ID, SubscriptionTableMap::COL_AXYS_USER_ID, SubscriptionTableMap::COL_PUBLISHER_ID, SubscriptionTableMap::COL_BOOKSHOP_ID, SubscriptionTableMap::COL_LIBRARY_ID, SubscriptionTableMap::COL_SUBSCRIPTION_TYPE, SubscriptionTableMap::COL_SUBSCRIPTION_EMAIL, SubscriptionTableMap::COL_SUBSCRIPTION_ENDS, SubscriptionTableMap::COL_SUBSCRIPTION_OPTION, SubscriptionTableMap::COL_SUBSCRIPTION_INSERT, SubscriptionTableMap::COL_SUBSCRIPTION_UPDATE, SubscriptionTableMap::COL_SUBSCRIPTION_CREATED, SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED, ],
        self::TYPE_FIELDNAME     => ['subscription_id', 'site_id', 'axys_user_id', 'publisher_id', 'bookshop_id', 'library_id', 'subscription_type', 'subscription_email', 'subscription_ends', 'subscription_option', 'subscription_insert', 'subscription_update', 'subscription_created', 'subscription_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'AxysUserId' => 2, 'PublisherId' => 3, 'BookshopId' => 4, 'LibraryId' => 5, 'Type' => 6, 'Email' => 7, 'Ends' => 8, 'Option' => 9, 'Insert' => 10, 'Update' => 11, 'CreatedAt' => 12, 'UpdatedAt' => 13, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'axysUserId' => 2, 'publisherId' => 3, 'bookshopId' => 4, 'libraryId' => 5, 'type' => 6, 'email' => 7, 'ends' => 8, 'option' => 9, 'insert' => 10, 'update' => 11, 'createdAt' => 12, 'updatedAt' => 13, ],
        self::TYPE_COLNAME       => [SubscriptionTableMap::COL_SUBSCRIPTION_ID => 0, SubscriptionTableMap::COL_SITE_ID => 1, SubscriptionTableMap::COL_AXYS_USER_ID => 2, SubscriptionTableMap::COL_PUBLISHER_ID => 3, SubscriptionTableMap::COL_BOOKSHOP_ID => 4, SubscriptionTableMap::COL_LIBRARY_ID => 5, SubscriptionTableMap::COL_SUBSCRIPTION_TYPE => 6, SubscriptionTableMap::COL_SUBSCRIPTION_EMAIL => 7, SubscriptionTableMap::COL_SUBSCRIPTION_ENDS => 8, SubscriptionTableMap::COL_SUBSCRIPTION_OPTION => 9, SubscriptionTableMap::COL_SUBSCRIPTION_INSERT => 10, SubscriptionTableMap::COL_SUBSCRIPTION_UPDATE => 11, SubscriptionTableMap::COL_SUBSCRIPTION_CREATED => 12, SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED => 13, ],
        self::TYPE_FIELDNAME     => ['subscription_id' => 0, 'site_id' => 1, 'axys_user_id' => 2, 'publisher_id' => 3, 'bookshop_id' => 4, 'library_id' => 5, 'subscription_type' => 6, 'subscription_email' => 7, 'subscription_ends' => 8, 'subscription_option' => 9, 'subscription_insert' => 10, 'subscription_update' => 11, 'subscription_created' => 12, 'subscription_updated' => 13, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'SUBSCRIPTION_ID',
        'Subscription.Id' => 'SUBSCRIPTION_ID',
        'id' => 'SUBSCRIPTION_ID',
        'subscription.id' => 'SUBSCRIPTION_ID',
        'SubscriptionTableMap::COL_SUBSCRIPTION_ID' => 'SUBSCRIPTION_ID',
        'COL_SUBSCRIPTION_ID' => 'SUBSCRIPTION_ID',
        'subscription_id' => 'SUBSCRIPTION_ID',
        'subscriptions.subscription_id' => 'SUBSCRIPTION_ID',
        'SiteId' => 'SITE_ID',
        'Subscription.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'subscription.siteId' => 'SITE_ID',
        'SubscriptionTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'subscriptions.site_id' => 'SITE_ID',
        'AxysUserId' => 'AXYS_USER_ID',
        'Subscription.AxysUserId' => 'AXYS_USER_ID',
        'axysUserId' => 'AXYS_USER_ID',
        'subscription.axysUserId' => 'AXYS_USER_ID',
        'SubscriptionTableMap::COL_AXYS_USER_ID' => 'AXYS_USER_ID',
        'COL_AXYS_USER_ID' => 'AXYS_USER_ID',
        'axys_user_id' => 'AXYS_USER_ID',
        'subscriptions.axys_user_id' => 'AXYS_USER_ID',
        'PublisherId' => 'PUBLISHER_ID',
        'Subscription.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'subscription.publisherId' => 'PUBLISHER_ID',
        'SubscriptionTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'subscriptions.publisher_id' => 'PUBLISHER_ID',
        'BookshopId' => 'BOOKSHOP_ID',
        'Subscription.BookshopId' => 'BOOKSHOP_ID',
        'bookshopId' => 'BOOKSHOP_ID',
        'subscription.bookshopId' => 'BOOKSHOP_ID',
        'SubscriptionTableMap::COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'bookshop_id' => 'BOOKSHOP_ID',
        'subscriptions.bookshop_id' => 'BOOKSHOP_ID',
        'LibraryId' => 'LIBRARY_ID',
        'Subscription.LibraryId' => 'LIBRARY_ID',
        'libraryId' => 'LIBRARY_ID',
        'subscription.libraryId' => 'LIBRARY_ID',
        'SubscriptionTableMap::COL_LIBRARY_ID' => 'LIBRARY_ID',
        'COL_LIBRARY_ID' => 'LIBRARY_ID',
        'library_id' => 'LIBRARY_ID',
        'subscriptions.library_id' => 'LIBRARY_ID',
        'Type' => 'SUBSCRIPTION_TYPE',
        'Subscription.Type' => 'SUBSCRIPTION_TYPE',
        'type' => 'SUBSCRIPTION_TYPE',
        'subscription.type' => 'SUBSCRIPTION_TYPE',
        'SubscriptionTableMap::COL_SUBSCRIPTION_TYPE' => 'SUBSCRIPTION_TYPE',
        'COL_SUBSCRIPTION_TYPE' => 'SUBSCRIPTION_TYPE',
        'subscription_type' => 'SUBSCRIPTION_TYPE',
        'subscriptions.subscription_type' => 'SUBSCRIPTION_TYPE',
        'Email' => 'SUBSCRIPTION_EMAIL',
        'Subscription.Email' => 'SUBSCRIPTION_EMAIL',
        'email' => 'SUBSCRIPTION_EMAIL',
        'subscription.email' => 'SUBSCRIPTION_EMAIL',
        'SubscriptionTableMap::COL_SUBSCRIPTION_EMAIL' => 'SUBSCRIPTION_EMAIL',
        'COL_SUBSCRIPTION_EMAIL' => 'SUBSCRIPTION_EMAIL',
        'subscription_email' => 'SUBSCRIPTION_EMAIL',
        'subscriptions.subscription_email' => 'SUBSCRIPTION_EMAIL',
        'Ends' => 'SUBSCRIPTION_ENDS',
        'Subscription.Ends' => 'SUBSCRIPTION_ENDS',
        'ends' => 'SUBSCRIPTION_ENDS',
        'subscription.ends' => 'SUBSCRIPTION_ENDS',
        'SubscriptionTableMap::COL_SUBSCRIPTION_ENDS' => 'SUBSCRIPTION_ENDS',
        'COL_SUBSCRIPTION_ENDS' => 'SUBSCRIPTION_ENDS',
        'subscription_ends' => 'SUBSCRIPTION_ENDS',
        'subscriptions.subscription_ends' => 'SUBSCRIPTION_ENDS',
        'Option' => 'SUBSCRIPTION_OPTION',
        'Subscription.Option' => 'SUBSCRIPTION_OPTION',
        'option' => 'SUBSCRIPTION_OPTION',
        'subscription.option' => 'SUBSCRIPTION_OPTION',
        'SubscriptionTableMap::COL_SUBSCRIPTION_OPTION' => 'SUBSCRIPTION_OPTION',
        'COL_SUBSCRIPTION_OPTION' => 'SUBSCRIPTION_OPTION',
        'subscription_option' => 'SUBSCRIPTION_OPTION',
        'subscriptions.subscription_option' => 'SUBSCRIPTION_OPTION',
        'Insert' => 'SUBSCRIPTION_INSERT',
        'Subscription.Insert' => 'SUBSCRIPTION_INSERT',
        'insert' => 'SUBSCRIPTION_INSERT',
        'subscription.insert' => 'SUBSCRIPTION_INSERT',
        'SubscriptionTableMap::COL_SUBSCRIPTION_INSERT' => 'SUBSCRIPTION_INSERT',
        'COL_SUBSCRIPTION_INSERT' => 'SUBSCRIPTION_INSERT',
        'subscription_insert' => 'SUBSCRIPTION_INSERT',
        'subscriptions.subscription_insert' => 'SUBSCRIPTION_INSERT',
        'Update' => 'SUBSCRIPTION_UPDATE',
        'Subscription.Update' => 'SUBSCRIPTION_UPDATE',
        'update' => 'SUBSCRIPTION_UPDATE',
        'subscription.update' => 'SUBSCRIPTION_UPDATE',
        'SubscriptionTableMap::COL_SUBSCRIPTION_UPDATE' => 'SUBSCRIPTION_UPDATE',
        'COL_SUBSCRIPTION_UPDATE' => 'SUBSCRIPTION_UPDATE',
        'subscription_update' => 'SUBSCRIPTION_UPDATE',
        'subscriptions.subscription_update' => 'SUBSCRIPTION_UPDATE',
        'CreatedAt' => 'SUBSCRIPTION_CREATED',
        'Subscription.CreatedAt' => 'SUBSCRIPTION_CREATED',
        'createdAt' => 'SUBSCRIPTION_CREATED',
        'subscription.createdAt' => 'SUBSCRIPTION_CREATED',
        'SubscriptionTableMap::COL_SUBSCRIPTION_CREATED' => 'SUBSCRIPTION_CREATED',
        'COL_SUBSCRIPTION_CREATED' => 'SUBSCRIPTION_CREATED',
        'subscription_created' => 'SUBSCRIPTION_CREATED',
        'subscriptions.subscription_created' => 'SUBSCRIPTION_CREATED',
        'UpdatedAt' => 'SUBSCRIPTION_UPDATED',
        'Subscription.UpdatedAt' => 'SUBSCRIPTION_UPDATED',
        'updatedAt' => 'SUBSCRIPTION_UPDATED',
        'subscription.updatedAt' => 'SUBSCRIPTION_UPDATED',
        'SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED' => 'SUBSCRIPTION_UPDATED',
        'COL_SUBSCRIPTION_UPDATED' => 'SUBSCRIPTION_UPDATED',
        'subscription_updated' => 'SUBSCRIPTION_UPDATED',
        'subscriptions.subscription_updated' => 'SUBSCRIPTION_UPDATED',
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
        $this->setName('subscriptions');
        $this->setPhpName('Subscription');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Subscription');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('subscription_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('axys_user_id', 'AxysUserId', 'INTEGER', false, 10, null);
        $this->addColumn('publisher_id', 'PublisherId', 'INTEGER', false, 10, null);
        $this->addColumn('bookshop_id', 'BookshopId', 'INTEGER', false, 10, null);
        $this->addColumn('library_id', 'LibraryId', 'INTEGER', false, 10, null);
        $this->addColumn('subscription_type', 'Type', 'VARCHAR', false, 16, null);
        $this->addColumn('subscription_email', 'Email', 'VARCHAR', false, 256, null);
        $this->addColumn('subscription_ends', 'Ends', 'SMALLINT', false, 16, null);
        $this->addColumn('subscription_option', 'Option', 'BOOLEAN', false, 1, false);
        $this->addColumn('subscription_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('subscription_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('subscription_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('subscription_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'subscription_created', 'update_column' => 'subscription_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? SubscriptionTableMap::CLASS_DEFAULT : SubscriptionTableMap::OM_CLASS;
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
     * @return array (Subscription object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = SubscriptionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SubscriptionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SubscriptionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SubscriptionTableMap::OM_CLASS;
            /** @var Subscription $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SubscriptionTableMap::addInstanceToPool($obj, $key);
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
            $key = SubscriptionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SubscriptionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Subscription $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SubscriptionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_ID);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_AXYS_USER_ID);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_BOOKSHOP_ID);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_LIBRARY_ID);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_TYPE);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_EMAIL);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_ENDS);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_OPTION);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_INSERT);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATE);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_CREATED);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.subscription_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.axys_user_id');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.bookshop_id');
            $criteria->addSelectColumn($alias . '.library_id');
            $criteria->addSelectColumn($alias . '.subscription_type');
            $criteria->addSelectColumn($alias . '.subscription_email');
            $criteria->addSelectColumn($alias . '.subscription_ends');
            $criteria->addSelectColumn($alias . '.subscription_option');
            $criteria->addSelectColumn($alias . '.subscription_insert');
            $criteria->addSelectColumn($alias . '.subscription_update');
            $criteria->addSelectColumn($alias . '.subscription_created');
            $criteria->addSelectColumn($alias . '.subscription_updated');
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
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_ID);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_AXYS_USER_ID);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_BOOKSHOP_ID);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_LIBRARY_ID);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_TYPE);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_EMAIL);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_ENDS);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_OPTION);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_INSERT);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATE);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_CREATED);
            $criteria->removeSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.subscription_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.axys_user_id');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.bookshop_id');
            $criteria->removeSelectColumn($alias . '.library_id');
            $criteria->removeSelectColumn($alias . '.subscription_type');
            $criteria->removeSelectColumn($alias . '.subscription_email');
            $criteria->removeSelectColumn($alias . '.subscription_ends');
            $criteria->removeSelectColumn($alias . '.subscription_option');
            $criteria->removeSelectColumn($alias . '.subscription_insert');
            $criteria->removeSelectColumn($alias . '.subscription_update');
            $criteria->removeSelectColumn($alias . '.subscription_created');
            $criteria->removeSelectColumn($alias . '.subscription_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(SubscriptionTableMap::DATABASE_NAME)->getTable(SubscriptionTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Subscription or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Subscription object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SubscriptionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Subscription) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SubscriptionTableMap::DATABASE_NAME);
            $criteria->add(SubscriptionTableMap::COL_SUBSCRIPTION_ID, (array) $values, Criteria::IN);
        }

        $query = SubscriptionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SubscriptionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SubscriptionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the subscriptions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return SubscriptionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Subscription or Criteria object.
     *
     * @param mixed $criteria Criteria or Subscription object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubscriptionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Subscription object
        }

        if ($criteria->containsKey(SubscriptionTableMap::COL_SUBSCRIPTION_ID) && $criteria->keyContainsValue(SubscriptionTableMap::COL_SUBSCRIPTION_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SubscriptionTableMap::COL_SUBSCRIPTION_ID.')');
        }


        // Set the correct dbName
        $query = SubscriptionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
