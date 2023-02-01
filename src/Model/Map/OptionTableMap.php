<?php

namespace Model\Map;

use Model\Option;
use Model\OptionQuery;
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
 * This class defines the structure of the 'options' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class OptionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.OptionTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'options';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Option';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Option';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Option';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the option_id field
     */
    public const COL_OPTION_ID = 'options.option_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'options.site_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'options.user_id';

    /**
     * the column name for the option_key field
     */
    public const COL_OPTION_KEY = 'options.option_key';

    /**
     * the column name for the option_value field
     */
    public const COL_OPTION_VALUE = 'options.option_value';

    /**
     * the column name for the option_created field
     */
    public const COL_OPTION_CREATED = 'options.option_created';

    /**
     * the column name for the option_updated field
     */
    public const COL_OPTION_UPDATED = 'options.option_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'UserId', 'Key', 'Value', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'userId', 'key', 'value', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [OptionTableMap::COL_OPTION_ID, OptionTableMap::COL_SITE_ID, OptionTableMap::COL_USER_ID, OptionTableMap::COL_OPTION_KEY, OptionTableMap::COL_OPTION_VALUE, OptionTableMap::COL_OPTION_CREATED, OptionTableMap::COL_OPTION_UPDATED, ],
        self::TYPE_FIELDNAME     => ['option_id', 'site_id', 'user_id', 'option_key', 'option_value', 'option_created', 'option_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'UserId' => 2, 'Key' => 3, 'Value' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'userId' => 2, 'key' => 3, 'value' => 4, 'createdAt' => 5, 'updatedAt' => 6, ],
        self::TYPE_COLNAME       => [OptionTableMap::COL_OPTION_ID => 0, OptionTableMap::COL_SITE_ID => 1, OptionTableMap::COL_USER_ID => 2, OptionTableMap::COL_OPTION_KEY => 3, OptionTableMap::COL_OPTION_VALUE => 4, OptionTableMap::COL_OPTION_CREATED => 5, OptionTableMap::COL_OPTION_UPDATED => 6, ],
        self::TYPE_FIELDNAME     => ['option_id' => 0, 'site_id' => 1, 'user_id' => 2, 'option_key' => 3, 'option_value' => 4, 'option_created' => 5, 'option_updated' => 6, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'OPTION_ID',
        'Option.Id' => 'OPTION_ID',
        'id' => 'OPTION_ID',
        'option.id' => 'OPTION_ID',
        'OptionTableMap::COL_OPTION_ID' => 'OPTION_ID',
        'COL_OPTION_ID' => 'OPTION_ID',
        'option_id' => 'OPTION_ID',
        'options.option_id' => 'OPTION_ID',
        'SiteId' => 'SITE_ID',
        'Option.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'option.siteId' => 'SITE_ID',
        'OptionTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'options.site_id' => 'SITE_ID',
        'UserId' => 'USER_ID',
        'Option.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'option.userId' => 'USER_ID',
        'OptionTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'options.user_id' => 'USER_ID',
        'Key' => 'OPTION_KEY',
        'Option.Key' => 'OPTION_KEY',
        'key' => 'OPTION_KEY',
        'option.key' => 'OPTION_KEY',
        'OptionTableMap::COL_OPTION_KEY' => 'OPTION_KEY',
        'COL_OPTION_KEY' => 'OPTION_KEY',
        'option_key' => 'OPTION_KEY',
        'options.option_key' => 'OPTION_KEY',
        'Value' => 'OPTION_VALUE',
        'Option.Value' => 'OPTION_VALUE',
        'value' => 'OPTION_VALUE',
        'option.value' => 'OPTION_VALUE',
        'OptionTableMap::COL_OPTION_VALUE' => 'OPTION_VALUE',
        'COL_OPTION_VALUE' => 'OPTION_VALUE',
        'option_value' => 'OPTION_VALUE',
        'options.option_value' => 'OPTION_VALUE',
        'CreatedAt' => 'OPTION_CREATED',
        'Option.CreatedAt' => 'OPTION_CREATED',
        'createdAt' => 'OPTION_CREATED',
        'option.createdAt' => 'OPTION_CREATED',
        'OptionTableMap::COL_OPTION_CREATED' => 'OPTION_CREATED',
        'COL_OPTION_CREATED' => 'OPTION_CREATED',
        'option_created' => 'OPTION_CREATED',
        'options.option_created' => 'OPTION_CREATED',
        'UpdatedAt' => 'OPTION_UPDATED',
        'Option.UpdatedAt' => 'OPTION_UPDATED',
        'updatedAt' => 'OPTION_UPDATED',
        'option.updatedAt' => 'OPTION_UPDATED',
        'OptionTableMap::COL_OPTION_UPDATED' => 'OPTION_UPDATED',
        'COL_OPTION_UPDATED' => 'OPTION_UPDATED',
        'option_updated' => 'OPTION_UPDATED',
        'options.option_updated' => 'OPTION_UPDATED',
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
        $this->setName('options');
        $this->setPhpName('Option');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Option');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('option_id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, null, null);
        $this->addColumn('option_key', 'Key', 'VARCHAR', false, 32, null);
        $this->addColumn('option_value', 'Value', 'VARCHAR', false, 2048, null);
        $this->addColumn('option_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('option_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('User', '\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Site', '\\Model\\Site', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
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
            'timestampable' => ['create_column' => 'option_created', 'update_column' => 'option_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? OptionTableMap::CLASS_DEFAULT : OptionTableMap::OM_CLASS;
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
     * @return array (Option object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = OptionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OptionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OptionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OptionTableMap::OM_CLASS;
            /** @var Option $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OptionTableMap::addInstanceToPool($obj, $key);
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
            $key = OptionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OptionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Option $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OptionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(OptionTableMap::COL_OPTION_ID);
            $criteria->addSelectColumn(OptionTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(OptionTableMap::COL_USER_ID);
            $criteria->addSelectColumn(OptionTableMap::COL_OPTION_KEY);
            $criteria->addSelectColumn(OptionTableMap::COL_OPTION_VALUE);
            $criteria->addSelectColumn(OptionTableMap::COL_OPTION_CREATED);
            $criteria->addSelectColumn(OptionTableMap::COL_OPTION_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.option_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.option_key');
            $criteria->addSelectColumn($alias . '.option_value');
            $criteria->addSelectColumn($alias . '.option_created');
            $criteria->addSelectColumn($alias . '.option_updated');
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
            $criteria->removeSelectColumn(OptionTableMap::COL_OPTION_ID);
            $criteria->removeSelectColumn(OptionTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(OptionTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(OptionTableMap::COL_OPTION_KEY);
            $criteria->removeSelectColumn(OptionTableMap::COL_OPTION_VALUE);
            $criteria->removeSelectColumn(OptionTableMap::COL_OPTION_CREATED);
            $criteria->removeSelectColumn(OptionTableMap::COL_OPTION_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.option_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.option_key');
            $criteria->removeSelectColumn($alias . '.option_value');
            $criteria->removeSelectColumn($alias . '.option_created');
            $criteria->removeSelectColumn($alias . '.option_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(OptionTableMap::DATABASE_NAME)->getTable(OptionTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Option or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Option object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(OptionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Option) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OptionTableMap::DATABASE_NAME);
            $criteria->add(OptionTableMap::COL_OPTION_ID, (array) $values, Criteria::IN);
        }

        $query = OptionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            OptionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                OptionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the options table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return OptionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Option or Criteria object.
     *
     * @param mixed $criteria Criteria or Option object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OptionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Option object
        }

        if ($criteria->containsKey(OptionTableMap::COL_OPTION_ID) && $criteria->keyContainsValue(OptionTableMap::COL_OPTION_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.OptionTableMap::COL_OPTION_ID.')');
        }


        // Set the correct dbName
        $query = OptionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
