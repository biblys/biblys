<?php

namespace Model\Map;

use Model\Invitation;
use Model\InvitationQuery;
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
 * This class defines the structure of the 'invitations' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class InvitationTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.InvitationTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'invitations';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Invitation';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Invitation';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Invitation';

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
    public const COL_ID = 'invitations.id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'invitations.site_id';

    /**
     * the column name for the email field
     */
    public const COL_EMAIL = 'invitations.email';

    /**
     * the column name for the code field
     */
    public const COL_CODE = 'invitations.code';

    /**
     * the column name for the allows_pre_download field
     */
    public const COL_ALLOWS_PRE_DOWNLOAD = 'invitations.allows_pre_download';

    /**
     * the column name for the consumed_at field
     */
    public const COL_CONSUMED_AT = 'invitations.consumed_at';

    /**
     * the column name for the expires_at field
     */
    public const COL_EXPIRES_AT = 'invitations.expires_at';

    /**
     * the column name for the created_at field
     */
    public const COL_CREATED_AT = 'invitations.created_at';

    /**
     * the column name for the updated_at field
     */
    public const COL_UPDATED_AT = 'invitations.updated_at';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'Email', 'Code', 'AllowsPreDownload', 'ConsumedAt', 'ExpiresAt', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'email', 'code', 'allowsPreDownload', 'consumedAt', 'expiresAt', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [InvitationTableMap::COL_ID, InvitationTableMap::COL_SITE_ID, InvitationTableMap::COL_EMAIL, InvitationTableMap::COL_CODE, InvitationTableMap::COL_ALLOWS_PRE_DOWNLOAD, InvitationTableMap::COL_CONSUMED_AT, InvitationTableMap::COL_EXPIRES_AT, InvitationTableMap::COL_CREATED_AT, InvitationTableMap::COL_UPDATED_AT, ],
        self::TYPE_FIELDNAME     => ['id', 'site_id', 'email', 'code', 'allows_pre_download', 'consumed_at', 'expires_at', 'created_at', 'updated_at', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'Email' => 2, 'Code' => 3, 'AllowsPreDownload' => 4, 'ConsumedAt' => 5, 'ExpiresAt' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'email' => 2, 'code' => 3, 'allowsPreDownload' => 4, 'consumedAt' => 5, 'expiresAt' => 6, 'createdAt' => 7, 'updatedAt' => 8, ],
        self::TYPE_COLNAME       => [InvitationTableMap::COL_ID => 0, InvitationTableMap::COL_SITE_ID => 1, InvitationTableMap::COL_EMAIL => 2, InvitationTableMap::COL_CODE => 3, InvitationTableMap::COL_ALLOWS_PRE_DOWNLOAD => 4, InvitationTableMap::COL_CONSUMED_AT => 5, InvitationTableMap::COL_EXPIRES_AT => 6, InvitationTableMap::COL_CREATED_AT => 7, InvitationTableMap::COL_UPDATED_AT => 8, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'site_id' => 1, 'email' => 2, 'code' => 3, 'allows_pre_download' => 4, 'consumed_at' => 5, 'expires_at' => 6, 'created_at' => 7, 'updated_at' => 8, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Invitation.Id' => 'ID',
        'id' => 'ID',
        'invitation.id' => 'ID',
        'InvitationTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'invitations.id' => 'ID',
        'SiteId' => 'SITE_ID',
        'Invitation.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'invitation.siteId' => 'SITE_ID',
        'InvitationTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'invitations.site_id' => 'SITE_ID',
        'Email' => 'EMAIL',
        'Invitation.Email' => 'EMAIL',
        'email' => 'EMAIL',
        'invitation.email' => 'EMAIL',
        'InvitationTableMap::COL_EMAIL' => 'EMAIL',
        'COL_EMAIL' => 'EMAIL',
        'invitations.email' => 'EMAIL',
        'Code' => 'CODE',
        'Invitation.Code' => 'CODE',
        'code' => 'CODE',
        'invitation.code' => 'CODE',
        'InvitationTableMap::COL_CODE' => 'CODE',
        'COL_CODE' => 'CODE',
        'invitations.code' => 'CODE',
        'AllowsPreDownload' => 'ALLOWS_PRE_DOWNLOAD',
        'Invitation.AllowsPreDownload' => 'ALLOWS_PRE_DOWNLOAD',
        'allowsPreDownload' => 'ALLOWS_PRE_DOWNLOAD',
        'invitation.allowsPreDownload' => 'ALLOWS_PRE_DOWNLOAD',
        'InvitationTableMap::COL_ALLOWS_PRE_DOWNLOAD' => 'ALLOWS_PRE_DOWNLOAD',
        'COL_ALLOWS_PRE_DOWNLOAD' => 'ALLOWS_PRE_DOWNLOAD',
        'allows_pre_download' => 'ALLOWS_PRE_DOWNLOAD',
        'invitations.allows_pre_download' => 'ALLOWS_PRE_DOWNLOAD',
        'ConsumedAt' => 'CONSUMED_AT',
        'Invitation.ConsumedAt' => 'CONSUMED_AT',
        'consumedAt' => 'CONSUMED_AT',
        'invitation.consumedAt' => 'CONSUMED_AT',
        'InvitationTableMap::COL_CONSUMED_AT' => 'CONSUMED_AT',
        'COL_CONSUMED_AT' => 'CONSUMED_AT',
        'consumed_at' => 'CONSUMED_AT',
        'invitations.consumed_at' => 'CONSUMED_AT',
        'ExpiresAt' => 'EXPIRES_AT',
        'Invitation.ExpiresAt' => 'EXPIRES_AT',
        'expiresAt' => 'EXPIRES_AT',
        'invitation.expiresAt' => 'EXPIRES_AT',
        'InvitationTableMap::COL_EXPIRES_AT' => 'EXPIRES_AT',
        'COL_EXPIRES_AT' => 'EXPIRES_AT',
        'expires_at' => 'EXPIRES_AT',
        'invitations.expires_at' => 'EXPIRES_AT',
        'CreatedAt' => 'CREATED_AT',
        'Invitation.CreatedAt' => 'CREATED_AT',
        'createdAt' => 'CREATED_AT',
        'invitation.createdAt' => 'CREATED_AT',
        'InvitationTableMap::COL_CREATED_AT' => 'CREATED_AT',
        'COL_CREATED_AT' => 'CREATED_AT',
        'created_at' => 'CREATED_AT',
        'invitations.created_at' => 'CREATED_AT',
        'UpdatedAt' => 'UPDATED_AT',
        'Invitation.UpdatedAt' => 'UPDATED_AT',
        'updatedAt' => 'UPDATED_AT',
        'invitation.updatedAt' => 'UPDATED_AT',
        'InvitationTableMap::COL_UPDATED_AT' => 'UPDATED_AT',
        'COL_UPDATED_AT' => 'UPDATED_AT',
        'updated_at' => 'UPDATED_AT',
        'invitations.updated_at' => 'UPDATED_AT',
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
        $this->setName('invitations');
        $this->setPhpName('Invitation');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Invitation');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', true, null, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 256, null);
        $this->addColumn('code', 'Code', 'VARCHAR', true, 8, null);
        $this->addColumn('allows_pre_download', 'AllowsPreDownload', 'BOOLEAN', false, 1, null);
        $this->addColumn('consumed_at', 'ConsumedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('expires_at', 'ExpiresAt', 'TIMESTAMP', true, null, null);
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
        $this->addRelation('InvitationsArticles', '\\Model\\InvitationsArticles', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':invitation_id',
    1 => ':id',
  ),
), null, null, 'InvitationsArticless', false);
        $this->addRelation('Article', '\\Model\\Article', RelationMap::MANY_TO_MANY, array(), null, null, 'Articles');
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
        return $withPrefix ? InvitationTableMap::CLASS_DEFAULT : InvitationTableMap::OM_CLASS;
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
     * @return array (Invitation object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = InvitationTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = InvitationTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + InvitationTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = InvitationTableMap::OM_CLASS;
            /** @var Invitation $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            InvitationTableMap::addInstanceToPool($obj, $key);
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
            $key = InvitationTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = InvitationTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Invitation $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                InvitationTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(InvitationTableMap::COL_ID);
            $criteria->addSelectColumn(InvitationTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(InvitationTableMap::COL_EMAIL);
            $criteria->addSelectColumn(InvitationTableMap::COL_CODE);
            $criteria->addSelectColumn(InvitationTableMap::COL_ALLOWS_PRE_DOWNLOAD);
            $criteria->addSelectColumn(InvitationTableMap::COL_CONSUMED_AT);
            $criteria->addSelectColumn(InvitationTableMap::COL_EXPIRES_AT);
            $criteria->addSelectColumn(InvitationTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(InvitationTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.code');
            $criteria->addSelectColumn($alias . '.allows_pre_download');
            $criteria->addSelectColumn($alias . '.consumed_at');
            $criteria->addSelectColumn($alias . '.expires_at');
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
            $criteria->removeSelectColumn(InvitationTableMap::COL_ID);
            $criteria->removeSelectColumn(InvitationTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(InvitationTableMap::COL_EMAIL);
            $criteria->removeSelectColumn(InvitationTableMap::COL_CODE);
            $criteria->removeSelectColumn(InvitationTableMap::COL_ALLOWS_PRE_DOWNLOAD);
            $criteria->removeSelectColumn(InvitationTableMap::COL_CONSUMED_AT);
            $criteria->removeSelectColumn(InvitationTableMap::COL_EXPIRES_AT);
            $criteria->removeSelectColumn(InvitationTableMap::COL_CREATED_AT);
            $criteria->removeSelectColumn(InvitationTableMap::COL_UPDATED_AT);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.email');
            $criteria->removeSelectColumn($alias . '.code');
            $criteria->removeSelectColumn($alias . '.allows_pre_download');
            $criteria->removeSelectColumn($alias . '.consumed_at');
            $criteria->removeSelectColumn($alias . '.expires_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(InvitationTableMap::DATABASE_NAME)->getTable(InvitationTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Invitation or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Invitation object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(InvitationTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Invitation) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(InvitationTableMap::DATABASE_NAME);
            $criteria->add(InvitationTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = InvitationQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            InvitationTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                InvitationTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the invitations table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return InvitationQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Invitation or Criteria object.
     *
     * @param mixed $criteria Criteria or Invitation object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvitationTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Invitation object
        }

        if ($criteria->containsKey(InvitationTableMap::COL_ID) && $criteria->keyContainsValue(InvitationTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.InvitationTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = InvitationQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
