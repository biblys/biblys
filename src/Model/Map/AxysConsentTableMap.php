<?php

namespace Model\Map;

use Model\AxysConsent;
use Model\AxysConsentQuery;
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
 * This class defines the structure of the 'axys_consents' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class AxysConsentTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.AxysConsentTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'axys_consents';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'AxysConsent';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\AxysConsent';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.AxysConsent';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'axys_consents.id';

    /**
     * the column name for the app_id field
     */
    public const COL_APP_ID = 'axys_consents.app_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'axys_consents.user_id';

    /**
     * the column name for the scopes field
     */
    public const COL_SCOPES = 'axys_consents.scopes';

    /**
     * the column name for the created_at field
     */
    public const COL_CREATED_AT = 'axys_consents.created_at';

    /**
     * the column name for the updated_at field
     */
    public const COL_UPDATED_AT = 'axys_consents.updated_at';

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
        self::TYPE_PHPNAME       => ['Id', 'AppId', 'UserId', 'Scopes', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'appId', 'userId', 'scopes', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [AxysConsentTableMap::COL_ID, AxysConsentTableMap::COL_APP_ID, AxysConsentTableMap::COL_USER_ID, AxysConsentTableMap::COL_SCOPES, AxysConsentTableMap::COL_CREATED_AT, AxysConsentTableMap::COL_UPDATED_AT, ],
        self::TYPE_FIELDNAME     => ['id', 'app_id', 'user_id', 'scopes', 'created_at', 'updated_at', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'AppId' => 1, 'UserId' => 2, 'Scopes' => 3, 'CreatedAt' => 4, 'UpdatedAt' => 5, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'appId' => 1, 'userId' => 2, 'scopes' => 3, 'createdAt' => 4, 'updatedAt' => 5, ],
        self::TYPE_COLNAME       => [AxysConsentTableMap::COL_ID => 0, AxysConsentTableMap::COL_APP_ID => 1, AxysConsentTableMap::COL_USER_ID => 2, AxysConsentTableMap::COL_SCOPES => 3, AxysConsentTableMap::COL_CREATED_AT => 4, AxysConsentTableMap::COL_UPDATED_AT => 5, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'app_id' => 1, 'user_id' => 2, 'scopes' => 3, 'created_at' => 4, 'updated_at' => 5, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'AxysConsent.Id' => 'ID',
        'id' => 'ID',
        'axysConsent.id' => 'ID',
        'AxysConsentTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'axys_consents.id' => 'ID',
        'AppId' => 'APP_ID',
        'AxysConsent.AppId' => 'APP_ID',
        'appId' => 'APP_ID',
        'axysConsent.appId' => 'APP_ID',
        'AxysConsentTableMap::COL_APP_ID' => 'APP_ID',
        'COL_APP_ID' => 'APP_ID',
        'app_id' => 'APP_ID',
        'axys_consents.app_id' => 'APP_ID',
        'UserId' => 'USER_ID',
        'AxysConsent.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'axysConsent.userId' => 'USER_ID',
        'AxysConsentTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'axys_consents.user_id' => 'USER_ID',
        'Scopes' => 'SCOPES',
        'AxysConsent.Scopes' => 'SCOPES',
        'scopes' => 'SCOPES',
        'axysConsent.scopes' => 'SCOPES',
        'AxysConsentTableMap::COL_SCOPES' => 'SCOPES',
        'COL_SCOPES' => 'SCOPES',
        'axys_consents.scopes' => 'SCOPES',
        'CreatedAt' => 'CREATED_AT',
        'AxysConsent.CreatedAt' => 'CREATED_AT',
        'createdAt' => 'CREATED_AT',
        'axysConsent.createdAt' => 'CREATED_AT',
        'AxysConsentTableMap::COL_CREATED_AT' => 'CREATED_AT',
        'COL_CREATED_AT' => 'CREATED_AT',
        'created_at' => 'CREATED_AT',
        'axys_consents.created_at' => 'CREATED_AT',
        'UpdatedAt' => 'UPDATED_AT',
        'AxysConsent.UpdatedAt' => 'UPDATED_AT',
        'updatedAt' => 'UPDATED_AT',
        'axysConsent.updatedAt' => 'UPDATED_AT',
        'AxysConsentTableMap::COL_UPDATED_AT' => 'UPDATED_AT',
        'COL_UPDATED_AT' => 'UPDATED_AT',
        'updated_at' => 'UPDATED_AT',
        'axys_consents.updated_at' => 'UPDATED_AT',
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
        $this->setName('axys_consents');
        $this->setPhpName('AxysConsent');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\AxysConsent');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('app_id', 'AppId', 'INTEGER', 'axys_apps', 'id', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'axys_accounts', 'id', true, null, null);
        $this->addColumn('scopes', 'Scopes', 'VARCHAR', true, 256, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('AxysApp', '\\Model\\AxysApp', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':app_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('AxysUser', '\\Model\\AxysUser', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
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
            'timestampable' => ['create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? AxysConsentTableMap::CLASS_DEFAULT : AxysConsentTableMap::OM_CLASS;
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
     * @return array (AxysConsent object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = AxysConsentTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AxysConsentTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AxysConsentTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AxysConsentTableMap::OM_CLASS;
            /** @var AxysConsent $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AxysConsentTableMap::addInstanceToPool($obj, $key);
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
            $key = AxysConsentTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AxysConsentTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AxysConsent $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AxysConsentTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AxysConsentTableMap::COL_ID);
            $criteria->addSelectColumn(AxysConsentTableMap::COL_APP_ID);
            $criteria->addSelectColumn(AxysConsentTableMap::COL_USER_ID);
            $criteria->addSelectColumn(AxysConsentTableMap::COL_SCOPES);
            $criteria->addSelectColumn(AxysConsentTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(AxysConsentTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.app_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.scopes');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
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
            $criteria->removeSelectColumn(AxysConsentTableMap::COL_ID);
            $criteria->removeSelectColumn(AxysConsentTableMap::COL_APP_ID);
            $criteria->removeSelectColumn(AxysConsentTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(AxysConsentTableMap::COL_SCOPES);
            $criteria->removeSelectColumn(AxysConsentTableMap::COL_CREATED_AT);
            $criteria->removeSelectColumn(AxysConsentTableMap::COL_UPDATED_AT);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.app_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.scopes');
            $criteria->removeSelectColumn($alias . '.created_at');
            $criteria->removeSelectColumn($alias . '.updated_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(AxysConsentTableMap::DATABASE_NAME)->getTable(AxysConsentTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a AxysConsent or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or AxysConsent object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AxysConsentTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\AxysConsent) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AxysConsentTableMap::DATABASE_NAME);
            $criteria->add(AxysConsentTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AxysConsentQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AxysConsentTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AxysConsentTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the axys_consents table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return AxysConsentQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AxysConsent or Criteria object.
     *
     * @param mixed $criteria Criteria or AxysConsent object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AxysConsentTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AxysConsent object
        }

        if ($criteria->containsKey(AxysConsentTableMap::COL_ID) && $criteria->keyContainsValue(AxysConsentTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AxysConsentTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AxysConsentQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
