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
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'axys_accounts.axys_account_id';

    /**
     * the column name for the axys_account_email field
     */
    public const COL_AXYS_ACCOUNT_EMAIL = 'axys_accounts.axys_account_email';

    /**
     * the column name for the axys_account_password field
     */
    public const COL_AXYS_ACCOUNT_PASSWORD = 'axys_accounts.axys_account_password';

    /**
     * the column name for the axys_account_key field
     */
    public const COL_AXYS_ACCOUNT_KEY = 'axys_accounts.axys_account_key';

    /**
     * the column name for the axys_account_email_key field
     */
    public const COL_AXYS_ACCOUNT_EMAIL_KEY = 'axys_accounts.axys_account_email_key';

    /**
     * the column name for the axys_account_screen_name field
     */
    public const COL_AXYS_ACCOUNT_SCREEN_NAME = 'axys_accounts.axys_account_screen_name';

    /**
     * the column name for the axys_account_slug field
     */
    public const COL_AXYS_ACCOUNT_SLUG = 'axys_accounts.axys_account_slug';

    /**
     * the column name for the axys_account_signup_date field
     */
    public const COL_AXYS_ACCOUNT_SIGNUP_DATE = 'axys_accounts.axys_account_signup_date';

    /**
     * the column name for the axys_account_login_date field
     */
    public const COL_AXYS_ACCOUNT_LOGIN_DATE = 'axys_accounts.axys_account_login_date';

    /**
     * the column name for the axys_account_first_name field
     */
    public const COL_AXYS_ACCOUNT_FIRST_NAME = 'axys_accounts.axys_account_first_name';

    /**
     * the column name for the axys_account_last_name field
     */
    public const COL_AXYS_ACCOUNT_LAST_NAME = 'axys_accounts.axys_account_last_name';

    /**
     * the column name for the axys_account_update field
     */
    public const COL_AXYS_ACCOUNT_UPDATE = 'axys_accounts.axys_account_update';

    /**
     * the column name for the axys_account_created field
     */
    public const COL_AXYS_ACCOUNT_CREATED = 'axys_accounts.axys_account_created';

    /**
     * the column name for the axys_account_updated field
     */
    public const COL_AXYS_ACCOUNT_UPDATED = 'axys_accounts.axys_account_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'Email', 'Password', 'Key', 'EmailKey', 'Username', 'Slug', 'SignupDate', 'LoginDate', 'FirstName', 'LastName', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'email', 'password', 'key', 'emailKey', 'username', 'slug', 'signupDate', 'loginDate', 'firstName', 'lastName', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [AxysAccountTableMap::COL_AXYS_ACCOUNT_ID, AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL, AxysAccountTableMap::COL_AXYS_ACCOUNT_PASSWORD, AxysAccountTableMap::COL_AXYS_ACCOUNT_KEY, AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL_KEY, AxysAccountTableMap::COL_AXYS_ACCOUNT_SCREEN_NAME, AxysAccountTableMap::COL_AXYS_ACCOUNT_SLUG, AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE, AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE, AxysAccountTableMap::COL_AXYS_ACCOUNT_FIRST_NAME, AxysAccountTableMap::COL_AXYS_ACCOUNT_LAST_NAME, AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE, AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED, AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED, ],
        self::TYPE_FIELDNAME     => ['axys_account_id', 'axys_account_email', 'axys_account_password', 'axys_account_key', 'axys_account_email_key', 'axys_account_screen_name', 'axys_account_slug', 'axys_account_signup_date', 'axys_account_login_date', 'axys_account_first_name', 'axys_account_last_name', 'axys_account_update', 'axys_account_created', 'axys_account_updated', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Email' => 1, 'Password' => 2, 'Key' => 3, 'EmailKey' => 4, 'Username' => 5, 'Slug' => 6, 'SignupDate' => 7, 'LoginDate' => 8, 'FirstName' => 9, 'LastName' => 10, 'Update' => 11, 'CreatedAt' => 12, 'UpdatedAt' => 13, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'email' => 1, 'password' => 2, 'key' => 3, 'emailKey' => 4, 'username' => 5, 'slug' => 6, 'signupDate' => 7, 'loginDate' => 8, 'firstName' => 9, 'lastName' => 10, 'update' => 11, 'createdAt' => 12, 'updatedAt' => 13, ],
        self::TYPE_COLNAME       => [AxysAccountTableMap::COL_AXYS_ACCOUNT_ID => 0, AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL => 1, AxysAccountTableMap::COL_AXYS_ACCOUNT_PASSWORD => 2, AxysAccountTableMap::COL_AXYS_ACCOUNT_KEY => 3, AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL_KEY => 4, AxysAccountTableMap::COL_AXYS_ACCOUNT_SCREEN_NAME => 5, AxysAccountTableMap::COL_AXYS_ACCOUNT_SLUG => 6, AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE => 7, AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE => 8, AxysAccountTableMap::COL_AXYS_ACCOUNT_FIRST_NAME => 9, AxysAccountTableMap::COL_AXYS_ACCOUNT_LAST_NAME => 10, AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE => 11, AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED => 12, AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED => 13, ],
        self::TYPE_FIELDNAME     => ['axys_account_id' => 0, 'axys_account_email' => 1, 'axys_account_password' => 2, 'axys_account_key' => 3, 'axys_account_email_key' => 4, 'axys_account_screen_name' => 5, 'axys_account_slug' => 6, 'axys_account_signup_date' => 7, 'axys_account_login_date' => 8, 'axys_account_first_name' => 9, 'axys_account_last_name' => 10, 'axys_account_update' => 11, 'axys_account_created' => 12, 'axys_account_updated' => 13, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'AXYS_ACCOUNT_ID',
        'AxysAccount.Id' => 'AXYS_ACCOUNT_ID',
        'id' => 'AXYS_ACCOUNT_ID',
        'axysAccount.id' => 'AXYS_ACCOUNT_ID',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'axys_accounts.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'Email' => 'AXYS_ACCOUNT_EMAIL',
        'AxysAccount.Email' => 'AXYS_ACCOUNT_EMAIL',
        'email' => 'AXYS_ACCOUNT_EMAIL',
        'axysAccount.email' => 'AXYS_ACCOUNT_EMAIL',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL' => 'AXYS_ACCOUNT_EMAIL',
        'COL_AXYS_ACCOUNT_EMAIL' => 'AXYS_ACCOUNT_EMAIL',
        'axys_account_email' => 'AXYS_ACCOUNT_EMAIL',
        'axys_accounts.axys_account_email' => 'AXYS_ACCOUNT_EMAIL',
        'Password' => 'AXYS_ACCOUNT_PASSWORD',
        'AxysAccount.Password' => 'AXYS_ACCOUNT_PASSWORD',
        'password' => 'AXYS_ACCOUNT_PASSWORD',
        'axysAccount.password' => 'AXYS_ACCOUNT_PASSWORD',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_PASSWORD' => 'AXYS_ACCOUNT_PASSWORD',
        'COL_AXYS_ACCOUNT_PASSWORD' => 'AXYS_ACCOUNT_PASSWORD',
        'axys_account_password' => 'AXYS_ACCOUNT_PASSWORD',
        'axys_accounts.axys_account_password' => 'AXYS_ACCOUNT_PASSWORD',
        'Key' => 'AXYS_ACCOUNT_KEY',
        'AxysAccount.Key' => 'AXYS_ACCOUNT_KEY',
        'key' => 'AXYS_ACCOUNT_KEY',
        'axysAccount.key' => 'AXYS_ACCOUNT_KEY',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_KEY' => 'AXYS_ACCOUNT_KEY',
        'COL_AXYS_ACCOUNT_KEY' => 'AXYS_ACCOUNT_KEY',
        'axys_account_key' => 'AXYS_ACCOUNT_KEY',
        'axys_accounts.axys_account_key' => 'AXYS_ACCOUNT_KEY',
        'EmailKey' => 'AXYS_ACCOUNT_EMAIL_KEY',
        'AxysAccount.EmailKey' => 'AXYS_ACCOUNT_EMAIL_KEY',
        'emailKey' => 'AXYS_ACCOUNT_EMAIL_KEY',
        'axysAccount.emailKey' => 'AXYS_ACCOUNT_EMAIL_KEY',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL_KEY' => 'AXYS_ACCOUNT_EMAIL_KEY',
        'COL_AXYS_ACCOUNT_EMAIL_KEY' => 'AXYS_ACCOUNT_EMAIL_KEY',
        'axys_account_email_key' => 'AXYS_ACCOUNT_EMAIL_KEY',
        'axys_accounts.axys_account_email_key' => 'AXYS_ACCOUNT_EMAIL_KEY',
        'Username' => 'AXYS_ACCOUNT_SCREEN_NAME',
        'AxysAccount.Username' => 'AXYS_ACCOUNT_SCREEN_NAME',
        'username' => 'AXYS_ACCOUNT_SCREEN_NAME',
        'axysAccount.username' => 'AXYS_ACCOUNT_SCREEN_NAME',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_SCREEN_NAME' => 'AXYS_ACCOUNT_SCREEN_NAME',
        'COL_AXYS_ACCOUNT_SCREEN_NAME' => 'AXYS_ACCOUNT_SCREEN_NAME',
        'axys_account_screen_name' => 'AXYS_ACCOUNT_SCREEN_NAME',
        'axys_accounts.axys_account_screen_name' => 'AXYS_ACCOUNT_SCREEN_NAME',
        'Slug' => 'AXYS_ACCOUNT_SLUG',
        'AxysAccount.Slug' => 'AXYS_ACCOUNT_SLUG',
        'slug' => 'AXYS_ACCOUNT_SLUG',
        'axysAccount.slug' => 'AXYS_ACCOUNT_SLUG',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_SLUG' => 'AXYS_ACCOUNT_SLUG',
        'COL_AXYS_ACCOUNT_SLUG' => 'AXYS_ACCOUNT_SLUG',
        'axys_account_slug' => 'AXYS_ACCOUNT_SLUG',
        'axys_accounts.axys_account_slug' => 'AXYS_ACCOUNT_SLUG',
        'SignupDate' => 'AXYS_ACCOUNT_SIGNUP_DATE',
        'AxysAccount.SignupDate' => 'AXYS_ACCOUNT_SIGNUP_DATE',
        'signupDate' => 'AXYS_ACCOUNT_SIGNUP_DATE',
        'axysAccount.signupDate' => 'AXYS_ACCOUNT_SIGNUP_DATE',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE' => 'AXYS_ACCOUNT_SIGNUP_DATE',
        'COL_AXYS_ACCOUNT_SIGNUP_DATE' => 'AXYS_ACCOUNT_SIGNUP_DATE',
        'axys_account_signup_date' => 'AXYS_ACCOUNT_SIGNUP_DATE',
        'axys_accounts.axys_account_signup_date' => 'AXYS_ACCOUNT_SIGNUP_DATE',
        'LoginDate' => 'AXYS_ACCOUNT_LOGIN_DATE',
        'AxysAccount.LoginDate' => 'AXYS_ACCOUNT_LOGIN_DATE',
        'loginDate' => 'AXYS_ACCOUNT_LOGIN_DATE',
        'axysAccount.loginDate' => 'AXYS_ACCOUNT_LOGIN_DATE',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE' => 'AXYS_ACCOUNT_LOGIN_DATE',
        'COL_AXYS_ACCOUNT_LOGIN_DATE' => 'AXYS_ACCOUNT_LOGIN_DATE',
        'axys_account_login_date' => 'AXYS_ACCOUNT_LOGIN_DATE',
        'axys_accounts.axys_account_login_date' => 'AXYS_ACCOUNT_LOGIN_DATE',
        'FirstName' => 'AXYS_ACCOUNT_FIRST_NAME',
        'AxysAccount.FirstName' => 'AXYS_ACCOUNT_FIRST_NAME',
        'firstName' => 'AXYS_ACCOUNT_FIRST_NAME',
        'axysAccount.firstName' => 'AXYS_ACCOUNT_FIRST_NAME',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_FIRST_NAME' => 'AXYS_ACCOUNT_FIRST_NAME',
        'COL_AXYS_ACCOUNT_FIRST_NAME' => 'AXYS_ACCOUNT_FIRST_NAME',
        'axys_account_first_name' => 'AXYS_ACCOUNT_FIRST_NAME',
        'axys_accounts.axys_account_first_name' => 'AXYS_ACCOUNT_FIRST_NAME',
        'LastName' => 'AXYS_ACCOUNT_LAST_NAME',
        'AxysAccount.LastName' => 'AXYS_ACCOUNT_LAST_NAME',
        'lastName' => 'AXYS_ACCOUNT_LAST_NAME',
        'axysAccount.lastName' => 'AXYS_ACCOUNT_LAST_NAME',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_LAST_NAME' => 'AXYS_ACCOUNT_LAST_NAME',
        'COL_AXYS_ACCOUNT_LAST_NAME' => 'AXYS_ACCOUNT_LAST_NAME',
        'axys_account_last_name' => 'AXYS_ACCOUNT_LAST_NAME',
        'axys_accounts.axys_account_last_name' => 'AXYS_ACCOUNT_LAST_NAME',
        'Update' => 'AXYS_ACCOUNT_UPDATE',
        'AxysAccount.Update' => 'AXYS_ACCOUNT_UPDATE',
        'update' => 'AXYS_ACCOUNT_UPDATE',
        'axysAccount.update' => 'AXYS_ACCOUNT_UPDATE',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE' => 'AXYS_ACCOUNT_UPDATE',
        'COL_AXYS_ACCOUNT_UPDATE' => 'AXYS_ACCOUNT_UPDATE',
        'axys_account_update' => 'AXYS_ACCOUNT_UPDATE',
        'axys_accounts.axys_account_update' => 'AXYS_ACCOUNT_UPDATE',
        'CreatedAt' => 'AXYS_ACCOUNT_CREATED',
        'AxysAccount.CreatedAt' => 'AXYS_ACCOUNT_CREATED',
        'createdAt' => 'AXYS_ACCOUNT_CREATED',
        'axysAccount.createdAt' => 'AXYS_ACCOUNT_CREATED',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED' => 'AXYS_ACCOUNT_CREATED',
        'COL_AXYS_ACCOUNT_CREATED' => 'AXYS_ACCOUNT_CREATED',
        'axys_account_created' => 'AXYS_ACCOUNT_CREATED',
        'axys_accounts.axys_account_created' => 'AXYS_ACCOUNT_CREATED',
        'UpdatedAt' => 'AXYS_ACCOUNT_UPDATED',
        'AxysAccount.UpdatedAt' => 'AXYS_ACCOUNT_UPDATED',
        'updatedAt' => 'AXYS_ACCOUNT_UPDATED',
        'axysAccount.updatedAt' => 'AXYS_ACCOUNT_UPDATED',
        'AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED' => 'AXYS_ACCOUNT_UPDATED',
        'COL_AXYS_ACCOUNT_UPDATED' => 'AXYS_ACCOUNT_UPDATED',
        'axys_account_updated' => 'AXYS_ACCOUNT_UPDATED',
        'axys_accounts.axys_account_updated' => 'AXYS_ACCOUNT_UPDATED',
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
        $this->addPrimaryKey('axys_account_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('axys_account_email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('axys_account_password', 'Password', 'VARCHAR', false, 255, null);
        $this->addColumn('axys_account_key', 'Key', 'LONGVARCHAR', false, null, null);
        $this->addColumn('axys_account_email_key', 'EmailKey', 'VARCHAR', false, 32, null);
        $this->addColumn('axys_account_screen_name', 'Username', 'VARCHAR', false, 128, null);
        $this->addColumn('axys_account_slug', 'Slug', 'VARCHAR', false, 32, null);
        $this->addColumn('axys_account_signup_date', 'SignupDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('axys_account_login_date', 'LoginDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('axys_account_first_name', 'FirstName', 'LONGVARCHAR', false, null, null);
        $this->addColumn('axys_account_last_name', 'LastName', 'LONGVARCHAR', false, null, null);
        $this->addColumn('axys_account_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('axys_account_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('axys_account_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
    0 => ':axys_account_id',
    1 => ':axys_account_id',
  ),
), null, null, 'AxysConsents', false);
        $this->addRelation('Cart', '\\Model\\Cart', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':axys_account_id',
  ),
), null, null, 'Carts', false);
        $this->addRelation('Option', '\\Model\\Option', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':axys_account_id',
  ),
), null, null, 'Options', false);
        $this->addRelation('Right', '\\Model\\Right', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':axys_account_id',
  ),
), null, null, 'Rights', false);
        $this->addRelation('Session', '\\Model\\Session', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':axys_account_id',
  ),
), null, null, 'Sessions', false);
        $this->addRelation('Stock', '\\Model\\Stock', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':axys_account_id',
  ),
), null, null, 'Stocks', false);
        $this->addRelation('Wish', '\\Model\\Wish', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':axys_account_id',
  ),
), null, null, 'Wishes', false);
        $this->addRelation('Wishlist', '\\Model\\Wishlist', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_account_id',
    1 => ':axys_account_id',
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
            'timestampable' => ['create_column' => 'axys_account_created', 'update_column' => 'axys_account_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
            'validate' => ['rule1' => ['column' => 'axys_account_email', 'validator' => 'Email']],
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
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_PASSWORD);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_KEY);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL_KEY);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_SCREEN_NAME);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_SLUG);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_FIRST_NAME);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_LAST_NAME);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED);
            $criteria->addSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.axys_account_email');
            $criteria->addSelectColumn($alias . '.axys_account_password');
            $criteria->addSelectColumn($alias . '.axys_account_key');
            $criteria->addSelectColumn($alias . '.axys_account_email_key');
            $criteria->addSelectColumn($alias . '.axys_account_screen_name');
            $criteria->addSelectColumn($alias . '.axys_account_slug');
            $criteria->addSelectColumn($alias . '.axys_account_signup_date');
            $criteria->addSelectColumn($alias . '.axys_account_login_date');
            $criteria->addSelectColumn($alias . '.axys_account_first_name');
            $criteria->addSelectColumn($alias . '.axys_account_last_name');
            $criteria->addSelectColumn($alias . '.axys_account_update');
            $criteria->addSelectColumn($alias . '.axys_account_created');
            $criteria->addSelectColumn($alias . '.axys_account_updated');
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
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_PASSWORD);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_KEY);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL_KEY);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_SCREEN_NAME);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_SLUG);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_FIRST_NAME);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_LAST_NAME);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED);
            $criteria->removeSelectColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.axys_account_email');
            $criteria->removeSelectColumn($alias . '.axys_account_password');
            $criteria->removeSelectColumn($alias . '.axys_account_key');
            $criteria->removeSelectColumn($alias . '.axys_account_email_key');
            $criteria->removeSelectColumn($alias . '.axys_account_screen_name');
            $criteria->removeSelectColumn($alias . '.axys_account_slug');
            $criteria->removeSelectColumn($alias . '.axys_account_signup_date');
            $criteria->removeSelectColumn($alias . '.axys_account_login_date');
            $criteria->removeSelectColumn($alias . '.axys_account_first_name');
            $criteria->removeSelectColumn($alias . '.axys_account_last_name');
            $criteria->removeSelectColumn($alias . '.axys_account_update');
            $criteria->removeSelectColumn($alias . '.axys_account_created');
            $criteria->removeSelectColumn($alias . '.axys_account_updated');
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
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID, (array) $values, Criteria::IN);
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

        if ($criteria->containsKey(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID) && $criteria->keyContainsValue(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AxysAccountTableMap::COL_AXYS_ACCOUNT_ID.')');
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
