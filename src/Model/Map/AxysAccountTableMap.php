<?php

namespace Model\Map;

use Model\AxysAccount;
use Model\AxysAccountQuery;
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
 * This class defines the structure of the 'axys_accounts' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class AxysAccountTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.AxysAccountTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'axys_accounts';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'AxysAccount';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\AxysAccount';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.AxysAccount';

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
     * the column name for the id field
     */
    public const COL_ID = 'axys_accounts.id';

    /**
     * the column name for the Email field
     */
    public const COL_EMAIL = 'axys_accounts.Email';

    /**
     * the column name for the user_password field
     */
    public const COL_USER_PASSWORD = 'axys_accounts.user_password';

    /**
     * the column name for the user_key field
     */
    public const COL_USER_KEY = 'axys_accounts.user_key';

    /**
     * the column name for the email_key field
     */
    public const COL_EMAIL_KEY = 'axys_accounts.email_key';

    /**
     * the column name for the user_screen_name field
     */
    public const COL_USER_SCREEN_NAME = 'axys_accounts.user_screen_name';

    /**
     * the column name for the user_slug field
     */
    public const COL_USER_SLUG = 'axys_accounts.user_slug';

    /**
     * the column name for the DateInscription field
     */
    public const COL_DATEINSCRIPTION = 'axys_accounts.DateInscription';

    /**
     * the column name for the DateConnexion field
     */
    public const COL_DATECONNEXION = 'axys_accounts.DateConnexion';

    /**
     * the column name for the user_nom field
     */
    public const COL_USER_NOM = 'axys_accounts.user_nom';

    /**
     * the column name for the user_prenom field
     */
    public const COL_USER_PRENOM = 'axys_accounts.user_prenom';

    /**
     * the column name for the user_update field
     */
    public const COL_USER_UPDATE = 'axys_accounts.user_update';

    /**
     * the column name for the user_created field
     */
    public const COL_USER_CREATED = 'axys_accounts.user_created';

    /**
     * the column name for the user_updated field
     */
    public const COL_USER_UPDATED = 'axys_accounts.user_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'Email', 'Password', 'Key', 'EmailKey', 'Username', 'Slug', 'Dateinscription', 'Dateconnexion', 'Nom', 'Prenom', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'email', 'password', 'key', 'emailKey', 'username', 'slug', 'dateinscription', 'dateconnexion', 'nom', 'prenom', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [AxysAccountTableMap::COL_ID, AxysAccountTableMap::COL_EMAIL, AxysAccountTableMap::COL_USER_PASSWORD, AxysAccountTableMap::COL_USER_KEY, AxysAccountTableMap::COL_EMAIL_KEY, AxysAccountTableMap::COL_USER_SCREEN_NAME, AxysAccountTableMap::COL_USER_SLUG, AxysAccountTableMap::COL_DATEINSCRIPTION, AxysAccountTableMap::COL_DATECONNEXION, AxysAccountTableMap::COL_USER_NOM, AxysAccountTableMap::COL_USER_PRENOM, AxysAccountTableMap::COL_USER_UPDATE, AxysAccountTableMap::COL_USER_CREATED, AxysAccountTableMap::COL_USER_UPDATED, ],
        self::TYPE_FIELDNAME     => ['id', 'Email', 'user_password', 'user_key', 'email_key', 'user_screen_name', 'user_slug', 'DateInscription', 'DateConnexion', 'user_nom', 'user_prenom', 'user_update', 'user_created', 'user_updated', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Email' => 1, 'Password' => 2, 'Key' => 3, 'EmailKey' => 4, 'Username' => 5, 'Slug' => 6, 'Dateinscription' => 7, 'Dateconnexion' => 8, 'Nom' => 9, 'Prenom' => 10, 'Update' => 11, 'CreatedAt' => 12, 'UpdatedAt' => 13, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'email' => 1, 'password' => 2, 'key' => 3, 'emailKey' => 4, 'username' => 5, 'slug' => 6, 'dateinscription' => 7, 'dateconnexion' => 8, 'nom' => 9, 'prenom' => 10, 'update' => 11, 'createdAt' => 12, 'updatedAt' => 13, ],
        self::TYPE_COLNAME       => [AxysAccountTableMap::COL_ID => 0, AxysAccountTableMap::COL_EMAIL => 1, AxysAccountTableMap::COL_USER_PASSWORD => 2, AxysAccountTableMap::COL_USER_KEY => 3, AxysAccountTableMap::COL_EMAIL_KEY => 4, AxysAccountTableMap::COL_USER_SCREEN_NAME => 5, AxysAccountTableMap::COL_USER_SLUG => 6, AxysAccountTableMap::COL_DATEINSCRIPTION => 7, AxysAccountTableMap::COL_DATECONNEXION => 8, AxysAccountTableMap::COL_USER_NOM => 9, AxysAccountTableMap::COL_USER_PRENOM => 10, AxysAccountTableMap::COL_USER_UPDATE => 11, AxysAccountTableMap::COL_USER_CREATED => 12, AxysAccountTableMap::COL_USER_UPDATED => 13, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'Email' => 1, 'user_password' => 2, 'user_key' => 3, 'email_key' => 4, 'user_screen_name' => 5, 'user_slug' => 6, 'DateInscription' => 7, 'DateConnexion' => 8, 'user_nom' => 9, 'user_prenom' => 10, 'user_update' => 11, 'user_created' => 12, 'user_updated' => 13, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'AxysAccount.Id' => 'ID',
        'id' => 'ID',
        'axysAccount.id' => 'ID',
        'AxysAccountTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'axys_accounts.id' => 'ID',
        'Email' => 'EMAIL',
        'AxysAccount.Email' => 'EMAIL',
        'email' => 'EMAIL',
        'axysAccount.email' => 'EMAIL',
        'AxysAccountTableMap::COL_EMAIL' => 'EMAIL',
        'COL_EMAIL' => 'EMAIL',
        'axys_accounts.Email' => 'EMAIL',
        'Password' => 'USER_PASSWORD',
        'AxysAccount.Password' => 'USER_PASSWORD',
        'password' => 'USER_PASSWORD',
        'axysAccount.password' => 'USER_PASSWORD',
        'AxysAccountTableMap::COL_USER_PASSWORD' => 'USER_PASSWORD',
        'COL_USER_PASSWORD' => 'USER_PASSWORD',
        'user_password' => 'USER_PASSWORD',
        'axys_accounts.user_password' => 'USER_PASSWORD',
        'Key' => 'USER_KEY',
        'AxysAccount.Key' => 'USER_KEY',
        'key' => 'USER_KEY',
        'axysAccount.key' => 'USER_KEY',
        'AxysAccountTableMap::COL_USER_KEY' => 'USER_KEY',
        'COL_USER_KEY' => 'USER_KEY',
        'user_key' => 'USER_KEY',
        'axys_accounts.user_key' => 'USER_KEY',
        'EmailKey' => 'EMAIL_KEY',
        'AxysAccount.EmailKey' => 'EMAIL_KEY',
        'emailKey' => 'EMAIL_KEY',
        'axysAccount.emailKey' => 'EMAIL_KEY',
        'AxysAccountTableMap::COL_EMAIL_KEY' => 'EMAIL_KEY',
        'COL_EMAIL_KEY' => 'EMAIL_KEY',
        'email_key' => 'EMAIL_KEY',
        'axys_accounts.email_key' => 'EMAIL_KEY',
        'Username' => 'USER_SCREEN_NAME',
        'AxysAccount.Username' => 'USER_SCREEN_NAME',
        'username' => 'USER_SCREEN_NAME',
        'axysAccount.username' => 'USER_SCREEN_NAME',
        'AxysAccountTableMap::COL_USER_SCREEN_NAME' => 'USER_SCREEN_NAME',
        'COL_USER_SCREEN_NAME' => 'USER_SCREEN_NAME',
        'user_screen_name' => 'USER_SCREEN_NAME',
        'axys_accounts.user_screen_name' => 'USER_SCREEN_NAME',
        'Slug' => 'USER_SLUG',
        'AxysAccount.Slug' => 'USER_SLUG',
        'slug' => 'USER_SLUG',
        'axysAccount.slug' => 'USER_SLUG',
        'AxysAccountTableMap::COL_USER_SLUG' => 'USER_SLUG',
        'COL_USER_SLUG' => 'USER_SLUG',
        'user_slug' => 'USER_SLUG',
        'axys_accounts.user_slug' => 'USER_SLUG',
        'Dateinscription' => 'DATEINSCRIPTION',
        'AxysAccount.Dateinscription' => 'DATEINSCRIPTION',
        'dateinscription' => 'DATEINSCRIPTION',
        'axysAccount.dateinscription' => 'DATEINSCRIPTION',
        'AxysAccountTableMap::COL_DATEINSCRIPTION' => 'DATEINSCRIPTION',
        'COL_DATEINSCRIPTION' => 'DATEINSCRIPTION',
        'DateInscription' => 'DATEINSCRIPTION',
        'axys_accounts.DateInscription' => 'DATEINSCRIPTION',
        'Dateconnexion' => 'DATECONNEXION',
        'AxysAccount.Dateconnexion' => 'DATECONNEXION',
        'dateconnexion' => 'DATECONNEXION',
        'axysAccount.dateconnexion' => 'DATECONNEXION',
        'AxysAccountTableMap::COL_DATECONNEXION' => 'DATECONNEXION',
        'COL_DATECONNEXION' => 'DATECONNEXION',
        'DateConnexion' => 'DATECONNEXION',
        'axys_accounts.DateConnexion' => 'DATECONNEXION',
        'Nom' => 'USER_NOM',
        'AxysAccount.Nom' => 'USER_NOM',
        'nom' => 'USER_NOM',
        'axysAccount.nom' => 'USER_NOM',
        'AxysAccountTableMap::COL_USER_NOM' => 'USER_NOM',
        'COL_USER_NOM' => 'USER_NOM',
        'user_nom' => 'USER_NOM',
        'axys_accounts.user_nom' => 'USER_NOM',
        'Prenom' => 'USER_PRENOM',
        'AxysAccount.Prenom' => 'USER_PRENOM',
        'prenom' => 'USER_PRENOM',
        'axysAccount.prenom' => 'USER_PRENOM',
        'AxysAccountTableMap::COL_USER_PRENOM' => 'USER_PRENOM',
        'COL_USER_PRENOM' => 'USER_PRENOM',
        'user_prenom' => 'USER_PRENOM',
        'axys_accounts.user_prenom' => 'USER_PRENOM',
        'Update' => 'USER_UPDATE',
        'AxysAccount.Update' => 'USER_UPDATE',
        'update' => 'USER_UPDATE',
        'axysAccount.update' => 'USER_UPDATE',
        'AxysAccountTableMap::COL_USER_UPDATE' => 'USER_UPDATE',
        'COL_USER_UPDATE' => 'USER_UPDATE',
        'user_update' => 'USER_UPDATE',
        'axys_accounts.user_update' => 'USER_UPDATE',
        'CreatedAt' => 'USER_CREATED',
        'AxysAccount.CreatedAt' => 'USER_CREATED',
        'createdAt' => 'USER_CREATED',
        'axysAccount.createdAt' => 'USER_CREATED',
        'AxysAccountTableMap::COL_USER_CREATED' => 'USER_CREATED',
        'COL_USER_CREATED' => 'USER_CREATED',
        'user_created' => 'USER_CREATED',
        'axys_accounts.user_created' => 'USER_CREATED',
        'UpdatedAt' => 'USER_UPDATED',
        'AxysAccount.UpdatedAt' => 'USER_UPDATED',
        'updatedAt' => 'USER_UPDATED',
        'axysAccount.updatedAt' => 'USER_UPDATED',
        'AxysAccountTableMap::COL_USER_UPDATED' => 'USER_UPDATED',
        'COL_USER_UPDATED' => 'USER_UPDATED',
        'user_updated' => 'USER_UPDATED',
        'axys_accounts.user_updated' => 'USER_UPDATED',
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
        $this->setName('axys_accounts');
        $this->setPhpName('AxysAccount');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\AxysAccount');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('Email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('user_password', 'Password', 'VARCHAR', false, 255, null);
        $this->addColumn('user_key', 'Key', 'LONGVARCHAR', false, null, null);
        $this->addColumn('email_key', 'EmailKey', 'VARCHAR', false, 32, null);
        $this->addColumn('user_screen_name', 'Username', 'VARCHAR', false, 128, null);
        $this->addColumn('user_slug', 'Slug', 'VARCHAR', false, 32, null);
        $this->addColumn('DateInscription', 'Dateinscription', 'TIMESTAMP', false, null, null);
        $this->addColumn('DateConnexion', 'Dateconnexion', 'TIMESTAMP', false, null, null);
        $this->addColumn('user_nom', 'Nom', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_prenom', 'Prenom', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('user_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('user_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('AxysConsent', '\\Model\\AxysConsent', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'AxysConsents', false);
        $this->addRelation('Cart', '\\Model\\Cart', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':id',
  ),
), null, null, 'Carts', false);
        $this->addRelation('Option', '\\Model\\Option', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':id',
  ),
), null, null, 'Options', false);
        $this->addRelation('Right', '\\Model\\Right', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':id',
  ),
), null, null, 'Rights', false);
        $this->addRelation('Session', '\\Model\\Session', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':id',
  ),
), null, null, 'Sessions', false);
        $this->addRelation('Stock', '\\Model\\Stock', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':id',
  ),
), null, null, 'Stocks', false);
        $this->addRelation('Wish', '\\Model\\Wish', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':id',
  ),
), null, null, 'Wishes', false);
        $this->addRelation('Wishlist', '\\Model\\Wishlist', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':id',
  ),
), null, null, 'Wishlists', false);
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
            'timestampable' => ['create_column' => 'user_created', 'update_column' => 'user_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
            'validate' => ['rule1' => ['column' => 'email', 'validator' => 'Email']],
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
        return $withPrefix ? AxysAccountTableMap::CLASS_DEFAULT : AxysAccountTableMap::OM_CLASS;
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
     * @return array (AxysAccount object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = AxysAccountTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AxysAccountTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AxysAccountTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AxysAccountTableMap::OM_CLASS;
            /** @var AxysAccount $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AxysAccountTableMap::addInstanceToPool($obj, $key);
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
            $key = AxysAccountTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AxysAccountTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AxysAccount $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AxysAccountTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AxysAccountTableMap::COL_ID);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_EMAIL);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_USER_PASSWORD);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_USER_KEY);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_EMAIL_KEY);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_USER_SCREEN_NAME);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_USER_SLUG);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_DATEINSCRIPTION);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_DATECONNEXION);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_USER_NOM);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_USER_PRENOM);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_USER_UPDATE);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_USER_CREATED);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_USER_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.Email');
            $criteria->addSelectColumn($alias . '.user_password');
            $criteria->addSelectColumn($alias . '.user_key');
            $criteria->addSelectColumn($alias . '.email_key');
            $criteria->addSelectColumn($alias . '.user_screen_name');
            $criteria->addSelectColumn($alias . '.user_slug');
            $criteria->addSelectColumn($alias . '.DateInscription');
            $criteria->addSelectColumn($alias . '.DateConnexion');
            $criteria->addSelectColumn($alias . '.user_nom');
            $criteria->addSelectColumn($alias . '.user_prenom');
            $criteria->addSelectColumn($alias . '.user_update');
            $criteria->addSelectColumn($alias . '.user_created');
            $criteria->addSelectColumn($alias . '.user_updated');
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
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_ID);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_EMAIL);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_USER_PASSWORD);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_USER_KEY);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_EMAIL_KEY);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_USER_SCREEN_NAME);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_USER_SLUG);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_DATEINSCRIPTION);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_DATECONNEXION);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_USER_NOM);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_USER_PRENOM);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_USER_UPDATE);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_USER_CREATED);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_USER_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.Email');
            $criteria->removeSelectColumn($alias . '.user_password');
            $criteria->removeSelectColumn($alias . '.user_key');
            $criteria->removeSelectColumn($alias . '.email_key');
            $criteria->removeSelectColumn($alias . '.user_screen_name');
            $criteria->removeSelectColumn($alias . '.user_slug');
            $criteria->removeSelectColumn($alias . '.DateInscription');
            $criteria->removeSelectColumn($alias . '.DateConnexion');
            $criteria->removeSelectColumn($alias . '.user_nom');
            $criteria->removeSelectColumn($alias . '.user_prenom');
            $criteria->removeSelectColumn($alias . '.user_update');
            $criteria->removeSelectColumn($alias . '.user_created');
            $criteria->removeSelectColumn($alias . '.user_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(AxysAccountTableMap::DATABASE_NAME)->getTable(AxysAccountTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a AxysAccount or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or AxysAccount object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AxysAccountTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\AxysAccount) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AxysAccountTableMap::DATABASE_NAME);
            $criteria->add(AxysAccountTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AxysAccountQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AxysAccountTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AxysAccountTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the axys_accounts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return AxysAccountQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AxysAccount or Criteria object.
     *
     * @param mixed $criteria Criteria or AxysAccount object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AxysAccountTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AxysAccount object
        }

        if ($criteria->containsKey(AxysAccountTableMap::COL_ID) && $criteria->keyContainsValue(AxysAccountTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AxysAccountTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AxysAccountQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
