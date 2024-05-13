<?php

namespace Model\Map;

use Model\Vote;
use Model\VoteQuery;
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
 * This class defines the structure of the 'votes' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class VoteTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.VoteTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'votes';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Vote';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Vote';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Vote';

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
     * the column name for the vote_id field
     */
    public const COL_VOTE_ID = 'votes.vote_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'votes.site_id';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'votes.axys_account_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'votes.user_id';

    /**
     * the column name for the vote_F field
     */
    public const COL_VOTE_F = 'votes.vote_F';

    /**
     * the column name for the vote_E field
     */
    public const COL_VOTE_E = 'votes.vote_E';

    /**
     * the column name for the vote_date field
     */
    public const COL_VOTE_DATE = 'votes.vote_date';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'AxysAccountId', 'UserId', 'F', 'E', 'Date', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'axysAccountId', 'userId', 'f', 'e', 'date', ],
        self::TYPE_COLNAME       => [VoteTableMap::COL_VOTE_ID, VoteTableMap::COL_SITE_ID, VoteTableMap::COL_AXYS_ACCOUNT_ID, VoteTableMap::COL_USER_ID, VoteTableMap::COL_VOTE_F, VoteTableMap::COL_VOTE_E, VoteTableMap::COL_VOTE_DATE, ],
        self::TYPE_FIELDNAME     => ['vote_id', 'site_id', 'axys_account_id', 'user_id', 'vote_F', 'vote_E', 'vote_date', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'AxysAccountId' => 2, 'UserId' => 3, 'F' => 4, 'E' => 5, 'Date' => 6, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'axysAccountId' => 2, 'userId' => 3, 'f' => 4, 'e' => 5, 'date' => 6, ],
        self::TYPE_COLNAME       => [VoteTableMap::COL_VOTE_ID => 0, VoteTableMap::COL_SITE_ID => 1, VoteTableMap::COL_AXYS_ACCOUNT_ID => 2, VoteTableMap::COL_USER_ID => 3, VoteTableMap::COL_VOTE_F => 4, VoteTableMap::COL_VOTE_E => 5, VoteTableMap::COL_VOTE_DATE => 6, ],
        self::TYPE_FIELDNAME     => ['vote_id' => 0, 'site_id' => 1, 'axys_account_id' => 2, 'user_id' => 3, 'vote_F' => 4, 'vote_E' => 5, 'vote_date' => 6, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'VOTE_ID',
        'Vote.Id' => 'VOTE_ID',
        'id' => 'VOTE_ID',
        'vote.id' => 'VOTE_ID',
        'VoteTableMap::COL_VOTE_ID' => 'VOTE_ID',
        'COL_VOTE_ID' => 'VOTE_ID',
        'vote_id' => 'VOTE_ID',
        'votes.vote_id' => 'VOTE_ID',
        'SiteId' => 'SITE_ID',
        'Vote.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'vote.siteId' => 'SITE_ID',
        'VoteTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'votes.site_id' => 'SITE_ID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'Vote.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'vote.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'VoteTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'votes.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'UserId' => 'USER_ID',
        'Vote.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'vote.userId' => 'USER_ID',
        'VoteTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'votes.user_id' => 'USER_ID',
        'F' => 'VOTE_F',
        'Vote.F' => 'VOTE_F',
        'f' => 'VOTE_F',
        'vote.f' => 'VOTE_F',
        'VoteTableMap::COL_VOTE_F' => 'VOTE_F',
        'COL_VOTE_F' => 'VOTE_F',
        'vote_F' => 'VOTE_F',
        'votes.vote_F' => 'VOTE_F',
        'E' => 'VOTE_E',
        'Vote.E' => 'VOTE_E',
        'e' => 'VOTE_E',
        'vote.e' => 'VOTE_E',
        'VoteTableMap::COL_VOTE_E' => 'VOTE_E',
        'COL_VOTE_E' => 'VOTE_E',
        'vote_E' => 'VOTE_E',
        'votes.vote_E' => 'VOTE_E',
        'Date' => 'VOTE_DATE',
        'Vote.Date' => 'VOTE_DATE',
        'date' => 'VOTE_DATE',
        'vote.date' => 'VOTE_DATE',
        'VoteTableMap::COL_VOTE_DATE' => 'VOTE_DATE',
        'COL_VOTE_DATE' => 'VOTE_DATE',
        'vote_date' => 'VOTE_DATE',
        'votes.vote_date' => 'VOTE_DATE',
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
        $this->setName('votes');
        $this->setPhpName('Vote');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Vote');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('vote_id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addColumn('axys_account_id', 'AxysAccountId', 'INTEGER', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, null, null);
        $this->addColumn('vote_F', 'F', 'INTEGER', false, null, null);
        $this->addColumn('vote_E', 'E', 'INTEGER', false, null, null);
        $this->addColumn('vote_date', 'Date', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Site', '\\Model\\Site', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, null, false);
        $this->addRelation('User', '\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
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
        return $withPrefix ? VoteTableMap::CLASS_DEFAULT : VoteTableMap::OM_CLASS;
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
     * @return array (Vote object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = VoteTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = VoteTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + VoteTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = VoteTableMap::OM_CLASS;
            /** @var Vote $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            VoteTableMap::addInstanceToPool($obj, $key);
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
            $key = VoteTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = VoteTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Vote $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                VoteTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(VoteTableMap::COL_VOTE_ID);
            $criteria->addSelectColumn(VoteTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(VoteTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(VoteTableMap::COL_USER_ID);
            $criteria->addSelectColumn(VoteTableMap::COL_VOTE_F);
            $criteria->addSelectColumn(VoteTableMap::COL_VOTE_E);
            $criteria->addSelectColumn(VoteTableMap::COL_VOTE_DATE);
        } else {
            $criteria->addSelectColumn($alias . '.vote_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.vote_F');
            $criteria->addSelectColumn($alias . '.vote_E');
            $criteria->addSelectColumn($alias . '.vote_date');
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
            $criteria->removeSelectColumn(VoteTableMap::COL_VOTE_ID);
            $criteria->removeSelectColumn(VoteTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(VoteTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(VoteTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(VoteTableMap::COL_VOTE_F);
            $criteria->removeSelectColumn(VoteTableMap::COL_VOTE_E);
            $criteria->removeSelectColumn(VoteTableMap::COL_VOTE_DATE);
        } else {
            $criteria->removeSelectColumn($alias . '.vote_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.vote_F');
            $criteria->removeSelectColumn($alias . '.vote_E');
            $criteria->removeSelectColumn($alias . '.vote_date');
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
        return Propel::getServiceContainer()->getDatabaseMap(VoteTableMap::DATABASE_NAME)->getTable(VoteTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Vote or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Vote object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(VoteTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Vote) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(VoteTableMap::DATABASE_NAME);
            $criteria->add(VoteTableMap::COL_VOTE_ID, (array) $values, Criteria::IN);
        }

        $query = VoteQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            VoteTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                VoteTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the votes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return VoteQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Vote or Criteria object.
     *
     * @param mixed $criteria Criteria or Vote object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(VoteTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Vote object
        }

        if ($criteria->containsKey(VoteTableMap::COL_VOTE_ID) && $criteria->keyContainsValue(VoteTableMap::COL_VOTE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.VoteTableMap::COL_VOTE_ID.')');
        }


        // Set the correct dbName
        $query = VoteQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
