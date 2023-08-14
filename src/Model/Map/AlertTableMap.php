<?php

namespace Model\Map;

use Model\Alert;
use Model\AlertQuery;
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
 * This class defines the structure of the 'alerts' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class AlertTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.AlertTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'alerts';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Alert';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Alert';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Alert';

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
     * the column name for the alert_id field
     */
    public const COL_ALERT_ID = 'alerts.alert_id';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'alerts.axys_account_id';

    /**
     * the column name for the article_id field
     */
    public const COL_ARTICLE_ID = 'alerts.article_id';

    /**
     * the column name for the alert_max_price field
     */
    public const COL_ALERT_MAX_PRICE = 'alerts.alert_max_price';

    /**
     * the column name for the alert_pub_year field
     */
    public const COL_ALERT_PUB_YEAR = 'alerts.alert_pub_year';

    /**
     * the column name for the alert_condition field
     */
    public const COL_ALERT_CONDITION = 'alerts.alert_condition';

    /**
     * the column name for the alert_insert field
     */
    public const COL_ALERT_INSERT = 'alerts.alert_insert';

    /**
     * the column name for the alert_update field
     */
    public const COL_ALERT_UPDATE = 'alerts.alert_update';

    /**
     * the column name for the alert_created field
     */
    public const COL_ALERT_CREATED = 'alerts.alert_created';

    /**
     * the column name for the alert_updated field
     */
    public const COL_ALERT_UPDATED = 'alerts.alert_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'AxysAccountId', 'ArticleId', 'MaxPrice', 'PubYear', 'Condition', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'axysAccountId', 'articleId', 'maxPrice', 'pubYear', 'condition', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [AlertTableMap::COL_ALERT_ID, AlertTableMap::COL_AXYS_ACCOUNT_ID, AlertTableMap::COL_ARTICLE_ID, AlertTableMap::COL_ALERT_MAX_PRICE, AlertTableMap::COL_ALERT_PUB_YEAR, AlertTableMap::COL_ALERT_CONDITION, AlertTableMap::COL_ALERT_INSERT, AlertTableMap::COL_ALERT_UPDATE, AlertTableMap::COL_ALERT_CREATED, AlertTableMap::COL_ALERT_UPDATED, ],
        self::TYPE_FIELDNAME     => ['alert_id', 'axys_account_id', 'article_id', 'alert_max_price', 'alert_pub_year', 'alert_condition', 'alert_insert', 'alert_update', 'alert_created', 'alert_updated', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'AxysAccountId' => 1, 'ArticleId' => 2, 'MaxPrice' => 3, 'PubYear' => 4, 'Condition' => 5, 'Insert' => 6, 'Update' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'axysAccountId' => 1, 'articleId' => 2, 'maxPrice' => 3, 'pubYear' => 4, 'condition' => 5, 'insert' => 6, 'update' => 7, 'createdAt' => 8, 'updatedAt' => 9, ],
        self::TYPE_COLNAME       => [AlertTableMap::COL_ALERT_ID => 0, AlertTableMap::COL_AXYS_ACCOUNT_ID => 1, AlertTableMap::COL_ARTICLE_ID => 2, AlertTableMap::COL_ALERT_MAX_PRICE => 3, AlertTableMap::COL_ALERT_PUB_YEAR => 4, AlertTableMap::COL_ALERT_CONDITION => 5, AlertTableMap::COL_ALERT_INSERT => 6, AlertTableMap::COL_ALERT_UPDATE => 7, AlertTableMap::COL_ALERT_CREATED => 8, AlertTableMap::COL_ALERT_UPDATED => 9, ],
        self::TYPE_FIELDNAME     => ['alert_id' => 0, 'axys_account_id' => 1, 'article_id' => 2, 'alert_max_price' => 3, 'alert_pub_year' => 4, 'alert_condition' => 5, 'alert_insert' => 6, 'alert_update' => 7, 'alert_created' => 8, 'alert_updated' => 9, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ALERT_ID',
        'Alert.Id' => 'ALERT_ID',
        'id' => 'ALERT_ID',
        'alert.id' => 'ALERT_ID',
        'AlertTableMap::COL_ALERT_ID' => 'ALERT_ID',
        'COL_ALERT_ID' => 'ALERT_ID',
        'alert_id' => 'ALERT_ID',
        'alerts.alert_id' => 'ALERT_ID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'Alert.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'alert.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'AlertTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'alerts.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'ArticleId' => 'ARTICLE_ID',
        'Alert.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'alert.articleId' => 'ARTICLE_ID',
        'AlertTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'alerts.article_id' => 'ARTICLE_ID',
        'MaxPrice' => 'ALERT_MAX_PRICE',
        'Alert.MaxPrice' => 'ALERT_MAX_PRICE',
        'maxPrice' => 'ALERT_MAX_PRICE',
        'alert.maxPrice' => 'ALERT_MAX_PRICE',
        'AlertTableMap::COL_ALERT_MAX_PRICE' => 'ALERT_MAX_PRICE',
        'COL_ALERT_MAX_PRICE' => 'ALERT_MAX_PRICE',
        'alert_max_price' => 'ALERT_MAX_PRICE',
        'alerts.alert_max_price' => 'ALERT_MAX_PRICE',
        'PubYear' => 'ALERT_PUB_YEAR',
        'Alert.PubYear' => 'ALERT_PUB_YEAR',
        'pubYear' => 'ALERT_PUB_YEAR',
        'alert.pubYear' => 'ALERT_PUB_YEAR',
        'AlertTableMap::COL_ALERT_PUB_YEAR' => 'ALERT_PUB_YEAR',
        'COL_ALERT_PUB_YEAR' => 'ALERT_PUB_YEAR',
        'alert_pub_year' => 'ALERT_PUB_YEAR',
        'alerts.alert_pub_year' => 'ALERT_PUB_YEAR',
        'Condition' => 'ALERT_CONDITION',
        'Alert.Condition' => 'ALERT_CONDITION',
        'condition' => 'ALERT_CONDITION',
        'alert.condition' => 'ALERT_CONDITION',
        'AlertTableMap::COL_ALERT_CONDITION' => 'ALERT_CONDITION',
        'COL_ALERT_CONDITION' => 'ALERT_CONDITION',
        'alert_condition' => 'ALERT_CONDITION',
        'alerts.alert_condition' => 'ALERT_CONDITION',
        'Insert' => 'ALERT_INSERT',
        'Alert.Insert' => 'ALERT_INSERT',
        'insert' => 'ALERT_INSERT',
        'alert.insert' => 'ALERT_INSERT',
        'AlertTableMap::COL_ALERT_INSERT' => 'ALERT_INSERT',
        'COL_ALERT_INSERT' => 'ALERT_INSERT',
        'alert_insert' => 'ALERT_INSERT',
        'alerts.alert_insert' => 'ALERT_INSERT',
        'Update' => 'ALERT_UPDATE',
        'Alert.Update' => 'ALERT_UPDATE',
        'update' => 'ALERT_UPDATE',
        'alert.update' => 'ALERT_UPDATE',
        'AlertTableMap::COL_ALERT_UPDATE' => 'ALERT_UPDATE',
        'COL_ALERT_UPDATE' => 'ALERT_UPDATE',
        'alert_update' => 'ALERT_UPDATE',
        'alerts.alert_update' => 'ALERT_UPDATE',
        'CreatedAt' => 'ALERT_CREATED',
        'Alert.CreatedAt' => 'ALERT_CREATED',
        'createdAt' => 'ALERT_CREATED',
        'alert.createdAt' => 'ALERT_CREATED',
        'AlertTableMap::COL_ALERT_CREATED' => 'ALERT_CREATED',
        'COL_ALERT_CREATED' => 'ALERT_CREATED',
        'alert_created' => 'ALERT_CREATED',
        'alerts.alert_created' => 'ALERT_CREATED',
        'UpdatedAt' => 'ALERT_UPDATED',
        'Alert.UpdatedAt' => 'ALERT_UPDATED',
        'updatedAt' => 'ALERT_UPDATED',
        'alert.updatedAt' => 'ALERT_UPDATED',
        'AlertTableMap::COL_ALERT_UPDATED' => 'ALERT_UPDATED',
        'COL_ALERT_UPDATED' => 'ALERT_UPDATED',
        'alert_updated' => 'ALERT_UPDATED',
        'alerts.alert_updated' => 'ALERT_UPDATED',
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
        $this->setName('alerts');
        $this->setPhpName('Alert');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Alert');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('alert_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('axys_account_id', 'AxysAccountId', 'INTEGER', false, 10, null);
        $this->addColumn('article_id', 'ArticleId', 'INTEGER', false, 10, null);
        $this->addColumn('alert_max_price', 'MaxPrice', 'INTEGER', false, 10, null);
        $this->addColumn('alert_pub_year', 'PubYear', 'INTEGER', false, 4, null);
        $this->addColumn('alert_condition', 'Condition', 'VARCHAR', false, 4, null);
        $this->addColumn('alert_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('alert_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('alert_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('alert_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'alert_created', 'update_column' => 'alert_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? AlertTableMap::CLASS_DEFAULT : AlertTableMap::OM_CLASS;
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
     * @return array (Alert object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = AlertTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AlertTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AlertTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AlertTableMap::OM_CLASS;
            /** @var Alert $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AlertTableMap::addInstanceToPool($obj, $key);
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
            $key = AlertTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AlertTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Alert $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AlertTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AlertTableMap::COL_ALERT_ID);
            $criteria->addSelectColumn(AlertTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(AlertTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(AlertTableMap::COL_ALERT_MAX_PRICE);
            $criteria->addSelectColumn(AlertTableMap::COL_ALERT_PUB_YEAR);
            $criteria->addSelectColumn(AlertTableMap::COL_ALERT_CONDITION);
            $criteria->addSelectColumn(AlertTableMap::COL_ALERT_INSERT);
            $criteria->addSelectColumn(AlertTableMap::COL_ALERT_UPDATE);
            $criteria->addSelectColumn(AlertTableMap::COL_ALERT_CREATED);
            $criteria->addSelectColumn(AlertTableMap::COL_ALERT_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.alert_id');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.alert_max_price');
            $criteria->addSelectColumn($alias . '.alert_pub_year');
            $criteria->addSelectColumn($alias . '.alert_condition');
            $criteria->addSelectColumn($alias . '.alert_insert');
            $criteria->addSelectColumn($alias . '.alert_update');
            $criteria->addSelectColumn($alias . '.alert_created');
            $criteria->addSelectColumn($alias . '.alert_updated');
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
            $criteria->removeSelectColumn(AlertTableMap::COL_ALERT_ID);
            $criteria->removeSelectColumn(AlertTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(AlertTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(AlertTableMap::COL_ALERT_MAX_PRICE);
            $criteria->removeSelectColumn(AlertTableMap::COL_ALERT_PUB_YEAR);
            $criteria->removeSelectColumn(AlertTableMap::COL_ALERT_CONDITION);
            $criteria->removeSelectColumn(AlertTableMap::COL_ALERT_INSERT);
            $criteria->removeSelectColumn(AlertTableMap::COL_ALERT_UPDATE);
            $criteria->removeSelectColumn(AlertTableMap::COL_ALERT_CREATED);
            $criteria->removeSelectColumn(AlertTableMap::COL_ALERT_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.alert_id');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.alert_max_price');
            $criteria->removeSelectColumn($alias . '.alert_pub_year');
            $criteria->removeSelectColumn($alias . '.alert_condition');
            $criteria->removeSelectColumn($alias . '.alert_insert');
            $criteria->removeSelectColumn($alias . '.alert_update');
            $criteria->removeSelectColumn($alias . '.alert_created');
            $criteria->removeSelectColumn($alias . '.alert_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(AlertTableMap::DATABASE_NAME)->getTable(AlertTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Alert or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Alert object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AlertTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Alert) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AlertTableMap::DATABASE_NAME);
            $criteria->add(AlertTableMap::COL_ALERT_ID, (array) $values, Criteria::IN);
        }

        $query = AlertQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AlertTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AlertTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the alerts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return AlertQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Alert or Criteria object.
     *
     * @param mixed $criteria Criteria or Alert object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AlertTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Alert object
        }

        if ($criteria->containsKey(AlertTableMap::COL_ALERT_ID) && $criteria->keyContainsValue(AlertTableMap::COL_ALERT_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AlertTableMap::COL_ALERT_ID.')');
        }


        // Set the correct dbName
        $query = AlertQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
