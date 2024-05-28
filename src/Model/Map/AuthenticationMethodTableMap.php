<?php

namespace Model\Map;

use Model\AuthenticationMethod;
use Model\AuthenticationMethodQuery;
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
 * This class defines the structure of the 'authentication_methods' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class AuthenticationMethodTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.AuthenticationMethodTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'authentication_methods';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'AuthenticationMethod';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\AuthenticationMethod';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.AuthenticationMethod';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'authentication_methods.id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'authentication_methods.site_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'authentication_methods.user_id';

    /**
     * the column name for the identity_provider field
     */
    public const COL_IDENTITY_PROVIDER = 'authentication_methods.identity_provider';

    /**
     * the column name for the external_id field
     */
    public const COL_EXTERNAL_ID = 'authentication_methods.external_id';

    /**
     * the column name for the access_token field
     */
    public const COL_ACCESS_TOKEN = 'authentication_methods.access_token';

    /**
     * the column name for the id_token field
     */
    public const COL_ID_TOKEN = 'authentication_methods.id_token';

    /**
     * the column name for the created_at field
     */
    public const COL_CREATED_AT = 'authentication_methods.created_at';

    /**
     * the column name for the updated_at field
     */
    public const COL_UPDATED_AT = 'authentication_methods.updated_at';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'UserId', 'IdentityProvider', 'ExternalId', 'AccessToken', 'IdToken', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'userId', 'identityProvider', 'externalId', 'accessToken', 'idToken', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [AuthenticationMethodTableMap::COL_ID, AuthenticationMethodTableMap::COL_SITE_ID, AuthenticationMethodTableMap::COL_USER_ID, AuthenticationMethodTableMap::COL_IDENTITY_PROVIDER, AuthenticationMethodTableMap::COL_EXTERNAL_ID, AuthenticationMethodTableMap::COL_ACCESS_TOKEN, AuthenticationMethodTableMap::COL_ID_TOKEN, AuthenticationMethodTableMap::COL_CREATED_AT, AuthenticationMethodTableMap::COL_UPDATED_AT, ],
        self::TYPE_FIELDNAME     => ['id', 'site_id', 'user_id', 'identity_provider', 'external_id', 'access_token', 'id_token', 'created_at', 'updated_at', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'UserId' => 2, 'IdentityProvider' => 3, 'ExternalId' => 4, 'AccessToken' => 5, 'IdToken' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'userId' => 2, 'identityProvider' => 3, 'externalId' => 4, 'accessToken' => 5, 'idToken' => 6, 'createdAt' => 7, 'updatedAt' => 8, ],
        self::TYPE_COLNAME       => [AuthenticationMethodTableMap::COL_ID => 0, AuthenticationMethodTableMap::COL_SITE_ID => 1, AuthenticationMethodTableMap::COL_USER_ID => 2, AuthenticationMethodTableMap::COL_IDENTITY_PROVIDER => 3, AuthenticationMethodTableMap::COL_EXTERNAL_ID => 4, AuthenticationMethodTableMap::COL_ACCESS_TOKEN => 5, AuthenticationMethodTableMap::COL_ID_TOKEN => 6, AuthenticationMethodTableMap::COL_CREATED_AT => 7, AuthenticationMethodTableMap::COL_UPDATED_AT => 8, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'site_id' => 1, 'user_id' => 2, 'identity_provider' => 3, 'external_id' => 4, 'access_token' => 5, 'id_token' => 6, 'created_at' => 7, 'updated_at' => 8, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'AuthenticationMethod.Id' => 'ID',
        'id' => 'ID',
        'authenticationMethod.id' => 'ID',
        'AuthenticationMethodTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'authentication_methods.id' => 'ID',
        'SiteId' => 'SITE_ID',
        'AuthenticationMethod.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'authenticationMethod.siteId' => 'SITE_ID',
        'AuthenticationMethodTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'authentication_methods.site_id' => 'SITE_ID',
        'UserId' => 'USER_ID',
        'AuthenticationMethod.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'authenticationMethod.userId' => 'USER_ID',
        'AuthenticationMethodTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'authentication_methods.user_id' => 'USER_ID',
        'IdentityProvider' => 'IDENTITY_PROVIDER',
        'AuthenticationMethod.IdentityProvider' => 'IDENTITY_PROVIDER',
        'identityProvider' => 'IDENTITY_PROVIDER',
        'authenticationMethod.identityProvider' => 'IDENTITY_PROVIDER',
        'AuthenticationMethodTableMap::COL_IDENTITY_PROVIDER' => 'IDENTITY_PROVIDER',
        'COL_IDENTITY_PROVIDER' => 'IDENTITY_PROVIDER',
        'identity_provider' => 'IDENTITY_PROVIDER',
        'authentication_methods.identity_provider' => 'IDENTITY_PROVIDER',
        'ExternalId' => 'EXTERNAL_ID',
        'AuthenticationMethod.ExternalId' => 'EXTERNAL_ID',
        'externalId' => 'EXTERNAL_ID',
        'authenticationMethod.externalId' => 'EXTERNAL_ID',
        'AuthenticationMethodTableMap::COL_EXTERNAL_ID' => 'EXTERNAL_ID',
        'COL_EXTERNAL_ID' => 'EXTERNAL_ID',
        'external_id' => 'EXTERNAL_ID',
        'authentication_methods.external_id' => 'EXTERNAL_ID',
        'AccessToken' => 'ACCESS_TOKEN',
        'AuthenticationMethod.AccessToken' => 'ACCESS_TOKEN',
        'accessToken' => 'ACCESS_TOKEN',
        'authenticationMethod.accessToken' => 'ACCESS_TOKEN',
        'AuthenticationMethodTableMap::COL_ACCESS_TOKEN' => 'ACCESS_TOKEN',
        'COL_ACCESS_TOKEN' => 'ACCESS_TOKEN',
        'access_token' => 'ACCESS_TOKEN',
        'authentication_methods.access_token' => 'ACCESS_TOKEN',
        'IdToken' => 'ID_TOKEN',
        'AuthenticationMethod.IdToken' => 'ID_TOKEN',
        'idToken' => 'ID_TOKEN',
        'authenticationMethod.idToken' => 'ID_TOKEN',
        'AuthenticationMethodTableMap::COL_ID_TOKEN' => 'ID_TOKEN',
        'COL_ID_TOKEN' => 'ID_TOKEN',
        'id_token' => 'ID_TOKEN',
        'authentication_methods.id_token' => 'ID_TOKEN',
        'CreatedAt' => 'CREATED_AT',
        'AuthenticationMethod.CreatedAt' => 'CREATED_AT',
        'createdAt' => 'CREATED_AT',
        'authenticationMethod.createdAt' => 'CREATED_AT',
        'AuthenticationMethodTableMap::COL_CREATED_AT' => 'CREATED_AT',
        'COL_CREATED_AT' => 'CREATED_AT',
        'created_at' => 'CREATED_AT',
        'authentication_methods.created_at' => 'CREATED_AT',
        'UpdatedAt' => 'UPDATED_AT',
        'AuthenticationMethod.UpdatedAt' => 'UPDATED_AT',
        'updatedAt' => 'UPDATED_AT',
        'authenticationMethod.updatedAt' => 'UPDATED_AT',
        'AuthenticationMethodTableMap::COL_UPDATED_AT' => 'UPDATED_AT',
        'COL_UPDATED_AT' => 'UPDATED_AT',
        'updated_at' => 'UPDATED_AT',
        'authentication_methods.updated_at' => 'UPDATED_AT',
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
        $this->setName('authentication_methods');
        $this->setPhpName('AuthenticationMethod');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\AuthenticationMethod');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', true, null, null);
        $this->addColumn('identity_provider', 'IdentityProvider', 'VARCHAR', false, 16, null);
        $this->addColumn('external_id', 'ExternalId', 'VARCHAR', false, 128, null);
        $this->addColumn('access_token', 'AccessToken', 'VARCHAR', false, 2048, null);
        $this->addColumn('id_token', 'IdToken', 'VARCHAR', false, 2048, null);
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
        return $withPrefix ? AuthenticationMethodTableMap::CLASS_DEFAULT : AuthenticationMethodTableMap::OM_CLASS;
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
     * @return array (AuthenticationMethod object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = AuthenticationMethodTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AuthenticationMethodTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AuthenticationMethodTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AuthenticationMethodTableMap::OM_CLASS;
            /** @var AuthenticationMethod $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AuthenticationMethodTableMap::addInstanceToPool($obj, $key);
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
            $key = AuthenticationMethodTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AuthenticationMethodTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AuthenticationMethod $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AuthenticationMethodTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AuthenticationMethodTableMap::COL_ID);
            $criteria->addSelectColumn(AuthenticationMethodTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(AuthenticationMethodTableMap::COL_USER_ID);
            $criteria->addSelectColumn(AuthenticationMethodTableMap::COL_IDENTITY_PROVIDER);
            $criteria->addSelectColumn(AuthenticationMethodTableMap::COL_EXTERNAL_ID);
            $criteria->addSelectColumn(AuthenticationMethodTableMap::COL_ACCESS_TOKEN);
            $criteria->addSelectColumn(AuthenticationMethodTableMap::COL_ID_TOKEN);
            $criteria->addSelectColumn(AuthenticationMethodTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(AuthenticationMethodTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.identity_provider');
            $criteria->addSelectColumn($alias . '.external_id');
            $criteria->addSelectColumn($alias . '.access_token');
            $criteria->addSelectColumn($alias . '.id_token');
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
            $criteria->removeSelectColumn(AuthenticationMethodTableMap::COL_ID);
            $criteria->removeSelectColumn(AuthenticationMethodTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(AuthenticationMethodTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(AuthenticationMethodTableMap::COL_IDENTITY_PROVIDER);
            $criteria->removeSelectColumn(AuthenticationMethodTableMap::COL_EXTERNAL_ID);
            $criteria->removeSelectColumn(AuthenticationMethodTableMap::COL_ACCESS_TOKEN);
            $criteria->removeSelectColumn(AuthenticationMethodTableMap::COL_ID_TOKEN);
            $criteria->removeSelectColumn(AuthenticationMethodTableMap::COL_CREATED_AT);
            $criteria->removeSelectColumn(AuthenticationMethodTableMap::COL_UPDATED_AT);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.identity_provider');
            $criteria->removeSelectColumn($alias . '.external_id');
            $criteria->removeSelectColumn($alias . '.access_token');
            $criteria->removeSelectColumn($alias . '.id_token');
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
        return Propel::getServiceContainer()->getDatabaseMap(AuthenticationMethodTableMap::DATABASE_NAME)->getTable(AuthenticationMethodTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a AuthenticationMethod or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or AuthenticationMethod object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AuthenticationMethodTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\AuthenticationMethod) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AuthenticationMethodTableMap::DATABASE_NAME);
            $criteria->add(AuthenticationMethodTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AuthenticationMethodQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AuthenticationMethodTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AuthenticationMethodTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the authentication_methods table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return AuthenticationMethodQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AuthenticationMethod or Criteria object.
     *
     * @param mixed $criteria Criteria or AuthenticationMethod object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AuthenticationMethodTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AuthenticationMethod object
        }

        if ($criteria->containsKey(AuthenticationMethodTableMap::COL_ID) && $criteria->keyContainsValue(AuthenticationMethodTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AuthenticationMethodTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AuthenticationMethodQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
